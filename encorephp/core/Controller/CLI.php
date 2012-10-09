<?php namespace Controller;

class CLI extends Controller
{
	public function __construct()
	{
		if ( ! IS_CLI)
		{
			show_404();
		}
	}
}