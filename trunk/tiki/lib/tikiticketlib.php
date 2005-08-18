<?php
/* $Header: /cvsroot/tikiwiki/tiki/lib/tikiticketlib.php,v 1.16 2005-08-18 16:23:05 mose Exp $

Tikiwiki CSRF protection.
also called Sea-Surfing

please report to security@tikiwiki.org 
if you find a better way to handle sea surfing nastiness
*/

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

// obsolete: will be removed soon
function ask_ticket($area) {
	$_SESSION['antisurf'] =  $area; 
	return true;
}

// obsolete: will be removed soon
function check_ticket($area) { 
	if (!isset($_SESSION['antisurf'])) $_SESSION['antisurf'] = '';
	if ($_SESSION['antisurf'] != $area) { 
		global $smarty, $feature_ticketlib;
		$_SESSION['antisurf'] =  $area; 
		if ($feature_ticketlib == 'y') {
			$smarty->assign('post',$_POST);
			$smarty->assign('query',$_SERVER["QUERY_STRING"]);
			$smarty->assign('self',$_SERVER["PHP_SELF"]);
			$smarty->assign('msg',tra("Sea Surfing (CSRF) detected. Operation blocked."));
			$smarty->display('error_ticket.tpl');
			die;
		}
	}
	return true;
}

// new valid function for ticketing :

function key_get($area, $confirmation_text = '', $confirmaction='') {
//confirmaction actin must be set if the param are not transfer via the URI
	global $tikilib,$smarty,$feature_ticketlib2,$user;
	if ($feature_ticketlib2 == 'y') {
		if ($user) {
			$whose = $user;
		} else { 
  		$whose = ' '. md5($_SERVER['REMOTE_ADDR'].$_SERVER['USER_AGENT']);
		}
		$ticket = md5(uniqid(rand()));
		$tikilib->set_user_preference($whose,'ticket',$ticket);
		$smarty->assign('ticket',$ticket);
		$_SESSION["ticket_$area"] = time();
		if (empty($confirmation_text)) {
			$confirmation_text = tra('Click here to confirm your action');
		}
		if (empty($confirmationaction)) {
			$confirmationaction = $_SERVER['REQUEST_URI'];
		}
		$smarty->assign('confirmation_text', $confirmation_text);
		$smarty->assign('confirmaction', $confirmaction);
		$smarty->display("confirm.tpl");
		die();
	}
}

function key_check($area) {
	global $tikilib,$smarty,$feature_ticketlib2,$user;
	if ($feature_ticketlib2 != 'y') {
		return true;
	} else {
		if (isset($_SESSION["ticket_$area"])
			and $_SESSION["ticket_$area"] < date('U')
			and $_SESSION["ticket_$area"] > (date('U')-(60*15))) {
			$smarty->load_filter('pre', 'ticket');
			if ($user) {
				$whose = $user;
			} else { 
				$whose = ' '. md5($_SERVER['REMOTE_ADDR'].$_SERVER['USER_AGENT']);
			}
			if (isset($_REQUEST) and is_array($_REQUEST)
				and (!isset($_REQUEST['ticket']) 
				or $_REQUEST['ticket'] != $tikilib->get_user_preference($whose,'ticket'))) {
				unset($_REQUEST);
			} else {
				return true;
			}
		}
		unset($_SESSION["ticket_$area"]);
		$smarty->assign('msg',tra('Sea Surfing (CSRF) detected. Operation blocked.'));
		$smarty->display("error.tpl");
		die();
	}
}

?>
