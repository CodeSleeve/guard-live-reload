<?php namespace Codesleeve\GuardLiveReload;

class LiveReloadEventTest extends \PHPUnit_Framework_TestCase
{	
	public function setUp()
	{
		$this->event = new LiveReloadEvent;
		$this->event->start(null);
	}

	public function tearDown()
	{
		$this->event->stop();
	}

	public function testConstruction()
	{
		// if we even make it here, we've constructed!
	}

}