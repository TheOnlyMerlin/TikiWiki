<?php

// $Header: /cvsroot/tikiwiki/tiki/lib/setup/babelfish.php,v 1.1 2007-10-06 15:18:43 nyloth Exp $
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for
// details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'],'tiki-setup.php')!=FALSE) {
  header('location: index.php');
  exit;
}

if ( $feature_babelfish == 'y' ) {
	require_once('lib/Babelfish.php');
	$smarty->assign('babelfish_links', Babelfish::links($language));
}

if ( $feature_babelfish_logo == 'y' ) {
	require_once('lib/Babelfish.php');
	$smarty->assign('babelfish_logo', Babelfish::logo($language));
}

