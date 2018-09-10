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
class file_down
{
var $files = array();
var $file_douwn = array();
var $down_files = array();
var $down_ar = array();
var $file_txt = array();
var $eror = array();
var $torrage = 0;

function reset_url($url)
{
$value = str_replace('http://','',$url);
$value = str_replace('www.','',$value);
$value = explode('/',$value);
return reset($value);
}

function video($content)
	{
	preg_match_all ('#\[video=(.+?)\]#is',$content,$preg_array);

if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
	$item = preg_replace('#.*,(.*)#i',"\\1",$item);
if (!(in_array ($item,$this->files)))
{
$this->files[] = $item;
continue;
}
}
}
	}

function rar($content)
	{
	preg_match_all ('#\[flash=.+?\](.+?)\[\/flash\]#is',$content,$preg_array);

if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
if (!(in_array ($item,$this->files)))
{
$this->files[] = $item;
continue;
}
}
}
	}

function zip($content)
	{
	preg_match_all ('#\[(url|leech)=(.+?(\.rar|\.zip))\]#is',$content,$preg_array);
if (count ($preg_array[2]) != 0)
{
foreach ($preg_array[2] as $item)
{
if (!(in_array ($item,$this->files)))
{
$this->files[] = $item;
continue;
}
}
}
	}

function doc($content)
	{
	preg_match_all ('#\[(url|leech)=(.+?\.(doc|txt))\]#is',$content,$preg_array);
if (count ($preg_array[2]) != 0)
{
foreach ($preg_array[2] as $item)
{
if (!(in_array ($item,$this->files)))
{
$this->files[] = $item;
continue;
}
}
}
	}

function txt($content)
	{
	preg_match_all ('#\[(url|leech)=(.+?\.apk)\]#is',$content,$preg_array);
if (count ($preg_array[2]) != 0)
{
foreach ($preg_array[2] as $item)
{
if (!(in_array ($item,$this->files)))
{

$this->files[] = $item;
continue;
}
}
}
	}

function dle($content)
	{
	$content = str_replace("[", "
	[", $content);
	//echo'<textarea style="width:100%;height:240px;">'.$content.'</textarea>';
	preg_match_all ('#\[(url|leech)=(\S+engine\/download.+?)\]#is',$content,$preg_array);

if (count ($preg_array[2]) != 0)
{
foreach ($preg_array[2] as $key=>$item)
{
if (!(in_array ($item,$this->files)))
{
	//echo $item;
$this->files[] = $item;
continue;
}
}
}
	}

function tor($content)
	{
	preg_match_all ('#\[(url|leech)=(.+?\.torrent)\]#is',$content,$preg_array);
if (count ($preg_array[2]) != 0)
{
foreach ($preg_array[2] as $item)
{
if (!(in_array ($item,$this->files)))
{

$this->files[] = $item;
continue;
}
}
}
	}


function file_process($down_ar)
		{
	global $config_rss;
//var_export ($down_ar);
foreach ($down_ar as $down=>$nast)
			{
$this->files = array();
if (trim ($this->short_story) != '')
{
$this->$down ($this->short_story);
}
if (trim ($this->full_story) != '')
{
$this->$down ($this->full_story);
}


if (count($this->files) != 0){
$i=1;
foreach ($this->files as $key=>$url)
		{
$get_file = $this->donlowd_serv($url, $nast['pap'], $nast['name'],$down,$key,$i);
$this->short_story = str_replace($url, $get_file, $this->short_story);
$this->full_story = str_replace($url, $get_file, $this->full_story);
++$i;
		}
			}
		}

}

function donlowd_serv ($url,$dirs,$name='',$down='',$dle='',$cn=0)
{
global $config, $config_rss, $full_news_link, $channel_info;
if ($full_news_link == '')$full_news_link = $url;

$news = '';
$namef = array();
$name = explode('=',$name);
if ($url != ''){

if ($config['version_id'] >'9.8'){
$diru = ROOTS_DIR.'/uploads/files/'.$dirs;
$dir = str_replace('//','/',ROOTS_DIR.'/uploads/files/'.$dirs.'/'.date('Y-m').'/');
if (!is_dir($diru)) {
@mkdir($diru,0777);
chmod_pap($diru);
}
$dir_o = $dirs.'/'.date('Y-m').'/';
}else {
$dir = ROOTS_DIR.'/uploads/files/';
}

if (!is_dir($dir)) {
@mkdir($dir,0777);
chmod_pap($dir);
}
if ($down != 'dle'){
$news = basename ($url);
$arr = explode ('/',$news);
$arr = end ($arr);
$arr = explode ('_',$arr);
if (count ($arr) != 0){
	$imag_new = end ($arr);
	$imag_new = totranslit($imag_new);}
}else{$imag_new = $news = trim($dle);}
if($name[1] == '1'){
	$news= explode ('.',$news);
	$news= $this->alt_name.'.'.end ($news);
	}
if ($name[0] == '1'){$news = $this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url'])).'_'.$news;
}

if (@file_exists($dir.$news))$news = mt_rand(10,99).'_'.$news;
$new = str_replace ('%27','',$news);

if (chmod_pap($dir))
{
$last_url = parse_url($url);


if(function_exists('curl_init') ) {
$u = str_replace(' ', '%20',$url);
$prov_url = reset_url($full_news_link);

$cookie_file = ENGINE_DIR.'/cache/system/'.$prov_url.'.txt';
if($dirs!='tor'){$info = info_host($u);
if($_GET['pf'])var_export($info);

$u = $info['url'];

if ('http://'.$prov_url == trim($u,'/') or 'http://www.'.$prov_url == trim($u,'/')){$u = $url;$info['http_code'] == '200';}
if ($info['http_code'] == '404' or $info['http_code'] == '500' or $info['http_code'] == '502')return $url;
}else{$u = str_replace(' ', '%20',$url);}
//echo $u;
$ch=curl_init ();
curl_setopt($ch,CURLOPT_URL,$u);
if ($proxy == 1){
if ($config_rss['proxy_file'] == 'yes'or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}
if (trim($proxy_url) != '')curl_setopt($ch,CURLOPT_PROXY,trim($proxy_url));
}


$cookies = str_replace('|||','; ',str_replace("\r",'',stripslashes(rtrim($channel_info['cookies']))));
preg_match ('#url_aut=(.+?);#i',$cookies,$mat);
if ($mat[1]!=''){
$url_aut = 'http://'.$mat[1];
$others = str_replace ('url_aut='.$mat[1].'; ','',$others);
$others = str_replace ('redirect=index.php','redirect='.$url,$others);
$fg = str_replace ('; ','&',$others);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fg);
}
curl_setopt($ch,CURLOPT_COOKIE,$cookies);
$headers = fopen(ENGINE_DIR.'/cache/system/headers.txt','w+b');
$fp = fopen($dir.$new,'w+b');
curl_setopt ($ch,CURLOPT_FILE,$fp);
curl_setopt($ch,CURLOPT_USERAGENT,"Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0");
curl_setopt ($ch,CURLOPT_REFERER, $full_news_link);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_WRITEHEADER,$headers); // записываем заголовки
if ($GLOBALS['proxy'] == 1){
if ($config_rss['proxy_file'] == 'yes' or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}

if (trim($proxy_url) != ''){

	
	$data_proxy = explode("@", trim($proxy_url));
	if(count($data_proxy) == 3){
	curl_setopt($ch,CURLOPT_PROXY,$data_proxy[1]);
	if(!empty($data_proxy[1]))curl_setopt($ch, CURLOPT_PROXYUSERPWD,$data_proxy[0]);
	if(!empty($data_proxy[2]))curl_setopt($ch, CURLOPT_PROXYTYPE, $data_proxy[2]);
	}else{
		curl_setopt($ch,CURLOPT_PROXY,$proxy_url);
	}

	}

}
//echo $cookie_file;
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1);
curl_exec($ch);
$inf = curl_getinfo($ch);
if($_GET['f'])var_export ($inf);
curl_close($ch);
fclose ($headers);
fclose ($fp);
}else{@copy($url ,$dir.$new);}
chmod_file($headers);
chmod_file($fp);
$str = @file_get_contents(ENGINE_DIR.'/cache/system/headers.txt');
preg_match('#Content-Length:(.*)#',$str,$sze_name);
if (intval($sze_name[1]) == 0)$sze_name[1] = $inf['size_download'];
preg_match('#filename=[\'" ](.*)[\'"]#',$str,$namef);
if($namef[1]=='')preg_match('#filename=(.*)#',$str,$namef);
if($namef[1]==''){
	preg_match('#Location:(.*)#',$str,$namef);
$namef[1] = basename (trim($namef[1]));
	}
if($namef[1]=='')$namef[1] = basename (trim($u));
echo 'Size downloads file - '.mksize(filesize($dir.$new));

if (trim($sze_name[1]) == filesize($dir.$new) and filesize($dir.$new) != 0)
	{
if($namef[1]!=''){
	$replace = array('"', '\'',';');
	$namef = preg_replace('#\[.*\]#i','',basename(str_replace($replace, '', $namef[1])));
	$namef = trim(totranslit ($namef),'.');
}

	if($namef != '' and $namef[1]!=''){
if($name[1] == '1'){
$namef = explode ('.',$namef);
$namef = $this->alt_name.'.'.end ($namef);
}
$news = $namef;
if ($name[0] == '1'){
$namef = $this->reset_url(($config_rss['http_url']!=''?$config_rss['http_url']:$config['http_home_url'])).'_'.$namef;
}
if(trim($dle) == 'torrent')$namef.= '.torrent';

$namef_ar = explode ('.',$namef);
$namef_end =end($namef_ar);
if ($cn > 0)$namef = str_replace(".".$namef_end, "", $namef).'_'.$cn.'.'.$namef_end;

if (@file_exists($dir.$namef))$namef = mt_rand(10,99).'_'.$namef;
	@rename ($dir.$new,$dir.$namef);
	chmod_file($dir.$namef);
	//@unlink(ENGINE_DIR.'/cache/system/headers.txt');
	}else{
		$namef = $new;
		}
if ($this->torrage == 1 and preg_match('/torrent/i',$namef))$serv_url = $this->d_torrage('/uploads/files/'.$dir_o.$namef);
else $serv_url = ($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']).str_replace('//', '/','uploads/files/'.$dir_o.$namef);

$this->down_files[$namef]= trim($dir_o.$namef, "/");
	}
}
}else{$serv_url = $this->eror[] = $url;
@unlink($dir.$new);
}
if (trim($serv_url==''))$serv_url = $this->eror[] = $url;
//echo $serv_url;
return $serv_url;
}

function d_torrage($get_file)
	{global $config_rss,$config;
$file = ROOTS_DIR.$get_file;
$url = 'http://torrage.com/upload.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_USERAGENT,"Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0");
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_POSTFIELDS,array("torrent" => "@$file") );
if ($GLOBALS['proxy'] == 1){
if ($config_rss['proxy_file'] == 'yes' or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}

if (trim($proxy_url) != ''){

	
	$data_proxy = explode("@", trim($proxy_url));
	if(count($data_proxy) == 3){
	curl_setopt($ch,CURLOPT_PROXY,$data_proxy[1]);
	if(!empty($data_proxy[1]))curl_setopt($ch, CURLOPT_PROXYUSERPWD,$data_proxy[0]);
	if(!empty($data_proxy[2]))curl_setopt($ch, CURLOPT_PROXYTYPE, $data_proxy[2]);
	}else{
		curl_setopt($ch,CURLOPT_PROXY,$proxy_url);
	}

	}

}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$otvet_ot_server = curl_exec($ch);
curl_close($ch);
echo'<textarea style="width:100%;height:240px;">'.@htmlspecialchars( $otvet_ot_server,ENT_QUOTES ,$config['charset']).'</textarea>';
preg_match('#<a href=".+?">(.+?)<\/a><\/p>#',$otvet_ot_server,$out);
if ($out[1]!=''){
	@unlink($file);
	return $out[1];
}else return str_replace('http:/','http://',str_replace('//', '/',($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']).$get_file));
	}

}

function mksize($bytes) {
	if ($bytes < 1000 * 1024)
		return number_format($bytes / 1024, 2) . " kB";
	if ($bytes < 1000 * 1048576)
		return number_format($bytes / 1048576, 2) . " MB";
	if ($bytes < 1000 * 1073741824)
		return number_format($bytes / 1073741824, 2) . " GB";
	if ($bytes < 1000 * 1099511627776)
		return number_format($bytes / 1099511627776, 2) . " TB";
	if ($bytes < 1000 * 1125899906842620)
		return number_format($bytes / 1125899906842620, 2) . " PB";
	if ($bytes < 1000 * 1152921504606850000)
		return number_format($bytes / 1152921504606850000, 2) . " EB";
}

?>