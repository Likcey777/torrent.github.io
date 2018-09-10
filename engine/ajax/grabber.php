<?php
/*
=====================================================
 Скрипт модуля Rss Grabber 3.6.9
 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2011
=====================================================
*/

@error_reporting(7);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');

include ENGINE_DIR.'/data/config.php';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/grabber.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}
if ($config['version_id'] >='8.0')require_once ENGINE_DIR.'/inc/include/functions.inc.php';
else require_once ENGINE_DIR.'/inc/functions.inc.php';

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = totranslit( $_COOKIE['selected_language'], false, false );

	if (@is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';
if ( file_exists( ROOT_DIR .'/language/'.$selected_language.'/grabber.lng') ) {
		@require_once ROOT_DIR .'/language/'.$selected_language .'/grabber.lng';
	} else die("Language file not found");
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
$data = @file('http://rss-grabber.ru/grabber_update.php');
if (str_replace('.','',trim($data['0'])).trim($data['1']) > str_replace('.','',$_POST['moduleversion']).$_POST['modulebuild']) {

$dats .= '<input onclick="grabber_updates_down(); return false;" class="edit" type="button" value=" '.$lang_grabber['update_yes'].' '.trim($data['0']).' build'.trim($data['1']).'" />';
}else{
$dats = "";
}

@header("HTTP/1.0 200 OK");
@header("HTTP/1.1 200 OK");
@header("Cache-Control: no-cache, must-revalidate, max-age=0");
@header("Expires: 0");
@header("Pragma: no-cache");
@header("Content-type: text/css; charset=".$config['charset']);

if (!strlen($dats)) echo $lang_grabber['update_no']; else echo $dats;

?>