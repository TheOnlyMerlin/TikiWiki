<?php
// Initialization
require_once('tiki-setup.php');
include('lib/live_support/lsadminlib.php');
include('lib/live_support/lslib.php');


if($tiki_p_admin != 'y' && !$lsadminlib->user_is_operator($user)) {
    $smarty->assign('msg',tra("You dont have permission to use this feature"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
}



if(isset($_REQUEST['adduser'])) {
	$lsadminlib->add_operator($_REQUEST['user']);
}
if(isset($_REQUEST['removeuser'])) {
	$lsadminlib->remove_operator($_REQUEST['removeuser']);
}

// Get the list of operators
$online_operators = $lsadminlib->get_operators('online');
$offline_operators = $lsadminlib->get_operators('offline');
$smarty->assign_by_ref('online_operators',$online_operators);
$smarty->assign_by_ref('offline_operators',$offline_operators);

// Get the list of users
if(!isset($_REQUEST['find_users'])) $_REQUEST['find_users']='';
$users = $userlib->get_users(0,-1,'login_asc', $_REQUEST['find_users']);

$ok_users=Array();
for($i=0;$i<count($users['data']);$i++) {
	foreach($online_operators as $op) {
		if($op['user'] == $users['data'][$i]['user']) {
			unset($users[$i]);
		}
	}
	foreach($offline_operators as $op) {
		if(isset($users['data'][$i]) && $op['user'] == $users['data'][$i]['user']) {
			unset($users['data'][$i]);
		}
	}
	if(isset($users['data'][$i])) {
		$ok_users[]=$users['data'][$i];
	}
}


$smarty->assign_by_ref('users',$ok_users);

// Display the template
$smarty->assign('mid','tiki-live_support_admin.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>