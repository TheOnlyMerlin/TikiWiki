<?php
// Initialization
require_once('tiki-setup.php');
include_once('lib/structures/structlib.php');
include_once('lib/wiki/wikilib.php');

if($feature_wiki != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

$page = $_REQUEST['page'];

if($structlib->page_is_in_structure($page)) {   
	$smarty->assign('structure','y');   
	$prev=$structlib->get_prev_page($page);   
	$next=$structlib->get_next_page($page);   
	$struct=$structlib->get_structure($page);   
	$smarty->assign('struct_next',$next);   
	$smarty->assign('struct_prev',$prev);   
	$smarty->assign('struct_struct',$struct);   
}  else {
  $smarty->assign('msg',tra("Page must be defined inside a structure to use this feature"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}


//print($GLOBALS["HTTP_REFERER"]);


if(!isset($_SESSION["thedate"])) {
  $thedate = date("U");
} else {
  $thedate = $_SESSION["thedate"];
}

$smarty->assign_by_ref('page',$_REQUEST["page"]); 

require_once('tiki-pagesetup.php');

// Check if we have to perform an action for this page
// for example lock/unlock
if($tiki_p_admin_wiki == 'y') {
if(isset($_REQUEST["action"])) {
  if($_REQUEST["action"]=='lock') {
    $tikilib->lock_page($page);
  } elseif ($_REQUEST["action"]=='unlock') {
    $tikilib->unlock_page($page);
  }  
}
}


// If the page doesn't exist then display an error
if(!$tikilib->page_exists($page)) {
  $smarty->assign('msg',tra("Page cannot be found"));
  $smarty->display("styles/$style_base/error.tpl");
  die;
}


// Now check permissions to access this page
if($tiki_p_view != 'y') {
  $smarty->assign('msg',tra("Permission denied you cannot view this page"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

// BreadCrumbNavigation here
// Get the number of pages from the default or userPreferences
// Remember to reverse the array when posting the array
$anonpref = $tikilib->get_preference('userbreadCrumb',4);
if($user) {
  $userbreadCrumb = $tikilib->get_user_preference($user,'userbreadCrumb',$anonpref);
} else {
  $userbreadCrumb = $anonpref;
}
if(!isset($_SESSION["breadCrumb"])) {
  $_SESSION["breadCrumb"]=Array();
}
if(!in_array($page,$_SESSION["breadCrumb"])) {
  if(count($_SESSION["breadCrumb"])>$userbreadCrumb) {
    array_shift($_SESSION["breadCrumb"]);
  } 
  array_push($_SESSION["breadCrumb"],$page);
} else {
  // If the page is in the array move to the last position
  $pos = array_search($page, $_SESSION["breadCrumb"]);
  unset($_SESSION["breadCrumb"][$pos]);
  array_push($_SESSION["breadCrumb"],$page);
}
//print_r($_SESSION["breadCrumb"]);


// Now increment page hits since we are visiting this page
if($count_admin_pvs == 'y' || $user!='admin') {
  $tikilib->add_hit($page);
}

// Get page data
$info = $tikilib->get_page_info($page);

// Verify lock status
if($info["flag"] == 'L') {
  $smarty->assign('lock',true);  
} else {
  $smarty->assign('lock',false);
}

// If not locked and last version is user version then can undo
$smarty->assign('canundo','n');	
if($info["flag"]!='L' && ( ($tiki_p_edit == 'y' && $info["user"]==$user)||($tiki_p_remove=='y') )) {
   $smarty->assign('canundo','y');	
}
if($tiki_p_admin_wiki == 'y') {
  $smarty->assign('canundo','y');		
}


$prev=$structlib->get_prev_page($page);   
$next=$structlib->get_next_page($page);   
$struct=$structlib->get_structure($page);   
$smarty->assign('struct_next',$next);   
$smarty->assign('struct_prev',$prev);   
$smarty->assign('struct_struct',$struct);   

$smarty->assign('structure','y');



//$smarty->assign_by_ref('lastModif',date("l d of F, Y  [H:i:s]",$info["lastModif"]));
$smarty->assign_by_ref('lastModif',$info["lastModif"]);
if(empty($info["user"])) {
  $info["user"]='anonymous';  
}
$smarty->assign_by_ref('lastUser',$info["user"]);


$section='wiki';
include_once('tiki-section_options.php');




$smarty->assign('wiki_extras','y');

// Display the Index Template
$smarty->assign('dblclickedit','y');
$smarty->assign('mid','tiki-show_page.tpl');
$smarty->assign('show_page_bar','y');
//$smarty->display("styles/$style_base/tiki-slideshow.tpl");
$smarty->display("tiki-slideshow.tpl");

?>