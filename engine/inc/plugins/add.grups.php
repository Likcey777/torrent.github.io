<?php
/*
=====================================================
 Скрипт модуля Rss Grabber 3.6.8
 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2009-2010
=====================================================
*/

if( !defined( 'DATALIFEENGINE') ) {
die( "Hacking attempt!");
}

if ($_REQUEST['doaction'] == 'del')
{

$db->query ('DELETE FROM '.PREFIX ."_rss_category where id='".$_REQUEST['id']."'");

$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss where category like '%=".$_REQUEST['id']."'");
while ($row = $db->get_row ($sql_result))
{
$row['category'] = str_replace('='.$_REQUEST['id'], '=0', $row['category']);
$db->query ('UPDATE '.PREFIX .('_rss set category='.$row['category'].' WHERE id = \''.$row['id'].'\''));
}
msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['del_grups'].'</b>', $lang_grabber['grup_del'],$PHP_SELF .'?mod=rss&action=grups');

}

if ($_POST['sort'] == $lang_grabber['sort_grups'])
{
$i=1;
foreach ($_POST['kanal'] as $k=>$v)
{
$db->query ('UPDATE '.PREFIX .('_rss_category set kanal='.((int)$v).' WHERE id = \''.((int)$k) .'\''));
$i++;
}
msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['sorts_grups'].'</b>', $lang_grabber['grup_sort'],$PHP_SELF .'?mod=rss&action=grups');
return 1;
}

if ($_POST['add'] == $lang_grabber['lang_add'] and trim($_POST['title']) != ''){

$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss_category where title='".$_POST['title']."'");
if ($db->num_rows ($sql_result) == 0){
	$db->query( 'INSERT INTO '.PREFIX ."_rss_category (osn, title, kanal, color)VALUES('".intval($_POST['rss_priv'])."', '".$_POST['title']."', '".count($_POST['kanal'])."+1', '".trim($_POST['mycolor'])."')");
msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['add_grups'].'</b>', $lang_grabber['grup_add'],$PHP_SELF .'?mod=rss&action=grups');
}else{msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['add_grups'].'</b>', $lang_grabber['err_g_add'],$PHP_SELF .'?mod=rss&action=grups');}
}




if ($_POST['rid'] == $lang_grabber['lang_izmen'])
{
	$db->query( 'UPDATE ' . PREFIX . "_rss_category set title='".$_POST['title']."', osn='".intval($_POST['rss_priv'])."', color='".trim($_POST['mycolor'])."' where id='".$_POST['id']."'");
	msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['grups_rided'].'</b>', $lang_grabber['rided_grup'],$PHP_SELF .'?mod=rss&action=grups');
}


//Главная
echoheader($lang_grabber['lang_grups'], '');
opentable ('<b>'.$lang_grabber['lang_grups'].'</b>');
$df = array();
$sql_category = $db->query ('SELECT * FROM '.PREFIX ."_rss");
while ($row = $db->get_row ($sql_category))
{
$category = explode ('=', $row['category']);

$df[$category[1]][] = $row['id'];

}


$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss_category ORDER BY kanal asc");
$channel_inf = array();
$entries = array();
$color_d= array();
while ($row = $db->get_row ($sql_result))
{

	if (trim($row['color']) == '')$row['color'] = '#f2f2f2;';
$run[0] = '-- '.$lang_grabber['osnov_grups'].' --';
if ($row['osn'] == '0'){
$color_d[$row['id']] = $row['color'];
            $entries[$row['id']][0] = "
<tr style=\"background-color: ".$row['color'].";height: 10px;\">
		<td width=\"5%\" style=\"padding:1px\" align=\"center\"><input type=\"text\" name=\"kanal[".$row['id']."]\" value=\"".$row['kanal']."\" class=\"edit\" align=\"center\" size=\"3\" /></td>
<td>
<b>{$row['title']} (".count($df[$row['id']]).")</b>
</td>
<td width=\"5%\" style=\"background-color: ".$row['color'].";\"><a href=\"?mod=rss&action=grups&doaction=exp&id=".$row['id']."\" title=\"{$lang_grabber['lang_rided']}\"><img src=\"engine/skins/grabber/images/notepad.png\" border=\"0\"></a>&nbsp;&nbsp;<a onClick=\"javascript:confirmdelete('".$row['id']."'); return(false)\" href=\"#\" title=\"{$lang_grabber['lang_del']}\"><img src=\"engine/skins/images/delete.png\" border=\"0\"></a></td>
</tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
$channel_inf[$row['id']][$row['id']] =  $row['title'];
}else{

$entries[$row['osn']][$row['id']] = "
<tr>
		<td width=\"5%\"  style=\"padding:1px;background-color: ".$color_d[$row['osn']].";\" align=\"center\"><input type=\"text\" name=\"kanal[".$row['id']."]\" value=\"".$row['kanal']."\" class=\"edit\" align=\"center\" size=\"3\" /></td>
<td  style=\"background-color: ".$row['color'].";\">
-- <b>{$row['title']} (".count($df[$row['id']]).")</b>
</td>
<td width=\"5%\" style=\"background-color: ".$row['color'].";\"><a href=\"?mod=rss&action=grups&doaction=exp&id=".$row['id']."\" title=\"{$lang_grabber['lang_rided']}\"><img src=\"engine/skins/grabber/images/notepad.png\" border=\"0\"></a>&nbsp;&nbsp;<a onClick=\"javascript:confirmdelete('".$row['id']."'); return(false)\" href=\"#\" title=\"{$lang_grabber['lang_del']}\"><img src=\"engine/skins/images/delete.png\" border=\"0\"></a></td>
</tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
$channel_inf[$row['osn']][$row['id']] = '-- '. $row['title'];
}
}
foreach($channel_inf as $value)
{
	if (count($value) != '0'){
foreach($value as $kkey=>$key)
{
$run[$kkey] = $key;
}
	}
}

echo <<< HTML
<script src="engine/skins/grabber/js/iColorPicker.js" type="text/javascript"></script>
<form method="post" name="rss_sort" id="rss_sort">
<table width="100%">
<tr>
<th width="5%" align="center" class="navigation" style="padding:4px">№</th>
<th align="center" class="navigation" style="padding:4px">{$lang_grabber['lang_name']}</th>
<th width="5%" align="center" class="navigation" style="padding:4px">{$lang_grabber['lang_go']}</th>
</tr>
</table>
HTML;
unterline ();
echo <<< HTML
<table width="100%">
HTML;
if ($config['version_id'] < '8.5'){
echo '<script type="text/javascript" src="engine/ajax/menu.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/menu.js"></script>';
}


echo <<< HTML
<script language="javascript" type="text/javascript">
<!--
function MenuBuild( m_id , led_action){

var menu=new Array()
var lang_action = "";

menu[0]='<a onClick="document.location=\'?mod=rss&action=grups&doaction=exp&id=' + m_id + '\'; return(false)" href="#">{$lang_grabber['lang_rided']}</a>';
menu[1]='<a onClick="javascript:confirmdelete(' + m_id + '); return(false)" href="#">{$lang_grabber['lang_del']}</a>';

return menu;
}
function confirmdelete(id){
var agree=confirm("{$lang_grabber['help_lang_del']}");
if (agree)
document.location="?mod=rss&action=grups&doaction=del&id="+id;
}
//-->
</script>
HTML;
if (count($entries) != '0'){
foreach($entries as $value)
{
echo implode('', $value);
}
}else{echo  '<tr><td colspan=3><center>-- '.$lang_grabber['no_grup_s'].' --</center></td></tr>';}

if ($_REQUEST['doaction'] != 'exp')
{
echo '
 <tr><td colspan=3 class="unterline"></td></tr>
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;" colspan=2><div class="navigation" ><b>'.$lang_grabber['add_g_pogrup'].'</b></div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right" ></td>
</tr>
 <tr><td colspan=3><div class="unterline"></div></td></tr>
';
	echo "<tr><td height=1 colspan=3 ><br />
<input type=\"text\" class=\"edit\" name=\"title\" value=\"\">
   <select name=\"rss_priv\" class=\"load_img\">
   ".sel ($run)."
   </select>
<input id=\"mycolor\" name=\"mycolor\" type=\"text\" value=\"#f2f2f2\" class=\"iColorPicker edit\" />
<input name=\"add\" type=\"submit\" class=\"btn btn-success\" value=\"".$lang_grabber['lang_add']."\" >
<input name=\"sort\" type=\"submit\" class=\"btn btn-primary\"	value=' ".$lang_grabber['lang_s_grup']." '/>
</td></tr>";

}
echo "</table>
</form>";

if ($_REQUEST['doaction'] == 'exp')
{
	if ($_POST['rid'] != $lang_grabber['lang_izmen']){
$sql_result = $db->super_query ('SELECT * FROM '.PREFIX ."_rss_category where id='".$_REQUEST['id']."'");
echo '
<table width="100%">
 <tr><td colspan=3 class="unterline"></td></tr>
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><b>'.$lang_grabber['riad_g_podrup'].'</b></div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right"></td>
    </tr>
</table>
<div class="unterline"></div>
';
echo "
<div align=\"left\">
<font color='green'><b>{$sql_result['title']}</b></font><br />
<form method=post name=\"addnews\" id=\"addnews\">
<input type=hidden name=\"id\" value=\"{$sql_result['id']}\">
<input class=\"edit\" name=\"title\" value=\"{$sql_result['title']}\">
<input id=\"mycolor\" name=\"mycolor\" type=\"text\" value=\"{$sql_result['color']}\" class=\"iColorPicker edit\" />
   <select name=\"rss_priv\" class=\"load_img\">
   ".sel ($run,$sql_result['osn'])."
   </select> 
<input name=\"rid\" type=\"submit\" class=\"edit\" style=\"background: #FFF; font-size:8pt;\" value=\"".$lang_grabber['lang_izmen']."\" > <input type=\"button\" class=\"edit\"	value=' ".$lang_grabber['lang_sbros']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss&action=grups\"' />
</form>  </div>
";
	}
}

if ($_REQUEST['doaction'] != 'exp')
{
echo "<br /><div class=\"unterline\"></div>
<div align=\"left\">
<input type=\"button\" class=\"btn btn-warning\"	value=' ".$lang_grabber['out']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss\"' />
</div>";
}
closetable ();
echofooter ();

?>