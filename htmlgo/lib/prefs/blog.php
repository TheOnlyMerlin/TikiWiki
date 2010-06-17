<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_blog_list() {
	return array(
		'blog_spellcheck' => array(
			'name' => tra('Spell cheking'),
			'hint' => tra('Requires a separate download'),
			'type' => 'flag',
			'help' => 'Spellcheck',
		),
		'blog_comments_per_page' => array(
			'name' => tra('Default number per page'),
			'type' => 'text',
			'size' => '3',
		),
		'blog_comments_default_ordering' => array(
			'name' => tra('Default ordering'),
			'type' => 'list',
			'options' => array(
				'commentDate_desc' => tra('Newest first'),
				'commentDate_asc' => tra('Oldest first'),
				'points_desc' => tra('Points'),
			),
		),
		'blog_list_order' => array(
			'name' => tra('Default ordering'),
			'type' => 'list',
			'options' => array(
				'created_desc' => tra('Creation Date (desc)'),
				'lastModif_desc' => tra('Last modification date (desc)'),
				'title_asc' => tra('Blog title (asc)'),
				'posts_desc' => tra('Number of posts (desc)'),
				'hits_desc' => tra('Visits (desc)'),
				'activity_desc' => tra('Activity (desc)'),
			),
		),
		'blog_list_title' => array(
			'name' => tra('Title'),
			'type' => 'flag',
		),
		'blog_list_title_len' => array(
			'name' => tra('Title length'),
			'type' => 'text',
			'size' => '3',
		),
		'blog_list_description' => array(
			'name' => tra('Description'),
			'type' => 'flag',
		),
		'blog_list_created' => array(
			'name' => tra('Creation date'),
			'type' => 'flag',
		),
		'blog_list_lastmodif' => array(
			'name' => tra('Last modified'),
			'type' => 'flag',
		),
		'blog_list_user' => array(
			'name' => tra('User'),
			'type' => 'list',
			'options' => array(
				'disabled' => tra('Disabled'),
				'text' => tra('Plain text'),
				'link' => tra('Link to user information'),
				'avatar' => tra('User avatar'),
			),
		),
		'blog_list_posts' => array(
			'name' => tra('Posts'),
			'type' => 'flag',
		),
		'blog_list_visits' => array(
			'name' => tra('Visits'),
			'type' => 'flag',
		),
		'blog_list_activity' => array(
			'name' => tra('Activity'),
			'type' => 'flag',
		),
		'blog_sharethis_publisher' => array(
			'name' => tra('Your ShareThis publisher identifier (optional)'),
			'type' => 'text',
			'size' => '40',
		),
	);
}
