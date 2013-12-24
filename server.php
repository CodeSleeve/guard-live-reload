<?php 

require __DIR__ . '/vendor/autoload.php';

$server = new Codesleeve\GuardLiveReload\LiveReloadServer;
$server->start();