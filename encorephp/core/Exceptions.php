<?php namespace Encore\Core;

class Exceptions
{
	public static function error_handler($severity, $message, $filepath, $line)
	{
		print_r(func_get_args());
	}
	
	public static function exception_handler($exception)
	{
		print_r(func_get_args());
	}
}