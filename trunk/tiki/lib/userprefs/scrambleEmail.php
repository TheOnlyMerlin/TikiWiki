<?php
// 	$Header: /cvsroot/tikiwiki/tiki/lib/userprefs/scrambleEmail.php,v 1.1 2003-10-17 20:56:37 sylvieg Exp $
/**
*	scramble an email with a method
*	@param string email emil to be scrambled
* 	@param string method=unicode or y: each character is replaced with the unicode value
* 	@param string method=strtr: mr@tw.org -> mr AT tw DOT org
* 	@param string method=x: mr@tw.org -> mr@xxxxxx
*	@return string scrambled email
*/
function scrambleEmail($email, $method='unicode') {
	switch ($method) {
	case 'strtr':
		$trans = array(	"@" => tra("(AT)"),
						"." => tra("(DOT)")
		);
		return strtr($email, $trans);
	case 'x' :
		$encoded = $email;
		for ($i = strpos($email, "@") + 1; $i < strlen($email); $i++) {
			if ($encoded[$i]  != ".") $encoded[$i] = 'x';
		}
		return $encoded;
	case 'unicode':
	case 'y':// for previous compatibility
		$encoded = '';
		for ($i = 0; $i < strlen($email); $i++) {
			$encoded .= '&#' . ord($email[$i]). ';';
		}
		return $encoded;
	case 'n':
	default:
		return $email;
	}
}
?>