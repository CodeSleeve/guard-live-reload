<?php namespace Codesleeve\GuardLiveReload;

use Illuminate\Support\ServiceProvider;

class GuardLiveReloadServiceProvider extends ServiceProvider
{
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
		$this->package('codesleeve/guard-live-reload');

		\Event::listen('guard.boot', function($guard)
		{
			$config = $guard->getConfig();

			$config['events'][] = new LiveReloadEvent;

			$guard->setConfig($config);
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