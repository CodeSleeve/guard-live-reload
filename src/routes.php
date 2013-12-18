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
		list($resource,$host,$origin) = get_headers($buffer);
		$upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		           "Upgrade: WebSocket\r\n" .
		           "Connection: Upgrade\r\n" .
		           "WebSocket-Origin: " . $origin . "\r\n" .
		           "WebSocket-Location: ws://" . $host . $resource . "\r\n" .
		           "\r\n";
		$handshake = true;
		socket_write($socket,$upgrade.chr(0),strlen($upgrade.chr(0)));
	});

});
