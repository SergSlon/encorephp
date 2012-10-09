<?php namespace Encore\Core;

class Config
{
	protected static $_config = array();

	public static function get($item, $file = NULL)
	{
		if (isset(self::$_config[$item]))
		{
			$item = self::$_config[$item];
			$file = $file === NULL ? NULL : ucfirst($file);

			if ($file === NULL)
			{
				return reset($item);
			}
			elseif (isset($item[$file]))
			{
				return $item[$file];
			}
			else
			{
				return NULL;
			}
		}
	}

	public static function load($file)
	{
		$file_name = ucfirst(strtolower($file));
		$file_path = APP_PATH . 'config/' . ucfirst($file_name) . '.php';

		if (file_exists($file_path))
		{
			require_once($file_path);

			if ( ! isset($config) OR ! is_array($config))
			{
				show_error('The file "Config.php" does not contain a valid config array.');
			}
			else
			{
				foreach ($config as $key => $value)
				{
					self::$_config[$key]['Config'] = $value;
				}
			}
		}
		else
		{
			show_error('Unable to locate the config file: ' . $file_name);
		}
	}
}