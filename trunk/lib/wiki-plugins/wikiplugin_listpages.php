<?php
// $Header: /cvsroot/tikiwiki/_mods/wiki-plugins/listpages/wiki-plugins/wikiplugin_listpages.php,v 1.3 2007-04-19 16:22:47 sylvieg Exp $
function wikiplugin_listpages_help() {
  $help = tra("List wiki pages.");
  $help .= "<br />";
  $help .= "~np~{LISTPAGES(initial=txt, showNameOnly=y|n, categId=id, structHead=y|n, showPageAlias=y|n, offset=num, max=num, find=txt, exact_match=y|n, only_orphan_pages=y|n, for_list_pages=y|n)}{LISTPAGES}~/np~";

	return $help;
}

function wikiplugin_listpages_info() {
	return array(
		'name' => tra('List Pages'),
		'description' => tra('List wiki pages.'),
		'prefs' => array(),
		'params' => array(
			'offset' => array(
				'required' => false,
				'name' => tra('Result Offset'),
				'description' => tra('Result number at which the listing should start.'),
			),
			'max' => array(
				'required' => false,
				'name' => tra('Result Count'),
				'description' => tra('Amount of results displayed in the list.'),
			),
			'initial' => array(
				'required' => false,
				'name' => tra('Initial'),
				'description' => tra('txt'),
			),
			'showNameOnly' => array(
				'required' => false,
				'name' => tra('Show Name Only'),
				'description' => tra('y|n'),
			),
			'categId' => array(
				'required' => false,
				'name' => tra('Category'),
				'description' => tra('Category ID'),
			),
			'structHead' => array(
				'required' => false,
				'name' => tra('Structure Head'),
				'description' => tra('y|n'),
			),
			'showPageAlias' => array(
				'required' => false,
				'name' => tra('Show Page Alias'),
				'description' => tra('y|n'),
			),
			'find' => array(
				'required' => false,
				'name' => tra('Find'),
				'description' => tra('txt'),
			),
			'exact_match' => array(
				'required' => false,
				'name' => tra('Exact Match'),
				'description' => tra('y|n, related to Find.'),
			),
			'only_orphan_pages' => array(
				'required' => false,
				'name' => tra('Only Orphan Pages'),
				'description' => tra('y|n'),
			),
			'for_list_pages' => array(
				'required' => false,
				'name' => tra('For List Pages'),
				'description' => tra('y|n'),
			),
		),
	);
}

function wikiplugin_listpages($data, $params) {
	global $prefs, $tiki_p_view, $tikilib, $smarty;

  if ( isset($prefs) ) {
    // Handle 1.10.x prefs
    $feature_listPages = $prefs['feature_listPages'];
    $feature_wiki = $prefs['feature_wiki'];
  } else {
    // Handle 1.9.x prefs
    global $feature_listPages, $feature_wiki;
  }

	if ( $feature_wiki != 'y' || $feature_listPages != 'y' || $tiki_p_view != 'y') {
    // the feature is disabled or the user can't read wiki pages
		return '';
	} 
	extract($params,EXTR_SKIP);
	$filter == array();
	if (!isset($initial)) {
		if (isset($_REQUEST['initial'])) {
			$initial = $_REQUEST['initial'];
		} else {
			$initial = '';
		}
	}
	if (!empty($categId)) {
		$filter['categId'] = $categId;
	}
	if (!empty($structHead) && $structHead == 'y') {
		$filter['structHead'] = $structHead;
	}
	if (!isset($offset)) {
		$offset = 0;
	}
	if (!isset($max)) {
		$max = -1;
	}
  if (!isset($sort)) {
    $sort = 'pageName_desc';
  }
  if (!isset($find)) {
    $find = '';
  }
  $exact_match = ( isset($exact_match) && $exact_match == 'y' );
  $only_name = ( isset($showNameOnly) && $showNameOnly == 'y' );
  $only_orphan_pages = ( isset($only_orphan_pages) && $only_orphan_pages == 'y' );
  $for_list_pages = ( isset($for_list_pages) && $for_list_pages == 'y' );
  $only_cant = false;

	$listpages = $tikilib->list_pages($offset, $max, $sort, $find, $initial, $exact_match, $only_name, $for_list_pages, $only_orphan_pages, $filter, $only_cant);

	$smarty->assign_by_ref('listpages', $listpages['data']);
	if (!empty($showPageAlias) && $showPageAlias == 'y')
		$smarty->assign_by_ref('showPageAlias', $showPageAlias);
	if (isset($showNameOnly) && $showNameOnly == 'y') {
		$ret = $smarty->fetch('wiki-plugins/wikiplugin_listpagenames.tpl');
	} else {
		$ret = $smarty->fetch('tiki-listpages_content.tpl');
	}

	return '~np~'.$ret.'~/np~';
}
?>
