<?php

require_once 'tiki-setup.php';
require_once 'lib/profilelib/profilelib.php';
require_once 'lib/profilelib/installlib.php';
require_once 'lib/profilelib/channellib.php';

if ($tiki_p_admin != 'y') {
  $smarty->assign('errortype', 401);
  $smarty->assign('msg', tra("You do not have permission to use this feature"));
  $smarty->display("error.tpl");
  die;
}

if( ! isset($_REQUEST['channels']) || ! is_array($_REQUEST['channels']) ) {
	$access->display_error( 'tiki-channel.php', tra('Invalid request. Expecting channels array.') );
}

$calls = array();
$channels = array();

foreach( $_REQUEST['channels'] as $info )
{
	if( ! isset( $info['channel_name'] ) ) {
		$access->display_error( 'tiki-channel.php', tra('Missing channel name.') );
	}

	$channel = $info['channel_name'];
	$channels[] = $channel;
	unset($info['channel_name']);
	$calls[] = array( $channel, $info ); 
}

$config = Tiki_Profile_ChannelList::fromConfiguration( $prefs['profile_channels'] );


$channels = array_unique( $channels );
$groups = $tikilib->get_user_groups( $user );

if( ! $user && ! $config->canExecuteChannels( $channels, $groups ) ) {
	// User not defined and some groups missing, likely to be a machine
	if( ! $access->http_auth() ) {
		$access->display_error( 'tiki-channel.php', tra('Authentication required.') );
	}

	// Get the new ones
	$groups = $tikilib->get_user_groups( $user );
}

if( ! $config->canExecuteChannels( $channels, $groups ) ) {
	$access->display_error( 'tiki-channel.php', tra('One of the requested channels cannot be requested. It does not exist or permission is denied.') );
}

$profiles = $config->getProfiles( $channels );

if( count($profiles) != count($channels) ) {
	$access->display_error( 'tiki-channel.php', tra('One of the install profiles could not be obtained.') );
}

Tiki_Profile::useUnicityPrefix(uniqid());
$installer = new Tiki_Profile_Installer;

foreach( $calls as $call ) {
	list( $channel, $userInput ) = $call;

	// Profile can be installed multiple times
	// Only last values preserved
	$profile = $profiles[$channel];
	$installer->forget( $profile );

	$installer->setUserData( $userInput );
	$installer->install( $profile );
	var_dump($profile);
}

if( isset($_REQUEST['return_uri']) ) {
	header( "Location: {$_REQUEST['return_uri']}" );
}
