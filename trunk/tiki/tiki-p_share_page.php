<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-p_share_page.php,v 1.1 2007-07-19 20:55:17 lphuberdeau Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.


// Initialization
require_once ('tiki-setup.php');

include_once ('lib/categories/categlib.php');
include_once ('lib/tree/categ_browse_tree.php');

include_once ('lib/sharelib.php');

// Basic validation {{{

if ($tiki_p_share_page != 'y') {
	$smarty->assign('msg', tra("You do not have permission to use this feature"));
	$smarty->display("error.tpl");
	die;
}

if( ! isset( $_REQUEST['objectId'] ) || ! isset( $_REQUEST['objectType'] ) ) {
	$smarty->assign('msg', tra("Missing parameter on page. objectId and objectType required."));
	$smarty->display("error.tpl");
	die;
}

$objectId = $_REQUEST['objectId'];
$objectType = $_REQUEST['objectType'];

$smarty->assign( 'objectId', $objectId );
$smarty->assign( 'objectType', $objectType );

// }}}

$validPermission = array(
	'tiki_p_view',
	'tiki_p_edit',
);

// Make sure the user can only assign permissions he has {{{
function cb_current_user_has_perm( $name )
{
	global $tikilib, $objectType, $objectId, $user;

	return $tikilib->user_has_perm_on_object( $user, $objectId, $objectType, $name, $name );
}

$validPermission = array_filter( $validPermission, 'cb_current_user_has_perm' );
// }}}

$sharedObject = new Tiki_ShareObject( $objectType, $objectId );

foreach( $validPermission as $permission )
	$sharedObject->loadPermission( $permission );

$smarty->assign( 'columns', $validPermission );
$smarty->assign( 'sharedObject', $sharedObject );

// Identify the 'other' groups / Must be done before form processing {{{
$others = $sharedObject->getOtherGroups();
$users = $groups = array();
foreach( $others as $group )
	if( $group{0} == '*' )
		$users[] = substr( $group, 1 );
	else
		$groups[] = $group;

$smarty->assign( 'otherUsers', $users );
$smarty->assign( 'otherGroups', $groups );
// }}}

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	// Handle the add to list form {{{
	if( isset( $_POST['groups'] ) && is_array( $_POST['groups'] ) )
	{
		// Add groups to the valid list for being selected
		foreach( $_POST['groups'] as $name )
			$group = $sharedObject->getGroup( $name );
	}

	if( isset( $_POST['users'] ) && is_array( $_POST['users'] ) )
	{
		// Add groups to the valid list for being selected
		foreach( $_POST['users'] as $name )
			$group = $sharedObject->getGroup( '*' . $name );
	}
	// }}}

	// Apply permission form {{{
	// Form always processed to preserve changes, but changes only applied
	// when 'apply' is clicked
	if( isset( $_POST['priv'] ) && is_array( $_POST['priv'] ) )
	{
		foreach( $_POST['priv'] as $group => $value )
			if( $group = $sharedObject->getGroup( $group ) )
			{
				// Handled as an array for future use
				$value = array_intersect( (array) $value, $validPermission );
				$group->setObjectPermissions( $value );
			}
	}

	if( isset( $_POST['apply'] ) )
		$sharedObject->saveObjectPermissions( $validPermission );
	// }}}
}

// Must be done after form processing
$smarty->assign( 'groups', $sharedObject->getValidGroups() );

$section = 'sharepage';
include_once ('tiki-section_options.php');
ask_ticket('share-page');

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

// Display the template
$smarty->assign('mid', 'tiki-p_share_page.tpl');
$smarty->display("tiki.tpl");

?>
