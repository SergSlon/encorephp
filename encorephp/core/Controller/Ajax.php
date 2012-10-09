<?php namespace Controller;

class Ajax extends Controller
{
	public function __construct()
	{
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) OR strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		{
			show_404();
		}
	}
}