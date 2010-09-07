<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

// Called by FCKEditor and defined in setup_fckeditor.tpl - FCKConfig.ajaxAutoSaveTargetUrl
// also now CKEDITOR.config.ajaxAutoSaveTargetUrl defined in block.textarea.php

// Used by ckeditor tikiwiki plugin and to reparse plugins in html mode
// possibly should be renamed?

$inputConfiguration = array( array(
	'staticKeyFilters' => array(
		'editor_id' => 'alpha',
//		'data' => 'alpha',
//		'script' => 'alpha',
	),
) );

require_once('tiki-setup.php');

if ($prefs['feature_ajax'] != 'y' || ($prefs['feature_ajax_autosave'] != 'y' && $prefs['wysiwyg_ckeditor'] != 'y')) {
	return;
}

require_once('lib/ajax/ajaxlib.php');

if (isset($_REQUEST['editor_id'])) {
	if (isset($_REQUEST['command']) && isset($_REQUEST['data']) && $_REQUEST['data'] != 'ajax error') {
		if ($_REQUEST['command'] == 'toWikiFormat') {
			global $editlib; include_once 'lib/wiki/editlib.php';
				
			$res = $editlib->parseToWiki(urldecode($_REQUEST['data']));
			
			header( 'Content-Type:text/xml; charset=UTF-8' ) ;
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<adapter command="toWikiFormat">';
			echo '<data><![CDATA[' .  $res . ']]></data>';
			echo '</adapter>';
		} else if ($_REQUEST['command'] == 'toHtmlFormat') {
			global $editlib; include_once 'lib/wiki/editlib.php';
				
			$res = $editlib->parseToWysiwyg(urldecode($_REQUEST['data']));
				
			header( 'Content-Type:text/xml; charset=UTF-8' ) ;
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<adapter command="toWikiFormat">';
			echo '<data><![CDATA[' .  $res . ']]></data>';
			echo '</adapter>';
		}
	} else if (isset($_REQUEST['data']) && $_REQUEST['data'] != 'ajax error') {	// autosave

		auto_save($_REQUEST['editor_id'],$_REQUEST['data'],$_REQUEST['script']);
		header( 'Content-Type:text/xml; charset=UTF-8' ) ;
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<adapter command="draft">';
		echo '<result message="success" />';
		echo '</adapter>';

	} else if (isset($_REQUEST['autoSaveId'])) {
		// do better some security here
		if (!empty($user)) {
			$headerlib->add_js('
function get_new_preview() {
$("body").css("opacity", 0.6);
location.replace("' . $tikiroot . 'tiki-auto_save.php?editor_id=' . $_REQUEST['editor_id'] . '&autoSaveId=' . $_REQUEST['autoSaveId'] . '");
}
$(window).load(function(){
	if (typeof opener != "undefined") {
		opener.ajaxPreviewWindow = this;
	}
}).unload(function(){
	if (typeof opener.ajaxPreviewWindow != "undefined") {
		opener.ajaxPreviewWindow = null;
	}
});
');
			$smarty->assign('headtitle', tra('Preview'));
			$data = '<div id="c1c2"><div id="wrapper"><div id="col1"><div id="tiki-center" class="wikitext">';
			if (has_autosave($_REQUEST['editor_id'], $_REQUEST['autoSaveId'])) {
				$data .= $tikilib->parse_data_raw(get_autosave($_REQUEST['editor_id'], $_REQUEST['autoSaveId']));
			} else {
				$arr = explode(':', $_REQUEST['autoSaveId']);
				if (count($arr) > 0 && $arr[0] == 'wiki_page') {
					global $wikilib; include_once('lib/wiki/wikilib.php');
					$canBeRefreshed = false;
					$data .= $wikilib->get_parse($arr[1], $canBeRefreshed);
				}
			}
			$data .= '</div></div></div></div>';
			$smarty->assign_by_ref( 'mid_data', $data);
			$smarty->assign( 'mid', '');
			$smarty->display("tiki_full.tpl");
		}
	}
}
