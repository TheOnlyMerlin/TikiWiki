<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
}

function smarty_function_gallery($params, &$smarty)
{
    global $tikilib;
    global $dbTiki;
    include_once('lib/imagegals/imagegallib.php');
    extract($params);
    // Param = id

    if (empty($id)) {
        $smarty->trigger_error("assign: missing 'id' parameter");
        return;
    }
    $img = $imagegallib->get_random_image($id);
    print('<center>');
    print('<table  border="0" cellpadding="0" cellspacing="0">');
    print('<tr>');
    print('<td align=center>');
    print('<a href="tiki-browse_image.php?galleryId='.$img['galleryId'].'&amp;imageId='.$img['imageId'].'"><img alt="thumbnail" class="athumb" src="show_image.php?id='.$img['imageId'].'&amp;thumb=1" /></a><br />');    
    print('<b>'.$img['name'].'</b><br>');
    if ($showgalleryname == 1) {
        print('<small>'. tra("From").' <a href="tiki-browse_gallery.php?galleryId='.$img['galleryId'].'">'.$img['gallery'].'</a></small>');
    } 
    print('</td></tr></table></center>');
}    
?>
<!--
<center>
<table  border="0" cellpadding="0" cellspacing="0">
<tr>
<td align=center>
<a href="tiki-browse_image.php?galleryId=<?php echo $img['galleryId']; ?>&amp;imageId=<?php echo $img['imageId']; ?>"><img alt="thumbnail" class="athumb" src="show_image.php?id=<?php echo $img['imageId']; ?>&amp;thumb=1" /></a><br />
<b><?php echo $img['name']; ?></b><br>
<?php if ($showgalleryname == 1) { ?><small>From <a href="tiki-browse_gallery.php?galleryId=<?php echo $img['galleryId']; ?>"><?php echo $img['gallery']; ?></a></small><?php } ?>
</td>
</tr>
</table>
</center>
-->
