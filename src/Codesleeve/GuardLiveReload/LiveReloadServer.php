<?php namespace Codesleeve\GuardLiveReload;

class LiveReloadServer
{
	public function __construct($config = array())
	{
		$livereload = new Protocols\LiveReloadProtocol;
		$command = new Protocols\CommandProtocol($livereload);

		$this->config = array_merge(array(
			'host' => '127.0.0.1',
			'port' => 35729,
			'timeout' => 2,
			'livereload' => $livereload,
			'command' => $command,
			'reload_cmd_file' => rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-livereload-reload',
			'shutdown_cmd_file' => rtrim(sys_get_temp_dir(), '/') . '/codesleeve-guard-livereload-shutdown',
			'routes' => array(
				array('/livereload', $livereload, array("*")),
				array('/livereload.js', new Protocols\HttpFileProtocol, array("*")),
				array('/command', $command, array('*')),
			),
		), $config);
	}

	/**
	 * Start this live reload server
	 * 
	 * @return 
	 */
	public function start()
	{
		$config = $this->config;

		$loop = \React\EventLoop\Factory::create();
		$app = new \Ratchet\App($config['host'], $config['port'], $config['host'], $loop);

		foreach ($config['routes'] as $route)
		{
			call_user_func_array(array($app, 'route'), $route);
		}

		$loop->addTimer($config['timeout'], array($this, 'watchTempFile'));
		$app->run();		
	}

	public function stop($loop)
	{
		print 'Received shutdown command, stopping application.' . PHP_EOL;
		$loop->stop();
	}
	/**
	 * Go look to see if a file exists about refreshing
	 * the page and if so we will send a reloadCommand to
	 * our livereload plugin.
	 * 
	 * @param  [type] $timer [description]
	 * @return [type]        [description]
	 */
	public function watchTempFile($timer)
	{
		$config = $this->config;

		if (file_exists($file = $config['reload_cmd_file']))
		{
			$config['livereload']->reloadCommand();
			unlink($file);
		}

		if (file_exists($file = $config['shutdown_cmd_file']))
		{
			$this->stop($timer->getLoop());
			unlink($file);
			return;
		}

		$timer->getLoop()->addTimer($config['timeout'], array($this, 'watchTempFile'));
	}
}