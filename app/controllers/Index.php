<?php namespace Controller;

class Index extends Controller
{
	public function index()
	{
		View::load('Index')->render();
	}

	public function helloAgain()
	{
		echo 'Hello world';
	}
}