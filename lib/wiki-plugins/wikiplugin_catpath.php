<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$
/*
 * Tikiwiki CATPATH plugin.
 * 
 * Syntax:
 * 
 * {CATPATH(
 *          divider=>string	#string that separates the categories, defaults to '>'
 *          top=>yes|no		#to display the TOP category or not, defaults to 'no'
 *         )}
 * {CATPATH}
 * 
 */
function wikiplugin_catpath_help() {
	return tra("Insert the full category path for each category that this wiki page belongs to").":<br />~np~{CATPATH(divider=>,top=>yes|no)}{CATPATH}~/np~";
}

function wikiplugin_catpath_info() {
	return array(
		'name' => tra('Category Path'),
		'documentation' => 'PluginCatPath',
		'description' => tra("Insert the full category path for each category that this wiki page belongs to"),
		'prefs' => array( 'feature_categories', 'wikiplugin_catpath' ),
		'params' => array(
			'divider' => array(
				'required' => false,
				'name' => tra('Separator'),
				'description' => tra('String used to separate the categories in the path. Default character is >.'),
			),
			'top' => array(
				'required' => false,
				'name' => tra('Display top category'),
				'description' => tra('yes|no, default to no'),
			),
		),
	);
}

function wikiplugin_catpath($data, $params) {
	global $dbTiki, $smarty, $tikilib, $prefs, $categlib;

	if (!is_object($categlib)) {
		require_once ("lib/categories/categlib.php");
	}

	if ($prefs['feature_categories'] != 'y') {
		return "<span class='warn'>" . tra("Categories are disabled"). "</span>";
	}

	extract ($params,EXTR_SKIP);

	// default divider is '>'
	if (!(isset($divider))) {
		$divider = '>';
	}

	// default setting for top is 'no'
	if (!(isset($top))) {
		$top = 'no';
	} elseif ($top != 'y' and $top != 'yes' and $top != 'n' and $top != 'no') {
		$top = 'no';
	}

	$objId = urldecode($_REQUEST['page']);

	$cats = $categlib->get_object_categories('wiki page', $objId);

	$catpath = '';

	foreach ($cats as $categId) {
		$catpath .= '<span class="categpath">';

		// Display TOP on each line if wanted
		if ($top == 'yes' or $top == 'y') {
			$catpath .= '<a class="categpath" href="tiki-browse_categories.php?parentId=0">TOP</a> ' . $divider . ' ';
		}

		$path = '';
		$info = $categlib->get_category($categId);
		$path
			= '<a class="categpath" href="tiki-browse_categories.php?parentId=' . $info["categId"] . '">' . $info["name"] . '</a>';

		while ($info["parentId"] != 0) {
			$info = $categlib->get_category($info["parentId"]);

			$path = '<a class="categpath" href="tiki-browse_categories.php?parentId=' . $info["categId"] . '">' . $info["name"] . '</a> ' . $divider . ' ' . $path;
		}

		$catpath .= $path . '</span><br />';
	}

	return $catpath;
}
