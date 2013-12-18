<?php namespace Codesleeve\WatcherLiveReload;

use Illuminate\Support\ServiceProvider;

class WatcherLiveReloadServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('codesleeve/watcher-live-reload');

		include __DIR__.'/../../routes.php';

		\Event::listen('asset.pipeline.boot', function($pipeline)
		{
			$config = $pipeline->getConfig();

			$javascript_files = array_key_exists('javascript_files', $config) ? $config['javascript_files'] : array();

			$realpath = realpath(__DIR__ . '/../../../assets/javascripts/');

			$javascript_files[] = $realpath . '/live-reload.js';

			$relativePath = str_replace($config['base_path'] . '/', '', $realpath);

			$config['javascript_files'] = $javascript_files;

			$config['paths'][] = $relativePath;

			$pipeline->setConfig($config);
		});

		\Event::listen('watcher.boot', function($watcher)
		{
			$config = $watcher->getConfig();

			$config['events'][] = new LiveReloadEvent;

			$watcher->setConfig($config);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}