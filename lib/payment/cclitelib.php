<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class CCLiteLib extends TikiDb_Bridge
{
	// member vars (defaults from prefs)
	private $gateway;
	private $key_hash;
	private $registry;
	private $currency;
	private $merchant_user;
	
	function __construct() {
		global $prefs;
		
		$this->gateway = rtrim($prefs['payment_cclite_gateway'], '/');
		$this->registry = $prefs['payment_cclite_registry'];
		$this->currency = $prefs['payment_currency'];
		$this->merchant_user = $prefs['payment_cclite_merchant_user'];
				
		if (($prefs['payment_cclite_mode'] == 'test' && $_SERVER['SERVER_ADDR'] != '127.0.0.1' && $_SERVER['SERVER_ADDR'] != '::1') || empty($prefs['payment_cclite_test_ip'])) {
			$ip = $_SERVER['SERVER_ADDR'];
		} else {
			// debug SERVER_ADDR for local testing on NAT'ed server
			$ip = $prefs['payment_cclite_test_ip'];
		}
		$api_hash = hash( $prefs['payment_cclite_hashing_algorithm'] , ( $prefs['payment_cclite_merchant_key'] . $ip), 'true');
		$this->key_hash = CCLiteLib::urlsafe_b64encode($api_hash);
	}
	
	public function get_invoice( $ipn_data ) {
		return isset( $ipn_data['invoice'] ) ? $ipn_data['invoice'] : 0;
	}

	public function get_amount( $ipn_data ) {
		return $ipn_data['mc_gross'];
	}

	public function is_valid( $ipn_data, $payment_info ) {
		global $prefs;

		// Make sure this is not a fake, must be verified even if discarded, otherwise will be resent
		if( ! $this->confirmed_by_cclite( $ipn_data ) ) {
			return false;
		}

		if( ! is_array( $payment_info ) ) {
			return false;
		}

		// Skip other events
		if( $ipn_data['payment_status'] != 'Completed' ) {
			return false;
		}

		// Make sure it is addressed to the right account
		if( $ipn_data['receiver_email'] != $prefs['payment_cclite_business'] ) {
			return false;
		}

		// Require same currency
		if( $ipn_data['mc_currency'] != $payment_info['currency'] ) {
			return false;
		}

		// Skip duplicate translactions
		foreach( $payment_info['payments'] as $payment ) {
			if( $payment['type'] == 'cclite' ) {
				if( $payment['details']['txn_id'] == $ipn_data['txn_id'] ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @param int $invoice
	 * @param decimal $amount
	 * @param string $currency
	 * @param string $registry
	 * 
	 * @return string result from cclite
	 */
	public function pay_invoice($invoice, $amount, $currency = '', $registry = '', $destination_user = '') {
		global $user, $prefs, $paymentlib;
		require_once 'lib/payment/paymentlib.php';
		
		$res = $this->cclite_send_request('pay', $amount, $destination_user, $registry, $currency);
		
		if (strpos($res, 'Transaction Accepted') !== false) {	// e.g. "Transaction Accepted<br/>Ref:&nbsp;hpnUKZZ4BMG4IXDHVmfxXdubtsk"
			$paymentlib->enter_payment( $invoice, $amount, 'cclite', array($res) );
		}

		return $res;
	}

	private function confirmed_by_cclite( $ipn_data ) {
		global $prefs;

		return true;	// for now TODO

		require_once 'lib/core/lib/Zend/Http/Client.php';
		$client = new Zend_Http_Client( $prefs['payment_cclite_environment'] );

		$base = array( 'cmd' => '_notify-validate' );

		$client->setParameterPost( array_merge( $base, $ipn_data ) );
		$response = $client->request( 'POST' );

		$body = $response->getBody();

		return 'VERIFIED' === $body;
	}

	/**
	 * Adapted from cclite 0.7 drupal gateway (example)
	 * 
	 * @command		recent|summary|pay|adduser|modifyuser|debit
	 * @amount		amount
	 * @other_user	destination for payment etc
	 * @registry	cclite registry
	 * @currency	currency (same as currency "name" in cclite (not "code" yet)
	 * @other		unused
	 * 
	 * @return		result from cclite server (html hopefully)
	 */

	private function cclite_send_request( $command, $amount = 0, $other_user = '', $registry = '', $currency = '', $other = '') {
		global $user, $prefs;
		
		if (empty($other_user)) { $other_user = $this->merchant_user; }
		if (empty($registry)) { $registry = $this->registry; }
		if (empty($currency)) { $currency = $this->currency; }
		
		$result = '';

		// construct the payment url from configuration information
		$cclite_base_url = $this->gateway;
		$REST_url = '';
		$ch = curl_init();
		if ($command != 'adduser') {
			$logon_result = $this->cclite_remote_logon($username, $registry);
			if (strlen($logon_result[1])) {
				curl_setopt($ch, CURLOPT_COOKIE, $logon_result[1]);
			} else {
				return tr('Logon to cclite server %0 failed for %1', $cclite_base_url, $user);
			}
		}
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		// this switch statement needs to map to the Rewrites in the cclite .htaccess file, so if you're
		// doing something custom-made, you need to think about:
		// -here-, .htaccess and various bits of login in the cclite motor
		switch ($command) {
			case 'recent':
				$REST_url = "$cclite_base_url/recent/transactions";
				break;
			case 'summary':
				$REST_url = "$cclite_base_url/summary";
				break;
			case 'pay':
				//if (! user_access('make payments')) {
				//    return "$username not authorised to make payments" ;
				//}
				// pay/test1/dalston/23/hack(s) using the merchant key
				$REST_url = "$cclite_base_url/pay/$other_user/$registry/$amount/$currency";
				break;
		case 'adduser':
			// direct/adduser/dalston/test1/email using the merchant key, without using individual logon
			$REST_url = "$cclite_base_url/direct/adduser/$other_user/$registry/$amount";
			curl_setopt($ch, CURLOPT_COOKIE, 'merchant_key_hash=' . $this->key_hash);
			break;
		case 'modifyuser':
			// direct/modifyuser/dalston/test1/email using the merchant key, without using individual logon
			// non-working at present...
			$REST_url = "$cclite_base_url/direct/modifyuser/$other_user/$registry/$amount";
			curl_setopt($ch, CURLOPT_COOKIE, 'merchant_key_hash=' . $this->key_hash);
			break;
		case 'debit':
			// non-working at present...
			$REST_url = "$cclite_base_url/debit/$other_user/$registry/$amount/$currency";
			break;
		default:
			return "No cclite function selected use <a title=\"cclite passthrough help\" href=\"/$cclite_base_url/help\">help</a>" ;
		}
		curl_setopt($ch, CURLOPT_URL, $REST_url);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * Modified from cclite 0.7 gateway various examples
	 *
	 * @return multitype:mixed string |multitype:string
	 */
	private function cclite_remote_logon($username = '', $registry = '') {
		global $user, $prefs, $userlib;
		
		if (empty($username)) { $username = $user; }
		
		// not worth trying if no user name
		if (!empty($username)) {
			
			if (empty($registry)) { $registry = $this->registry; }
			$cclite_base_url = $this->gateway;
			
			// payment url from configuration information
			$REST_url = "$cclite_base_url/logon/$username/$registry";	// /$api_hash
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_COOKIE, 'merchant_key_hash=' . $this->key_hash);
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_URL, $REST_url);
			$logon = curl_exec($ch);
			if ($logon) {
				preg_match("/login failed for $username at $registry/", $logon, $results);
				if ($results) {	// no user there?
					$email = $userlib->get_user_email($username);
					if ($email) {	// required
						$results = $this->cclite_send_request('adduser', $email, $username, $registry);
						if ($results) {
							$logon = curl_exec($ch);
						}
					}
				}
			}
			if ($logon) {
				curl_close($ch);
				$ch = null;
				preg_match_all('|Set-Cookie: (.*);|U', $logon, $results);
				$cookies = implode("; ", $results[1]);
				return array($logon, $cookies);
			}
		}
		if ($ch) {
			curl_close($ch);
		}
		// fall through failed
		return array('failed', array());
	}

	// used to transport merchant key hash - probably duplicates of tiki fns REFACTOR?
	static function urlsafe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}
	static function urlsafe_b64decode($string) {
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data.= substr('====', $mod4);
		}
		return base64_decode($data);
	}

}

global $cclitelib;
$cclitelib = new CCLiteLib;

