<?php

/**
 * This allows us to route to the correct assets
 */
Route::group(Config::get('watcher-live-reload::routing'), function()
{
	Route::get('should-i-reload', function()
	{
		$filename = storage_path() . '/reload_trigger_file';

		if (file_exists($filename) && unlink($filename))
		{
			return 'yes';
		}

		return 'no';
	});

	Route::get('ws', function()
	{
		
	});

});
