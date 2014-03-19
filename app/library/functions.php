<?php

class Functions {

	public static function shorten($str, $length, $append = '…') {
	  $strLength = mb_strlen($str);

	  if ($strLength <= $length) {
	     return $str;
	  }

	  return mb_substr($str, 0, $length) . $append;
	}
	public static function slug($str){

		//Convert cyryllic characters to latin characters

		$englishtranslationtable = array('А' => 'a', 'а' => 'a', 'Б' => 'b', 'б' => 'b', 'В' => 'v', 'в' => 'v', 'Г' => 'g', 'г' => 'g', 'Д' => 'd',
		'д' => 'd', 'Е' => 'e', 'е' => 'e', 'Ж' => 'j', 'ж' => 'j', 'З' => 'z', 'з' => 'z', 'И' => 'i', 'и' => 'i', 'Й' => 'y', 'й' => 'y',
		'К' => 'k', 'к' => 'k', 'Л' => 'l', 'л' => 'l', 'М' => 'm', 'м' => 'm', 'Н' => 'n', 'н' => 'n', 'О' => 'o', 'о' => 'o', 'П' => 'p',
		'п' => 'p', 'Р' => 'r', 'р' => 'r', 'С' => 's', 'с' => 's', 'Т' => 't', 'т' => 't', 'У' => 'u', 'у' => 'u', 'Ф' => 'f', 'ф' => 'f',
		'Х' => 'h', 'х' => 'h', 'Ц' => 'tz', 'ц' => 'tz', 'Ч' => 'ch', 'ч' => 'ch', 'Ш' => 'sh', 'ш' => 'sh', 'Щ' => 'sht', 'щ' => 'sht', 'Ъ' => 'a', 'ъ' => 'a', 'Ю' => 'yu', 'ю' => 'yu', 'Я' => 'ya', 'я' => 'ya',
		);

		// First remove all html-tags
		$str = preg_replace("/<[^>]+>/", " ", $str);

		// Replace characters such as é, â, ä etc...
		$str = strtr($str, $englishtranslationtable);

		// Remove all unwanted characters
		$str = preg_replace("/[^a-zA-Z0-9]/", " ", $str);

		// First trim front and end of string
		$str = trim($str);

		// First correct multiple spaces
		$str = preg_replace( "/  +/", " ", $str);

		// Replace spaces with -
		$str = str_replace(" ", "-", $str);

		// Make string lowercase and return string
		return strtolower($str);
	}
	public static function youtube($url) {
		$arr = parse_url($url);
		parse_str($arr['query']);
		return "http://www.youtube.com/embed/".$v;
	}
		public static function vimeo($url) {
		$arr = parse_url($url);
		$path = explode('/', $arr['path']);
		return "//player.vimeo.com/video/".$path[1];
	}
}