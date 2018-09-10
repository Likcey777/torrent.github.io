<?php

/*
=====================================================
 Скрипт модуля Rss Grabber 3.6.9
 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2009-2013
=====================================================
*/
if( !defined( 'DATALIFEENGINE') ) {
die( "Hacking attempt!");
}

include_once ENGINE_DIR .'/inc/plugins/backup.php';
include_once ENGINE_DIR .'/inc/include/functions.inc.php';
include_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
include_once ENGINE_DIR .'/inc/plugins/rss.functions.php';

chmod_pap(ROOT_DIR .'/backup/');
if (!is_dir(ROOT_DIR .'/backup/rss')){
@mkdir(ROOT_DIR .'/backup/rss',0777);
}
chmod_pap(ROOT_DIR .'/backup/rss');

if ($action == 'save_channel')
{
$ids = $_POST['channel'];
if (count ($ids) == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
}

$name = '/backup/rss/'.$config['charset'].'_'.date("Y-m-d_H-i").'_rss.zip';
foreach ($ids as $id)
{
	$cop = '';
$values = array();
$copy = array();
$copys = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
$number = $copys['xpos'];
$copys['id'] =  '';
if (count ($ids) == 1) $name = '/backup/rss/'.$config['charset'].'_'.reset_ur($copys['url']).'_'.date("Y-m-d_H-i").'.zip';
foreach ($copys as $key => $value){
	$values[] = $key;
$copy[$key] = "'".$db->safesql(stripslashes($value))."'";
}

$copss = implode(',', $copy);
$valuess = implode(',', $values);

$cop = $copss.'++++'.$valuess.'
';
$handler = fopen(ROOT_DIR.$name,'ab');
fwrite($handler,$cop);
fclose($handler);

if (trim ($copy['title']) != '')
{$title = stripslashes (strip_tags ($copy['title']));
if (50 <e_str ($title))
{
$title = e_sub ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
$mgs .= $lang_grabber['channel'].' '.$number.'<font color="green">"'.$title.' | '.$copy['url'].'"</font> <font color="red">'.$lang_grabber['copy_channel_ok'].'</font><br />';


}
if (@file_exists(ROOT_DIR .$name) ){
$mgs .= '<br /><br /><A href="'.$name.'" ><STRONG>'.$lang_grabber['ex_file'].'</STRONG></A>';
msg ($lang_grabber['info'],$lang_grabber['ex_kanal'], $mgs ,$PHP_SELF .'?mod=rss');
}else{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');}return 1;}
$cop=$module_info['host'][2].$lang_grabber['post'];
if ($action == 'save_up_channel')
{
echoheader ($lang_grabber['imp_file'],'');
opentable ('');
tableheader ($lang_grabber['imp_kanal']);
echo '<table width="100%">
<tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr>
<tr>
		<td style="padding:4px" class="option">
		'.$lang_grabber['help_imp_exp'].'
		</td>
		<td align=middle >
<form action="?mod=rss" method=post enctype="multipart/form-data" name="form" id="form">
<input type="hidden" name="action" value="backup">
<input type=file class="edit" size="40" name=uploadfile><br />
<input type=url class="edit" size="50" name=urlfile><br /><br />
<input type=submit class="btn btn-success" value='.$lang['db_load_a'].'>

</form><br /></td>
</tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr>
	<tr>
		<td colspan="2"><div class="hr_line"></div></td>
	</tr>
<tr><td style="padding:4px" class="option">
<input type="button" class="btn btn-warning" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></td></tr>
</table>';
closetable ();
echofooter ();
exit();
}




if( $action == 'backup') {
global $lang_grabber;
 //var_export ($_POST);
$uploadfile = ROOT_DIR .'/engine/cache/bakup.txt';
if($_POST['save'] != 'save'){
$uploadfilename = $_FILES['uploadfile']['name'];
if($_POST['urlfile'] != '') {
	$DoDownloadFile = DoDownloadFile($_POST['urlfile'], $uploadfile );
	$uploadfilename = basename($_POST['urlfile']);
}
if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'],$uploadfile) or $DoDownloadFile)
{
$uploadfile = file($uploadfile);
$tpls = '<form method=post name="news_form" id="news_form" action="?mod=rss">
<input type="hidden" name="action" value="backup" />
<table width="100%">
<tr><td style="padding:4px" class="navigation">
'.$lang_grabber['help_imp_kan'].'<td></tr><tr>
<td align="right" colspan="3"  class="navigation">'.$lang_grabber['kan_file'].': <b>'.$uploadfilename.'</b>
</td></tr>
	<tr>
		<td colspan="3"><div class="hr_line"></div></td>
	</tr>
<tr><td class="navigation"><center><b>'.$lang_grabber['name_canal'].'</b></center></td><td width="2%" ><input type="checkbox"	name="all_id" id="all_id" onclick="checkAll(document.news_form.id)" title="'.$lang_grabber['lang_add'].'" /></td><td width="2%" ><input type="checkbox" name="all_upd" id="all_upd" onclick="checkAlls(document.news_form.upd)" title="'.$lang_grabber['lan_upload'].'" /></td></tr>
	<tr>
		<td colspan="3"><div class="hr_line"></div></td>
	</tr>
</table>
<table width="100%">
';



foreach ($uploadfile as $value)
	{
if (preg_match("#(utf-8|windows-1251)#i",$uploadfilename,$charik) ) {
if ($charik[1] != strtolower($config['charset']) and trim($value) != '') {
	$value = convert($charik[1],strtolower($config['charset']),$value);
	}
}
$key = explode("++++", $value);
$ks = explode ("','", $key[0]);
$vs = explode (",", $key[1]);
foreach ($vs as $k=>$v)
		{
if ($ks[$k] == "'")$ks[$k] = '';
$rss_array[$v] = $ks[$k];
}
//var_export ($rss_array);
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE url = '".$rss_array['url']."'");
if (trim ($rss_array['title']) != '')
{
$title = trim(stripslashes (strip_tags ($rss_array['title'])), "'");
if (50 <e_str ($title))
{
$title = e_sub ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
if ($rss_dub['id'] == '')
		{
$tpls .= '<tr><td style="padding:4px"><font color="green">'.$title.'</font></td><td width="2%" ><input type="checkbox"	name="id[]" id="id" checked value="'.$rss_array['xpos'].'" title="'.$lang_grabber['lang_add'].'" /></td><td width="2%" ><input type="checkbox" disabled title="'.$lang_grabber['lan_upload'].'" /></td></tr>';
}else{
$tpls .= '<tr><td style="padding:4px"><font color="red">'.$title.'</font></td><td width="2%" ><input type="checkbox" id="id" name="id[]" value="'.$rss_array['xpos'].'" title="'.$lang_grabber['lang_add'].'" /></td><td width="2%" ><input type="checkbox" name="upd[]" id="upd" checked value="'.$rss_dub['id'].'" title="'.$lang_grabber['lan_upload'].'" /></td></tr>';
}
$tpls .= '<td background="engine/skins/images/mline.gif" height=1 colspan=6></td>';
	}

$tpls .= '</table><div class="hr_line"></div>
<input type="hidden" name="charset_file" value="'.$charik[1].'" />
<input type="hidden" name="save" value="save" />
<input type="hidden" name="filename" value="'.$uploadfilename.'" />
		<input align="left" class="btn btn-success" type="submit" value=" '.$lang_grabber['lang_dal'].' " >&nbsp;
		<input type="button" class="btn btn-warning" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /><br />
</form><br />';
echoheader ($lang_grabber['imp_file'],'');
opentable ('');
tableheader ($lang_grabber['imp_kanal']);

echo"
<script>
function checkAll(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==''?'#E8F9E6':'';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          ='#E8F9E6';
      }document.news_form.all_id.checked=true;
document.news_form.all_upd.checked=false;
    }
}

function checkAlls(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==''?'#FFCC00':'';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          ='#FFCC00';
      }document.news_form.all_upd.checked=true;
document.news_form.all_id.checked=false;
    }
}

</script>
";
echo $tpls;

closetable ();
echofooter ();
exit();
}else{
@unlink($_FILES['uploadfile']['tmp_name']);
msg($lang_grabber['info'],$lang_grabber['info'],$lang['images_uperr_3'],$PHP_SELF .'?mod=rss');
}

}else{

if (count($_POST['id']) != 0 or count($_POST['upd']) != 0){
$uploadfile = file($uploadfile);
/*var_export ($_POST);

if (count($_POST['upd']) == 0)exit();*/

foreach ($uploadfile as $value)
	{

if( $_POST['charset_file'] != '' ) {
if ($_POST['charset_file'] != strtolower($config['charset']) and trim($value) != '') {
	$value = convert($_POST['charset_file'],strtolower($config['charset']),$value);
	}
}

$rss_array = array();
$rss_upd = array();
$key = explode("++++", $value);
$ks = explode ("','", $key[0]);
$vs = explode (",", $key[1]);
foreach ($vs as $k=>$v)
		{
if ($ks[$k] == "'")$ks[$k] = '';
$rss_array[$v] = $ks[$k];
}



if (count($_POST['upd']!=0) and isset($_POST['upd']))
		{
foreach($_POST['upd'] as $kls){
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '".$kls."'");
if ($rss_dub['url'] == $rss_array['url']){
$db->query( 'DELETE FROM '.PREFIX ."_rss WHERE id ='$kls'");
$db->query ('INSERT INTO '.PREFIX ."_rss ({$key[1]})VALUES ({$key[0]})");
$id = $db->insert_id();
$db->query( 'UPDATE '.PREFIX ."_rss SET id='$kls', xpos = '".$rss_dub['xpos']."' WHERE id ='$id'");
}
}
		}

if (count($_POST['id']!=0) and isset($_POST['id']))
		{
foreach($_POST['id'] as $kls)
		{
if ($kls == $rss_array['xpos']){
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE url = '".$rss_array['url']."'");
if ($rss_dub['id'] == '')
$db->query ('INSERT INTO '.PREFIX ."_rss ({$key[1]})VALUES ({$key[0]})");
$id = $db->insert_id();
$sql_result = $db->query ('SELECT url FROM '.PREFIX .'_rss' );
$pnum = $db->num_rows ($sql_result);
$db->query( 'UPDATE '.PREFIX ."_rss SET xpos = '$pnum' WHERE id ='$id'");
		}
	}
}

$msg_f .= '<font color="green">'.$rss_array['title'].'|'.$rss_array['url'].'</font><br>';

	}

msg($lang_grabber['info'],$lang_grabber['info'],'<font color="red"><strong>'.$lang_grabber['imp_ok'].'</strong></font><br><br>'.$msg_f,$PHP_SELF .'?mod=rss');

}else{msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');}
}

return 1;
}

function DoDownloadFile($url, $uploadfile ) {
global $config, $db, $config_rss, $lang_grabber;
@unlink($uploadfile);
$rh = curl_init();
curl_setopt($rh,CURLOPT_URL,$url);
curl_setopt($rh,CURLOPT_HEADER,0);
curl_setopt($rh,CURLOPT_CONNECTTIMEOUT,120);
$fp =fopen($uploadfile,'w+b');
curl_setopt ($rh,CURLOPT_FILE,$fp);
curl_setopt($rh,CURLOPT_TIMEOUT,120);
@curl_setopt($rh,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($rh,CURLOPT_USERAGENT,'Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0');
curl_setopt($rh,CURLOPT_FAILONERROR,1);
curl_exec($rh);
$error = curl_error($rh);
curl_close($rh);
fclose ($fp);
			if (!$error) {return true;
				}else{
				return false;
					}
	}

		function reset_ur($url)
	{
		$value = str_replace("http://", "", $url);
		$value = str_replace("www.", "", $value);
		$value = explode("/", $value);
		return reset($value);
	}


?>
