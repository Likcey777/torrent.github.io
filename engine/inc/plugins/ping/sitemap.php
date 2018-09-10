<?php
if( ! defined( 'DATALIFEENGINE' ) ) {
die( "Hacking attempt!" );
}

@ini_set ('memory_limit',"128M");
@set_time_limit (0);
@ini_set ('max_execution_time',0);
define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');


$user_group = get_vars ( "usergroup" );

if (!is_array( $user_group )) {
	$user_group = array ();

	$db->query ( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );

	while ( $row = $db->get_row () ) {

		$user_group[$row['id']] = array ();

		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}

	}
	set_vars ( "usergroup", $user_group );
	$db->free ();
}


require_once ENGINE_DIR . '/inc/plugins/ping/ping.func.php';
include_once ENGINE_DIR.'/classes/google.class.php';
	$map = new googlemap($config);

	$config['charset'] = strtolower($config['charset']);

	$allow_list = explode ( ',', $user_group[5]['allow_cats'] );
	$not_allow_cats = explode ( ',', $user_group[5]['not_allow_cats'] );
	$stop_list = "";

	if ($allow_list[0] != "all") {
		
		if ($config['allow_multi_category']) {
			
			$stop_list = "category regexp '[[:<:]](" . implode ( '|', $allow_list ) . ")[[:>:]]' AND ";
		
		} else {
			
			$stop_list = "category IN ('" . implode ( "','", $allow_list ) . "') AND ";
		
		}
		
	}

	if( $not_allow_cats[0] != "" ) {
		
		if ($config['allow_multi_category']) {
			
			$stop_list = "category NOT REGEXP '[[:<:]](" . implode ( '|', $not_allow_cats ) . ")[[:>:]]' AND ";
		
		} else {
			
			$stop_list = "category NOT IN ('" . implode ( "','", $not_allow_cats ) . "') AND ";
		
		}
		
	}

	$grow = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_post WHERE {$stop_list}approve=1" );
	
	$map->limit = $grow['count'];

	if ( $map->limit > 45000 ) {

		$pages_count = @ceil( $grow['count'] / 40000 );

		$sitemap = $map->build_index( $pages_count );

		if ( $config['charset'] != "utf-8" ) {
			if( function_exists( 'mb_convert_encoding' ) ) {
		
				$sitemap = mb_convert_encoding( $sitemap, "UTF-8", $config['charset'] );
		
			} elseif( function_exists( 'iconv' ) ) {
			
				$sitemap = iconv($config['charset'], "UTF-8//IGNORE", $sitemap);
			
			}
		}


	    $handler = fopen(ROOT_DIR. "/uploads/sitemap.xml", "wb+");
	    fwrite($handler, $sitemap);
	    fclose($handler);
	
		@chmod(ROOT_DIR. "/uploads/sitemap.xml", 0666);

		$sitemap = $map->build_stat();

		if ( $config['charset'] != "utf-8" ) {

			if( function_exists( 'mb_convert_encoding' ) ) {
		
				$sitemap = mb_convert_encoding( $sitemap, "UTF-8", $config['charset'] );
		
			} elseif( function_exists( 'iconv' ) ) {
			
				$sitemap = iconv($config['charset'], "UTF-8//IGNORE", $sitemap);
			
			}

		}

	    $handler = fopen(ROOT_DIR. "/uploads/sitemap1.xml", "wb+");
	    fwrite($handler, $sitemap);
	    fclose($handler);
	
		@chmod(ROOT_DIR. "/uploads/sitemap1.xml", 0666);

		for ($i =0; $i < $pages_count; $i++) {

			$t = $i+2;
			$n = $n+1;

			$sitemap = $map->build_map_news( $n );

			if ( $config['charset'] != "utf-8" ) {
				if( function_exists( 'mb_convert_encoding' ) ) {
			
					$sitemap = mb_convert_encoding( $sitemap, "UTF-8", $config['charset'] );
			
				} elseif( function_exists( 'iconv' ) ) {
				
					$sitemap = iconv($config['charset'], "UTF-8//IGNORE", $sitemap);
				
				}

			}


		    $handler = fopen(ROOT_DIR. "/uploads/sitemap{$t}.xml", "wb+");
		    fwrite($handler, $sitemap);
		    fclose($handler);
		
			@chmod(ROOT_DIR. "/uploads/sitemap{$t}.xml", 0666);

		}


	} else {

		$sitemap = $map->build_map();

		if ( $config['charset'] != "utf-8" ) {

			if( function_exists( 'mb_convert_encoding' ) ) {
		
				$sitemap = mb_convert_encoding( $sitemap, "UTF-8", $config['charset'] );
		
			} elseif( function_exists( 'iconv' ) ) {
			
				$sitemap = iconv($config['charset'], "UTF-8//IGNORE", $sitemap);
			
			}
		}
	
	    $handler = fopen(ROOT_DIR. "/uploads/sitemap.xml", "wb+");
	    fwrite($handler, $sitemap);
	    fclose($handler);
	
		@chmod(ROOT_DIR. "/uploads/sitemap.xml", 0666);
	}

$url = $config['http_home_url']."sitemap.xml";
if($rss_config['yahookey'] == "" or !$rss_config['yahookey']){
$ping_url = array (
"http://ping.blogs.yandex.ru/ping?sitemap=".$url,
"http://api.moreover.com/ping?u=".$url,
"http://www.google.com/webmasters/sitemaps/ping?sitemap=".$url,
"http://www.submissions.ask.com/ping?sitemap=".$url,
"http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=".$url,
"http://www.bing.com/webmaster/ping.aspx?siteMap=".$url
);
}else{
$ping_url = array (
"http://ping.blogs.yandex.ru/ping?sitemap=".$url,
"http://api.moreover.com/ping?u=".$url,
"http://www.google.com/webmasters/sitemaps/ping?sitemap=".$url,
"http://www.submissions.ask.com/ping?sitemap=".$url,
"http://www.bing.com/webmaster/ping.aspx?siteMap=".$url,
"http://www.search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=".$rss_config['yahookey']."&url=".$url
);
}
$pgg = weblog_ping($ping_url);

if (count($pgg) != '0')$ping_ms =  ' <br /><b><font color="red">Карта отправлена</font></b><br />';

if (count($pgg) != '0') {
$month[1] = "Январ";
$month[2] = "Феврал";
$month[3] = "Март";
$month[4] = "Апрел";
$month[5] = "Ма";
$month[6] = "Июн";
$month[7] = "Июл";
$month[8] = "Август";
$month[9] = "Сентябр";
$month[10] = "Октябр";
$month[11] = "Ноябр";
$month[12] = "Декабр";
$dnum = date("w");
$mnum = date("n");
$daym = date("d");
$year = date("Y");
$textday = $day[$dnum];
$monthm = $month[$mnum];
if ($mnum==3||$mnum==8){$k="а";}else{$k="я";}
$time = date('H:i:s');
$entry_line = "$daym $monthm$k $year года, в $time  | <font color=red>Отправка карты сайта в поисковые системы</font>\n";
$fp = fopen(ENGINE_DIR."/cache/system/pinglogs.txt", "a");fputs($fp, $entry_line);fclose($fp);}
?>

