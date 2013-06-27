<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_lowercase_list()
{
	return array(
		'lowercase_username' => array(
			'name' => tra('Force lowercase'),
			'type' => 'flag',
			'help' => 'Login+Config#Case_Sensitivity',
			'default' => 'n',
		),
	);	
}
