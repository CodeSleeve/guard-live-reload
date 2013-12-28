<?php namespace Codesleeve\GuardLiveReload;

class LiveReloadServer
{
	public function __construct($config = array())
	{
		$livereload = new Protocols\LiveReloadProtocol;

		$this->config = array_merge(array(
			'host' => '127.0.0.1',
			'port' => 35729,
			'timeout' => 2,
			'livereload' => $livereload,
			'watch' => rtrim(sys_get_temp_dir(), '/') . '/guard-reload',
			'routes' => array(
				array('/livereload', $livereload, array("*")),
				array('/livereload.js', new Protocols\HttpFileProtocol, array("*")),
				array('/command', new Protocols\CommandProtocol($livereload), array('*')),
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

		if (file_exists($config['watch']))
		{
			$config['livereload']->reloadCommand();
			unlink($config['watch']);
		}

		$timer->getLoop()->addTimer($config['timeout'], array($this, 'watchTempFile'));
	}
}