<?php
if( ! defined( 'DATALIFEENGINE' ) ) {
die( "Hacking attempt!" );
}

function weblog_ping($data, $site_name= '' , $news_url= '') 
{global $config;
	if ($news_url != '' and $site_name != ''){
if (strtolower($config['charset']) != 'utf-8')$site_name = iconv("WINDOWS-1251", "UTF-8//IGNORE", $site_name);
if (function_exists(xmlrpc_encode_request)){
$request = xmlrpc_encode_request('weblogUpdates.ping', array($site_name, $news_url) );
}else{

$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
"<methodCall>\n".
"\t<methodName>weblogUpdates.ping</methodName>\n".
"\t<params>\n".
"\t\t<param>\n".
"\t\t\t<value>".$site_name."</value>\n".
"\t\t</param>\n".
"\t\t<param>\n".
"\t\t\t<value>".$news_url."</value>\n".
"\t\t</param>\n".
"\t</params>\n".
"</methodCall>";
}
}else{$request = '';}
  $eror = array();
  $result = array();
  $eror_files = array ();
  foreach ($data as $id => $url) {

$url = str_replace("www.", "", $url);

$http = "";

if(strpos($url, "http")){
$http = "http://";
}

// Парсим хост, путь и порт
$parse = parse_url($http.$url);
if(!isset($parse['host'])) return false;
$host = $parse['host'];
$port = isset($parse['port'])?$parse['port']:80;
$path  = isset($parse['path'])?$parse['path']:'/';

// Открываем соединение
if($ping = @fsockopen($host, $port, $errno, $errstr, 15)){

fputs ($ping, "POST ".$path." HTTP/1.0\r\n".
"User-Agent: Modpingation\r\n".
"Host: ".$host."\r\n".
"Content-Type: text/xml\r\n".
"Content-length: ".strlen($request)."\r\n\r\n");
fputs ($ping, $request);

if ($news_url != '' and $site_name != ''){
$response = '';
while(!feof($ping)) {
$response.= fgets($ping, 128);
if (strpos($response, "</xmldatauct>") !== false) break; 
}
	preg_match ("#<name>flerror</name>[^<]*<value>([\s\S]+?)</value>#i", $response, $out);
$symbols = array("\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "¬", "#", ";", ":", "-", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', " ");
$out[1] = str_replace($symbols, "", strip_tags($out[1]));
if (count($out) != '0' and $out[1] == '0')$result[$id] = 'ok';
else $eror[] = $id;
}else{
$result[$id] = $result[$id] = 'ok';
  }
fclose ($ping);
}else $eror[] = $id;
  }

foreach ($eror as $id)
	{
$eror_files[] = $data[$id];
unset ($data[$id]);
}

if (sizeof($eror_files)){
$eror_files = implode ("\n",$eror_files); 
$data = implode ("\n",$data); 
openz(ENGINE_DIR."/inc/plugins/ping/pingeror.txt",$eror_files,$wr='w+' );
openz(ENGINE_DIR."/inc/plugins/ping/pingsite.txt",$data,$wr='w+' );
}
return $result;
}

?>