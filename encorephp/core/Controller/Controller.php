<?php namespace Controller;

class Controller
{
	private static $instance = FALSE;

	public function __construct()
	{
		alias([
			'View' => 'Controller\View'
		]);
	}
}