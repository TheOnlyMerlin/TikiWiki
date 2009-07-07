<?php

require_once 'TikiTestCase.php';

/* 
 * This test case verifies that we can access various Tiki libraries
 * from inside Acceptance tests. This can be useful for example,
 * to set starting conditions of the Tiki DB directly, without having
 * to go through Selenium actions in the browser (the later 
 * is slow).
*/

class  AcceptanceTests_TikiLibrariesAccessTest extends TikiTestCase
{

    protected function setUp()
    {
    }


    public function ___testRememberToReactivateAllTestsInTikiLibrariesAccessTest() {
       	$this->fail("Don't forget to do this");
    }
       
    public function testAccessPreferences() {
    	global $tikilib;
    	
    	$pref_name = 'feature_machine_translation';

    	$gotPreference = $tikilib->get_preference($pref_name);
    	$this->assertEquals(NULL, $gotPreference, "get_preference() should initially have returned NULL for preference '$pref_name'");
    	$gotPreference = $prefs[$pref_name];
    	$this->assertEquals(NULL, $gotPreference, "\$prefs[$pref_name] should initially have been NULL");

		$tikilib->set_preference($pref_name, 'y');
    	$gotPreference = $tikilib->get_preference($pref_name);
    	$this->assertEquals('y', $gotPreference, "After setting it, get_preference() should have returned 'y' after following preference was set: '$pref_name'");
    	$gotPreference = $prefs[$pref_name];
    	$this->assertEquals('y', $gotPreference, "\$prefs[$pref_name] should initially have been 'y' after that preference was set. NOTE: At this point, this test fails. I think set_preference() should not only update the DB, but also set \$prefs");
    }    
    
}