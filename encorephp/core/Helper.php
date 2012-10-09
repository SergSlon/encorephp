<?php namespace Encore\Core;

class Helper
{
	private static $_helpers = [];
	public static function load($helpers)
	{
		$to_load = func_get_args();

		foreach ($to_load as $name)
		{
			$file_name = ucfirst(strtolower($name));
			$path = SYS_PATH . 'helpers/' . $file_name . '.php';
			
			if ( ! isset(self::$_helpers[$file_name]))
			{
				if (file_exists($path))
				{
					$overload_path = APP_PATH . 'helpers/' . Config::get('helper_overload_prefix') . $file_name . '.php';

					if (file_exists($overload_path))
					{
						self::$_helpers[$file_name] = $overload_path;
						include_once($overload_path);
					}
					else
					{
						self::$_helpers[$file_name] = $path;
						include_once($path);
					}
				}
			}
			else
			{
				// TODO: Already loaded, log this!
			}
		}
	}
}