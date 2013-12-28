<?php 

/**
 * Try to find the composer autoloader and then require it.
 * If we cannot find the composer autoloader then we will 
 * throw an Exception
 * 
 * @return composer autoloader | Exception
 */
function require_composer()
{
	$autoloaders = array(
		__DIR__ . '/../../autoload.php',
		__DIR__ . '/vendor/autoload.php',
	);

	foreach ($autoloaders as $autoloader)
	{
		$file = realpath($autoloader);

		if (file_exists($file))
		{
			return require($file);
		}
	}

	throw new Exception("Could not require composer autoloader!");
}

// require composer's autoloader
require_composer();

// start a new Live Reload Server
$server = new Codesleeve\GuardLiveReload\LiveReloadServer;
$server->start();