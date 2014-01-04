<?php namespace Codesleeve\GuardLiveReload;

use Symfony\Component\Process\Process;
use Codesleeve\Guard\Events\EventInterface;

class LiveReloadEvent implements EventInterface
{
	/**
	 * We have started guard... so let's start the web sockets server
	 * 
	 * @param  [type] $guard [description]
	 * @return [type]          [description]
	 */
	public function start($guard)
	{
		$this->guard = $guard;

		$this->reloadCmd = rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-livereload-reload';

		$this->shutdownCmd = rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-livereload-shutdown';

		$server = realpath(__DIR__ . '/../../../server.php');

		$this->startInBackground("php {$server}");
	}

	/**
	 * Stop things
	 * 
	 * @return [type]          [description]
	 */
	public function stop()
	{
		file_put_contents($this->shutdownCmd, 'shutdown');
	}

	/**
	 * Let livereload server know to send updates
	 * 
	 * @param  $event  
	 * @return         
	 */
	public function listen($event)
	{
		file_put_contents($this->reloadCmd, 'reload');
	}

	/**
	 * Execute this command in background
	 * 
	 * @param  string $cmd
	 * @return void
	 */
	private function startInBackground($cmd)
	{
		if (substr(php_uname(), 0, 7) == "Windows")
		{
			$cmd = "start /B ". $cmd;
		}
		else
		{
			$cmd .= " > /dev/null &";		
		}

		$desc = array(
		    0 => array('pipe', 'r'),
		    1 => array('file', rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-live-reload.log', 'a'),
		    2 => array('file', rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-live-reload.err', 'a'),
		);

		$this->process = proc_open($cmd, $desc, $pipes);
	}
}