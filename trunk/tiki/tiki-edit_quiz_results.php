<?php
// Initialization
require_once('tiki-setup.php');

if($feature_quizzes != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if(!isset($_REQUEST["quizId"])) {
  $smarty->assign('msg',tra("No quiz indicated"));
  $smarty->display("styles/$style_base/error.tpl");
  die;
}

$smarty->assign('individual','n');
if($userlib->object_has_one_permission($_REQUEST["quizId"],'quiz')) {
  $smarty->assign('individual','y');
  if($tiki_p_admin != 'y') {
    $perms = $userlib->get_permissions(0,-1,'permName_desc','','quizzes');
    foreach($perms["data"] as $perm) {
      $permName=$perm["permName"];
      if($userlib->object_has_permission($user,$_REQUEST["quizId"],'quiz',$permName)) {
        $$permName = 'y';
        $smarty->assign("$permName",'y');
      } else {
        $$permName = 'n';
        $smarty->assign("$permName",'n');
      }
    }
  }
}


if($tiki_p_admin_quizzes != 'y') {
    $smarty->assign('msg',tra("You dont have permission to use this feature"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
}


$smarty->assign('quizId',$_REQUEST["quizId"]);
$quiz_info=$tikilib->get_quiz_result($_REQUEST["quizId"]);
$smarty->assign('quiz_info',$quiz_info);

if(!isset($_REQUEST["resultId"])) {
  $_REQUEST["resultId"] = 0;
}
$smarty->assign('resultId',$_REQUEST["resultId"]);

if($_REQUEST["resultId"]) {
  $info = $tikilib->get_quiz_result($_REQUEST["resultId"]);
} else {
  $info = Array();
  $info["fromPoints"]=0;
  $info["toPoints"]=0;
  $info["answer"]='';
}
$smarty->assign('answer',$info["answer"]);
$smarty->assign('fromPoints',$info["fromPoints"]);
$smarty->assign('toPoints',$info["toPoints"]);

if(isset($_REQUEST["remove"])) {
  $tikilib->remove_quiz_result($_REQUEST["remove"]);
}

if(isset($_REQUEST["save"])) {
  $tikilib->replace_quiz_result($_REQUEST["resultId"], $_REQUEST["quizId"], $_REQUEST["fromPoints"],$_REQUEST["toPoints"],$_REQUEST["answer"]);
  $smarty->assign('answer','');
  $smarty->assign('resultId',0);
}


if(!isset($_REQUEST["sort_mode"])) {
  $sort_mode = 'fromPoints_asc'; 
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
$channels = $tikilib->list_quiz_results($_REQUEST["quizId"],$offset,$maxRecords,$sort_mode,$find);


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

// Fill array with possible number of questions per page
$positions=Array();
for($i=1;$i<100;$i++) $positions[]=$i;
$smarty->assign('positions',$positions);



// Display the template
$smarty->assign('mid','tiki-edit_quiz_results.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>