<?PHP
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

require_once ENGINE_DIR.'/data/config.php';
require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
@include_once ENGINE_DIR.'/data/rss_config.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';
if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/update_grabber.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

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

$obj = new FileView;
@header("HTTP/1.0 200 OK");
@header("HTTP/1.1 200 OK");
@header("Cache-Control: no-cache, must-revalidate, max-age=0");
@header("Expires: 0");
@header("Pragma: no-cache");
@header("Content-type: text/css; charset=".$config['charset']);

$obj->UnArchive();


class FileView {

			function getParam($name,$default,$type,$addsl = true) {

		$arr = $_REQUEST;

		$val = (isset($arr[$name]) && $arr[$name]!='') ? $arr[$name] : $default;
		settype($val,$type);

		if ($addsl) {

			if (!get_magic_quotes_gpc()) $val = addslashes($val);
		}
		else {

			if (get_magic_quotes_gpc()) $val = stripslashes($val);
		}

		return $val;
	}
	
	function UnArchive() {
global $config, $db, $config_rss, $lang_grabber;
		$DIR = $this->getParam('DIR','/','string',false);
		$patch = ROOT_DIR.$DIR.'uploads'.$DIR.'files';
		if(chmod_pap($patch) == false) {echo $lang_grabber['wr_eror']; exit();}
		$file = $patch.$DIR.'uploads_grabber.zip.bak';
$ret = 0;
if (file_exists($file)) {
$file = bak_file($file);
$DIR = $this->getParam('DIR','/','string',false);
			classCreator::createPclZip();
			$archive = new PclZip($file);
foreach ($archive->listContent() as $zip_file)
	{
if ($zip_file['folder'] == false){
$fuls = ROOT_DIR.$DIR.$zip_file['filename'];
$Name .= $fuls . "<br>";
if (file_exists($fuls))unlink_file($fuls,true);
if (file_exists($fuls.".bak"))bak_file($fuls.".bak");
}
}
			unlink_file($file);
				echo $lang_grabber['update_bak_ok'];
	}else echo $lang_grabber['update_zip_er'];
	}

}

function unlink_file($file,$bak=false) {
global $config_rss;
if ($config_rss['bak'] == 'yes' and $bak==false)@rename($file, $file.".bak");
else @unlink ($file);
}
function bak_file($file) {
$new_file = str_replace(".bak","",$file);
if (@rename($file, $new_file))return $new_file;
else return false;
}


function chmod_pap($file) {

        if(file_exists($file)){
        if(is_writable($file)){
            return true;
        }
        else{
            @chmod($file, 0777);
            if(is_writable($file)){
                return true;
            }else{
                @chmod($file, 0755);
                if(is_writable($file)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        }else{return false;}
}


?>