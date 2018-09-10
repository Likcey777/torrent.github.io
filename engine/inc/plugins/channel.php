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
die( 'Hacking attempt!');
}



if ( file_exists( ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng') ) {
require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';
}else {
if ( file_exists( ROOT_DIR .'/language/Russian/grabber.lng') ) {
require_once ROOT_DIR .'/language/Russian/grabber.lng';
}
}
@include(ENGINE_DIR.'/data/rss_config.php');
require_once ENGINE_DIR .'/inc/plugins/core.php';
require_once ENGINE_DIR .'/classes/templates.class.php';
require_once ENGINE_DIR .'/classes/parse.class.php';
include_once ENGINE_DIR.'/classes/rss.class.php';
$parse = new ParseFilter (array (),array (),1,1);
$tpl = new dle_template ();
require_once ENGINE_DIR .'/inc/plugins/backup.php';
$tpl->dir = ENGINE_DIR .'/inc/plugins/templates/';
require_once ENGINE_DIR .'/inc/plugins/channel.php';
require_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
require_once ENGINE_DIR .'/inc/plugins/rss.functions.php';


$add_bb = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div><div id="skip" style="padding:5px 0 0 2px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'skip\')" ><b>{skip}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="get" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'get\')"><b>{get}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="num" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'num\')"><b>{num}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div></div>';

$add_bbz = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div><div id="skip" style="padding:5px 0 0 2px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'skip\')" ><b>{skip}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="get" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'get\')"><b>{get}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="num" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'num\')"><b>{num}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="frag" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'frag\')"><b>{frag}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="zagolovok" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'zagolovok\')"><b>{zagolovok}</b>
</div>
 <div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>';
if ($action == 'updategrup_channel'){

$id = explode(',',$_POST['id']);
if (count ($id) > 0)
{

if ($_POST['category'] != ''){
$category_post = $db->safesql( implode( ',',$_POST['category']));
}else{$category_post = '0';}
$allow_main = intval ($_POST['allow_main']);
$allow_mod = intval ($_POST['allow_mod']);
$allow_comm = intval ($_POST['allow_comm']);
$allow_auto = intval ($_POST['allow_auto']);
$allow_load = $db->safesql ($_POST['load_img']);
$thumb_images = intval ($_POST['thumb_img']);
$allow_rate = intval ($_POST['allow_rate']);
$allow_auto = intval ($_POST['auto']);
$allow_more = intval ($_POST['allow_more']);
$allow_water = intval ($_POST['allow_watermark']);
$date_format = intval ($_POST['news_date']);
$symbol = $db->safesql ($_POST['symbols']);
$ftags = $db->safesql ($_POST['tags']);
$metatitle = $db->safesql ($_POST['meta_title']);
$meta_descr = $db->safesql ($_POST['meta_descr']);
$key_words = $db->safesql ($_POST['key_words']);
$ctp = intval ($_POST['so']).'='.intval ($_POST['po']);
$full_link = stripslashes ($_POST['full_link']);
$date = $db->safesql(trim($_POST['date'])).'='.intval ($_POST['dim_week']).'='.intval ($_POST['dim_date']).'='.intval ($_POST['dim_sait']).'='.intval ($_POST['dim_cat']);
$original_rss_url = $row['url'];
$rss_url = $db->safesql ($_POST['rss_url']);
$rss = intval ($_POST['rss_html']);


$cookies = $db->safesql (str_replace ('
','|||',$_POST['cookies']));
$keywords = $db->safesql(str_replace ('
','|||',$_POST['keywords'])).'==='.$db->safesql($_POST['sfr_short']).'==='.$db->safesql($_POST['sfr_full']);
$stkeywords = $db->safesql (str_replace ('
','|||',$_POST['stkeywords'])).'==='.$db->safesql($_POST['efr_short']).'==='.$db->safesql($_POST['efr_full']);
if ($_POST['groups'] != '')$autor_grups = implode( ',',$_POST['groups']);
$Autors = $db->safesql (str_replace ('
','|||',$_POST['Autors'])).'='.$autor_grups;
$xdescr = $db->safesql ($_POST['rss_xdescr']);
$start_template = $db->safesql ($_POST['start_template']);
$finish_template = $db->safesql ($_POST['finish_template']);
$delate = $db->safesql (str_replace ('
','|||',$_POST['delate']));
$inser = $db->safesql (str_replace ('
','|||',$_POST['inser']));
$start =  $db->safesql(str_replace ('
','|||',$_POST['start']));
$finish = $db->safesql( str_replace ('
','|||',$_POST['finish']));
$ful_start = $db->safesql ($_POST['ful_start']);
$ful_end = $db->safesql ($_POST['ful_end']);
$start_title = $db->safesql ($_POST['start_title']);
$stitles = $db->safesql (str_replace ('
','|||',$_POST['s_del']));
$ftitles = $db->safesql (str_replace ('
','|||',$_POST['end_del']));
$kategory = $db->safesql (str_replace ('
','|||',$_POST['kategory']));
if ($rss_result['charset'] != '') $_POST['charset'] = $rss_result['charset'];

$dop_nast = intval ($_POST['dop_watermark']).'='.intval ($_POST['text_url']).'='.intval ($_POST['proxy']).'='.intval ($_POST['x']).'='.intval ($_POST['y']).'='.intval ($_POST['show_autor']).'='.intval ($_POST['show_tegs']).'='.intval ($_POST['show_date']).'='.intval ($_POST['show_code']).'='.intval ($_POST['show_f']).'='.intval ($_POST['null']).'='.intval ($_POST['one_serv']).'='.intval ($_POST['margin']).'='.intval ($_POST['show_down']).'='.$_POST['charset'].'='.intval ($_POST['dubl_host']).'='.intval ($_POST['text_url_sel']).'='.intval ($_POST['parse_url_sel']).'='.intval ($_POST['full_url_and']).'='.intval ($_POST['grab_pause']).'='.intval ($_POST['step_page']).'='.intval ($_POST['add_pause']).'='.intval ($_POST['kol_short']).'='.intval ($_POST['page_break']).'='.$db->safesql ($_POST['sim_short']).'='.intval ($_POST['starter_page']);

$dnast = intval ($_POST['image_align']).'='.intval ($_POST['image_align_full']).'='.intval ($_POST['show_symbol']).'='.intval ($_POST['show_metatitle']).'='.intval ($_POST['show_metadescr']).'='.intval ($_POST['show_keywords']).'='.intval ($_POST['show_url']).'='.intval ($_POST['rss_parse']).'='.intval ($_POST['tags_auto']).'='.intval ($_POST['auto_metatitle']).'='.intval ($_POST['data_deap']).'='.intval ($_POST['deap']).'='.intval ($_POST['auto_symbol']).'='.intval ($_POST['auto_numer']).'='.intval ($_POST['show_date_expires']).'='.intval ($_POST['wat_host']).'='.intval ($_POST['cron_auto']).'='.intval ($_POST['rewrite_data']).'='.intval ($_POST['ret_xf']).'='.intval ($_POST['kol_cron']).'='.$db->safesql ($_POST['tags_kol']).'='.intval ($_POST['tags_zag']).'='.intval ($_POST['start_title_f']).'='.$db->safesql ($_POST['watermark_image_light']).'='.$db->safesql ($_POST['watermark_image_dark']).'='.$db->safesql ($_POST['leech_shab']).'='.intval ($_POST['cross_post']).'='.intval ($_POST['cache_link']).'='.intval ($_POST['twitter_post']).'='.intval ($_POST['rewrite_con']).'='.intval ($_POST['rewrite_no']).'='.intval ($_POST['leech_dop']).'='.intval ($_POST['convert_utf']).'='.intval ($_POST['title_gener']).'='.(intval ($_POST['lang_title']) == 0 ?'1': '0').'='.intval ($_POST['lang_title_komb']).'='.intval ($_POST['image_align_post']).'='.intval ($_POST['max_image']).'='.intval ($_POST['min_image']).'='.$db->safesql ($_POST['kol_image_short']).'='.intval($_POST['zip_image']).'='.intval($_POST['yan_on']).'='.$db->safesql($_POST['lang_yan']).'='.intval ($_POST['auto_chpu']).'='.intval ($_POST['kpop_image']).'='.intval ($_POST['nostor_image']);

$short_story = intval ($_POST['clear_short']).'='.intval ($_POST['short_img']).'='.intval ($_POST['short_full']).'='.intval ($_POST['sinonim']).'='.intval ($_POST['pings']).'='.$db->safesql ($_POST['teg_fix']).'='.intval ($_POST['cat_nul']).'='.intval ($_POST['keyw_sel']).'='.intval ($_POST['log_pas']).'='.intval ($_POST['text_html']).'='.intval ($_POST['descr_sel']).'='.intval ($_POST['title_prob']).'='.intval ($_POST['no_prow']).'='.intval ($_POST['lang_on']).'='.$db->safesql ($_POST['lang_in']).'='.$db->safesql ($_POST['lang_out']).'='.intval ($_POST['cat_sp']).'='.intval ($_POST['clear_full']).'='.$db->safesql ($_POST['lang_outf']).'='.intval ($_POST['sinonim_sel']).'='.intval ($_POST['add_full']).'='.(intval ($_POST['log_cookies']) == 0 ?'1': '0').'='.intval ($_POST['short_img_p']);

$end_title = $db->safesql ($_POST['s_title']).'=='.$db->safesql ($_POST['end_title']).'=='.$stitles.'=='.$ftitles.'=='.$db->safesql ($_POST['link_start_del']).'=='.$db->safesql ($_POST['link_finish_del']);
$start_short = $db->safesql ($_POST['start_short']);
$end_short = intval ($_POST['end_short']).'='.intval ($_POST['hide']).'='.intval ($_POST['leech']).'='.intval ($_POST['rewrite_news']);
$sart_link = $db->safesql ($_POST['sart_link']);
$end_link = $db->safesql ($_POST['end_link']);
$sart_cat = $db->safesql ($_POST['sart_cat']).'|||'.$db->safesql ($_POST['shab_data']).'|||'.$db->safesql ($_POST['zhv_code']);
$end_cat = $db->safesql ($_POST['end_cat']);
$dop_full = $db->safesql ($_POST['dop_full']);
if ($rss == '1'){
$start_short = '';
$sart_link = '';
$ctp = '';
}

for ($x=0;$x++<$_POST['kol_xfields'];){
if (trim($_POST['xfields_template_'.$x]) != ''and $_POST['rss_xfields_'.$x] != '0'){$templ[] = $xfields_template = $db->safesql ($_POST['rss_xfields_'.$x]).'=='.$db->safesql ($_POST['xfields_template_'.$x]).'=='.intval ($_POST['ret_xf_'.$x]).'=='.intval ($_POST['sh_fl_'.$x]).'=='.intval ($_POST['sh_im_'.$x]).'=='.$db->safesql ($_POST['xfields_delete_'.$x]).'=='.$db->safesql ($_POST['xfields_insert_'.$x]).'=='.$db->safesql ($_POST['rs_im_'.$x]).'=='.intval ($_POST['rs_sm_'.$x]).'=='.intval ($_POST['sh_nw_'.$x]);
}
}
if($templ)$xfields_template = implode ('|||',$templ);
else $xfields_template = '';
$files = intval($_POST['files_video']).'=='.$db->safesql ($_POST['pap_video']).'=='.intval($_POST['files_rar']).'=='.$db->safesql ($_POST['rar_video']).'=='.intval($_POST['files_zip']).'=='.$db->safesql ($_POST['pap_zip']).'=='.intval($_POST['files_doc']).'=='.$db->safesql ($_POST['pap_doc']).'=='.intval($_POST['files_txt']).'=='.$db->safesql ($_POST['pap_txt']).'=='.intval($_POST['files_dle']).'=='.$db->safesql ($_POST['pap_dle']).'=='.intval($_POST['url_video']).'=='.intval($_POST['url_rar']).'=='.intval($_POST['url_zip']).'=='.intval($_POST['url_doc']).'=='.intval($_POST['url_txt']).'=='.intval($_POST['url_dle']).'=='.intval($_POST['tit_video']).'=='.intval($_POST['tit_rar']).'=='.intval($_POST['tit_zip']).'=='.intval($_POST['tit_doc']).'=='.intval($_POST['tit_txt']).'=='.intval($_POST['tit_dle']).'=='.intval($_POST['files_atach']).'=='.$db->safesql($_POST['file_name']).'=='.intval($_POST['files_tor']).'=='.intval($_POST['url_tor']).'=='.intval($_POST['tit_tor']).'=='.$db->safesql ($_POST['pap_tor']).'=='.intval($_POST['tor_torrage']);

$mgs = '';

foreach ($id as $key)
	{
$rss_channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$key'");
$categoryes = explode ('=',$rss_channel_info['category']);
if($_POST['category_rid'] == 1)$category = $category_post.'='.intval ($categoryes[1]);
else $category = $rss_channel_info['category'];
$url = get_urls($rss_channel_info['url']);
$url_et = get_urls($_POST['rss_url']);
if ($url['host'] == $url_et['host']){
$db->query( 'UPDATE '.PREFIX ."_rss SET category='$category', allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', cookies ='$cookies', start_template ='$start_template', finish_template ='$finish_template', delate = '$delate', load_img ='$allow_load', url ='$rss_url', allow_watermark ='$allow_water', date_format ='$date_format', keywords ='$keywords', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', stkeywords ='$stkeywords', ful_start='$ful_start', start_title='$start_title', start_short='$start_short', end_short='$end_short', sart_link='$sart_link', end_link='$end_link', sart_cat='$sart_cat', xdescr='$xdescr', inser='$inser', start='$start', finish='$finish', end_title = '$end_title', end_link = '$end_link', short_story='$short_story', dop_nast='$dop_nast', full_link='$full_link', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', key_words='$key_words', kategory='$kategory', xfields_template='$xfields_template', dop_full='$dop_full', files='$files' WHERE id ='$key'");

}else{
$db->query( 'UPDATE '.PREFIX ."_rss SET category='$category',  allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', load_img ='$allow_load', allow_watermark ='$allow_water', date_format ='$date_format', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', short_story='$short_story', dop_nast='$dop_nast', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', end_short='$end_short', key_words='$key_words', kategory='$kategory', xfields_template='$xfields_template', dop_full='$dop_full', files='$files' WHERE id ='$key'");
}

if (trim ($rss_channel_info['title']) != '')
{
$title = stripslashes (strip_tags ($rss_channel_info['title']));
if (50 <strlen ($title))
{
$title = substr ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
$mgs .= $lang_grabber['channel'].' - №'.$rss_channel_info['xpos'].' <font color="green">"'.$title.' | '.$rss_channel_info['url'].'"</font> <font color="red">'.$lang_grabber['edit_channel_ok'].'</font><br />';

	}

msg ($lang_grabber['info'],$lang_grabber['change_channel'], $mgs ,$PHP_SELF .'?mod=rss');
$db->close;
exit;
}else{msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');}
}$start_pos = spoiler($start_pos.$men);
if ($action == 'editgrup_channel'){

if (count ($_POST['channel']) == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
return 1;
}else{$id = implode(',',$_POST['channel']);}


if (isset ($id))
{
if (trim ($id) == '' and $id == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');
return 1;
}

$rss_channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='".$_POST['channel'][0]."'");
$titles = '';
$urls ='';



$cookies = str_replace ('|||','
',stripslashes($rss_channel_info['cookies']));
$keywordsd = explode('===',$rss_channel_info['keywords']);
$keywords = str_replace ('|||','
',stripslashes ($keywordsd[0]));
$stkeywordsd = explode('===',$rss_channel_info['stkeywords']);
$stkeywords = str_replace ('|||','
',stripslashes ($stkeywordsd[0]));
$Autor = explode('=',$rss_channel_info['Autors']);
$Autors = str_replace ('|||','
',stripslashes ($Autor['0']));
$short_story = explode('=',$rss_channel_info['short_story']);
$date = explode('=',$rss_channel_info['date']);
$delate = str_replace ('|||','
',stripslashes ($rss_channel_info['delate']));
$inser = str_replace ('|||','
',stripslashes ($rss_channel_info['inser']));
$start = str_replace ('|||','
',$rss_channel_info['start']);
$finish = str_replace ('|||','
',$rss_channel_info['finish']);
$end_title = explode ('==',$rss_channel_info['end_title']);
$hide_leech = explode('=',$rss_channel_info['end_short']);
$ctp = explode ('=',$rss_channel_info['ctp']);
$dop_nast = explode ('=',$rss_channel_info['dop_nast']);
$dnast = explode ('=',$rss_channel_info['dnast']);
$categoryes = explode ('=',$rss_channel_info['category']);
$stitles = str_replace ('|||','
',$end_title[2]);
$ftitles = str_replace ('|||','
',$end_title[3]);
$kategory = str_replace ('|||','
',$rss_channel_info['kategory']);
if(strlen(stripslashes ($date[0])) == 10) $date[0] = '';
$files = explode('==',$rss_channel_info['files']);
echoheader ('','');
$channel_name = '';
foreach($_POST['channel'] as $key)
	{
$title_gr = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$key'");
//$titles .= stripslashes ($title_gr['title']).'<br>';
//$urls .= stripslashes ($title_gr['url']).'<br>';

$title_grups = stripslashes (strip_tags ($title_gr['title']));
if (trim ($title_grups) != '')
{
if (50 <strlen ($title_grups))
{
$title_grups = substr ($title_grups,0,50) .'...';
}
}
else
{
$title_grups = $lang_grabber['no_title'];
}


              $channel_name .= '<font color=green> №' .$title_gr['xpos'].' - '. stripslashes ($title_grups) . '</font> (<font color=red>' . stripslashes ($title_gr['url']) . '</font>) <a href="'.$title_gr['url'] .'" target="_blank" title="Перейти на сайт донора">[i]</a> <a href="?mod=rss&action=channel&subaction=edit&id='.$title_gr['id'] .'" target="_blank" title="Редактировать канал отдельно">[P]</a></br> ';


}


$channel_inf = array();
$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc' );
$run[0] = '';
while ($channel_info = $db->get_row($sql_result)) {
if ($channel_info['osn'] == '0')$channel_inf[$channel_info['id']][$channel_info['id']] =  $channel_info['title'];
else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- '. $channel_info['title'];
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
opentable ('<a href='.$PHP_SELF .'?mod=rss>'.$lang_grabber['index_page'] . '<b>ГРУППОВОЕ РЕДАКТИРОВАНИЕ КАНАЛОВ</b>' );
$tpl->load_template ('grup_addchannel.tpl');
$tpl->set ('{title}',$channel_name);

$tpl->set ('{file-video}', ($files[0] == 0 ?'': 'checked'));
$tpl->set ('{file-rar}', ($files[2] == 0 ?'': 'checked'));
$tpl->set ('{file-zip}', ($files[4] == 0 ?'': 'checked'));
$tpl->set ('{file-doc}', ($files[6] == 0 ?'': 'checked'));
$tpl->set ('{file-txt}', ($files[8] == 0 ?'': 'checked'));
$tpl->set ('{file-dle}', ($files[10] == 0 ?'': 'checked'));
$tpl->set ('{file-tor}', ($files[26] == 0 ?'': 'checked'));
$tpl->set ('{url-video}', ($files[12] == 0 ?'': 'checked'));
$tpl->set ('{url-rar}', ($files[13] == 0 ?'': 'checked'));
$tpl->set ('{url-zip}', ($files[14] == 0 ?'': 'checked'));
$tpl->set ('{url-doc}', ($files[15] == 0 ?'': 'checked'));
$tpl->set ('{url-txt}', ($files[16] == 0 ?'': 'checked'));
$tpl->set ('{url-dle}', ($files[17] == 0 ?'': 'checked'));
$tpl->set ('{url-tor}', ($files[27] == 0 ?'': 'checked'));
$tpl->set ('{tit-video}', ($files[18] == 0 ?'': 'checked'));
$tpl->set ('{tit-rar}', ($files[19] == 0 ?'': 'checked'));
$tpl->set ('{tit-zip}', ($files[20] == 0 ?'': 'checked'));
$tpl->set ('{tit-doc}', ($files[21] == 0 ?'': 'checked'));
$tpl->set ('{tit-txt}', ($files[22] == 0 ?'': 'checked'));
$tpl->set ('{tit-dle}', ($files[23] == 0 ?'': 'checked'));
$tpl->set ('{tit-tor}', ($files[28] == 0 ?'': 'checked'));
$tpl->set ('{file-atach}', ($files[24] == 0 ?'': 'checked'));
$tpl->set ('{file-name}',$files[25]);
$tpl->set ('{tor-torrage}', ($files[30] == 0 ?'': 'checked'));
$tpl->set ('{pap-video}',$files[1]);
$tpl->set ('{pap-rar}',$files[3]);
$tpl->set ('{pap-zip}',$files[5]);
$tpl->set ('{pap-doc}',$files[7]);
$tpl->set ('{pap-txt}',$files[9]);
$tpl->set ('{pap-dle}',$files[11]);
$tpl->set ('{pap-tor}',$files[29]);
$tpl->set ('{rss_html}',($rss_channel_info['rss'] == 0 ?'': 'checked'));
$tpl->set ('{stkeywords}',$stkeywords);
$tpl->set ('{charsets}',$dop_nast[14]);
$tpl->set ('{dubl-host}', ($dop_nast[15] == 0 ?'': 'checked'));
$tpl->set ('{cache-link}', ($dnast[27] == 0 ?'': 'checked'));
$tpl->set ('{one-serv}', ($dop_nast[11] == 0 ?'': 'checked'));

$tpl->set ('{discr}',stripslashes ($rss_channel_info['descr']));
$tpl->set ('{address}',stripslashes ($rss_channel_info['url']));
$tpl->set ('{date-format}',gen_date_format ($rss_channel_info['date_format']));
$tpl->set ('{category}',categorynewsselection (explode(',',$categoryes[0]),0));
$tpl->set ('{rss-priv}',sel ($run,$categoryes[1]));
$tpl->set ('{groups}',get_groups(explode(',',$Autor['1'])));
$tpl->set ('{load-images}', ($rss_channel_info['load_img'] == 0 ?'': 'checked'));
$tpl->set ('{thumb-images}', ($rss_channel_info['thumb_img'] == 0 ?'': 'checked'));
$tpl->set ('{allow-main}', ($rss_channel_info['allow_main']  == 0 ?'': 'checked'));
$tpl->set ('{allow-mod}',($rss_channel_info['allow_mod'] == 0 ?'': 'checked'));
$tpl->set ('{allow-comm}', ($rss_channel_info['allow_comm'] == 0 ?'': 'checked'));
$tpl->set ('{allow-rate}', ($rss_channel_info['allow_rate'] == 0 ?'': 'checked'));
$tpl->set ('{allow-full}', ($rss_channel_info['allow_more'] == 0 ?'': 'checked'));
$tpl->set ('{allow-auto}', ($rss_channel_info['allow_auto'] == 0 ?'': 'checked'));
$tpl->set ('{allow-water}', ($rss_channel_info['allow_watermark'] == 0 ?'': 'checked'));
$tpl->set ('{show_autor}', ($dop_nast[5] == 0 ?'': 'checked'));
$tpl->set ('{show_tegs}', ($dop_nast[6] == 0 ?'': 'checked'));
$tpl->set ('{show_date}', ($dop_nast[7] == 0 ?'': 'checked'));
$tpl->set ('{show_code}', ($dop_nast[8] == 0 ?'': 'checked'));
$tpl->set ('{show_down}', ($dop_nast[13] == 0 ?'': 'checked'));
$tpl->set ('{show_f}', ($dop_nast[9] == 0 ?'': 'checked'));
$tpl->set ('{show_symbol}', ($dnast[2] == 0 ?'': 'checked'));
$tpl->set ('{show_metatitle}', ($dnast[3] == 0 ?'': 'checked'));
$tpl->set ('{show_metadescr}', ($dnast[4] == 0 ?'': 'checked'));
$tpl->set ('{show_keywords}', ($dnast[5] == 0 ?'': 'checked'));
$tpl->set ('{wat-host}', ($dnast[15] == 0 ?'': 'checked'));
$tpl->set ('{rewrite-data}', ($dnast[17] == 0 ?'': 'checked'));
$tpl->set ('{show-url}', ($dnast[6] == 0 ?'': 'checked'));
$tpl->set ('{cron-auto}',$dnast[16]);
$tpl->set ('{kol-cron}',$dnast[19]);
$tpl->set ('{tags-kol}',$dnast[20]);
$tpl->set ('{rss-parse}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full'],'3'=>$lang_grabber['thumb_shortfull']),$dnast[7]));
$tpl->set ('{tags-auto}', ($dnast[8] == 0 ?'': 'checked'));
$tpl->set ('{tags-zag}', ($dnast[21] == 0 ?'': 'checked'));
$tpl->set ('{auto-metatitle}', ($dnast[9] == 0 ?'': 'checked'));
$tpl->set ('{data-deap}',$dnast[10]);
$tpl->set ('{max-image}',$dnast[37]);
$tpl->set ('{deap}',deap ($dnast[11] == 0 ?'yes': 'no'));
$tpl->set ('{symbol}',$rss_channel_info['symbol']);
$tpl->set ('{auto-symbol}', ($dnast[12] == 0 ?'': 'checked'));
$tpl->set ('{auto-numer}',sel (array(1 =>'1',2 =>'2',3 =>'3'),$dnast[13] ));
$tpl->set ('{show_date_expires}', ($dnast[14] == 0 ?'': 'checked'));
$tpl->set ('{tags}',$rss_channel_info['ftags']);
$tpl->set ('{meta-title}',$rss_channel_info['metatitle']);
$tpl->set ('{meta-descr}',$rss_channel_info['meta_descr']);
$tpl->set ('{key-words}',$rss_channel_info['key_words']);
$tpl->set ('{hide}', ($hide_leech[1] == 0 ?'': 'checked'));
$tpl->set ('{leech}', ($hide_leech[2] == 0 ?'': 'checked'));
$tpl->set ('{leech-shab}',stripslashes ($dnast[25]));
$tpl->set ('{rewrite-news}', ($hide_leech[3] == 0 ?'': 'checked'));
$tpl->set ('{rewrite-con}',sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$dnast[29]));
$tpl->set ('{rewrite-no}', ($dnast[30] == 0 ?'': 'checked'));
$tpl->set ('{leech-dop}', ($dnast[31] == 0 ?'': 'checked'));
$tpl->set ('{convert-utf}', ($dnast[32] == 0 ?'': 'checked'));
$tpl->set ('{title-gener}', ($dnast[33] == 0 ?'': 'checked'));
$tpl->set ('{clear-short}', ($short_story[0] == 0 ?'': 'checked'));
$tpl->set ('{clear-full}', ($short_story[17] == 0 ?'': 'checked'));
$tpl->set ('{short-images}', ($short_story[1] == 0 ?'': 'checked'));
$tpl->set ('{short-images-p}',sel (array(0 =>$lang_grabber['sel_lu'],1 =>$lang['upload_t_seite_2'],2 =>$lang['upload_t_seite_3']),$short_story[22]));


$tpl->set ('{kol-image-short}', $dnast[39]);
$tpl->set ('{zip-image}', ($dnast[40] == 0 ?'': 'checked'));

$tpl->set ('{kpop-image}', $dnast[44]);
$tpl->set ('{nostor-image}', ($dnast[45] == 0 ?'': 'checked'));

$tpl->set ('{min-image}',$dnast[38]);

$tpl->set ('{auto-chpu}', ($dnast[43] == 0 ?'': 'checked'));

$tpl->set ('{short-full}', ($short_story[2] == 0 ?'': 'checked'));
$tpl->set ('{pings}', ($short_story[4] == 0 ?'': 'checked'));
$tpl->set ('{teg-fix}',stripslashes ($short_story[5]));
$tpl->set ('{cat-nul}', ($short_story[6] == 0 ?'': 'checked'));
$tpl->set ('{text-html}', ($short_story[9] == 0 ?'': 'checked'));
$tpl->set ('{dim-week}', ($date[1] == 0 ?'': 'checked'));
$tpl->set ('{dim-date}', ($date[2] == 0 ?'': 'checked'));
$tpl->set ('{dim-sait}', ($date[3] == 0 ?'': 'checked'));
$tpl->set ('{dim-cat}', ($date[4] == 0 ?'': 'checked'));
$tpl->set ('{title-prob}', ($short_story[11] == 0 ?'': 'checked'));
$tpl->set ('{no-prow}',dubl_news ($short_story[12]));
$tpl->set ('{grab-pause}',$dop_nast[19]);
$tpl->set ('{add-pause}',$dop_nast[21]);
$tpl->set ('{kol-short}',$dop_nast[22]);
$tpl->set ('{sim-short}',$dop_nast[24]);
$tpl->set ('{starter-page}',$dop_nast[25]);
$tpl->set ('{page-break}',$dop_nast[23]);
$tpl->set ('{image-align}',gen_x ($dnast[0],4));
$tpl->set ('{image-align-full}',gen_x ($dnast[1],4));
$tpl->set ('{image-align-post}',gen_x ($dnast[36] == ''? $dnast[1]:$dnast[36],4));
$tpl->set ('{start-template}',str_replace ('&','&amp;',stripslashes ($rss_channel_info['start_template'])));
$tpl->set ('{end-template}',str_replace ('&','&amp;',stripslashes (str_replace ('|||','
',$rss_channel_info['finish_template']))));
$finish = str_replace ('|||','
',$rss_channel_info['finish']);
$tpl->set ('{x}',gen_x ($dop_nast[3]));
$tpl->set ('{y}',gen_y ($dop_nast[4]));
$tpl->set ('{delate}',stripslashes (@htmlspecialchars($delate,ENT_QUOTES ,$config['charset'])));
$tpl->set ('{inser}',stripslashes (@htmlspecialchars($inser,ENT_QUOTES ,$config['charset'])));
$tpl->set ('{start}',stripslashes (@htmlspecialchars($start,ENT_QUOTES ,$config['charset'])));
$tpl->set ('{finish}',stripslashes (@htmlspecialchars($finish,ENT_QUOTES ,$config['charset'])));
$tpl->set ('{full-link}',stripslashes (@htmlspecialchars($rss_channel_info['full_link'],ENT_QUOTES ,$config['charset'])));
$tpl->set ('{dop-full}',stripslashes (@htmlspecialchars($rss_channel_info['dop_full'],ENT_QUOTES ,$config['charset'])));
$tpl->set ('{so}',$ctp[0]);
$tpl->set ('{po}',$ctp[1]);
$tpl->set ('{dop-watermark}', ($dop_nast[0] == 0 ?'': 'checked'));
$tpl->set ('{watermark-image-light}',stripslashes ($dnast[23]));
$tpl->set ('{watermark-image-dark}',stripslashes ($dnast[24]));
$tpl->set ('{add-full}', ($short_story[20] == 0 ?'': 'checked'));
$tpl->set ('{lang-title}', ($dnast[34] == 1 ?'': 'checked'));/// переделать 
$tpl->set ('{lang-title-komb}', ($dnast[35] == 0 ?'': 'checked'));
$tpl->set ('{yan-on}',($dnast[41] == 0 ?'': 'checked'));
$tpl->set ('{lang-yan}',lang_yan($dnast[42] == ''?'ru': $dnast[42]));
$tpl->set ('{lang-on}', ($short_story[13] == 0 ?'': 'checked'));
$tpl->set ('{lang-out}',slected_lang ($short_story[15] == ''?'en': $short_story[15]));
$tpl->set ('{lang-in}',slected_lang($short_story[14] == ''?'ru': $short_story[14]));
$tpl->set ('{lang-outf}',slected_lang ($short_story[18] == ''?'': $short_story[18]));
$tpl->set ('{cat-sp}', ($short_story[16] == 0 ?'': 'checked'));
$tpl->set ('{text-url-sel}',sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$dop_nast[16]));
$tpl->set ('{full-url-and}', ($dop_nast[18] == 0 ?'': 'checked'));
$tpl->set ('{parse-url-sel}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_full'],'2'=>$lang_grabber['thumb_short'],'3'=>$lang_grabber['thumb_shortfull']),$dop_nast[17]));
$tpl->set ('{log-pas}', ($short_story[8] == 0 ?'': 'checked'));
$tpl->set ('{log-cookies}', ($short_story[21] == 1 ?'': 'checked'));
$tpl->set ('{keyw-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener'],5 =>$lang_grabber['sel_don']),$short_story[7]));
$tpl->set ('{descr-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener'],5 =>$lang_grabber['sel_don']),$short_story[10]));
$tpl->set ('{text-url}',sel (array(0 =>$lang_grabber['no_izm'],1 =>$lang_grabber['url_klik'],2 =>$lang_grabber['url_no_donw'],3 =>$lang_grabber['url_no_donor']),$dop_nast[1]));
$tpl->set ('{prox}',($dop_nast[2] == 0 ?'': 'checked'));
$tpl->set ('{null}', ($dop_nast[10] == 0 ?'': 'checked'));
$tpl->set ('{load-img}',server_host($rss_channel_info['load_img']));
$tpl->set ('{margin}',intval($dop_nast[12]));
$tpl->set ('{xdescr}',htmlspecialchars($rss_channel_info['xdescr'],ENT_QUOTES ,$config['charset']));
$tpl->set ('{ful-start}',stripslashes ($rss_channel_info['ful_start']));
$tpl->set ('{ful-end}',stripslashes ($rss_channel_info['ful_end']));
$tpl->set ('{start-title}',stripslashes ($rss_channel_info['start_title']));
$tpl->set ('{start-title-f}', ($dnast[22] == 0 ?'': 'checked'));
$tpl->set ('{end-title}',stripslashes ($end_title[1]));
$tpl->set ('{s-title}',stripslashes ($end_title[0]));
$tpl->set ('{link-start-del}',stripslashes ($end_title[4]));
$tpl->set ('{link-finish-del}',stripslashes ($end_title[5]));
$tpl->set ('{sfr-short}',stripslashes ($keywordsd[1]));
$tpl->set ('{efr-short}',stripslashes ($stkeywordsd[1]));
$tpl->set ('{sfr-full}',stripslashes ($keywordsd[2]));
$tpl->set ('{efr-full}',stripslashes ($stkeywordsd[2]));
$tpl->set ('{end-del}',stripslashes ($ftitles));
$tpl->set ('{s-del}',stripslashes ($stitles));
$tpl->set ('{start-short}',stripslashes ($rss_channel_info['start_short']));
$tpl->set ('{end-short}', ($hide_leech[0] == 0 ?'': 'checked'));
$tpl->set ('{sart-link}',stripslashes ($rss_channel_info['sart_link']));
$tpl->set ('{step-page}',$dop_nast[20]);
$tpl->set ('{end-link}', ($rss_channel_info['end_link'] == 0 ?'': 'checked'));
$sart_cat = explode('|||',$rss_channel_info['sart_cat']);
$tpl->set ('{sart-cat}',stripslashes ($sart_cat[0]));
$tpl->set ('{shab-data}',stripslashes ($sart_cat[1]));
$tpl->set ('{zhv-code}',stripslashes (@htmlspecialchars(str_replace ('===','
',$sart_cat[2]),ENT_QUOTES ,$config['charset'])));
$tpl->set ('{end-cat}',stripslashes ($rss_channel_info['end_cat']));
$tpl->set ('{date}',stripslashes ($date[0]));
$tpl->set ('{cookies}',$cookies);
$tpl->set ('{keywords}',$keywords);
$tpl->set ('{Autors}',$Autors);
$tpl->set ('{kategory}',$kategory);
$xfields_template = explode ('|||',$rss_channel_info['xfields_template']);
$list = rss_xfields(1);
$template = "";
$x= 1;
foreach ($xfields_template as $value){
if ($value != ''){
$key = explode ('==',$value);
if ($list[$key[0]] == '')$list[$key[0]] = $lang_grabber['list_dop_pole'];
$template .= '

<div class="title_spoiler"><center><img id="image-full_'.$x.'" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg(\'full_'.$x.'\')"><b>'.strip_tags($list[$key[0]]).' ['.$key[0].']</b></a></center></div>

<div id="full_'.$x.'" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
 <!-- <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4" ><center><b>'.$lang_grabber['list_dop_pole'].' ['.$list[$key[0]].']</b></center></td>
  </tr> -->
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'),$key[0]).'
   </select><br>
   '.$lang_grabber['use_po_get'].'
<input type="checkbox" name="ret_xf_'.$x.'" value="1" '.($key[2] == 0 ?'': 'checked').' />
    '.$lang_grabber['take_short-story'].'
<input type="checkbox" name="sh_fl_'.$x.'" value="1" '.($key[3] == 0 ?'': 'checked').' />
	     '.$lang_grabber['kol-vo'].' 
     <input name="rs_sm_'.$x.'" class="load_img" type="text" size="3" value="'.$key[8].'">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['help_kol_xf_word'].'\', this, event, \'500px\')">[?]</a>
	  <br />
   '.$lang_grabber['pole_img'].'
<input type="checkbox" name="sh_im_'.$x.'" value="1" '.($key[4] == 0 ?'no': 'checked').' />
   '.$lang_grabber['img_size'].'
     <input name="rs_im_'.$x.'" class="load_img" type="text" size="10" value="'.$key[7].'">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['opt_sys_maxsided'].'\', this, event, \'500px\')">[?]</a>
	 <br>
'.$lang_grabber['kod_ost'].'
<input type="checkbox" name="sh_nw_'.$x.'" value="1" '.($key[9] == 0 ?'': 'checked').' />
      </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'">'.stripslashes($key[1]).'</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4"><center>'.$lang_grabber['templates_search_regular'].' '.$lang_grabber['in_dop_pol'].'</center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['expression'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_'.$x.'">'.stripslashes($key[5]).'</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['paste'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_'.$x.'">'.stripslashes($key[6]).'</textarea>
   </td></tr>
</table>
</div>
';
$x++;
}
}
$template .=    '

<!-- <div><a href="javascript:ShowOrHide(\'full_'.$x.'\');"><center>'.$lang_grabber['add_new_dop'].'</center></a></div> -->

<div class="title_spoiler"><center><img id="image-full_'.$x.'" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg(\'full_'.$x.'\')">'.$lang_grabber['add_new_dop'].'</a></center></div>

<div id="full_'.$x.'" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
<!--    <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4" ><center><b>'.$lang_grabber['list_dop_pole'].' </b></center></td>
  </tr> -->
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'),'').'
   </select><br>
   '.$lang_grabber['use_po_get'].'
<input type="checkbox" name="ret_xf_'.$x.'" value="1"/>
   '.$lang_grabber['take_short-story'].'
     <input type="checkbox"  name="sh_fl_'.$x.'" value="1"/>
      '.$lang_grabber['kol-vo'].' 
     <input name="rs_sm_'.$x.'" class="load_img" type="text" size="3" value="0">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['help_kol_xf_word'].'\', this, event, \'500px\')">[?]</a>
   <br />
   '.$lang_grabber['pole_img'].'
      <input type="checkbox" name="sh_im_'.$x.'" value="1"/>
   <br>
   '.$lang_grabber['img_size'].'
     <input name="rs_im_'.$x.'" class="load_img" type="text" size="10" value="">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['opt_sys_maxsided'].'\', this, event, \'500px\')">[?]</a>
'.$lang_grabber['kod_ost'].'
     <input type="checkbox" name="sh_nw_'.$x.'" value="1"/>
   </select>
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4"><center>'.$lang_grabber['templates_search_regular'].' '.$lang_grabber['in_dop_pol'].'</center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['expression'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_'.$x.'"></textarea>
   </td></tr>
</table>


   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['paste'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_'.$x.'"></textarea>
   </td></tr>
</table>
</div>
<a style="padding:10px" href="'.$PHP_SELF .'?mod=xfields&xfieldsaction=configure" target="_blank">'.$lang_grabber['list_dop_pole'].'</a><br /><br />';
$tpl->set ('{kol-xfields}',$x);
$tpl->set ('{xfields-template}',$template);
if (@file_exists ($rss_plugins.'sinonims.php') )
{
$sin =  '
  <tr style="border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['sinonims'].':</td>
   <td width="768" style="padding:4px">
  <input type="checkbox" name="sinonim" value="1" '.($short_story[3] == 0 ?'': 'checked').' value="1"/> 
   <select name="sinonim_sel" class="load_img">'.
sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$short_story[19]).'
   </select>
  </td>
  </tr>';
}
if(@file_exists(ENGINE_DIR .'/inc/crosspost.addnews.php'))
{
$sin .=  '
  <tr style="border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['crosspost'].':</td>
   <td width="768" style="padding:4px">
   <input type="checkbox" name="cross_post" '. ($dnast[26] == 0 ?'': 'checked').' value="1"/> 
  </td>
  </tr>';
}
if((@file_exists(ENGINE_DIR.'/modules/twitter.php') or @file_exists(ENGINE_DIR.'/modules/socialposting/posting.php')))
{
$sin .=  '
  <tr style="border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['twitter'].':</td>
   <td width="768" style="padding:4px">
   <input type="checkbox" name="twitter_post" '. ($dnast[28] == 0 ?'': 'checked').' value="1"/> 
  </td>
  </tr>';
}
$tpl->set ('{sinonim}',$sin);
$tpl->set ('{opt_sys_yes}',$lang['opt_sys_yes']);
$tpl->set ('{opt_sys_no}',$lang['opt_sys_no']);
foreach ($lang_grabber as $key =>$value){$tpl->set ('{'.$key.'}',$value);}
$form = '   <form method="post" >
    <input type="hidden" name="id" value="'.$id .'" />
    <input type="hidden" name="action" value="channel" />
    <input type="hidden" name="subaction" value="do_change" />';
include_once ($rss_plugins.'inserttag.php');
$tpl->set ('{inserttag}',$bb_js);
$form .= "
<script>
    $(function(){
        $('#tags').autocomplete({
            serviceUrl:'engine/ajax/tags_rss.php',
            minChars:3,
            delimiter: /(,|;)\s*/,
            maxHeight:400,
            width:348,
            deferRequestBy: 300
          });

    });

function simpletags(thetag)
{
                doInsert(\"{\"+thetag+\"}\", \"\", false);
}

function ShowOrHideg( id ) {
      var item = document.getElementById(id);
      if ( document.getElementById('image-'+ id) ) {
        var image = document.getElementById('image-'+ id);
      } else {
        var image = null;
      }
      if (!item) {
        retun;
      }  else {
        if (item.style) {
            if (item.style.display == \"none\") {
                item.style.display = \"\";
                image.src = './engine/skins/grabber/images/minus.gif';
            } else {
                item.style.display = \"none\";
                image.src = './engine/skins/grabber/images/plus.gif';
            }
         } else{ item.visibility = \"show\"; }
      }
};
</script>
";
$tpl->set ('{BB_code}',$add_bb);
$tpl->set ('{BB_codez}',$add_bbz);
$tpl->set ('{BB_codezz}',$add_bbzz);
$tpl->copy_template = $form .$tpl->copy_template .'
            <input align="left" class="edit" type="submit"  value=" '.$lang_grabber['save'].' " >&nbsp;
            <input type="button"    class="edit" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></form>';
$tpl->compile ('rss');
echo $tpl->result['rss'];
closetable ();
echofooter ();
$db->close;
exit();
}
}


clear_cache();
$db->close;

?>
