<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

global $page;

$userid = $userlib->get_user_id($user);
$smarty->assign('userid',$userid);

$pageid = $tikilib->get_page_id_from_name($page);
$smarty->assign('pageid',$pageid);
$smarty->assign('page',$page);

?>
