<?php
// Initialization
require_once('tiki-setup.php');
include_once('lib/admin/adminlib.php');

if($tiki_p_admin != 'y') {
    $smarty->assign('msg',tra("You dont have permission to use this feature"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
}


if(!isset($_REQUEST["extwikiId"])) {
  $_REQUEST["extwikiId"] = 0;
}
$smarty->assign('extwikiId',$_REQUEST["extwikiId"]);

if($_REQUEST["extwikiId"]) {
  $info = $adminlib->get_extwiki($_REQUEST["extwikiId"]);
} else {
  $info = Array();
  $info["extwiki"]='';
  $info['name']='';
  
}
$smarty->assign('info',$info);


if(isset($_REQUEST["remove"])) {
  $adminlib->remove_extwiki($_REQUEST["remove"]);
}


if(isset($_REQUEST["save"])) {
  
  $adminlib->replace_extwiki($_REQUEST["extwikiId"], $_REQUEST["extwiki"],$_REQUEST['name']);
  $info = Array();
  $info["extwiki"]='';
  $info['name']='';
  $smarty->assign('info',$info);
  $smarty->assign('name','');
}

if(!isset($_REQUEST["sort_mode"])) {
  $sort_mode = 'extwikiId_desc'; 
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
$channels = $adminlib->list_extwiki($offset,$maxRecords,$sort_mode,$find);

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
$smarty->assign('mid','tiki-admin_external_wikis.tpl');
$smarty->display("styles/$style_base/tiki.tpl");


?>
 
