<?php @ini_set('display_errors', true);
if (!defined('DATALIFEENGINE')) {
    die('Hacking attempt!');
}
@include (ENGINE_DIR . '/data/rss_config.php');
if (intval($config_rss['memory_limit']) != 0) @ini_set('memory_limit', $config_rss['memory_limit']);
else @ini_set('memory_limit', "256M");
@set_time_limit(0);
@ini_set('post_max_size', "2000M");
@ini_set('upload_max_filesize', "2000M");
@ini_set('max_input_nesting_level', 256);
@ini_set('max_input_vars', 864000);
@ini_set('max_input_time', 864000);
@ini_set('max_execution_time', 864000);
@ini_set('output_buffering', 'off');
@ob_end_clean();
clearstatcache();
ob_implicit_flush(TRUE);
$dle_plugins = ENGINE_DIR . '/classes/';
$rss_plugins = ENGINE_DIR . '/inc/plugins/';
$selected_language = $config['langs'];
$module_info = array('name' => 'RSS Grabber', 'host' => 'rss-grabber', 'zone' => 'de', 'version' => '3.6.9', 'build' => '1609');
if (isset($_COOKIE['selected_language'])) {
    $_COOKIE['selected_language'] = totranslit($_COOKIE['selected_language'], false, false);
    if (@is_dir(ROOT_DIR . '/language/' . $_COOKIE['selected_language'])) {
        $selected_language = $_COOKIE['selected_language'];
        $config['langs'] = $selected_language;
    }
}
if (file_exists(ROOT_DIR . '/language/' . $config['langs'] . '/grabber.lng')) {
    require_once ROOT_DIR . '/language/' . $config['langs'] . '/grabber.lng';
} else {
    if (file_exists(ROOT_DIR . '/language/Russian/grabber.lng')) {
        require_once ROOT_DIR . '/language/Russian/grabber.lng';
        require_once $rss_plugins . 'core.php';
        require_once $rss_plugins . 'strip_tags_smart.php';
        require_once $rss_plugins . 'rss.classes.php';
        require_once $rss_plugins . 'rss.functions.php';
        require_once $rss_plugins . 'channel.php';
        require_once $rss_plugins . 'rss.parser.php';
        require_once $rss_plugins . 'classes.file.php';
        $file = ROOT_DIR . '/language/' . $config['langs'] . '/grabber.lng';
        $d_lang = e_sub(strtolower($config['langs']), 0, 2);
        $n = count($lang_grabber);
        $i = 0;
        $text = translate_google("Подождите идёт локализация модуля ...", 'ru', $d_lang);
        echoheader('', '');
        opentable($text);
        ob_flush();
        flush();
        foreach ($lang_grabber as $key => $value) {
            $val_lng.= $key . "1a1a1 " . $value . " 2a2a2";
        }
        $val_lng = str_replace('&#', '<hw', $val_lng);
        $val_lng = str_replace('&lt;', 'lt;', $val_lng);
        $val_lng = str_replace('&gt;', 'gt;', $val_lng);
        $vals = translate_google($val_lng, 'ru', $d_lang);
        $vals = str_replace("
", '<br />', $vals);
        $vals = str_replace(' /', '/', $vals);
        $vals = str_replace('/ ', '/', $vals);
        $vals = str_replace('"', '', $vals);
        $vals = str_replace('lt;', '&lt;', $vals);
        $vals = str_replace('gt;', '&gt;', $vals);
        $vals = str_replace('<hw', '&#', $vals);
        $vals = str_replace('==: ==', '==:==', $vals);
        $handler = fopen(ROOT_DIR . '/language/' . $config['langs'] . '/grabb.lng', "w+");
        fwrite($handler, $vals);
        fclose($handler);
        $tr_n = explode("2a2a2", $vals);
        foreach ($tr_n as $valu_g) {
            $tr_v = explode("1a1a1", $valu_g);
            $lang_grabber[trim($tr_v[0]) ] = trim($tr_v[1]);
        }
        echo translate_google("Локализация завершена!!!", 'ru', $d_lang) . '<br /><br /><input type="button" class="btn btn-warning" value="' . $lang_grabber['go_index'] . '" onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" />';
        closetable();
        echofooter();
        $handler = fopen($file, "w+");
        fwrite($handler, "<?php

");
        fwrite($handler, "\$lang_grabber = " . var_export($lang_grabber, true) . ';' . "
?" . '>');
        fclose($handler);
        exit();
    } else {
        die("Language file not found");
    }
}
$dle_plugins = ENGINE_DIR . '/classes/';
require_once $dle_plugins . 'templates.class.php';
require_once $dle_plugins . 'parse.class.php';
$parse = new ParseFilter(array(), array(), 1, 1);
$key_iframe = array_search('iframe', $parse->tagBlacklist);
unset($parse->tagBlacklist[$key_iframe]);
$key_script = array_search('script', $parse->tagBlacklist);
unset($parse->tagBlacklist[$key_script]);
$tpl = new dle_template();
$rss_plugins = ENGINE_DIR . '/inc/plugins/';
require_once $rss_plugins . 'core.php';
require_once $rss_plugins . 'strip_tags_smart.php';
require_once $rss_plugins . 'backup.php';
$tpl->dir = $rss_plugins . 'templates/';
require_once $rss_plugins . 'rss.classes.php';
require_once $rss_plugins . 'rss.functions.php';
require_once $rss_plugins . 'channel.php';
require_once $rss_plugins . 'rss.parser.php';
require_once $rss_plugins . 'classes.file.php';
if (file_exists($rss_plugins . 'include/torrent.php')) {
    require_once $rss_plugins . 'include/bencode.php';
    require_once $rss_plugins . 'include/torrent.php';
}
if (file_exists($rss_plugins . 'include/class.apivk.php')) {
    require_once $rss_plugins . 'include/class.apivk.php';
}
if ($_REQUEST['action'] != '') {
    $action = $_REQUEST['action'];
} else {
    $action = '';
}
if ($_REQUEST['subaction'] != '') {
    $subaction = $_REQUEST['subaction'];
} else {
    $subaction = '';
}
if ($_REQUEST['id'] != '') {
    $id = intval($_REQUEST['id']);
} else {
    $id = '';
}
$add_bb = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div><div id="skip" style="padding:5px 0 0 2px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'skip\')" ><b>{skip}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="get" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'get\')"><b>{get}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="num" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'num\')"><b>{num}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="link" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'link\')"><b>{link}</b>
</div>
 <div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>
</div>
';
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
<div id="link" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'link\')"><b>{link}</b>
</div>
 <div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>';
$add_bbzz = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div>
<div id="frag" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'frag\')"><b>{frag}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="zagolovok" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'zagolovok\')"><b>{zagolovok}</b>
</div>
 <div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>';
if (($action == 'channel') and ($subaction == 'add')) {
    echoheader('', '');
    opentable($lang_grabber['new_channel']);
    $channel_inf = array();
    $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_rss_category ORDER BY kanal asc');
    $run[0] = '';
    while ($channel_info = $db->get_row($sql_result)) {
        if ($channel_info['osn'] == '0') $channel_inf[$channel_info['id']][$channel_info['id']] = $channel_info['title'];
        else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- ' . $channel_info['title'];
    }
    foreach ($channel_inf as $value) {
        if (count($value) != '0') {
            foreach ($value as $kkey => $key) {
                $run[$kkey] = $key;
            }
        }
    }
    $tpl->load_template('rss_addchannel.tpl');
    $tpl->set('{rss-priv}', sel($run, ''));
    $tpl->set('{title}', '');
    $tpl->set('{category}', categorynewsselection(1, 0));
    $tpl->set('{address}', '');
    $tpl->set('{date-format}', gen_date_format($config_rss['date']));
    $tpl->set('{charsets}', '');
    $tpl->set('{load-img}', server_host($config_rss['img_host']));
    $tpl->set('{dubl-host}', '');
    $tpl->set('{rss-parse}', sel(array('0' => $lang_grabber['no_thumb'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full'], '3' => $lang_grabber['thumb_shortfull']), ''));
    $tpl->set('{cache-link}', '');
    $tpl->set('{one-serv}', '');
    $tpl->set('{image-align}', gen_x($config_rss['image_align'], 4));
    $tpl->set('{image-align-full}', gen_x($config_rss['image_align_full'], 4));
    $tpl->set('{image-align-post}', gen_x($config_rss['image_align_full'], 4));
    $tpl->set('{hide}', ($config_rss['hide'] == 'yes' ? 'checked' : ''));
    $tpl->set('{leech}', ($config_rss['leech'] == 'yes' ? 'checked' : ''));
    $tpl->set('{leech-dop}', ($config_rss['leech_dop'] == 'yes' ? 'checked' : ''));;
    $tpl->set('{leech-shab}', '');
    $tpl->set('{thumb-images}', ($config_rss['cat'] == 'yes' ? 'checked' : ''));
    $tpl->set('{cat-nul}', '');
    $tpl->set('{cat-sp}', '');
    $tpl->set('{kategory}', '');
    $tpl->set('{kol-short}', '');
    $tpl->set('{sim-short}', '');
    $tpl->set('{starter-page}', '');
    $tpl->set('{page-break}', '');
    $tpl->set('{data-deap}', '');
    $tpl->set('{max-image}', '');
    $tpl->set('{deap}', deap());
    $tpl->set('{log-pas}', '');
    $tpl->set('{log-cookies}', 'checked');
    $tpl->set('{wat-host}', '');
    $tpl->set('{tags-auto}', ($config_rss['tags_auto'] == 'yes' ? 'checked' : ''));
    $tpl->set('{tags-zag}', '');
    $tpl->set('{allow-mod}', ($config_rss['allow-mod'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-main}', ($config_rss['allow-main'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-comm}', ($config_rss['allow-rate'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-rate}', ($config_rss['allow-comm'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-full}', ($config_rss['allow-full'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-auto}', ($config_rss['allow-auto'] == 'yes' ? 'checked' : ''));
    $tpl->set('{allow-water}', ($config_rss['allow-water'] == 'yes' ? 'checked' : ''));
    $tpl->set('{rewrite-news}', ($config_rss['rewrite-news'] == 'yes' ? 'checked' : ''));
    $tpl->set('{rewrite-data}', '');
    $tpl->set('{rewrite-no}', '');
    $tpl->set('{rewrite-con}', sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), '0'));
    $tpl->set('{clear-short}', ($config_rss['clear-short'] == 'yes' ? 'checked' : ''));
    $tpl->set('{clear-full}', '');
    $tpl->set('{short-images}', ($config_rss['short-images'] == 'yes' ? 'checked' : ''));
    $tpl->set('{short-images-p}', sel(array(0 => $lang_grabber['sel_lu'], 1 => $lang['upload_t_seite_2'], 2 => $lang['upload_t_seite_3']), $config_rss['short-images-p']));
    $tpl->set('{kol-image-short}', '');
    $tpl->set('{nostor-image}', '');
    $tpl->set('{kpop-image}', '');
    $tpl->set('{zip-image}', '');
    $tpl->set('{short-full}', ($config_rss['short-full'] == 'yes' ? 'checked' : ''));
    $tpl->set('{dop-watermark}', ($config_rss['dop-watermark'] == 'yes' ? 'checked' : ''));
    $tpl->set('{watermark-image-light}', $config_rss['watermark_image_light']);
    $tpl->set('{watermark-image-dark}', $config_rss['watermark_image_dark']);
    $tpl->set('{null}', ($config_rss['null'] == 'yes' ? 'checked' : ''));
    $tpl->set('{grab-pause}', '');
    $tpl->set('{add-pause}', '');
    $tpl->set('{cron-auto}', '');
    $tpl->set('{min-image}', '');
    $tpl->set('{text-html}', '');
    $tpl->set('{dim-date}', '');
    $tpl->set('{dim-sait}', '');
    $tpl->set('{dim-cat}', '');
    $tpl->set('{dim-week}', '');
    $tpl->set('{file-atach}', '');
    $tpl->set('{tor-torrage}', '');
    $tpl->set('{file-name}', '');
    $tpl->set('{file-video}', '');
    $tpl->set('{file-rar}', '');
    $tpl->set('{file-zip}', '');
    $tpl->set('{file-doc}', '');
    $tpl->set('{file-txt}', '');
    $tpl->set('{file-dle}', '');
    $tpl->set('{file-tor}', '');
    $tpl->set('{url-video}', '');
    $tpl->set('{url-rar}', '');
    $tpl->set('{url-zip}', '');
    $tpl->set('{url-doc}', '');
    $tpl->set('{url-txt}', '');
    $tpl->set('{url-dle}', '');
    $tpl->set('{url-tor}', '');
    $tpl->set('{tit-video}', '');
    $tpl->set('{tit-rar}', '');
    $tpl->set('{tit-zip}', '');
    $tpl->set('{tit-doc}', '');
    $tpl->set('{tit-txt}', '');
    $tpl->set('{tit-dle}', '');
    $tpl->set('{tit-tor}', '');
    $tpl->set('{pap-video}', '');
    $tpl->set('{pap-rar}', '');
    $tpl->set('{pap-zip}', '');
    $tpl->set('{pap-doc}', '');
    $tpl->set('{pap-txt}', '');
    $tpl->set('{pap-dle}', '');
    $tpl->set('{pap-tor}', '');
    $tpl->set('{full-url-and}', '');
    $tpl->set('{text-url-sel}', sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), $config_rss['url-sel']));
    $tpl->set('{parse-url-sel}', sel(array('0' => $lang_grabber['no_thumb'], '1' => $lang_grabber['thumb_full'], '2' => $lang_grabber['thumb_short'], '3' => $lang_grabber['thumb_shortfull']), $config_rss['parse-url-sel']));
    $tpl->set('{keyw-sel}', sel(array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener'], 5 => $lang_grabber['sel_don']), $config_rss['keyw-sel']));
    $tpl->set('{descr-sel}', sel(array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener'], 5 => $lang_grabber['sel_don']), $config_rss['descr-sel']));
    $tpl->set('{text-url}', sel(array(0 => $lang_grabber['no_izm'], 1 => $lang_grabber['url_klik'], 2 => $lang_grabber['url_no_donw'], 3 => $lang_grabber['url_no_donor']), $config_rss['text-url']));
    $list = rss_xfields(1);
    $template = "";
    $x = 1;
    foreach ($list as $key => $value) {
        if ($value != '') {
            $template.= '

<center><div class="title_spoiler"><img id="image-full_' . $x . '" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg(\'full_' . $x . '\')"><b>' . $value . ' [' . $key . ']</b></a></div></center>

<div id="full_' . $x . '" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr>
   <td style="padding:4px"  align="center">' . $lang_grabber['rss_xfields'] . '
   <select name="rss_xfields_' . $x . '" class="load_img">
    ' . sel(rss_xfields('1'), $key) . '
   </select><br>
   ' . $lang_grabber['use_po_get'] . '
<input type="checkbox" name="ret_xf_' . $x . '" value="1" />
    ' . $lang_grabber['take_short-story'] . '
<input type="checkbox" name="sh_fl_' . $x . '" value="1" />
	     ' . $lang_grabber['kol-vo'] . '
     <input name="rs_sm_' . $x . '" class="load_img" type="text" size="3" value="">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['help_kol_xf_word'] . '\', this, event, \'500px\')">[?]</a>
	  <br />
   ' . $lang_grabber['pole_img'] . '
<input type="checkbox" name="sh_im_' . $x . '" value="1" /><br />
' . $lang_grabber['full_stor'] . '
<input type="checkbox" name="full_stor_' . $x . '" value="1" checked /> <br />
   ' . $lang_grabber['img_size'] . '
     <input name="rs_im_' . $x . '" class="load_img" type="text" size="10" value="">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['opt_sys_maxsided'] . '\', this, event, \'500px\')">[?]</a>
	 <br>
' . $lang_grabber['kod_ost'] . '
<input type="checkbox" name="sh_nw_' . $x . '" value="1" />
<br><br>
' . $lang_grabber['def_dop'] . '
     <input name="def_dop_' . $x . '" class="load_img" type="text" size="50" value="">
      </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">' . $add_bb . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_' . $x . '"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>' . $lang_grabber['templates_search_regular'] . ' ' . $lang_grabber['in_dop_pol'] . '</center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['expression'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_' . $x . '"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['paste'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_' . $x . '"></textarea>
   </td></tr>
</table>
</div>';
            $x++;
        }
    }
    $template.= '<a style="padding:10px" href="' . $PHP_SELF . '?mod=xfields&xfieldsaction=configure" target="_blank">' . $lang_grabber['list_dop_pole'] . '</a><br /><br />';
    $tpl->set('{kol-xfields}', $x);
    $tpl->set('{xfields-template}', $template);
    $tpl->set('{x}', gen_x($config_rss['x']));
    $tpl->set('{y}', gen_y($config_rss['y']));
    $tpl->set('{margin}', $config_rss['margin']);
    if (@file_exists($rss_plugins . 'sinonims.php')) {
        $sin = '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['sinonims'] . ':</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="sinonim" value="1" ' . ($config_rss['sinonim'] == 'no' ? '' : 'checked') . '/>
    <select name="sinonim_sel" class="load_img">' . sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), $config_rss['sinonim-sel']) . '
   </select>
  </td>
  </tr>';
    }
    if (@file_exists(ENGINE_DIR . '/inc/crosspost.addnews.php')) {
        $sin.= '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['crosspost'] . ':</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="cross_post" value="1" />
  </td>
  </tr>';
    }
    if ((@file_exists(ENGINE_DIR . '/modules/twitter.php') or @file_exists(ENGINE_DIR . '/modules/socialposting/posting.php'))) {
        $sin.= '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['twitter'] . ':</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="twitter_post" value="1" />
  </td>
  </tr>';
    }
    $tpl->set('{sinonim}', $sin);
    $tpl->set('{title-prob}', '');
    $tpl->set('{title-gener}', '');
    $tpl->set('{convert-utf}', ($config_rss['convert'] == 'yes' ? 'checked' : ''));
    $tpl->set('{no-prow}', dubl_news($config_rss['no_prow']));
    $tpl->set('{pings}', ($config_rss['allow_post']));
    $tpl->set('{show_autor}', ($config_rss['show_autor'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_tegs}', ($config_rss['show_tegs'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_date}', ($config_rss['show_date'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_code}', ($config_rss['show_code'] == 'yes' ? 'checked' : ''));;
    $tpl->set('{show_date}', ($config_rss['show_date'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_code}', ($config_rss['show_code'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_down}', ($config_rss['show_down'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_f}', ($config_rss['show_f'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_symbol}', ($config_rss['show_symbol'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show-url}', ($config_rss['show_url'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_date_expires}', ($config_rss['show_date_expires'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_metatitle}', ($config_rss['show_metatitle'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_metadescr}', ($config_rss['show_metadescr'] == 'yes' ? 'checked' : ''));
    $tpl->set('{show_keywords}', ($config_rss['show_keywords'] == 'yes' ? 'checked' : ''));
    $tpl->set('{symbol}', '');
    $tpl->set('{end-template}', '');
    $tpl->set('{auto-symbol}', '');
    $tpl->set('{auto-numer}', sel(array(1 => '1', 2 => '2', 3 => '3'), ''));
    $tpl->set('{tags}', '');
    $tpl->set('{teg-fix}', '');
    $tpl->set('{meta-title}', '');
    $tpl->set('{auto-metatitle}', ($config_rss['auto_metatitle'] == 'yes' ? 'checked' : ''));
    $tpl->set('{auto-chpu}', '');
    $tpl->set('{meta-descr}', '');
    $tpl->set('{key-words}', '');
    $tpl->set('{prox}', '');
    $tpl->set('{start-template}', '<div id={skip}news-id-{skip}>{get}</div>');
    $tpl->set('{delate}', '');
    $tpl->set('{inser}', '');
    $tpl->set('{cookies}', '');
    $tpl->set('{keywords}', '');
    $tpl->set('{xdescr}', '');
    $tpl->set('{stkeywords}', '');
    $tpl->set('{date}', '');
    $tpl->set('{start}', '');
    $tpl->set('{finish}', '');
    $tpl->set('{kol-cron}', '');
    $tpl->set('{tags-kol}', '');
    $tpl->set('{dop-full}', '');
    $tpl->set('{groups}', get_groups(explode(',', $config_rss['reg_group'])));
    $tpl->set('{Autors}', '');
    $tpl->set('{link-start-del}', '');
    $tpl->set('{link-finish-del}', '');
    $tpl->set('{ful-start}', '');
    $tpl->set('{start-title}', '');
    $tpl->set('{start-title-f}', '');
    $tpl->set('{s-title}', '');
    $tpl->set('{end-title}', '');
    $tpl->set('{sfr-short}', '');
    $tpl->set('{efr-short}', '');
    $tpl->set('{sfr-full}', '');
    $tpl->set('{efr-full}', '');
    $tpl->set('{s-del}', '');
    $tpl->set('{end-del}', '');
    $tpl->set('{start-short}', '<div id={skip}news-id-{skip}>{get}</div>');
    $tpl->set('{end-short}', '');
    $tpl->set('{sart-link}', '');
    $tpl->set('{step-page}', '');
    $tpl->set('{end-link}', '');
    $tpl->set('{sart-cat}', '');
    $tpl->set('{shab-data}', '');
    $tpl->set('{full-link}', '');
    $tpl->set('{so}', '');
    $tpl->set('{po}', '');
    $tpl->set('{zhv-code}', '');
    $tpl->set('{lang-title}', 'checked');
    $tpl->set('{lang-title-komb}', '');
    $tpl->set('{yan-on}', '');
    list($yanin, $yanout) = explode("-", $dnast[42]);
    $tpl->set('{lang-yan-in}', lang_yan($yanin == '' ? 'ru' : $yanin));
    $tpl->set('{lang-yan-out}', lang_yan($yanout == '' ? 'ru' : $yanout));
    $tpl->set('{lang-on}', '');
    $tpl->set('{lang-in}', slected_lang('ru'));
    $tpl->set('{lang-out}', slected_lang('en'));
    $tpl->set('{lang-outf}', slected_lang(''));
    $tpl->set('{add-full}', '');
    include_once (ENGINE_DIR . '/inc/include/inserttag.php');
    $tpl->set('{inserttag}', $bb_js);
    $form.= "
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
</script>
<script language=\"javascript\" type=\"text/javascript\">
var skip = 0;
var get = 0;
var frag = 0;
var zagolovok = 0;


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
    $tpl->set('{opt_sys_yes}', $lang['opt_sys_yes']);
    $tpl->set('{opt_sys_no}', $lang['opt_sys_no']);
    $tpl->set('{BB_code}', $add_bb);
    $tpl->set('{BB_codez}', $add_bbz);
    $tpl->set('{BB_codezz}', $add_bbzz);
    foreach ($lang_grabber as $key => $value) $tpl->set('{' . $key . '}', $value);
    $tpl->copy_template = '<form method="post"><input type="hidden" name="action" value="channel" /><input type="hidden" name="subaction" value="doadd" />' . $tpl->copy_template . $form . '
        <input align="left" class="btn btn-success" type="submit" value=" ' . $lang_grabber['save'] . ' " >&nbsp;
        <input type="button" class="btn btn-warning" value=" ' . $lang_grabber['out'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" /></form>';
    $tpl->compile('rss');
    echo $tpl->result['rss'];
    closetable();
    echofooter();
    $db->close;
    return 1;
}
if ($config_rss['get_proxy'] == 'yes') get_proxy();
$config_rss['get_prox'] = $tab_id;
if (($action == 'channel') and ($subaction == 'doadd')) {
    $stop = false;
    $rss_url = stripslashes($_POST['rss_url']);
    if ($_POST['category'] != '') {
        $category_post = $db->safesql(implode(',', $_POST['category']));
    } else {
        $category_post = '0';
    }
    $category = $category_post . '=' . intval($_POST['rss_priv']);
    $allow_more = intval($_POST['allow_more']);
    $allow_mod = intval($_POST['allow_mod']);
    $allow_main = intval($_POST['allow_main']);
    $allow_comm = intval($_POST['allow_comm']);
    $allow_rate = intval($_POST['allow_rate']);
    $auto = intval($_POST['auto']);
    $load_images = $db->safesql($_POST['load_img']);
    $thumb_images = intval($_POST['thumb_img']);
    $allow_watermark = intval($_POST['allow_watermark']);
    $date_format = intval($_POST['news_date']);
    $ctp = intval($_POST['so']) . '=' . intval($_POST['po']);
    $dnast = intval($_POST['image_align']) . '=' . intval($_POST['image_align_full']) . '=' . intval($_POST['show_symbol']) . '=' . intval($_POST['show_metatitle']) . '=' . intval($_POST['show_metadescr']) . '=' . intval($_POST['show_keywords']) . '=' . intval($_POST['show_url']) . '=' . intval($_POST['rss_parse']) . '=' . intval($_POST['tags_auto']) . '=' . intval($_POST['auto_metatitle']) . '=' . intval($_POST['data_deap']) . '=' . intval($_POST['deap']) . '=' . intval($_POST['auto_symbol']) . '=' . intval($_POST['auto_numer']) . '=' . intval($_POST['show_date_expires']) . '=' . intval($_POST['wat_host']) . '=' . intval($_POST['cron_auto']) . '=' . intval($_POST['rewrite_data']) . '=' . intval($_POST['ret_xf']) . '=' . intval($_POST['kol_cron']) . '=' . $db->safesql($_POST['tags_kol']) . '=' . intval($_POST['tags_zag']) . '=' . intval($_POST['start_title_f']) . '=' . $db->safesql($_POST['watermark_image_light']) . '=' . $db->safesql($_POST['watermark_image_dark']) . '=' . $db->safesql($_POST['leech_shab']) . '=' . intval($_POST['cross_post']) . '=' . intval($_POST['cache_link']) . '=' . intval($_POST['twitter_post']) . '=' . intval($_POST['rewrite_con']) . '=' . intval($_POST['rewrite_no']) . '=' . intval($_POST['leech_dop']) . '=' . intval($_POST['convert_utf']) . '=' . intval($_POST['title_gener']) . '=' . (intval($_POST['lang_title']) == 0 ? '1' : '0') . '=' . intval($_POST['lang_title_komb']) . '=' . intval($_POST['image_align_post']) . '=' . intval($_POST['max_image']) . '=' . intval($_POST['min_image']) . '=' . $db->safesql($_POST['kol_image_short']) . '=' . intval($_POST['zip_image']) . '=' . intval($_POST['yan_on']) . '=' . $db->safesql($_POST['lang_yan_in'] . "-" . $_POST['lang_yan_out']) . '=' . intval($_POST['auto_chpu']) . '=' . intval($_POST['kpop_image']) . '=' . intval($_POST['nostor_image']);
    $full_link = stripslashes($_POST['full_link']);
    $short_story = intval($_POST['clear_short']) . '=' . intval($_POST['short_img']) . '=' . intval($_POST['short_full']) . '=' . intval($_POST['sinonim']) . '=' . intval($_POST['pings']) . '=' . $db->safesql($_POST['teg_fix']) . '=' . intval($_POST['cat_nul']) . '=' . intval($_POST['keyw_sel']) . '=' . intval($_POST['log_pas']) . '=' . intval($_POST['text_html']) . '=' . intval($_POST['descr_sel']) . '=' . intval($_POST['title_prob']) . '=' . intval($_POST['no_prow']) . '=' . intval($_POST['lang_no']) . '=' . $db->safesql($_POST['lang_in']) . '=' . $db->safesql($_POST['lang_out']) . '=' . intval($_POST['cat_sp']) . '=' . intval($_POST['clear_full']) . '=' . $db->safesql($_POST['lang_outf']) . '=' . intval($_POST['sinonim_sel']) . '=' . intval($_POST['add_full']) . '=' . (intval($_POST['log_cookies']) == 0 ? '1' : '0') . '=' . intval($_POST['short_img_p']);
    $start_template = $db->safesql($_POST['start_template']);
    $finish_template = $db->safesql(str_replace('
', '|||', $_POST['finish_template']));
    $dop_full = $db->safesql($_POST['dop_full']);
    $start = $db->safesql(str_replace('
', '|||', $_POST['start']));
    $finish = $db->safesql(str_replace('
', '|||', $_POST['finish']));
    $delate = $db->safesql(str_replace('
', '|||', $_POST['delate']));
    $inser = $db->safesql(str_replace('
', '|||', $_POST['inser']));
    $symbol = $db->safesql($_POST['symbols']);
    $ftags = $db->safesql($_POST['tags']);
    $metatitle = $db->safesql($_POST['meta_title']);
    $meta_descr = $db->safesql($_POST['meta_descr']);
    $key_words = $db->safesql($_POST['key_words']);
    $ful_start = $db->safesql($_POST['ful_start']);
    $ful_end = $db->safesql($_POST['ful_end']);
    $start_title = $db->safesql($_POST['start_title']);
    $stitles = $db->safesql(str_replace('
', '|||', $_POST['s_del']));
    $ftitles = $db->safesql(str_replace('
', '|||', $_POST['end_del']));
    $end_title = $db->safesql($_POST['s_title']) . '==' . $db->safesql($_POST['end_title']) . '==' . $stitles . '==' . $ftitles . '==' . $db->safesql($_POST['link_start_del']) . '==' . $db->safesql($_POST['link_finish_del']);
    $start_short = $db->safesql($_POST['start_short']);
    $end_short = intval($_POST['end_short']) . '=' . intval($_POST['hide']) . '=' . intval($_POST['leech']) . '=' . intval($_POST['rewrite_news']);
    $sart_link = $db->safesql($_POST['sart_link']);
    $end_link = intval($_POST['end_link']);
    $sart_cat = $db->safesql($_POST['sart_cat']) . '|||' . $db->safesql($_POST['shab_data']) . '|||' . $db->safesql(str_replace('
', '===', $_POST['zhv_code']));
    $end_cat = $db->safesql($_POST['end_cat']);
    $xdescr = $db->safesql($_POST['rss_xdescr']);
    $date = $db->safesql(trim($_POST['date'])) . '=' . intval($_POST['dim_week']) . '=' . intval($_POST['dim_date']) . '=' . intval($_POST['dim_sait']) . '=' . intval($_POST['dim_cat']);
    $cookies = $db->safesql(str_replace('
', '|||', $_POST['cookies']));
    $keywords = $db->safesql(str_replace('
', '|||', $_POST['keywords'])) . '===' . $db->safesql($_POST['sfr_short']) . '===' . $db->safesql($_POST['sfr_full']);
    $stkeywords = $db->safesql(str_replace('
', '|||', $_POST['stkeywords'])) . '===' . $db->safesql($_POST['efr_short']) . '===' . $db->safesql($_POST['efr_full']);
    if ($_POST['groups'] != '') $autor_grups = implode(',', $_POST['groups']);
    $Autors = $db->safesql(str_replace('
', '|||', $_POST['Autors'])) . '=' . $autor_grups;
    $proxy = '';
    $kategory = $db->safesql(str_replace('
', '|||', $_POST['kategory']));
    for ($x = 0;$x++ < $_POST['kol_xfields'];) {
        if ((trim($_POST['xfields_template_' . $x]) != '' or trim($_POST['def_dop_' . $x]) != '') and $_POST['rss_xfields_' . $x] != '0') {
            $templ[] = $xfields_template = $db->safesql($_POST['rss_xfields_' . $x]) . '==' . $db->safesql($_POST['xfields_template_' . $x]) . '==' . intval($_POST['ret_xf_' . $x]) . '==' . intval($_POST['sh_fl_' . $x]) . '==' . intval($_POST['sh_im_' . $x]) . '==' . $db->safesql($_POST['xfields_delete_' . $x]) . '==' . $db->safesql($_POST['xfields_insert_' . $x]) . '==' . $db->safesql($_POST['rs_im_' . $x]) . '==' . intval($_POST['rs_sm_' . $x]) . '==' . intval($_POST['sh_nw_' . $x]) . '==' . $db->safesql($_POST['def_dop_' . $x]) . '==' . intval($_POST['full_stor_' . $x]);
        }
    }
    if ($templ) $xfields_template = implode('|||', $templ);
    else $xfields_template = '';
    if (trim($rss_url) == '') {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['error_url'], "javascript:history.go(-1)");
        return 1;
    }
    if (!(preg_match("#(http:\/\/|https:\/\/)#i", $rss_url)) or reset_url($rss_url) == '') {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['error_url_msg'], "javascript:history.go(-1)");
        return 1;
    }
    $rss_url = $db->safesql($rss_url);
    $inf = $db->super_query('SELECT id,title FROM ' . PREFIX . "_rss WHERE url = '" . trim($rss_url) . "'");
    $rss_result = get_rss_channel_info($rss_url, intval($_POST['proxy']), $_POST['charset']);
    if ($rss_result['title'] != '') {
        $channel_title = $rss_result['title'];
        $channel_descr = $rss_result['description'];
        $rss = '1';
    } else {
        $channel_title = $rss_result['html'];
        $channel_descr = $rss_result['html'];
        $rss = '0';
    }
    if ($rss_result['charset'] != '') $_POST['charset'] = $rss_result['charset'];
    if ($rss == '1') {
        $start_short = '';
        $sart_link = '';
        $ctp = '';
    }
    $dop_nast = intval($_POST['dop_watermark']) . '=' . intval($_POST['text_url']) . '=' . intval($_POST['proxy']) . '=' . intval($_POST['x']) . '=' . intval($_POST['y']) . '=' . intval($_POST['show_autor']) . '=' . $db->safesql($_POST['show_tegs']) . '=' . intval($_POST['show_date']) . '=' . intval($_POST['show_code']) . '=' . intval($_POST['show_f']) . '=' . intval($_POST['null']) . '=' . intval($_POST['one_serv']) . '=' . intval($_POST['margin']) . '=' . intval($_POST['show_down']) . '=' . $_POST['charset'] . '=' . intval($_POST['dubl_host']) . '=' . intval($_POST['text_url_sel']) . '=' . intval($_POST['parse_url_sel']) . '=' . intval($_POST['full_url_and']) . '=' . intval($_POST['grab_pause']) . '=' . intval($_POST['step_page']) . '=' . intval($_POST['add_pause']) . '=' . intval($_POST['kol_short']) . '=' . intval($_POST['page_break']) . '=' . $db->safesql($_POST['sim_short']) . '=' . intval($_POST['starter_page']);
    $files = intval($_POST['files_video']) . '==' . $db->safesql($_POST['pap_video']) . '==' . intval($_POST['files_rar']) . '==' . $db->safesql($_POST['rar_video']) . '==' . intval($_POST['files_zip']) . '==' . $db->safesql($_POST['pap_zip']) . '==' . intval($_POST['files_doc']) . '==' . $db->safesql($_POST['pap_doc']) . '==' . intval($_POST['files_txt']) . '==' . $db->safesql($_POST['pap_txt']) . '==' . intval($_POST['files_dle']) . '==' . $db->safesql($_POST['pap_dle']) . '==' . intval($_POST['url_video']) . '==' . intval($_POST['url_rar']) . '==' . intval($_POST['url_zip']) . '==' . intval($_POST['url_doc']) . '==' . intval($_POST['url_txt']) . '==' . intval($_POST['url_dle']) . '==' . intval($_POST['tit_video']) . '==' . intval($_POST['tit_rar']) . '==' . intval($_POST['tit_zip']) . '==' . intval($_POST['tit_doc']) . '==' . intval($_POST['tit_txt']) . '==' . intval($_POST['tit_dle']) . '==' . intval($_POST['files_atach']) . '==' . $db->safesql($_POST['file_name']) . '==' . intval($_POST['files_tor']) . '==' . intval($_POST['url_tor']) . '==' . intval($_POST['tit_tor']) . '==' . $db->safesql($_POST['pap_tor']) . '==' . intval($_POST['tor_torrage']);
    if ($stop == false) {
        $sql_result = $db->query('SELECT url FROM ' . PREFIX . '_rss');
        $pnum = $db->num_rows($sql_result) + 1;
        if (trim($channel_title) == '') $channel_title = reset_urlk($rss_url);
        $channel_title = $db->safesql(str_replace('"', "&#34;", $channel_title));
        $channel_descr = $db->safesql($channel_descr);
        $sql_query = 'INSERT INTO ' . PREFIX . "_rss (url, title, descr, category, allow_main, allow_comm, allow_rate, allow_auto, load_img, allow_more, start_template, finish_template, cookies, allow_watermark, date_format, keywords, Autors, thumb_img, allow_mod, stkeywords, rss, ful_start, start_title, start_short, end_short, sart_link, end_link, sart_cat, xdescr, xpos, delate, inser, start, finish, end_title, short_story, dop_nast, ctp, full_link, date, dnast, symbol, ftags, metatitle, meta_descr, key_words, kategory, xfields_template, dop_full, files) VALUES ('$rss_url', '$channel_title', '$channel_descr', '$category', '$allow_main', '$allow_comm', '$allow_rate', '$auto', '$load_images', '$allow_more', '$start_template', '$finish_template', '$cookies', '$allow_watermark', '$date_format', '$keywords', '$Autors', '$thumb_images', '$allow_mod', '$stkeywords', '$rss', '$ful_start', '$start_title', '$start_short', '$end_short', '$sart_link', '$end_link', '$sart_cat', '$xdescr', '$pnum', '$delate', '$inser', '$start', '$finish', '$end_title', '$short_story', '$dop_nast', '$ctp', '$full_link', '$date', '$dnast', '$symbol', '$ftags', '$metatitle', '$meta_descr', '$key_words', '$kategory', '$xfields_template' ,'$dop_full', '$files')";
        $db->query($sql_query);
        $rss_id = $db->insert_id();
        if (trim($channel_title) != '') {
            $title = stripslashes(strip_tags_smart($channel_title));
            if (50 < e_str($title)) {
                $title = e_sub($title, 0, 50) . '...';
            }
        } else {
            $title = $lang_grabber['no_title'];
        }
        if ($rss == 1) {
            $mgs = $lang_grabber['channel'] . ' &#8470;' . $pnum . ' <font color="green">"' . $title . ' | ' . $rss_url . '"</font> <font color="red">' . $lang_grabber['add_msg_rss'] . '</font><br />';
            msg($lang_grabber['info'], $lang_grabber['add_channel_ms'], $mgs . ((count($inf) != 0) ? '<br />* * *<br /><b style="color:#ff0000;">' . $lang_grabber['add_msg_er'] . '</b><br /><br /><a class="list" href="admin.php?mod=rssaction=channel&subaction=edit&id=' . $inf['id'] . '"><b style="color:blue;">' . $inf['title'] . '</b></a>' : ''), $PHP_SELF . '?mod=rss');
            return 1;
        } else {
            $mgs = $lang_grabber['channel'] . ' &#8470; <b>' . $pnum . '</b> => <font color="green">"' . $title . ' | ' . $rss_url . '"</font> <font color="red">' . $lang_grabber['add_msg_html'] . '</font><br />';
            msg($lang_grabber['info'], $lang_grabber['add_channel_ms'], $mgs . ((count($inf) != 0) ? '<br />* * *<br /><b style="color:#ff0000;">' . $lang_grabber['add_msg_er'] . '</b><br /><br /><a class="list" href="admin.php?mod=rss&action=channel&subaction=edit&id=' . $inf['id'] . '"><b style="color:blue;">' . $inf['title'] . '</b></a>' : ''), $PHP_SELF . '?mod=rss');
            $db->close;
            return 1;
        }
    }
}
if (($action == 'channel') and ($subaction == 'do_change')) {
    if (isset($id)) {
        $stop = false;
        if (!((!(trim($id) == '') AND !($id == 0)))) {
            msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['channel_msg_id'], "javascript:history.go(-1)");
            return 1;
        }
        $row = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id ='$id'");
        if ($_POST['category'] != '') {
            $category_post = $db->safesql(implode(',', $_POST['category']));
        } else {
            $category_post = '0';
        }
        $category = $category_post . '=' . intval($_POST['rss_priv']);
        $allow_main = intval($_POST['allow_main']);
        $allow_mod = intval($_POST['allow_mod']);
        $allow_comm = intval($_POST['allow_comm']);
        $allow_load = $db->safesql($_POST['load_img']);
        $thumb_images = intval($_POST['thumb_img']);
        $allow_rate = intval($_POST['allow_rate']);
        $allow_auto = intval($_POST['auto']);
        $allow_more = intval($_POST['allow_more']);
        $allow_water = intval($_POST['allow_watermark']);
        $date_format = intval($_POST['news_date']);
        $symbol = $db->safesql($_POST['symbols']);
        $ftags = $db->safesql($_POST['tags']);
        $metatitle = $db->safesql($_POST['meta_title']);
        $meta_descr = $db->safesql($_POST['meta_descr']);
        $key_words = $db->safesql($_POST['key_words']);
        $dnast = intval($_POST['image_align']) . '=' . intval($_POST['image_align_full']) . '=' . intval($_POST['show_symbol']) . '=' . intval($_POST['show_metatitle']) . '=' . intval($_POST['show_metadescr']) . '=' . intval($_POST['show_keywords']) . '=' . intval($_POST['show_url']) . '=' . intval($_POST['rss_parse']) . '=' . intval($_POST['tags_auto']) . '=' . intval($_POST['auto_metatitle']) . '=' . intval($_POST['data_deap']) . '=' . intval($_POST['deap']) . '=' . intval($_POST['auto_symbol']) . '=' . intval($_POST['auto_numer']) . '=' . intval($_POST['show_date_expires']) . '=' . intval($_POST['wat_host']) . '=' . intval($_POST['cron_auto']) . '=' . intval($_POST['rewrite_data']) . '=' . intval($_POST['ret_xf']) . '=' . intval($_POST['kol_cron']) . '=' . $db->safesql($_POST['tags_kol']) . '=' . intval($_POST['tags_zag']) . '=' . intval($_POST['start_title_f']) . '=' . $db->safesql($_POST['watermark_image_light']) . '=' . $db->safesql($_POST['watermark_image_dark']) . '=' . $db->safesql($_POST['leech_shab']) . '=' . intval($_POST['cross_post']) . '=' . intval($_POST['cache_link']) . '=' . intval($_POST['twitter_post']) . '=' . intval($_POST['rewrite_con']) . '=' . intval($_POST['rewrite_no']) . '=' . intval($_POST['leech_dop']) . '=' . intval($_POST['convert_utf']) . '=' . intval($_POST['title_gener']) . '=' . (intval($_POST['lang_title']) == 0 ? '1' : '0') . '=' . intval($_POST['lang_title_komb']) . '=' . intval($_POST['image_align_post']) . '=' . intval($_POST['max_image']) . '=' . intval($_POST['min_image']) . '=' . $db->safesql($_POST['kol_image_short']) . '=' . intval($_POST['zip_image']) . '=' . intval($_POST['yan_on']) . '=' . $db->safesql($_POST['lang_yan_in'] . "-" . $_POST['lang_yan_out']) . '=' . intval($_POST['auto_chpu']) . '=' . intval($_POST['kpop_image']) . '=' . intval($_POST['nostor_image']);
        $short_story = intval($_POST['clear_short']) . '=' . intval($_POST['short_img']) . '=' . intval($_POST['short_full']) . '=' . intval($_POST['sinonim']) . '=' . intval($_POST['pings']) . '=' . $db->safesql($_POST['teg_fix']) . '=' . intval($_POST['cat_nul']) . '=' . intval($_POST['keyw_sel']) . '=' . intval($_POST['log_pas']) . '=' . intval($_POST['text_html']) . '=' . intval($_POST['descr_sel']) . '=' . intval($_POST['title_prob']) . '=' . intval($_POST['no_prow']) . '=' . intval($_POST['lang_on']) . '=' . $db->safesql($_POST['lang_in']) . '=' . $db->safesql($_POST['lang_out']) . '=' . intval($_POST['cat_sp']) . '=' . intval($_POST['clear_full']) . '=' . $db->safesql($_POST['lang_outf']) . '=' . intval($_POST['sinonim_sel']) . '=' . intval($_POST['add_full']) . '=' . (intval($_POST['log_cookies']) == 0 ? '1' : '0') . '=' . intval($_POST['short_img_p']);
        $ctp = intval($_POST['so']) . '=' . intval($_POST['po']);
        $full_link = stripslashes($_POST['full_link']);
        $date = $db->safesql(trim($_POST['date'])) . '=' . intval($_POST['dim_week']) . '=' . intval($_POST['dim_date']) . '=' . intval($_POST['dim_sait']) . '=' . intval($_POST['dim_cat']);
        $original_rss_url = $row['url'];
        $rss_url = $db->safesql($_POST['rss_url']);
        $rss = intval($_POST['rss_html']);
        if (trim($rss_url) == '') {
            msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['error_url'], "javascript:history.go(-1)");
            return 1;
        }
        if (!(preg_match("#(http:\/\/|https:\/\/)#i", $rss_url)) or reset_url($rss_url) == '') {
            msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['error_url_msg'], "javascript:history.go(-1)");
            return 1;
        }
        if ($original_rss_url != $rss_url) {
            $inf = $db->super_query('SELECT title FROM ' . PREFIX . "_rss WHERE url = '" . trim($rss_url) . "'");
            $rss_result = get_rss_channel_info($rss_url, intval($_POST['proxy']), $_POST['charset']);
            if ($rss_result['title'] != '') {
                $channel_title = $rss_result['title'];
                $channel_descr = $rss_result['description'];
                $rss = '1';
            } else {
                $channel_title = $rss_result['html'];
                $channel_descr = $rss_result['html'];
                $rss = '0';
            }
        } else {
            $channel_title = $_POST['rss_title'];
        }
        if ($stop == false) {
            $cookies = $db->safesql(str_replace('
', '|||', $_POST['cookies']));
            $keywords = $db->safesql(str_replace('
', '|||', $_POST['keywords'])) . '===' . $db->safesql($_POST['sfr_short']) . '===' . $db->safesql($_POST['sfr_full']);
            $stkeywords = $db->safesql(str_replace('
', '|||', $_POST['stkeywords'])) . '===' . $db->safesql($_POST['efr_short']) . '===' . $db->safesql($_POST['efr_full']);
            if ($_POST['groups'] != '') $autor_grups = implode(',', $_POST['groups']);
            $Autors = $db->safesql(str_replace('
', '|||', $_POST['Autors'])) . '=' . $autor_grups;
            $xdescr = $db->safesql($_POST['rss_xdescr']);
            $start_template = $db->safesql($_POST['start_template']);
            $finish_template = $db->safesql(str_replace('
', '|||', $_POST['finish_template']));
            $delate = $db->safesql(str_replace('
', '|||', $_POST['delate']));
            $inser = $db->safesql(str_replace('
', '|||', $_POST['inser']));
            $start = $db->safesql(str_replace('
', '|||', $_POST['start']));
            $finish = $db->safesql(str_replace('
', '|||', $_POST['finish']));
            $ful_start = $db->safesql($_POST['ful_start']);
            $ful_end = $db->safesql($_POST['ful_end']);
            $start_title = $db->safesql($_POST['start_title']);
            $stitles = $db->safesql(str_replace('
', '|||', $_POST['s_del']));
            $ftitles = $db->safesql(str_replace('
', '|||', $_POST['end_del']));
            $kategory = $db->safesql(str_replace('
', '|||', $_POST['kategory']));
            if ($rss_result['charset'] != '') $_POST['charset'] = $rss_result['charset'];
            $dop_nast = intval($_POST['dop_watermark']) . '=' . intval($_POST['text_url']) . '=' . intval($_POST['proxy']) . '=' . intval($_POST['x']) . '=' . intval($_POST['y']) . '=' . intval($_POST['show_autor']) . '=' . intval($_POST['show_tegs']) . '=' . intval($_POST['show_date']) . '=' . intval($_POST['show_code']) . '=' . intval($_POST['show_f']) . '=' . intval($_POST['null']) . '=' . intval($_POST['one_serv']) . '=' . intval($_POST['margin']) . '=' . intval($_POST['show_down']) . '=' . $_POST['charset'] . '=' . intval($_POST['dubl_host']) . '=' . intval($_POST['text_url_sel']) . '=' . intval($_POST['parse_url_sel']) . '=' . intval($_POST['full_url_and']) . '=' . intval($_POST['grab_pause']) . '=' . intval($_POST['step_page']) . '=' . intval($_POST['add_pause']) . '=' . intval($_POST['kol_short']) . '=' . intval($_POST['page_break']) . '=' . $db->safesql($_POST['sim_short']) . '=' . intval($_POST['starter_page']);;
            $end_title = $db->safesql($_POST['s_title']) . '==' . $db->safesql($_POST['end_title']) . '==' . $stitles . '==' . $ftitles . '==' . $db->safesql($_POST['link_start_del']) . '==' . $db->safesql($_POST['link_finish_del']);
            $start_short = $db->safesql($_POST['start_short']);
            $end_short = intval($_POST['end_short']) . '=' . intval($_POST['hide']) . '=' . intval($_POST['leech']) . '=' . intval($_POST['rewrite_news']);
            $sart_link = $db->safesql($_POST['sart_link']);
            $end_link = intval($_POST['end_link']);
            $sart_cat = $db->safesql($_POST['sart_cat']) . '|||' . $db->safesql($_POST['shab_data']) . '|||' . $db->safesql(str_replace('
', '===', $_POST['zhv_code']));
            $end_cat = $db->safesql($_POST['end_cat']);
            $dop_full = $db->safesql($_POST['dop_full']);
            if ($rss == '1') {
                $start_short = '';
                $sart_link = '';
                $ctp = '';
            }
            for ($x = 0;$x++ < $_POST['kol_xfields'];) {
                if ((trim($_POST['xfields_template_' . $x]) != '' or trim($_POST['def_dop_' . $x]) != '') and $_POST['rss_xfields_' . $x] != '0') {
                    $templ[] = $xfields_template = $db->safesql($_POST['rss_xfields_' . $x]) . '==' . $db->safesql($_POST['xfields_template_' . $x]) . '==' . intval($_POST['ret_xf_' . $x]) . '==' . intval($_POST['sh_fl_' . $x]) . '==' . intval($_POST['sh_im_' . $x]) . '==' . $db->safesql($_POST['xfields_delete_' . $x]) . '==' . $db->safesql($_POST['xfields_insert_' . $x]) . '==' . $db->safesql($_POST['rs_im_' . $x]) . '==' . intval($_POST['rs_sm_' . $x]) . '==' . intval($_POST['sh_nw_' . $x]) . '==' . $db->safesql($_POST['def_dop_' . $x]) . '==' . intval($_POST['full_stor_' . $x]);
                }
            }
            if ($templ) $xfields_template = implode('|||', $templ);
            else $xfields_template = '';
            $files = intval($_POST['files_video']) . '==' . $db->safesql($_POST['pap_video']) . '==' . intval($_POST['files_rar']) . '==' . $db->safesql($_POST['rar_video']) . '==' . intval($_POST['files_zip']) . '==' . $db->safesql($_POST['pap_zip']) . '==' . intval($_POST['files_doc']) . '==' . $db->safesql($_POST['pap_doc']) . '==' . intval($_POST['files_txt']) . '==' . $db->safesql($_POST['pap_txt']) . '==' . intval($_POST['files_dle']) . '==' . $db->safesql($_POST['pap_dle']) . '==' . intval($_POST['url_video']) . '==' . intval($_POST['url_rar']) . '==' . intval($_POST['url_zip']) . '==' . intval($_POST['url_doc']) . '==' . intval($_POST['url_txt']) . '==' . intval($_POST['url_dle']) . '==' . intval($_POST['tit_video']) . '==' . intval($_POST['tit_rar']) . '==' . intval($_POST['tit_zip']) . '==' . intval($_POST['tit_doc']) . '==' . intval($_POST['tit_txt']) . '==' . intval($_POST['tit_dle']) . '==' . intval($_POST['files_atach']) . '==' . $db->safesql($_POST['file_name']) . '==' . intval($_POST['files_tor']) . '==' . intval($_POST['url_tor']) . '==' . intval($_POST['tit_tor']) . '==' . $db->safesql($_POST['pap_tor']) . '==' . intval($_POST['tor_torrage']);
            if (trim($channel_title) == '') $channel_title = reset_urlk($rss_url);
            $channel_title = $db->safesql(str_replace('"', "&#34;", $channel_title));
            $channel_descr = $db->safesql($channel_descr);
            $db->query('UPDATE ' . PREFIX . "_rss SET title = '$channel_title', descr = '$channel_descr', rss = '$rss',category='$category', allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', cookies ='$cookies', start_template ='$start_template', finish_template ='$finish_template', delate = '$delate', load_img ='$allow_load', url ='$rss_url', allow_watermark ='$allow_water', date_format ='$date_format', keywords ='$keywords', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', stkeywords ='$stkeywords', ful_start='$ful_start', start_title='$start_title', start_short='$start_short', end_short='$end_short', sart_link='$sart_link', sart_cat='$sart_cat', xdescr='$xdescr', inser='$inser', start='$start', finish='$finish', end_title = '$end_title', end_link = '$end_link', short_story='$short_story', dop_nast='$dop_nast', full_link='$full_link', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', key_words='$key_words', kategory='$kategory', xfields_template='$xfields_template', dop_full='$dop_full', files='$files' WHERE id ='$id'");
            if (!$inf) header("Status: 204 ");
            if (trim($channel_title) != '') {
                $title = stripslashes(strip_tags_smart($channel_title));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            $mgs = $lang_grabber['channel'] . ' <font color="green">"' . $title . ' | ' . $rss_url . '"</font> <font color="red">' . $lang_grabber['edit_channel_ok'] . '</font><br />';
            msg($lang_grabber['info'], $lang_grabber['change_channel'], $mgs . ($inf ? '<br />* * *<br /><b style="color:#ff0000;">' . $lang_grabber['add_msg_er'] . '</b><br /><br /><a class="list" href="admin.php?mod=rss&action=channel&subaction=edit&id=' . $inf['id'] . '"><b style="color:blue;">' . $inf['title'] . '</b></a>' : '') . '<br> <a href="' . $PHP_SELF . '?mod=rss"  class="btn btn-success" style="text-decoration: none;color:#fff;">' . $lang_grabber['go_index'] . '</a> &nbsp;&nbsp;<a style="text-decoration: none;color:#fff;" class="btn btn-warning" href="javascript:history.go(-1)">' . $lang_grabber['back'] . '</a>');
            $db->close;
            return 1;
        }
    }
}
if (($action == 'channel') and ($subaction == 'edit')) {
    if (isset($id)) {
        if (!((!(trim($id) == '') AND !($id == 0)))) {
            msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['channel_msg_id'], "javascript:history.go(-1)");
            return 1;
        }
        $rss_channel_info = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id ='$id'");
        $rss_channel_info['title'] = str_replace('\\', '',$rss_channel_info['title']);
$rss_channel_info['title'] = stripslashes ($rss_channel_info['title']);
$rss_channel_info['title'] = str_replace('"',"&#34;",$rss_channel_info['title']);
$cookies = str_replace (' || | ','',stripslashes($rss_channel_info['cookies']));
$keywordsd = explode(' === ',$rss_channel_info['keywords']);
$keywords = str_replace (' || | ','',stripslashes ($keywordsd[0]));
$stkeywordsd = explode(' === ',$rss_channel_info['stkeywords']);
$stkeywords = str_replace (' || | ','',stripslashes ($stkeywordsd[0]));
$Autor = explode(' = ',$rss_channel_info['Autors']);
$Autors = str_replace (' || | ','',stripslashes ($Autor['0']));
$short_story = explode(' = ',$rss_channel_info['short_story']);
$date = explode(' = ',$rss_channel_info['date']);
$delate = str_replace (' || | ','',stripslashes ($rss_channel_info['delate']));
$inser = str_replace (' || | ','',stripslashes ($rss_channel_info['inser']));
$start = str_replace (' || | ','',$rss_channel_info['start']);
$finish = str_replace (' || | ','',$rss_channel_info['finish']);
$end_title = explode (' == ',$rss_channel_info['end_title']);
$hide_leech = explode(' = ',$rss_channel_info['end_short']);
$ctp = explode (' = ',$rss_channel_info['ctp']);
$dop_nast = explode (' = ',$rss_channel_info['dop_nast']);
$dnast = explode (' = ',$rss_channel_info['dnast']);
$categoryes = explode (' = ',$rss_channel_info['category']);
$stitles = str_replace (' || | ','',$end_title[2]);
$ftitles = str_replace (' || | ','',$end_title[3]);
$kategory = str_replace (' || | ','',$rss_channel_info['kategory']);
if(e_str(stripslashes ($date[0])) == 10) $date[0] = '';
$files = explode(' == ',$rss_channel_info['files']);
echoheader ('','');
if (trim ($rss_channel_info['title']) != '')
{
$channel_name = ' < / br > < fontcolor = green > & #8470;'.$rss_channel_info['xpos'].' - '.stripslashes ($rss_channel_info['title']) .'</font> (<font color=red>'.stripslashes ($rss_channel_info['url']) .'</font>) <a href="'.$rss_channel_info['url'] .'" target="_blank">[i]</a>';
        
    }
    $channel_inf = array();
    $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_rss_category ORDER BY kanal asc');
    $run[0] = '';
    while ($channel_info = $db->get_row($sql_result)) {
        if ($channel_info['osn'] == '0') $channel_inf[$channel_info['id']][$channel_info['id']] = $channel_info['title'];
        else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- ' . $channel_info['title'];
    }
    foreach ($channel_inf as $value) {
        if (count($value) != '0') {
            foreach ($value as $kkey => $key) {
                $run[$kkey] = $key;
            }
        }
    }
    opentable('<a href=' . $PHP_SELF . '?mod=rss>' . $lang_grabber['index_page'] . $lang_grabber['change_channel'] . $channel_name);
    $tpl->load_template('rss_addchannel.tpl');
    $tpl->set('{file-video}', ($files[0] == 0 ? '' : 'checked'));
    $tpl->set('{file-rar}', ($files[2] == 0 ? '' : 'checked'));
    $tpl->set('{file-zip}', ($files[4] == 0 ? '' : 'checked'));
    $tpl->set('{file-doc}', ($files[6] == 0 ? '' : 'checked'));
    $tpl->set('{file-txt}', ($files[8] == 0 ? '' : 'checked'));
    $tpl->set('{file-dle}', ($files[10] == 0 ? '' : 'checked'));
    $tpl->set('{file-tor}', ($files[26] == 0 ? '' : 'checked'));
    $tpl->set('{url-video}', ($files[12] == 0 ? '' : 'checked'));
    $tpl->set('{url-rar}', ($files[13] == 0 ? '' : 'checked'));
    $tpl->set('{url-zip}', ($files[14] == 0 ? '' : 'checked'));
    $tpl->set('{url-doc}', ($files[15] == 0 ? '' : 'checked'));
    $tpl->set('{url-txt}', ($files[16] == 0 ? '' : 'checked'));
    $tpl->set('{url-dle}', ($files[17] == 0 ? '' : 'checked'));
    $tpl->set('{url-tor}', ($files[27] == 0 ? '' : 'checked'));
    $tpl->set('{tit-video}', ($files[18] == 0 ? '' : 'checked'));
    $tpl->set('{tit-rar}', ($files[19] == 0 ? '' : 'checked'));
    $tpl->set('{tit-zip}', ($files[20] == 0 ? '' : 'checked'));
    $tpl->set('{tit-doc}', ($files[21] == 0 ? '' : 'checked'));
    $tpl->set('{tit-txt}', ($files[22] == 0 ? '' : 'checked'));
    $tpl->set('{tit-dle}', ($files[23] == 0 ? '' : 'checked'));
    $tpl->set('{tit-tor}', ($files[28] == 0 ? '' : 'checked'));
    $tpl->set('{file-atach}', ($files[24] == 0 ? '' : 'checked'));
    $tpl->set('{file-name}', $files[25]);
    $tpl->set('{tor-torrage}', ($files[30] == 0 ? '' : 'checked'));
    $tpl->set('{pap-video}', $files[1]);
    $tpl->set('{pap-rar}', $files[3]);
    $tpl->set('{pap-zip}', $files[5]);
    $tpl->set('{pap-doc}', $files[7]);
    $tpl->set('{pap-txt}', $files[9]);
    $tpl->set('{pap-dle}', $files[11]);
    $tpl->set('{pap-tor}', $files[29]);
    $tpl->set('{rss_html}', ($rss_channel_info['rss'] == 0 ? '' : 'checked'));
    $tpl->set('{stkeywords}', $stkeywords);
    $tpl->set('{charsets}', $dop_nast[14]);
    $tpl->set('{dubl-host}', ($dop_nast[15] == 0 ? '' : 'checked'));
    $tpl->set('{cache-link}', ($dnast[27] == 0 ? '' : 'checked'));
    $tpl->set('{one-serv}', ($dop_nast[11] == 0 ? '' : 'checked'));
    $tpl->set('{title}', $rss_channel_info['title']);
    $tpl->set('{discr}', stripslashes($rss_channel_info['descr']));
    $tpl->set('{address}', stripslashes($rss_channel_info['url']));
    $tpl->set('{date-format}', gen_date_format($rss_channel_info['date_format']));
    $tpl->set('{category}', categorynewsselection(explode(',', $categoryes[0]), 0));
    $tpl->set('{rss-priv}', sel($run, $categoryes[1]));
    $tpl->set('{groups}', get_groups(explode(',', $Autor['1'])));
    $tpl->set('{load-images}', ($rss_channel_info['load_img'] == 0 ? '' : 'checked'));
    $tpl->set('{thumb-images}', ($rss_channel_info['thumb_img'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-main}', ($rss_channel_info['allow_main'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-mod}', ($rss_channel_info['allow_mod'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-comm}', ($rss_channel_info['allow_comm'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-rate}', ($rss_channel_info['allow_rate'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-full}', ($rss_channel_info['allow_more'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-auto}', ($rss_channel_info['allow_auto'] == 0 ? '' : 'checked'));
    $tpl->set('{allow-water}', ($rss_channel_info['allow_watermark'] == 0 ? '' : 'checked'));
    $tpl->set('{show_autor}', ($dop_nast[5] == 0 ? '' : 'checked'));
    $tpl->set('{show_tegs}', ($dop_nast[6] == 0 ? '' : 'checked'));
    $tpl->set('{show_date}', ($dop_nast[7] == 0 ? '' : 'checked'));
    $tpl->set('{show_code}', ($dop_nast[8] == 0 ? '' : 'checked'));
    $tpl->set('{show_down}', ($dop_nast[13] == 0 ? '' : 'checked'));
    $tpl->set('{show_f}', ($dop_nast[9] == 0 ? '' : 'checked'));
    $tpl->set('{show_symbol}', ($dnast[2] == 0 ? '' : 'checked'));
    $tpl->set('{show_metatitle}', ($dnast[3] == 0 ? '' : 'checked'));
    $tpl->set('{show_metadescr}', ($dnast[4] == 0 ? '' : 'checked'));
    $tpl->set('{show_keywords}', ($dnast[5] == 0 ? '' : 'checked'));
    $tpl->set('{wat-host}', ($dnast[15] == 0 ? '' : 'checked'));
    $tpl->set('{rewrite-data}', ($dnast[17] == 0 ? '' : 'checked'));
    $tpl->set('{show-url}', ($dnast[6] == 0 ? '' : 'checked'));
    $tpl->set('{cron-auto}', $dnast[16]);
    $tpl->set('{kol-cron}', $dnast[19]);
    $tpl->set('{tags-kol}', $dnast[20]);
    $tpl->set('{rss-parse}', sel(array('0' => $lang_grabber['no_thumb'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full'], '3' => $lang_grabber['thumb_shortfull']), $dnast[7]));
    $tpl->set('{tags-auto}', ($dnast[8] == 0 ? '' : 'checked'));
    $tpl->set('{tags-zag}', ($dnast[21] == 0 ? '' : 'checked'));
    $tpl->set('{auto-metatitle}', ($dnast[9] == 0 ? '' : 'checked'));
    $tpl->set('{data-deap}', $dnast[10]);
    $tpl->set('{max-image}', $dnast[37]);
    $tpl->set('{deap}', deap($dnast[11] == 0 ? 'yes' : 'no'));
    $tpl->set('{symbol}', $rss_channel_info['symbol']);
    $tpl->set('{auto-symbol}', ($dnast[12] == 0 ? '' : 'checked'));
    $tpl->set('{auto-numer}', sel(array(1 => '1', 2 => '2', 3 => '3'), $dnast[13]));
    $tpl->set('{show_date_expires}', ($dnast[14] == 0 ? '' : 'checked'));
    $tpl->set('{tags}', $rss_channel_info['ftags']);
    $tpl->set('{meta-title}', $rss_channel_info['metatitle']);
    $tpl->set('{meta-descr}', $rss_channel_info['meta_descr']);
    $tpl->set('{key-words}', $rss_channel_info['key_words']);
    $tpl->set('{hide}', ($hide_leech[1] == 0 ? '' : 'checked'));
    $tpl->set('{leech}', ($hide_leech[2] == 0 ? '' : 'checked'));
    $tpl->set('{leech-shab}', stripslashes($dnast[25]));
    $tpl->set('{rewrite-news}', ($hide_leech[3] == 0 ? '' : 'checked'));
    $tpl->set('{rewrite-con}', sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), $dnast[29]));
    $tpl->set('{rewrite-no}', ($dnast[30] == 0 ? '' : 'checked'));
    $tpl->set('{leech-dop}', ($dnast[31] == 0 ? '' : 'checked'));
    $tpl->set('{convert-utf}', ($dnast[32] == 0 ? '' : 'checked'));
    $tpl->set('{title-gener}', ($dnast[33] == 0 ? '' : 'checked'));
    $tpl->set('{clear-short}', ($short_story[0] == 0 ? '' : 'checked'));
    $tpl->set('{clear-full}', ($short_story[17] == 0 ? '' : 'checked'));
    $tpl->set('{short-images}', ($short_story[1] == 0 ? '' : 'checked'));
    $tpl->set('{short-images-p}', sel(array(0 => $lang_grabber['sel_lu'], 1 => $lang['upload_t_seite_2'], 2 => $lang['upload_t_seite_3']), $short_story[22]));
    $tpl->set('{kol-image-short}', $dnast[39]);
    $tpl->set('{zip-image}', ($dnast[40] == 0 ? '' : 'checked'));
    $tpl->set('{kpop-image}', $dnast[44]);
    $tpl->set('{nostor-image}', ($dnast[45] == 0 ? '' : 'checked'));
    $tpl->set('{min-image}', $dnast[38]);
    $tpl->set('{auto-chpu}', ($dnast[43] == 0 ? '' : 'checked'));
    $tpl->set('{short-full}', ($short_story[2] == 0 ? '' : 'checked'));
    $tpl->set('{pings}', ($short_story[4] == 0 ? '' : 'checked'));
    $tpl->set('{teg-fix}', stripslashes($short_story[5]));
    $tpl->set('{cat-nul}', ($short_story[6] == 0 ? '' : 'checked'));
    $tpl->set('{text-html}', ($short_story[9] == 0 ? '' : 'checked'));
    $tpl->set('{dim-week}', ($date[1] == 0 ? '' : 'checked'));
    $tpl->set('{dim-date}', ($date[2] == 0 ? '' : 'checked'));
    $tpl->set('{dim-sait}', ($date[3] == 0 ? '' : 'checked'));
    $tpl->set('{dim-cat}', ($date[4] == 0 ? '' : 'checked'));
    $tpl->set('{title-prob}', ($short_story[11] == 0 ? '' : 'checked'));
    $tpl->set('{no-prow}', dubl_news($short_story[12]));
    $tpl->set('{grab-pause}', $dop_nast[19]);
    $tpl->set('{add-pause}', $dop_nast[21]);
    $tpl->set('{kol-short}', $dop_nast[22]);
    $tpl->set('{sim-short}', $dop_nast[24]);
    $tpl->set('{starter-page}', $dop_nast[25]);
    $tpl->set('{page-break}', $dop_nast[23]);
    $tpl->set('{image-align}', gen_x($dnast[0], 4));
    $tpl->set('{image-align-full}', gen_x($dnast[1], 4));
    $tpl->set('{image-align-post}', gen_x($dnast[36] == '' ? $dnast[1] : $dnast[36], 4));
    $tpl->set('{start-template}', str_replace('&', '&amp;', stripslashes($rss_channel_info['start_template'])));
    $tpl->set('{end-template}', str_replace('&', '&amp;', stripslashes(str_replace('|||', '
', $rss_channel_info['finish_template']))));
    $finish = str_replace('|||', '
', $rss_channel_info['finish']);
    $tpl->set('{x}', gen_x($dop_nast[3]));
    $tpl->set('{y}', gen_y($dop_nast[4]));
    $tpl->set('{delate}', stripslashes(@htmlspecialchars($delate, ENT_QUOTES, $config['charset'])));
    $tpl->set('{inser}', stripslashes(@htmlspecialchars($inser, ENT_QUOTES, $config['charset'])));
    $tpl->set('{start}', stripslashes(@htmlspecialchars($start, ENT_QUOTES, $config['charset'])));
    $tpl->set('{finish}', stripslashes(@htmlspecialchars($finish, ENT_QUOTES, $config['charset'])));
    $tpl->set('{full-link}', stripslashes(@htmlspecialchars($rss_channel_info['full_link'], ENT_QUOTES, $config['charset'])));
    $tpl->set('{dop-full}', stripslashes(@htmlspecialchars($rss_channel_info['dop_full'], ENT_QUOTES, $config['charset'])));
    $tpl->set('{so}', $ctp[0]);
    $tpl->set('{po}', $ctp[1]);
    $tpl->set('{dop-watermark}', ($dop_nast[0] == 0 ? '' : 'checked'));
    $tpl->set('{watermark-image-light}', stripslashes($dnast[23]));
    $tpl->set('{watermark-image-dark}', stripslashes($dnast[24]));
    $tpl->set('{add-full}', ($short_story[20] == 0 ? '' : 'checked'));
    $tpl->set('{lang-title}', ($dnast[34] == 1 ? '' : 'checked'));
    $tpl->set('{lang-title-komb}', ($dnast[35] == 0 ? '' : 'checked'));
    $tpl->set('{yan-on}', ($dnast[41] == 0 ? '' : 'checked'));
    list($yanin, $yanout) = explode("-", $dnast[42]);
    $tpl->set('{lang-yan-in}', lang_yan($yanin == '' ? 'ru' : $yanin));
    $tpl->set('{lang-yan-out}', lang_yan($yanout == '' ? 'ru' : $yanout));
    $tpl->set('{lang-on}', ($short_story[13] == 0 ? '' : 'checked'));
    $tpl->set('{lang-out}', slected_lang($short_story[15] == '' ? 'en' : $short_story[15]));
    $tpl->set('{lang-in}', slected_lang($short_story[14] == '' ? 'ru' : $short_story[14]));
    $tpl->set('{lang-outf}', slected_lang($short_story[18] == '' ? '' : $short_story[18]));
    $tpl->set('{cat-sp}', ($short_story[16] == 0 ? '' : 'checked'));
    $tpl->set('{text-url-sel}', sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), $dop_nast[16]));
    $tpl->set('{full-url-and}', ($dop_nast[18] == 0 ? '' : 'checked'));
    $tpl->set('{parse-url-sel}', sel(array('0' => $lang_grabber['no_thumb'], '1' => $lang_grabber['thumb_full'], '2' => $lang_grabber['thumb_short'], '3' => $lang_grabber['thumb_shortfull']), $dop_nast[17]));
    $tpl->set('{log-pas}', ($short_story[8] == 0 ? '' : 'checked'));
    $tpl->set('{log-cookies}', ($short_story[21] == 1 ? '' : 'checked'));
    $tpl->set('{keyw-sel}', sel(array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener'], 5 => $lang_grabber['sel_don']), $short_story[7]));
    $tpl->set('{descr-sel}', sel(array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener'], 5 => $lang_grabber['sel_don']), $short_story[10]));
    $tpl->set('{text-url}', sel(array(0 => $lang_grabber['no_izm'], 1 => $lang_grabber['url_klik'], 2 => $lang_grabber['url_no_donw'], 3 => $lang_grabber['url_no_donor']), $dop_nast[1]));
    $tpl->set('{prox}', ($dop_nast[2] == 0 ? '' : 'checked'));
    $tpl->set('{null}', ($dop_nast[10] == 0 ? '' : 'checked'));
    $tpl->set('{load-img}', server_host($rss_channel_info['load_img']));
    $tpl->set('{margin}', intval($dop_nast[12]));
    $tpl->set('{xdescr}', htmlspecialchars($rss_channel_info['xdescr'], ENT_QUOTES, $config['charset']));
    $tpl->set('{ful-start}', stripslashes($rss_channel_info['ful_start']));
    $tpl->set('{ful-end}', stripslashes($rss_channel_info['ful_end']));
    $tpl->set('{start-title}', stripslashes($rss_channel_info['start_title']));
    $tpl->set('{start-title-f}', ($dnast[22] == 0 ? '' : 'checked'));
    $tpl->set('{end-title}', stripslashes($end_title[1]));
    $tpl->set('{s-title}', stripslashes($end_title[0]));
    $tpl->set('{link-start-del}', stripslashes($end_title[4]));
    $tpl->set('{link-finish-del}', stripslashes($end_title[5]));
    $tpl->set('{sfr-short}', stripslashes($keywordsd[1]));
    $tpl->set('{efr-short}', stripslashes($stkeywordsd[1]));
    $tpl->set('{sfr-full}', stripslashes($keywordsd[2]));
    $tpl->set('{efr-full}', stripslashes($stkeywordsd[2]));
    $tpl->set('{end-del}', stripslashes($ftitles));
    $tpl->set('{s-del}', stripslashes($stitles));
    $tpl->set('{start-short}', stripslashes($rss_channel_info['start_short']));
    $tpl->set('{end-short}', ($hide_leech[0] == 0 ? '' : 'checked'));
    $tpl->set('{sart-link}', stripslashes($rss_channel_info['sart_link']));
    $tpl->set('{step-page}', $dop_nast[20]);
    $tpl->set('{end-link}', ($rss_channel_info['end_link'] == 0 ? '' : 'checked'));
    $sart_cat = explode('|||', $rss_channel_info['sart_cat']);
    $tpl->set('{sart-cat}', stripslashes($sart_cat[0]));
    $tpl->set('{shab-data}', stripslashes($sart_cat[1]));
    $tpl->set('{zhv-code}', stripslashes(@htmlspecialchars(str_replace('===', '
', $sart_cat[2]), ENT_QUOTES, $config['charset'])));
    $tpl->set('{end-cat}', stripslashes($rss_channel_info['end_cat']));
    $tpl->set('{date}', stripslashes($date[0]));
    $tpl->set('{cookies}', $cookies);
    $tpl->set('{keywords}', $keywords);
    $tpl->set('{Autors}', $Autors);
    $tpl->set('{kategory}', $kategory);
    $xfields_template = explode('|||', $rss_channel_info['xfields_template']);
    $list = rss_xfields(1);
    $template = "";
    $x = 1;
    foreach ($xfields_template as $value) {
        if ($value != '') {
            $key = explode('==', $value);
            if ($list[$key[0]] == '') $list[$key[0]] = $lang_grabber['list_dop_pole'];
            $template.= '

<div class="title_spoiler"><center><img id="image-full_' . $x . '" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg(\'full_' . $x . '\')"><b>' . strip_tags($list[$key[0]]) . ' [' . $key[0] . ']</b></a></center></div>

<div id="full_' . $x . '" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
 <!-- <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4" ><center><b>' . $lang_grabber['list_dop_pole'] . ' [' . $list[$key[0]] . ']</b></center></td>
  </tr> -->
  <tr>
   <td style="padding:4px"  align="center">' . $lang_grabber['rss_xfields'] . '
   <select name="rss_xfields_' . $x . '" class="load_img">
    ' . sel(rss_xfields('1'), $key[0]) . '
   </select><br>
   ' . $lang_grabber['use_po_get'] . '
<input type="checkbox" name="ret_xf_' . $x . '" value="1" ' . ($key[2] == 0 ? '' : 'checked') . ' />
    ' . $lang_grabber['take_short-story'] . '
<input type="checkbox" name="sh_fl_' . $x . '" value="1" ' . ($key[3] == 0 ? '' : 'checked') . ' />
	     ' . $lang_grabber['kol-vo'] . '
     <input name="rs_sm_' . $x . '" class="load_img" type="text" size="3" value="' . $key[8] . '">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['help_kol_xf_word'] . '\', this, event, \'500px\')">[?]</a><br />
	  ' . $lang_grabber['full_stor'] . '
<input type="checkbox" name="full_stor_' . $x . '" value="1" ' . ($key[11] == 0 ? '' : 'checked') . ' /> <br />
   ' . $lang_grabber['pole_img'] . '
<input type="checkbox" name="sh_im_' . $x . '" value="1" ' . ($key[4] == 0 ? 'no' : 'checked') . ' />
   ' . $lang_grabber['img_size'] . '
     <input name="rs_im_' . $x . '" class="load_img" type="text" size="10" value="' . $key[7] . '">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['opt_sys_maxsided'] . '\', this, event, \'500px\')">[?]</a>
	 <br>
' . $lang_grabber['kod_ost'] . '
<input type="checkbox" name="sh_nw_' . $x . '" value="1" ' . ($key[9] == 0 ? '' : 'checked') . ' /> <br><br>
' . $lang_grabber['def_dop'] . '
     <input name="def_dop_' . $x . '" class="load_img" type="text" size="50" value="' . $key[10] . '">

      </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">' . $add_bb . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_' . $x . '">' . stripslashes($key[1]) . '</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4"><center>' . $lang_grabber['templates_search_regular'] . ' ' . $lang_grabber['in_dop_pol'] . '</center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['expression'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_' . $x . '">' . stripslashes($key[5]) . '</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['paste'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_' . $x . '">' . stripslashes($key[6]) . '</textarea>
   </td></tr>
</table>
</div>
';
            $x++;
        }
    }
    $template.= '

<!-- <div><a href="javascript:ShowOrHide(\'full_' . $x . '\');"><center>' . $lang_grabber['add_new_dop'] . '</center></a></div> -->

<div class="title_spoiler"><center><img id="image-full_' . $x . '" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg(\'full_' . $x . '\')">' . $lang_grabber['add_new_dop'] . '</a></center></div>

<div id="full_' . $x . '" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
<!--    <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4" ><center><b>' . $lang_grabber['list_dop_pole'] . ' </b></center></td>
  </tr> -->
  <tr>
   <td style="padding:4px"  align="center">' . $lang_grabber['rss_xfields'] . '
   <select name="rss_xfields_' . $x . '" class="load_img">
    ' . sel(rss_xfields('1'), '') . '
   </select><br>
   ' . $lang_grabber['use_po_get'] . '
<input type="checkbox" name="ret_xf_' . $x . '" value="1"/>
   ' . $lang_grabber['take_short-story'] . '
     <input type="checkbox"  name="sh_fl_' . $x . '" value="1"/>
      ' . $lang_grabber['kol-vo'] . '
     <input name="rs_sm_' . $x . '" class="load_img" type="text" size="3" value="0">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['help_kol_xf_word'] . '\', this, event, \'500px\')">[?]</a>
   <br />
   ' . $lang_grabber['pole_img'] . '
      <input type="checkbox" name="sh_im_' . $x . '" value="1"/><br />
   ' . $lang_grabber['full_stor'] . '
<input type="checkbox" name="full_stor_' . $x . '" value="1" checked /> <br />
   ' . $lang_grabber['img_size'] . '
     <input name="rs_im_' . $x . '" class="load_img" type="text" size="10" value="">&nbsp;<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['opt_sys_maxsided'] . '\', this, event, \'500px\')">[?]</a>
' . $lang_grabber['kod_ost'] . '
     <input type="checkbox" name="sh_nw_' . $x . '" value="1"/>
   <br><br>
' . $lang_grabber['def_dop'] . '
     <input name="def_dop_' . $x . '" class="load_img" type="text" size="50" value="">
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">' . $add_bb . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_' . $x . '"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dotted #c4c4c4;  border-top:1px dotted #c4c4c4"><center>' . $lang_grabber['templates_search_regular'] . ' ' . $lang_grabber['in_dop_pol'] . '</center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['expression'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_' . $x . '"></textarea>
   </td></tr>
</table>


   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >' . $lang_grabber['paste'] . '</td>
   <td width="83%" style="padding:4px">' . $add_bbz . '<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_' . $x . '"></textarea>
   </td></tr>
</table>
</div>
<a style="padding:10px" href="' . $PHP_SELF . '?mod=xfields&xfieldsaction=configure" target="_blank">' . $lang_grabber['list_dop_pole'] . '</a><br /><br />';
    $tpl->set('{kol-xfields}', $x);
    $tpl->set('{xfields-template}', $template);
    if (@file_exists($rss_plugins . 'sinonims.php')) {
        $sin = '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['sinonims'] . ':</td>
   <td width="768" style="padding:4px">
  <input type="checkbox" name="sinonim" value="1" ' . ($short_story[3] == 0 ? '' : 'checked') . ' value="1"/>
   <select name="sinonim_sel" class="load_img">' . sel(array('0' => $lang_grabber['thumb_shortfull'], '1' => $lang_grabber['thumb_short'], '2' => $lang_grabber['thumb_full']), $short_story[19]) . '
   </select>
  </td>
  </tr>';
    }
    if (@file_exists(ENGINE_DIR . '/inc/crosspost.addnews.php')) {
        $sin.= '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['crosspost'] . ':</td>
   <td width="768" style="padding:4px">
   <input type="checkbox" name="cross_post" ' . ($dnast[26] == 0 ? '' : 'checked') . ' value="1"/>
  </td>
  </tr>';
    }
    if ((@file_exists(ENGINE_DIR . '/modules/twitter.php') or @file_exists(ENGINE_DIR . '/modules/socialposting/posting.php'))) {
        $sin.= '
  <tr style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;">
   <td style="padding:4px"  width="304">' . $lang_grabber['twitter'] . ':</td>
   <td width="768" style="padding:4px">
   <input type="checkbox" name="twitter_post" ' . ($dnast[28] == 0 ? '' : 'checked') . ' value="1"/>
  </td>
  </tr>';
    }
    $tpl->set('{sinonim}', $sin);
    $tpl->set('{opt_sys_yes}', $lang['opt_sys_yes']);
    $tpl->set('{opt_sys_no}', $lang['opt_sys_no']);
    foreach ($lang_grabber as $key => $value) {
        $tpl->set('{' . $key . '}', $value);
    }
    $form = '   <form method="post" >
    <input type="hidden" name="id" value="' . $id . '" />
    <input type="hidden" name="action" value="channel" />
    <input type="hidden" name="subaction" value="do_change" />';
    include_once (ENGINE_DIR . '/inc/include/inserttag.php');
    $tpl->set('{inserttag}', $bb_js);
    $form.= "
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
    $tpl->set('{BB_code}', $add_bb);
    $tpl->set('{BB_codez}', $add_bbz);
    $tpl->set('{BB_codezz}', $add_bbzz);
    $tpl->copy_template = $form . $tpl->copy_template . '
            <input align="left" class="btn btn-success" type="submit" OnClick="alert(\'' . $lang_grabber['channel'] . ' ' . $lang_grabber['edit_channel_ok'] . '\')" value=" ' . $lang_grabber['save'] . ' " >&nbsp;
            <input type="button" class="btn btn-warning" value=" ' . $lang_grabber['out'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" /></form>';
    $tpl->compile('rss');
    echo $tpl->result['rss'];
    closetable();
    echofooter();
    $db->close;
    return 1;
}
}
if ($action == 'doaddnews') {
    echoheader('', '');
    opentable($lang_grabber['add_news']);
    if ($_POST['channels']) {
        $xfields = xfieldsload();
        foreach ($xfields as $key => $val) {
            $xfields_loads[$val[0]] = $val;
        }
        foreach ($_POST['channels'] as $ckey => $channel_id) {
            $fieldvalue = array();
            $xdoe_channel = array();
            $news_per_channel = intval($_POST['news-per-channel-' . $channel_id]);
            $channel_info = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id ='$channel_id'");
            if (trim($channel_info['title']) != '') {
                $tit = stripslashes(strip_tags_smart($channel_info['title']));
                if (50 < e_str($tit)) {
                    $tit = e_sub($tit, 0, 50) . '...';
                }
            } else {
                $URL = get_urls(trim($channel_info['url']));
                $tit = $URL['host'];
            }
            echo '<b style="color:green;align:left;"> &#8470;' . $channel_info['xpos'] . ' - ' . $tit . '</b><br>';
            $dop_nast = explode('=', $channel_info['dop_nast']);
            $dates = explode('=', $channel_info['date']);
            $dnast = explode('=', $channel_info['dnast']);
            $dop_sort = explode('=', $channel_info['short_story']);
            $rss_files = explode('==', $channel_info['files']);
            if ($news_per_channel == 0) {
                $firt = array();
                foreach ($_POST as $key_con => $dsfa) if (preg_match("#mod#i", $key_con)) $firt[] = 1;
                $news_per_channel = count($firt) + 1;
            }
            $n = $news_per_channel - 1;
            $nn = 0;
            for ($di = 1;$di < $news_per_channel;++$di) {
                $db->close;
                $db->connect(DBUSER, DBPASS, DBNAME, DBHOST);
                $cp_output = $start_pos = "";
                $xdoe_files = array();
                $xdoe = array();
                if (intval($dop_nast[21]) != 0) sleep($dop_nast[21]);
                $news_selected = $_POST['sel_' . $di . $channel_id];
                if ($news_selected == 1) {
                    $tegs = stripslashes($_POST['tags_' . $di . $channel_id]);
                    if (count($_POST['category' . $di . $channel_id . '_'])) {
                        $category = $_POST['category' . $di . $channel_id . '_'];
                        $category_list = $db->safesql(implode(',', $_POST['category' . $di . $channel_id . '_']));
                    } else {
                        $category_list = '0';
                    }
                    $news_id = trim($_POST['news_id_' . $di . $channel_id]);;
                    $news_title = trim($_POST['title_' . $di . $channel_id]);
                    $_POST['title'] = html_entity_decode($news_title);
                    $alt_name = totranslit($news_title);
                    $short_story = $_POST['short_' . $di . $channel_id];
                    $full_story = $_POST['full_' . $di . $channel_id];
                    $sinonims_val = ($_POST['sinonims_' . $di . $channel_id] == 1 ? 1 : 0);
                    $crosspost_val = ($_POST['crosspost_' . $di . $channel_id] == 1 ? 1 : 0);
                    $twitter_val = ($_POST['twitter_' . $di . $channel_id] == 1 ? 1 : 0);
                    $rewrite = ($_POST['rewrite_' . $di . $channel_id] == 1 ? 1 : 0);
                    $approve = ($_POST['mod_' . $di . $channel_id] == 1 ? 1 : 0);
                    $allow_comm = ($_POST['comm_' . $di . $channel_id] == 1 ? 1 : 0);
                    $allow_main = ($_POST['main_' . $di . $channel_id] == 1 ? 1 : 0);
                    $allow_rate = intval($channel_info['allow_rate']);
                    $allow_more = intval($channel_info['allow_more']);
                    $thistime = $_POST['date-from-channel_' . $di . $channel_id];
                    $serv = $_POST['serv_' . $di . $channel_id];
                    $full_news_link = $_POST['news_link_' . $di . $channel_id];
                    $fieldvalue = $_POST['xfield' . $di . $channel_id . '_'];
                    $author = $db->safesql($_POST['autor_' . $di . $channel_id]);
                    $meta_title = $db->safesql($_POST['meta_title' . $di . $channel_id]);
                    $descr = $db->safesql($_POST['descr' . $di . $channel_id]);
                    $keywords = $db->safesql($_POST['keywords' . $di . $channel_id]);
                    $mets = ($_POST['met_' . $di . $channel_id] == 1 ? 1 : 0);
                    $expires = $_POST['expires_' . $di . $channel_id];
                    $dimages = '';
                    if ($mets == 1) $meta_title = $db->safesql(trim($meta_title . ' ' . $news_title));
                    else $meta_title = $db->safesql(trim($meta_title));
                    $alt_name = $db->safesql(totranslit(stripslashes($news_title), true, false));
                    $catalog_url = $db->safesql(e_sub(strip_tags_smart(stripslashes(trim($_POST['symbol' . $di . $channel_id]))), 0, 3));
                    $stop = false;
                    if ($category_list == '0' and $dop_sort[6] == 0) $stop = true;
                    if (preg_match("#{frag#", $short_story) or preg_match("#{frag#", $full_story) or preg_match("#{frag#", $news_title)) $stop = true;
                    if ($news_title != '' and !$stop) {
                        $safeTitle = $db->safesql($news_title);
                        if ($dop_sort[12] == 0) {
                            $where = " WHERE title like '%" . $safeTitle . "%'";
                        } elseif ($dop_sort[12] == 1 and $full_news_link != '') {
                            $where = " WHERE xfields like '%" . $db->safesql($full_news_link) . "%'";
                        } elseif ($dop_sort[12] == 2) {
                            $where = " WHERE title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "'";
                        } elseif ($dop_sort[12] == 3 and $full_news_link != '') {
                            $where = " WHERE title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "' or xfields like '%" . $db->safesql($full_news_link) . "%'";
                        } else {
                            $where = " WHERE title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "'";
                        }
                        $sql_Title = $db->query('SELECT * FROM ' . PREFIX . '_post' . $where);
                        $db_num_rows = $db->num_rows($sql_Title);
                        if ($db_num_rows == 0 or $rewrite == 1 or $dop_sort[12] == 0) {
                            if ($dop_sort[17] == 1 or intval($dop_sort[20]) == 1 or trim($full_story) != '') {
                                if ((trim($short_story) != '' or $dop_sort[0] == 1) and trim($news_title) != '') {
                                    include $rss_plugins . 'include/addnews.php';
                                    if (sizeof($xdoe) or sizeof($xdoe_files)) {
                                        echo $nn . '. <a class="list" href="index.php?newsid=' . $news_id . '" target="_blank"><b style="color:#9933FF;">' . $safet . '</b></a> ' . $ping_msg . ' <a class="list" href="' . $full_news_link . '" target="_blank">[link]</a><br />' . (sizeof($xdoe) ? '<b style="padding-left:15px;">' . $lang_grabber['post_msg_pics'] . '</b><br />' . preg_replace("#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i", '<a style="padding-left:15px;" class="list" href="" target="_blank"></a>', implode('<br />', $xdoe)) . '<br />' : '') . (sizeof($xdoe_files) ? '<b>' . $lang_grabber['post_msg_files'] . '</b><br />' . implode('<br />', $xdoe_files) : '') . '<br />';
                                    } else {
                                        echo $nn . '. <a class="list" href="index.php?newsid=' . $news_id . '" target="_blank"><b style="color:blue;">' . $safet . '</b></a> ' . $ping_msg . '<br>';
                                    }
                                    if ($cp_output != '') echo $cp_output . '<br>';
                                    ob_flush();
                                    flush();
                                }
                            }
                        }
                    }
                }
                $db->close;
            }
            echo ((sizeof($xdoe_channel)) ? '' : '<b style="color:red;">' . $lang_grabber['post_msg_no'] . '</b><br /><br />');
        }
        $db->close;
        if ($approve == '1' and $dop_sort[4] == 1 and @file_exists($rss_plugins . 'ping/pingsite.txt')) {
            $rss_lenta = new image_controller();
            $rss_lenta->download_host($config['http_home_url'] . 'engine/ajax/rss_lenta.php', 'nn=' . $nn);
            include ($rss_plugins . 'ping/grabberping.php');
        }
        if ($config_rss['sitemap'] == 'yes' and @file_exists($rss_plugins . 'ping/sitemap.php')) {
            include ($rss_plugins . 'ping/sitemap.php');
        }
        echo '<br /><a class=main href="' . $PHP_SELF . '?mod=rss">' . $lang_grabber['back'] . '</a><br /><br /></center>';
        closetable();
        echofooter();
        $db->free();
        $db->close;
        clear_cache();
    } else {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
    }
    return 1;
}
if (preg_match('/scan/i', $action)) {
    $channel = $_POST['channel'];
    $_POST['str_url'] = array_diff($_POST['str_url'], array(''));
    $count_str_url = count($_POST['str_url']);
    if ($count_str_url > '0') {
        $_POST['str_news'] = 1;
        $_POST['str_newf'] = $count_str_url;
        if (count($channel) == 0) {
            $ur_keys = array();
            foreach ($_POST['str_url'] as $ur_key) {
                $U = get_urls(trim($ur_key));
                if (!in_array($ur_keys, $sql_u['id'])) $ur_keys[] = $U;
                else continue;
                $sql_u = $db->super_query("SELECT id FROM " . PREFIX . "_rss WHERE url like '%" . $db->safesql($U['host']) . "%'");
                if ($sql_u['id'] != '' and !in_array($channel, $sql_u['id'])) $channel[] = $sql_u['id'];
            }
        }
    }
    if (intval($_POST['str_news']) != 0) {
        define('Y_GRAB_LIMIT', intval($_POST['str_news']) - 1);
    } else {
        define('Y_GRAB_LIMIT', 0);
    }
    if (intval($_POST['str_newf']) != 0) define('X_GRAB_LIMIT', intval($_POST['str_newf']));
    else define('X_GRAB_LIMIT', e_str($action) > 4 ? str_replace('scan', '', $action) : false);
    if (count($channel) == 0) {
        if ($count_str_url == '0') msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        else msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['no_grab_url'], "javascript:history.go(-1)");
        return 1;
    }
    $channel_list = @implode(',', $channel);
    $rss_parser = new rss_parser();
    $sql = $db->query('SELECT * FROM ' . PREFIX . ('_rss WHERE id IN (' . $channel_list . ') ORDER BY xpos,title ASC'));
    $db->close;
    $news_count = 1;
    echoheader('', '');
    echo '<form method=post name="news_form" id="news_form">';
    if ($config_rss['button_nw'] == "yes") $button_nw = '<button align="right" type="submit" class="btn" id="checkss" disabled> - ' . $lang_grabber['no_news_selected'] . ' - </button>';
    opentable($lang_grabber['grab_msg'], $button_nw);
    if ($config_rss['get_proxy'] == 'yes') get_proxy();
    $config_rss['get_prox'] = $tab_id;
    echo $bb_js . "<script type=\"text/javascript\">
var sin_open = 0;
var nosin_open = 0;
    function find_relates ( id )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');
        var title = ajax.encodeVAR( document.getElementById('title_' + id).value);
        var varsString = 'title=' + title;
        ajax.requestFile ='engine/ajax/find_relates.php';
        ajax.element = 'related_news' + id;
        ajax.sendAJAX(varsString);
return false;
    }

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

    function auto_keywords ( key, id )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');

        var wysiwyg = '{$config['allow_admin_wysiwyg']}';

            var short_txt = ajax.encodeVAR( document.getElementById('short_' + id).value );
            var varsString = \"short_txt=\" + short_txt;
            ajax.setVar(\"full_txt\", ajax.encodeVAR( document.getElementById('full' + id).value ));

        ajax.setVar(\"key\", key);
        ajax.requestFile = \"engine/ajax/keywords.php\";

        if (key == 1) { ajax.element = 'autodescr' + id; }
        else { ajax.element = 'keywords' + id;}

        ajax.method = 'POST';
        ajax.sendAJAX(varsString);

        return false;
    };

</script>

";
    echo '


<input type="hidden" name="action" value="doaddnews" />
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td style="padding:5px;" bgcolor="#FFFFFF">
    <script>
        var form = document.getElementById(\'news_form\');

        // ---------------------------------
        //  Check column
        // ---------------------------------
        function check_all ( permtype , master_box) {
        var ajax = new dle_ajax();
        ajax.onShow (\'\');
        var checkboxes = form.getElementsByTagName(\'input\');
        for (var i = 0; i < checkboxes.length; i++)
        {
            var element = checkboxes[i];
            if ( element && (element.id != \'mod\') && (element.id != \'main\') && (element.id != \'comm\') ) {
            var element_id = element.id;
            var a = element_id.replace( /^(.+?)_.+?$/, "$1" );
            if (a == permtype)
            {
             element.checked = master_box;
            }
            }
        }
ajax.onHide (\'\');
        return false;
        }
        // ---------------------------------
        //  Check all categories
        // ---------------------------------
        function check_cat() {
         var select_list = form.getElementsByTagName(\'select\');
         var value      = form.category.value;
         for (var i = 0; i < select_list.length; i++)
         {
            var element = select_list[i];
            element.value = value;
         }
         return false;
        }

function checkAll(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==\'\'?\'#E8F9E6\':\'\';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          =\'#E8F9E6\';
      }document.news_form.select_all.checked=true;
    }
}

function selectRow(evnt,elmnt){
  var ch=elmnt.getElementsByTagName("TD")[10].firstChild;
  tg = document.all?evnt.srcElement:evnt.target;
  //if(tg.tagName!=\'INPUT\')ch.checked=!ch.checked;
  elmnt.style.backgroundColor=ch.checked?\'#E8F9E6\':\'\';
}

    function preview( id )
    {
        dd=window.open(\'\',\'prv\',\'height=400,width=750,resizable=1,scrollbars=1\');
        document.addnews.target=\'prv\';
        document.addnews.title.value = document.getElementById(\'title_\' + id).value;
        document.addnews.short_story.value = document.getElementById(\'short_\' + id).value;
        if (document.getElementById(\'full\' + id)) {
        document.addnews.full_story.value = document.getElementById(\'full\' + id).value;
        } else {
        document.addnews.full_story.value = "";
        }
        document.addnews.allow_br.value = 1;
        document.addnews.submit();
    }
function ShowOrHideEx(id, show) {
    var item = null;
    if (document.getElementById) {
        item = document.getElementById(id);
    } else if (document.all) {
        item = document.all[id];
    } else if (document.layers){
        item = document.layers[id];
    }
    if (item && item.style) {
        item.style.display = show ? "" : "none";
    }
    }
    function xfInsertText(text, element_id) {
    var item = null;
    if (document.getElementById) {
        item = document.getElementById(element_id);
    } else if (document.all) {
        item = document.all[element_id];
    } else if (document.layers){
        item = document.layers[element_id];
    }
    if (item) {
        item.focus();
        item.value = item.value + " " + text;
        item.focus();
    }
    }

    </script>
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<link rel="stylesheet" href="engine/skins/grabber/chosen/chosen.css" />
<script src="engine/skins/grabber/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
$(document).ready(function(){
    $(".cat_select").chosen({disable_search_threshold: 5, allow_single_deselect:true, no_results_text: "{nosearch}"});

});
    </script>
';
    echo "
<script>
  $(document).ready(function(e){

  $('#news_form').submit(function() {
    $(this).ajaxSubmit(options);
    // !!! ¬ажно !!!
    // всегда возвращаем false, чтобы предупредить стандартные
    // действи¤ браузера (переход на страницу form.php)
    return false;
  });

    function countChecked() {
      var n = $(\".sel:checked\").length;
      $(\"#checks\").text(n == 0 ? '- " . $lang_grabber['no_news_selected'] . " -' : \"" . $lang_grabber['add_database'] . " \" + n + (n%10 == 1 ? \" " . $lang_grabber['post_one'] . "\" : (n <= 4 ? \" " . $lang_grabber['post_sm'] . "\" : \" " . $lang_grabber['post_big'] . "\")));
$('#checks').attr('disabled', n == 0 ? true : false);
if (n ==0 ){
	$(\"#checks\").toggleClass('btn-danger', true);
	$(\"#checks\").toggleClass('btn-success', false);
	}else{
$(\"#checks\").toggleClass('btn-danger',false);
$(\"#checks\").toggleClass('btn-success', true);
}
    }
    countChecked();
    $(\":checkbox\").click(countChecked);

    function countCheckeds() {
      var n = $(\".sel:checked\").length;
      $(\"#checkss\").text(n == 0 ? '- " . $lang_grabber['no_news_selected'] . " -' : \"" . $lang_grabber['add_database'] . " \" + n + (n%10 == 1 ? \" " . $lang_grabber['post_one'] . "\" : (n <= 4 ? \" " . $lang_grabber['post_sm'] . "\" : \" " . $lang_grabber['post_big'] . "\")));
$('#checkss').attr('disabled', n == 0 ? true : false);
if (n ==0 ){
	$(\"#checkss\").toggleClass('btn-danger', true);
	$(\"#checkss\").toggleClass('btn-success', false);
	}else{
$(\"#checkss\").toggleClass('btn-danger',false);
$(\"#checkss\").toggleClass('btn-success', true);
}
    }

    countCheckeds();
    $(\":checkbox\").click(countCheckeds);

  });

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
    include_once (ENGINE_DIR . '/inc/include/inserttag.php');
    echo $bb_js;
    while ($channel_info = $db->get_row($sql)) {
        $str_urls = array();
        $i = 1;
        $end_title = explode('==', $channel_info['end_title']);
        $dop_sort = explode('=', $channel_info['short_story']);
        $dop_nast = explode('=', $channel_info['dop_nast']);
        $ctp = explode('=', $channel_info['ctp']);
        $start_template = stripslashes($channel_info['start_template']);
        $finish_template = explode('|||', stripslashes($channel_info['finish_template']));
        $dnast = explode('=', $channel_info['dnast']);
        $sart_cat = explode('|||', $channel_info['sart_cat']);
        $cookies = str_replace('|||', '; ', str_replace("
", '', stripslashes(rtrim($channel_info['cookies']))));
        $allow_mod = ($channel_info['allow_mod'] == 0 ? 'checked' : '');
        $allow_main = ($channel_info['allow_main'] == 1 ? 'checked' : '');
        $allow_comm = ($channel_info['allow_comm'] == 1 ? 'checked' : '');
        $channel_id = $channel_info['id'];
        $hide_leech = explode('=', $channel_info['end_short']);
        $rewrite = ($hide_leech[3] == 1 ? 'checked' : '');
        $met = ($dnast[9] == 1 ? 'checked' : '');
        $config_rss['convert'] = (intval($dnast[32]) == 1 ? 'yes' : 'no');
        $rss = $channel_info['rss'];
        $charsets = $start_pos = '';
        $leech_shab = $dnast[25];
        if (trim($dop_nast[14]) != '' or $dop_nast[14] != '0') $charsets = explode('/', $dop_nast[14]);
        if (count($ctp) == 0 and $rss == 1) {
            $ctp[0] = 0;
            $ctp[1] = 0;
        }
        if ($_POST['str_kans'] and $_POST['str_kanf'] and $rss != 1) {
            $ctp[0] = $_POST['str_kans'];
            $ctp[1] = $_POST['str_kanf'];
        }
        $URL = get_urls(trim($channel_info['url']));
        if ($count_str_url > '0') {
            foreach ($_POST['str_url'] as $kk => $str_url) {
                if ($str_url == '') {
                    unset($_POST['str_url'][$kk]);
                    continue;
                }
                if (preg_match("#" . str_replace('www.', '', 'http://' . $URL['host']) . "#i", str_replace('www.', '', $str_url))) {
                    if (!(in_array($str_url, $str_urls))) $str_urls[] = $str_url;
                    unset($_POST['str_url'][$kk]);
                }
            }
            $_POST['str_newf'] = count($str_urls);
        }
        if (intval($_POST['str_news']) != 0) $text_str = '<br><font color="green"> ' . $lang_grabber['s_news'] . ' ' . $_POST['str_news'] . ' ' . $lang_grabber['po_news'] . ' ' . $_POST['str_newf'] . ' ' . $lang_grabber['post_one'] . '</font>';
        echo '  <input type="hidden" name="channels[]" value="' . $channel_id . '" />
    <fieldset style="border:1px dotted #c4c4c4;">
    <legend><font color="#C0C0C0">&#8470;' . $channel_info['xpos'] . ' - </font>' . $channel_info['title'] . '<br /><a href="http://' . $URL['host'] . '" target="_blank"><font color="blue">http://' . $URL['host'] . '</font></a>' . $text_str . '</legend>' . '';
        $cron_job = ENGINE_DIR . '/cache/cron_job.txt';
        if (@file_exists($cron_job)) {
            $job = file($cron_job);
            if ($job[0] == $channel_info['id']) {
                $lang_grabber['no_news'] = $lang_grabber['job_on_cron'];
                echo '
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     <b>' . $lang_grabber['job_on_cron'] . '</b>
    </td></tr>
    </table>
    </fieldset>';
                continue;
            }
        }
        $news_per_channel = 1;
        if ($ctp[1] > 0 and intval($ctp[0]) == 0) $ctp[0] = '1';
        for ($cv = $ctp[0];$cv <= $ctp[1];$cv++) {
            if (intval($dop_nast[20]) != '0' and intval($dop_nast[20]) != '1') {
                if (intval($dop_nast[25]) == 0) $dop_nast[25] = '1';
                $cvp = abs($cv * $dop_nast[20] - $dop_nast[20]);
                if ($cvp == 0) $cvp = intval($dop_nast[25]);
            } else {
                $cvp = abs($cv);
            }
            if (count($str_urls) == '0') {
                if ($cvp != 0 and $rss == 0) {
                    if ($channel_info['full_link'] == '') {
                        $rows = $channel_info['url'] . '/page/' . $cvp . '/';
                    } else {
                        $rows = str_replace('{num}', $cvp, $channel_info['full_link']);
                    }
                    if ($cvp == 0 or $cvp == 1) $pg = $lang_grabber['pst_st'];
                    else $pg = $lang_grabber['pst'] . $cvp;
                    echo '<table width="100%">
 <tr>
        <td ><a href="' . $rows . '" target="_blank"><b><font color="orange">' . $pg . '</font></b></a></td>
</tr>
</table>';
                    $URL = get_urls(trim($rows));
                }
                if ($rss == 1) {
                    $rss_parser->default_cp = $dop_nast[14];
                    $rss_result = $rss_parser->Get($channel_info['url'], $dop_nast[2]);
                } else {
                    $URLitems = get_full($URL['scheme'], $URL['host'], $URL['path'], $URL['query'], $cookies, $dop_nast[2], $dop_sort[8], $dop_sort[21]);
                    if ($URL['host'] == "vk.com") {
                        $rss_result = $URLitems["response"];
                        unset($rss_result[0]);
                        $chariks = 'utf-8';
                        $rss = 2;
                    } else {
                        if (trim($dop_nast[14]) == '' or $dop_nast[14] == '0') $chariks = charset($URLitems);
                        else $chariks = $charsets[0];
                        if ($channel_info['ful_start'] != '') {
                            $row_ful_start = explode("
", $channel_info['ful_start']);
                            if ($row_ful_start[1] != '') {
                                $URLitems = get_page($URLitems, $row_ful_start[1]);
                                $URLitems = $URLitems[0];
                            }
                            $rss_result = get_page($URLitems, $row_ful_start[0]);
                        } else {
                            $rss_result = get_dle($URLitems);
                        }
                    }
                }
                $time_stamp = time() + $config['date_adjust'] * 60;
                $time = date('Y-m-d H:i:s', $time_stamp);
            } else {
                $rss_result = $str_urls;
                $rss = 0;
            }
            if ($rss_result) {
                if ($rss == 1) {
                    if (X_GRAB_LIMIT) {
                        if ($rss_result['items_count'] > X_GRAB_LIMIT) $grab_lis = X_GRAB_LIMIT - $rss_result['items_count'];
                        else $grab_lis = X_GRAB_LIMIT;
                        $rss_result['items'] = array_slice($rss_result['items'], Y_GRAB_LIMIT, $grab_lis);
                    }
                    $rss_result = $rss_result['items'];
                } else {
                    if (X_GRAB_LIMIT && count($rss_result) > X_GRAB_LIMIT) {
                        if (count($rss_result) > X_GRAB_LIMIT) $grab_lis = X_GRAB_LIMIT - count($rss_result);
                        else $grab_lis = X_GRAB_LIMIT;
                        $rss_result = array_slice($rss_result, Y_GRAB_LIMIT, $grab_lis);
                    }
                }
                $news_str_channel = 1;
                $result = count($rss_result);
                echo ' <input type="hidden" name="news-per-result-' . $channel_id . '"   value="' . $result . '" />';
                if ($config_rss['reverse'] == 'no') $rss_result = array_reverse($rss_result, true);
                foreach ($rss_result as $skey => $item) {
                    echo ' <input type="hidden" name="news-result" value="1" />';
                    $skey = $skey + 1;
                    $tags_tmp = '';
                    $charik = '';
                    if (intval($dop_nast[19]) != 0) sleep($dop_nast[19]);
                    unset($news_link);
                    unset($news_tit);
                    unset($news_title);
                    unset($short_story);
                    unset($full_story);
                    unset($xfields_array);
                    if (count($str_urls) == '0') {
                        if ($rss == 1) {
                            $news_tit = rss_strip($item['title']);
                            $short_story = rss_strip($item['description']);
                            $news_link = stripslashes(rss_strip($item['link']));
                            $tags_tmp = rss_strip($item['category']);
                        } elseif ($rss == 2) {
                            $news_tit = rss_strip($item['title']);
                            if ($chariks != strtolower($config['charset'])) $news_tit = convert($chariks, strtolower($config['charset']), $news_tit);
                            $short_story = "[img]" . $item['image_medium'] . "[/img]";
                            $news_link = stripslashes(rss_strip($item['link']));
                            $tags_tmp = rss_strip($item['category']);
                        } else {
                            if ($chariks != strtolower($config['charset']) AND $item != '') $item = convert($chariks, strtolower($config['charset']), $item);
                            if (trim($channel_info['start_title']) != '' and $dnast[22] != 1) $news_tit = strip_tags_smart(get_full_news($item, $channel_info['start_title']));
                            if ($channel_info['end_link'] != 1) {
                                $short_story = rss_strip(get_short_news($item, $channel_info['start_short']));
                            } else {
                                $short_story = rss_strip(get_full_news($item, $channel_info['start_short']));
                            }
                            if (trim($channel_info['sart_link']) == '') {
                                $tu_link = get_link($item);
                                $news_link = 'http://' . $URL['host'] . '/index.php?newsid=' . $tu_link;
                            } else {
                                $news_lin = get_full_news($item, $channel_info['sart_link']);
                                $news_link = full_path_build($news_lin, $URL['host'], $URL['path']);
                            }
                        }
                        if ($rss == 1) {
                            if (trim($news_link) == '') {
                                $news_link = stripslashes(rss_strip($item['guid']));
                            }
                        }
                    } else {
                        $news_link = $item;
                    }
                    if (trim($news_tit) != '') {
                        echo "
        <div id=\"progressbar\"></div>
<script> storyes($skey, $result,'" . trim($news_tit) . "');</script>";
                    }
                    ob_flush();
                    flush();
                    if ($dnast[27] == 1) {
                        $link_cachefile = array_map("trim", @file(ENGINE_DIR . "/inc/plugins/files/cachefile.txt"));
                        if (!in_array($news_link, $link_cachefile)) openz(ENGINE_DIR . "/inc/plugins/files/cachefile.txt", $news_link . "
", 'a');
                        else continue;
                    }
                    $db->close;
                    $db->connect(DBUSER, DBPASS, DBNAME, DBHOST);
                    if (trim($end_title[2]) != '' and trim($news_tit) != '') $news_tit = rss_strip(relace_news($news_tit, $end_title[2], $end_title[3]));
                    $alt_name = $db->safesql(totranslit(stripslashes($news_tit), true, false));
                    $safeTitle = $db->safesql($news_tit);
                    $link = 'get_url' . $story;
                    $news_link = full_path_build($news_link, $URL['host'], $URL['path']);
                    if ($dop_sort[12] == 0) {
                        $where = ' LIMIT 1';
                    } elseif ($dop_sort[12] == 1 and $news_link != '') {
                        $where = " WHERE xfields like '%" . $db->safesql($news_link) . "%'";
                    } elseif ($dop_sort[12] == 2) {
                        $where = " WHERE title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "'";
                    } elseif ($dop_sort[12] == 3 and $news_link != '') {
                        $where = " WHERE xfields like '%" . $db->safesql($news_link) . "%' OR title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "'";
                    } else {
                        if ($safeTitle != '' and $alt_name != '') $where = " WHERE title = '" . $safeTitle . "' OR alt_name = '" . $alt_name . "'";
                        else $where = ' LIMIT 1';
                    }
                    $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_post' . $where);
                    if ($db->num_rows($sql_result) == 0 or $news_tit == '' or $hide_leech[3] == 1 or $dop_sort[12] == 0) {
                        include $rss_plugins . 'include/init.php';
                        if ($news_title != '') echo "
        <div id=\"progressbar\"></div>
<script> storyes($skey, $result,'$news_title');</script>";
                        ob_flush();
                        flush();
                        if (trim($news_title) == '') 'No title';
                        $full_allow_news = false;
                        $db->close;
                        $db->connect(DBUSER, DBPASS, DBNAME, DBHOST);
                        if ($dop_sort[12] == 0) {
                            $where = ' LIMIT 1';
                        } elseif ($dop_sort[12] == 1 and $news_link != '') {
                            $where = " WHERE xfields like '%" . $db->safesql($news_link) . "%'";
                        } elseif ($dop_sort[12] == 2) {
                            $where = " WHERE title = '" . $db->safesql($news_title) . "' OR alt_name = '" . $db->safesql($alt_name) . "'";
                        } elseif ($dop_sort[12] == 3 and $news_link != '') {
                            $where = " WHERE xfields like '%" . $db->safesql($news_link) . "%' OR title = '" . $db->safesql($news_title) . "' OR alt_name = '" . $db->safesql($alt_name) . "'";
                        } else {
                            $where = " WHERE title = '" . $db->safesql($news_title) . "' OR alt_name = '" . $db->safesql($alt_name) . "'";
                        }
                        $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_post' . $where);
                        if ($db->num_rows($sql_result) != 0 and $hide_leech[3] != 1) $full_allow_news = true;
                        if ($db->num_rows($sql_result) == 0 or $hide_leech[3] == 1 or $dop_sort[12] == 0) {
                            include $rss_plugins . 'include/parser.php';
                            if ($db->num_rows($sql_result) == 0 and $hide_leech[3] == 1) $rewrite = '';
                            if ($db->num_rows($sql_result) != 0 and $hide_leech[3] == 1) $rewrite = 'checked';
                            if ($dnast[30] == 1 and $db->num_rows($sql_result) == 0) {
                                $allow_news = false;
                                $full_allow_news = false;
                            }
                            if ($allow_news) {
                                $author_info = "&nbsp;<a onclick=\"javascript:window.open('?mod=editusers&action=edituser&user={$author}','User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500'); return(false)\" href=\"#\"><img src=\"engine/skins/grabber/images/adminrss.gif\" style=\"vertical-align: middle;border: none;\" /></a>";
                                $news_info = "&nbsp;<a onclick=\"javascript:window.open('{$news_link}','','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500'); return(false)\" href=\"google.com\"><img src=\"engine/skins/grabber/images/addresrss.gif\" alt='" . $lang_grabber['orig_news'] . "' title='" . $lang_grabber['orig_news'] . "' style=\"vertical-align: middle;border: none;\" /></a>";
                                $sin_bb = " <div id=\"sin_b\" class=\"editor_button\" onclick=\"simpletag('sin')\"><img title=\"{$lang_grabber['sin_bbcode']}\" src=\"engine/skins/grabber/bbcodes/images/sin2.gif\" width=\"23\" height=\"25\" border=\"0\"></div><div id=\"nosin_b\" class=\"editor_button\" onclick=\"simpletag('nosin')\"><img title=\"{$lang_grabber['nosin_bbcode']}\" src=\"engine/skins/grabber/bbcodes/images/nosin2.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";
                                $sin_bb.= '<div class="editor_button" onclick="javascript:window.open( \'' . $PHP_SELF . '?mod=rss&action=sinonim\')"><img title="' . $lang_grabber['base_sin'] . '" src="engine/skins/grabber/bbcodes/images/sin.gif" width="23" height="25" border="0"></div>';
                                $sin_but = "<input class=\"edit\" style=\"margin: 0 0 3 0px; background: #E8F9E6; font-size:9pt;\" onclick=\"simpletag('sin')\" type=\"button\"  value=\"sin\">  <input class=\"edit\" style=\"margin: 0 0 3 0px; background: #E8F9E6; font-size:9pt; text-decoration:line-through; \" onclick=\"simpletag('nosin')\" type=\"button\"    value=\"nosin\">
";
                                $key_wordss = '';
                                $descrs = '';
                                if ($dop_sort[7] == 5) $key_wordss = keyword($tags_tty);
                                $key_words = trim($key_wordss, " ,");
                                $key_words = e_sub($key_words, 0, 190 + strpos(e_sub($key_words, 190), ','));
                                if ($dop_sort[10] == 5) $descrs = description($tags_tty);
                                $descr = trim($descrs, " ,");
                                $descr = e_sub($descr, 0, 190 + strpos(e_sub($descr, 190), ' '));
                                $xfieldsaction = 'categoryfilter';
                                include ($rss_plugins . 'xfields.php');
                                echo $categoryfilter;
                                echo "  <script >
    $(document).ready(function(e){
    $(\"select#category$i$channel_id\").change(function (e) {
            var str = \"\";
            $(\"select#category$i$channel_id option:selected\").each(function () {
                str += $(this).text() + \"  \";
                });
            $(\".category$i$channel_id\").text(str);
        })
        .trigger('change');
    });
    </script>

<script>
    $(function(){
        $('#tags$i$channel_id').autocomplete({
            serviceUrl:'engine/ajax/tags_rss.php',
            minChars:3,
            delimiter: /(,|;)\s*/,
            maxHeight:400,
            width:348,
            deferRequestBy: 300
          });

    });
</script>
<table width=\"100%\">
    <tr class=\"light\" onMouseOut=this.className=\"light\"
       onMouseOver=this.className=\"highlight\"
       onclick=\"selectRow(event,this)\">
        <td style=\"padding:4px\" align=\"left\" ><a href=\"javascript:ShowOrHideg('full_$i$channel_id');\">$news_title_out</a>";
                                if ($dop_nast[5] == '0') $ava = ' style="display:none"';
                                if ($dop_nast[7] == '0') $avd = ' style="display:none"';
                                if ($dop_nast[6] == '0') $avt = ' style="display:none"';
                                if ($dop_nast[13] == '0') $avw = ' style="display:none"';
                                if ($dnast[2] == '0') $ada = ' style="display:none"';
                                if ($dnast[3] == '0') $add = ' style="display:none"';
                                if ($dnast[4] == '0') $adt = ' style="display:none"';
                                if ($dnast[5] == '0') $adw = ' style="display:none"';
                                if ($dnast[6] == '0') $adu = ' style="display:none"';
                                if ($dnast[14] == '0') $ade = ' style="display:none"';
                                if ($dnast[2] == '0' and $dnast[3] == '0' and $dnast[4] == '0' and $dnast[5] == '0' and $dnast[6] == '0') $adg = ' style="display:none"';
                                if ($dop_sort[17] == 0 and intval($dop_sort[20]) == 0) {
                                    if (trim($full_story) == '' and trim($news_link) != '') {
                                        echo "      <br /><font color=red>{$lang_grabber['no_full_story']}</font> ==> <a href=\"$news_link\" target=\"_blank\">{$news_link}</a>";
                                    }
                                }
                                echo "</td>
        <td align=\"right\" ><font color=red><div class=\"category$i$channel_id\"></div></font></td>
        <td width=\"1%\" ></td>";
                                if ($dnast[28] == 1 and (@file_exists(ENGINE_DIR . '/modules/twitter.php') or @file_exists(ENGINE_DIR . '/modules/socialposting/posting.php'))) {
                                    echo "  <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"twitter_$i$channel_id\" id=\"twitter_$i$channel_id\" checked value=\"1\" style=\"background-color: #ffffff; color: #0000FF;\" title=\"{$lang_grabber['twitter']}\" /></td>";
                                } else {
                                    echo "<td width=\"0px\" align=\"right\"></td>";
                                }
                                if ($dnast[26] == 1 and @file_exists(ENGINE_DIR . '/inc/crosspost.addnews.php')) {
                                    echo "  <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"crosspost_$i$channel_id\" id=\"crosspost_$i$channel_id\" checked value=\"1\" style=\"background-color: #ffffff; color: #FF6600;\" title=\"{$lang_grabber['crosspost']}\" /></td>";
                                } else {
                                    echo "<td width=\"0px\" align=\"right\"></td>";
                                }
                                if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
                                    echo "  <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"sinonims_$i$channel_id\" id=\"sinonims_$i$channel_id\" checked value=\"1\" style=\"background-color: #ffffff; color: #008000;\" title=\"{$lang_grabber['val_sinonims']}\" /></td>";
                                } else {
                                    echo "<td width=\"0px\" align=\"right\"></td>";
                                }
                                echo "  <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"rewrite_$i$channel_id\"  id=\"rewrite_$i$channel_id\" {$rewrite} value=\"1\" title=\"{$lang_grabber['val_rewrite']}\" /></td>
        <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"mod_$i$channel_id\"  id=\"mod_$i$channel_id\" {$allow_mod} value=\"1\" title=\"{$lang_grabber['val_mod']}\" /></td>
        <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"main_$i$channel_id\" id=\"main_$i$channel_id\" {$allow_main} value=\"1\" title=\"{$lang_grabber['val_main']}\"/></td>
        <td class=\"bd0\" align=\"right\"><input type=\"checkbox\"  name=\"comm_$i$channel_id\" id=\"comm_$i$channel_id\" {$allow_comm} value=\"1\" title=\"{$lang_grabber['val_comm']}\"/></td>
        <td class=\"bd0\" align=\"right\"><input class=\"sel\" type=\"checkbox\" name=\"sel_$i$channel_id\" id=\"sel\" value=\"1\" style=\"background-color: #ffffff; color: #ff0000;\" title=\"{$lang_grabber['val_post']}\" /></td>
</tr>
     <tr>
        <td colspan=\"10\">
<div style=\"padding-top:5px;padding-bottom:2px;display:none\" id=\"full_$i$channel_id\">
<div class=\"hr_line\"></div>
<table width=\"100%\">
    <tr>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['edit_et']}</td>
        <td><input size=\"70\" name=\"title_$i$channel_id\" id=\"title_$i$channel_id\" value=\"{$news_title_out}\" class=\"edit\" > <input class=\"btn btn-inverse\" type=\"button\" onClick=\"find_relates($i$channel_id); return false;\" style=\"width:100px;\" value=\"{$lang['b_find']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[hint_title]}', this, event, '220px')\">[?]</a> {$news_info} <div id=\"related_news$i$channel_id\"></div>
         </td>
    </tr>
    <tr $ava>
        <td  width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang_grabber['author']}</td>
        <td><input  name=\"autor_$i$channel_id\" id=\"autor_$i$channel_id\" size=\"30\" value=\"{$author}\">$author_info</td>
    </tr>
    <tr $avd>
        <td  width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$dat}:</td>
        <td><input  name=\"date-from-channel_$i$channel_id\" id=\"date-from-channel_$i$channel_id\" size=\"30\" value=\"{$thistime}\">
<img src=\"engine/skins/grabber/images/img.gif\"    align=\"absmiddle\" id=\"f_trigger_c_$i$channel_id\" style=\"cursor: pointer; border: 0\" title=\"{$lang['edit_ecal']}\"/>
<script type=\"text/javascript\">
Calendar.setup({inputField:\"date-from-channel_$i$channel_id\",ifFormat:\"%Y-%m-%d %H:%M\",button:\"f_trigger_c_$i$channel_id\",align : \"Br\",timeFormat:\"24\",showsTime:true,singleClick:true});
</script>
       </td>
</tr>
    <tr>
        <td  width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['edit_cat']}</td>
        <td><select name=\"category$i$channel_id []\" id=\"category$i$channel_id\" onchange=\"onCategoryChange$i$channel_id(this.value)\" class=\"cat_select\" multiple>{ $categories_list }</select>
        </td>
    </tr>
<tr $avt><td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang_grabber['tegs_post']}:</td>
<td><input size=\"70\" id=\"tags$i$channel_id\" name=\"tags_$i$channel_id\" value=\"{$tags_tmp}\" class=\"edit bk\" autocomplete=\"off\">
        </td>
    </tr>
</table>
<div class=\"hr_line\"></div>
<table width=\"100%\">";
                                echo "   <tr>
	<td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_short']}<br /></td>
    <td>";
                                if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
                                    if ($dop_nast[8] == '1') echo $sin_bb;
                                    else echo $sin_but;
                                }
                                if ($dop_nast[8] == '1') {
                                    echo $bb_panel;
                                }
                                echo "<textarea style=\"width:98%; height:200px\" onclick=\"setFieldName(this.name)\" id=\"short_$i$channel_id\" name=\"short_$i$channel_id\">{$short_story}</textarea>";
                                if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
                                    echo "<input class=\"edit\" type=\"button\" onClick=\"start_sinonims(1, $i$channel_id); return false;\" style=\"width:180px; background: #FFF9E0; border: 1px solid #8C8C8C;\" value=\"{$lang_grabber['sinonims_preview']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_sinonims_preview']}', this, event, '220px')\">[?]</a> <span id=\"sinonim_short$i$channel_id\"></span>";
                                }
                                echo "
    </td>
	</tr>
    <tr>
    <td  width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_full']}</td>
    <td><br />";
                                if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
                                    if ($dop_nast[8] == '1') echo $sin_bb;
                                    else echo $sin_but;
                                }
                                if ($dop_nast[8] == '1') {
                                    echo $bb_panel;
                                }
                                echo "<textarea style=\"width:98%; height:200px\" onclick=\"setFieldName(this.name)\" id=\"full$i$channel_id\" name=\"full_$i$channel_id\">" . @htmlspecialchars($full_story, ENT_QUOTES, $config['charset']) . "</textarea>";
                                if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
                                    echo "
<input class=\"edit\" type=\"button\" onClick=\"start_sinonims(2, $i$channel_id); return false;\" style=\"width:180px; background: #FFF9E0; border: 1px solid #8C8C8C;\" value=\"{$lang_grabber['sinonims_preview']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_sinonims_preview']}', this, event, '220px')\">[?]</a> <span id=\"sinonim_full$i$channel_id\"></span>";
                                }
                                echo "
    </td>
</tr>
<tr $avw>
    <td  width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang_grabber['pics_post']}:</td>
    <td>
<select name=\"serv_$i$channel_id\" id=\"serv_$i$channel_id\">
" . server_host($channel_info['load_img']) . "</select>
    <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_post_rad']}', this, event, '220px')\">[?]</a>
    </td>
</tr>
</table>
<div class=\"hr_line\"></div>
<table width=\"100%\">

";
                                if ($dop_nast[9] == '1') {
                                    echo "<tr >
        <td style=\"padding:4px\" align=\"left\" colspan=\"8\"><a href=\"javascript:ShowOrHideg('xf_$i$channel_id');\"><font color=\"green\">{$lang_grabber['doppol']}</font></a></td>
    </tr>
     <tr>
        <td colspan=\"8\">
<div style=\"display:none\" id=\"xf_$i$channel_id\">
<div class=\"hr_line\"></div>
<table width=\"100%\">";
                                    include $rss_plugins . 'include/xfields.php';
                                    if ($channel_info['allow_more'] == 1) {
                                        $fieldvalue['source_name'] = $channel_info['title'];
                                        $fieldvalue['source_link'] = $news_link;
                                    }
                                    $xfieldsaction = 'list';
                                    include ($rss_plugins . 'xfields.php');
                                    $config_code_bb = explode(',', $config_rss['code_bb']);
                                    $config_sin_dop = explode(',', $config_rss['sin_dop']);
                                    if ($config_rss['code_bb'] != '') {
                                        foreach ($config_code_bb as $value) {
                                            $output = str_replace('<!--' . $value . '-->', '<!--' . $value . '-->' . $bb_panel, $output);
                                        }
                                    }
                                    if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php') and $config_rss['sin_dop'] != '') {
                                        foreach ($config_sin_dop as $val) {
                                            if (in_array($val, $config_code_bb)) $output = str_replace('<!--' . $val . '-->', $sin_bb, $output);
                                            else $output = str_replace('<!--' . $val . '-->', $sin_but, $output);
                                        }
                                    }
                                    echo $output;
                                    echo '</table>
</div>
</td>
</tr>';
                                }
                                echo "<tr $adg>
        <td style=\"padding:4px\" align=\"left\" colspan=\"8\"><a href=\"javascript:ShowOrHideg('dop_$i$channel_id');\"><font color=\"green\">{$lang_grabber['dopmet']}</font></a></td>
    </tr>
     <tr>
        <td colspan=\"8\">
<div style=\"display:none\" id=\"dop_$i$channel_id\">
<table width=\"100%\" >
";
                                if (intval($dnast[10]) != 0) {
                                    $expires = date('Y-m-d H:i:s', (strtotime($thistime) + $dnast[10] * 86400));
                                    if ($dnast[11] == 1) $expi = 'selected';
                                    else $exp = 'selected';
                                } else {
                                    $expires = '';
                                }
                                if (intval($dnast[10]) != 0) {
                                    if ($expires != '') {
                                        $datede = strtotime($expires);
                                    } else {
                                        $datede = strtotime($thistime) + $dnast[10] * 86400;
                                    }
                                    $db->query('INSERT INTO ' . PREFIX . "_post_log (news_id, expires, action) VALUES('$news_id', '$datede', '{$dnast[11]}')");
                                }
                                echo "        <td colspan=\"2\"><div class=\"hr_line\"></div></td>
    <tr $ada>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['catalog_url']}</td>
        <td><input type=\"text\" name=\"symbol$i$channel_id\" size=\"5\"  class=\"edit\" value=\"{$channel_info['symbol']}\"><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[catalog_hint_url]}', this, event, '300px')\">[?]</a></td>
    </tr>
    <tr $adu>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_url']}</td>
        <td><input type=\"text\" name=\"alt_name$i$channel_id\" size=\"55\"  class=\"edit\" value=\"{$alt_names}\"><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[hint_url]}', this, event, '300px')\">[?]</a></td>
    </tr>

    <tr $ade>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['date_expires']}</td>
        <td><input type=\"text\" name=\"expires_$i$channel_id\" id=\"e_date_c_$i$channel_id\" size=\"20\" value=\"{$expires}\" class=edit>
<img src=\"engine/skins/grabber/images/img.gif\"  align=\"absmiddle\" id=\"e_trigger_c_$i$channel_id\" style=\"cursor: pointer; border: 0\" /> {$lang['cat_action']} <select name=\"expires_action_$i$channel_id\"><option $exp value=\"0\">{$lang['edit_dnews']}</option><option $expi value=\"1\" >{$lang['mass_edit_notapp']}</option></select><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang['hint_expires']}', this, event, '320px')\">[?]</a>
<script type=\"text/javascript\">
    Calendar.setup({
        inputField     :    \"e_date_c_$i$channel_id\",     // id of the input field
        ifFormat       :    \"%Y-%m-%d\",      // format of the input field
        button         :    \"e_trigger_c_$i$channel_id\",  // trigger for the calendar (button ID)
        align          :    \"Br\",           // alignment
        singleClick    :    true
    });
</script></td>
    </tr>

        <tr $add>
            <td  width=\"140\" height=\"29\" >&nbsp;</td>
            <td>{$lang['add_metatags']}<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang['hint_metas']}', this, event, '220px')\">[?]</a></td>
        </tr>

        <tr $add>
            <td  width=\"140\" height=\"29\"  style=\"padding-left:5px;\">{$lang['meta_title']}</td>
            <td><input type=\"text\" name=\"meta_title$i$channel_id\" style=\"width:388px;\" class=\"edit\" value=\"" . str_replace('{zagolovok}', $news_title, $channel_info['metatitle']) . "\"> <input type=\"checkbox\" name=\"met_$i$channel_id\" {$met} value=\"1\" title=\"{$lang_grabber['val_met']}\" /></td>
        </tr>
        <tr $adt>
            <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['meta_descr']}:<br><i>({$lang['meta_descr_max']})</i></td>
            <td><textarea type=\"text\" name=\"descr$i$channel_id\" id=\"autodescr$i$channel_id\" style=\"width:388px;height:70px;\" >{$descr}</textarea>
        </td>
        </tr>
        <tr $adw>
            <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['meta_keys']}:<br><i>({$lang['meta_descr_max']})</i></td>
            <td><textarea name=\"keywords$i$channel_id\" id='keywords$i$channel_id' style=\"width:388px;height:70px;\">{$key_words}</textarea>

            </td>
        </tr>
</table>
</div>
</td>
</tr>
";
                                echo "<input type=\"hidden\" name=\"news_link_$i$channel_id\" value=\"$news_link\">";
                                echo "<input type=\"hidden\" name=\"news_id_$i$channel_id\" value=\"$news_id\">";
                                echo "
<tr>
    <td><input class=\"edit\" style=\"background: #E8F9E6; font-size:9pt;\" onClick=\"preview($i$channel_id)\" type=\"button\"  value=\"{$lang['btn_preview']}\"></td><td align=right><a href=\"javascript:ShowOrHideg('full_$i$channel_id');\"><font color=orange>&uarr; {$lang_grabber['contr']} &uarr;</font></a></td>
    </tr>
</table>
<div class=\"hr_line\"></div>
</div>
</td>
</tr>
</table>
     <table cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">
     <tr><td style=\"border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4\" height=1 colspan=8></td></tr>
     </table>";
                                ++$i;
                                ++$news_count;
                                ++$news_per_channel;
                                ++$news_str_channel;
                                continue;
                            }
                        }
                    }
                    if ($full_allow_news or trim($news_title) == '') {
                        echo "<table width=\"100%\">
    <tr class=\"navigation\">
        <td style=\"padding:4px\" align=\"left\" >{$news_tit}</td></td>
        <td align=\"right\" > {$lang_grabber['intus_dabase']}</td></tr>
		<tr><td style=\"border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4\" height=1 colspan=7></td></tr>
		</table>";
                    } else {
                        echo "<table width=\"100%\">
    <tr class=\"navigation\">
        <td style=\"padding:4px\" align=\"left\" >{$news_title}</td></td>
        <td align=\"right\" > {$lang_grabber['filer_news']}</td></tr>
		<tr><td style=\"border-bottom:1px dotted #c4c4c4; border-top:1px dotted #c4c4c4\" height=1 colspan=7></td></tr>
		</table>";
                    }
                }
                if ($news_count == 1) {
                    echo '<div align="center" class="navigation">- ' . $lang_grabber['no_news'] . ' -</div>';
                }
                $str = $news_str_channel - 1;
                if ($cv != 0 and $rss == 0) {
                    if ($str == 1) $now = $lang_grabber['post_one'];
                    elseif ($str < 4) $now = $lang_grabber['news_sm'];
                    else $now = $lang_grabber['post_big'];
                    if ($str == 0) {
                        echo '<font color="#339900">- ' . $lang_grabber['no_news'] . ' -</font>';
                    } else {
                        echo '
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     <font color="green">' . $lang_grabber['yes_news'] . '<b>' . $str . '</b> ' . $now . '</font>
    </td></tr>
    </table>';
                    }
                }
            }
        }
        $kol = $news_per_channel - 1;
        echo '  <input type="hidden" name="news-per-channel-' . $channel_id . '"   value="' . $news_per_channel . '" />
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     ' . $lang_grabber['yes_news'] . ' <b>' . $kol . '</b> ' . $lang_grabber['post_big'] . '
    </td></tr>
    </table>
    </fieldset>';
        continue;
    }
    $db->free();
    $categories_list = categorynewsselection(0, 0);
    echo '<br/>';
    unterline();
    if (function_exists('memory_get_peak_usage')) {
        $mem_usage = memory_get_peak_usage(true);
        if ($mem_usage < 1024) echo $mem_usage . ' bytes';
        elseif ($mem_usage < 1048576) $memory_usage = round($mem_usage / 1024, 2) . ' kb';
        else $memory_usage = round($mem_usage / 1048576, 2) . ' mb';
    }
    $kolv = $news_count - 1;
    echo '  <table cellpadding="0" cellspacing="0" width="100%" border=0>
    <tr >
    <td class="navigation" style="padding:0px">
    ' . $lang_grabber['news_all'] . ': <b>' . $kolv . '</b><br /> ' . $lang_grabber['mem_gr'] . ' ~ ' . $memory_usage . '
    </td>
    <td align="right" style="padding:4px;">
     ' . $lang_grabber['gl_val'] . ':
     <select name="category" id="category" onChange="check_cat();" class="edit">
     ' . $categories_list . '
     </select></td>';
    if ($dnast[28] == 1 and (@file_exists(ENGINE_DIR . '/modules/twitter.php') or @file_exists(ENGINE_DIR . '/modules/socialposting/posting.php'))) {
        echo '<td class="bd0" align="right"><input    type="checkbox" name="twitter_all" id="twitter_all"   onClick="check_all(\'twitter\', this.checked);" title="' . $lang_grabber['twitter'] . ' (' . $lang_grabber['val_all'] . ')">';
    } else {
        echo "<td width=\"0px\" align=\"right\"></td>";
    }
    if ($dnast[26] == 1 and @file_exists(ENGINE_DIR . '/inc/crosspost.addnews.php')) {
        echo '<td class="bd0" align="right"><input    type="checkbox" name="crosspost_all" id="crosspost_all"   onClick="check_all(\'crosspost\', this.checked);" title="' . $lang_grabber['crosspost'] . ' (' . $lang_grabber['val_all'] . ')">';
    } else {
        echo "<td width=\"0px\" align=\"right\"></td>";
    }
    if ($dop_sort[3] == 1 and @file_exists($rss_plugins . 'sinonims.php')) {
        echo '<td class="bd0" align="right"><input    type="checkbox" name="sinonims_all" id="sinonims_all"   onClick="check_all(\'sinonims\', this.checked);" title="' . $lang_grabber['val_sinonims'] . ' (' . $lang_grabber['val_all'] . ')">';
    } else {
        echo "<td width=\"0px\" align=\"right\"></td>";
    }
    echo '</td>
        <td class="bd0" align="right">
    <input type="checkbox" name="rewrite_all" id="rewrite_all" onClick="check_all(\'rewrite\', this.checked);" title="' . $lang_grabber['val_rewrite'] . ' (' . $lang_grabber['val_all'] . ')"></td>
        <td class="bd0"  align="right">
    <input  type="checkbox" name="approve"  id="approve"    onClick="check_all(\'mod\', this.checked);" title="' . $lang_grabber['val_mod'] . ' (' . $lang_grabber['val_all'] . ')"></td>
        <td class="bd0"  align="right">
    <input type="checkbox" name="main_all"  id="main_all"   onClick="check_all(\'main\', this.checked);" title="' . $lang_grabber['val_main'] . ' (' . $lang_grabber['val_all'] . ')"></td>
        <td class="bd0"  align="right">
    <input type="checkbox" name="comm_all"  id="comm_all"   onClick="check_all(\'comm\', this.checked);" title="' . $lang_grabber['val_comm'] . ' (' . $lang_grabber['val_all'] . ')"></td>
        <td class="bd0"  align="right">
    <input type=checkbox name="select_all" id="select_all" onClick="checkAll(document.news_form.sel)" title="' . $lang_grabber['val_all'] . '">
    </td>
<td width="1%" >
</td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="0" width="100%">
    <tr>
    <td align="left" style="padding:4px"><input type="button" class="btn btn-warning btn-mini" value=" ' . $lang_grabber['out'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" /> </td>
    <td align="right" style="padding:4px"><div class="quick" ></div>

	<button align="right" type="submit" class="btn btn-success" id="checks" disabled> - ' . $lang_grabber['no_news_selected'] . ' - </button>

	</td>
    </tr>
    </table>
</td>
    </tr>
</table>
</div></form>
';
    echo "<form method=post name=\"addnews\" id=\"addnews\">
<input type=hidden name=\"mod\" value=\"preview\">
<input type=hidden name=\"title\" value=\"\">
<input type=hidden name=\"short_story\" value=\"\">
<input type=hidden name=\"full_story\" value=\"\">
<input type=hidden name=\"allow_br\" value=\"1\">
</form>";
    echo "<script>document.getElementById( 'loading-layer-text' ).innerHTML = '{$lang['ajax_info']}';</script>";
    unterline();
    closetable();
    echofooter();
    $db->close;
    return 1;
}
if ($action == 'config') {
    include $rss_plugins . 'config.php';
    return 1;
}
if ($action == 'upload') {
    if (!is_dir($rss_plugins . 'files')) {
        @mkdir($rss_plugins . 'files', 0777);
        @chmod($rss_plugins . 'files', 0777);
    }
    $uploadfile = ENGINE_DIR . '/inc/plugins/files/proxy.txt';
    if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile) and $_FILES['uploadfile']['type'] == 'text/plain') {
        echo '<font color="green">' . $lang_grabber['file_pr_sv'] . ' </font> <font color="red">' . $lang_grabber['file_loaded'] . ' ' . date('Y-m-d H:i:s', filectime(ENGINE_DIR . '/inc/plugins/files/proxy.txt')) . '</font>';
    } else {
        @unlink($uploadfile);
        echo "<font color=\"red\">{$lang_grabber['eror']}! {$lang['images_uperr_3']}</font>";
    }
    exit();
}
if ($action == 'copy_channel') {
    $ids = $_POST['channel'];
    if (count($ids) == 0) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        return 1;
    }
    foreach ($ids as $id) {
        $copys = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
        $copy = array();
        $sql_result = $db->query('SELECT url FROM ' . PREFIX . '_rss');
        $copys['xpos'] = $db->num_rows($sql_result) + 1;
        $copys['id'] = '';
        $copys['title'] = '[' . $lang_grabber['cops_canal'] . '] ' . $copys['title'];
        foreach ($copys as $key => $value) $copy[$key] = "'" . $db->safesql(stripslashes($value)) . "'";
        $copye = implode(',', $copy);
        $db->query('INSERT INTO ' . PREFIX . "_rss VALUES ({$copye})");
        if (trim($copy['title']) != '') {
            $title = stripslashes(strip_tags_smart($copy['title']));
            if (50 < e_str($title)) {
                $title = e_sub($title, 0, 50) . '...';
            }
        } else {
            $title = $lang_grabber['no_title'];
        }
        $mgs.= $lang_grabber['channel'] . ' ' . $copy['xpos'] . '<font color="green">"' . $title . ' | ' . $copy['url'] . '"</font> <font color="red">' . $lang_grabber['copy_channel_ok'] . '</font><br />';
    }
    msg($lang_grabber['info'], $lang_grabber['channel_copy'], $mgs, $PHP_SELF . '?mod=rss');
    return 1;
}
if ($action == 'auto_channel') {
    $ids = $_POST['channel'];
    if (count($ids) == 0) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        return 1;
    }
    foreach ($ids as $id) {
        $auto = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
        $db->query('UPDATE ' . PREFIX . "_rss SET allow_auto = 1 WHERE id ='$id'");
        if (trim($auto['title']) != '') {
            $title = stripslashes(strip_tags_smart($auto['title']));
            if (50 < e_str($title)) {
                $title = e_sub($title, 0, 50) . '...';
            }
        } else {
            $title = $lang_grabber['no_title'];
        }
        $mgs.= $lang_grabber['channel'] . ' <font color="green">"' . $title . ' | ' . $auto['url'] . '"</font> <font color="red">' . $lang_grabber['auto_channel_ok'] . '</font><br />';
    }
    clear_cache('cron.rss');
    msg($lang_grabber['info'], $lang_grabber['channel_auto_y'], $mgs, $PHP_SELF . '?mod=rss');
    return 1;
}
if ($action == 'noauto_channel') {
    $ids = $_POST['channel'];
    if (count($ids) == 0) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        return 1;
    }
    foreach ($ids as $id) {
        $auto = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
        $db->query('UPDATE ' . PREFIX . "_rss SET allow_auto = '0' WHERE id = '$id' ");
        if (trim($auto['title']) != '') {
            $title = stripslashes(strip_tags_smart($auto['title']));
            if (50 < e_str($title)) {
                $title = e_sub($title, 0, 50) . '...';
            }
        } else {
            $title = $lang_grabber['no_title'];
        }
        $mgs.= $lang_grabber['channel'] . ' <font color="green">"' . $title . ' | ' . $auto['url'] . '"</font> <font color="red">' . $lang_grabber['auto_channel_no'] . '</font><br />';
    }
    clear_cache('cron.rss');
    msg($lang_grabber['info'], $lang_grabber['channel_auto_n'], $mgs, $PHP_SELF . '?mod=rss');
    return 1;
}
if ($action == 'del_channel') {
    $ids = $_POST['channel'];
    if (count($ids) == 0) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        return 1;
    }
    if ($_POST['act'] == 'sav') {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $del = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
            $db->query('DELETE FROM ' . PREFIX . ('_rss WHERE id = \'' . $id . '\''));
            if (trim($del['title']) != '') {
                $title = stripslashes(strip_tags_smart($del['title']));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            $mgs.= $lang_grabber['channel'] . ' <font color="green">"' . $title . ' | ' . $del['url'] . '"</font> <font color="red">' . $lang_grabber['del_channel_ok'] . '</font><br />';
        }
        clear_cache('cron.rss');
        msg($lang_grabber['info'], $lang_grabber['del_channel'], $mgs, $PHP_SELF . '?mod=rss');
        return 1;
    } elseif ($_POST['act'] != 'sav') {
        echoheader('', '');
        opentable($lang_grabber['del_channel']);
        foreach ($ids as $id) {
            $del = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
            if (trim($del['title']) != '') {
                $title = stripslashes(strip_tags_smart($del['title']));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            $mgs.= ' <font color="green">"' . $title . ' | ' . $del['url'] . '"</font> <font color="red"><br />';
        }
        $ids = implode(',', $ids);
        echo '
<form method="post" name="del_channel" id="del_channel">
<input type="hidden" name="action" value="del_channel">
<input type="hidden" name="act" value="sav">
<table width="100%">
    <tr>
 <td align="center">
<b><font color="red">' . $lang_grabber['del_action'] . '</font></b><br /><br />' . $mgs . '
</td>
</tr>
    <tr>
 <td align="center">
 <br />
<input type="hidden" name="channel" value="' . $ids . '">
<input type="submit" class="btn btn-success"   value=" ' . $lang['opt_sys_yes'] . ' " ">
<input type="button" class="btn btn-warning"   value=" ' . $lang['opt_sys_no'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" />
</td>
    </tr>
</table>
</form>';
        closetable();
        echofooter();
        return 1;
    }
}
if ($action == 'sort') {
    $xpos = $_POST['xpos'];
    $i = 1;
    foreach ($xpos as $k => $v) {
        $db->query('UPDATE ' . PREFIX . ('_rss set xpos=' . ((int)$v) . ' WHERE id = \'' . ((int)$k) . '\''));
        $i++;
    }
    msg($lang_grabber['info'], $lang_grabber['sort_channel'], $lang_grabber['sort_channel_ok'], $PHP_SELF . '?mod=rss');
    return 1;
}
if (($config['updgrab'] - time()) < 1) {
    unset($config['keygrab']);
    unset($config['updgrab']);
}

if ($action == 'sinonim') {
    include $rss_plugins . 'add.sin.php';
    return 1;
}
if ($action == 'get_proxy') {
    include $rss_plugins . 'proxy.php';
    if (get_proxy() == true) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['prox_up_ed'], $PHP_SELF . '?mod=rss&action=config');
    } else {
        $time = time() - filectime(ENGINE_DIR . '/inc/plugins/files/proxy.txt');
        if ($time <= 3600) $inf = $lang_grabber['up_time'] . date('i', (1200 - $time)) . ' min.';
        else $inf = $lang_grabber['no_upd_prox'];
        msg($lang_grabber['info'], $lang_grabber['info'], $inf, $PHP_SELF . '?mod=rss&action=config');
    }
    return 1;
}
if ($action == 'grups') {
    include $rss_plugins . 'add.grups.php';
    return 1;
}
if ($action == 'addgrup_channel') {
    $ids = $_POST['channel'];
    if (count($ids) == 0) {
        msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['grab_msg_er'], "javascript:history.go(-1)");
        return 1;
    }
    if ($_POST['act'] == 'sav') {
        $sql_result = $db->super_query('SELECT * FROM ' . PREFIX . "_rss_category WHERE id = '{$_POST['rss_priv']}'");
        if ($sql_result['title'] == '') $mgs = '<b><font color=green>' . $lang_grabber['y_c_trans'] . '</font></b> <br /><br />';
        else $mgs = ' <b><font color=green>' . $lang_grabber['yes_trans'] . '</font> <font color=red>' . $sql_result['title'] . '</font></b><br /><br />';
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $del = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
            $categoryes = explode('=', $del['category']);
            $ht = $categoryes[0] . '=' . $_POST['rss_priv'];
            $db->query('UPDATE ' . PREFIX . "_rss SET category='$ht' WHERE id = '$id'");
            if (trim($del['title']) != '') {
                $title = stripslashes(strip_tags_smart($del['title']));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            $mgs.= ' <font color="#C0C0C0">"' . $title . ' | ' . $del['url'] . '"</font><br />';
        }
        msg($lang_grabber['info'], $lang_grabber['transfer_canal'], $mgs, $PHP_SELF . '?mod=rss');
        return 1;
    } elseif ($_POST['act'] != 'sav') {
        echoheader('', '');
        opentable($lang_grabber['transfer_canal']);
        $channel_inf = array();
        $channel_color = array();
        $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_rss_category ORDER BY kanal asc');
        $run[0] = '';
        while ($channel_info = $db->get_row($sql_result)) {
            if ($channel_info['osn'] == '0') $channel_inf[$channel_info['id']][$channel_info['id']] = $channel_info['title'];
            else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- ' . $channel_info['title'];
        }
        foreach ($channel_inf as $value) {
            if (count($value) != '0') {
                foreach ($value as $kkey => $key) {
                    $run[$kkey] = $key;
                }
            }
        }
        foreach ($ids as $id) {
            $del = $db->super_query('SELECT * FROM ' . PREFIX . "_rss WHERE id = '$id'");
            if (trim($del['title']) != '') {
                $title = stripslashes(strip_tags_smart($del['title']));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            $mgs.= ' <font color="green">"' . $title . ' | ' . $del['url'] . '"</font> <font color="red"><br />';
        }
        $ids = implode(',', $ids);
        echo '
<form method="post" name="addgrup_channel" id="addgrup_channel">
<input type="hidden" name="action" value="addgrup_channel">
<input type="hidden" name="act" value="sav">
<table width="100%">
    <tr>
 <td align="center">
<b><font color="red">' . $lang_grabber['you_tr_can'] . ':</font></b><br /><br />' . $mgs . '
<br /></td>
</tr>
<tr>
<td  class="hr_line" colspan=6></td>
</tr>
    <tr>
 <td align="center"><br />
 ' . $lang_grabber['selected_grup'] . ': <select name="rss_priv" class="load_img">
    ' . sel($run, '') . '
   </select><br />
<br /></td>
</tr>
<tr>
<td  class="hr_line" colspan=6></td>
</tr>
    <tr>
 <td align="center">
 <br />
<input type="hidden" name="channel" value="' . $ids . '">
<input type="submit" class="btn btn-success"   value=" ' . $lang_grabber['mov_can'] . ' " ">
<input type="button" class="btn btn-warning" value=" ' . $lang_grabber['out'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" />
</td>
    </tr>
</table>
</form>';
        closetable();
        echofooter();
        return 1;
    }
}
if ($action == 'ping') {
    if ($_GET['deletey']) {
        openz(ENGINE_DIR . '/cache/system/pinglogs.txt', ' ');
        msg($lang_grabber['ping'], '<b>' . $lang_grabber['ping_title'] . '</b>', '<font color=green>' . $lang_grabber['cl_log_yes'] . '</font>', $PHP_SELF . '?mod=rss&action=ping');
    } elseif ($_GET['delete']) {
        echoheader('', '');
        opentable('<b>' . $lang_grabber['ping_title'] . '</b>');
        echo '<center><b><font color=red>' . $lang_grabber['cl_log_ask'] . ' ?</font></b><br /><br /><input type="button" class="btn btn-sin"   value=" ' . $lang['opt_sys_yes'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=ping&deletey=yes\'" />
<input type="button" class="btn btn-danger"   value=" ' . $lang['opt_sys_no'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=ping\'" />
   </center>';
        closetable();
        echofooter();
        return 1;
        msg($lang_grabber['ping'], '<b>' . $lang_grabber['ping_title'] . '</b>', '<font color=red>' . $lang_grabber['cl_log_ask'] . ' ?</font>', $PHP_SELF . '?mod=rss&action=ping&deletey=yes');
    } else {
        echoheader('', '');
        opentable('<b>' . $lang_grabber['ping_title'] . '</b>');
        $arr = array_map('trim', file(ENGINE_DIR . '/cache/system/pinglogs.txt'));
        if ($arr[0] != '') $all = count($arr);
        if ($config_rss['ping_lognum'] != '') $pnumber = $config_rss['ping_lognum'];
        else $pnumber = 5;
        echo "<div class=\"quick\">";
        if (isset($all)) {
            echo ' <b>' . $lang_grabber['ping_writing_all'] . ' ' . $all . ' ' . $lang_grabber['sht'] . '</b><br/>';
        } else {
            echo $lang_grabber['cl_log_msg'];
        }
        echo "</div>
<style type=\"text/css\" media=\"all\">
.listp {
    color: #999898;
    font-size: 11px;
    font-family: tahoma;
    padding: 5px;
}

.listp a:active,
.listp a:visited,
.listp a:link {
    color: green;
    text-decoration:none;
    }

.listp a:hover {
    color: blue;
    text-decoration: underline;
    }
</style>
<div class=\"listp\">";
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $num_pages = ceil($all / $pnumber);
        $start = $page * $pnumber - $pnumber;
        if ($page > $num_pages || $page < 1) {
            $page = 1;
            $start = 0;
        }
        if ($all) {
            for ($i = $all - $start - 1;$i >= $all - $start - $pnumber;$i--) {
                if (!isset($arr[$i])) break;
                echo $all - $i . '. ' . $arr[$i];
                echo '<br/>';
            }
            echo '</div>';
            if ($num_pages == 1) {
            } else {
                $npp_nav = "<div class=\"news_navigation\" style=\"margin-bottom:5px; margin-top:5px;\">";
                for ($i = 1;$i <= $num_pages;$i++) {
                    if ($i == 1 or $i == $num_pages or abs($i - $page) < 10) {
                        if ($i == $page) $npp_nav.= " <SPAN>$i</SPAN> ";
                        else $npp_nav.= ' <a href="' . $PHP_SELF . '?mod=rss&action=ping&page=' . $i . '">' . $i . '</a> ';
                    } else {
                        if ($page + 10 == $i) {
                            $npp_nav.= ' <a href="' . $PHP_SELF . '?mod=rss&action=ping&page=' . $i . '">' . $i . '</a> ... ';
                        } elseif ($page - 10 == $i) {
                            $npp_nav.= ' ... <a href="' . $PHP_SELF . '?mod=rss&action=ping&page=' . $i . '">' . $i . '</a> ';
                        } else {
                            $npp_nav.= '';
                        }
                    }
                }
                $npp_nav.= '
    <form action="" onsubmit="topage() return false;">
    <script type="text/javascript">
        function topage() {
            var loca = window.location+"";
            var locas = loca.split("page");
                loca = locas[0];
                locas = loca.split("' . $PHP_SELF . '");
            window.location.href = locas[0] + \'' . $PHP_SELF . '?mod=rss&action=ping&page=\' + document.getElementById(\'num_page\').value;
        }
    </script>
        <span><input id="num_page" style="background:none; height:15px; width:50px; border:0;"/></span> <a href="#" onclick="topage(); return false;">' . $lang_grabber['go_do'] . '</a>
    </form>
    ';
                $npp_nav.= '</div>';
                echo $npp_nav;
            }
        }
        echo '<br><input type="button" class="btn btn-sin" value=" ' . $lang_grabber['ping_del_log'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=ping&delete=yes\'" />
<input type="button" class="btn btn-warning" value=" ' . $lang_grabber['out'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" />
   ';
        closetable();
        echofooter();
    }
    return 1;
}
if ($action == 'updates') {
    include $rss_plugins . 'update.php';
    return 1;
}
if ($_GET['s'] == 'go') {
    $_POST['search'] = $_GET['s'];
    $_POST['key'] = $_GET['k'];
    $_POST['pol'] = $_GET['p'];
}
if ($action == 'search_k') {
    if (count($_POST['channel']) > 1) msg($lang_grabber['info'], $lang_grabber['info'], $lang_grabber['sel_one_canal'] . '! ', "javascript:history.go(-1)");
    $_POST['search'] = 'go';
    $can_s = $db->super_query('SELECT url FROM ' . PREFIX . "_rss WHERE id='" . $_POST['channel'][0] . "'");
    $_POST['key'] = reset_url($can_s['url']);
    $_POST['pol'] = 'url';
}
$search = "<form method=\"post\" >
<input type=\"hidden\" name=\"search\" value=\"go\">
{$lang_grabber['search']}
    <input size=\"25\" class=\"edit\" name=\"key\" value=\"" . $_POST['key'] . "\" /> {$lang_grabber['po_news']}
     <select name=\"pol\">";
$search.= $_POST['pol'] == 'url' ? "<option value=\"url\" selected \">{$lang_grabber['links']}</option>" : "<option value=\"url\">{$lang_grabber['links']}</option>";
$search.= $_POST['pol'] == 'title' ? "<option value=\"title\" selected \">{$lang_grabber['names']}</option>" : "<option value=\"title\">{$lang_grabber['names']}</option>";
$search.= $_POST['pol'] == 'xdescr' ? "<option value=\"xdescr\" selected \">{$lang_grabber['descrs']}</option>" : "<option value=\"xdescr\">{$lang_grabber['descrs']}</option>";
$search.= "</select>
    <input type=\"submit\" class=\"btn btn-primary\" value=\"{$lang_grabber['go_search']}\" />
</form>";
$vose = '
<style type="text/css">
.title_spoiler {
    color: #636363;

    border: 1px solid #bebebe;
    font-weight: bold;
    font-size: 9pt;
    padding: 2px;
    margin-top: 5px;
}
.text_spoiler {

    border: 1px solid #bebebe;
    border-top: 0;
    margin-bottom: 5px;
}

.title_spoil {
     color: #636363;

     border: 1px solid #C8E4FA;
     font-weight: bold;
     font-size: 9pt;
     padding: 2px;
     margin-top: 5px;
     margin-left: 0px;
     margin-right: 0px;
}
.text_spoil {

     border: 1px solid #C8E4FA;
     border-top: 0;
     margin-bottom: 5px;
     margin-left: 0px;
     margin-right: 0px;
}
.darkw{ background-color: #E8F9E6}
</style>
';
$order_by = '';
$sort_rss = get_vars('rss.sort');
if (!$_POST['dlenewssortby']) {
    if ($sort_rss[0] == '' or $sort_rss[1] == '') $order_by = 'xpos DESC ,title DESC';
    else $order_by = $sort_rss[0] . ' ' . $sort_rss[1];
} else {
    $order_by = $_POST['dlenewssortby'] . ' ' . $_POST['dledirection'];
    $sort_rss = array(0 => $_POST['dlenewssortby'], 1 => $_POST['dledirection']);
    set_vars('rss.sort', $sort_rss);
}
$channel_inf = array();
$channel_info = array();
$grup_result = $db->query('SELECT * FROM ' . PREFIX . '_rss_category ORDER BY kanal asc');
$channel_inf[0] = '';
while ($channel_info = $db->get_row($grup_result)) {
    $channel_inf[$channel_info['id']] = $channel_info['osn'];
    $channel_color[$channel_info['id']] = $channel_info['color'];
}
if ($_POST['search'] == 'go' and $_POST['key'] != '') {
    if ($_POST['pol'] == '') $_POST['pol'] = 'url';
    $sql_result = $db->query('SELECT * FROM ' . PREFIX . '_rss WHERE ' . $_POST['pol'] . " like '%" . $_POST['key'] . "%' ORDER BY $order_by");
    $empty = $db->num_rows($sql_result) == 0;
    $hk = $db->num_rows($sql_result);
} else {
    $sql_result = $db->query('SELECT * FROM ' . PREFIX . "_rss ORDER BY $order_by");
    $empty = $db->num_rows($sql_result) == 0;
}
if ($empty) {
    $vose.= '
<form method="post" name="rss_form" id="rss_form">
<span id="channels"></span>
<table cellpadding="4" cellspacing="0" width="100%">
    <tr>
    <td height="40" valign="middle" align="center" class="navigation">- ' . $lang_grabber['no_canals'] . ' -</td>
    </tr>
    </table>';
} else {
    $vose.= '<input style="display:none" type="checkbox"  name="tables" id="tables" value="" />';
    $vose.= "
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
      }document.news_set_sort.check_all.checked=true;
    }
}
function selectRow(evnt,elmnt){
  var ch=elmnt.getElementsByTagName(\"TD\")[5].firstChild;
  tg = document.all?evnt.srcElement:evnt.target;
  if(tg.tagName!='INPUT')ch.checked=!ch.checked;
  elmnt.style.backgroundColor=ch.checked?'#E8F9E6':'';

document.channels(document.rss_form.channel.length);
}

function ShowOrHidegr( id, name ) {
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
                item.style.display = \"table\";
                image.src = './engine/skins/grabber/images/minus.gif';
                var curCookie = id + \"=\" + '1';
            } else {
                item.style.display = \"none\";
                image.src = './engine/skins/grabber/images/plus.gif';
                var curCookie = id + \"=\" + '';
            }
         } else{ item.visibility = \"show\"; }
      }
document.cookie = curCookie;
};

function ShowOrHidegrp( id, name ) {
      var item = document.getElementById(id);
      if ( document.getElementById('images-'+ id) ) {
        var images = document.getElementById('images-'+ id);
      }
      if (!item) {
        retun;
      }  else {
        if (item.style) {
            if (item.style.display == \"none\") {
                item.style.display = \"table\";
                images.src = './engine/skins/grabber/images/p-minus.gif';
                var curCookie = id + \"=\" + '1';
            } else {
                item.style.display = \"none\";
                images.src = './engine/skins/grabber/images/p-plus.gif';
                var curCookie = id + \"=\" + '';
            }
         } else{ item.visibility = \"show\"; }
      }
document.cookie = curCookie;
};

function ShowOrHideAll() {
var show = document.getElementById('tables');
    var item = document.getElementsByTagName('table');
for(n=0;n<item.length;n++){
      if (!item[n]) {
        retun;
      }  else {
        if (item[n].style.display ) {

      if ( document.getElementById('image-'+ item[n].id) ) {
        var image = document.getElementById('image-'+ item[n].id);
      }     else {
        var image = null;
      }
      if ( document.getElementById('images-'+ item[n].id) ) {
        var images = document.getElementById('images-'+ item[n].id);
      } else {
        var images = null;
      }
if (!show.checked) {
            if (item[n].style.display == \"none\") {
                item[n].style.display = \"table\";
                if(image)   image.src = '/engine/skins/grabber/images/minus.gif';
                if(images) images.src = '/engine/skins/grabber/images/p-minus.gif';
var curCookie = item[n].id + \"=\" + '1';
            }
            }else {
                item[n].style.display = \"none\";
                if(image)image.src = '/engine/skins/grabber/images/plus.gif';
                if(images) images.src = '/engine/skins/grabber/images/p-plus.gif';
var curCookie = item[n].id + \"=\" + '';
            }
         }
         else{ item[n].visibility = \"show\"; }

      }
document.cookie = curCookie;
}

if (show.checked)
    {
    show.checked = false;
    }else{
        show.checked = true;}

};


	function Addurls(numm) {
for(n=0;n<numm;n++){
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;
     var iteration = lastRow+1;

     var tr = tbl.insertRow(lastRow);

     var td = tr.insertCell(0);
     td.setAttribute('style', 'padding: 0px 0px 2px 0px;');
     td.setAttribute('align', 'right');

     var el = document.createElement('input');
     el.setAttribute('type', 'text');
     el.setAttribute('name', 'str_url[]');
     el.setAttribute('size', '35');
     el.setAttribute('value', '');

         var newText = document.createTextNode(iteration);

     var elm = document.createElement('button');
     elm.setAttribute('disabled', 'disabled');
     elm.setAttribute('class', 'edit');
     elm.setAttribute('style', 'background:#9E9E9E;color:#fff;');

     var elem = document.createElement('input');
     elem.setAttribute('type', 'text');
     elem.setAttribute('disabled', 'disabled');
     elem.setAttribute('border', '0');
     elem.setAttribute('size', '1');
     elem.setAttribute('value', iteration);
         td.appendChild(elm);
elm.appendChild(newText);
         //td.appendChild(elem);
         td.appendChild(el);
}
	}


	function Removeurls() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;
     if (lastRow > 1){
              tbl.deleteRow(lastRow - 1);
     }
	}
</script>";
    if ($start_pos) {
        $vose.= news_sort_rss($_POST['dlenewssortby'], $_POST['dledirection']);
        $vose.= '
<div>
    <form method="post" name="rss_form" id="rss_form">
<span id="channels"></span>
    <table cellpadding="6" align="center" cellspacing="0" width="100%" border="0">
    <tr>
    <td colspan="6">';
        $vose.= '<div class="unterline"></div>';
        $vose.= '</td></tr></table>';
        $rss_kanal = array();
        $cat_rss = array();
        while ($row = $db->get_row($sql_result)) {
            $rss_kan = '';
            if (trim($row['title']) != '') {
                $title = stripslashes(strip_tags_smart($row['title']));
                if (50 < e_str($title)) {
                    $title = e_sub($title, 0, 50) . '...';
                }
            } else {
                $title = $lang_grabber['no_title'];
            }
            if ($row['xdescr']) {
                $xdescr = $row['xdescr'];
                if (e_str($xdescr) > 50) {
                    $xdescr = e_sub($xdescr, 0, 50) . '...';
                }
            } else {
                $xdescr = '&nbsp;';
            }
            if ($row['allow_auto'] == 0) {
                $auto = '';
            } else {
                $auto = '<font color=green><b>' . $lang['opt_sys_yes'] . '</b></font>';
            }
            if ($row['rss'] == 0) {
                $rss = '<font color=blue>HTML</font>';
            } else {
                $rss = '<font color=red>RSS</font>';
            }
            $row['url'] = stripslashes($row['url']);
            $row['descr'] = stripslashes($row['descr']);
            $categoryes = explode('=', $row['category']);
            $del = array();
            if (trim($channel_color[intval($categoryes[1]) ]) == '') $channel_color[intval($categoryes[1]) ] = '#f2f2f2';
            if (intval($categoryes[1]) != '0') {
                if (intval($channel_inf[$categoryes[1]]) != '0') $style_grups = 'text_spoil" style ="border-color: ' . $channel_color[intval($categoryes[1]) ] . ';"';
                else $style_grups = 'text_spoiler';
            } else {
                $style_grups = 'light';
            }
            $rss_kan = '<tr class="' . $style_grups . '" onMouseOut=this.className="' . $style_grups . '"
       onMouseOver=this.className="highlight"
       onclick=selectRow(event,this)>
        <td width="5%" style="padding:1px" align="center"><input type="text" name="xpos[' . $row['id'] . ']" value="' . $row['xpos'] . '" class="edit" align="center" size="3" /></td>
        <td width="5%" style="padding:1px" align="center">' . $rss . '</td>
        <td width="6%" style="padding:1px" align="center">' . $auto . '</td>
        <td style="padding:4px">
        <a href="' . $row['url'] . '" target=\"_blank\">[i]</a>&nbsp;<a href="' . $PHP_SELF . '?mod=rss&action=channel&subaction=edit&id=' . $row['id'] . '" class="hintanchor" onMouseover="showhint(\'<b>' . $row['url'] . '</b><br/>' . $row['descr'] . '\', this, event, \'300px\');">' . $title . '</a></td>
        <td style="padding:4px" align="center">' . $xdescr . '</td>
        <td width="3%"><input style="background-color: #ffffff; color: #ff0000;" title="' . $lang_grabber['val_post'] . '" type="checkbox" name="channel[]" id="channel" value="' . $row['id'] . '" />
        </td>
     </tr>
     <tr>';
            if (intval($categoryes[1]) != '0' and array_key_exists($categoryes[1], $channel_inf)) {
                if (intval($channel_inf[$categoryes[1]]) != '0') $cat_rss[$channel_inf[$categoryes[1]]][$categoryes[1]][$row['id']] = $rss_kan;
                else $cat_rss[$categoryes[1]][0][$row['id']] = $rss_kan;
            } else {
                $rss_kanal[$row['id']] = $rss_kan;
            }
        }
        if (count($cat_rss) != '0') {
            $grups_rss = array();
            foreach ($cat_rss as $papka => $kanals) {
                $grups_r = '';
                $del = $db->super_query('SELECT * FROM ' . PREFIX . "_rss_category WHERE id= '" . $papka . "'");
                $id_spoiler = spoiler($del['title']);
                $vose.= "    <script type=\"text/javascript\">
    $(document).ready( function() {
       $(\"#ch_$id_spoiler\").click( function() {
            if($('#ch_$id_spoiler').attr('checked')){
                $(\"#\" + $(this).attr('name') + \" input:checkbox:enabled\").attr('checked', true);
                $(\"#\" + $(this).attr('name') + \" tr\").css('background-color', '#E8F9E6');
            } else {
                $(\"#\" + $(this).attr('name') + \" input:checkbox\").attr('checked', false);
                $(\"#\" + $(this).attr('name') + \" tr\").css('background-color', '');
            }
       });
    });
</script>";
                $kol = '';
                $kol = count($kanals, 1) - count($kanals);
                if (count($kanals) == '1' and count($kanals[0]) > 0) {
                    $kol = count($kanals, 1) - count($kanals);
                } else {
                    $kols = count($kanals[0]) == 0 ? count($kanals) : count($kanals) - 1;
                    $kol = count($kanals, 1) - count($kanals) . '|' . $kols;
                }
                if (trim($del['color']) == '') $del['color'] = '#f2f2f2;';
                if ($_COOKIE[$id_spoiler] != 1) {
                    $strp = 'style="display:none;background-color: ' . $del['color'] . ';"';
                    $strp_i = 'src="./engine/skins/grabber/images/plus.gif"';
                } else {
                    $strp = 'style="display:table;background-color: ' . $del['color'] . ';"';
                    $strp_i = 'src="./engine/skins/grabber/images/minus.gif"';
                }
                $grups_r.= '<table width="100%" border="0" ><div class="title_spoiler" style ="background-color: ' . $del['color'] . ';"><img id="image-' . $id_spoiler . '" style="vertical-align: middle;border: none;" alt="" ' . $strp_i . ' />&nbsp;<a href="javascript:ShowOrHidegr(\'' . $id_spoiler . '\', \'rss_sp_' . $papka . '\')">' . $del['title'] . ' (' . $kol . ')</a>

<input type="checkbox"  name="' . $id_spoiler . '" id="ch_' . $id_spoiler . '" value="" alt="' . $lang_grabber['sel_all_can'] . '" title="' . $lang_grabber['sel_all_can'] . '"/>
</div>

</table>
<table id="' . $id_spoiler . '" name="rss_sp_' . $papka . '" cellpadding="6" align="center" cellspacing="0" width="100%" border="0" class="text_spoiler" ' . $strp . ' >';
                ksort($kanals);
                foreach ($kanals as $papk => $kanal) {
                    if ($papk != 0) {
                        $osn = $db->super_query('SELECT * FROM ' . PREFIX . "_rss_category WHERE id= '" . $papk . "'");
                        $id_spoil = spoiler($osn['title']);
                        $vose.= "    <script type=\"text/javascript\">
    $(document).ready( function() {
       $(\"#ch_$id_spoil\").click( function() {
            if($('#ch_$id_spoil').attr('checked')){
                $(\"#\" + $(this).attr('name') + \" input:checkbox:enabled\").attr('checked', true);
                $(\"#\" + $(this).attr('name') + \" tr\").css('background-color', '#E8F9E6');
            } else {
                $(\"#\" + $(this).attr('name') + \" input:checkbox\").attr('checked', false);
                $(\"#\" + $(this).attr('name') + \" tr\").css('background-color', '');
            }
       });
    });
</script>";
                        if (trim($osn['color']) == '') $osn['color'] = '#f2f2f2;';
                        if ($_COOKIE[$id_spoil] != 1) {
                            $strj = 'style="display:none;background-color: ' . $osn['color'] . ';"';
                            $strj_i = 'src="./engine/skins/grabber/images/p-plus.gif"';
                        } else {
                            $strj = 'style="display:table;background-color: ' . $osn['color'] . ';"';
                            $strj_i = 'src="./engine/skins/grabber/images/p-minus.gif"';
                        }
                        $grups_r.= '<tr><td colspan=6 ><table width="100%" border="0"><div class="title_spoil"  style ="background-color: ' . $osn['color'] . ';"><img id="images-' . $id_spoil . '" style="vertical-align: middle;border: none;" alt="" ' . $strj_i . ' />&nbsp;<a href="javascript:ShowOrHidegrp(\'' . $id_spoil . '\', \'rss_sp_' . $papk . '\')">' . $osn['title'] . ' (' . count($kanal) . ')</a>

<input type="checkbox" name="' . $id_spoil . '" id="ch_' . $id_spoil . '" value="" alt="' . $lang_grabber['sel_all_can'] . '" title="' . $lang_grabber['sel_all_can'] . '"/>
</div>
</table>
<table id="' . $id_spoil . '" name="rss_sp_' . $papk . '" cellpadding="6" align="center" cellspacing="0" width="100%" border="0" class="text_spoil" ' . $strj . ' >' . implode('<td style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;" height=1 colspan=6></td></tr>', $kanal) . '</table></td></tr> ';
                    }
                }
                if (count($kanals[0]) > 0) $grups_r.= implode('<td style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;" height=1 colspan=8></td></tr>', $kanals[0]) . '</table> ';
                $grups_rss[$del['kanal']] = $grups_r;
            }
            ksort($grups_rss);
            $vose.= implode($grups_rss);
        }
    }
}
if (count($rss_kanal) != 0) $vose.= '<table cellpadding="6" align="center" cellspacing="0" width="100%" border="0">' . implode('<td style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;" height=1 colspan=6></td></tr>', $rss_kanal) . '<td style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;" height=1 colspan=6></td></tr></table>';
$vose.= '<table cellpadding="4" width="100%"><tr><td colspan="6"><br/>';
$vose.= '<div class="unterline"></div>';
$vose.= '  </td></tr> <tr>
    <td colspan="5">
    <table width="100%" border="0">
     <tr>
<td align="left" width="270">';
if ($_POST['search'] == 'go' and $_POST['key'] != '') {
    $vose.= '
<input type="button" class="btn btn-warning" value="' . $lang_grabber['go_index'] . '" onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss\'" />
';
}
$vose.= '
        <input type="button" class="btn btn-success" value="' . $lang_grabber['add_canal'] . '" onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=channel&subaction=add\'" />
     </td>
     <td style="padding:2px" align="right" colspan="2">' . $lang['xfield_xact'] . ':

     <select id="ui_element" name="action">
        <option value="scan" selected style="background: #EFEFEF;">' . $lang['rss_news'] . '</option>
        <option value="scan1">' . $lang_grabber['rss_news'] . ' ' . $lang_grabber['post_sm'] . '</option>
        <option value="scan3">' . $lang_grabber['rss_news'] . ' 3 ' . $lang_grabber['post_sm'] . '</option>
        <option value="scan5">' . $lang_grabber['rss_news'] . ' 5 ' . $lang_grabber['post_big'] . '</option>
        <option value="scan10">' . $lang_grabber['rss_news'] . ' 10 ' . $lang_grabber['post_big'] . '</option>
        <option value="scan15">' . $lang_grabber['rss_news'] . ' 15 ' . $lang_grabber['post_big'] . '</option>
        <option value="scan20">' . $lang_grabber['rss_news'] . ' 20 ' . $lang_grabber['post_big'] . '</option>';
if ($config_rss['news_kol'] != '') {
    $vose.= '<option value="scan' . $config_rss['news_kol'] . '">' . $lang_grabber['rss_news'] . ' ' . $config_rss['news_kol'] . ' ' . $lang_grabber['post_big'] . '</option>';
}
$vose.= '<option value="auto_channel" style="background: #EFEFEF; color:green">' . $lang_grabber['channel_auto_y'] . '</option>
        <option value="noauto_channel">' . $lang_grabber['channel_auto_n'] . '</option>
        <option value="copy_channel" style="background: #EFEFEF; color:orange; font: bold 110% ;">' . $lang_grabber['channel_copy'] . '</option>
        <option value="sort" style="background: #EFEFEF; color:blue">' . $lang_grabber['channel_sort'] . '</option>
        <option value="del_channel" style="background: #EFEFEF; color:red">' . $lang_grabber['channel_del'] . '</option>
<option value="save_channel" style="background: #EFEFEF;">' . $lang_grabber['expo'] . '</option>
<option value="save_up_channel" style="background: #EFEFEF;">' . $lang_grabber['impo'] . '</option>
        <option value="addgrup_channel" style="background: #EFEFEF; color:red">' . $lang_grabber['move_grup'] . '</option>
<option value="editgrup_channel">' . $lang_grabber['batch_edit'] . '</option>
     </select></td>
	 <td align="left" style="padding:2px" class="navigation"> ' . $lang_grabber['s_news'] . ' <input type="text" class="edit" name="str_news" size="3" value=""/> ' . $lang_grabber['po_news'] . ' <input type="text" class="edit" name="str_newf" size="3" value=""/> ' . $lang_grabber['post_one'] . '
     </td>
     <td align="right" rowspan="2" width="50">
        <input type="submit" class="btn btn-warning btn-mini" style="height: 40px;" value="' . $lang_grabber['b_start'] . '"/>
     </td>
	 </tr>
	 <tr>     <td align="left" rowspan="2" colspan="3"  class="navigation">' . $lang_grabber['help_run'] . '</td>
<td style="padding:2px" align="left" class="navigation" width="150"> ' . $lang_grabber['s_news'] . ' <input type="text" class="edit" name="str_kans" size="3" value=""/> ' . $lang_grabber['po_news'] . ' <input type="text" class="edit" name="str_kanf" size="3" value=""/> ' . $lang_grabber['page_do'] . '</td>
</tr>
    </table>
	 <tr>
<td style="padding:2px 0px 2px 2px" colspan="3" align="right" class="navigation" width="150">

<table id="tblSample" class="upload">
<tr>
<td style="padding:2px 0px 2px 2px;">' . $lang_grabber['grab_links'] . '<a href="#" class="hintanchor" onMouseover="showhint(\'' . $lang_grabber['help_grab_links'] . '\', this, event, \'250px\')">[?]</a> <button  disabled="disabled"  class="edit"  style="background:#9E9E9E;color:#fff;" >1</button><input type="text" class="edit" name="str_url[]" size="35" value=""/>
</td>
</tr>
</table>
<div>
<input type=button class="btn btn-sin" value=\' + 1 \'  title="' . $lang_grabber['grab_links_1'] . '" onClick="Addurls(1);return false;">
<input type=button class="btn btn-sin" value=\' + 5 \'  title="' . $lang_grabber['grab_links_5'] . '" onClick="Addurls(5);return false;">
<input type=button class="btn btn-sin" value=\' + 10 \'  title="' . $lang_grabber['shr_max'] . '" onClick="Addurls(10);return false;">
</div>
</td>
</tr>
    </td></tr>
    </table></form>';
if (count($cat_rss) != '0') $spoi = '  <a href="javascript:ShowOrHideAll()"><font color=orange>&uarr; ' . $lang_grabber['shr_max'] . ' &darr;</font></a>';
echoheader('', '');
check_disable_functions();
opentable($lang_grabber['rss_list'] . $spoi, $tr . $search);
if ($_POST['search'] == 'go' and $_POST['key'] != '') {
    echo ' <table width="100%" border=0>
    <tr><td><font color="#999898">' . $lang_grabber['you_sear'] . ': <font color="green">' . $_POST['key'] . '</font><br />
' . $lang_grabber['res_sear'] . ': <font color="blue">' . $hk . '</font></font></td></tr><tr><td style="border-bottom-width: 1px;border-bottom-style: groove; border-bottom-color: grey;" height=1 colspan=6></td></tr></table>';
}
echo $vose;
closetable();
opentable();
tableheader($lang_grabber['tabs_extra']);
echo "
<script>

var ajax = new dle_ajax();

function check_updates ( ){
	document.getElementById( 'loading-layer-text' ).innerHTML = '{$lang['dle_updatebox']}';
document.getElementById( 'main_box' ).innerHTML = '{$lang_grabber['con_serv']}';
	var varsString = \"moduleversion={$module_info['version']}\";
	ajax.setVar(\"modulebuild\", '{$module_info['build']}');
	ajax.requestFile = \"engine/ajax/grabber.php\";
	ajax.element = 'main_box';
	ajax.method = 'POST';

	ajax.sendAJAX(varsString);

	return false;
}

function grabber_updates_down ( ){
	document.getElementById( 'loading-layer-text' ).innerHTML = '{$lang['dle_updatebox']}';
document.getElementById( 'main_box' ).innerHTML = '{$lang_grabber['con_serv']}';
	var varsString = \"key={$config['keygrab']}\";
	ajax.setVar(\"ver\", '{$module_info['version']}');
	ajax.setVar(\"bul\", '{$module_info['build']}');
	ajax.requestFile = \"engine/ajax/update_grabber.php\";
	ajax.element = 'main_box';
	ajax.method = 'POST';

	ajax.sendAJAX(varsString);

	return false;
}
function grabber_updates (dwn){
	document.getElementById( 'loading-layer-text' ).innerHTML = '{$lang['dle_updatebox']}';
document.getElementById( 'main_box' ).innerHTML = '{$lang_grabber['con_serv']}';
	var varsString = \"url=dwn\";
	ajax.setVar(\"dwn\", dwn);
	ajax.requestFile = \"engine/ajax/update_grabber.php\";
	ajax.element = 'main_box';
	ajax.method = 'POST';

	ajax.sendAJAX(varsString);

	return false;
}
function closead()
{
    var obj = document.getElementById( \"ad\" );
    obj.style.visibility = \"hidden\";
}
</script>";
echo '


<table width="100%" cellpadding="4">
    <tr><td align="left">
        <input type="button" class="btn btn-primary" value=" ' . $lang_grabber['glob_options'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=config\'" />

<input type="button" class="btn btn-info" value=" ' . $lang_grabber['grups'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=grups\'" />';
if (@file_exists(ENGINE_DIR . '/inc/plugins/sinonims.php')) {
    echo '
<input type="button" class="btn btn-inverse" value=" ' . $lang_grabber['sinonims_bottom'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=sinonim\'" />';
}
echo '
</td><td id="main_box" align=right>
<input onclick="check_updates(); return false;" class="btn btn-danger" type="button" value=" ' . $lang_grabber['update_bottom'] . ' ">
</td>
<td align=right>
<input type="button" class="btn btn-sin" value=" ' . $lang_grabber['ping_button'] . ' " onClick="document.location.href = \'' . $PHP_SELF . '?mod=rss&action=ping\'" />
     </td></tr>
    <tr>
        <td colspan=6 align="right" style="padding-top:5px;" class="navigation"><a href="http://rss-grabber.ru" target="_blank">' . $module_info['name'] . ' ' . $module_info['version'] . ' build' . $module_info['build'] . '</a> | Copyright 2013 &copy created by Andersoni. All rights reserved.</td>
    </tr>
 </table>

 ';
closetable();
echofooter();
$db->close;;
