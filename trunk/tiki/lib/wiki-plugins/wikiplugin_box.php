<?php
/*
 * $Header: /cvsroot/tikiwiki/tiki/lib/wiki-plugins/wikiplugin_box.php,v 1.2 2003-06-29 17:38:39 zaufi Exp $
 *
 * Tiki-Wiki BOX plugin.
 * 
 * Syntax:
 * 
 *  {BOX([title=>Title],[bg=color],[width=>num[%]])}
 *   Text inside box
 *  {BOX}
 * 
 */

function wikiplugin_box_help() {
  return "Insert theme styled box on wiki page";
}

function wikiplugin_box($data,$params) {
  /* set default values for some args */
  $title="Message box";

  extract($params);
  $w = (isset($width)) ? " width=$width" : "";
  $back =  (isset($bg)) ? " style='background:$bg'" : "";
  $begin="<table$w><tr><td><div class=cbox$back><div class=cbox-title>$title</div><div class=cbox-data$back>";
  $end="</div></div></td></tr></table>";

  return $begin . $data . $end;
}

?>
