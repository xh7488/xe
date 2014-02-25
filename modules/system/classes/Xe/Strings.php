<?php
namespace Xe;
class Strings{
	/**
	 +----------------------------------------------------------
	 * 清除html,换行，空格类
	 +----------------------------------------------------------
	 */
	static function substr($str, $length, $charset = "utf-8")
	{
		$str = trim($str); //清除字符串两边的空格
		$str = strip_tags($str, ""); //利用php自带的函数清除html格式
		$str = preg_replace("/\t/", "", $str); //使用正则表达式匹配需要替换的内容，如：空格，换行，并将替换为空。
		$str = preg_replace("/\r\n/", "", $str);
		$str = preg_replace("/\r/", "", $str);
		$str = preg_replace("/\n/", "", $str);
		$str = preg_replace("/ /", "", $str);
		$str = preg_replace("/&nbsp; /", "", $str); //匹配html中的空格
		$str = trim($str); //清除字符串两边的空格
	
		if (function_exists("mb_substr"))
		{
			$substr = mb_substr($str, 0, $length, $charset);
		}
		else
		{
			$c['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$c['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			preg_match_all($c[$charset], $str, $match);
			$substr = join("", array_slice($match[0], 0, $length));
		}
	
		return $substr;
	}
	static function truncate($string,$len,$postfix='...')
	{
		$string=trim($string);
		$str_len=mb_strlen($string);
		return ($str_len>$len)?mb_substr($string, 0,$len).$postfix:$string;
	}

}