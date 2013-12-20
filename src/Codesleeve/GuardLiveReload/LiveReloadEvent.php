<?php namespace Codesleeve\GuardLiveReload;

use Codesleeve\Guard\Events\EventInterface;

class LiveReloadEvent implements EventInterface
{
	/**
	 * We have started the watcher... 
	 * 
	 * @param  [type] $guard [description]
	 * @return [type]          [description]
	 */
	public function start($guard)
	{
		$this->watcher = $guard;
	}

	/**
	 * Stop things
	 * 
	 * @return [type]          [description]
	 */
	public function stop()
	{

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