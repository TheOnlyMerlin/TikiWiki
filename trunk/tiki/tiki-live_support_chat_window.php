<?php
// Initialization
require_once('tiki-setup.php');
include('lib/live_support/lslib.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0

// This is a generic chat window used by users, operators and observers
// should receive the following parameters
// reqId: the requestId
// role: the role of the user can be (operator,user,other)

// We should receive the reqId here
$senderId = md5(uniqId('.'));
$smarty->assign('senderId',$senderId);

if($_REQUEST['role']=='operator') {
	$lslib->operator_accept($_REQUEST['reqId'],$user,$senderId);
	$lslib->set_operator_id($_REQUEST['reqId'],$senderId);
}	
if($_REQUEST['role']=='user') {
	$lslib->set_user_id($_REQUEST['reqId'],$senderId);
	$lslib->set_request_status($_REQUEST['reqId'],'op_accepted');
}
$smarty->assign('role',$_REQUEST['role']);

$smarty->assign('req_info',$lslib->get_request($_REQUEST['reqId']));

$smarty->assign('reqId',$_REQUEST['reqId']);

if(!isset($user)) {
	$smarty->assign('username','anonymous');
} else {
	$smarty->assign('username',$user);
}

// Display the template
$smarty->display("tiki-live_support_chat_window.tpl");
?>