<?php
namespace Xe;
class Sess
{
	static function start()
	{
		session_set_cookie_params(1440,'/');
		//session_set_save_handler(new DBSession());
		//debug(ini_get('session.save_path'));
		session_start();
		
	}
}