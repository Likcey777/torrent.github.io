<?php
/**
=====================================================
 Модуль Show-Full v1.1
=====================================================
 Автор MSW
 Сайт поддержки: http://0-web.ru/
=====================================================
 Данный код защищен авторскими правами
-----------------------------------------------------
 Файл: show-full.php
=====================================================
*/

if($_REQUEST['edit']) {
	define('DATALIFEENGINE', true);
	define('ROOT_DIR', '../..');
	define('ENGINE_DIR', '..');
	include ENGINE_DIR.'/data/config.php';
	require_once ENGINE_DIR.'/classes/mysql.php';
	require_once ENGINE_DIR.'/data/dbconfig.php';
	require_once ENGINE_DIR.'/modules/functions.php';
	require_once ENGINE_DIR.'/classes/parse.class.php';
	require_once ENGINE_DIR.'/modules/sitelogin.php';
	require_once ENGINE_DIR.'/init.php';
	@header("Content-type: text/css; charset={$config['charset']}");

	$news_id = intval($_REQUEST['news_id']);
	$_REQUEST['edit'] = intval($_REQUEST['edit']);
	$news_db = $db->query("SELECT short_story, full_story FROM dle_post WHERE id='{$news_id}'");
	$row = $db->get_row($news_db);

	if ($_REQUEST['edit'] == 1) {
		if($user_group[$member_id['user_group']]['allow_hide']) $row['full_story'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $row['full_story']);
		else $row['full_story'] = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">".$lang['news_regus']."</div>", $row['full_story'] );

		if($config['files_allow']=="yes") {
			if( strpos( $row['full_story'], "[attachment=" ) !== false ) {
			$row['full_story'] = show_attach( $row['full_story'], $news_id );
			}
		}
		if( $config['allow_read_count'] == "yes" ) {
			if( $config['cache_count'] ) $db->query( "INSERT INTO ".PREFIX."_views (news_id) VALUES ('{$news_id}')" );
			else $db->query( "UPDATE ".PREFIX."_post set news_read=news_read+1 where id='{$news_id}'" );
		}
		echo stripslashes($row['full_story']);
	} else {
		echo stripslashes($row['short_story']);
	}
} else {
	if(!defined('DATALIFEENGINE')) die("Hacking attempt!");
	$news_id = $this->data['{news-id}'];
echo <<<HTML
<input type="hidden" id="show_full_{$news_id}" value="1" />
<span onclick="show_full({$news_id})" style="cursor:pointer;">Быстрый просмотр</span>
HTML;
}
?>