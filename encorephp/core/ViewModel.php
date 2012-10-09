<?php namespace View;

class ViewModel extends \Core\View
{
	public function init($data = [])
	{
		parent::init();
		
		\View::clean()->data = $data;
	}
}