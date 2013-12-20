<?php namespace Codesleeve\GuardLiveReload;

use DateTime;
use Codesleeve\Guard\Events\EventInterface;

class LiveReloadEvent implements EventInterface
{
	/**
	 * We have started the watcher... 
	 * 
	 * @param  [type] $watcher [description]
	 * @return [type]          [description]
	 */
	public function start($watcher)
	{
		$this->watcher = $watcher;
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
	 * @param  $watcher
	 * @return         
	 */
	public function listen($event)
	{
	 	$date = new DateTime;
		file_put_contents(storage_path() . '/reload_trigger_file', $date->format(DateTime::ISO8601));
	}
}