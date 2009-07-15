<?php

class Perms_BaseTest extends TikiTestCase
{
	function testWithoutConfiguration() {
		$accessor = Perms::get();

		$expect = new Perms_Accessor;

		$this->assertEquals( $expect, $accessor );
	}

	function testGroupsPropagateToAccessor() {
		$perms = new Perms;
		$perms->setGroups( array( 'Registered', 'Administrator' ) );
		Perms::set( $perms );

		$expect = new Perms_Accessor;
		$expect->setGroups( array( 'Registered', 'Administrator' ) );

		$this->assertEquals( $expect, Perms::get() );
	}

	/**
	 * @dataProvider resolverMatches
	 */
	function testResolverFactoryChaining( $context, $expectedResolver ) {
		$perms = new Perms;
		$perms->setResolverFactories( array(
			new Perms_ResolverFactory_TestFactory( array( 'object' ), array(
				'a' => $rA = new Perms_Resolver_Default( true ),
				'b' => $rB = new Perms_Resolver_Default( true ),
			) ),
			new Perms_ResolverFactory_TestFactory( array( 'category' ), array(
				'1' => $r1 = new Perms_Resolver_Default( true ),
				'2' => $r2 = new Perms_Resolver_Default( true ),
			) ),
			new Perms_ResolverFactory_TestFactory( array(), array(
				'' => $rG = new Perms_Resolver_Default( true ),
			) ),
		) );
		Perms::set( $perms );

		$this->assertSame( $$expectedResolver, Perms::get( $context )->getResolver() );
	}

	function resolverMatches() {
		return array(
			'testObjectA' => array( array( 'object' => 'a' ), 'rA' ),
			'testObjectB' => array( array( 'object' => 'b' ), 'rB' ),
			'testCategoryIgnoredWhenObjectMatches' => array( array( 'object' => 'b', 'category' => '1' ), 'rB' ),
			'testCategoryObtainOnObjectMiss' => array( array( 'object' => 'c', 'category' => '1' ), 'r1' ),
			'testCategoryOnly' => array( array( 'category' => 2 ), 'r2' ),
			'testObjectAndCategoryMiss' => array( array( 'object' => 'd', 'category' => '3' ), 'rG' ),
			'testNoContext' => array( array(), 'rG' ),
		);
	}

	function testResolverNotCalledTwiceWhenFound() {
		$mock = $this->getMock( 'Perms_ResolverFactory' );
		$mock->expects($this->exactly(2))
			->method( 'getHash' )
			->will( $this->returnValue( '123' ) );
		$mock->expects($this->once())
			->method( 'getResolver' )
			->will( $this->returnValue( new Perms_Resolver_Default(true) ) );

		$perms = new Perms;
		$perms->setResolverFactories( array(
			$mock,
		) );
		Perms::set( $perms );
		
		Perms::get();
		Perms::get();
	}

	function testResolverNotCalledTwiceWhenNotFound() {
		$mock = $this->getMock( 'Perms_ResolverFactory' );
		$mock->expects($this->exactly(2))
			->method( 'getHash' )
			->will( $this->returnValue( '123' ) );
		$mock->expects($this->once())
			->method( 'getResolver' )
			->will( $this->returnValue( null ) );

		$perms = new Perms;
		$perms->setResolverFactories( array(
			$mock,
		) );
		Perms::set( $perms );
		
		Perms::get();
		Perms::get();
	}

	function testBulkLoading() {
		$mockObject = $this->getMock( 'Perms_ResolverFactory' );
		$mockCategory = $this->getMock( 'Perms_ResolverFactory' );
		$mockGlobal = $this->getMock( 'Perms_ResolverFactory' );

		$perms = new Perms;
		$perms->setResolverFactories( array( $mockObject, $mockCategory, $mockGlobal ) );
		Perms::set($perms);

		$mockObject->expects($this->any())
			->method('getHash')
			->will($this->returnCallback(array($this,'customHash')));
		$mockObject->expects($this->once())
			->method('bulk')
			->with($this->equalTo(array( 'type' => 'wiki page' )), $this->equalTo('object'), $this->equalTo(array('A', 'B', 'C', 'D', 'E')))
			->will($this->returnValue( array('A', 'C', 'E') ));
		$mockCategory->expects($this->once())
			->method('bulk')
			->with($this->equalTo(array( 'type' => 'wiki page' )), $this->equalTo('object'), $this->equalTo(array('A', 'C', 'E')))
			->will($this->returnValue( array('C') ) );
		$mockGlobal->expects($this->once())
			->method('bulk')
			->with($this->equalTo(array( 'type' => 'wiki page')), $this->equalTo('object'), $this->equalTo(array('C')) )
			->will($this->returnArgument(0));

		$data = array(
			array( 'pageId' => 1, 'pageName' => 'A', 'content' => 'Hello World' ),
			array( 'pageId' => 2, 'pageName' => 'B', 'content' => 'Hello World' ),
			array( 'pageId' => 3, 'pageName' => 'C', 'content' => 'Hello World' ),
			array( 'pageId' => 4, 'pageName' => 'D', 'content' => 'Hello World' ),
			array( 'pageId' => 5, 'pageName' => 'E', 'content' => 'Hello World' ),
		);

		Perms::bulk( array( 'type' => 'wiki page' ), 'object', $data, 'pageName' );
	}

	function testBulkPrefiltersAlreadyKnown() {
		$mockObject = $this->getMock( 'Perms_ResolverFactory' );

		$perms = new Perms;
		$perms->setResolverFactories( array( $mockObject ) );
		Perms::set($perms);

		$mockObject->expects($this->any())
			->method('getHash')
			->will($this->returnCallback(array($this,'customHash')));
		$mockObject->expects($this->once())
			->method('getResolver')
			->with($this->equalTo( array('type' => 'wiki page', 'object' => 'C') ))
			->will($this->returnValue( new Perms_Resolver_Default(true) ) );
		$mockObject->expects($this->once())
			->method('bulk')
			->with($this->equalTo(array( 'type' => 'wiki page' )), $this->equalTo('object'), $this->equalTo(array('A', 'B')))
			->will($this->returnValue( array('A', 'C', 'E') ));

		Perms::get( array( 'type' => 'wiki page', 'object' => 'C' ) );

		$data = array( 'A', 'B', 'C' );
		Perms::bulk( array( 'type' => 'wiki page' ), 'object', $data );
	}

	function customHash( $context ) {
		return serialize($context);
	}

	function testFiltering() {
		$perms = new Perms;
		$perms->setResolverFactories( array(
			new Perms_ResolverFactory_TestFactory( array('object'), array(
				'A' => new Perms_Resolver_Default( true ),
				'B' => new Perms_Resolver_Default( true ),
				'C' => new Perms_Resolver_Default( false ),
				'D' => new Perms_Resolver_Default( false ),
				'E' => new Perms_Resolver_Default( true ),
			) ),
		) );
		Perms::set($perms);

		$data = array(
			array( 'pageId' => 1, 'pageName' => 'A', 'content' => 'Hello World' ),
			array( 'pageId' => 2, 'pageName' => 'B', 'content' => 'Hello World' ),
			array( 'pageId' => 3, 'pageName' => 'C', 'content' => 'Hello World' ),
			array( 'pageId' => 4, 'pageName' => 'D', 'content' => 'Hello World' ),
			array( 'pageId' => 5, 'pageName' => 'E', 'content' => 'Hello World' ),
		);

		$out = Perms::filter( array( 'type' => 'wiki page' ), 'object', $data, 'pageName', 'view' );

		$expect = array(
			array( 'pageId' => 1, 'pageName' => 'A', 'content' => 'Hello World' ),
			array( 'pageId' => 2, 'pageName' => 'B', 'content' => 'Hello World' ),
			array( 'pageId' => 5, 'pageName' => 'E', 'content' => 'Hello World' ),
		);

		$this->assertEquals( $expect, $out );
	}
}

