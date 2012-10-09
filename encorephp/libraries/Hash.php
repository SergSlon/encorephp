<?php require_once(SYS_PATH . 'Classes/phpass/PasswordHash.php');

class Hash
{
	use \Singleton;
	
	protected $level = 8;
	protected $portable = TRUE;
	protected $phpass;
	
	public function init()
	{
		$this->phpass = new PasswordHash($this->level, $this->portable);
	}
	
	public static function encrypt($pass)
	{
		return self::instance()->phpass->HashPassword($pass);
	}
	
	public static function check($pass, $stored)
	{
		return self::instance()->phpass->CheckPassword($pass, $stored);
	}
	
	public static function level($level)
	{
		if ($level <= 8 AND $level >= 1)
		{
			self::instance()->level = (int) $level;
		}
//		else
//		{
//			show_error('The encryption level is not valid');
//		}
	}
	
	public static function portable($value)
	{
		self::instance()->portable = (bool) $value;
	}
}