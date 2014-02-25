<?php
namespace Dou\Controller;
use \Xe\Controller\Controller;
// use Xe\Sess;
class Auth extends Controller {
	public function __construct() {
		\Xe\Sess::start();
		if (empty ( $_SESSION ['loginuser'] )) {
			header ( "location: /login" );
			exit();
		}
	}
}