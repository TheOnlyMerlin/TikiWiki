<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: tiki-index.php 17851 2009-04-08 13:25:53Z sylvieg $
require_once 'tiki-setup.php';
$access->check_feature('wiki_validate_plugin');
$access->check_permission('tiki_p_plugin_approve');
if (isset($_POST['clear']) && is_array($_POST['clear'])) {
	foreach($_POST['clear'] as $fp) $tikilib->plugin_clear_fingerprint($fp);
}
$smarty->assign('plugin_list', $tikilib->list_plugins_pending_approval());
$smarty->assign('mid', 'tiki-plugins.tpl');
$smarty->display("tiki.tpl");
