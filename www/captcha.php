<?php
/**
 * DOUCO TEAM
 * ============================================================================
 * * COPYRIGHT DOUCO 2013-2014.
 * http://www.douco.com;
 * ----------------------------------------------------------------------------
 * Author:DouCo
 * Release Date: 2013-8-28
 */
 
define('IN_DOUCO', true);
define('EXIT_INIT', true);

require(dirname(__FILE__) . '/include/init.php');
require(ROOT_PATH . 'include/captcha.class.php');

//实例化验证码
$captcha = new Captcha(70, 25);

//清除之前出现的多余输入
@ob_end_clean();

$captcha->create_captcha();

?>