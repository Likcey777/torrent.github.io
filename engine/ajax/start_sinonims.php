<?php

@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/inc/plugins/start_sinonims.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}



require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';
require_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';
require_once ENGINE_DIR .'/classes/parse.class.php';
include_once ENGINE_DIR.'/classes/rss.class.php';
$parse = new ParseFilter (array (),array (),1,1);

if( ! $is_logged ) die( "error" );


if (@file_exists (ENGINE_DIR ."/inc/plugins/sinonims.php") )
{


$story =  trim( convert_unicods( $_POST['story'], $config['charset'] ) );
$story = $parse->BB_Parse($story ,true);
			$story = preg_replace( "#<i>(.+?)</i>#is", "[i]\\1[/i]", $story );
			$story = preg_replace( "#<b>(.+?)</b>#is", "[b]\\1[/b]", $story );
			$story = preg_replace( "#<s>(.+?)</s>#is", "[s]\\1[/s]", $story );
			$story = preg_replace( "#<u>(.+?)</u>#is", "[u]\\1[/u]", $story );
$story = strip_tags ($story);
$story = trim(preg_replace('/[\r\n\t]{3,}/','
', $story));
	$noss = array();
preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#is", $story, $nosinonims);
foreach ($nosinonims[1] as $key => $value){
$noss['nosinonims_'.$key] = $value;
}
if (count($noss) != '')$story=strtr ($story, array_flip($noss));
include_once(ENGINE_DIR ."/inc/plugins/sinonims.php");
if (preg_match('/\[sin\]/', $story)){
$story =preg_match_all ("#\[sin\](.+?)\[\/sin\]#is", $story, $out);
$story = implode ('<hr noshade size=\"1\"/>', $out[1]);

$story = sinonims ($story, true);
/*$story =preg_replace ("#\[sin\](.+?)\[\/sin\]#ie", "sinonims('\\1',true)", $story);*/
}else{
$story = sinonims ($story, true);
}
$story = $parse->BB_Parse($story ,false);
}


if (count($noss) != '')$story=strtr ($story, $noss);
$story=strtr($story, array('[sin]'=>'', '[/sin]'=>'','[nosin]'=>'', '[/nosin]'=>'', 'biggrab '=>''));
@header( "Content-type: text/css; charset=" . $config['charset'] );

if ($_POST['key'] == 1)$id = 'sin_short'.$_POST['id'];
else $id = 'sin_full'.$_POST['id'];

if( $story ) echo "<a href=\"javascript:ShowOrHide('$id');\"><font color=\"green\">&darr;показать / скрыть &uarr;</font></a><br><div style=\"width:98%; background: #ffc;border:1px solid #9E9E9E;padding: 5px;margin-top: 7px;margin-right: 10px;\" id=".$id." >
" . $story . "</div>";

function convert_unicods($t, $to = 'windows-1251') {
	$to = strtolower( $to );

	if( $to == 'utf-8' ) {
		
		return urldecode( $t );
	
	} else {
		
		if( function_exists( 'iconv' ) ) $t = iconv( "UTF-8", $to . "//IGNORE", $t );
		else $t = "The library iconv is not supported by your server";
	
	}
	
	return urldecode( $t );
}
?>