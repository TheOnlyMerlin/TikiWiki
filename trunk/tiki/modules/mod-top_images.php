<?php
include_once("lib/imagegals/imagegallib.php");
$ranking = $imagegallib->list_images(0, $module_rows, 'hits_desc','');
$smarty->assign('modTopImages',$ranking["data"]);
?>
