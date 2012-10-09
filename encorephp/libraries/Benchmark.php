<?php

class Benchmark
{
	use \Singleton;

	protected $marks = [];

	public static function start($key)
	{
		self::instance()->marks[$key]['start'] = microtime();

		return self::instance();
	}

	public static function end($key)
	{
		self::instance()->marks[$key]['end'] = microtime();

		return self::instance();
	}

	public static function mark($key)
	{
		self::instance()->marks[$key] = microtime();

		return self::instance();
	}

	public static function elapsed($start_key, $end_key = NULL)
	{
		if (is_null($end_key))
		{
			$start = self::instance()->marks[$start_key]['start'];
			$end = self::instance()->marks[$start_key]['end'];
		}
		else
		{
			$start = self::instance()->marks[$start_key];
			$end = self::instance()->marks[$end_key];
		}

		$total_time = round(($end - $start), 4);

		return $total_time;
	}
}