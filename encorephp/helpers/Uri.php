<?php defined('SYS_PATH') OR exit('No direct script access');

function uri_string()
{
	$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
	
	$exp = explode('/', $uri, 2);
	
	if (file_exists($exp[0]))
	{
		return isset($exp[1]) ? $exp[1] : '';
	}
	else
	{
		return $uri;
	}
}

function segments()
{	
	$segment_array = explode('/', uri_string());

	$segments = [];
	$seg = 1;
	foreach ($segment_array as $key => $segment)
	{
		if ( ! empty($segment))
		{
			if ($key !== 0 OR ! file_exists($segment))
			{
				$segments[$seg] = $segment;
				$seg++;
			}
		}
	}
		
	return $segments;
}

function segment($key)
{
	$segments = segments();
	return isset($segments[$key]) ? $segments[$key] : FALSE;
}

function total_segments()
{
	return count(segments());
}