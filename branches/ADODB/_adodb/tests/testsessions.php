<?php

/* 
V4.05 13 Dec 2003  (c) 2000-2003 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
  Set tabs to 4 for best viewing.
	
  Latest version is available at http://php.weblogs.com/
*/

function NotifyExpire($ref,$key)
{
	print "<p><b>Notify Expiring=$ref, sessionkey=$key</b></p>";
}

//-------------------------------------------------------------------
	
	$ADODB_SESSION_DRIVER='oci8';
	$ADODB_SESSION_CONNECT='';
	$ADODB_SESSION_USER ='scott';
	$ADODB_SESSION_PWD ='natsoft';
	$ADODB_SESSION_DB ='';

	$USER = 'JLIM'.rand();
	$ADODB_SESSION_EXPIRE_NOTIFY = array('USER','NotifyExpire');
	
	GLOBAL $HTTP_SESSION_VARS;
	ob_start();
	error_reporting(E_ALL);
	
	$ADODB_SESS_DEBUG = true;
	//include('../adodb.inc.php');
	include('../session/adodb-cryptsession.php');
	session_start();
	
	print "<h3>PHP ".PHP_VERSION."</h3>";
	
	$HTTP_SESSION_VARS['MONKEY'] = array('1','abc',44.41);
	if (!isset($HTTP_GET_VARS['nochange'])) @$HTTP_SESSION_VARS['AVAR'] += 1;
	
	print "<p><b>\$HTTP_SESSION_VARS['AVAR']={$HTTP_SESSION_VARS['AVAR']}</b></p>";
	
	if (rand() % 10 == 0) {
	
		print "<p>GC</p>";
		adodb_sess_gc(10);
		
		print "<p>Random session destroy</p>";
		session_destroy();
	}
	print "<hr>";
	print_r($HTTP_COOKIE_VARS);
?>