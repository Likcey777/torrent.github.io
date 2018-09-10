<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if( !$_SESSION['step_update'] ) {
	
	$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_comments_files";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_comments_files (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `c_id` int(10) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `date` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  KEY `c_id` (`c_id`),
  KEY `author` (`author`)
) ENGINE=" . $storage_engine . " DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci";

	$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `allow_up_image` TINYINT(1) NOT NULL DEFAULT '0' , ADD `allow_up_watermark` TINYINT(1) NOT NULL DEFAULT '0' , ADD `allow_up_thumb` TINYINT(1) NOT NULL DEFAULT '0' , ADD `up_count_image` SMALLINT NOT NULL DEFAULT '0' , ADD `up_image_side` VARCHAR(20) NOT NULL DEFAULT '' , ADD `up_image_size` MEDIUMINT(5) NOT NULL DEFAULT '0' , ADD `up_thumb_size` VARCHAR(20) NOT NULL DEFAULT ''";
	$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static_files` CHANGE `onserver` `onserver` VARCHAR(190) NOT NULL DEFAULT ''";
	$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` CHANGE `template` `template` MEDIUMTEXT NOT NULL";
	$tableSchema[] = "ALTER TABLE `" . PREFIX . "_post_extras` ADD INDEX `rating` (`rating`), ADD INDEX `news_read` (`news_read`)";
	$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `allow_up_image` = '1', `allow_up_watermark` = '1', `allow_up_thumb` = '1', `up_count_image` = '3', `up_image_side` = '800x600', `up_image_size`='200', `up_thumb_size`='200x150' WHERE id = '1'";
	$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `allow_up_image` = '0', `allow_up_watermark` = '1', `allow_up_thumb` = '1', `up_count_image` = '3', `up_image_side` = '800x600', `up_image_size`='200', `up_thumb_size`='200x150' WHERE id > '1'";

	foreach($tableSchema as $table) {
		$db->query ($table);
	}

	if ($db->error_count) {
	
		$error_info = "Всего запланировано запросов: <b>".$db->query_num."</b> Неудалось выполнить запросов: <b>".$db->error_count."</b>. Возможно они уже выполнены ранее.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 
	
		foreach ($db->query_list as $value) {
	
			$error_info .= $value['query']."<br /><br />";
	
		}
	
		$error_info .= "</div>";
	
	} else $error_info = "";

	$sql_info = "<br /><br /><div style=\"background:#F2DDDD;border:1px solid #992A2A;padding:5px;color: #992A2A;text-align: justify;\"><b>Важная информация:</b><br /><br />На следующем шаге системе обновления DLE необходимо выполнить тяжелый запрос для таблицы пользователей. На некоторых больших сайтах выполнение данного запроса может занимать продолжительное время и возможно не сможет быть выполнено PHP скриптом. Если скрипт зависнет и запрос не будет выполнен, то вам необходимо будет выполнить данный запрос вручную средствами SSH. Скопируйте запрос, который вам необходимо будет выполнить, если он не будет выполнен автоматически:<br/><br/><b>ALTER TABLE `" . PREFIX . "_comments` ADD INDEX `rating` (`rating`);</b><br /><br /></div>";

	$_SESSION['step_update'] = 1;

	if ( $error_info ) {

		msgbox("info","Информация", "{$error_info}{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	} else {

	    msgbox("info","Информация", "<br /><div style=\"border: 1px solid #475936; background: #6F8F52; color: #FFFFFF;padding:8px;text-align: justify;\">Было успешно выполнено <b>".$db->query_num."</b> запросов.</div>{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	}

	die();
}

if( $_SESSION['step_update'] == 1 ) {

	$db->query ("ALTER TABLE `" . PREFIX . "_comments` ADD INDEX `rating` (`rating`)");

	if ($db->error_count) {
	
		$error_info = "Всего запланировано запросов: <b>".$db->query_num."</b> Неудалось выполнить запросов: <b>".$db->error_count."</b>. Возможно они уже выполнены ранее.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 
	
		foreach ($db->query_list as $value) {
	
			$error_info .= $value['query']."<br /><br />";
	
		}
	
		$error_info .= "</div>";
	
	} else $error_info = "";

	$_SESSION['step_update'] = 2;

	$sql_info = "<br /><br /><div style=\"background:#F2DDDD;border:1px solid #992A2A;padding:5px;color: #992A2A;text-align: justify;\"><b>Важная информация:</b><br /><br />На следующем шаге системе обновления DLE необходимо выполнить тяжелый запрос для таблицы пользователей. На некоторых больших сайтах выполнение данного запроса может занимать продолжительное время и возможно не сможет быть выполнено PHP скриптом. Если скрипт зависнет и запрос не будет выполнен, то вам необходимо будет выполнить данный запрос вручную средствами SSH. Скопируйте запрос, который вам необходимо будет выполнить, если он не будет выполнен автоматически:<br/><br/><b>ALTER TABLE `" . PREFIX . "_users` CHANGE `password` `password` VARCHAR(255) NOT NULL DEFAULT '', ADD `news_subscribe` TINYINT(1) NOT NULL DEFAULT '0', ADD `comments_reply_subscribe` TINYINT(1) NOT NULL DEFAULT '0';</b><br /><br /></div>";

	if ( $error_info ) {

		msgbox("info","Информация", "{$error_info}{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	} else {

	    msgbox("info","Информация", "<div style=\"border: 1px solid #475936; background: #6F8F52; color: #FFFFFF;padding:8px;text-align: justify;\">Был успешно выполнен <b>1 MySQL</b> запрос.</div>{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	}

	die();

}

if( $_SESSION['step_update'] == 2 ) {

	$db->query ("ALTER TABLE `" . PREFIX . "_users` CHANGE `password` `password` VARCHAR(255) NOT NULL DEFAULT '', ADD `news_subscribe` TINYINT(1) NOT NULL DEFAULT '0', ADD `comments_reply_subscribe` TINYINT(1) NOT NULL DEFAULT '0'");
	
	if ($db->error_count) {
	
		$error_info = "Всего запланировано запросов: <b>".$db->query_num."</b> Неудалось выполнить запросов: <b>".$db->error_count."</b>. Возможно они уже выполнены ранее.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 
	
		foreach ($db->query_list as $value) {
	
			$error_info .= $value['query']."<br /><br />";
	
		}
	
		$error_info .= "</div>";
	
	} else $error_info = "";

	$_SESSION['step_update'] = 3;

	$sql_info = "<br /><br /><div style=\"background:#F2DDDD;border:1px solid #992A2A;padding:5px;color: #992A2A;text-align: justify;\"><b>Важная информация:</b><br /><br />На следующем шаге системе обновления DLE необходимо выполнить тяжелый запрос для таблицы пользователей. На некоторых больших сайтах выполнение данного запроса может занимать продолжительное время и возможно не сможет быть выполнено PHP скриптом. Если скрипт зависнет и запрос не будет выполнен, то вам необходимо будет выполнить данный запрос вручную средствами SSH. Скопируйте запрос, который вам необходимо будет выполнить, если он не будет выполнен автоматически:<br/><br/><b>ALTER TABLE `" . PREFIX . "_post` CHANGE `short_story` `short_story` MEDIUMTEXT NOT NULL, CHANGE `full_story` `full_story` MEDIUMTEXT NOT NULL, CHANGE `xfields` `xfields` MEDIUMTEXT NOT NULL, CHANGE `category` `category` VARCHAR(190) NOT NULL DEFAULT '0', CHANGE `alt_name` `alt_name` VARCHAR(190) NOT NULL DEFAULT '', DROP INDEX `tags`;</b><br /><br /></div>";

	if ( $error_info ) {

		msgbox("info","Информация", "{$error_info}{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	} else {

	    msgbox("info","Информация", "<div style=\"border: 1px solid #475936; background: #6F8F52; color: #FFFFFF;padding:8px;text-align: justify;\">Был успешно выполнен <b>1 MySQL</b> запрос.</div>{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	}

	die();
}

if( $_SESSION['step_update'] == 3 ) {

	$db->query ("ALTER TABLE `" . PREFIX . "_post` CHANGE `short_story` `short_story` MEDIUMTEXT NOT NULL, CHANGE `full_story` `full_story` MEDIUMTEXT NOT NULL, CHANGE `xfields` `xfields` MEDIUMTEXT NOT NULL, CHANGE `category` `category` VARCHAR(190) NOT NULL DEFAULT '0', CHANGE `alt_name` `alt_name` VARCHAR(190) NOT NULL DEFAULT ''");
	$db->query ("ALTER TABLE `" . PREFIX . "_post` DROP INDEX `tags`");

	if ($db->error_count) {
	
		$error_info = "Всего запланировано запросов: <b>".$db->query_num."</b> Неудалось выполнить запросов: <b>".$db->error_count."</b>. Возможно они уже выполнены ранее.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 
	
		foreach ($db->query_list as $value) {
	
			$error_info .= $value['query']."<br /><br />";
	
		}
	
		$error_info .= "</div>";
	
	} else $error_info = "";

	$_SESSION['step_update'] = 4;

	$sql_info = "";

	if ( $error_info ) {

		msgbox("info","Информация", "{$error_info}{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	} else {

	    msgbox("info","Информация", "<div style=\"border: 1px solid #475936; background: #6F8F52; color: #FFFFFF;padding:8px;text-align: justify;\">Был успешно выполнен <b>1 MySQL</b> запрос.</div>{$sql_info}<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

	}

	die();
}

if( $_SESSION['step_update'] == 4 ) {

	$config['version_id'] = "11.1";
	$config['fullcache_days'] = "30";
	
	$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Извините, но невозможно записать информацию в файл <b>.engine/data/config.php</b>.<br />Проверьте правильность проставленного CHMOD!");
	fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
	foreach($config as $name => $value)
	{
		fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
	}
	fwrite($handler, ");\n\n?>");
	fclose($handler);

	require_once(ENGINE_DIR.'/data/videoconfig.php');
	
	$video_config['theme'] = "default";

	$con_file = fopen(ENGINE_DIR.'/data/videoconfig.php', "w+") or die("Извините, но невозможно создать файл <b>.engine/data/videoconfig.php</b>.<br />Проверьте правильность проставленного CHMOD!");
	fwrite( $con_file, "<?PHP \n\n//Videoplayers Configurations\n\n\$video_config = array (\n\n" );
	foreach ( $video_config as $name => $value ) {
			
		fwrite( $con_file, "'{$name}' => \"{$value}\",\n\n" );
		
	}
	fwrite( $con_file, ");\n\n?>" );
	fclose($con_file);
	
	$fdir = opendir( ENGINE_DIR . '/cache/system/' );
	while ( $file = readdir( $fdir ) ) {
		if( $file != '.' and $file != '..' and $file != '.htaccess' ) {
			@unlink( ENGINE_DIR . '/cache/system/' . $file );
			
		}
	}
	
	@unlink(ENGINE_DIR.'/data/snap.db');
	
	clear_cache();

	$_SESSION['step_update'] = false;

	msgbox("info","Информация", "Обновление базы данных с версии <b>11.0</b> до версии <b>11.1</b> успешно завершено.<br /><br />Нажмите далее для продолжения процессa обновления скрипта.");

}

?>