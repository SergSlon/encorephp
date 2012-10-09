<?php namespace Encore\Core;

class Router
{
	public $directory = NULL;
	public $file = NULL;
	public $class = NULL;
	public $method = NULL;
	public $args = [];
	
	protected $_default_directory;
	protected $_callback;
	
	public function __construct()
	{
		$this->_default_directory = APP_PATH . 'controllers/';
	}
	
	public function process()
	{
		$routes = $this->_routes();

		foreach ($routes as $route => $callback)
		{
			$route = '#^'.$route.'$#';
			$this->_callback = $callback;
			
			if (preg_match($route, uri_string()))
			{
				return preg_replace_callback(
					$route,
					function($matches)
					{
						$matches = array_slice($matches, 1);
						$segments = [];
						
						foreach ($matches as $match)
						{
							$exp = explode('/', $match);
							
							foreach ($exp as $seg)
							{
								if ( ! empty($seg))
								{
									$segments[] = trim($seg, '/');
								}
							}
						}
						
						return call_user_func_array($this->_callback, $segments);
					},
					uri_string()
				);
			}
		}
		
		$segments = segments();

		foreach ($segments as $segment)
		{
			$segment = strtolower($segment);
			
			if (is_dir($this->_default_directory . $this->directory . $segment) AND $this->file === NULL)
			{
				$this->directory .= $segment . '/';
			}
			elseif (file_exists($this->_default_directory . $this->directory . ucfirst($segment) . '.php') AND $this->file === NULL)
			{
				$this->file = ucfirst($segment);
			}
			elseif ($this->method === NULL AND $this->file !== NULL)
			{
				$this->method = $segment;
			}
			else
			{
				$this->args[] = $segment;
			}
		}

		if ($this->file === NULL)
		{
			if ($this->directory !== NULL OR (file_exists($this->_default_directory . $this->directory . 'Index' . '.php') AND empty($segments)))
			{
				$this->file = 'Index';
				$this->method = isset($this->args[0]) ? $this->args[0] : 'index';
				array_shift($this->args);
			}
			else
			{
				echo '404';
				exit;
			}
		}
		
		$this->controller = $this->directory . $this->file;

		if ($this->method === NULL)
		{
			$this->method = 'index';
		}
				
		$this->loadController($this->controller, $this->method, $this->args);
	}

	protected function _routes()
	{
		require_once(APP_PATH . 'config/Routes.php');

		return $route;
	}

	public static function loadController($controller, $method = 'index', $args = [])
	{
		$file = APP_PATH . 'controllers/' . $controller . '.php';

		// Compress output?
		ob_start(Config::get('compress') ? 'ob_gzhandler' : NULL);

        if ($file !== NULL)
        {
            if (file_exists($file))
            {
                require_once($file);

                $class = '/' . $controller;
               	$class = '\Controller\\' . trim(strrchr($class, '/'), '/');

                $instance = new $class;
            }
            else
            {
                show_404();
            }
        }
        else
        {
            show_404();
        }
		
		if (method_exists($instance, '_remap'))
		{
			\Benchmark::end('execution_time');
			echo self::parse_vars($instance->_remap($method, $args));
		}
		elseif (method_exists($instance, $method))
		{
			\Benchmark::end('execution_time');
			echo self::parse_vars($instance->{$method}());
		}
		else
		{
			exit('404');
		}
		
		ob_end_flush();
	}
	
	public static function parse_vars($output)
	{
		$output = str_replace("{rendered_in}", \Benchmark::elapsed('execution_time'), $output);
		$output = str_replace("{memory_usage}", memory_usage() . 'MB', $output);

		return $output;
	}
}