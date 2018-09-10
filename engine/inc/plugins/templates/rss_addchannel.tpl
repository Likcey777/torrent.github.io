


<link rel="stylesheet" type="text/css"  href="engine/skins/grabber/css/box.css" />
<link rel="stylesheet" href="engine/skins/grabber/chosen/chosen.css" />
<script src="engine/skins/grabber/chosen/chosen.jquery.min.js" type="text/javascript"></script>
<script src="engine/skins/grabber/js/box.js" type="text/javascript"></script>
    <script type="text/javascript">
$(document).ready(function(){
    $("select.load_img, .cat_select").chosen({disable_search_threshold: 5, allow_single_deselect:true, no_results_text: "{nosearch}"});
	$('input[type=checkbox]').tzCheckbox({labels:['{opt_sys_yes}','{opt_sys_no}']});
});
    </script>

<style type="text/css">
select.load_img {
width:150px;
}
ul.tabs {
font-weight:bold;
	height: 28px;
	line-height: 25px;
	list-style: none;
	border-bottom: 1px solid #DDD;
	background: #FFF;
}
.tabs li {
	float: left;
	display: inline;
	margin: 0 1px -1px 0;
	padding: 0 13px 1px;
	color: #fff;
	cursor: pointer;
	background: #808080;
	border: 1px solid #E4E4E4;
	border-bottom: 1px solid #808080;
	position: relative;
	border-top-left-radius: 10px; 
	border-top-right-radius: 10px;
}
.tabs li:hover {
	color: black;
	padding: 0 13px 1px;
	background: #EFEFEF;
	border: 1px solid #E4E4E4;

}
.tabs li.current {
	color: black;
	background: #EFEFEF;
	padding: 0 13px 1px;
	border: 1px solid #D4D4D4;
	border-bottom: 1px solid #EFEFEF;
}
.box {
	display: none;
	border: 1px solid #D4D4D4;
  border-width: 0 1px 1px;
	padding: 0 12px;
}
.box.visible {
	display: block;
}

</style>
    <script type="text/javascript">
(function($) {
$(function() {

	$('ul.tabs').each(function(i) {
		var storage = localStorage.getItem('tab'+i);
		if (storage) $(this).find('li').eq(storage).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').hide().eq(storage).show();
	})

	$('ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
		var ulIndex = $('ul.tabs').index($(this).parents('ul.tabs'));
		localStorage.removeItem('tab'+ulIndex);
		localStorage.setItem('tab'+ulIndex, $(this).index());
	})

})
})(jQuery)
    </script>

<div class="section">

	<ul class="tabs">
					<li class="current"> {Options}</a></li>
					<li> {Images}</li>
					<li> {Dop_Options}</li>
					<li> {Visualization}</li>
					<li> {Filter_Authorization}</li>
					<li> {Authors}</li>
					<li> {For_HTML}</li>
					<li> {Replacements}</li>
				</ul>


   <!-- Основные настройки -->
   <div class="box visible">

  <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px" width="260"><b>{name_canal}:</b></td>
   <td style="padding:4px" ><strong><input name="rss_title" id="rss_title" type="text" value="{title}" size="70" /></strong>
    </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px" width="260"><b>{url_canal}</b></td>
    <td style="padding:4px"><input class="load_img" size="70" type="url" name="rss_url" value="{address}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_url_canal}', this, event, '420px')">[?]</a>
    </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{rss_gr}</td>
   <td style="padding:4px">
   <select data-placeholder="{selgroup}" name="rss_priv" class="cat_select">
    {rss-priv}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rss_gr}', this, event, '500px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{chars}:</td>
   <td style="padding:4px"><input type="text"  size="15" name="charset" value="{charsets}">
  <a href="#" class="hintanchor" onMouseover="showhint('{help_chars}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{grabpause}</td>
   <td style="padding:4px"><input type="text"  size="5" name="grab_pause" value="{grab-pause}"> {sek}
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{add_pause_word}</td>
   <td style="padding:4px"><input type="text"  size="5" name="add_pause" value="{add-pause}"> {sek}
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{use_proxy}</td>
   <td style="padding:4px">
   <input type="checkbox" name="proxy" value="1" {prox} />
  <a href="#" class="hintanchor" onMouseover="showhint('{help_use_proxy}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{rss_canal}</td>
   <td style="padding:4px">
   <input type="checkbox" name="rss_html" value="1" {rss_html} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rss_canal}', this, event, '420px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px" width="260">{rss_description}:</td>
    <td style="padding:4px"><input type="text" size="53" name="rss_xdescr" value="{xdescr}">  
    </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{cat_default}</td>
  <td style="padding:4px">
   <select data-placeholder="{selcateory}" name="category[]" class="cat_select" multiple>
    {category}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_cat_default}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{cat_auto}</td>
   <td style="padding:4px">
    <input type="checkbox" name="thumb_img" value="1" {thumb-images} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_cat_auto}', this, event, '420px')">[?]</a> 
  </td>
  </tr>

  <tr>
   <td style="padding:4px"  width="260">{catsp}</td>
   <td style="padding:4px">
    <input type="checkbox" name="cat_sp" value="1" {cat-sp} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_catsp}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr>
            <td height="29" style="padding-left:5px;"><b>{kats}:</b>{help_kats}</td>
      <td style="padding:4px">
      <textarea name="kategory" class="load_img" style="width:388px;height:70px;">{kategory}</textarea>
         <a href="#" class="hintanchor" onMouseover="showhint('{help_kats1}', this, event, '360px')">[?]</a>
	</td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{catnul}</td>
   <td style="padding:4px">
   <input type="checkbox" name="cat_nul" value="1" {cat-nul} />
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{teg_auto}</td>
   <td style="padding:4px">
   <input type="checkbox" name="tags_auto" value="1"  {tags-auto} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_teg_auto}', this, event, '420px')">[?]</a> 
   <input type="checkbox" name="tags_zag" value="1" {tags-zag} />
    <input type="text" id="tags_kol" name="tags_kol" size="10"  class="load_img" value="{tags-kol}"><a href="#" class="hintanchor" onMouseover="showhint('{slov_teg}', this, event, '260px')">[?]</a>

  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{rewrite}</td>
   <td style="padding:4px">
   <input type="checkbox" name="rewrite_news" value="1"  {rewrite-news} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rewrite}', this, event, '420px')">[?]</a> 
   {upd_date}:
   <input type="checkbox" name="rewrite_data" value="1"   {rewrite-data} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rewrite1}', this, event, '420px')">[?]</a>
   {upd_con}:
   <select name="rewrite_con" class="load_img">
    {rewrite-con}
   </select>
  </td>
  </tr>

  <!-- <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{pro_rewrite}</td>
   <td style="padding:4px">
   <input type="checkbox" name="rewrite_pro" value="1"  {rewrite-pro} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rewrite_pro}', this, event, '420px')">[?]</a> 
  </td>
  </tr> -->

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{no_rewrite}</td>
   <td style="padding:4px">
   <input type="checkbox" name="rewrite_no" value="1"   {rewrite-no} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_no_rewrite}', this, event, '420px')">[?]</a> 
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{no_prov}</td>
   <td style="padding:4px">
   <select name="no_prow" class="load_img">
    {no-prow}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_no_prov}', this, event, '420px')">[?]</a> 
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{cachelink}:</td>
   <td style="padding:4px">
<input type="checkbox" name="cache_link" value="1"   {cache-link} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_cache_link}', this, event, '420px')">[?]</a> 
  </td>
  </tr>



  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{moderation}</td>
   <td style="padding:4px">
<input type="checkbox" name="allow_mod" value="1"    {allow-mod} />
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{post_index}</td>
   <td style="padding:4px">
 <input type="checkbox" name="allow_main" value="1" {allow-main} />
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{rating}</td>
   <td style="padding:4px">
 <input type="checkbox" name="allow_rate" value="1" {allow-rate} />
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{commentary}</td>
   <td style="padding:4px">
 <input type="checkbox" name="allow_comm" value="1" {allow-comm} />
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{xfields_canal}</td>
   <td style="padding:4px">
 <input type="checkbox" name="allow_more" value="1" {allow-full} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_xfields_canal}', this, event, '500px')">[?]</a>
    {doc_leech_dop}
 <input type="checkbox" name="leech_dop" value="1" {leech-dop} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_leech_dop}', this, event, '500px')">[?]</a>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{add_auto}<a href="#" class="hintanchor" onMouseover="showhint('{help_add_auto}', this, event, '280px')">[?]</a></td>
   <td style="padding:4px">
 <input type="checkbox" name="auto" value="1" {allow-auto} />
	&nbsp;&nbsp;
   <!-- {cronauto}  <input type="text"  size="5" name="cron_auto" value="{cron-auto}"> {mins}<a href="#" class="hintanchor" onMouseover="showhint('{help_cronauto}', this, event, '420px')">[?]</a> -->&nbsp;&nbsp;{crone_glob} <input type="text"  size="5" name="kol_cron" value="{kol-cron}">
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{date_news}</td>
  <td style="padding:4px">
   <select name="news_date"  class="load_img">
    {date-format}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_date_news}', this, event, '360px')">[?]</a>
  </td>
  </tr>

    </table>

   </div>
  <!-- Конец -->
 {inserttag}
  <!-- Изображения -->
   <div class="box">
  <table cellpadding="" cellspacing="0" width="98%" align="center">

    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center"><b>{im_vkl}</b></td>
    </tr>

  <tr style="border-bottom:1px dashed #c4c4c4;">
   <td style="padding:4px"  width="260">{parse_rss}</td>
   <td style="padding:4px">
   <select name="rss_parse" class="load_img">
    {rss-parse}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_parse_rss}', this, event, '500px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{down_pics}</td>
   <td style="padding:4px">
<select name="load_img" class="load_img">
{load-img}
</select>
	<a href="#" class="hintanchor" onMouseover="showhint('{help_post_rad}', this, event, '220px')">[?]</a>
	</td>
  </tr>

    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{maximage}:</td>
   <td style="padding:4px">
<input type="text" size="10" name="max_image" value="{max-image}">
<a href="#" class="hintanchor" onMouseover="showhint('{help_max_image}', this, event, '220px')">[?]</a>
	</td>
  </tr>

    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{minimage}:</td>
   <td style="padding:4px">
<input type="text" size="10" name="min_image" value="{min-image}">
<a href="#" class="hintanchor" onMouseover="showhint('{help_min_image}', this, event, '220px')">[?]</a>
	</td>
  </tr>

    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{k_pop_image}:</td>
   <td style="padding:4px">
<input type="text" size="10" name="kpop_image" value="{kpop-image}">
<a href="#" class="hintanchor" onMouseover="showhint('{help_kpop_image}', this, event, '220px')">[?]</a>
	</td>
  </tr>

<tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{no_stor_image}:</td>
   <td style="padding:4px">
 <input type="checkbox" name="nostor_image" value="1" {nostor-image} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_nostor_image}', this, event, '420px')">[?]</a>
	</td>
  </tr>

<tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{zipimage}:</td>
   <td style="padding:4px">
 <input type="checkbox" name="zip_image" value="1" {zip-image} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_zipimage}', this, event, '420px')">[?]</a>
	</td>
  </tr>

<tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{img_down_full}</td>
   <td style="padding:4px">
   <select name="parse_url_sel" class="load_img">
    {parse-url-sel}
   </select>
<a href="#" class="hintanchor" onMouseover="showhint('{help_img_down_full}', this, event, '420px')">[?]</a>
	</td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{add_pics_shortstory}</td>
   <td style="padding:4px">
 <input type="checkbox" name="short_img" value="1" {short-images} />
   <select name="short_img_p" class="load_img">
    {short-images-p}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_add_pics_shortstory}', this, event, '420px')">[?]</a>
<input type="text" size="10" name="kol_image_short" value="{kol-image-short}">
<a href="#" class="hintanchor" onMouseover="showhint('{help_kolimageshort}', this, event, '220px')">[?]</a>
  </td>
  </tr>

 <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{dubl_host}</td>
  <td style="padding:4px">
 <input type="checkbox" name="dubl_host" value="1" {dubl-host} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dubl_host}', this, event, '420px')">[?]</a>
    </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{img_short}</td>
  <td style="padding:4px">
 <input type="checkbox" name="one_serv" value="1" {one-serv} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_img_short}', this, event, '420px')">[?]</a>
    </td>
  </tr>

  <tr style=" border-top:1px dashed #c4c4c4" colspan="2">
   <td style="padding:4px" width="304">{align} <a href="#" class="hintanchor" onMouseover="showhint('{help_align}', this, event, '420px')">[?]</a>
   </td>
   <td style="padding:4px">
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{align_short}</td>
   <td style="padding:4px">
    <select name="image_align"  class="load_img">
      {image-align}
    </select>
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{align_full}</td>
   <td style="padding:4px">
    <select name="image_align_full" class="load_img">
      {image-align-full}
    </select>
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{align_post}</td>
   <td style="padding:4px">
    <select name="image_align_post" class="load_img">
      {image-align-post}
    </select>
   </td>
  </tr>



  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{folder_pics}<a href="#" class="hintanchor" onMouseover="showhint('{help_folder_pics1}', this, event, '360px')"><font color=red><b>*</b></font></a></td>
 <td style="padding:4px"><input type="text" size="20" name="date" value="{date}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_folder_pics}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260"><b>{dim}</b>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dim}', this, event, '420px')">[?]</a> 

</td>
  <td style="padding:4px">
  {dimdate}
 <input type="checkbox" name="dim_date" value="1" {dim-date} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_dimdate}', this, event, '420px')">[?]</a>  
&nbsp;&nbsp;{dimsait}
 <input type="checkbox" name="dim_sait" value="1" {dim-sait} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_dimsait}', this, event, '420px')">[?]</a><br><br>
   {dimcat}   
<input type="checkbox" name="dim_cat" value="1" {dim-cat} />
<a href="#" class="hintanchor" onMouseover="showhint('{help_dimcat}', this, event, '420px')">[?]</a>
   &nbsp;&nbsp;{zag_nov}
   <input type="checkbox" name="dim_week" value="1" {dim-week} />
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{watermark_add}</td>
  <td style="padding:4px">
<input type="checkbox" name="allow_watermark" value="1" {allow-water} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_watermark_add}', this, event, '420px')">[?]</a>
{wathost}  
<input type="checkbox" name="wat_host" value="1" {wat-host} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_wathost}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260"><b>{dop_watermark}:</b><br><br><br><span class=small><b>light</b></span><br><br><span class=small><b>dark</b></span>
  </td>
  <td style="padding:4px"><br>
<input type="checkbox" name="dop_watermark" value="1" {dop-watermark} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dop_watermark}', this, event, '420px')">[?]</a><br><br>
 <input type=text name='watermark_image_light' value="{watermark-image-light}" size=50><br><br>
<input type=text  name='watermark_image_dark' value="{watermark-image-dark}" size=50>
  </td>
  </tr>

  <tr style=" border-top:1px dashed #c4c4c4" colspan="2">
   <td style="padding:4px" width="304">{place_watermark} <a href="#" class="hintanchor" onMouseover="showhint('{help_place_watermark}', this, event, '420px')">[?]</a>
   </td>
   <td style="padding:4px">
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{place_watermark_x}</td>
   <td style="padding:4px">
    <select name="x"  class="load_img">
      {x}
    </select>
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{place_watermark_x}</td>
   <td style="padding:4px">
    <select name="y" class="load_img">
      {y}
    </select>
   </td>
  </tr>

  <tr style="border-top:1px dashed #c4c4c4">
   <td style="padding:4px" >{padding_watermark}</td>
   <td style="padding:4px"><input align="center" type="text" size="5" name="margin" value="{margin}"> 
   <a href="#" class="hintanchor" onMouseover="showhint('{help_padding_watermark}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  </tr>


    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center"><b>{fil_vkl}</b></td>
    </tr>

  <tr>
   </td><td  width="304">{help_down_fil}</td><td style="padding:4px">

<table border="0" width="100%">

  <tr>
   <td style="padding:0px 0px 0px 4px" ><i>{typ_fil}</i></td>
   <td style="padding:0px 0px 0px 4px" ><i>{down_fil}</i></td>
   <td style="padding:0px 0px 0px 4px" ><i>{pap_down}</i><a href="#" class="hintanchor" onMouseover="showhint('{help_pap_down}', this, event, '420px')">[?]</a></td>
   <td style="padding:0px 0px 0px 4px" ><i>{url_fil_down}</i></td>
   <td style="padding:0px 0px 0px 4px" ><i>{tit_fil_down}</i></td>
  </tr>
  <tr>
   <td style="padding:4px">{teg_vid} [video=...]</td>
   <td style="padding:4px">
<input type="checkbox" name="files_video" value="1" {file-video} />
</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_video" value="{pap-video}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_video" value="1" {url-video} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_video" value="1" {tit-video} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">{teg_vid} [flash...]</td>
   <td style="padding:4px">
<input type="checkbox" name="files_rar" value="1"  {file-rar} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_rar" value="{pap-rar}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_rar" value="1" {url-rar} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_rar" value="1" {tit-rar} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">zip,rar</td>
   <td style="padding:4px">
<input type="checkbox" name="files_zip" value="1"  {file-zip} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_zip" value="{pap-zip}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_zip" value="1" {url-zip} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_zip" value="1" {tit-zip} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">doc,txt</td>
   <td style="padding:4px">
<input type="checkbox" name="files_doc" value="1"  {file-doc} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_doc" value="{pap-doc}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_doc" value="1" {url-doc} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_doc" value="1" {tit-doc} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">apk</td>
   <td style="padding:4px">
<input type="checkbox" name="files_txt" value="1"  {file-txt} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_txt" value="{pap-txt}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_txt" value="1" {url-txt} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_txt" value="1" {tit-txt} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">dle</td>
   <td style="padding:4px">
<input type="checkbox" name="files_dle" value="1"  {file-dle} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_dle" value="{pap-dle}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_dle" value="1" {url-dle} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_dle" value="1" {tit-dle} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px">torrent</td>
   <td style="padding:4px">
<input type="checkbox" name="files_tor" value="1"  {file-tor} />
	</td>
<td style="padding:4px">
<input type="text" size="20" name="pap_tor" value="{pap-tor}">
</td>
<td style="padding:4px">
<input type="checkbox" name="url_tor" value="1" {url-tor} />
    </td>
<td style="padding:4px">
<input type="checkbox" name="tit_tor" value="1" {tit-tor} />
      </td>
  </tr>
</table>
   </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px" ><b>{torrage}:</b></td>
   <td style="padding:4px">
<input type="checkbox" name="tor_torrage" value="1" {tor-torrage} />
      </td>
  </tr>
  <tr style=" border-top:1px dashed #c4c4c4">
   <td style="padding:4px" ><b>{atach}:</b></td>
   <td style="padding:4px">
<input type="checkbox" name="files_atach" value="1" {file-atach} />
     </td>
  </tr>
  <tr style="border-top:1px dashed #c4c4c4">
   <td style="padding:4px" ><b>{filename}:</b></td>
   <td style="padding:4px"><input align="center" type="text" size="30" name="file_name" value="{file-name}">
<a href="#" class="hintanchor" onMouseover="showhint('{help_filename}', this, event, '420px')">[?]</a>
   </td>
  </tr>
</table>

   </div>
  <!-- Конец -->


  <!-- Дополнительные настройки -->
   <div class="box">

  <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{title_probel}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="title_prob" value="1" {title-prob} />
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{br_text_html}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="text_html" value="1" {text-html} />
  </td>
  </tr>

   <tr>
   <td colspan="2" style="border-top:1px dashed #c4c4c4" ></td>
  </tr>
  <tr >
    <td style="padding:4px" style="border-top:1px dashed #c4c4c4" >{html_tag_no_del}</td>
    <td style="padding:4px"><input type="text" size="30" name="teg_fix" value="{teg-fix}">  
    <a href="#" class="hintanchor" onMouseover="showhint('{help_html_tag_no_del}', this, event, '360px')">[?]</a>
    </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{convert}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="convert_utf" value="1" {convert-utf} />
   <a href="#" class="hintanchor" onMouseover="showhint('{help_convert}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{titlegener}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="title_gener" value="1" {title-gener}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_titlegener}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{shortstory_del}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="clear_short" value="1" {clear-short}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_shortstory_del}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{fullstory_del}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="clear_full" value="1" {clear-full}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_fullstory_del}', this, event, '420px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{fullstory_add}</td>
   <td width="768" style="padding:4px">
<input type="checkbox" name="add_full" value="1" {add-full}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_fullstory_add}', this, event, '420px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{kol_short_word}</td>
   <td width="768" style="padding:4px"><input type="text" size="5" name="kol_short" value="{kol-short}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_kol_short_word}', this, event, '420px')">[?]</a>
   &nbsp;<input type="text" size="5" name="sim_short" value="{sim-short}"><a href="#" class="hintanchor" onMouseover="showhint('{help_sim_short_word}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" >{pagebreak}</td>
  <td style="padding:4px" >
<input type="text" size="5" name="page_break" value="{page-break}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_pagebreak}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" >{shortstory_fulstory}</td>
  <td style="padding:4px" >
<input type="checkbox" name="short_full" value="1" {short-full}/>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_shortstory_fulstory}', this, event, '420px')">[?]</a>
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{del_empty_line}</td>
   <td style="padding:4px">
<input type="checkbox" name="null" value="1" {null}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_del_empty_line}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{text_url_active}</td>
   <td style="padding:4px">
   <select name="text_url" class="load_img">
    {text-url}
   </select>

   <select name="text_url_sel" class="load_img">
    {text-url-sel}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_text_url_active}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{active_hide}</td>
   <td style="padding:4px">
<input type="checkbox" name="hide" value="1" {hide}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_active_hide}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="260">{active_leech}</td>
   <td style="padding:4px">
<input type="checkbox" name="leech" value="1" {leech}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_active_leech}', this, event, '420px')">[?]</a>
{leechshab}: <input type="text"  name="leech_shab" size="62"  class="load_img" value="{leech-shab}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_leech-shab}', this, event, '420px')">[?]</a>
  </td>
  </tr>

    {sinonim}

  <tr>
   <td style="padding:4px"  width="304">{ping}:</td>
   <td style="padding:4px">
<input type="checkbox" name="pings" value="1" {pings}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_ping}', this, event, '420px')">[?]</a>
  </td>
  </tr>


    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>

  <tr>
   <td style="padding:4px"  width="304">{traslate}</td>
   <td style="padding:4px">
<input type="checkbox" name="lang_on" value="1" {lang-on}/>
   <select name="lang_in" class="load_img">
    {lang-in}
   </select>
   <b>&rArr;</b>
   <select name="lang_out" class="load_img">
    {lang-out}
   </select>
   <b>&rArr;</b>
   <select name="lang_outf" class="load_img">
    {lang-outf}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_traslate}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr>
   <td style="padding:4px"  width="304">{traslate-yan}</td>
   <td style="padding:4px">
<input type="checkbox" name="yan_on" value="1" {yan-on}/>
   <select name="lang_yan_in" class="load_img">
    {lang-yan-in}
   </select>
   <select name="lang_yan_out" class="load_img">
    {lang-yan-out}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_traslate}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr>
   <td style="padding:4px"  width="304">{langtitle}</td>
   <td style="padding:4px">
<input type="checkbox" name="lang_title" value="1" {lang-title}/><!-- переделать -->
   {titlekomb}
<input type="checkbox" name="lang_title_komb" value="1" {lang-title-komb}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{titlekomb}', this, event, '420px')">[?]</a>
  </td>
  </tr>


    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td  colspan="2" style="padding: 5px 0px">

<div class="title_spoiler"><img id="image-dop_news" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg('dop_news')"><b>{s_t_k_o}</b></a></div>

  <div id="dop_news" style="display:none;">
  <table cellpadding="" cellspacing="0" width="100%" align="center">
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{catalog_grab}<a href="#" class="hintanchor" onMouseover="showhint('{help_catalog_grab}', this, event, '260px')">[?]</a></td>
        <td style="padding:4px"><input type="text" name="symbols" size="5"  class="load_img" value="{symbol}">
	 auto <input type="checkbox" name="auto_symbol" value="1" {auto-symbol}/> {kol_cmb} <select name="auto_numer" class="load_img">{auto-numer}</select>
	</td>
    </tr>
    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
        <td width="140" height="29" style="padding:4px">{tags_lenta}</td>
        <td style="padding:4px"><input type="text" id="tags" name="tags" size="62"  class="load_img" value="{tags}"><a href="#" class="hintanchor" onMouseover="showhint('{help_tags_lenta}', this, event, '260px')">[?]</a>
    </td></tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px">{date_expires}</td>
   <td style="padding:4px"><input align="center" type="text" size="5" name="data_deap" class="load_img" value="{data-deap}">
   <select name="deap" class="load_img">
    {deap}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{hint_expires}', this, event, '420px')">[?]</a>
  </td>
  </tr>
	    <tr >
	        <td>&nbsp;</td>
	        <td style="padding:4 0 0 4px">{add_metatags_grab}<a href="#" class="hintanchor" onMouseover="showhint('{help_metatags_grab}', this, event, '220px')">[?]</a></td>
	    </tr>
	    <tr>
	        <td height="29" style="padding-left:5px;">{meta_title_grab}</td>
	        <td style="padding:4px"><input type="text" name="meta_title" style="width:250px;" class="load_img" value="{meta-title}">  {auto_meta_title}  <input type="checkbox" name="auto_metatitle" value="1" {auto-metatitle}/>
    </td>

	    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
	        <td style="padding:4px"  width="304">{chpu}</td>
	        <td style="padding:4px"><input type="checkbox" name="auto_chpu" value="1" {auto-chpu}/>
    </td>

  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{gen_meta_descr_grab}</td>
   <td style="padding:4px"><select name="descr_sel" class="load_img">{descr-sel}</select></td>
  </tr>

	    <tr>
	        <td height="29" style="padding-left:5px;">{meta_descr_grab}:<br><i>{help_meta_descr_grab}</i></td>
	        <td style="padding:4px"><textarea name="meta_descr" class="load_img" style="width:388px;height:70px;">{meta-descr}</textarea></td>
	    </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{gen_meta_keys_grab}</td>
   <td style="padding:4px"><select name="keyw_sel" class="load_img">{keyw-sel}</select></td>
  </tr>

	    <tr style=" border-top:1px dashed #c4c4c4">
	        <td height="29" style="padding-left:5px;">{meta_keys_grab}:<br><i>{help_meta_keys_grab}</i></td>
	        <td style="padding:4px"><textarea name="key_words" class="load_img" style="width:388px;height:70px;">{key-words}</textarea>
			</td>
	    </tr>

    </table>
</div>
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td  colspan="2" style=" padding: 4px 0px">

<div class="title_spoiler"><img id="image-dop_dop" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg('dop_dop')"><b>{dop_s_f_t}</b></a></div>

 <div id="dop_dop" style="display:none;">
  <table cellpadding="" cellspacing="0" width="100%" align="center">
  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><b>{dop_p_zag}:</b></td>
  </tr>
  <tr>

   <tr>
   <td colspan="2" style="border-top:1px dashed #c4c4c4" ></td>
  </tr>
  <tr >
    <td style="padding:4px" style="border-top:1px dashed #c4c4c4" >{v_str}</td>
    <td style="padding:4px"><input type="text" style="width:79%;"" name="s_title" value="{s-title}">  <a href="#" class="hintanchor" onMouseover="showhint('{help_v_str}', this, event, '400px')">[?]</a>
    </td>
  </tr>

  <tr >
    <td style="padding:4px" >{v_konz}</td>
    <td style="padding:4px"><input type="text" style="width:79%;" name="end_title" value="{end-title}">  <a href="#" class="hintanchor" onMouseover="showhint('{help_v_str}', this, event, '400px')">[?]</a>
    </td>
  </tr>

  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><b>{dop_fr_sh}:</b></td>
  </tr>
  <tr>

  <tr>
   <td style="padding:4px">{v_str}</td>
   <td style="padding:4px">{BB_codezz}<textarea onclick="setFieldName(this.name)" class="load_img" rows="4"  style="width:79%; height:100px"  name="sfr_short">{sfr-short}</textarea>
   </td>
  </tr>

  <tr>
   <td style="padding:4px">{v_konz}</td>
   <td style="padding:4px">{BB_codezz}<textarea onclick="setFieldName(this.name)" class="load_img" rows="4"  style="width:79%; height:100px"  name="efr_short">{efr-short}</textarea>
   </td>
  </tr>

  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><b>{dop_fr_fu}:</b></td>
  </tr>
  <tr>

  <tr>
   <td style="padding:4px">{v_str}</td>
   <td style="padding:4px">{BB_codezz}<textarea onclick="setFieldName(this.name)" class="load_img" rows="4"  style="width:79%; height:100px"  name="sfr_full">{sfr-full}</textarea>
   </td>
  </tr>

  <tr>
   <td style="padding:4px">{v_konz}</td>
   <td style="padding:4px">{BB_codezz}<textarea onclick="setFieldName(this.name)" class="load_img" rows="4"  style="width:79%; height:100px"  name="efr_full">{efr-full}</textarea>

   </td>
  </tr>
    </table>
</div>
   </td>
  </tr>
    </table>

   </div>
  <!-- Конец -->


  <!-- Визуализация -->
   <div class="box">
   <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_author}</td>
   <td style="padding:4px">
<input type="checkbox" name="show_autor" value="1" {show_autor}/>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_date}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_date" value="1" {show_date}/>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_tag}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_tegs" value="1" {show_tegs}/>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_bbcode}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_code" value="1" {show_code}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_down}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_down" value="1" {show_down}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="260">{display_xfields}</td>
  <td style="padding:4px">
  <input type="checkbox" name="show_f" value="1" {show_f}/>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_display_xfields}', this, event, '360px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_catalog_grab}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_symbol" value="1" {show_symbol}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_url_grab}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_url" value="1" {show-url}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_date_expires}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_date_expires" value="1" {show_date_expires}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_meta_title_grab}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_metatitle" value="1" {show_metatitle}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_meta_descr_grab}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_metadescr" value="1" {show_metadescr}/>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="260">{display_meta_keys_grab}</td>
   <td style="padding:4px">
   <input type="checkbox" name="show_keywords" value="1" {show_keywords}/>
   </td>
  </tr>

    </table>

   </div>
  <!-- Конец -->


  <!-- Фильтр, Авторизация -->
   <div class="box">
   <table cellpadding="" border="0" cellspacing="0" width="98%" align="center">



  <tr style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >
   <td style="padding:4px" valign="top" >{keyword}</td>
   <td style="padding:4px"><textarea class="load_img" rows="4"  style="width:79%; height:100px"  name="keywords">{keywords}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_keyword}', this, event, '400px')">[?]</a>
   </td>
  </tr>


  <tr style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >
   <td style="padding:4px" valign="top" >{stop_keyword}</td>
   <td style="padding:4px"><textarea class="load_img" rows="4"  style="width:79%; height:100px"  name="stkeywords">{stkeywords}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_stop_keyword}', this, event, '400px')">[?]</a>
   </td>
  </tr>



   <tr>
   <td colspan="2" style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >{authorization}</td>
  </tr>
<tr>
   <td style="padding:4px"  width="250">{login_pass}</td>
   <td style="padding:4px">
<input type="checkbox" name="log_pas" value="1" {log-pas}/>
   </td>
  </tr>
<tr>
   <td style="padding:4px"  width="250">{cokie_wr}:</td>
   <td style="padding:4px">
<input type="checkbox" name="log_cookies" value="1" {log-cookies}/>
   </td>
  </tr>
  <tr>
   <td style="padding:4px" valign="top" >{cookiess}:</td>
   <td style="padding:4px">
    <textarea class="load_img" rows="4"  style="width:79%; height:100px"  name="cookies">{cookies}</textarea>
    <a href="#" class="hintanchor" onMouseover="showhint('{help_authorization}', this, event, '400px')">[?]</a>
   </td>
  </tr>




  </table>

   </div>


 <!-- Конец -->

 <!-- Авторы -->
   <div class="box">
   <table cellpadding="" cellspacing="0" width="98%" align="center">
 <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >{author_title}</td>
  </tr>

  <tr>
  <td style="padding:4px" >{group_author_default}</td>
  <td style="padding:4px">
   <select name="groups[]" class="cat_select" multiple>
    {groups}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_group_author_default}', this, event, '400px')">[?]</a>
  </td>
  </tr>

  <tr>
   <td style="padding:4px" valign="top" >{author}</td>
   <td style="padding:4px"><textarea class="load_img" rows="10" cols="40" name="Autors">{Autors}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_author}', this, event, '400px')">[?]</a>
   </td></tr>
  </table>
  
  </div>

<!-- Конец -->

  <!-- Шаблоны -->

  <div class="box">



   <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">

    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center"><b>{nast_rss_html}</b></td>
    </tr>
   <tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;">{templates_fullstory}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{templates_fulltstory}', this, event, '260px')">[?]</a></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">{delicate_control_templates}:
<input type="checkbox" name="end_short"  value="1" {end-short} />
 </td>
  </tr>
  </table>

  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_delicate_control_templates}</td>
  </tr>
  </table>
   <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" id="start_template"  name="start_template">{start-template}</textarea>


   </td></tr>
</table>


<!-- Шаблон полной дополнительный -->
<div class="title_spoiler"><center><img id="image-templates_fullstory" style="vertical-align: middle;border: none;" alt="" src="./engine/skins/grabber/images/plus.gif" />&nbsp;<a href="javascript:ShowOrHideg('templates_fullstory')">{templates_fullstory} <b>dop</b></a></center></div>

<div id="templates_fullstory" style="display:none">
  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;">{templates_fullstory}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{templates_fulltstory}', this, event, '260px')">[?]</a></td>
  </tr>
  </table>
   <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" id="finish_template" name="finish_template">{end-template}</textarea>
   </td></tr>
</table>
</div>



<!-- Шаблон полной дополнительный -->


   <table border="0" cellpadding="0" cellspacing="0"  width="98%" align="center">
<tr align="center">
   <td colspan="4" style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{dops_full}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{help_dop_ful}', this, event, '400px')">[?]</a>  {isp_sel}: <input type="checkbox" name="full_url_and" value="1" {full-url-and} /></td>
  </tr>

  <tr align="center">
    <td colspan="4" style="padding:4px">{BB_code}<input onclick="setFieldName(this.name)" type="text" style="width:79%;" name="dop_full" value="{dop-full}">

   </td>
  </tr>
</table>

  <table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
    <tr>
        <td bgcolor="#EFEFEF" colspan="4" height="29" style="padding-left:10px;" align="center"><b>{vk_dop_pol}</b></td>
    </tr>
</table>
<input type="hidden" name="kol_xfields" value="{kol-xfields}" />
{xfields-template}



  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center"><b>{ins_html}</b></td>
    </tr>  
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;">{templates_shortstory}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{help_templates_shortstory}', this, event, '400px')">[?]</a></td>
  </tr>
  <tr align="center">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="ful_start">{ful-start}</textarea>
    </td>
   </tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_news}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{start_templates_html}', this, event, '400px')">[?]</a></td>
</tr>
</table> 
<table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
  <td  align="center"  style="padding:4px">{delicate_control_templates}:
<input type="checkbox" name="end_link" value="1" {end-link} />
    </td>
</tr>
</table>
  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_delicate_control_templates}</td>
  </tr>
  </table>
  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
   <textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start_short">{start-short}</textarea>
   </td>   
  </tr>
</table>


<table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_title}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{help_templates_title}', this, event, '400px')">[?]</a>&nbsp;  <input type="checkbox" name="start_title_f" value="1" {start-title-f} /> <a href="#" class="hintanchor" onMouseover="showhint('{help_start_title_f}', this, event, '400px')">[?]</a></td>
  </tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start_title">{start-title}</textarea> 
   </td>
  </tr>
</table>



<table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_url_fullstory}&nbsp; <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_url_fullstory}', this, event, '400px')">[?]</a></td>
  </tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="sart_link">{sart-link}</textarea>
  </tr>
</table>
 <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_tag}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{start_templates_html_cat}', this, event, '400px')">[?]</a></td>
  </tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="sart_cat">{sart-cat}</textarea>
   </td>
  </tr>
</table>
<table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_data}&nbsp;<a href="#" class="hintanchor" onMouseover="showhint('{start_html_data}', this, event, '400px')">[?]</a></td>
  </tr>
  <tr align="left">

   <td width="83%"  align="center" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="shab_data">{shab-data}</textarea>
   </td>
  </tr>
</table>


   <table border="0" cellpadding=""  cellspacing="0" width="98%" align="center">
 
  <tr style="border-bottom:1px dashed #c4c4c4;border-top:1px dashed #c4c4c4">
    <td width="17%" valign="middle"  style="padding:4px" >{templates_page}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="full_link" value="{full-link}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_page}', this, event, '400px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{steppage}</td>
    <td style="padding:4px"><input type="text"  size="10" name="step_page" class="load_img" value="{step-page}"><a href="#" class="hintanchor" onMouseover="showhint('{help_steppage}', this, event, '400px')">[?]</a></td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{starterpage}</td>
    <td style="padding:4px"><input type="text"  size="10" name="starter_page" class="load_img" value="{starter-page}"><a href="#" class="hintanchor" onMouseover="showhint('{help_steppage}', this, event, '400px')">[?]</a></td>
  </tr>

  <tr style="border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{start_page}</td>
    <td style="padding:4px"><input type="text" class="load_img" size="10" name="so" value="{so}">&nbsp;{end_page}&nbsp;<input type="text" class="load_img" size="10" name="po" value="{po}"></td>
  </tr>



   <!-- <tr align="center">
   <td colspan="4" >{description_html}</td>
</tr> -->
</table>

  <table border="0" cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">
{rekl_HTML}
   </td>
  </tr>
  </table>

</div>
<!-- Конец -->



 <!-- Замены -->
   <div class="box">



<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_link}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{link_start_d}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="link_start_del" value="{link-start-del}">
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{link_finish_d}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="link_finish_del" value="{link-finish-del}">
   </td>
   </tr>
</table>




<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_title}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{title_start_del}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="s_del">{s-del}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_search_title}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{title_end_del}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="end_del">{end-del}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_subst_title}', this, event, '400px')">[?]</a>
   </td>
    </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_search}</td>
  </tr>
  </table>
<br/>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{search_line}</td>
   <td width="83%" style="padding:4px">{BB_codez}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="delate">{delate}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_search_line}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{subst_line}</td>
   <td width="83%" style="padding:4px">{BB_codez}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="inser">{inser}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_subst_line}', this, event, '400px')">[?]</a>
   </td>
    </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_search}</td>
  </tr>
  </table>
<br/>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_regular}</center></td>
  </tr>
  <tr>
   <td width="17%" align="center" valign="middle"  style="padding:4px">{expression}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start">{start}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{regular_expression}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>                                                        
    <td width="17%" align="center" valign="middle"  style="padding:4px">{paste}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="finish">{finish}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{end_regular_expression}', this, event, '400px')">[?]</a>
   </td>
   </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td  style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">
{help_search}
   </td>
  </tr>
</table>

<br />
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{shab_z_f}</center></td>
  </tr>
  <tr>
   <td width="17%" align="center" valign="middle"  style="padding:4px">{wat_zax}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="zhv_code">{zhv-code}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_shab_z_f}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>

  </div>

<!-- Конец -->


</div>
<br />
