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
		$this->stopInBackground();
	}

	/**
	 * Let livereload server know to send updates
	 * 
	 * @param  $event  
	 * @return         
	 */
	public function listen($event)
	{
		$date = new DateTime;
		$filename = realpath(rtrim(sys_get_temp_dir(), '/') . '/guard-reload');

		file_put_contents($filename, $date->format(DateTime::ISO8601));
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
		    1 => array('pipe', 'w'),
		    2 => array('pipe', 'w')
		);

		$this->process = proc_open($cmd, $desc, $pipes);
	}

	/**
	 * Stop this process in background
	 * 
	 * @return void
	 */
	private function stopInBackground()
	{
		$s = proc_get_status($this->process);

		posix_kill($s['pid'], SIGKILL);
		proc_close($this->process);
	}
}