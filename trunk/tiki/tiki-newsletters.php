<?php
// Initialization
require_once('tiki-setup.php');
include_once('lib/newsletters/nllib.php');
include_once('lib/webmail/htmlMimeMail.php');

if($feature_newsletters != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if(!$user && $tiki_p_subscribe_email_newsletters != 'y') {
  $smarty->assign('msg',tra("You must be logged in to subscribe to newsletters"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

$smarty->assign('subscribe','n');	

$foo = parse_url($_SERVER["REQUEST_URI"]);
$smarty->assign('url_subscribe',httpPrefix().$foo["path"]);


if(isset($_REQUEST["nlId"])) {
$smarty->assign('individual','n');
if($userlib->object_has_one_permission($_REQUEST["nlId"],'newsletter')) {
  $smarty->assign('individual','y');
  if($tiki_p_admin != 'y') {
    $perms = $userlib->get_permissions(0,-1,'permName_desc','','newsletters');
    foreach($perms["data"] as $perm) {
      $permName=$perm["permName"];
      if($userlib->object_has_permission($user,$_REQUEST["nlId"],'newsletter',$permName)) {
        $$permName = 'y';
        $smarty->assign("$permName",'y');
      } else {
        $$permName = 'n';
        $smarty->assign("$permName",'n');
      }
    }
  }
}
}

if($user) {
  $user_email = $tikilib->get_user_email($user);
} else {
  $user_email = '';	
}
$smarty->assign('email',$user_email);

$smarty->assign('confirm','n');
if(isset($_REQUEST["confirm_subscription"])) {
  $conf = $nllib->confirm_subscription($_REQUEST["confirm_subscription"]);
  if($conf) {
    $smarty->assign('confirm','n');
    $smarty->assign('nl_info',$conf);	
  } 
}
if(isset($_REQUEST["unsubscribe"])) {
  $conf = $nllib->unsubscribe($_REQUEST["unsubscribe"]);
  if($conf) {
    $smarty->assign('unsub','n');
    $smarty->assign('nl_info',$conf);	
  } 	
}

if($tiki_p_subscribe_newsletters == 'y') {
  if(isset($_REQUEST["subscribe"])) {
    if($tiki_p_subscribe_email_newsletters != 'y') {
      $_REQUEST["email"] = $userlib->get_user_email($user);	
    }	
    // Now subscribe the email address to the newsletter
    $nllib->newsletter_subscribe($_REQUEST["nlId"],$_REQUEST["email"]);
  }
}

if(isset($_REQUEST["info"])) {
  $nl_info = $nllib->get_newsletter($_REQUEST["nlId"]);
  $smarty->assign('nl_info',$nl_info);
  $smarty->assign('subscribe','y');	
}
/* List newsletters */

if(!isset($_REQUEST["sort_mode"])) {
  $sort_mode = 'created_desc'; 
} else {
  $sort_mode = $_REQUEST["sort_mode"];
} 

if(!isset($_REQUEST["offset"])) {
  $offset = 0;
} else {
  $offset = $_REQUEST["offset"]; 
}
$smarty->assign_by_ref('offset',$offset);

if(isset($_REQUEST["find"])) {
  $find = $_REQUEST["find"];  
} else {
  $find = ''; 
}
$smarty->assign('find',$find);

$smarty->assign_by_ref('sort_mode',$sort_mode);
$channels = $nllib->list_newsletters($offset,$maxRecords,$sort_mode,$find);

$cant_pages = ceil($channels["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages',$cant_pages);
$smarty->assign('actual_page',1+($offset/$maxRecords));
if($channels["cant"] > ($offset+$maxRecords)) {
  $smarty->assign('next_offset',$offset + $maxRecords);
} else {
  $smarty->assign('next_offset',-1); 
}
// If offset is > 0 then prev_offset
if($offset>0) {
  $smarty->assign('prev_offset',$offset - $maxRecords);  
} else {
  $smarty->assign('prev_offset',-1); 
}

$smarty->assign_by_ref('channels',$channels["data"]);


// Display the template
$smarty->assign('mid','tiki-newsletters.tpl');
$smarty->display("styles/$style_base/tiki.tpl");

?>