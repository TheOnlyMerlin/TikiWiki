<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

$inputConfiguration = array(
	array('staticKeyFilters' => array(
		'email' => 'email',
		'name' => 'text',
		'pass' => 'text',
		'passAgain' => 'text',
	))
);

$auto_query_args = array();

require_once ('tiki-setup.php');

ask_ticket('register');

if (isset($redirect) && !empty($redirect)) {
	header('Location: '.$redirect);
	exit;
}

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');
global $user;
$smarty->assign('user_exists', TikiLib::lib('user')->user_exists($user));

$re = $userlib->get_group_info(isset($_REQUEST['chosenGroup']) ? $_REQUEST['chosenGroup'] : 'Registered');
$tr = TikiLib::lib('trk')->get_tracker($re['usersTrackerId']);
if (!empty($tr['description'])) {
	$smarty->assign('userTrackerHasDescription', true);
}

$smarty->assign('mid', 'tiki-register.tpl');
$smarty->display('tiki.tpl');
