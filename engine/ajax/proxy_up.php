<?php

@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

@include(ENGINE_DIR.'/data/rss_config.php');
include ENGINE_DIR . '/data/config.php';
$rss_plugins = ENGINE_DIR .'/inc/plugins/';
require_once $rss_plugins.'core.php';
require_once $rss_plugins.'rss.classes.php';
require_once $rss_plugins.'rss.functions.php';
@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';
if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/inc/plugins/start_sinonims.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}



if($config_rss['url_proxy'] == '')$config_rss['url_proxy']='http://spys.ru/proxylist/';
$link = get_urls($config_rss['url_proxy']);
if($config_rss['get_prox'] = $tab_id)$proxy_content = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$proxy);
preg_match_all('!(\d+\.\d+\.\d+\.\d+<script type="text\/javascript">document.write\(.+?\)<\/script>)(.+?<\/font><\/td><\/tr>)!',$proxy_content,$tran);
if (!sizeof($tran[1]))preg_match_all('!(\d+\.\d+\.\d+\.\d+:\d+)!',$proxy_content,$tran);
else preg_match('!<\/table><script type="text\/javascript">(.*)<\/script>!',$proxy_content,$an);
/*
foreach($tran[2] as $hg){
$hg=str_replace('<font class=spy14>S</font>','S',$hg);
$hg=str_replace('<font class=spy2>','<font class=spy1>',$hg);
$hg=str_replace('<font class=spy5>','<font class=spy1>',$hg);

preg_match_all('!<font class=spy1>(.+?)<\/font>!',$hg,$tra);

if($tra[1][2][0]<2 and $tra[1][2][1]=='.')var_export($tra);

}
*/
if ($an[1]!='')
{
$coc=explode(";",$an[1]);
$kl=array();
foreach($coc as $vl)
{
if(strpos($vl,"^")){
	$kl1[]='('.preg_replace('!=\d\^!', '^', $vl).')';
	$kl2[]=preg_replace('!.*=(\d)\^.*!', "\\1", $vl);
	}

}

}
$tr = '';
foreach ($tran[1] as $value)
		{
$value = str_replace('<script type="text/javascript">document.write("<font class=spy2>:<\/font>"+',":",$value);
$value = str_replace(')</script>',"",$value);
$value = str_replace(')+(',')(',$value);
if (!sizeof($kl))$value = str_replace($kl1,$kl2,$value);
$tr .= $value.'
';
}
//echo $tr;
$writable = chmod_file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
openz(ENGINE_DIR.'/inc/plugins/files/proxy.txt',$tr);

@header( "Content-type: text/css; charset=" . $config['charset'] );


if (trim($tr) != '' and $writable){echo '<div style="width:100%;"><font color="green">'.$lang_grabber['msg_proxy_yes'].'</font> <font color="red">'.date( "Y-m-d H:i:s",filectime(ENGINE_DIR ."/inc/plugins/files/proxy.txt")).' обновлён </font></div>';}else{echo '<font color="green">Запись файла '.$file.'</font> '.$file_status; }



?>