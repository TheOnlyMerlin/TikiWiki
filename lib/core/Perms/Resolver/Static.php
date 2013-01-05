<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Resolver containing the list of permissions for each group as a
 * static list. The resolvers are generated by factories and apply
 * for a specific context.
 */
class Perms_Resolver_Static implements Perms_Resolver
{
	private $known = array();
	private $from = '';

	function __construct( array $known, $from = '' )
	{
		foreach ( $known as $group => $perms ) {
			$this->known[$group] = array_fill_keys($perms, true);
		}
		$this->from = $from;
	}

	function check( $name, array $groups )
	{
		foreach ( $groups as $groupName ) {
			if ( isset( $this->known[$groupName] ) ) {
				if ( isset( $this->known[$groupName][$name] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	function from()
	{
		return $this->from;
	}

	function applicableGroups()
	{
		return array_keys($this->known);
	}
}
