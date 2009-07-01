<?php
// $Id$

/* Workspaces GUI Management */
require_once ('tiki-setup.php');
$access->check_script($_SERVER["SCRIPT_NAME"],basename(__FILE__));
require_once ('lib/workspaces/wsController.php');

$ws_gui = new ws_gui_controller();

$ws_gui->check_if_new_to_ws();

if ( isset($_REQUEST['wsoptions']) )
{
    if ( (isset($_REQUEST['wsdevtools'])) && ($_REQUEST['wsdevtools'] == 'create') )
	header("Location: ./lib/workspaces/wstools/scriptCreator.php?action=create");
    else
	header("Location: ./lib/workspaces/wstools/scriptCreator.php?action=destroy");
}

ask_ticket('admin-inc-workspaces');
