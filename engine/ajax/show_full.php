<?php
/*
=====================================================
 Модуль: Show Full
 Версия: v2.0
=====================================================
 Автор: MSW
 Сайт поддержки: http://0-web.ru/
=====================================================
 Файл: show_full.php
=====================================================
*/

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

@session_start();

define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');

include ENGINE_DIR . '/data/config.php';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

@header("Content-type: text/xml; charset={$config['charset']}");
$news_id = intval($_POST['id']);
if(!$news_id) die("Ошибка! Перезагрузите страницу и попробуйте снова!");
if(!$template = file_get_contents(ROOT_DIR."/templates/{$config['skin']}/ajax_news_full.tpl"))die("Ошибка! Не получилось загрузить шаблон фаил");
if ($config['version_id'] > 9.6) $row = $db->super_query("SELECT * FROM dle_post LEFT JOIN dle_post_extras ON (dle_post.id=dle_post_extras.news_id) WHERE id='{$news_id}'" );
else $row = $db->super_query("SELECT allow_rate,full_story,short_story,xfields,id,title,rating,vote_num,access FROM ".PREFIX."_post WHERE id='{$news_id}'" );
$user_group = get_vars("usergroup");

		
if(empty($row['full_story']))
$template = preg_replace( "'\\[not-full_story\\](.*?)\\[/not-full_story\\]'is", "\\1", $template );
else $template = preg_replace( "'\\[not-full_story\\]\\[/not-full_story\\]'is", "\\1", $template );
if(strlen( $row['full_story'] ) < 13) {
			$row['full_story'] = $row['short_story'];
		}
if( $row['allow_rate'] ) $rating= ShowRating( $row['id'], $row['rating'], $row['vote_num'], $user_group[$member_id['user_group']]['allow_rating'] );
		else $rating='';

$full_story = stripslashes($row['full_story']);
                     
					 if( strpos( $template, "{youtube}" ) !== false ){
					preg_match_all('#\<!--dle_youtube_begin:.+?--\>(.*?)\<!--dle_youtube_end--\>#is',$full_story,$youtube);
					for($ai=0; count($youtube[1])>$ai; $ai++){
					$temp_youtube.=$youtube[1][$ai];
					}
					$youtube=$temp_youtube;
					}
					
					if( strpos( $template, "{video}" ) !== false ){
					 preg_match_all('#<!--dle_video_begin:.+?-->(.*?)<!--dle_video_end-->#is',$full_story,$video);
					for($ai=0; count($video[1])>$ai; $ai++){
					$temp_video.=$video[1][$ai];
					}
					$video=$temp_video;
					}
					
					if( strpos( $template, "{audio}" ) !== false ){
					 preg_match_all('#<!--dle_audio_begin:.+?-->(.*?)<!--dle_audio_end-->#is',$full_story,$audio);
					for($ai=0; count($audio[1])>$ai; $ai++){
					$temp_audio.=$audio[1][$ai];
					}
					$audio=$temp_audio;
					}

#*** hide ***#
if($user_group[$member_id['user_group']]['allow_hide']) $full_story = str_ireplace("[hide]", "", str_ireplace("[/hide]", "", $full_story));
else $full_story = preg_replace("#\[hide\](.+?)\[/hide\]#ims", "<div class=\"quote\">Скрытый текст</div>", $full_story);

#*** THEME ***#
if($_COOKIE['dle_skin']) {
	if(@is_dir(ROOT_DIR.'/templates/'.$_COOKIE['dle_skin'])) {
		$config['skin'] = $_COOKIE['dle_skin'];
	}
}
$full_story = str_replace('{THEME}', "/templates/{$config['skin']}", $full_story);

$template = str_replace('{title}', $row['title'], $template);
$template = str_replace('{full-story}', $full_story, $template);
$template = str_replace('{youtube}', $youtube, $template);
$template = str_replace('{video}', $video, $template);
$template = str_replace('{audio}', $audio, $template);
$xfields = xfieldsload();
		if( strpos( $template, "[xfvalue_" ) !== false ) {
			
			$xfieldsdata = xfieldsdataload( $row['xfields'] );
			
			foreach ( $xfields as $value ) {
				$preg_safe_name = preg_quote( $value[0], "'" );
				
				if( empty( $xfieldsdata[$value[0]] ) ) {
				    if( strpos( $template, "[xfgiven_{$preg_safe_name}=" ) !== false ) 	$template = preg_replace( "'\\[xfgiven_{$preg_safe_name}={(.*?)}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $template );
				     else $template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $template );
				    
				} else {
				    if( strpos( $template, "[xfgiven_{$preg_safe_name}=" ) !== false )$template = preg_replace( "'\\[xfgiven_{$preg_safe_name}={(.*?)}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\2", $template );
				    else $template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $template );
				}
				
				$template = str_replace( "[xfvalue_{$preg_safe_name}]", stripslashes( $xfieldsdata[$value[0]] ), $template );
			} 
		}
	if ($config['version_id'] > 9.6){
	if ( preg_match( "#\\{full-story limit=['\"](.+?)['\"]\\}#i", $template, $matches ) ) {

			$count= intval($matches[1]);

			$row['full_story'] = str_replace( "</p><p>", " ", $row['full_story'] );
			$row['full_story'] = strip_tags( $row['full_story'], "<br>" );
			$row['full_story'] = trim(str_replace( "<br>", " ", str_replace( "<br />", " ", str_replace( "\n", " ", str_replace( "\r", "", $row['full_story'] ) ) ) ));

			if( $count AND dle_strlen( $row['full_story'], $config['charset'] ) > $count ) {
					
				$row['full_story'] = dle_substr( $row['full_story'], 0, $count, $config['charset'] );
					
				if( ($temp_dmax = dle_strrpos( $row['full_story'], ' ', $config['charset'] )) ) $row['full_story'] = dle_substr( $row['full_story'], 0, $temp_dmax, $config['charset'] );
				
			}

			$template = str_replace( $matches[0], $row['full_story'], $template);

		}
	}
	if( $config['files_allow'] == "yes" ) if( strpos( $template, "[attachment=" ) !== false ) {
		$template = show_attach( $template, $news_id );
	}
	if( $config['allow_alt_url'] == "yes" ) {
			
			if( $config['seo_type'] == 1 OR $config['seo_type'] == 2  ) {
				
				if( $row['category'] and $config['seo_type'] == 2 ) {
					
					$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				} else {
					
					$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				}
			
			} else {
				
				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
		
		}
		$template = str_replace( "{link}", $full_link, $template);
echo '<?xml version="1.0" encoding="'.$config['charset'].'"?>'.
'<site>
 <title><![CDATA['.$row['title'].']]></title>
 <des><![CDATA['.$template.']]></des>
 <rate><![CDATA['.$rating.']]></rate>
</site>
';
?>