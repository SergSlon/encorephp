<?php defined('SYS_PATH') OR exit('No direct script access');

function alias($class, $alias = NULL)
{
	if (is_array($class))
	{
		foreach ($class as $_class => $_alias)
		{
			class_alias($_class, $_alias);
		}
	}
	elseif ($alias !== NULL)
	{
		class_alias($class, $alias);
	}
}

/**
 * Set HTTP Status Header
 *
 * @access	public
 * @param	int		the status code
 * @param	string
 * @return	void
 */

function set_status_header($code = 200, $text = '')
{
	$stati = array(
						200	=> 'OK',
						201	=> 'Created',
						202	=> 'Accepted',
						203	=> 'Non-Authoritative Information',
						204	=> 'No Content',
						205	=> 'Reset Content',
						206	=> 'Partial Content',

						300	=> 'Multiple Choices',
						301	=> 'Moved Permanently',
						302	=> 'Found',
						304	=> 'Not Modified',
						305	=> 'Use Proxy',
						307	=> 'Temporary Redirect',

						400	=> 'Bad Request',
						401	=> 'Unauthorized',
						403	=> 'Forbidden',
						404	=> 'Not Found',
						405	=> 'Method Not Allowed',
						406	=> 'Not Acceptable',
						407	=> 'Proxy Authentication Required',
						408	=> 'Request Timeout',
						409	=> 'Conflict',
						410	=> 'Gone',
						411	=> 'Length Required',
						412	=> 'Precondition Failed',
						413	=> 'Request Entity Too Large',
						414	=> 'Request-URI Too Long',
						415	=> 'Unsupported Media Type',
						416	=> 'Requested Range Not Satisfiable',
						417	=> 'Expectation Failed',

						500	=> 'Internal Server Error',
						501	=> 'Not Implemented',
						502	=> 'Bad Gateway',
						503	=> 'Service Unavailable',
						504	=> 'Gateway Timeout',
						505	=> 'HTTP Version Not Supported'
					);

	if ($code == '' OR ! is_numeric($code))
	{
		show_error('Status codes must be numeric', 500);
	}

	if (isset($stati[$code]) AND $text == '')
	{
		$text = $stati[$code];
	}

	if ($text == '')
	{
		show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
	}

	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	if (substr(php_sapi_name(), 0, 3) == 'cgi')
	{
		header("Status: {$code} {$text}", TRUE);
	}
	elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
	{
		header($server_protocol." {$code} {$text}", TRUE, $code);
	}
	else
	{
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}

/**
 * General Error Page
 *
 * This function takes an error message as input
 * (either as a string or an array) and displays
 * it using the specified template.
 *
 * @access	private
 * @param	string|array the message
 * @param	string the heading
 * @param 	int	the status code
 * @param	string the template name
 * @return	string
 */
function show_error($message, $heading = 'An Error Occurred', $status_code = 500, $template = 'general')
{
	set_status_header($status_code);
		
	if (is_array($message))
	{
		$array = $message;
		
		$message = isset($array['message']) ? $array['message'] : NULL;
		$message .= isset($array['file']) ? ('<br><strong>File: </strong>' . $array['file']) : NULL;
		$message .= isset($array['line']) ? ('<br><strong>Line: </strong>' . $array['line']) : NULL;
	}
	
	ob_start();
	require(APP_PATH . 'views/errors/' . $template . '.php');
	$buffer = ob_get_contents();
	ob_end_flush();
	exit;
}

function show_404()
{
	set_status_header(404);
	show_error('The page you requested was not found', '404 Not Found');
}

function memory_usage($format = 'MB')
{
	return round(memory_get_usage()/1024/1024, 2);
}