<?php namespace Core;

class Loader
{
    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $ns The namespace to use.
     */
    public static function register()
    {
        // Register the autoloaders
        spl_autoload_register(array('\Core\Loader', 'library'));
        spl_autoload_register(array('\Core\Loader', 'model'));
        spl_autoload_register(array('\Core\Loader', 'core'));
        spl_autoload_register(array('\Core\Loader', 'controller'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public static function unregister()
    {
        // Unregister the autoloaders
        spl_autoload_unregister(array('\Core\Loader', 'library'));
        spl_autoload_unregister(array('\Core\Loader', 'model'));
        spl_autoload_unregister(array('\Core\Loader', 'core'));
        spl_autoload_unregister(array('\Core\Loader', 'controller'));
    }

    public static function library($class)
    {
        extract(self::_getClass($class));

        $core_path = SYS_PATH . 'libraries/' . $file_name . $class . '.php';
        $app_path = APP_PATH . 'libraries/' . $file_name . $class . '.php';

        if (file_exists($core_path))
        {
            require_once($core_path);

            if (file_exists($app_path))
            {
                require_once($app_path);
            }
        }
        elseif (file_exists($app_path))
        {
            require_once($app_path);
        }
    }

    public static function controller($class)
    {
        extract(self::_getClass($class));

        $core_path = SYS_PATH . 'core/Controller/' . $class . '.php';
        
        if (file_exists($core_path))
        {
            require_once($core_path);
        }
    }

    public static function model($class)
    {
        extract(self::_getClass($class));

        $path = APP_PATH . 'models/' . $class . '.php';
        
        if (file_exists($path))
        {
            // Include the file
            require_once($path);
        }
    }

    public static function core($class)
    {
        extract(self::_getClass($class));

        $core_path = SYS_PATH . 'core/' . $class . '.php';
        $overload_path = APP_PATH . 'core/' . $class . '.php';
        
        if (file_exists($core_path))
        {
            require_once($core_path);

            if (file_exists($overload_path))
            {
                require_once($overload_path);
            }
            else
            {
                alias('Encore\Core\\' . $class, 'Core\\' . $class);
            }
        }
    }

    protected static function _getClass($class)
    {
        if (($lastNsPos = strripos($class, '\\')) !== FALSE)
        {
            $namespace = substr($class, 0, $lastNsPos);

            return array(
                'class' => substr($class, $lastNsPos + 1),
                'file_name' => str_replace('\\', '/', $namespace) . '/'
            );
        }
        else
        {
            return array(
                'class' => $class,
                'file_name' => NULL
            );
        }
    }
}