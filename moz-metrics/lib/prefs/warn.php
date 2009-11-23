<?php

function prefs_warn_list() {
	return array(
		'warn_on_edit_time' => array(
			'name' => tra('Edit idle timeout'),
			'shorthint' => tra('minutes'),
			'type' => 'list',
			'options' => array(
				'1' => tra('1'),
				'2' => tra('2'),
				'5' => tra('5'),
				'10' => tra('10'),
				'15' => tra('15'),
				'30' => tra('30'),
			),
		),
	);
	
}
