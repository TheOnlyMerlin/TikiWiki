<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Test class for TikiConnect.
 * Generated by PHPUnit on 2011-08-24 at 13:32:56.
 */
class Connect_Client_Test extends TikiTestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->obj = new Connect_Client();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	public function testBuildConnectData()
	{
		$data = $this->obj->buildConnectData(); // TODO check status etc

		$this->assertGreaterThan(0, count($data));
	}

}
