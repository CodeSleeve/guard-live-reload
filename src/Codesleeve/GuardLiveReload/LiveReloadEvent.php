<?php namespace Codesleeve\GuardLiveReload;

use DateTime;

class LiveReloadEvent
{
	/**
	 * let the route for this package know that we should reload now
	 * 
	 * @param  $event  
	 * @param  $watcher
	 * @return         
	 */
	public function listen($event, $watcher)
	{
	 	$date = new DateTime;
		file_put_contents(storage_path() . '/reload_trigger_file', $date->format(DateTime::ISO8601));
	}
}