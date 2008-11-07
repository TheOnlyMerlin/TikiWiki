<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

/* params
 * - link_on_section
 * - css = use suckerfish menu
 * - type = vert|horiz
 * - id = menu ID (mandatory)
 * - tr = y|n , n means no option translation (default y) */
function smarty_function_menu($params, &$smarty)
{
    global $tikilib, $user, $headerlib, $prefs;
    global $menulib; include_once('lib/menubuilder/menulib.php');
	extract($params);

	if (empty($link_on_section) || $link_on_section == 'y') {
		$smarty->assign('link_on_section', 'y');
	} else {
		 $smarty->assign('link_on_section', 'n');
	}
	if (empty($translate)) {
		$translate = 'y';
	}
	$smarty->assign_by_ref('translate', $translate);
	if (isset($css) and $prefs['feature_cssmenus'] == 'y') {
		static $idCssmenu = 0;
		if (isset($type) && ($type == 'vert' || $type == 'horiz')) {
			$css = "cssmenu_$type.css";
		} else {
			$css = 'cssmenu.css';
			$type = '';
		}
		//$headerlib->add_cssfile("css/$css", 50); too late to do that(must be done in header)
		$headerlib->add_jsfile('lib/menubuilder/menu.js');
		$tpl = 'tiki-user_cssmenu.tpl';
		$smarty->assign('menu_type', $type);
		if(! isset($css_id)){//adding $css_id parameter to customize menu id and prevent automatic id renaming when a menu is removed
			$smarty->assign('idCssmenu', $idCssmenu++);	
		} else {
			$smarty->assign('idCssmenu', $css_id);
		}
	} else {
		$tpl = 'tiki-user_menu.tpl';
	}
	$menu_info = $tikilib->get_menu($id);
	$channels = $tikilib->list_menu_options($id,0,-1,'position_asc','','',isset($prefs['mylevel'])?$prefs['mylevel']:0);
	$channels = $menulib->setSelected($channels, isset($sectionLevel)?$sectionLevel:'', isset($toLevel)?$toLevel: '');
	$channels = $tikilib->sort_menu_options($channels);
	$smarty->assign('menu_channels',$channels['data']);
	$smarty->assign('menu_info',$menu_info);
    $smarty->display($tpl);
}

function compare_menu_options($a, $b) { return strcmp(tra($a['name']), tra($b['name'])); }

?>
