<?php
$news_full_link = $full_news_link = $news_link;
$frag_ar = $zhv_code = array();
$frag_ar = explode("===", $sart_cat[2]);

if(count($frag_ar)){
	$kl_z = 0;
foreach ($frag_ar as $kl_zas=>$frag_vl){
$key_fr = '';

if ($charik != strtolower($config['charset']) and trim ($frag_vl) != '' and trim ($charik) != '') {
	$frag_vl = convert(strtolower($config['charset']),$charik,$frag_vl);
	}
if (preg_match("#\[GOLINK=(.*)\]#",$frag_vl,$outs_f)){
	
$frag_vl = str_replace($outs_f[0], '{get}', $frag_vl);
$key_fr = full_path_build(get_full_news ($full,$frag_vl),$URL['host'],$URL['path']);

$linkf = replace_url($news_li(trim(rss_strip(full_path_build($key_fr,$URL['host'],$URL['path'])))));
$full_fr = get_full ($linkf['scheme'],$linkf['host'],$linkf['path'],$linkf['query'],$cookies,$dop_nast[2],$dop_sort[8],$dop_sort[21]);
$key_fr = get_full_news ($full_fr,$outs_f[1]);

}else{
$key_fr = get_full_news ($full,$frag_vl);
}
$kl_z++;
if (trim ($key_fr) != '')
{
if ($charik != strtolower($config['charset']) and trim ($key_fr) != '' and trim ($charik) != '') {
	$key_fr = convert($charik,strtolower($config['charset']),$key_fr);
	}
if (trim($channel_info['start']) != ''){
$key_fr = relace_news ($key_fr,$channel_info['start'],$row_finish);
}
if($dop_sort[9] != 0) {
$key_fr = trim(preg_replace('/[\r\n\t]+/',' ',$key_fr));
$key_fr = trim(preg_replace("#(<br \/>|<br>)\s+(\S)#",'\\1\\2',$key_fr));
$key_fr = trim(preg_replace('/\s+/',' ',$key_fr));
}
$key_fr = rss_strip($key_fr);
$key_fr = addcslashes(stripslashes($key_fr),'#');
$key_fr = parse_Thumb ($key_fr);
$key_fr = parse_rss ($key_fr);
$key_fr = $parse->decodeBBCodes ($key_fr,false);
$key_fr = rss_strip ($key_fr);
$key_fr = strip_tags_smart ($key_fr,'<object><embed><param>'.$dop_sort[5]);
if($dop_sort[13] == 1) {$key_fr = translate_google ($key_fr,$dop_sort[14] ,$dop_sort[15] );}
if($dop_sort[13] == 1 and $dop_sort[18] != '') {$key_fr = rss_strip (translate_google ($key_fr,$dop_sort[15] ,$dop_sort[18] ));}
$key_fr = preg_replace('#&quot;#','"',$key_fr);
$zhv_code[$kl_z] = strip_br($key_fr);
}
}
}

if (trim ($full_story) != '')
{
$full_story = rss_strip($full_story);
$full_story = addcslashes(stripslashes($full_story),"#");
$full_story = parse_Thumb ($full_story);
$full_story = parse_rss ($full_story);
$full_story = $parse->decodeBBCodes ($full_story,false);
$full_story = rss_strip ($full_story);
$full_story = strip_tags_smart ($full_story,'<object><embed><param>'.$dop_sort[5]);
$full_story = preg_replace('#&quot;#','"',$full_story);
$full_story = strip_br($full_story);
}
else
{
$full_story = '';
$full_allow_news = true;
}
$short_story = parse_Thumb ($short_story);
$short_story = parse_rss ($short_story);
$short_story = unhtmlentities( $short_story );
$short_story = $parse->decodeBBCodes ($short_story,false);
$short_story = rss_strip ($short_story);
$short_story = strip_tags_smart ($short_story,'<object><embed><param>'.$dop_sort[5]);
$short_story  = preg_replace('#&quot;#','"',$short_story );
$short_story  = strip_br($short_story );
$short_stor =  $short_story;
if ($dop_sort[2] == 1) $full_story = $short_story.'<br /><br />'.$full_story;
if ($dop_sort[0] != 0)$short_story = '';
$full_story = strip_br($full_story);
$news_title = str_replace( '  ',' ',$news_title );
if($dop_sort[11] != 0) {
if (@file_exists ($rss_plugins.'sinonims.php') )
{
include_once($rss_plugins.'sinonims.php');
$news_title = sinonims ($news_title);
}
}
$full_story = parse_host ($full_story,$link['host'],$link['path']);
$short_story = parse_host ($short_story,$link['host'],$link['path']);
$short_story = create_URL ($short_story,$link['host']);
$full_story = create_URL ($full_story,$link['host']);


if ($dnast[7] == 1 or $dnast[7] == 3) $short_story = url_img_($short_story );
if ($dnast[7] == 2 or $dnast[7] == 3) $full_story = url_img_($full_story );
if ($dop_nast[1] == 1){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$full_story );
}



if($dop_nast[1] == 2){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace_callback( '#\[url=(.+?)\](.+?)\[\/url\]#i',"downs_host", $short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace_callback( '#\[url=(.+?)\](.+?)\[\/url\]#i',"downs_host", $full_story );
}
if($dop_nast[1] == 3){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace_callback( '#\[url=(.+?)\](.+?)\[\/url\]#i',"downs_host", $short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace_callback( '#\[url=(.+?)\](.+?)\[\/url\]#i',"downs_host", $full_story );
}

if ($hide_leech[1] == '1'){
$short_story = replace_hide ($short_story);
$full_story = replace_hide ($full_story);
}

$short_story = replace_leech ($short_story);
$full_story = replace_leech ($full_story);



$short_story = replace_quote ($short_story);
$full_story = replace_quote ($full_story);
if ($dop_nast[10] == 1){
$short_story = trim(preg_replace('/[\r\n\t ]{3,}/','
',$short_story));
$full_story = trim(preg_replace('/[\r\n\t ]{3,}/','
',$full_story));
}
if (intval($dop_nast[22]) != 0){
if ($dop_nast[24] != '')$kones = $dop_nast[24];
else $kones = ' ';
$kol_b='';
if (trim($short_story) != '')$full_stor = $short_story;
else $full_stor = $full_story;
$bb_d = array();
$bb_dd = array();
$dop_kon = strpos(e_sub( $full_stor ,$dop_nast[22]),$kones);
$short_sto = e_sub( $full_stor ,0,$dop_nast[22]);
preg_match_all('#\[(img|thumb).*\].*?\[\/(img|thumb)\]#is',$short_sto,$bb_d);
if (count($bb_d[0]) != 0){
foreach($bb_d[0] as $eh){
$kol_b += e_str($eh);
}
}
preg_match_all('#\[.*?\]#i',$short_sto,$bb_dd);
if (count($bb_dd[0]) != 0){
foreach($bb_dd[0] as $eh){
$kol_b += e_str($eh);
}
}
preg_match_all('#\<.*?\>#i',$short_sto,$bb_dd);
if (count($bb_dd[0]) != 0){
foreach($bb_dd[0] as $eh){
$kol_b += e_str($eh);
}
}


$kol_sim = $dop_nast[22] + $kol_b;
$dop_kon = strpos(e_sub( $full_stor ,$kol_sim),$kones);
$nach = $kol_sim + $dop_kon + 1;

if (intval($kol_sim) != 0) $short_story = e_sub( $full_stor ,0,$nach);
else $short_story = e_sub( $full_stor ,0,strpos(e_sub( $full_stor ,$kol_sim),$kones));
if( e_str($full_stor) == e_str($short_story) and $dop_nast[22] < '999'){
$short_story_a = strip_tags ($db->safesql( $parse->BB_Parse( $full_story, false ) ));
$short_story_a = preg_replace('#\[img.*?\](.+?)\[\/img\]#i', '', $short_story_a);
$short_story_a = preg_replace("#(\[.*?\])#is", '', $short_story_a);

$dop_kon = strpos(e_sub( $short_story_a ,$dop_nast[22]),$kones);
$nach = $dop_nast[22] + $dop_kon + 1;
$short_story .= e_sub( $short_story_a ,0,$nach);
}

$short_story .= '...';
$short_story = preg_replace('#\[\S+\.\.\.#','...',$short_story);
$short_story = preg_replace('#<\S+\.\.\.#','...',$short_story);
$short_story = preg_replace('#\.\s\.\.\.#','...',$short_story);
$short_story = preg_replace('#,\s\.\.\.#','...',$short_story);
$short_story = str_replace('....','...',$short_story);
$short_story = preg_replace('#[\.]{2,}#','',$short_story);
$short_story = close_dangling_tags($short_story.'...');

if (trim($short_story,'., ') == '')$short_story = '';
}
if ($dop_sort[1] == 1 or $dop_sort[0] == 1 or count($str_urls) >'0') {
if ($short_story == ''and $full_story != ''or $dop_sort[1] == 1){
if (!preg_match ('#\[img.+?\]#i',$short_story) and !preg_match ('#\[thumb.+?\]#i',$short_story))$short_story = get_im ($full_story,$dop_sort[22],$dnast[39]).$short_story;
}
}
if ($dop_sort[17] != 0)$full_story = '';
if ($dop_nast[17] == 1 or $dop_nast[17] == 3){
$indeg = get_im ($full_story);
$full_story = str_replace ($indeg,'',$full_story);
$full_story = $indeg.$full_story;
}
if ($dop_nast[17] == 2 or $dop_nast[17] == 3){
$indeg = get_im ($short_story);
$short_story = str_replace ($indeg,'',$short_story);
$short_story = $indeg.$short_story;
}
if(intval($dop_nast[23]) != 0){
$full_story = str_replace('[thumb','[img',$full_story);
$full_story = str_replace('thumb]','img]',$full_story);
preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$full_story,$img_a);
$is = 1;
$num_i=ceil(count($img_a[0])/$dop_nast[23]);
$is_k = 1;
foreach ($img_a[0] as $value)
{
if ($is %$dop_nast[23] == 0){
$full_story = str_replace($value,$value."\n{PAGEBREAK}\n",$full_story);
$is_k++;
}
$is++;
if ($num_i == $is_k)break;
}
}

if(trim($item['player']) != "" and $rss == 2)$full_story = '<iframe src="'.$item['player'].'" width="607" height="360" frameborder="0"></iframe>';

if (trim($channel_info['xfields_template']) != '')
{
$xfields_array = get_xfields (rss_strip($full_story),$short_story,$channel_info['xfields_template'],$xfields_array);
$full_story = $xfields_array['content_story'];
$short_story = $xfields_array['content0_story'];
}
$short_story = replace_align ($short_story,$dnast[0]);
$full_story = replace_align ($full_story,$dnast[1]);

if ($dnast[36] != '' and $dnast[36] != $dnast[1])
	{
$img_poster = get_im ($full_story);
$full_story = str_replace ($img_poster, replace_align ($img_poster,$dnast[36]),$full_story);
$full_story = preg_replace('#\[center\]\[(img|thumb)=(.*?)\[\/(img|thumb)\]\\[/center\]#is','[\\1=\\2[/\\3]',$full_story);
}


if (!((!($channel_info['date_format'] == 0) AND !($channel_info['date_format'] == 1))))
{
$added_time_stamp = time () +($config['date_adjust'] * 60);
$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
if ($channel_info['date_format'] == 1)
{
$interval = mt_rand ($config_rss['interval_start']*60,$config_rss['interval_finish']*60);
$added_time_stamp += $interval;
$dat = $lang_grabber['date_post'].$lang_grabber['date_casual'];
}
}
else
{
if ($rss == 1 or trim($sart_cat[1]) != ''){
if ($channel_info['date_format'] == 2)
{
if(!preg_match('/\d/i',$data_tmp))$data_tmp = time ();
else $data_tmp = parse_date($data_tmp);
if ($data_tmp >time ())$data_tmp = time ();
if ($rss == 0 and trim($data_tmp) != ''){$added_time_stamp = $data_tmp;}
else $added_time_stamp = strtotime ($item['pubDate']);
$dat = $lang_grabber['date_post'].$lang_grabber['date_channel'];
}
}else{$added_time_stamp = time () +($config['date_adjust'] * 60);
$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
}
}
$thistime = date( 'Y-m-d H:i:s',$added_time_stamp);
if (preg_match('/00:00:00/',$thistime))$thistime = str_replace('00:00:00',date('H:i:s',time()),$thistime);
$keywordsd = explode ('===',$channel_info['keywords']);
$keywords = stripslashes ($keywordsd[0]);
if (trim ($keywords) != '')
{
$allow_news = FALSE;
$keywords = explode ('|||',$keywords);
foreach ($keywords as $word)
{
if (trim($word) != ''){
$word = addcslashes(stripslashes($word),'"[]!-.#?*%\\()|/');
if (preg_match ('#'.$word.'#',$short_story) or preg_match ('#'.$word.'#',$full_story) or preg_match ('#'.$word.'#',$news_title))
{
$allow_news = TRUE;
}
}else{$allow_news = TRUE;}
}
}
else
{
$allow_news = TRUE;
}
$stkeywordsd = explode ('===',$channel_info['stkeywords']);
$stkeywords = stripslashes ($stkeywordsd[0]);


if (trim($keywordsd[1]) != '')$short_story = $keywordsd[1].' '.$short_story;
if (trim($keywordsd[2]) != '')$full_story = $keywordsd[2].' '.$full_story;
if (trim($stkeywordsd[1]) != '')$short_story .=' '.$stkeywordsd[1];
if (trim($stkeywordsd[2]) != '')$full_story .=' '.$stkeywordsd[2];


foreach ($zhv_code as $k_zh=>$v_zh){
$short_story= str_replace('{frag'.$k_zh.'}',$v_zh,$short_story);
$full_story= str_replace('{frag'.$k_zh.'}',$v_zh,$full_story);
$news_title = str_replace('{frag'.$k_zh.'}',$v_zh,$news_title);
$short_story= str_replace('{frag}',$v_zh,$short_story);
$full_story= str_replace('{frag}',$v_zh,$full_story);
$news_title = str_replace('{frag}',$v_zh,$news_title);
}
$news_title_out = $parse->decodeBBCodes($news_title);
if (trim ($channel_info['delate']) != '')
{
$row_inser= str_replace('{zagolovok}',$news_title,$channel_info['inser']);
$row_inser= str_replace('{link}',$news_link,$row_inser);
foreach ($zhv_code as $k_zh=>$v_zh){
	$row_inser= str_replace('{frag'.$k_zh.'}',$v_zh,$row_inser);
	$row_inser= str_replace('{frag}',$v_zh,$row_inser);
	}
$short_story = relace_news ($short_story,$channel_info['delate'],$row_inser,1);
$full_story = relace_news ($full_story,$channel_info['delate'],$row_inser,2);
}

$short_story= str_replace('{link}',$news_link,$short_story);
$full_story= str_replace('{link}',$news_link,$full_story);

$short_story= str_replace('{zagolovok}',$news_title,$short_story);
$full_story= str_replace('{zagolovok}',$news_title,$full_story);

$full_story = parse_host ($full_story,$link['host'],$link['path']);
$short_story = parse_host ($short_story,$link['host'],$link['path']);

if (intval($dnast[38]) != 0){
$short_story = min_delete($short_story, $dnast[38]);
$full_story = min_delete($full_story, $dnast[38]);
}



$short_story= trim(stripslashes(strip_br ($short_story))," \r\n\t†");
$full_story= trim(stripslashes(strip_br ($full_story))," \r\n\t†");


if (trim ($stkeywords) != '')
{
$stkeywords = explode ('|||',$stkeywords);
foreach ($stkeywords as $word)
{
if (trim($word) != ''){
$word = addcslashes(stripslashes($word),'"[]!-.#?*%\\()|/');
if (preg_match ('#'.$word.'#',$short_story) or preg_match ('#'.$word.'#',$full_story) or preg_match ('#'.$word.'#',$news_title))
{
$allow_news = FALSE;
}
}
}
}

if ($allow_news)
{

$Autor = explode('=',$channel_info['Autors']);
if (trim($Autor[0]) != '')
{
$input=array ();
$autor = explode ("|||",stripslashes($Autor[0]));
foreach ($autor as $value)
{
$input[] =trim($value);
}
}
else
{if (trim($Autor[1]) == '') $Autor[1] = $config_rss['reg_group'];
if (trim($Autor[1]) == '') $Autor[1] = 1;
$channel_infos = $db->query ("SELECT * FROM ".PREFIX ."_users WHERE user_group IN ({$Autor[1]})");
while ($channel_infon = $db->get_row($channel_infos)) {
$input[] = $channel_infon['name'];
}
}
if ($input != '')$author= $input[array_rand ($input)];



if ($db->num_rows ($sql_result) != 0 and $hide_leech[3] == 1){
$word = addcslashes(stripslashes($news_link),'"[]!-.#?*%\\()|/');
while ($ren = $db->get_row($sql_result)){
if ($dnast[17] != 1){
$xfi=true;
if ($dop_sort[12] == 2)$xfi=false;
if(preg_match("#".$word."#i",$ren['xfields']) or !$xfi){
$rew_story_a =  @html_entity_decode(trim($full_story),ENT_QUOTES ,$config['charset']) ;
//echo'<textarea style="width:100%;height:240px;">'.$rew_story_a.'</textarea>';
$rew_story_a = preg_replace('#\[img.*?\](.+?)\[\/img\]#i', '11', $rew_story_a);
$rew_story_a = preg_replace('#\[thumb.*?\](.+?)\[\/thumb\]#i', '11', $rew_story_a);
$rew_story_a = preg_replace('#\[url.*?\](.+?)\[\/url\]#i', '11', $rew_story_a);
$rew_story_a = preg_replace("#(\[.*?\])#is", '11', $rew_story_a);
$rew_story_a = preg_replace("#(\<.*?\>)#is", '11', $rew_story_a);
$rew_story_a =  str_br($rew_story_a) ;

//echo'<textarea style="width:100%;height:240px;">'.$rew_story_a.'</textarea>';
$rew_story_a = e_str($rew_story_a);


$rew_story_b = $parse->decodeBBCodes ($ren['full_story'],false);
$rew_story_b = @html_entity_decode(trim($rew_story_b),ENT_QUOTES ,$config['charset']) ;
//echo'<textarea style="width:100%;height:240px;">'.$rew_story_b.'</textarea>';
$rew_story_b = preg_replace('#\[img.*?\](.+?)\[\/img\]#i', '11', $rew_story_b);
$rew_story_b = preg_replace('#\[thumb.*?\](.+?)\[\/thumb\]#i', '11', $rew_story_b);
$rew_story_b = preg_replace('#\[url.*?\](.+?)\[\/url\]#i', '11', $rew_story_b);
$rew_story_b = preg_replace("#(\[.*?\])#is", '11', $rew_story_b);
$rew_story_b = preg_replace("#(\<.*?\>)#is", '11', $rew_story_b);
$rew_story_b = str_br($rew_story_b) ;

//echo'<textarea style="width:100%;height:240px;">'.$rew_story_b.'</textarea>';
$rew_story_b = e_str($rew_story_b) ;

//echo $rew_story_a ."-". $rew_story_b;


if ($rew_story_a != $rew_story_b){$allow_news = true;
$news_id = $row = $ren['id'];
$author = $ren['autor'];
}else{$allow_news = false;$full_allow_news=false;}
break;
}
}else{
if ($dnast[30] == 1 and trim($data_tmp) == '')$data_tmp = 'data';
if ($channel_info['date_format'] == 2 and $data_tmp != ''){
if ($dop_sort[12] == 1  or $dop_sort[12] == 3)$xfi=true;
if(preg_match("#".$word."#i",$ren['xfields']) or !$xfi or trim($ren['xfields']) == ''){
$allow_news = false;$full_allow_news=false;
if (parse_date($thistime) >parse_date($ren['date'])){
$allow_news = true;
$news_id = $row = $ren['id'];
$author = $ren['autor'];
}else{$allow_news = false;$full_allow_news=false;}
break;}
}
}
}
}


$category_row = array();
$category = array();
$kateg = array();
$tags_tmps = array();
$tags_tm = '';
$tags_tmp = replace_tags_title ($tags_tmp);
if (is_array($tags_tmp))$tags_tmp = reset($tags_tmp);
$tags_tmpr = $tags_tmp;



if ($dnast[21] == 0) {$tags_tmps = replace_tags_title ($tags_tmp.','.$news_tit,$dnast[20]);
}else{ $tags_tmps = replace_tags ($tags_tmp.','.$full_story.','.$news_tit,$dnast[20]);}
if ($dnast[8] == 0){
$tags_tm = trim(preg_replace('/[\r\n\t]+/','',$tags_tmp));
if (intval($dnast[20]) != 0 and $tags_tm != ''){
$sort = explode(",",$tags_tm);
$strsm = array ();
for ($ir=0;$ir<intval($dnast[20]);$ir++){$strsm[] = $sort[$ir];}
$tags_tm = implode(",",$strsm);
}
}else{
$tags_tm = $tags_tmps[0];
}
$tags_tmp = trim($channel_info['ftags'].','.$tags_tm ,',');
if (trim($channel_info['sart_link'])=='' and $rss == 0)$n_link = get_flink ($tags_tty.$full,$link['host'],$tu_link);
else $n_link = $news_link;
if (trim($channel_info['kategory']) != ''){
foreach (explode ('|||',$channel_info['kategory']) as $value){
$kr = explode ('==',$value);
foreach (explode (',',$kr[0]) as $wnd){
$url_kats = addcslashes(stripslashes(reset_urlk($wnd)),'"[]!-.#?*%\\()|/');
if($dop_sort[16] == 1)$for = $tags_tmpr;
else $for = $n_link;
if (preg_match('#'.$url_kats.'#i',$for)){
foreach (explode (',',$kr[1]) as $key){
$db->close;
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if (trim($key) != '')$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name like '".$db->safesql(trim($key))."%' or alt_name like '".$db->safesql(strtolower(trim($key)))."%' or name like '".$db->safesql(trim($key))."%' or alt_name like '".$db->safesql(trim($key))."%'");
if (trim($sql_cat['id']) != '')
{
$kateg[]=$sql_cat['id'];
}
}
}
}
}
}
if (count($kateg) == 0){
if ($channel_info['thumb_img'] == 1){
$gory = explode (',',$tags_tmps[1].','.$tags[1]);
foreach ($gory as $value) {

$db->close;
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if (trim($value) != '')$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name like '".$db->safesql(trim($value))."%' or alt_name like '".$db->safesql(strtolower(trim($value)))."%' or alt_name like '".$db->safesql(trim($value))."%' or alt_name like '".$db->safesql(trim($value))."%'");
if (trim($sql_cat['id']) != '')
{
$category[]=$sql_cat['id'];
}
}
}
}else{$category =$kateg;}


$categoryes = explode ('=',$channel_info['category']);
$category_row = explode (',',$categoryes[0]);
if (count($category_row) == 1)$category_row = $categoryes[0];
if (count($category) != '0'){
$categories_list = CategoryNewsSelection (array_unique($category),0);
$category_list = implode(',',array_unique($category));
}else{
$categories_list = CategoryNewsSelection ($category_row,0);
$category_list = reset($categoryes);


if ($_GET['cat'] == '1'){
$tags_tmp_alt = totranslit(trim($tags_tmp));

echo $tags_tmp;
$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name='".$db->safesql(trim($tags_tmp))."' or alt_name='".$db->safesql(trim($tags_tmp_alt))."'");
echo '<br />'.$sql_cat['name'];
if (trim($sql_cat['id']) == '')
{
	$db->query( "INSERT INTO " . PREFIX . "_category (parentid, name, alt_name, icon, skin, descr, keywords, news_sort, news_msort, news_number, short_tpl, full_tpl, metatitle, show_sub) values ('0', '$tags_tmp', '$tags_tmp_alt', '', '', '', '', '', '', '0', '', '', '', '0')" );
	@unlink( ENGINE_DIR . '/cache/system/category.php' );
	clear_cache();
$category_list = $db->insert_id();
$categories_list .='<option style="color: black" value="'.$category_list.'" SELECTED>'.$tags_tmp.'</option>';
}else{
	$category_list = $sql_cat['id'];
	$categories_list .='<option style="color: black" value="'.$sql_cat['id'].'" SELECTED>'.$tags_tmp.'</option>';
	}
}

}
$db->close;



if (count($scrip[0])){

foreach ($scrip[0] as $k_s=>$s_v){
	echo $k_s;
$full_story = preg_replace("#\[skpipt".$k_s."\]#is", $s_v,$full_story);
}
}

}

?>