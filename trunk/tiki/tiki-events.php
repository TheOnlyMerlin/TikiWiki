<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-events.php,v 1.2 2005-01-22 22:54:54 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/events/evlib.php');

if ($feature_events != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_events");

	$smarty->display("error.tpl");
	die;
}

$smarty->assign('confirm', 'n');

//TODO: memorize the charset for each subscription
if (isset($_REQUEST["confirm_subscription"])) {
	$conf = $evlib->confirm_subscription($_REQUEST["confirm_subscription"]);

	if ($conf) {
		$smarty->assign('confirm', 'y');

		$smarty->assign('ev_info', $conf);
	}
}

$smarty->assign('unsub', 'n');

if (isset($_REQUEST["unsubscribe"])) {
	$conf = $evlib->unsubscribe($_REQUEST["unsubscribe"]);

	if ($conf) {
		$smarty->assign('unsub', 'y');

		$smarty->assign('ev_info', $conf);
	}
}

if (!$user && $tiki_p_subscribe_events != 'y' && !isset($_REQUEST["confirm_subscription"])) {
	$smarty->assign('msg', tra("You must be logged in to subscribe to events"));

	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["evId"])) {
	$_REQUEST["evId"] = 0;
}

$smarty->assign('evId', $_REQUEST["evId"]);

$smarty->assign('subscribe', 'n');
$smarty->assign('subscribed', 'n');

$foo = parse_url($_SERVER["REQUEST_URI"]);
$smarty->assign('url_subscribe', $tikilib->httpPrefix(). $foo["path"]);

if (isset($_REQUEST["evId"])) {
	$smarty->assign('individual', 'n');

	if ($userlib->object_has_one_permission($_REQUEST["evId"], 'event')) {
		$smarty->assign('individual', 'y');

		if ($tiki_p_admin != 'y') {
			$perms = $userlib->get_permissions(0, -1, 'permName_desc', '', 'events');

			foreach ($perms["data"] as $perm) {
				$permName = $perm["permName"];

				if ($userlib->object_has_permission($user, $_REQUEST["evId"], 'event', $permName)) {
					$$permName = 'y';

					$smarty->assign("$permName", 'y');
				} else {
					$$permName = 'n';

					$smarty->assign("$permName", 'n');
				}
			}
		}
	}
}

if ($user) {
	$user_email = $userlib->get_user_email($user);
} else {
	$user_email = '';
}

$smarty->assign('email', $user_email);

if ($tiki_p_subscribe_events == 'y') {
	if (isset($_REQUEST["subscribe"])) {
	check_ticket('events');
		$smarty->assign('subscribed', 'y');

		if ($tiki_p_subscribe_email != 'y') {
			$_REQUEST["email"] = $userlib->get_user_email($user);
		}

		// Now subscribe the email address to the event
		$evlib->event_subscribe($_REQUEST["evId"], $_REQUEST["email"], $_REQUEST["fname"], $_REQUEST["lname"], $_REQUEST["company"], $tikilib->get_user_preference($user, 'mailCharset', 'utf-8'));
	}
}

if (isset($_REQUEST["info"])) {
	$ev_info = $evlib->get_event($_REQUEST["evId"]);

	$smarty->assign('ev_info', $ev_info);
	$smarty->assign('subscribe', 'y');
}
/* List events */
if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'created_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$smarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$smarty->assign('find', $find);

$smarty->assign_by_ref('sort_mode', $sort_mode);
$channels = $evlib->list_events($offset, $maxRecords, $sort_mode, $find);

$temp_max = count($channels["data"]);
for ($i = 0; $i < $temp_max; $i++) {
	if ($userlib->object_has_one_permission($channels["data"][$i]["evId"], 'events')) {
		$channels["data"][$i]["individual"] = 'y';

		if ($userlib->object_has_permission($user, $channels["data"][$i]["evId"], 'event', 'tiki_p_subscribe_events')) {
			$channels["data"][$i]["individual_tiki_p_subscribe_events"] = 'y';
		} else {
			$channels["data"][$i]["individual_tiki_p_subscribe_events"] = 'n';
		}

		if ($tiki_p_admin == 'y'
			|| $userlib->object_has_permission($user, $channels["data"][$i]["evId"], 'event', 'tiki_p_admin_events')) {
			$channels["data"][$i]["individual_tiki_p_subscribe_events"] = 'y';
		}
	} else {
		$channels["data"][$i]["individual"] = 'n';
	}
}

$cant_pages = ceil($channels["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $maxRecords));

if ($channels["cant"] > ($offset + $maxRecords)) {
	$smarty->assign('next_offset', $offset + $maxRecords);
} else {
	$smarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$smarty->assign('prev_offset', -1);
}

$smarty->assign_by_ref('channels', $channels["data"]);
ask_ticket('events');

// Display the template
$smarty->assign('mid', 'tiki-events.tpl');
$smarty->display("tiki.tpl");

?>
