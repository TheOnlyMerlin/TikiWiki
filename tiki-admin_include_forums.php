<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

if (isset($_REQUEST["homeforumprefs"]) && isset($_REQUEST["home_forum"])) {
	check_ticket('admin-inc-forums');
	simple_set_value('home_forum');
}

if (isset($_REQUEST["forumprefs"])) {
	check_ticket('admin-inc-forums');
}

if (isset($_REQUEST["forumlistprefs"])) {
	check_ticket('admin-inc-forums');
}
if (isset($_REQUEST["forumthreadprefs"])) {
	check_ticket('admin-inc-forums');
}
include_once ("lib/commentslib.php");
$commentslib = new Comments($dbTiki);
$forums = $commentslib->list_forums(0, -1, 'name_desc', '');
$smarty->assign_by_ref('forums', $forums["data"]);
ask_ticket('admin-inc-forums');
