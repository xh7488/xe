<?php
namespace Dou\admin\Controller;
use Xe\Controller\Controller;
class Auth extends Controller{
	public function __construct() {
		\Xe\Sess::start();
		if (empty ( $_SESSION ['loginuser'] )) {
			header ( "location: /admin/login" );
			exit();
		}
	}
}