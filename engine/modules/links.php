<?php
/*
=====================================================
DataLife Engine - by SoftNews Media Group
-----------------------------------------------------
http://dle-news.ru/
-----------------------------------------------------
Copyright (c) 2004,2013 SoftNews Media Group
=====================================================
Данный код защищен авторскими правами
=====================================================
Файл: links.php
-----------------------------------------------------
Назначение: Модуль перекрестных ссылок
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

//################# Определение ссылок
$links = get_vars( "links" );

if( !is_array( $links ) ) {
	$links = array ();
	
	$db->query( "SELECT * FROM " . PREFIX . "_links" );
	
	while ( $row_b = $db->get_row() ) {
		
		$links[$row_b['id']] = array ();
		
		foreach ( $row_b as $key => $value ) {
			$links[$row_b['id']][$key] = stripslashes( $value );
		}
	
	}
	set_vars( "links", $links );
	$db->free();
}

if( count( $links ) ) {

	$find = array ();
	$replace = array ();

	if ( $config['charset'] == "utf-8" ) $register .= "u";

	$host = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	foreach ( $links as $value ) {
		$words = explode("(", $value['word']);
		$register ="";

		if ($host AND $value['link'] AND $host == $value['link']) continue;

		if ( !$value['only_one'] ) $register .="i";
		if ( $config['charset'] == "utf-8" ) $register .= "u";

		if (count($words) == 1) { 
			$find[] = "#(^|\b|\s|\<br \/\>)(" . preg_quote( $value['word'], "#" ) . ")(\b|\s|!|\?|\.|,|$)#".$register;
			$replace[] = "\\1<a href=\"{$value['link']}\">\\2</a>\\3";

		}

		if (count($words) == 2) { 
			$find[] = "#(^|\b|\s|\<br \/\>)(" . preg_quote( $words[0], "#" ) . ")({$words[1]}(\b|\s|!|\?|\.|,|$)#".$register;
			$replace[] = "\\1<a href=\"{$value['link']}\">\\2\\3</a>\\4";

		}
	}

	if( count( $find ) ) {
	
		$source = preg_split( '((>)|(<))', $tpl->result['main'], - 1, PREG_SPLIT_DELIM_CAPTURE );
		$count = count( $source );
			
		for($i = 0; $i < $count; $i ++) {

			if( $source[$i] == "<" AND substr($source[$i+1], 0, 5) == 'title')  {
				$i = $i+3;
				continue;
			}

			if( $source[$i] == "<" AND substr($source[$i+1], 0, 2) == 'a ')  {
				$i = $i+3;
				continue;
			}

			if( $source[$i] == "<" or $source[$i] == "[" ) {
				$i ++;
				continue;
			}
				
			if( $source[$i] != "" ) {
	
				$source[$i] = preg_replace( $find, $replace, $source[$i] );
	
			}
		}
				
		$source = join( "", $source );
		$tpl->result['main'] = $source;
		unset($source);
	}

}
?>