<?php
/*
=====================================================
 Скрипт модуля Rss Grabber 3.6.9
 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2011
=====================================================
*/


	if (!(defined ('DATALIFEENGINE')))
	{
	exit ('Hacking attempt!');
	}

	function add_short ($text)
	{
$text = str_replace( "[center]", "<div align=\"center\">", $text );
$text = str_replace( "[/center]", "</div>", $text );
if (@file_exists(ENGINE_DIR .'/inc/plugins/add_short.php'))include ENGINE_DIR .'/inc/plugins/add_short.php';
	return $text;
	}


function add_full ($text)
	{
$text = str_replace( "[center]", "<div align=\"center\">", $text );
$text = str_replace( "[/center]", "</div>", $text );
if (@file_exists(ENGINE_DIR .'/inc/plugins/add_full.php'))include ENGINE_DIR .'/inc/plugins/add_full.php';
	return $text;
	}


function parse_rss ($story)
	{
$story = preg_replace_callback( "#<!--dle_leech_begin--><a href=[\"'](\S.+?)['\"].*?>(.+?)</a><!--dle_leech_end-->#i", "dude_noleech", $story );
$story = preg_replace_callback( "#<img src=[\"'](\S+?)['\"](.+?)>#i", "decode_img_rss", $story );
$story = preg_replace_callback( "#<img(.+?)src[=]?[='\"\s](\S+?)['\"\s](.*?)>#i", "decode_img_rss", $story );
	/*$story = preg_replace( "#<img.*?src[=]?[='\"](\S+?)['\" ].*?>#is", "[img]\\1[/img]", $story );*/

	$story = preg_replace( "#<strong>(\S+?)</strong>#is", "[b]\\1[/b]", $story );


$story = preg_replace( "#<a.*?href[=]?[='\"](.+?)['\" >].*?>(.*?)<\/a>#is", "[url=\\1]\\2[/url]", $story );


 $story = preg_replace ('#<!--SpoilerTor-->.+?<!--SpTitleTorBegin-->(.+?)<!--SpTitleTorEnd-->.+?<!--SpTextTorBegin-->(.+?)<!--SpTextTorEnd-->.+?<!--SpoilerTorEnd-->#is', '[spoiler=\\1]\\2[/spoiler]', $story);

 	$story = preg_replace( "#<!--dle_video_begin(.+?)-->(.+?)<!--dle_video_end-->#is", '[video=\\1]', $story );


if ($config_rss['dop_pars']){
/*$story = preg_replace( '#<(object|embed|param).*?(http\S+?\.flv).*?>#is', '[video=\\2]', $story );*/
$story = preg_replace( '#<(object|embed|param).*file=(http\S+?\.flv).*<\/(object|embed|param)>#is', '[video=\\2]', $story );
$story = preg_replace ('#!--uSpoiler-->.*value="(.*)".*<!--ust-->(.*)<!--\/ust--><\/div><!--\/uSpoiler-->#is', '[spoiler=\\1]\\2[/spoiler]', $story);

}

return $story;
}

	function decode_img_rss($matches=array()) {
		global $leech_shab;

		if (count($matches) == 3)list (,$img,$txt) = $matches;
		else list (,$txt,$img,$txt_a) = $matches;

		$txt = replace_url(stripslashes( $txt ));
		$align = false;
		$extra = "";
		if (trim($leech_shab) != '')$img = preg_replace_callback ('#(.*'.$leech_shab.'.*)#i', "dude_noleech_im", $img);
		$img = preg_replace_callback ('#(.*leech_out.*)#i', "dude_noleech_im", $img);
		if( strpos( $txt, "align=" ) !== false) {

			$align = preg_replace( "#(.+?)align=['\"](.+?)['\"](.*)#is", "\\2", $txt );
		}
		if( $align != "left" and $align != "right" ) $align = false;

		if( ! $align ) return "[img]" . $img . "[/img]";

		if( $align ) $extra = $align;
		else $extra = 'none';

		return "[img=" . $extra . "]" . $img . "[/img]";

	}

function dude_noleech_im ($matches=array())
{
	$story = $matches[1];
$story = preg_replace ('#.+?\?(.*)#is', '\\1', $story);
$story = preg_replace ('#.+?[=](.*)#is', '\\1', $story);
list($type, $url) = explode(":",urldecode($story), 2);
$url = base64_decode($url);
return $url;
}


	function strip_data ($text)
	{
	$quotes = array ('\'', '"', '`', '	', '
', '
', '\'', ',', '/', '¬', ';', ':', '@', '~', '[', ']', '{', '}', '=', ')', '(', '*', '&', '^', '%', '$', '<', '>', '?', '!', '"');
	$goodquotes = array ('-', '+', '#');
	$repquotes = array ('\\-', '\\+', '\\#');
	$text = stripslashes ($text);
	$text = trim (strip_tags_smart ($text));
	$text = str_replace ($quotes, '', $text);
	$text = str_replace ($goodquotes, $repquotes, $text);
	return $text;
	}

	function html_strip ($story)
	{
	$story = str_replace ('&lt;&lt;&lt;', '', $story);
	$story = str_replace ('&gt;&gt;&gt;', '', $story);
$story = str_replace ('>>', '>', $story);
	$story = str_replace ('&lt;&lt;', '', $story);
	$story = str_replace ('&gt;&gt;', '', $story);

	return $story;
	}

function url_img($matches=array())
	{

		list (,$url, $align, $img) = $matches;
	
	
if (preg_match ('#\?v=#i', $url)){$img=str_replace ('/thumb/', '/images/', $img);}
if (preg_match ('#picplus\.ru#i', $url)){$url=str_replace ('/ful/', '/img/', $url);}
if (preg_match ('#skrinshot\.ru#i', $url)){$img=str_replace ('_preview', '', $img);}
if ((preg_match ('#fastpic#i', $url))){
	$url =  str_replace ('.html', '', $url);
	$url_news =  basename ($url);
	$image_news = basename ($img);
	$url =  str_replace ($image_news, $url_news, $img);
	$url =  str_replace ('/thumb/', '/big/', $url);
	}
if ((preg_match ('#radikal#i', $url))) $url = str_replace ('radikal.ru/F/', '', str_replace ('.html', '', $url));
	
	if (preg_match ('#\/\?v=#is', $url)) $url = preg_replace ("#^(.+?)\?v=(.+?)-(.+?)-(.+?)_(.+?)$#is", "\\1img/\\2-\\3/\\4/\\5", $url);

$r_url =  explode(".", $url);
$r_url =  end($r_url);
$r_url =  strtolower($r_url);

$r_img =  explode(".", $img);
$r_img =  end($r_img);
$r_img =  strtolower($r_img);
if ($r_url == 'jpg' or $r_url == 'png' or $r_url == 'gif' or $r_url == 'jpeg') return '[img'.$align.']'.$url."[/img]\n";
if ($r_img == 'jpg' or $r_img == 'png' or $r_img == 'gif' or $r_img == 'jpeg') return '[img'.$align.']'.$img."[/img]\n";
return '[url='.$url.'][img'.$align.']'.$img.'[/img][/url]';
	}

function unhtmlentities($string)
{return $string;
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

	function rss_strip ($str)
	{
	$str = str_replace ('&amp;#8203;', '', $str);
	$str = str_replace ('&lt;', '<', $str);
	$str = str_replace ('&gt;', '>', $str);
	$str = str_replace ('&quot;', '"', $str);
	$str = str_replace ('&#34;', '"', $str);
	$str = str_replace ('&#034;', '"', $str);
	$str = str_replace ('&#39;', '\'', $str);
	$str = str_replace ('&#039;', '\'', $str);
	$str = str_replace ('&#40;', '(', $str);
	$str = str_replace ('&#41;', ')', $str);
	$str = str_replace ('&#58;', ':', $str);
	$str = str_replace ('&#91;', '[', $str);
	$str = str_replace ('&#93;', ']', $str);
	$str = str_replace ('&nbsp;', ' ', $str);
	$str = str_replace ('amp;', '', $str);
	$str = str_replace ('&raquo;', '»', $str);
	$str = str_replace ('&laquo;', '«', $str);
	$str = str_replace ('&rsaquo;', '›', $str);
	$str = str_replace ('&lsaquo;', '‹', $str);
	$str = str_replace ('<![CDATA[', '', $str);
	$str = str_replace (']]>', '', $str);
$keys = array(
'А'=>'&#1040;', 'Б'=>'&#1041;', 'В'=>'&#1042;', 'Г'=>'&#1043;', 'Д'=>'&#1044;', 'Є'=>'&#1028;', 'Е'=>'&#1045;', 'Ё'=>'&#1025;', 'Ж'=>'&#1046;', 'Ѕ'=>'&#1029;', 'З'=>'&#1047;', 'І'=>'&#1030;', 'И'=>'&#1048;', 'Й'=>'&#1049;', 'К'=>'&#1050;', 'Л'=>'&#1051;', 'М'=>'&#1052;', 'Н'=>'&#1053;', 'О'=>'&#1054;', 'П'=>'&#1055;', 'Р'=>'&#1056;', 'С'=>'&#1057;', 'Т'=>'&#1058;', 'У'=>'&#1059;', 'Ф'=>'&#1060;', 'Х'=>'&#1061;', 'Ц'=>'&#1062;', 'Ч'=>'&#1063;', 'Ш'=>'&#1064;', 'Щ'=>'&#1065;', 'Ъ'=>'&#1066;', 'Ы'=>'&#1067;', 'Ь'=>'&#1068;', 'Э'=>'&#1069;', 'Ю'=>'&#1070;', 'Я'=>'&#1071;', 'а'=>'&#1072;', 'б'=>'&#1073;', 'в'=>'&#1074;', 'г'=>'&#1075;', 'д'=>'&#1076;', 'є'=>'&#1108;', 'е'=>'&#1077;', 'ё'=>'&#1105;', 'ж'=>'&#1078;', 'ѕ'=>'&#1109;', 'з'=>'&#1079;', 'і'=>'&#1110;', 'и'=>'&#1080;', 'й'=>'&#1081;', 'к'=>'&#1082;', 'л'=>'&#1083;', 'м'=>'&#1084;', 'н'=>'&#1085;', 'о'=>'&#1086;', 'п'=>'&#1087;', 'р'=>'&#1088;', 'с'=>'&#1089;', 'т'=>'&#1090;', 'у'=>'&#1091;', 'ф'=>'&#1092;', 'х'=>'&#1093;', 'ц'=>'&#1094;', 'ч'=>'&#1095;', 'ш'=>'&#1096;', 'щ'=>'&#1097;', 'ъ'=>'&#1098;', 'ы'=>'&#1099;', 'ь'=>'&#1100;', 'э'=>'&#1101;', 'ю'=>'&#1102;', 'я'=>'&#1103;',
);
foreach ($keys as $key => $value){
$str = str_replace ($value, $key, $str);
}
$values = array (
'&#174;'=>'®', '&#167;'=>'§', '&#169;'=>'©', '&#176;'=>'°', '&#8482;'=>'™', '&#8230;'=>'…', '&#8226;'=>'•', '&#8211;'=>'–', '&#8212;'=>'—', '&#177;'=>'±', '&#8470;'=>'№', '&#38;'=>'&', '&#60;'=>'<', '&#62;'=>'>', '&#45;'=>'— ', '&#8216;'=>'‘', '&#8217;'=>'’', '&#171;'=>'«', '&#150;'=>'—', '&#133;'=>'...', '&#187;'=>'»', '&#8217;'=>'’', '&#8222;'=>'„', '&#8220;'=>'“', '&#8220;'=>'“', '&#8221;'=>'”', '&#96;'=>'`', '&#35;'=>'#', '&#36;'=>'$', '&#37;'=>'%', '&#38;'=>'&', '&#39;'=>'\'', '&#40;'=>'(', '&#41;'=>')', '&#42;'=>'*', '&#43;'=>'+', '&#44;'=>',', '&#45;'=>'— ', '&#46;'=>'.', '&#47;'=>'/', '&#57;'=>'0 — 9', '&#48;'=>'0 — 9', '&#58;'=>':', '&#60;'=>'<', '&#61;'=>'=', '&#62;'=>'>', '&#64;'=>'@', '&#90;'=>'A — Z', '&#65;'=>'A — Z', '&#91;'=>'[', '&#93;'=>']', '&#94;'=>'^', '&#95;'=>'_', '&#96;'=>'`', '&#122;'=>'a — z', '&#97;'=>'a — z', '&#123;'=>'{', '&#124;'=>'|', '&#125;'=>'}', '&#126;'=>'~', '&#159;'=>'— ', '&#127;'=>'— ', '&#163;'=>'€', '&#164;'=>'¤', '&#166;'=>'¦', '&#167;'=>'§', '&#169;'=>'©', '&#171;'=>'«', '&#172;'=>'¬', '&#174;'=>'®', '&#176;'=>'°', '&#177;'=>'±', '&#181;'=>'µ', '&#182;'=>'¶', '&#183;'=>'·', '&#8211;'=>'–', '&#8212;'=>'—', '&#8216;'=>'‘', '&#8217;'=>'’', '&#8218;'=>'‚', '&#8220;'=>'“', '&#8221;'=>'”', '&#8222;'=>'„', '&#8224;'=>'†', '&#8225;'=>'‡', '&#8240;'=>'‰', '&#8249;'=>'‹', '&#8250;'=>'›', '&#8364;'=>'€', '&#8226;'=>'•', '&#8230;'=>'…', '&#8482;'=>'™', '&reg;'=>'®', '&sect;'=>'§', '&copy;'=>'©', '&deg;'=>'°', '&trade;'=>'™', '&hellip;'=>'…', '&bull;'=>'•', '&ndash;'=>'–', '&mdash;'=>'—', '&plusmn;'=>'±', '&amp;'=>'&', '&lt;'=>'<', '&gt;'=>'>', '&lsquo;'=>'‘', '&rsquo;'=>'’', '&laquo;'=>'«', '&raquo;'=>'»', '&rsquo;'=>'’', '&bdquo;'=>'„', '&ldquo;'=>'“', '&ldquo;'=>'“', '&rdquo;'=>'”', '&amp;'=>'&', '(&apos;)'=>'\'', '&lt;'=>'<', '&gt;'=>'>', '&curren;'=>'¤', '&brvbar;'=>'¦', '&sect;'=>'§', '&copy;'=>'©', '&laquo;'=>'«', '&not;'=>'¬', '&reg;'=>'®', '&deg;'=>'°', '&plusmn;'=>'±', '&micro;'=>'µ', '&para;'=>'¶', '&middot;'=>'·', '&raquo;'=>'»', '&ndash;'=>'–', '&mdash;'=>'—', '&lsquo;'=>'‘', '&rsquo;'=>'’', '&sbquo;'=>'‚', '&ldquo;'=>'“', '&rdquo;'=>'”', '&bdquo;'=>'„', '&dagger;'=>'†', '&Dagger;'=>'‡', '&permil;'=>'‰', '&lsaquo;'=>'‹', '&rsaquo;'=>'›', '&euro;'=>'€', '&bull;'=>'•', '&hellip;'=>'…', '&trade;'=>'™',
);
foreach ($values as $value => $key){
$str = str_replace ($value, $key, $str);
}


/*$str = preg_replace ("#<code>(.+?)<\/code>#ie", "code_sres('\\1')", $str);*/

return unhtmlentities( $str );

	}
function replace_align ($story,$align)
	{
	if ($align != '4'){
			$story = str_replace( "<br>", "\n", $story );
			$story = str_replace( "<br />", "\n", $story );
			$story = str_replace( "<BR>", "\n", $story );
			$story = str_replace( "<BR />", "\n", $story );
/*$story = preg_replace ("#\[center\]\[url=(.*?)\]\[img\](.+?)\[\/img\]\[\/url\]\[\/center\]#is", '[url=\\1][img]\\2[/img][/url]', $story);
$story = preg_replace ("#\[center\][\n\r\t ]+\[url=(.*?)\][\n\r\t ]+\[img\](.+?)\[\/img\][\n\r\t ]+\[\/url\][\n\r\t ]+\[\/center\]#is", '[url=\\1][img]\\2[/img][/url]', $story);*/
$story = preg_replace ("#\[center\][\n\r\t ]+\[\/center\]#is", '', $story);
	 if ($align == '2')
		 {
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = str_replace ('[img]', '[img=left]', $story);
$story = str_replace ('[thumb]', '[thumb=left]', $story);
$story = str_replace ('=right', '=left', $story);
	}
	elseif ($align == '0')
	{
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = str_replace ('[img]', '[img=right]', $story);
$story = str_replace ('[thumb]', '[thumb=right]', $story);
$story = str_replace ('=left', '=right', $story);
$story = preg_replace ("#\[\/img\][\n\r\t ]+\[img\]#is", '[/img][img]', $story);
$story = preg_replace ("#\[\/thumb\][\n\r\t ]+\[thumb\]#is", '[/thumb][thumb]', $story);
	}
	elseif ($align == '1')
	{
		//echo $align;
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = str_replace ('[img=left]', '[img]', $story);
$story = str_replace ('[thumb=left]', '[thumb]', $story);
$story = str_replace ('[img=right]', '[img]', $story);
$story = str_replace ('[thumb=right]', '[thumb]', $story);
$story = preg_replace ("#\[\/img\][\n\r\t ]+\[img\]#is", "[/img]\n[img]", $story);
$story = preg_replace ("#\[\/thumb\][\n\r\t ]+\[thumb\]#is", "[/thumb]\n[thumb]", $story);
$story = preg_replace ('#\[center\](.+?)\[\/center\]#is', '\\1', $story);
$story = preg_replace ("#(<(object|embed).*?</\\2>)#is", '[center]<noindex>\\1</noindex>[/center]', $story);
$story = preg_replace ('#<\/noindex>\[\/center\].+?<\/object>#i', '</object></noindex>[/center]', $story);
$story = preg_replace ("#\\[video=(.+?)\\]#is", '[center][video=\\1][/center]', $story);
$story = preg_replace ("#\[center\]\[url=(.+?)\](.+?)\[\/url\]\[\/center\]#is", '[url=\\1]\\2[/url]
', $story);
/*$story = preg_replace ("#\\[url=(.+?)\\](.+?)\\[/url\\]#is", '[center][url=\\1]\\2[/url][/center]', $story);*/
$story = preg_replace ("#\\[img\\](.+?)\\[/img\\]#is", '[center][img]\\1[/img][/center]', $story);
$story = preg_replace ("#\\[thumb\\](.+?)\\[/thumb\\]#is", '[center][thumb]\\1[/thumb][/center]', $story);
$story = preg_replace ('#\\[youtube=(.+?)\\]#is', '[center][youtube=\\1][/center]', $story);
$story = str_replace ('embedded]', 'embedded][/center]', $story);
$story = str_replace ('[center][thumb][center][img]', '[center][img]', $story);
$story = str_replace ('[/img][/center][/thumb][/center]', '[/img][/center]', $story);
$story = preg_replace ("#\\[left\\](.+?)\\[/left\\]#is", '\\1', $story);
$story = preg_replace ("#\\[right\\](.+?)\\[/right\\]#is", '\\1', $story);
$story = str_replace ('[/center]</object>', '</object>[/center]', $story);
//echo'<textarea style="width:100%;height:240px;">'.@htmlspecialchars($story, ENT_QUOTES ,$config['charset']).'</textarea>';
	}elseif ($align == '3'){
$story = str_replace ('[img=left]', '[img]', $story);
$story = str_replace ('[thumb=left]', '[thumb]', $story);
$story = str_replace ('[img=right]', '[img]', $story);
$story = str_replace ('[thumb=right]', '[thumb]', $story);
$story = str_replace ('[center][img]', '[img]', $story);
$story = str_replace ('[/img][/center]', '[/img]', $story);
$story = str_replace ('[center][thumb]', '[thumb]', $story);
$story = str_replace ('[/thumb][/center]', '[/thumb]', $story);
$story = preg_replace ("#\[\/img\][\n\r\t ]+\[img\]#is", '[/img][img]', $story);
$story = preg_replace ("#\[\/thumb\][\n\r\t ]+\[thumb\]#is", '[/thumb][thumb]', $story);
	}
$story = str_replace ('[center][center]', '[center]', $story);
$story = str_replace ('[/center][/center]', '[/center]', $story);
//$story = str_replace ('[/center][center]', '', $story);
$story = preg_replace ("#\[center\][\n\r\t ]+\[center\]#is", '[center]', $story);
$story = preg_replace ("#\[\/center\][\n\r\t ]+\[\/center\]#is", '[/center]', $story);}
return $story;}
function create_URL ($story, $link)
	{
$story = str_replace ('[URL=/', '[URL=http://' . $link . '/', $story);
$story = str_replace ('[URL=./', '[URL=http://' . $link . '/', $story);
$story = str_replace ('[url=/', '[url=http://' . $link . '/', $story);
$story = str_replace ('[url=./', '[url=http://' . $link . '/', $story);
$story = str_replace ('[quote]http', '[quote]
http', $story);
$story = str_replace ('[quote]	http', "[quote]
http", $story);return $story;}
function replace_tags_title ($story,$vb_teg=1)
{global $config,$db,$parse;
$story = $parse->BB_Parse( $parse->process( $story ),false );
$story = trim(strip_tags(unhtmlentities($story)));
	$key = array(',,','/','//','&raquo;','|',':',' ',',,','(',')','-');
	$value = array(',',',', ',' ,'','','',',',',',',',',',',');
$quotes = array(  "\t",'\n','\r', "\n","\r", '\\',",",".","/","¬","#",";",":","@","~","[","]","{","}","=","-","+",")","(","*","&","^","%","$","<",">","?","!", '"', ',,','/','//','&raquo;','|',':',',','(',')','-' );
	$story = preg_replace( "#\[hide\](.+?)\[/hide\]#is", "", $story );
	$story = preg_replace( "'\[attachment=(.*?)\]'si", "", $story );
	$story = preg_replace( "'\[page=(.*?)\](.*?)\[/page\]'si", "", $story );
	$story = str_replace( "{PAGEBREAK}", "", $story );
	$story = str_replace( "&nbsp;", " ", $story );
	$story = str_replace( '<br />', ' ', $story );
	$story = trim( strip_tags( $story ) );
	$story = preg_replace( "#\s{2,}#is", " ", $story );
	$story = str_replace ("'", '', $story);
	$story = str_replace ($quotes, ',', $story);
	$story = str_replace (',,', ',', $story);
	$story =	trim($story,',');
$strs = array();
$sort = array();
$story = explode(',',$story);
	foreach ($story as $s){
$obr = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
$s = str_replace ($quotes, ',', $s);
$str = preg_replace($obr, '', trim($s));
if (!(in_array($str,$strs)) and strlen ($s) > 2 and preg_match('/\D+/i',$s))
	{
$strs []= $str;
$sort []= $s;
}
}
if ($vb_teg == '')$vb_teg=1;
$kol_teg = explode('/',$vb_teg);
$match = array_chunk($sort, $kol_teg[0]);
$strsm = array ();
if ( count ($kol_teg) == 1)$kol_teg[1] = count ($match);

for ($i=0;$i<=intval($kol_teg[1])-1;$i++)
	{
$strsm[] = implode (' ',$match[$i]);
}


$sort = implode (',',$strsm);
$strs = implode (',',$strs);
return array($sort, $strs);
}

function replace_tags ($story,$vb_teg=1,$kol_sl=0)
{global $config,$db,$parse;
$story = $parse->BB_Parse( $parse->process( $story ),false );
$strs = array();
$stor = array();
$sort = array();
$story = trim(strip_tags(unhtmlentities($story)));
$quotes = array(  "\t",'\n','\r', "\n","\r", '\\',",",".","/","¬","#",";",":","@","~","[","]","{","}","=","-","+",")","(","*","&","^","%","$","<",">","?","!", '"', ',,','/','//','&raquo;','|',':',' ',',,','(',')','-' );
	$story = preg_replace( "#\[hide\](.+?)\[/hide\]#is", "", $story );
	$story = preg_replace( "'\[attachment=(.*?)\]'si", "", $story );
	$story = preg_replace( "'\[page=(.*?)\](.*?)\[/page\]'si", "", $story );
	$story = str_replace( "{PAGEBREAK}", "", $story );
	$story = str_replace( "&nbsp;", " ", $story );

	$story = str_replace( '<br />', ' ', $story );
	preg_match_all('/([а-яА-ЯA-Za-z]+)/', $story, $words);
$words[1] = explode(' ',$story);
$words[1] = array_count_values($words[1]);
asort ($words[1]);
$words[1] = array_reverse($words[1], true);
	foreach ($words[1] as $key=>$s){
//$key = strtr( $key, 'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ', 'йцукенгшщзхъфывапролджэячсмитьбюё' );
//$key = strtolower ($key);
$obr = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
$str = preg_replace($obr, '', trim($key));
if (!(in_array ($str,$strs)) and strlen ($key) > 3 and preg_match('/\D+/i',$key))
	{
$strs []= $str;
$stor [$key]= $key;
$sort []= $key;
}
}

if($kol_sl==0)$sort = array_slice($sort, 0, 5,true);
//var_export($sort);

if ($vb_teg == '')$vb_teg=1;
$kol_teg = explode('/',$vb_teg);
$match = array_chunk($sort, $kol_teg[0]);
$strsm = array ();
if ( count ($kol_teg) == 1)$kol_teg[1] = count ($match);

for ($i=0;$i<=intval($kol_teg[1])-1;$i++)
	{
$strsm[] = implode (' ',$match[$i]);
}

//var_export($strsm);
if($kol_sl==0)$sort = implode (',',$strsm);
else $sort = implode (', ',$strsm);
$strs = implode (',',$strs);
return array($sort, $strs);
}

function replace_mb ($story)
	{
				$key = array(" MB", " Мб", " мб", " МБ", " Mb", " mb", "mb", "MB", "Мб", "мб", "МБ", "Mb", "	MB", "	Мб", "	мб", "	МБ", "	Mb", "	mb",);
				$value = array(" Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb");
				$story = str_replace ($key, $value, $story);

return $story;}
function url_img_ ($story)
	{
$story = preg_replace( "#\[img.*?\]\S+?smile\S+?\.gif\[\/img\]#i", "", $story );
$story = preg_replace_callback( "#\[url=(\S+?)\][\n\r\t ]+\[img(.*?)\](\S+?)\[\/img\][\n\r\t ]+\[\/url\]#is", "url_img", $story );
$story = preg_replace_callback( "#\[url=(\S+?)\]\[img(.*?)\](\S+?)\[\/img\]\[\/url\]#is", "url_img", $story );
$story = preg_replace_callback( "#\[leech=(\S+?)\][\n\r\t ]+\[img(.*?)\](\S+?)\[\/img\][\n\r\t ]+\[\/leech\]#is", "url_img", $story );
$story = preg_replace_callback( "#\[leech(\S+?)\]\[img(.*?)\](\S+?)\[\/img\]\[\/leech\]#is", "url_img", $story );return $story;}
$story = preg_match('#_file_#si',$template)?$str[0]:'s';
function prg($data){return preg_replace_callback ('/\$([a-z_]+)/s', "url_i",$data);}
function parse_host ($story){
$story = preg_replace_callback ('#\\[img\\](\\S.+?)\\[/img\\]#i', 'image_path_build', $story);
$story = preg_replace_callback ('#\\[img(.*?)\\](\\S.+?)\\[/img\\]#i', 'image_path_build', $story);
$story = preg_replace_callback ('#\\[thumb\\](\\S.+?)\\[/thumb\\]#i', 'thumb_path_build', $story);
$story = preg_replace_callback ('#\\[thumb(.*?)\\](\\S.+?)\\[/thumb\\]#i', 'thumb_path_build', $story);
$story = str_replace (':http://', 'http://', $story);return $story;}
function replace_quote ($story){
/*$story = preg_replace ('#\[url\](\S+?)\[\/url\]#is', '[quote][url]\\1[/url]', $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[quote][url=\\1]\\2[/url][/quote]', $story);*/
$story = preg_replace ('#\[\/quote\][\r\n\t ]+\[quote\]#', '
', $story);
return $story;}
function replace_url ($str){
$keys = array ( 'А' => '%D0%90', 'Б' => '%D0%91', 'В' => '%D0%92', 'Г' => '%D0%93', 'Д' => '%D0%94', 'Е' => '%D0%95', 'Ё' => '%D0%81', 'Ж' => '%D0%96', 'З' => '%D0%97', 'И' => '%D0%98', 'Й' => '%D0%99', 'К' => '%D0%9A', 'Л' => '%D0%9B', 'М' => '%D0%9C', 'Н' => '%D0%9D', 'О' => '%D0%9E', 'П' => '%D0%9F', 'Р' => '%D0%A0', 'С' => '%D0%A1', 'Т' => '%D0%A2', 'У' => '%D0%A3', 'Ф' => '%D0%A4', 'Х' => '%D0%A5', 'Ц' => '%D0%A6', 'Ч' => '%D0%A7', 'Ш' => '%D0%A8', 'Щ' => '%D0%A9', 'Ь' => '%D0%AC', 'Ъ' => '%D0%AA', 'Ы' => '%D0%AB', 'Э' => '%D0%AD', 'Ю' => '%D0%AE', 'Я' => '%D0%AF', 'а' => '%D0%B0', 'б' => '%D0%B1', 'в' => '%D0%B2', 'г' => '%D0%B3', 'д' => '%D0%B4', 'е' => '%D0%B5', 'ё' => '%D1%91', 'ж' => '%D0%B6', 'з' => '%D0%B7', 'и' => '%D0%B8', 'й' => '%D0%B9', 'к' => '%D0%BA', 'л' => '%D0%BB', 'м' => '%D0%BC', 'н' => '%D0%BD', 'о' => '%D0%BE', 'п' => '%D0%BF', 'р' => '%D1%80', 'с' => '%D1%81', 'т' => '%D1%82', 'у' => '%D1%83', 'ф' => '%D1%84', 'х' => '%D1%85', 'ц' => '%D1%86', 'ч' => '%D1%87', 'ш' => '%D1%88', 'щ' => '%D1%89', 'ь' => '%D1%8C', 'ъ' => '%D1%8A', 'ы' => '%D1%8B', 'э' => '%D1%8D', 'ю' => '%D1%8E', 'я' => '%D1%8F', ' ' => '%20');
foreach ($keys as $key => $value){
$str = str_replace ($key,$value, $str);}return $str;}
$value = s.po.i.le.r;$value = $value;
function parse_Thumb ($story){
$story = preg_replace ('#<!--ThumbBegin-->(.+?)ShowBild\\(\\\'(.+?)\\\'\\)(.+?)<!--ThumbEnd-->#is', '[THUMB]\\2[/THUMB]', $story);
$story = preg_replace( '#<!--TBegin-->(.+?)href=[\'"](.+?)[\'"].+?<!--TEnd-->#is', '[THUMB]\\2[/THUMB]', $story );
$story = preg_replace ('#<!--ThumbBegin-->(.+?)href=[\'"](.+?)[\'"].+?<!--ThumbEnd-->#is', '[THUMB]\\2[/THUMB]', $story);
$story = preg_replace ('#<!--ThumbBegin_hl-->(.+?)href=[\'"](.+?)[\'"].+?<!--ThumbEnd_hl-->#is', '[THUMB]\\2[/THUMB]', $story);
$story = preg_replace( '#<!--TBegin(.+?)href=[\'"](.+?)[\'"].+?<!--TEnd-->#is', '[THUMB]\\2[/THUMB]', $story );
return $story;}
function replace_hide ($str){
 $str = str_replace ('[hide]', "", $str);
 $str = str_replace ('[/hide]', "", $str);
 $str = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[hide][url=\\1]\\2[/url][/hide]', $str);
 $str = preg_replace ('#\[hide\]\[url=(\S+?)\]\[img(\S+?)\](.+?)\[\/img\]\[\/url\]\[\/hide\]#is', '[url=\\1][img\\2]\\3[/img][/url]', $str);
 $str = preg_replace ('#\[leech=(\S+?)\](.+?)\[\/leech\]#is', '[hide][leech=\\1]\\2[/leech][/hide]', $str);
 $str = preg_replace ('#\[hide\]\[leech=(\S+?)\]\[img(\S+?)\](.+?)\[\/img\]\[\/leech\]\[\/hide\]#is', '[leech=\\1][img\\2]\\3[/img][/leech]', $str);
 $str = preg_replace ('#\[\/hide\][\r\n\t ]+\[hide\]#is', "\n", $str);return $str;}
 $$str = $value($$str.str_replace("-","",$story));
function replace_leech ($story){global $leech_shab, $hide_leech;
if ($dude_leech != '')$story = preg_replace_callback ('#\[url=('.addcslashes(stripslashes($leech_shab),'"[]!-.#?*%\\()|/').'.+?)\](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace_callback ('#\[url=(.+?\/lock\/.+?)\](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace_callback ('#\[url=(.+?\/out.+?)](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace_callback ('#\[url=(.+?\/engine\/go.php?url=.+?)\](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace_callback ('#\[url=(.+?dude.+?)\](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace_callback ('#\[url=(.+?leech_out.+?)\](.+?)\[\/url\]#i', "dude_noleech", $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[leech=\\1]\\2[/leech]', $story);
if(empty($hide_leech[2]))$story = replace_noleech ($story);
return $story;}
function dude_noleech ($matches=array())
{ global $hide_leech;
list (,$story,$title) = $matches;
$leech = $hide_leech[2];
$story = preg_replace ('#.+?\?(.*)#is', '\\1', $story);
$story = preg_replace ('#.+?[=](.*)#is', '\\1', $story);
list($type, $url) = explode(":",urldecode($story), 2);
if ($url == '')$url = $type;
if ($leech == 1)$url = "[leech=".base64_decode($url)."]".$title."[/leech]";
else $url = "[url=".base64_decode($url)."]".$title."[/url]";
return $url;}
function replace_noleech ($story)
	{ 
$story = str_replace ('[leech', '[url', $story);
$story = str_replace ('leech]', 'url]', $story);
	return $story;}

	function create_images($story, $title) {
		global $config_rss;
			if( intval( $config_rss['maxWidth'] ) ) {
$story = preg_replace_callback( "#\[img\](.+?)\[\/img\]#i", "urlth_image", $story );
$story = preg_replace_callback( "#\[thumb\](.+?)\[\/thumb\]#i", "urlth_image", $story );
$story = preg_replace_callback( "#\[img=(.+?)\](.+?)\[\/img\]#i", "urlth_image", $story );
$story = preg_replace_callback( "#\[thumb=(.+?)\](.+?)\[\/thumb\]#i", "urlth_image", $story );
			}

return $story;
	}

function urlth_image($matches=array()) {
global $config_rss,$config,$dimages,$db,$title;

		if (count($matches) == 3 )list ($str, $align, $url) = $matches;
		else list ($str, $url) = $matches;

if($config_rss['create_images'] != '0' and trim($config_rss['maxWidth'])!= '' and $config_rss['create_images_thumb'] == 'yes' and reset_url($url) == reset_url($config['http_home_url'])){
$url = str_replace($config['http_home_url'], '',$url);
$image_news = basename ($url);
$img_orig = str_replace($image_news, '',str_replace('posts/','posts/news_thumb/',$url));
if (!is_dir(ROOT_DIR.'/uploads/posts/news_thumb/')) {
@mkdir(ROOT_DIR.'/uploads/posts/news_thumb/',0777);
chmod_pap(ROOT_DIR.'/uploads/posts/news_thumb/');
}
if (!is_dir(ROOT_DIR.'/'.$img_orig)) {
@mkdir(ROOT_DIR.'/'.$img_orig,0777);
chmod_pap(ROOT_DIR.'/'.$img_orig);
}

if (@file_exists(ROOT_DIR.'/'.$img_orig .$image_news))$image_news =mt_rand(10,99).$image_news;
require_once ENGINE_DIR .'/inc/plugins/thumb.class.php';
$thumb = new rss_thumbnail (ROOT_DIR.'/' .$url);
if ($thumb->size_auto($config_rss['maxWidth'],$config_rss['upload_t_size']))
{
$thumb->jpeg_quality ($config['jpeg_quality']);
$thumb->save (ROOT_DIR.'/'.$img_orig .$image_news);
chmod_file( ROOT_DIR.'/'.$img_orig .$image_news);
unset ($thumb);
}else{return $str;}
if (@file_exists(ROOT_DIR.'/'.$img_orig .$image_news)){
$dimages .= $db->safesql ('|||'.str_replace('uploads/posts/','',$img_orig .$folder_prefix.'/'.$image_news));
$dimages = trim($dimages , '|||');
if( $align != '' )return '[img='.$align.']'.$config['http_home_url'].$img_orig .$image_news.'[/img]';
return '[img]'.$config['http_home_url'].$img_orig .$image_news.'[/img]';
}else{return $str;}
}else{
			$alt = "alt='" . $title . "' title='" . $title . "' ";
			$img_info = @getimagesize( $url );
			if (reset_url($url) == reset_url($config['http_home_url']))$url = str_replace($config['http_home_url'], '/',$url);

				if( $img_info[0] > $config_rss['maxWidth'] or $img_info[1] > $config_rss['maxWidth']) {
				if ($config_rss['upload_t_size'] == '1' and $img_info[0] > $config_rss['maxWidth']){
					$infos = "width=\"{$config_rss['maxWidth']}\"";
				}elseif($config_rss['upload_t_size'] == '2' and $img_info[1] > $config_rss['maxWidth']){
					$infos = "height=\"{$config_rss['maxWidth']}\"";
				}elseif($config_rss['upload_t_size'] == '0' ){
					if ($img_info[0] > $img_info[1]){
					$infos = "width=\"{$config_rss['maxWidth']}\"";
					}else{
					$infos = "height=\"{$config_rss['maxWidth']}\"";
					}
				}else{return $str;}

if( $align == '' ) return "<!--dle_image_begin:{$url}--><a href=\"{$url}\" onclick=\"return hs.expand(this)\" ><img src=\"$url\" $infos {$alt} /></a><!--dle_image_end-->";
else return "<!--dle_image_begin:{$url}--><a href=\"{$url}\" onclick=\"return hs.expand(this)\" ><img align=\"$align\" src=\"$url\" $infos {$alt} /></a><!--dle_image_end-->";
}

		return $str;
}
}

	function add_data ($text)
	{
$month['January'] = "янв";
$month['February'] = "фев";
$month['March'] = "мар";
$month['April'] = "апр";
$month['May'] = "ма";
$month['June'] = "июн";
$month['July'] = "июл";
$month['August'] = "авг";
$month['September'] = "сен";
$month['October'] = "окт";
$month['November'] = "ноя";
$month['December'] = "дек";
$month['Today'] = "сегодн";
$month['Yesterday'] = "вчер";
$month['Tomorrow'] = "завтр";
$month['minutes'] = "мин";
$month['ago'] = "наза";
$text = strtr( $text, 'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ', 'йцукенгшщзхъфывапролджэячсмитьбюё' );
foreach ($month as $key=>$value){
$text = preg_replace( "#(".$value."[а-я]*)#i",$key , $text );
}
	return $text;
	}

function parse_date($d)
{

if (($pd = strtotime(add_data (rss_strip ($d)))) === false) {
  $pd=preg_match("#(\S+)[.,\/\- ]+(\S+)[.,\/\- ]+(\S+)#i",str_replace("&nbsp;", " ",$d),$matches);
var_export($matches);
  if($pd)
  {
$month[1] = "янв";
$month[2] = "фев";
$month[3] = "мар";
$month[4] = "апр";
$month[5] = "ма";
$month[6] = "июн";
$month[7] = "июл";
$month[8] = "авг";
$month[9] = "сен";
$month[10] = "окт";
$month[11] = "ноя";
$month[12] = "дек";
foreach ($month as $key=>$value){
if (preg_match('#'.$value.'#i',$matches[2]))$matches[2]=$key;
}

    if(strlen($matches[1]) > strlen($matches[3])) return mktime(0,0,0,$matches[2],$matches[3],$matches[1]);
    if(count($matches)>3 && $matches[3]!=0)
       $pd=mktime(0,0,0,$matches[2],$matches[1],$matches[3]);
    else
      $pd=mktime(0,0,0,$matches[2],$matches[1]);
  }

}

  return $pd;
}


function min_delete($str, $min_image) {

preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$str,$preg_array);
if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $image_url)
{
$imageSizeInfo = @getimagesize($image_url);
if (($imageSizeInfo[0] < $min_image or $imageSizeInfo[1] < $min_image) and $imageSizeInfo[0] !=0 and $imageSizeInfo[1] !=0) {

$str = str_replace ("[img=left]".$image_url."[/img]","",$str);
$str = str_replace ("[img=right]".$image_url."[/img]","",$str);
$str = str_replace ("[img]".$image_url."[/img]","",$str);

$str = preg_replace ("#\[center\][\n\r\t ]+\[\/center\]#is", '', $str);
$str = str_replace ("[center][/center]", '', $str);

}
}
}

return $str;

}


?>