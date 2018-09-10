<?php
$proxy = $dop_nast[2];
if(preg_match("#{data(.*?)}#is",$meta_title,$data_title)){
$data_title = str_replace("=", "", $data_title[1]);
$data_title = empty($data_title) ? date( 'Y.m.d' ,time()) : date( $data_title,time());
$meta_title = preg_replace("#{data(.*?)}#is", $data_title, $meta_title);
}

$xfte = array();
$xfte = explode ('|||', $channel_info['xfields_template']);
foreach ($xfte as $val)
{
	$key = explode ('==', $val);
	$xfields_basa[$key[0]] = $key;
}

$xfields = '';
$filecontents = array();




if( ! empty( $fieldvalue )) {

foreach ( $fieldvalue as $xfielddataname =>$xfielddatavalue ) {
if( $xfielddatavalue == '' or $xfielddataname == 'content_story' or $xfielddataname == 'content0_story') {
continue 1;
}

$xfields_data = $xfields_basa[$xfielddataname];
$xfields_im = false;
if ($xfields_data[4] == 1)$xfields_im = true;


$xfielddatavalue = stripslashes($xfielddatavalue);
if ($xfields_im )
{
if ($serv == '0' and $xfields_im)$servx = 'serv';
	else $servx = $serv;

$xfields_prop = $xfields_loads[$xfielddataname];
$full_images = array();
$di_control = new image_controller ();
$di_control->post = '';
if ($xfields_im == true)$xfielddatavalue='[img]'.$xfielddatavalue.'[/img]';
$di_control->short_story = $xfielddatavalue;
$di_control->proxy = $dop_nast[2];
$di_control->prob = (intval($dnast[44]) == 0 ? '1': intval($dnast[44])) ;
$di_control->dubl =$dop_nast[15];
if ($dates[0] != ''and e_str($dates[0]) != 10){
$di_control->post = '/'.$dates[0] ;
}
if (intval($key[7]) != 0)$di_control->post .= '/th_post';
if ($dates[1] == 1 or $config_rss['alt_name'] == 'yes')$di_control->dim_week = $alt_name;
$di_control->dim_date =$dates[2];
$di_control->dim_sait =$dates[3];
$di_control->wat_h =$dnast[15];
if (intval($key[7]) != 0)$di_control->max_up_side = $key[7];
else $di_control->max_up_side = $config['max_up_side'];
if (intval($dnast[37]) != 0)$di_control->max_image = $dnast[37];
else $di_control->max_image = $config['max_image'];
$di_control->min_image = $dnast[38];
if ( count($category) and $dates[4] == 1) $di_control_cat = $db->super_query ('SELECT alt_name FROM '.PREFIX ."_category WHERE id ='".$category[0]."'");
$di_control->dim_cat = 1;
$di_control->cat = $xfielddataname.$di_control_cat ['alt_name'];
if ($channel_info['allow_watermark'] == 1)
{
$di_control->allow_watermark = true;
if ($dop_nast[0] == 1)
{
if (trim($dnast[23]) != '')$config_rss ['watermark_image_light']=$dnast[23];
if (trim($dnast[24]) != '')$config_rss['watermark_image_dark']=$dnast[24];
$di_control->watermark_image_light = ROOT_DIR .$config_rss ['watermark_image_light'];
$di_control->watermark_image_dark = ROOT_DIR .$config_rss['watermark_image_dark'];
}
}
$di_control->x = $dop_nast[3];
$di_control->y = $dop_nast[4];
$di_control->margin = $dop_nast[12];
if ($db_num_rows >0 and $rewrite == 1){
$di_control->rewrite = $rewrite;
}
if ($dop_nast[11] == 1)$di_control->shs = true;
$db->close;
$pro = $di_control->process($servx);
if (count($pro) != 0) {
$xdoe[] = implode('<br />',$pro);
}
if (count ($di_control->upload_images) != 0)
{
$folder_prefix = trim($di_control->post.$di_control->pap_data,'/');
$dim = '|||'.$folder_prefix.'/';
$dimage = implode ($dim,$di_control->upload_images);
$dimages = $db->safesql ($folder_prefix.'/'.$dimage);
}
$xfielddatavalue = $di_control->short_story;
if (count($di_control->upload_image ) != 0 and intval($key[7]) == 0){
$short_story=strtr ($short_story,$di_control->upload_image);
$full_story=strtr ($full_story,$di_control->upload_image);
}
if ($xfields_im == true){
$xfielddatavalue=str_replace( '[thumb]','',$xfielddatavalue );
$xfielddatavalue=str_replace( '[/thumb]','',$xfielddatavalue );
$xfielddatavalue=str_replace( '[img]','',$xfielddatavalue );
$xfielddatavalue=str_replace( '[/img]','',$xfielddatavalue );
}
}


$config_code_bb = explode (',',$config_rss['sin_dop'] );
if (in_array ($xfielddataname ,$config_code_bb) and @file_exists ($rss_plugins.'sinonims.php'))
{
include_once($rss_plugins.'sinonims.php');
preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#i",$xfielddatavalue,$nosinonims);
foreach ($nosinonims[1] as $key =>$value){
$noss['nosinonims_'.$key] = $value;
}
if (count($noss) != '')$xfielddatavalue=strtr ($xfielddatavalue,array_flip($noss));
if (preg_match('/\[sin\]/',$xfielddatavalue)){
$xfielddatavalue = preg_replace_callback ("#\[sin\](.+?)\[\/sin\]#i","sinonims_call",$xfielddatavalue);
}else{$xfielddatavalue = sinonims ($xfielddatavalue);}
if (count($noss) != '')$xfielddatavalue=strtr ($xfielddatavalue,$noss);
}

if ($xfields_im != true)$xfielddatavalue = str_replace( "\r\n",'<br>',$xfielddatavalue );
$xfielddataname = $db->safesql( $xfielddataname );
$xfielddataname = str_replace( '|','&#124;',$xfielddataname );
$xfielddataname = str_replace( "\r\n",'__NEWL__',$xfielddataname );
$xfielddatavalue = str_replace( '|','&#124;',$xfielddatavalue );
$xfielddatavalue = str_replace( "\r\n",'__NEWL__',$xfielddatavalue );
$filecontents[] = "$xfielddataname|$xfielddatavalue";
}
if (count($filecontents) != 0) $xfields = implode( '||',$filecontents );
}
else
{
$xfields = '';
}

$xfields = $filecontents = trim($xfields,'||');

$row = '';
$date_time = strtotime ($thistime);
if ($db_num_rows > 1 and $rewrite == 1 and $news_id == ''){
$word = addcslashes(stripslashes($full_news_link),'"[]!-.#?*%\\()|/');
while ( $rew = $db->get_row($sql_Title) ) {
if ($dop_sort[12] == 2 )$xfi=true;
else $xfi=false;
if(preg_match("#".$word."#i",$rew['xfields']) or $xfi or trim($rew['xfields']) == ''){
if (parse_date($thistime) > parse_date ($rew['date']) or $dnast[17] == 0){
$news_id = $row = $rew['id'];
$author = $rew['autor'];
}else{$rewrite = 0;}
break;
}
}
if ($rewrite == 0)return;
}elseif ($db_num_rows == 1 and $rewrite == 1){
while ( $rew = $db->get_row($sql_Title) ) {
$news_id = $row = $rew['id'];
$author = $rew['autor'];
}
}

$full_story .= '[xfields]'.$xfields.'[/xfields]';


if ($db_num_rows >0 and $rewrite == 1 and $news_id == '')return;
if ($serv != '0'or $dop_nast[11] == 1)
{
$di_control = new image_controller ();
$di_control->post = '';
$di_control->short_story = $short_story;
$di_control->full_story = $full_story;
$di_control->proxy = $dop_nast[2];
$di_control->prob = (intval($dnast[44]) == 0 ? '1': intval($dnast[44])) ;
$di_control->dubl =$dop_nast[15];
if ($dates[0] != ''and e_str($dates[0]) != 10){$di_control->post = '/'.$dates[0] ;}
if ($dates[1] == 1 or $config_rss['alt_name'] == 'yes')$di_control->dim_week = $alt_name;
$di_control->dim_date =$dates[2];
$di_control->dim_sait =$dates[3];
$di_control->dim_cat =$dates[4];
$di_control->wat_h =$dnast[15];
if (intval($dnast[37]) != 0)$di_control->max_image = $dnast[37];
else $di_control->max_image = $config['max_image'];
$di_control->max_up_side = $config['max_up_side'];
$di_control->min_image = $dnast[38];
if ( count($category)) $di_control_cat = $db->super_query ('SELECT alt_name FROM '.PREFIX ."_category WHERE id ='".$category[0]."'");
$di_control->cat = $di_control_cat ['alt_name'];
if ($channel_info['allow_watermark'] == 1)
{
$di_control->allow_watermark = true;
if ($dop_nast[0] == 1)
{
if (trim($dnast[23]) != '')$config_rss ['watermark_image_light']=$dnast[23];
if (trim($dnast[24]) != '')$config_rss['watermark_image_dark']=$dnast[24];
$di_control->watermark_image_light = ROOT_DIR .$config_rss ['watermark_image_light'];
$di_control->watermark_image_dark = ROOT_DIR .$config_rss['watermark_image_dark'];
}
}
$di_control->x = $dop_nast[3];
$di_control->y = $dop_nast[4];
$di_control->margin = $dop_nast[12];
if ($db_num_rows >0 and $rewrite == 1){
$di_control->rewrite = $rewrite;
}
if ($dop_nast[11] == 1)$di_control->shs = true;
$db->close;
$pro = $di_control->process($serv);
if (count($pro) != 0) {
$xdoe[] = implode('<br />',$pro);
}
$short_story = $di_control->short_story;
$full_story = $di_control->full_story;
if (count ($di_control->upload_images) != 0)
{
$folder_prefix = trim($di_control->post.$di_control->pap_data,'/');
$dim = '|||'.$folder_prefix.'/';
$dimage = implode ($dim,$di_control->upload_images);
$dimages .= '|||'.$db->safesql ($folder_prefix.'/'.$dimage);
}
}
$dimages = explode('|||',$dimages);
$dimages = array_unique ($dimages);
$dimages = implode ('|||',$dimages);
$dimages = trim($dimages ,'|||');
$short_story = add_short ($short_story);
$full_story = add_full ($full_story);




$f_d = array();

$f_u = array();
$down_files1 = array();
$down_files2 = array();
$down_erors1 = array();
$down_erors2 = array();
$s_story = array();
$f_story = array();
if ($rss_files[0] == 1){$f_d['video']['pap'] = $rss_files[1];$f_d['video']['name'] = $rss_files[12].'='.$rss_files[18];}
if ($rss_files[2] == 1){$f_d['rar']['pap'] = $rss_files[3];$f_d['rar']['name'] = $rss_files[13].'='.$rss_files[19];}
if ($rss_files[4] == 1){$f_d['zip']['pap'] = $rss_files[5];$f_d['zip']['name'] = $rss_files[14].'='.$rss_files[20];}
if ($rss_files[6] == 1){$f_d['doc']['pap'] = $rss_files[7];$f_d['doc']['name'] = $rss_files[15].'='.$rss_files[21];}
if ($rss_files[8] == 1){$f_d['txt']['pap'] = $rss_files[9];$f_d['txt']['name'] = $rss_files[16].'='.$rss_files[22];}
if ($rss_files[10] == 1){$f_d['dle']['pap'] = $rss_files[11];$f_d['dle']['name'] = $rss_files[17].'='.$rss_files[23];}
if ($rss_files[26] == 1){$f_d['tor']['pap'] = $rss_files[29];$f_d['tor']['name'] = $rss_files[27].'='.$rss_files[28];}
if (count($f_d) !=0 ){
$file_down = new file_down;
$file_down->short_story = $short_story;
$file_down->full_story = $full_story;
$file_down->alt_name = $alt_name;
$file_down->torrage = $rss_files[30];
$file_down->file_process($f_d);
$short_story = $file_down->short_story;
$full_story = $file_down->full_story;
$down_files1 = $file_down->down_files;
$down_erors1 = $file_down->eror;
}
$s_story = relace_news_don ($short_story,$alt_name,$rss_files[30]);
$f_story = relace_news_don ($full_story,$alt_name,$rss_files[30]);
$short_story = $s_story['story'];
$full_story = $f_story['story'];
$down_files2 = array_merge ($s_story['files'],$f_story['files']);
$down_erors2 = array_merge ($s_story['erors'],$f_story['erors']);
$down_files = array_diff(array_merge ($down_files1,$down_files2),array(''));
$down_erors = array_diff(array_merge ($down_erors1,$down_erors2),array(''));
if (count($down_erors)!=0)$xdoe_files[] = implode('<br />',$down_erors);
echo "
        <div id=\"progressbar\"></div>

        <script>
storyes($di, $n, '$news_title' );
    </script>";
ob_flush();
flush();



if (preg_match('#<script>#i',$dop_sort[5]))
	{
preg_match_all ("#(<script.+?>.+?<\/script>)#is",$full_story, $scrip);
if (count($scrip[0])){
foreach ($scrip[0] as $k_s=>$s_v){
$template = addcslashes(stripslashes($s_v),"[]!-.#?*%+\\()|");
$full_story = preg_replace("#".$template."#is", "[skpipt".$k_s."]",$full_story);
}

}

}

if (@file_exists ($rss_plugins.'sinonims.php') )
{
include_once($rss_plugins.'sinonims.php');
preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#is",$short_story,$nosinonimsshort_story);
foreach ($nosinonimsshort_story[1] as $key =>$value){
$nossshort_story['nosinonims_'.$key] = $value;
}
if (count($nossshort_story) != '')$short_story=strtr ($short_story,array_flip($nossshort_story));
if (preg_match('/\[sin\]/',$short_story)){
$short_story = preg_replace_callback ("#\[sin\](.+?)\[\/sin\]#is","sinonims_call",$short_story);
}else{
if ($dop_sort[3] == 1 and $sinonims_val == 1 and ($dop_sort[19] == 0 or $dop_sort[19] == 1 ))$short_story = sinonims ($short_story);
}
if (count($nossshort_story) != '')$short_story=strtr ($short_story,$nossshort_story);
preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#is",$full_story,$nosinonimsfull_story);
foreach ($nosinonimsfull_story[1] as $key =>$value){
$nossfull_story['nosinonims_'.$key] = $value;
}
if (count($nossfull_story) != '')$full_story=strtr ($full_story,array_flip($nossfull_story));
if (preg_match('/\[sin\]/',$full_story)){
$full_story = preg_replace_callback ("#\[sin\](.+?)\[\/sin\]#is","sinonims_call",$full_story);
}else{
if ($dop_sort[3] == 1 and $sinonims_val == 1 and ($dop_sort[19] == 0 or $dop_sort[19] == 2 )){$full_story = sinonims ($full_story);
}
}
if (count($nossfull_story) != '')$full_story=strtr ($full_story,$nossfull_story);
}


if (sizeof($xdoe) and $dnast[45]){$news_title = '';
					if($dimages != '')
						{
						$sha_mages= explode("|||", $dimages);
							foreach ($sha_mages as $sha_del)@unlink(ROOT_DIR.'/uploads/posts/'.$sha_del);
						}
}

$short_story=strtr($short_story,array('[sin]'=>'','[/sin]'=>'','[nosin]'=>'','[/nosin]'=>'','biggrab '=>''));
$full_story =strtr($full_story,array('[sin]'=>'','[/sin]'=>'','[nosin]'=>'','[/nosin]'=>'','biggrab '=>''));


$news_title = stripslashes($news_title);
$short_story = stripslashes($short_story);
$full_story = stripslashes($full_story);
$news_title = $db->safesql($parse->process($news_title));


if (count($down_files) != 0)
{
if(@file_exists(ENGINE_DIR .'/inc/xbt.php'))preg_match("#version.*['\"](.*)['\"]#i", @file_get_contents(ENGINE_DIR .'/inc/xbt.php'), $ver_xbt);
if(@file_exists(ENGINE_DIR .'/inc/tracker.php'))preg_match("#version.*['\"](.*)['\"]#i", @file_get_contents(ENGINE_DIR .'/inc/tracker.php'), $ver_xbt);
	foreach ($down_files as $name=>$image_name)
	{
$id = '';
$word = addcslashes(stripslashes(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']).'uploads/files/'.$image_name),'"[]!-.#?*%\\()|/');

	if(substr($image_name,-8) == ".torrent" and @file_exists(ENGINE_DIR."/modules/tracker/upload.php")) 
		{
		if ($config_rss['xbt'] == "no")
			{
			$short_story = preg_replace("#\[(url|leech)=".$word."\].*\[\/(url|leech)\]#iUs",'',$short_story);
			$full_story = preg_replace("#\[(url|leech)=".$word."\].*\[\/(url|leech)\]#iUs",'',$full_story);
			$full_story =str_replace ("[url=".stripslashes($word)."]","",$full_story);
			$xfields = preg_replace("#\[(url|leech)=".$word."\].*\[\/(url|leech)\]#iUs",'',$xfields);
			$xfields =str_replace ("[url=".stripslashes($word)."]","",$xfields);
			}


			if (version_compare($ver_xbt[1], '3.0', ">="))
			{
				$db->query( "DELETE FROM " . PREFIX . "_files WHERE name='torrent.deleted'" );
				if ( @file_exists( $rss_plugins.'include/torrent.php') ) 
					{
					$uploaded_filename = $torrent_file = ROOT_DIR.'/uploads/files/'.$image_name;
					if(@filesize($torrent_file) == 0){
					@unlink(ROOT_DIR.'/uploads/files/'.$image_name);
					continue;
					}
				if(is_dir($torrent_file))continue;
				$data = file_get_contents($torrent_file);
				if ($data == false)continue;
				$torrent = new Torrent();
				if ($torrent->load($data) == false)continue;
				$sha_file = $torrent->getHash();
				$sql_sha = $db->super_query("SELECT * FROM ".PREFIX ."_tracker_files_hash WHERE hash='{$sha_file}' LIMIT 1");
				//echo "basa-".$sql_sha['hash']. " == new-" .$sha_file."<br />";
				if ($sql_sha['hash']!='')
					{
					echo "<font color=\"red\"><b>This file is already uploaded to the site! ID: " .$sql_sha['id']."</b></font><br />";
					@unlink(ROOT_DIR.'/uploads/files/'.$image_name);
					if($dimages != '')
						{
						$sha_mages= explode("|||", $dimages);
							foreach ($sha_mages as $sha_del)@unlink(ROOT_DIR.'/uploads/posts/'.$sha_del);
						}
			return 2;
					}
				}
			}
			define('STANDART_UPL', true);
			include ENGINE_DIR."/modules/tracker/upload.php";
		}else {
		$tr_fild = $tr_info = "";
		$onserver = $image_name;
		}
	if (version_compare($ver_xbt[1], '2.6', "<") and empty($id) ){
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
		$db->query( "INSERT INTO ".PREFIX ."_files (news_id, name, onserver, author, date{$tr_fild}) values ('0', '$name', '$onserver', '$author', '$date_time'{$tr_info})");
		$id = $db->insert_id();
	}
	if ($rss_files[25] != '')$name = ':'.$rss_files[25];
	else $name = '';
	$name = str_replace('{zagolovok}',$title,$name);
	if($_GET['x'] == 1){
echo $onserver.'<br>';
echo '<textarea style="width:100%;height:240px;">'.$full_story.'</textarea><br>';
}
		if($rss_files[24] == 1 or $config_rss['xbt'] == "yes")
		{
		$short_story = preg_replace("#\[(url|leech)=".$word."\].*?\[\/(url|leech)\]#is","[attachment=$id$name]",$short_story);
		$full_story =preg_replace ("#\[(url|leech)=".$word."\].*?\[\/(url|leech)\]#is","[attachment=$id$name]",$full_story);
        $full_story =str_replace ("[url=".stripslashes($word)."]","[attachment=$id$name]",$full_story);
		$xfields = preg_replace ("#\[(url|leech)=".$word."\].*?\[\/(url|leech)\]#is","[attachment=$id$name]",$xfields);
        $xfields =str_replace ("[url=".stripslashes($word)."]","[attachment=$id$name]", $xfields);
		}
	}
}


if($dnast[40] == 1 and $dimages != ''){
$rss_archive = 0;
include_once $rss_plugins.'pclzip.php';
	$ar_file = 'uploads/files/'.$alt_name.'_img.zip';
	$ar_mages = 'uploads/posts/'.str_replace("|||", ",uploads/posts/", $dimages );
if (!function_exists('PclZipUtilPathReduction')) {
classCreator::createPclZip();
 }
	$ar_archive = new PclZip($ar_file);
	$ar_list = $ar_archive->create($ar_mages,PCLZIP_OPT_REMOVE_ALL_PATH);
if ($ar_list != 0){
   $db->query( "INSERT INTO ".PREFIX ."_files (news_id, name, onserver, author, date) values ('0', '".basename($ar_file)."', '".basename($ar_file)."', '$author', '$date_time')");
$rss_archive = $db->insert_id();
	@rename (ROOT_DIR.'/'.$ar_file, ROOT_DIR.'/uploads/files/'.$rss_archive.'.zip');
$db->query('UPDATE '.PREFIX."_files set onserver='".$rss_archive.".zip' WHERE id='$rss_archive'");
if($rss_files[24] == 1){
	if ($rss_files[25] != '')$name = ':'.$rss_files[25];
	else $name = '';
$full_story = $full_story."<br /><br />[attachment=$rss_archive$name]";
}else{
	if ($rss_files[25] != '')$name = $rss_files[25];
	else $name = '{zagolovok}';
$full_story = $full_story."<br /><br />[url=/".$ar_file."]".$name."[/url]";
}
$full_story = str_replace('{zagolovok}',$news_title,$full_story);
}
}

preg_match ("#\[xfields\](.+?)\[\/xfields\]#is", $full_story, $xfields_new);

if ($xfields_new[1] != '')
{
$xfields = $xfields_new[1];

$full_story = str_replace($xfields_new[0], "", $full_story);

}
$full_story = preg_replace("#\[xfields\](.*)\[\/xfields\]#is", "", $full_story);
$xfields_dop = explode("||",$xfields);

$xfields = array();
$filecontents = array();
$xf_search_words = array ();
	if( !empty( $xfields_dop ) ) {



		foreach ( $xfields_dop as $value ) {
		list($xfielddataname, $xfielddatavalue) = explode("|", $value);
			if( $xfielddatavalue == "" ) {
				continue 1;
			}

			$xfields_prop = $xfields_loads[$xfielddataname];
			$xfields_data = $xfields_basa[$xfielddataname];
			
			if ($xfields_prop[5] == 0 AND $postedxfields[$xfields_prop[0]] === "" AND $xfields_prop[3] != "select") {
				continue 1;
		
			}

			if ($xfields_prop[3] == "select") {
				$options = explode("\r\n", $xfields_prop[4]);
				$options = explode("|", $options[$xfielddatavalue] );
		        $xfielddatavalue = $options[0];
			}


			if($xfields_prop[3] == "yesorno") {
				$xfielddatavalue = intval($xfielddatavalue);

			} elseif (($xfields_data[4] == 1 OR $xfields_prop[8] == 1 OR $xfields_prop[6] == 1 OR $xfields_prop[3] == "select" OR $xfields_prop[3] == "image" OR $xfields_prop[3] == "file") AND $xfielddatavalue != "" ) {

				$xfielddatavalue = trim( htmlspecialchars(strip_tags( stripslashes($xfielddatavalue) ), ENT_QUOTES, $config['charset'] ));
				$xfielddatavalue = str_ireplace( "{include", "&#123;include", $xfielddatavalue );

				if($xfields_prop[3] == "image"){

					$xfielddatavalue_im = explode ("/uploads/posts/", $xfielddatavalue);
					$xfielddatavalue = end($xfielddatavalue_im);
				}

			} elseif ( $xfielddatavalue != "" ) {

				$xfielddatavalue = $parse->BB_Parse($parse->process($xfielddatavalue), false);

			}

			if ( $xfields_prop[6] AND !empty( $xfielddatavalue ) ) {
				$temp_array = explode( ",", $xfielddatavalue );

				foreach ($temp_array as $value2) {
					$value2 = trim($value2);
					if($value2) $xf_search_words[] = array( $db->safesql($value[0]), $db->safesql($value2) );
				}

			}

			$xfielddataname = str_replace( "|", "&#124;", $xfielddataname );
			$xfielddataname = str_replace( "\r\n", "__NEWL__", $xfielddataname );
			$xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue );
			$xfielddatavalue = str_replace( "\r\n", "__NEWL__", $xfielddatavalue );
			$filecontents[] = "$xfielddataname|$xfielddatavalue";
		}

		if ( count($filecontents) ) $xfields = $db->safesql(implode( "||", $filecontents )); else $xfields = '';

	} else $xfields = '';

if ($channel_info['allow_more'] == 1 or $dop_sort[12] == 1  or $dop_sort[12] == 3)
{
if ($dnast[31] == 1)$xfields .='||source_leech|'.$db->safesql( $parse->BB_Parse( $parse->process( '[leech='.$full_news_link.']'.$channel_info['title'].'[/leech]'),false ) );
$xfields .= '||source_name|'.$db->safesql($channel_info['title']) .'||source_link|'.$full_news_link;
}
$xfields = trim($xfields,'||');




$key_wordss = '';
$descrs = '';


if ($key_wordss == ''or $descrs == ''){
$metatags_short = create_metategs ($short_story);
$metatags_full = create_metategs ($full_story);
}

if($keywords == '')
	{

if ($dop_sort[7] != 4 and $key_wordss == ''){
if ($dop_sort[7] == 1){
$key_wordss = trim($metatags_short['keywords'],' ,');
}elseif ($dop_sort[7] == 2){
$key_wordss = trim($metatags_full['keywords'],' ,');
}elseif ($dop_sort[7] == 3){
if ($metatags_full['keywords'] >= $metatags_short['keywords']){
$key_wordss = trim($metatags_full['keywords'],' ,');
}else{
$key_wordss = trim($metatags_short['keywords'],' ,');
}
}else{
$key_wordss = trim($metatags_short['keywords'].', '.$metatags_full['keywords'],' ,');
}
}
$meta_words = '';
$words_array = str_replace('{zagolovok}',$news_title,$channel_info['key_words']);
$words_array = explode("\n", $words_array);
foreach($words_array as $words_a){
$words_a = explode("|", $words_a);
$rand_words = array_rand($words_a);
$meta_words .= $words_a[$rand_words].',';
}
$meta_words = trim($meta_words," ,");
$key_words = trim($meta_words.', '.$key_wordss," ,");
$keywords = substr( $key_words ,0,190 +strpos(substr( $key_words ,190),','));
	}

if($descr == '')
	{
if ($dop_sort[10] != 4 and $descrs == ''){
if ($dop_sort[10] == 1){
$descrs = trim($metatags_short['description'],' ,');
}elseif ($dop_sort[10] == 2){
$descrs = trim($metatags_full['description'],' ,');
}elseif ($dop_sort[10] == 3){
if ($metatags_full['description'] >= $metatags_short['description']){
$descrs = trim($metatags_full['description'],' ,');
}else{
$descrs = trim($metatags_short['description'],' ,');
}
}else{
$descrs = trim($metatags_short['description'].', '.$metatags_full['description'],' ,');
}
}

$meta_descr = '';
$descr_array = str_replace('{zagolovok}',$news_title,$channel_info['meta_descr']);
$descr_array = explode("\n", $descr_array);
foreach($descr_array as $descr_a){
$descr_a = explode("|", $descr_a);
$rand_keys = array_rand($descr_a);
$meta_descr .= $descr_a[$rand_keys].',';
}
$meta_descr = trim($meta_descr ," ,");
$descr = $meta_descr.', '.$descrs ;
$descr = trim($descr ," ,");
$descr = substr( $descr ,0,190 +strpos(substr( $descr ,190),' '));
	}






if (trim($full_story) == '' and $dop_sort[17] == 0 and intval($dop_sort[20]) == 0)return;

	if($_GET['x'] == 1){
echo stripslashes($word).'<br>';
echo '<textarea style="width:100%;height:240px;">'.$full_story.'</textarea><br>';
}

preg_match_all ("#<iframe(.+?)</iframe>#is",$short_story,$iframeshort_story);
foreach ($iframeshort_story[0] as $key =>$value){$noshort_iframe['ifme_'.$key] = $value;}
if (count($noshort_iframe) != '')$short_story=strtr ($short_story,array_flip($noshort_iframe));
preg_match_all ("#<iframe(.+?)(.*?)</iframe>#is",$full_story,$iframefull_story);
foreach ($iframefull_story[0] as $key =>$value){$nossfull_iframe['ifme_'.$key] = $value;}
if (count($nossfull_iframe) != '')$full_story=strtr ($full_story,array_flip($nossfull_iframe));

$key_iframe = array_search('iframe',$parse->tagBlacklist);
unset ($parse->tagBlacklist[$key_iframe]);
$key_script = array_search('script',$parse->tagBlacklist);
unset ($parse->tagBlacklist[$key_script]);

$short_story = unhtmlentities($short_story );
$full_story = unhtmlentities($full_story );
if (($config_rss['create_images'] == 1 or $config_rss['create_images'] == 3) and intval( $config_rss['maxWidth'] ) !='0'){
$short_story = $parse->BB_Parse(create_images($parse->process($short_story) ,$news_title) ,false);
}else{
$short_story = $parse->BB_Parse($parse->process($short_story) ,false);
}
if (($config_rss['create_images'] == 2 or $config_rss['create_images'] == 3) and intval( $config_rss['maxWidth'] ) !='0'){
$full_story = $parse->BB_Parse(create_images($parse->process($full_story) ,$news_title) ,false);
}else{
$full_story = $parse->BB_Parse($parse->process($full_story) ,false);
}
$news_read = rand(intval($config_rss['rate_start']),intval($config_rss['rate_finish']));
if($allow_rate == 1){$vote_num = rand(0,$news_read);
$rating = rand($vote_num*(intval($config_rss['rate_bal'])-2),$vote_num*intval($config_rss['rate_bal']));
}
$rating = intval ($rating);
$vote_num = intval ($vote_num);
if (count($noshort_iframe))$short_story=strtr ($short_story,$noshort_iframe);
if (count($nossfull_iframe))$full_story=strtr ($full_story,$nossfull_iframe);
$short_story = str_replace ('%20',' ',str_replace ('&#111;','o',$short_story ));
$full_story = str_replace ('%20',' ',str_replace ('&#111;','o',$full_story ));
$safet = $parse->decodeBBCodes($_POST['title']);
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if(intval($dnast[43])==1)$alt_name='';
if (count($scrip[0])){
foreach ($scrip[0] as $k_s=>$s_v){
$full_story = preg_replace("#\[skpipt".$k_s."\]#is", $s_v,$full_story);
}
}

$full_story = preg_replace_callback ("#<script(.+?)>(.+?)<\/script>#is", "script_br", $full_story);
$short_story = $db->safesql($short_story);
$full_story = $db->safesql($full_story);


if ($db_num_rows >0 and $rewrite == 1){
if ($config['version_id'] >='7.2')$tes = ", tags='".$db->safesql($tegs)."'";
if ($config['version_id'] >'8.0')$fgs = ", metatitle='$meta_title', symbol='$catalog_url'";
if ($dnast[29] == 1)$shfu_re = "short_story='$short_story'";
elseif ($dnast[29] == 2)$shfu_re = "full_story='$full_story'";
else $shfu_re = "short_story='$short_story', full_story='$full_story'";
$result = $db->query( 'UPDATE '.PREFIX ."_post set date='$thistime', title='$news_title', $shfu_re , descr='$descr', keywords='$keywords', category='$category_list', alt_name='$alt_name', allow_comm='$allow_comm', approve='$approve', allow_main='$allow_main',  xfields='$xfields' $tes $fgs WHERE id='$news_id'");
$db->query ('UPDATE '.PREFIX ."_users SET lastdate = '$date_time' WHERE name ='$author'");
$db->query('UPDATE '.PREFIX ."_images SET images='$dimages', date='$date_time' WHERE news_id ='$news_id'");
}else{
if ($config['version_id'] >='7.2'){$te =", '".$db->safesql($tegs)."'";$tes = ', tags';}
if ($config['version_id'] >'8.0'){$fgrs = ", '$meta_title', '$catalog_url'";$fgs = ', metatitle, symbol';}
if ($config['version_id'] <'9.6'){
$db->query ( 'INSERT INTO '.PREFIX ."_post (autor, category, date, title, alt_name, short_story, full_story, xfields, allow_main, approve, allow_comm, allow_rate, allow_br, rating, vote_num, news_read, fixed, descr, keywords $tes $fgs) VALUES ('$author', '$category_list', '$thistime', '$news_title', '$alt_name', '$short_story', '$full_story', '$xfields', '$allow_main', '$approve', '$allow_comm', '$allow_rate', '1', '$rating', '$vote_num', '$news_read', '0', '$descr', '$keywords' $te $fgrs)");
$news_id = $row = $db->insert_id();
}else{
$db->query ( 'INSERT INTO '.PREFIX ."_post (autor, category, date, title, alt_name, short_story, full_story, xfields, allow_main, approve, allow_comm, allow_br, fixed, descr, keywords $tes $fgs) VALUES ('$author', '$category_list', '$thistime', '$news_title', '$alt_name', '$short_story', '$full_story', '$xfields', '$allow_main', '$approve', '$allow_comm', '1', '0', '$descr', '$keywords' $te $fgrs)");
$news_id = $row = $db->insert_id();
$author_id = $db->super_query ('SELECT * FROM '.PREFIX ."_users WHERE name ='$author'");
$db->query( "INSERT INTO " . PREFIX . "_post_extras (news_id, allow_rate, news_read, vote_num, rating, user_id) VALUES('$news_id', '$allow_rate', '$news_read', '$vote_num', '$rating', '{$author_id['user_id']}')" );
}
if( $approve == '1' and $crosspost_val == 1 and @file_exists(ENGINE_DIR .'/inc/crosspost.addnews.php')) {
$_POST['crosspost_approve'] = true;
include ENGINE_DIR .'/inc/crosspost.addnews.php';
}
if($approve == '1' and $twitter_val == 1 and @file_exists(ENGINE_DIR.'/modules/twitter.php')) include ENGINE_DIR.'/modules/twitter.php';
$db->query ('UPDATE '.PREFIX ."_users SET news_num = news_num + 1, lastdate = '$date_time' WHERE name ='$author'");
}
$xdoe_channel[] = $news_id;
$nn++;
$safeT[] = '<b>'.$nn.'</b> . <b style="color:green;">'.$tit.'</b> &#x25ba; <a class="list" href="index.php?newsid='.$news_id.'" target="_blank"><b style="color:blue;">'.$safet.'</b></a> '.$ping_msg ;
if ($tegs != ''and $db_num_rows == 0) {
$tags = array();
$tegs = explode (',',$tegs);
$res = $db->super_query("SELECT * FROM ".PREFIX."_tags LIMIT 1");
foreach ($tegs as $value) {
if (isset($res['alt_tag'])){
if (trim($value) != '') $tags[] = "('$news_id', '".$db->safesql(trim($value))."', '".$db->safesql(trim(totranslit($value)))."')";
}else{
if (trim($value) != '') $tags[] = "('$news_id', '".$db->safesql(trim($value))."')";
}
}
$tags = implode(', ',$tags);
if (isset($res['alt_tag']))$db->query('INSERT INTO '.PREFIX.'_tags (news_id, tag, alt_tag) VALUES '.$tags);
else $db->query('INSERT INTO '.PREFIX.'_tags (news_id, tag) VALUES '.$tags);
}
if (trim ($dimages) != ''and ($db_num_rows == 0 or $dop_sort[12] == 0))
{
$db->query('INSERT INTO '.PREFIX ."_images (images, news_id, author, date) VALUES   ('$dimages', '$news_id', '$author', '$date_time')");
}
if(count($xf_search_words) and $config['version_id'] > '10.6'){
		
			$temp_array = array();
		
		foreach ( $xf_search_words as $value ) {
			
			$temp_array[] = "('" . $news_id . "', '" . $value[0] . "', '" . $value[1] . "')";
		}
		
		$xf_search_words = implode( ", ", $temp_array );
	
$db->query( "INSERT INTO " . PREFIX . "_xfsearch (news_id, tagname, tagvalue) VALUES " . $xf_search_words );
}
$db->close;
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
if (count($down_files) != 0 or $rss_archive != 0)
{
$db->query('UPDATE '.PREFIX."_files set news_id = '$news_id', author = '$author' WHERE news_id='0'");
}
if (intval($dnast[10]) != 0){
if ($expires != ''){$datede = strtotime ($expires);}else
{$datede = strtotime ($thistime) +$dnast[10] * 86400;}
$db->query( 'INSERT INTO '.PREFIX ."_post_log (news_id, expires, action) VALUES('$news_id', '$datede', '{$dnast[11]}')");
}
#****** SocialPosting *** start ******#
if($twitter_val == 1 and @file_exists(ENGINE_DIR.'/modules/socialposting/posting.php')){
	$config_posting['cron_posting'] ="off";
	include ENGINE_DIR.'/modules/socialposting/posting.php';}
#****** TwitterPoster *** start  ******#

?>