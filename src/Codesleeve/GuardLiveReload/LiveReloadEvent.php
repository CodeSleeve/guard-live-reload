<?php namespace Codesleeve\GuardLiveReload;

use Codesleeve\Guard\Events\EventInterface;

class LiveReloadEvent implements EventInterface
{
	/**
	 * Port that live reload web sockets server runs on
	 * 
	 * @var integer
	 */
	public $port = 35729;

	/**
	 * We have started guard... so let's start the web sockets server
	 * 
	 * @param  [type] $guard [description]
	 * @return [type]          [description]
	 */
	public function start($guard)
	{
		// $this->guard = $guard;

		// $this->liveReload = new LiveReloadServer;

		// $this->app = new Ratchet\App('localhost', $this->port);
		// $this->app->route('/livereload', $this->liveReload);
		// // $this->app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
		// $this->app->run();		
	}

	/**
	 * Stop things
	 * 
	 * @return [type]          [description]
	 */
	public function stop()
	{
		// $this->app
	}

	/**
	 * Let livereload server know to send updates
	 * 
	 * @param  $event  
	 * @return         
	 */
	public function listen($event)
	{
	 	// 	$date = new DateTime;
		// file_put_contents(storage_path() . '/reload_trigger_file', $date->format(DateTime::ISO8601));
		// 
	}
}