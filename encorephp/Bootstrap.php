<?php defined('SYS_PATH') OR exit('No direct script access');

ini_set('zlib.output_compression', FALSE);

if (PHP_VERSION < 5.4)
	exit('EncorePHP requires PHP 5.4 or greater');

define('IS_CLI', PHP_SAPI === 'cli');

require_once(SYS_PATH . 'core/Common.php');
require_once(SYS_PATH . 'core/Loader.php');
require_once(SYS_PATH . 'core/Controller/Controller.php');
require_once(SYS_PATH . 'core/Model/Model.php');
require_once(SYS_PATH . 'core/Model/ORM.php');
require_once(SYS_PATH . 'traits/Singleton.php');

\Core\Loader::register();

Benchmark::start('execution_time');

set_exception_handler(function (\Exception $e)
{
	return \Core\Exceptions::exception_handler($e);
});

set_error_handler(function ($severity, $message, $filepath, $line)
{
	return \Core\Exceptions::error_handler($severity, $message, $filepath, $line);
});

alias([
	'Core\Router' => 'Router',
	'Core\Config' => 'Config',
	'Core\Helper' => 'Helper',
	'Core\View'   => 'View'
]);

$router = new \Core\Router;

// Autoload some stuff
Config::load('config');
Helper::load('Uri');

// Process the URI and find controller and method
$router->process();