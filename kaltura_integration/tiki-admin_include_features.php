<?php
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Process Features form(s)
if (isset($_REQUEST["features"])) {

	$features_toggles = array(

	    "feature_kaltura",
		"feature_action_calendar",
		"feature_ajax",
		"feature_ajax_autosave",
		"feature_banning",
		"feature_comm",
		"feature_contacts",
		"feature_custom_home",
		"feature_debug_console",
		"feature_events", //2009-04-29 marclaporte: can we remove this?
		"feature_friends",
		"feature_fullscreen",
		"feature_integrator",
		"feature_intertiki",
		"feature_jscalendar",
		"feature_mailin",
		"feature_messages",
		"feature_minical",
		"feature_mobile",
		"feature_morcego",
		"feature_notepad",
		"feature_phplayers",
		"feature_cssmenus",
		"feature_redirect_on_error",
		"feature_referer_stats",
		"feature_sheet",
		"feature_sefurl",
		"feature_stats",
		"feature_tasks",
		"feature_mytiki",
		"feature_userPreferences",
		"feature_user_bookmarks",
		"feature_user_watches",
		"feature_group_watches",
		"feature_daily_report_watches",
		"feature_quick_object_perms",
		"feature_user_watches_translations",
		"feature_userfiles",
		"feature_usermenu",
		"feature_webmail",
		"feature_workflow",
		"feature_wysiwyg",
		"feature_xmlrpc",
		"feature_userlevels",
		"feature_shadowbox",
		"feature_tikitests",
		"feature_magic",
		"feature_groupalert",
		"feature_workspaces",
		"feature_wiki_mindmap",
		"use_minified_scripts",
		"feature_print_indexed",
		'debug_ignore_xdebug',
	);

	$pref_byref_values = array(
		"user_flip_modules"
	);

	check_ticket('admin-inc-features');
	foreach ($features_toggles as $toggle) {
		simple_set_toggle ($toggle);
	}
	foreach ($pref_byref_values as $britem) {
		byref_set_value ($britem);
	}

	$cachelib->empty_full_cache();

}

$smarty->assign('php_major_version', substr(PHP_VERSION, 0, strpos(PHP_VERSION, '.')));

ask_ticket('admin-inc-features');
