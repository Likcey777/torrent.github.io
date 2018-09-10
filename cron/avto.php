<?php
/*
=====================================================
 Скрипт модуля Rss Grabber

 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2009-2010
=====================================================
*/

define('DATALIFEENGINE',true);
extract($_REQUEST,EXTR_SKIP);
define('ROOT_DIR',dirname(dirname(__FILE__)));
define('ENGINE_DIR',ROOT_DIR .'/engine');

include_once ENGINE_DIR.'/data/rss_config.php';
$module_info = array ('name'=>'RSS Grabber','host'=>'rss-grabber','zone'=>'de','version'=>'3.6.9');
if(intval($config_rss['memory_limit'])!=0)@ini_set ('memory_limit',$config_rss['memory_limit'].'M');
else @ini_set ('memory_limit', "128M");
ignore_user_abort(true);
@set_time_limit (0);
@ini_set ('max_input_time',864000);
@ini_set ('post_max_size',"20M");
@ini_set ('upload_max_filesize',"20M");
@ini_set ('max_execution_time',864000);
@ini_set ('output_buffering','off');
@ob_end_clean ();
clearstatcache ();
ob_implicit_flush (TRUE);
@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

$dle_plugins = ENGINE_DIR .'/classes/';
$rss_plugins = ENGINE_DIR .'/inc/plugins/';


@include_once (ENGINE_DIR . '/data/config.php');
if ($config['version_id']  > '10.1') date_default_timezone_set ( $config['date_adjust'] );
if ($config['http_home_url'] == "") {
	$config['http_home_url'] = explode ( "index.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset ( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];}
require_once ROOT_DIR .'/language/Russian/grabber.lng';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';
require_once ENGINE_DIR .'/classes/parse.class.php';
$parse = new ParseFilter (array (),array (),1,1);
require_once ENGINE_DIR .'/inc/plugins/core.php';
require_once ENGINE_DIR .'/inc/plugins/strip_tags_smart.php';
require_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
require_once ENGINE_DIR .'/inc/plugins/rss.functions.php';
require_once ENGINE_DIR .'/inc/plugins/rss.parser.php';
require_once ENGINE_DIR .'/inc/plugins/classes.file.php';
@include_once ENGINE_DIR.'/data/rss_config.php';
if ( file_exists( $rss_plugins.'include/torrent.php') ) {
require_once $rss_plugins.'include/bencode.php';
require_once $rss_plugins.'include/torrent.php';
}
if ( file_exists( $rss_plugins.'include/class.apivk.php') ){
require_once $rss_plugins.'include/class.apivk.php';
}

//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$cat_info = get_vars ( "category" );

if (! is_array ( $cat_info )) {
	$cat_info = array ();

	$db->query ( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
	while ( $row = $db->get_row () ) {

		$cat_info[$row['id']] = array ();

		foreach ( $row as $key => $value ) {
			$cat_info[$row['id']][$key] = stripslashes ( $value );
		}

	}
	set_vars ( "category", $cat_info );
	$db->free ();
}

$pidFile = dirname(__FILE__).'/robot.txt';
$time_sec=time();
$time_file = @filemtime($pidFile);
$id_file = @file_get_contents($pidFile);
$id_n = false;
if (!empty($_GET['id']) and intval($id_file) == intval($_GET['id']) and !empty($id_file)) $id_n = true;

$time=$time_sec - intval($time_file);

if ($time < 600 and !$id_n)
  die("ERROR: script is already running, try later...\n");
if ($fp = fopen($pidFile,'w')) {
if(!empty($_GET['id']))	fwrite($fp,intval($_GET['id']));
else fwrite($fp,time());
fclose($fp);
@chmod($pidFile,0666);
}else{ die("ERROR: Cannot register script's PID\n");}


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
 <head>
<meta content="text/html; charset='.$config['charset'].'" http-equiv="content-type" />
 </head>

 <body>';
if ($config_rss['google'] != '' and !preg_match("#http\:\/\/translate\.google#i",$config_rss['google'])) echo '<script src="http://google.com/jsapi?key='.$config_rss['google'].'" type="text/javascript"></script>';

$start1=gettimeofday();
$i=0;

for ($x=0;$x<2;$x++){
if($grabber == true) break;
$rss_cron_array = get_vars('cron.rss');
if (!$rss_cron_array)$rss_cron_array = array();
$rss_cron_data = get_vars('cron.rss.data');
if (!$rss_cron_data) $rss_cron_data = array();
if (intval($_GET['id']) != 0){$ka = "id='".intval($_GET['id'])."'";$rss_cron_array = array();$rss_cron_data = array();}
else {$ka = "allow_auto = '1'";}
$sql_result = $db->query("SELECT * FROM ".PREFIX ."_rss WHERE $ka ORDER BY RAND()");
$pnum = $db->num_rows ($sql_result);
$found = false;
//echo $i.' == '.$pnum;
if($i == $pnum ) break;
if ($config_rss['get_proxy'] == "yes") get_proxy();
while ($channel_info = $db->get_row($sql_result)) {
$grabber = false;
if ($pnum == 0) break;
if ( count($rss_cron_array) >= $pnum ) $rss_cron_array = array();
$channel_id = $channel_info['id'];
$found = in_array($channel_id,$rss_cron_array);
if (!$found) {
++$i;
//var_export ($rss_cron_array);
$rss_cron_array[] = $channel_id;
$data_cron = time() - $rss_cron_data[$channel_id];
$dnast = explode ('=',$channel_info['dnast']);
if (intval($dnast[16]) != 0)$cron_data = $dnast[16]*60;
	else $cron_data = 0;
if ($data_cron >= $cron_data)
	{
$rss_cron_data[$channel_id] = time();
set_vars('cron.rss.data',$rss_cron_data);
set_vars('cron.rss',$rss_cron_array);
if ( count($rss_cron_array) >= $pnum ) $rss_cron_array = array();
if($config_rss['get_prox'] = $tab_id)$grabber = start_process($channel_id,$channel_info);
}else{
$cron_dop_time = $cron_data - $data_cron;
	echo "<B><font color=#993300>№".$channel_info['xpos']." ".$channel_info['title']."</font></B> оставшееся время для следующего запуска".date( "i:s",$cron_dop_time)."<br />";

set_vars('cron.rss',$rss_cron_array);
}
	}
//if ($pnum == $i)$grabber = true;
if ( !$found and intval($config_rss['cron_cikl']) == 0) $grabber = true;
if($grabber == true) break;
}

}

if( function_exists('memory_get_peak_usage') ) {
$mem_usage = memory_get_peak_usage(true);
if ($mem_usage < 1024)
echo $mem_usage." bytes";
elseif ($mem_usage < 1048576)
$memory_usage = round($mem_usage/1024,2)." кб";
else
$memory_usage = round($mem_usage/1048576,2)." мб";
}

$end1=gettimeofday();
$totaltime1 = (float)($end1['sec'] - $start1['sec']) + ((float)($end1['usec'] - $start1['usec'])/1000000);

echo "<br /><br /> Использовано памяти - ".$memory_usage."<br />Время выполнения - ".$totaltime1;

echo ' </body>
</html>';


$fp = fopen($pidFile,'w');
fwrite($fp, '');
fclose($fp);
@chmod($pidFile,0666);
@unlink($pidFile);

 function start_process($channel_id,$channel_info)
{
global $db,$parse,$story,$config,$config_rss,$leech_shab,$news_link,$title,$dimages,$hide_leech,$dop_nast,$link,$charik;

$xfields = xfieldsload();

foreach ($xfields as $key=>$val)
{
	$xfields_loads[$val[0]] = $val;
}

$rss_plugins = ENGINE_DIR .'/inc/plugins/';
$id_list = array();
$rss_files = explode('==',$channel_info['files']);
$end_title = explode ('==',$channel_info['end_title']);
$dop_sort = explode ('=',$channel_info['short_story']);
$dop_nast = explode ('=',$channel_info['dop_nast']);
$ctp = explode ('=',$channel_info['ctp']);
$start_template = stripslashes ($channel_info['start_template']);
$finish_template = explode ('|||',stripslashes ($channel_info['finish_template']));
$sart_cat = explode('|||', $channel_info['sart_cat']);
$dnast = explode ('=',$channel_info['dnast']);
$leech_shab = $dnast[25];
$cookies = str_replace('|||',"; ",str_replace("\r","",stripslashes(rtrim($channel_info['cookies']))));
$allow_main	= $channel_info['allow_main'];
$dates = explode ('=',$channel_info['date']);
$allow_mod	= $channel_info['allow_mod'];
$allow_comm	= $channel_info['allow_comm'];
$allow_rate	= $channel_info['allow_rate'];
if ($allow_mod == 1){$approve = "0";}else{$approve = "1";}
$hide_leech = explode('=',$channel_info['end_short']);
$rss = $channel_info['rss'];
$config_rss['convert'] = (intval($dnast[32]) == 1 ?'yes': 'no');
$charsets = $start_pos = $dimages = '';
$_POST['title'] = html_entity_decode($news_title);
$sinonims_val = $dop_sort[3];
$crosspost_val = $dnast[26];
$twitter_val = $dnast[28];
$rewrite = $hide_leech[3];
$allow_more = intval ($channel_info['allow_more']);

if (trim($dop_nast[14]) != '' or $dop_nast[14] != '0')$charsets = explode("/",$dop_nast[14]);
if ($rss == 1){$ctp[0] = 0;$ctp[1] = 0;}

$rss_parser = new rss_parser();
$i = 0;
if ($ctp[1] > 0 and intval($ctp[0]) == 0 ) $ctp[0] = '1';

if (intval($ctp[0]) > intval($ctp[1])  ){
$ctp[0] = $ctp[0] * '-1';
$ctp[1] = $ctp[1] * '-1';
}

for ($cv=$ctp[0];$cv<=$ctp[1];$cv++)
{
if (intval($dop_nast[20]) != '0' ){
$cvp = abs($cv * $dop_nast[20] - $dop_nast[20] );
if ($cvp == 0 ) $cvp = intval($dop_nast[25]);
}else{$cvp = abs($cv);}

if (intval($config_rss['news_limit']) !=0) {
if (intval($dnast[19]) !=0 )$config_rss['news_limit'] = $dnast[19];
if ($i == $config_rss['news_limit']) break;
}

if ($cvp != 0 and $rss == 0)
{
if ($channel_info['full_link'] == ''){
$rows = $channel_info['url'].'/page/'.$cvp.'/';
}else {
$rows = str_replace ('{num}',$cvp,$channel_info['full_link']);
}



$URL = get_urls(trim($rows));
$kol_cachefile = $ctp[1] - $ctp[0] ;
if (abs($kol_cachefile) > 2){
$rows_cachefile = array_map("trim", file(ROOT_DIR.'/cron/'.$URL['host'].'.txt'));
if (in_array (trim($rows),$rows_cachefile))  continue;
}else{ if (@file_exists(ROOT_DIR.'/cron/'.$URL['host'].'.txt')) openz(ROOT_DIR.'/cron/'.$URL['host'].'.txt',"");}
if ($cvp == 0 or $cvp == 1 ) $pg = 'Главная страница';else $pg = 'Страница '.$cvp;
echo '<table width="100%">
 <tr>
		<td	><a href="'.$rows.'" target="_blank"><b><font color="orange">'.$pg.'</font></b></a></td>
</tr>
</table>';
}else{$URL = get_urls(trim($channel_info['url']));}
if ($rss == 1){
$rss_parser->default_cp = $dop_nast[14];
$rss_result = $rss_parser->Get ($channel_info['url'],$dop_nast[2]);
}else{
$URLitems =	get_full ($URL[scheme],$URL['host'],$URL['path'],$URL['query'],$cookies,$dop_nast[2], $dop_sort[8],$dop_sort[21]);
if ($URL['host'] == "vk.com") {
	$rss_result = $URLitems["response"];
	unset ($rss_result[0]);
	$charik = 'utf-8';
	$rss = 2;
}else{

if (trim($dop_nast[14]) == ''or $dop_nast[14] == '0')$charik = charset($URLitems);else $charik = $charsets[0];
if ($channel_info['ful_start'] != ''){
$row_ful_start = explode("\r\n",$channel_info['ful_start']);


if ($row_ful_start[1] != ''){$URLitems = get_page ($URLitems,$row_ful_start[1]);
$URLitems = $URLitems[0];
}
$rss_result = get_page ($URLitems,$row_ful_start[0]);
	}else{
		$rss_result = get_dle($URLitems);
		}
}
}
$now_kol = false;

if ($rss_result) {
if ($rss == 1){
$rss_result = $rss_result['items'];
}
if($config_rss['reverse'] == 'no')$rss_result = array_reverse($rss_result,true);
foreach ($rss_result as $item) {

$dimages = '';
$cp_output = "";
$xdoe_files = array();
$xdoe = array();
if (intval($config_rss['news_limit']) !=0) {
if (intval($dnast[19]) !=0 )$config_rss['news_limit'] = $dnast[19];
if ($i == $config_rss['news_limit']) break;
}

//////////parse0

$tags_tmp = '';
$stop = false;
if (intval($dop_nast[19]) != 0)sleep ($dop_nast[19]);
unset ($title);
unset ($news_link);
unset ($news_tit);
unset ($short_story);
unset ($full_story);
unset ($xfields_array);
if ($rss == 1){
$news_tit = rss_strip ($item['title']);
$short_story = $item['description'];
$news_link = stripslashes ($item['link']);
$tags_tmp = rss_strip ($item['category']);
}elseif ($rss == 2){
$news_tit = rss_strip ($item['title']);
if ($charik != strtolower($config['charset']) ) $news_tit = convert($charik,strtolower($config['charset']),$news_tit);
$short_story = "[img]".$item['image_medium']."[/img]";
$news_link = stripslashes (rss_strip($item['link']));
$tags_tmp = rss_strip ($item['category']);
}else{
if ($charik != strtolower($config['charset']) AND $item!= "") $item = convert($charik,strtolower($config['charset']),$item);
if (trim($channel_info['start_title']) != '' and $dnast[22] != 1)$news_tit = strip_tags_smart(get_full_news($item,$channel_info['start_title']));
if ($channel_info['end_link'] != 1){
$short_story = get_short_news ($item,$channel_info['start_short']);
}else{
$short_story = get_full_news ($item,$channel_info['start_short']);
}
if (trim($channel_info['sart_link'])==''){
$tu_link = get_link ($item);
$news_link = 'http://'.$URL['host'].'/index.php?newsid='.$tu_link;
}else{
$news_lin = get_full_news($item,$channel_info['sart_link']);
$news_link = full_path_build ($news_lin,$URL['host']);
}

}

if ($rss == 1){
if (trim ($news_link) == '')
{
$news_link = stripslashes ($item['guid']);
}
}
if ($dnast[27] == 1){
$link_cachefile = array_map("trim",@file(ENGINE_DIR."/inc/plugins/files/cachefile.txt"));
if (!in_array ($news_link,$link_cachefile))openz(ENGINE_DIR."/inc/plugins/files/cachefile.txt",$news_link."\n",'a');
else continue;
}
$db->close;
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if (trim($end_title[2]) != '' and trim($news_tit) != '') $news_tit =rss_strip( relace_news ($news_tit,$end_title[2],$end_title[3]));
$alt_name = $db->safesql (totranslit( stripslashes( $news_tit ) ));
$safeTitle  = $db->safesql($news_tit);
$link = 'get_url'.$story;
$news_link = full_path_build ($news_link,$URL['host'],$URL['path']);
if ($dop_sort[12] == 0) {$where = ' LIMIT 1';}
elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%' OR title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
else {if ($safeTitle != ''and $alt_name != '')$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";
else $where = ' LIMIT 1';
}
//echo $where.'<br>';
//echo $db->num_rows ($sql_result).'<br>';

$sql_result = $sql_Title = $db->query('SELECT * FROM '.PREFIX .'_post'.$where);
if ($db->num_rows ($sql_result) == 0 or $news_tit ==''or $hide_leech[3] == 1 or $dop_sort[12] == 0)
{

include $rss_plugins.'include/init.php';


if (trim($news_title) == '') continue;
$db->close;
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if ($dop_sort[12] == 0) {$where = " WHERE title like '%".$news_title."%'";}
elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%' OR title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
else {$where = " WHERE title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
$sql_result = $sql_Title = $db->query("SELECT * FROM ".PREFIX ."_post".$where);
//echo $db->num_rows ($sql_result).'<br>';
$db_num_rows = $db->num_rows ($sql_Title);
if ($db->num_rows ($sql_result) == 0 or $hide_leech[3] == 1 or $dop_sort[12] == 0)
{




///////parse

include $rss_plugins.'include/parser.php';
///////parse

if ($db->num_rows ($sql_result) > 0 and ($hide_leech[3] == 1 ) and $news_id == '')$allow_news = false;
if ($dnast[30] == 1 and $db->num_rows ($sql_result) == 0){$allow_news = false;}


if ($allow_news)
{

include $rss_plugins.'include/xfields.php';

$stop = false;
if (empty($category_list) and $dop_sort[6] == 0) $stop = true;
if (preg_match("#{frag#",$short_story) or preg_match("#{frag#",$full_story) or preg_match("#{frag#",$news_title)) $stop = true;




if ($dop_sort[17] == 1 or intval($dop_sort[20]) == 1 or trim($full_story) != ''){
if ((trim($short_story) != '' or $dop_sort[0] == 1) and trim($news_title) != '' and !$stop) {

/////////////////stop
$_POST['title'] = html_entity_decode($news_title);
if ($dnast[9] == 1)$meta_title = $db->safesql(trim($channel_info['metatitle'].' '.$news_title));else $meta_title = $db->safesql(trim(str_replace('{zagolovok}', $news_title,$channel_info['metatitle'])));
$serv = $channel_info['load_img'];
$tegs=$tags_tmp;
include $rss_plugins.'include/addnews.php';
$i++;
/////////////////stop


}
}
}
}
}
if (trim ($channel_info['title']) != '')
{
$tit = stripslashes (strip_tags_smart ($channel_info['title']));
if (50 < e_str ($tit))
{
$tit = e_sub ($tit,0,50) .'...';
}
}
else
{
$tit = 'Без названия...';
}
if ($title!= '')$news_tit = $title;

if ($z <= 1)$z = 1;
if ($e <= 0)$e = 0;
if ($p <= 0)$p = 0;
if ($_SERVER['HTTP_USER_AGENT'] != ''){
echo(($news_id)?$z.'. <b style="color:blue;">'.$tit.'</b> &#x25ba;<font color=red><b>'.$news_tit.'</b></font><br>':$z.'. <b style="color:green;">'.$tit.'</b> &#x25ba;<b>'.$news_tit.'</b></font><br>');

if($cp_output != '')echo $cp_output.'<br>' ;

}

($news_id)? ++$e : ++$p;
unset ($news_id);
++$z;
}
if($i == 0 and sizeof($rss_items) and $kol_cachefile > 2){ openz(ROOT_DIR.'/cron/'.$URL['host'].'.txt',trim($rows)."\n", 'a');}
}else{
echo "<B><font color=#993300>№".$channel_info['xpos']." ".$channel_info['title']."</font></B><br />Канал не сграбблен проверьте настройки<br /><br />";
return false;
}

}

if ($e == 0 and $p == 0)return false;
echo "<B><font color=#993300>№".$channel_info['xpos']." ".$tit."</font></B><br />Добавленно ".$e." новостей<br />Пропущено ".$p." новостей<br /><br />";
if ($e == 0)return false;
$db->close;
$rss_lenta = new image_controller ();
if( $approve == '1'and $dop_sort[4] == 1 and @file_exists(ENGINE_DIR .'/inc/plugins/ping/pingsite.txt')) {
$rss_lenta->download_host ($config['http_home_url'].'engine/ajax/rss_lenta.php','nn='.$e);
include ( ENGINE_DIR .'/inc/plugins/ping/grabberping.php');
}
if($config_rss['sitemap'] == 'yes' and @file_exists(ENGINE_DIR .'/inc/plugins/ping/sitemap.php')) {
include ( ENGINE_DIR .'/inc/plugins/ping/sitemap.php');
}
if(@file_exists(ROOT_DIR .'/crosspost.php') and $dnast[26] == 1 )$cros_html = $rss_lenta->get_host ($config['http_home_url'].'crosspost.php');
echo $cros_html;
$db->close;
return true;
};

?>