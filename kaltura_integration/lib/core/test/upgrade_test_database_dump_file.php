<?php
/*
 * Created on Jun 24, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once 'TikiAcceptanceTestDBRestorer.php';

die ("WARNING: This script will destroy the current Tiki db. Uncomment this line in the script to proceed.");

if ($argc != 2) {
	die("Missing argument. USAGE: $argv[0] <dump_filename>");
}
 
$test_TikiAcceptanceTestDBRestorer = new TikiAcceptanceTestDBRestorer();
$test_TikiAcceptanceTestDBRestorer->restoreDBFromScratch($argv[1]);

$local_php = 'db/local.php';

require_once('installer/installlib.php');
include_once ('lib/adodb/adodb.inc.php');

include $local_php;
$dbTiki = ADONewConnection($db_tiki);
$dbTiki->Connect($host_tiki, $user_tiki, $pass_tiki, $dbs_tiki);
$installer = new Installer;
$installer->update();

$test_TikiAcceptanceTestDBRestorer->create_dump_file($argv[1]);
