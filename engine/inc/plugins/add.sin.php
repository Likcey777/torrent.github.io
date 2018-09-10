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

if( !defined( 'DATALIFEENGINE') ) {
die( "Hacking attempt!");
}
if ($config['version_id'] < '8.5'){
echo '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>';
}

if ($_GET['alf'] != '')
	{
$sql = $db->query("SELECT * FROM " . PREFIX . "_synonims WHERE string like '".$_GET['alf']."%'");
$est = '';

                  if ($db->num_rows ($sql) > 0) {
$all = $db->num_rows ($sql);
$pnumber=$config_rss['page_sinonims'];
$page=(isset($_GET['page'])) ?(int)$_GET['page'] : 1;
$num_pages=ceil($all/$pnumber);
$start = $pnumber*$page-$pnumber+1;
$end = $pnumber*$page;
$x = 1;
          while ($row = $db->get_array($sql)){
if ($x >= $start){
            $storyr=explode('|',$row['string']);
            $pattern=$storyr[0];
            $vars =$storyr[1];
	$est .="$x. <input style=\"color:#8C8C8C; margin: 1 0 1 0px;\" name=\"new$x\" size=\"30\" value=\"$pattern\" /> <input style=\"color:#8C8C8C; margin: 1 0 1 0px;\"name=\"var$x\"  size=\"30\" value=\"$vars\" /><input type=\"hidden\" name=\"id$x\" value=\"{$row['id']}\" /><input type=\"hidden\" name=\"varo$x\" value=\"{$vars}\" /> <input type=\"hidden\" name=\"newo$x\" value=\"{$pattern}\" /><br />";
}
if ($end == $x)break;
$x++;
  }

if($pages == 1){
}else{
$npp_nav = "<div class=\"news_navigation\" style=\"margin-bottom:5px; margin-top:5px;\">";
for($i =1;$i <= $num_pages;$i++)
{
if ($i == 1 or $i == $num_pages or abs($i-$page) < 5){
	if ($i == $page)$npp_nav .= " <SPAN>$i</SPAN> ";
	else $npp_nav .= ' <a href="'.$PHP_SELF.'?mod=rss&action=sinonim&alf='.$_GET['alf'].'&page='.$i.'">'.$i.'</a> ';
}else{
if ($page+5 == $i ) {
	$npp_nav .= ' <a href="'.$PHP_SELF.'?mod=rss&action=sinonim&alf='.$_GET['alf'].'&page='.$i.'">'.$i.'</a> ... ';
}elseif ( $page-5 == $i ){
$npp_nav .= ' ... <a href="'.$PHP_SELF.'?mod=rss&action=sinonim&alf='.$_GET['alf'].'&page='.$i.'">'.$i.'</a> ';
}else{
$npp_nav .= '';
}
}
}

$npp_nav .=    '<br />

        <span><input id="num_page" style="background:none; height:15px; width:50px; border:0;"/></span> <a href="#" onclick="topage(); return false;">'.$lang_grabber['go_do'].'</a>

    ';

$npp_nav .= "</div>";
}



				 if ($est != ''){
echoheader($lang_grabber['d_r_sin'], '');
opentable ('<b>'.$lang_grabber['sin_database'].'</b>');
echo 
"<div align=\"center\">
					 {$lang_grabber['sin_bu']} <b>\"{$_GET['alf']}\"</b> <font color=\"#8C8C8C\">({$all} {$lang_grabber['sht']}.)</font><br /><br />
    <span style=\"text-align: center;\">
<form method=\"post\" action=\"?mod=rss&action=sinonim\">
$est
<br />
$npp_nav
<br />
<input type=\"hidden\" name=\"kol\" value=\"{$x}\" />
    <input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-info\" name=\"rid\" value=\"{$lang_grabber['lang_rided']}\">
	<input type=\"button\" class=\"btn btn-warning\"	value=' ".$lang_grabber['out']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss&action=sinonim\"' />
	</form></span>";
	echo '    <form action="" onsubmit="topage() return false;">
    <script type="text/javascript">
        function topage() {
            var loca = window.location+"";
            var locas = loca.split("page");
                loca = locas[0];
                locas = loca.split("'.$PHP_SELF .'");
            window.location.href = locas[0] + \''.$PHP_SELF.'?mod=rss&action=sinonim&alf='.$_GET['alf'].'&page=\' + document.getElementById(\'num_page\').value;
        }
    </script>
    </form>
	<br><br>
</div>
';
closetable ();
echofooter ();
exit;
				 }
				  }else{


echoheader($lang_grabber['d_r_sin'], '');
opentable ('<b>'.$lang_grabber['sin_database'].'</b>');
echo 
"<div align=\"center\">
					  {$lang_grabber['sins_buk']} <b>\"{$_GET['alf']}\"</b> {$lang_grabber['no_database']} <br /><br />
<input type=\"button\" class=\"btn btn-warning\"	value=' ".$lang_grabber['out']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss&action=sinonim\"' />
</div>
";
closetable ();
echofooter ();
exit;}

	}


if ($_POST['rid'] == $lang_grabber['lang_add'] and $_POST['new']!='' and $_POST['var']!='') {
$newsin = $_POST['new'].'|'.$_POST['var'];

$db->query("INSERT INTO " . PREFIX . "_synonims (string) VALUES ('$newsin')");

msg ($lang_grabber['dod_add'],$lang_grabber['dod_add'],'<font color=red>'.$lang_grabber['new_sin_add'].'</font>',$PHP_SELF .'?mod=rss&action=sinonim');
}elseif($_POST['rid'] == $lang_grabber['lang_add'] and ($_POST['new']=='' or $_POST['var']=='')){msg ($lang_grabber['dod_add'],'<b>'.$lang_grabber['add_sin_database'].'</b>','<font color=red>'.$lang_grabber['no_pol_zap'].'</b>',$PHP_SELF .'?mod=rss&action=sinonim');}


if ($_POST['rid'] == $lang_grabber['lang_rided']) {
$mes_sin = '';
$i=1;
for ($x=1; $x <= $_POST['kol']; $x++){
if ($_POST['new'.$x] != $_POST['newo'.$x] or $_POST['var'.$x] != $_POST['varo'.$x]){
if ($_POST['id'.$x] != '' and $_POST['new'.$x]!= '' and $_POST['var'.$x] != ''){

$mes_sin .="<table border=0 style=\"color:#8C8C8C;\"><tr><td style=\"color:#8C8C8C;\">$i. </td><td>
<input type=\"hidden\" name=\"id$x\" value=\"{$_POST['id'.$x]}\" /> 
<input type=\"hidden\" name=\"varo$x\" value=\"{$_POST['varo'.$x]}\" /> <font style=\"color:#008000; margin: 5 0 0 8px;\" >{$_POST['newo'.$x]}</font>
<br>

<input style=\"color:#8C8C8C; margin: 0 3 10 5px;\" name=\"new$x\" size=\"30\" value=\"{$_POST['new'.$x]}\" /></td>

<td>

<input type=\"hidden\" name=\"newo$x\" value=\"{$_POST['newo'.$x]}\" /> <font style=\"color:#0000FF; margin: 0 0 0 3px;\">{$_POST['varo'.$x]}</font>
<br>

<input style=\"color:#8C8C8C; margin: 0 3 10 0px;\" name=\"var$x\"  size=\"30\" value=\"{$_POST['var'.$x]}\" /><br /></td></tr></table>";
$i++;
}
}
}
if ($mes_sin != ''){ 
	echoheader($lang_grabber['d_r_sin'], '');
opentable ('<b>'.$lang_grabber['red_sin'].'</b>');
	echo '<div><center><font color=red><b>'.$lang_grabber['prov_add_sin'].'</b></font></center>
<span>
	<form method="post">
	<br /><center>'.$mes_sin."<input type=\"hidden\" name=\"kol\" value=\"{$x}\" /><br />
<input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-success\" name=\"rids\" value=\"{$lang_grabber['lang_rided']}\">

    <input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-warning\" name=\"rid\"  value=\"{$lang_grabber['lang_sbros']}\"></center>
</span><br>
	</form>
</div>";
closetable ();
echofooter ();
exit;
}else{msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['red_sins'].'</b>', '<font color=red>'.$lang_grabber['yj_red_sins'].'</font>' ,$PHP_SELF .'?mod=rss&action=sinonim');}
}


if ($_POST['rids'] == $lang_grabber['lang_rided']) {
$mes_sin = '';
for ($x=1; $x <= $_POST['kol']; $x++){
if ($_POST['new'.$x] != $_POST['newo'.$x] or $_POST['var'.$x] != $_POST['varo'.$x]){
$newsin = $_POST['new'.$x].'|'.$_POST['var'.$x];
if ($_POST['id'.$x] != '' and $_POST['new'.$x]!= '' and $_POST['var'.$x] != ''){
$db->query ("UPDATE ".PREFIX ."_synonims SET string = '$newsin' WHERE id = '".$_POST['id'.$x]."' ");

$mes_sin .= '<font color=green><b>'.$_POST['new'.$x].'</b></font> - <font color=blue><b>'.$_POST['var'.$x].'</b></font> <br />';
}
}
}
if ($mes_sin != ''){ $mes_sin = '<font color=red>'.$lang_grabber['red_sin_ok'].'</font><br /><br />'.$mes_sin;
msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['red_sins'].'</b>',$mes_sin ,$PHP_SELF .'?mod=rss&action=sinonim');
}else{msg ($lang_grabber['red_grups'],'<b>'.$lang_grabber['red_sins'].'</b>', $lang_grabber['red_sin_on'] ,$PHP_SELF .'?mod=rss&action=sinonim');}
}


if ($_POST['add'] == $lang_grabber['lang_dal']) {
if ($_POST['orig'] !=''){
$sql = $db->query("SELECT * FROM " . PREFIX . "_synonims WHERE string like '".$_POST['orig']."%'");

                  if ($db->num_rows ($sql) > 0) {
$all = $db->num_rows ($sql);
$i = 1;
          while ($row = $db->get_array($sql)){

            $storyr=explode('|',$row['string']);
            $pattern=$storyr[0];
            $vars =$storyr[1];
	$est .="$i. <input style=\"color:#8C8C8C; margin: 1 0 1 0px;\" name=\"new$i\" size=\"30\" value=\"$pattern\" /> <input style=\"color:#8C8C8C; margin: 1 0 1 0px;\" name=\"var$i\"  size=\"30\" value=\"$vars\" /><input type=\"hidden\" name=\"id$i\" value=\"{$row['id']}\" /> <input type=\"hidden\" name=\"varo$i\" value=\"{$vars}\" /> <input type=\"hidden\" name=\"newo$i\" value=\"{$pattern}\" /><br />";

 $i++;

				  }
				 
				  }


echoheader($lang_grabber['d_r_sin'], '');
opentable ('<b>'.$lang_grabber['add_red_sin'].'</b>');
echo 
"<div align=\"center\">
<b>{$lang_grabber['sin_add_ok']}</b><br />
<form method=\"post\" >
    <span style=\"text-align: center;\">
<input name=\"new\" size=\"30\" value=\"{$_POST['orig']}\" /><font color=\"red\"></font> <input  name=\"var\"  size=\"30\" value=\"{$_POST['sinon']}\" /><br><br>
    <input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-success\" name=\"rid\"  value=\"{$lang_grabber['lang_add']}\" />&nbsp;<input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-warning\" name=\"rid\"  value=\"{$lang_grabber['lang_sbros']}\"><br />
<br />";
if ($est){
echo "
<font color=\"#8C8C8C\"><b>{$lang_grabber['dub_sins']}</b> ($all {$lang_grabber['sht']}.)</font>
<br />
$est<br />
<input type=\"hidden\" name=\"kol\" value=\"{$i}\" />
<input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-success\" name=\"rid\"  value=\"{$lang_grabber['lang_rided']}\">

    <input type=\"submit\" style=\"width: 100px;\" class=\"btn btn-warning\" name=\"rid\"  value=\"{$lang_grabber['lang_sbros']}\">";}else{echo "<font color=\"#8C8C8C\"><b>{$lang_grabber['dub_sins']}</b><br>
- {$lang_grabber['no_database_sear']} -</font>";}
echo "
    </span><br><br>
</form>
</div>
";
closetable ();
echofooter ();
exit;
}elseif($_POST['add'] == $lang_grabber['lang_dal'] and $_POST['new']!=''){
echoheader($lang_grabber['d__sin'], '');
opentable ($lang_grabber['d_r_sin']);
echo 
"<div align=\"center\">
    {$lang_grabber['form_add_sin']}!<br /><br />
<form method=\"post\" >
	{$lang_grabber['is_sin']} <input name=\"orig\" size=\"30\" value=\"\" />    {$lang_grabber['os_sin']}  <input name=\"sinon\" size=\"50\" value=\"\" />
   <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_is_sin']}', this, event, '420px')\">[?]</a>
   <br /><br />
    <span style=\"text-align: center;\">
    <input type=\"submit\" class=\"btn btn-success\" name=\"add\"  value=\"{$lang_grabber['lang_dal']}\" />
    </span><br><br>
</form>
</div>
";

closetable ();
echofooter ();
}elseif($_POST['add'] == $lang_grabber['lang_dal'] and $_POST['new'] ==''){
msg ($lang_grabber['dod_add'],'<b>'.$lang_grabber['er_big'].'</b>','<font color="red">'.$lang_grabber['no_v_sin'].'</b>',$PHP_SELF .'?mod=rss&action=sinonim');
}
}


$sql_result = $db->query ('SELECT id FROM '.PREFIX .'_synonims' );
$all_sin = $db->num_rows ($sql_result) ;
echoheader($lang_grabber['d__sin'], '');
opentable ('<b>'.$lang_grabber['ops_sin'].'</b>');
if ($config['version_id'] < '8.5'){
echo '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>';
}
echo 
"
<script type=\"text/javascript\">
	function start_sinonims (key, id )
	{
		var ajax = new dle_ajax();
		ajax.onShow ('');
if (key == 1)var title = ajax.encodeVAR( document.getElementById('short_' + id).value);
else var title = ajax.encodeVAR( document.getElementById('full' + id).value);

		var varsString = 'story=' + title;
		ajax.setVar(\"id\", id);
		ajax.setVar(\"key\", key);
		ajax.requestFile ='engine/ajax/start_sinonims.php';

		if (key == 1)ajax.element = 'sinonim_short' + id;
else ajax.element = 'sinonim_full' + id;
		ajax.method = 'POST';
		ajax.sendAJAX(varsString);
return false;
	}
</script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'	border='0' /></div>
<div style=\"padding:4px; border-bottom:1px dashed #c4c4c4;\" align=\"left\"><b>{$lang_grabber['d_r_sin']}</b></div>

<div align=\"center\"><br>
<form method=\"post\" >
{$lang_grabber['is_sin_b']} <input name=\"orig\" size=\"30\" style=\"color: #FF0000;\" value=\"\" />

    <input type=\"submit\" class=\"btn btn-primary\" name=\"add\"  value=\"{$lang_grabber['lang_dal']}\" />

</form><br>
<div style=\"padding:4px; border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4\" align=\"left\"><b>{$lang_grabber['b_ch_sear']}</b></div>

<div class=\"news_navigation\" style=\"margin-bottom:5px; margin-top:5px;\">

<A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=0>0</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=1>1</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=2>2</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=3>3</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=4>4</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=5>5</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=6>6</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=7>7</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=8>8</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=9>9</A>  <BR>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=A>A</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=B>B</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=C>C</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=D>D</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=E>E</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=F>F</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=G>G</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=H>H</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=I>I</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=J>J</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=K>K</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=L>L</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=M>M</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=N>N</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=O>O</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=P>P</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Q>Q</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=R>R</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=S>S</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=T>T</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=U>U</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=V>V</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=W>W</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=X>X</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Y>Y</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Z>Z</A>  <BR>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=А>А</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Б>Б</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=В>В</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Г>Г</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Д>Д</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Е>Е</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ж>Ж</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=З>З</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=И>И</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Й>Й</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=К>К</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Л>Л</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=М>М</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Н>Н</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=О>О</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=П>П</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Р>Р</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=С>С</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Т>Т</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=У>У</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ф>Ф</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Х>Х</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ц>Ц</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ч>Ч</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ш>Ш</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Щ>Щ</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ъ>Ъ</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ы>Ы</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ь>Ь</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Э>Э</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Ю>Ю</A>  <A href=".$PHP_SELF."?mod=rss&action=sinonim&alf=Я>Я</A>
</div>

<div align=\"center\"><font color=\"#8C8C8C\">{$lang_grabber['all_sin_data']}: <b>{$all_sin}</b> {$lang_grabber['sht']}.</font><br><br></div>

<div style=\"padding:4px; border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4\" align=\"left\"><b>{$lang_grabber['sin_text']}</b></div><br />

<textarea style=\"width:98%; height:200px\" id=\"short_1\" name=\"short_1\"></textarea>

<input class=\"btn btn-success\" type=\"button\" onClick=\"start_sinonims(1, 1); return false;\" value=\"{$lang_grabber['sinonims_preview']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_sinonims_preview']}', this, event, '220px')\">[?]</a> <span align=\"left\" id=\"sinonim_short1\"></span>

<br /><div class=\"unterline\"></div>
<div align=\"left\"><input type=\"button\" class=\"btn btn-warning\"	value=' ".$lang_grabber['out']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss\"' />
</div>

</div>
";

closetable ();
echofooter ();




?>