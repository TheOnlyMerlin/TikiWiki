<?php
/*
 * $Header: /cvsroot/tikiwiki/tiki/lib/wiki-plugins/wikiplugin_box.php,v 1.14 2005-01-22 22:55:56 mose Exp $
 *
 * Tiki-Wiki BOX plugin.
 * 
 * Syntax:
 * 
 *  {BOX([title=>Title],[bg=>color|#999fff],[width=>num[%]],[align=>left|right|center])}
 *   Content inside box
 *  {BOX}
 * 
 */

function wikiplugin_box_help() {
	return tra("Insert theme styled box on wiki page").":<br />~np~{BOX(title=>Title, bg=>color, width=>num[%], align=>left|right|center)}".tra("text")."{BOX}~/np~";
}

function wikiplugin_box($data, $params) {
	global $smarty;
	/* set default values for some args */
	$title = tra("Message box");
	
	// if (substr($data, 0, 2) == "\r\n") $data = substr($data, 2);
    
	extract ($params,EXTR_SKIP);
	$w    = (isset($width)) ? " width=\"$width\""  : "";
	$bg   = (isset($bg))    ? " background:$bg;" : "";
  $al   = (isset($align) && ($align == 'right' || $align == "center")) ? " align=\"$align\"" : "";
    
	$begin  = "<table$al$w><tr><td><div class='cbox'".(strlen($bg) > 0 ? " style='$bg'" : "").">";
	if (isset($title)) {
    $begin .= "<div class='cbox-title'>$title</div>";
	}
	$begin.= "<div class='cbox-data'".(strlen($bg) > 0 ? " style=\"$bg\"" : "").">";
	$end = "</div></div></td></tr></table>";
	// Prepend any newline char with br
	$data = preg_replace("/\\n/", "<br />\n", $data);
	// Insert "\n" at data begin if absent (so start-of-line-sensitive syntaxes will be parsed OK)
	if (substr($data, 0, 1) != "\n") 
    	$data = "\n".$data;
	
	$smarty->assign('plugin_box_data', $data);
	
	return $smarty->fetch('plugins/plugin-box.tpl');
}

?>
