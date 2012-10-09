<?php namespace Encore\Core;

class View
{
	use \Singleton;
	
	protected $view_file;
	protected $view_model;
	protected $data = [];
	
	public function init()
	{
		require_once(SYS_PATH . 'core/ViewModel.php');
	}
	
	public static function load($file)
	{
		if (file_exists(APP_PATH . 'views/' . $file . '.php'))
		{
			self::instance()->view_file = APP_PATH . 'views/' . $file . '.php';
			
			$split = explode('/', $file);
			self::instance()->view_model = end($split);
		}
		else
		{
			show_error('Could not load view file: ' . $file);
		}
		
		return self::instance();
	}

	public static function set($variable, $value = NULL)
	{
		if (is_array($variable))
		{
			foreach ($variable as $key => $value)
			{
				self::instance()->data[$key] = $value;
			}
		}
		else
		{
			self::instance()->data[$variable] = $value;
		}

		return self::instance();
	}
	
	public static function render($data = [], $return = FALSE)
	{
		// Set any data that was passed
		self::instance()->set($data);
		
		// Start the output buffer
		ob_start();
		
		// Extract the data variables
		extract(self::instance()->data);
		
		// Include the view file
		include(self::instance()->view_file);
		
		$view_model = '\View\\' . self::instance()->view_model;

		if (class_exists($view_model, FALSE))
		{
			ob_end_clean();
			return new $view_model(self::instance()->data);
		}
		
		// Get the contents and flush
		$buffer = ob_get_contents();
		ob_end_clean();
		
		if ($return)
		{
			return Router::parse_vars($buffer);
		}
		else
		{
			echo Router::parse_vars($buffer);
		}
	}
	
}