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
require_once ENGINE_DIR .'/inc/plugins/pclzip.php';
if ($config['version_id'] >='8.0')require_once ENGINE_DIR.'/inc/include/functions.inc.php';
else require_once ENGINE_DIR.'/inc/functions.inc.php';

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
if ($_POST['url'] == 'dwn')$obj->UnArchive($_POST['dwn']);
else $obj->DoDownloadFile($_POST['ver'], $_POST['bul'], $_POST['key']);


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
	
	function UnArchive($file) {
global $config, $db, $config_rss, $lang_grabber;

$ret = 0;
$install = false;
$ful_pap = array();
if (file_exists($file)) {
$DIR = $this->getParam('DIR','/','string',false);
			classCreator::createPclZip();
			$archive = new PclZip($file);
foreach ($archive->listContent() as $zip_file)
	{
if ($zip_file['folder'] == true){
$ful_pa = array();
$ful_pa['chmod'] = '0'.@decoct(@fileperms($_SERVER['DOCUMENT_ROOT'].$DIR.$zip_file['filename'])) % 1000;
$ful_pa['filename'] = $_SERVER['DOCUMENT_ROOT'].$DIR.$zip_file['filename'];
if (chmod_pap(ROOT_DIR.$DIR.$zip_file['filename']) == false){ $msg = '<input type="button" class="edit" value="'.$lang_grabber['update_dwn'].'" onClick="document.location.href = \''.$DIR.'uploads'.$DIR.'files'.$DIR.'uploads_grabber.zip\'" />';
}else { $msg = '<input type="button" class="edit" value="'.$lang_grabber['update_ok'].'!" onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />';}
$ful_pap[] = $ful_pa;
}
}
foreach ($archive->listContent() as $zip_file)
	{
if ($zip_file['folder'] == false){
if ($zip_file['filename']=='install/db.php')$install = true;
$fuls = ROOT_DIR.$DIR.$zip_file['filename'];
$Name .= $fuls . "<br>";
if (file_exists($fuls))unlink_file($fuls);
}
}
			$ret = $archive->extract($v_options[PCLZIP_OPT_PATH] = ROOT_DIR, PCLZIP_OPT_REPLACE_NEWER);
			}
			unlink_file($file);
			if ($ret == 0) {
				
				echo $lang_grabber['update_zip_er']." : ".$archive->errorInfo(true);
			}
			else {
if ($install == true and @file_exists(ROOT_DIR.'/install/db.php')){
			require_once ROOT_DIR.'/install/db.php';
			foreach($db_query as $table)
			{
				$db->query($table, false);
			}
				unlink_file(ROOT_DIR.'/install/db.php');
				unlink_file(ROOT_DIR.'/install');
			}

				echo $msg;
			}

	}
	
	
	function DoDownloadFile($ver, $bul, $key) {
global $config, $db, $config_rss, $lang_grabber;
		$DIR = $this->getParam('DIR','/','string',false);
		$patch = ROOT_DIR.$DIR.'uploads'.$DIR.'files';
		if(chmod_pap($patch) == false) {echo $lang_grabber['wr_eror']; exit();}
		$name = $patch.$DIR.'uploads_grabber.zip';
unlink_file($name);
 if(is_writable($patch)){
$url = 'http://rss-grabber.ru/grabbers/updates.php';
$fg = 'ver='.$ver.'&bul='.$bul.'&key='.$key.'&cod=utf8';
$rh = curl_init();
curl_setopt($rh,CURLOPT_URL,$url);
curl_setopt($rh,CURLOPT_HEADER,0);
curl_setopt($rh,CURLOPT_CONNECTTIMEOUT,120);
$fp =fopen($name,'w+b');
curl_setopt ($rh,CURLOPT_FILE,$fp);
curl_setopt($rh,CURLOPT_TIMEOUT,120);
curl_setopt($rh,CURLOPT_ENCODING,'');
@curl_setopt($rh,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($rh,CURLOPT_POST,1);
curl_setopt($rh,CURLOPT_POSTFIELDS,$fg);
curl_setopt($rh,CURLOPT_FAILONERROR,1);
curl_setopt($rh, CURLOPT_HTTPHEADER, array('Expect:'));
curl_exec($rh);
$error = curl_error($rh);
curl_close($rh);

fclose ($fp);
			if (!$error) {
				if (filesize($name) != 0) {
					echo '<input onclick="grabber_updates(\''.$name.'\'); return false;" class="edit" type="button" value=" '.$lang_grabber['update_yy'].'" />&nbsp;<input type="button" class="edit" value="'.$lang_grabber['update_dwn'].'" onClick="document.location.href = \''.$DIR.'uploads'.$DIR.'files'.$DIR.'uploads_grabber.zip\'" />';
				}else{@unlink ($name); echo '<font size="2" color="red"><b>'.$lang_grabber['no_lis'].'</b></font>';}
			}else{@unlink ($name); echo $lang_grabber['update_er'].": $error";}
				}
	}


		
}

function unlink_file($file) {
global $config_rss;
if ($config_rss['bak'] == 'yes')@rename($file, $file.".bak");
else @unlink ($file);
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