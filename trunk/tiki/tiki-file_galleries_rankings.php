<?php
// Initialization
require_once('tiki-setup.php');
include_once('lib/rankings/ranklib.php');

if($feature_file_galleries != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if($feature_file_galleries_rankings != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if($tiki_p_view_file_gallery != 'y') {
  $smarty->assign('msg',tra("Permission denied you cannot view this section"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

$allrankings = Array(
  Array( 'name'=> 'Top visited file galleries', 'value'=> tra('filegal_ranking_top_galleries')),
  Array( 'name'=> 'Most downloaded files', 'value'=>tra('filegal_ranking_top_files')),
  Array( 'name'=> 'Last files', 'value'=>tra('filegal_ranking_last_files')),
);
$smarty->assign('allrankings',$allrankings);

if(!isset($_REQUEST["which"])) {
  $which = 'filegal_ranking_top_files';
} else {
  $which = $_REQUEST["which"];
}
$smarty->assign('which',$which);


// Get the page from the request var or default it to HomePage
if(!isset($_REQUEST["limit"])) {
  $limit = 10;
} else {
  $limit = $_REQUEST["limit"];
}

$smarty->assign_by_ref('limit',$limit);

// Rankings:
// Top Pages
// Last pages
// Top Authors
$rankings=Array();

$rk = $ranklib->$which($limit);
$rank["data"] = $rk["data"];
$rank["title"] = $rk["title"];
$rank["y"]=$rk["y"];
$rankings[] = $rank;



$smarty->assign_by_ref('rankings',$rankings);
$smarty->assign('rpage','tiki-file_galleries_rankings.php');
// Display the template
$smarty->assign('mid','tiki-ranking.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>
