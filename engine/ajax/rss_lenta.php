<?php


@error_reporting(7);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/rss.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
include_once ENGINE_DIR . '/data/dbconfig.php';
include_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/templates.class.php';
include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

check_xss();
$_TIME = time() + ($config['date_adjust'] * 60);

$tpl = new dle_template( );
$tpl->dir = ROOT_DIR . '/templates';
define( 'TEMPLATE_DIR', $tpl->dir );


$cat_info = get_vars( "category" );

if( ! $cat_info ) {
	$cat_info = array ();
	
	$db->query( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
	while ( $row = $db->get_row() ) {
		
		$cat_info[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$cat_info[$row['id']][$key] = $value;
		}
	
	}
	set_vars( "category", $cat_info );
	$db->free();
}

$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = $value;
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

$member_id['user_group'] = 5;

$view_template = "rss";

$config['allow_cache'] = true;
$config['allow_banner'] = false;
$config['rss_number'] = intval( $config['rss_number'] );
$config['rss_format'] = intval( $config['rss_format'] );
$cstart = 0;

$config['allow_cache'] = false;


$rss_content = <<<XML
<?xml version="1.0" encoding="{$config['charset']}"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<title>{$config['home_title']}</title>
<link>{$config['http_home_url']}</link>
<language>ru</language>
<description>{$config['home_title']}</description>
<generator>DataLife Engine</generator>
XML;

if( $config['site_offline'] == "yes" or ! $config['allow_rss'] ) {
	
	$rss_content .= <<<XML
<item>
<title>RSS in offline mode</title>
<guid isPermaLink="true"></guid>
<link></link>
<description>RSS in offline mode</description>
<category>undefined</category>
<dc:creator>DataLife Engine</dc:creator>
<pubDate>DataLife Engine</pubDate>
</item>
XML;

} else {
	
	if( $config['rss_format'] == 1 ) {
		
		$tpl->template = <<<XML
<item>
<title>{title}</title>
<guid isPermaLink="true">{rsslink}</guid>
<link>{rsslink}</link>
<description><![CDATA[{short-story}]]></description>
<category><![CDATA[{category}]]></category>
<dc:creator>{rssauthor}</dc:creator>
<pubDate>{rssdate}</pubDate>
</item>
XML;
	
	} elseif( $config['rss_format'] == 2 ) {
		
		$rss_content = <<<XML
<?xml version="1.0" encoding="{$config['charset']}"?>
<rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">
<channel>
<title>{$config['home_title']}</title>
<link>{$config['http_home_url']}</link>
<language>ru</language>
<description>{$config['home_title']}</description>
<image>
<url>{$config['http_home_url']}yandexlogo.gif</url>
<title>{$config['home_title']}</title>
<link>{$config['http_home_url']}</link>
</image>
<generator>DataLife Engine</generator>
XML;
		
		$tpl->template = <<<XML
<item>
<title>{title}</title>
<link>{rsslink}</link>
<description>{short-story}</description>
<category>{category}</category>
<author>{rssauthor}</author>
<pubDate>{rssdate}</pubDate>
<yandex:full-text>{full-story}</yandex:full-text>
</item>
XML;
	
	} else {
		
		$tpl->template = <<<XML
<item>
<title>{title}</title>
<guid isPermaLink="true">{rsslink}</guid>
<link>{rsslink}</link>
<description>{short-story}</description>
<category>{category}</category>
<dc:creator>{rssauthor}</dc:creator>
<pubDate>{rssdate}</pubDate>
</item>
XML;
	
	}
	@unlink (ENGINE_DIR . '/cache/rss.tmp');
	$tpl->copy_template = $tpl->template;
	$config['rss_number'] = $_POST['nn'];
	include_once ENGINE_DIR . '/engine.php';
	
	$rss_content .= $tpl->result['content'];
}

$rss_content .= '</channel></rss>';

$handle = fopen(ROOT_DIR . '/grabber.xml', 'w+');
fwrite($handle,$rss_content);
fclose($handle);

?>