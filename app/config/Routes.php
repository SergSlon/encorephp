<?php defined('SYS_PATH') OR exit('No direct script access');

$route['hello(/.+)?'] = function()
{
	Router::loadController('Index', 'helloAgain');
};