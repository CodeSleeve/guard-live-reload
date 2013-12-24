<?php namespace Codesleeve\GuardLiveReload;

class ProtocolTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		// $loop = \React\EventLoop\Factory::create();
		// $client = new \Devristo\Phpws\Client\WebSocket("ws://echo.websocket.org/?encoding=text", $loop, $logger);

		// $client->on("connected", function($headers) use ($client)
		// {
		// 	$client->send("Hello world!");
		// });

		// $client->on("message", function($message) use ($client)
		// {
	 //    	echo "Got message: ". $message->getData() . PHP_EOL;
		//     $client->close();
		// });

		// $client->open();
		// $loop->run();
	}

	public function testConstruction()
	{
		// does this even get here?
	}


}