<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2016 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: shortsite.php
-----------------------------------------------------
 Назначение: WYSIWYG для новостей с сайта
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$p_name = urlencode($member_id['name']);

if( $config['allow_site_wysiwyg'] == "1" ) {

	if ( $user_group[$member_id['user_group']]['allow_image_upload'] OR $user_group[$member_id['user_group']]['allow_file_upload'] ) {
		
		$image_upload = "'dleupload',";
		$image_q_upload = ", 'imageUpload'";
		
	} else { $image_upload = ""; $image_q_upload = ""; }

	$js_array[] = "engine/editor/jscripts/froala/editor.js";
	$js_array[] = "engine/editor/jscripts/froala/languages/{$lang['wysiwyg_language']}.js";

	$onload_scripts[] = <<<HTML
      $('.wysiwygeditor').froalaEditor({
        dle_root: dle_root,
        dle_upload_area : "short_story",
        dle_upload_user : "{$p_name}",
        dle_upload_news : "{$id}",
        width: '100%',
        height: '310',
        language: '{$lang['wysiwyg_language']}',
		placeholderText: '',
        enter: $.FroalaEditor.ENTER_BR,
        toolbarSticky: false,
        theme: 'gray',
        htmlRemoveTags: ['script', 'style'],
		lineBreakerTags: ['table', 'hr', 'iframe', 'pre', 'dl'],
        linkAlwaysNoFollow: false,
        linkInsertButtons: ['linkBack'],
        linkList:[],
        linkAutoPrefix: '',
        linkStyles: {
          'fr-strong': 'Bold',
          'fr-text-red': 'Red',
          'fr-text-blue': 'Blue',
          'fr-text-green': 'Green'
        },
        linkText: true,

        paragraphFormat: {
            N: 'Normal',
            H1: 'Heading 1',
            H2: 'Heading 2',
            H3: 'Heading 3',
            H4: 'Heading 4',
            H5: 'Heading 5',
            p: 'Paragraph',
            div: 'Layer',
        },
        paragraphStyles: {
          'fr-text-bordered': 'Bordered',
          'fr-text-spaced': 'Spaced',
          'fr-text-uppercase': 'Uppercase',
          'fr-text-gray': 'Gray',
          'fr-text-red': 'Red',
          'fr-text-blue': 'Blue',
          'fr-text-green': 'Green'
        },
        tableStyles: {
          'fr-solid-borders': 'Solid Borders',
          'fr-dashed-borders': 'Dashed Borders',
          'fr-alternate-rows': 'Alternate Rows'
        },
        tableCellStyles: {
          'fr-red': 'Red',
          'fr-blue': 'Blue',
          'fr-green': 'Green'
        },
        imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
        imageDefaultWidth: 0,
        imageInsertButtons: ['imageBack', '|', 'imageByURL'{$image_q_upload}],
		imageUploadURL: 'engine/ajax/upload.php',
		imageUploadParam: 'qqfile',
		imageUploadParams: { "subaction" : "upload", "news_id" : "{$id}", "area" : "short_story", "author" : "{$p_name}", "mode" : "quickload"  },
        imageMaxSize: {$config['max_up_size']} * 1024,
        imagePaste: false,
        imageStyles: {
          'fr-bordered': 'Borders',
          'fr-rounded': 'Rounded',
          'fr-padded': 'Padded',
          'fr-shadows': 'Shadows',
        },
		
        toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'align', 'color', 'insertLink', 'insertImage', {$image_upload}'insertVideo', 'paragraphFormat', 'paragraphStyle', 'dlehide', 'dlequote', 'dlespoiler', 'html'],

        toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'color', 'insertLink', '|', 'insertImage',{$image_upload}'insertVideo', 'dleaudio', '|', 'paragraphFormat', 'paragraphStyle', '|', 'formatOL', 'formatUL', '|', 'dlehide', 'dlequote', 'dlespoiler', 'html'],

        toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'indent', 'outdent', '|', 'subscript', 'superscript', '|', 'insertTable', 'formatOL', 'formatUL', 'insertHR', '|', 'clearFormatting', 'selectAll', '|', 'fullscreen', 'html', '-', 
                         'fontFamily', 'fontSize', '|', 'color', 'paragraphFormat', 'paragraphStyle', '|', 'insertLink', 'dleleech', '|', 'emoticons', 'insertImage',{$image_upload}'|', 'insertVideo', 'dleaudio','|', 'dlehide', 'dlequote', 'dlespoiler','dlecode','page_dropdown'],

        toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'indent', 'outdent', '|', 'subscript', 'superscript', '|', 'insertTable', 'formatOL', 'formatUL', 'insertHR', '|', 'clearFormatting', 'selectAll', '|', 'fullscreen', 'html', '-', 
                         'fontFamily', 'fontSize', '|', 'color', 'paragraphFormat', 'paragraphStyle', '|', 'insertLink', 'dleleech', '|', 'emoticons', 'insertImage',{$image_upload}'|', 'insertVideo', 'dleaudio','|', 'dlehide', 'dlequote', 'dlespoiler','dlecode','page_dropdown']

      }).on('froalaEditor.image.inserted froalaEditor.image.replaced', function (e, editor, \$img, response) {
	  
			if( response ) {
			
			    response = jQuery.parseJSON(response);
			  
			    \$img.removeAttr("data-returnbox").removeAttr("data-success").removeAttr("data-xfvalue").removeAttr("data-flink");

				if(response.flink) {
				  if(\$img.parent().attr('rel') == "highslide") {
		
					\$img.parent().attr('href', response.flink);
		
				  } else {
		
					\$img.wrap( '<a href="'+response.flink+'" class="highslide" rel="highslide"></a>' );
					
				  }
				}
			  
			}
			
		});
HTML;

$shortarea = <<<HTML
<link rel="stylesheet" href="{$config['http_home_url']}engine/editor/jscripts/froala/fonts/font-awesome.css">
<link rel="stylesheet" href="{$config['http_home_url']}engine/editor/jscripts/froala/css/editor.css">
<link rel="stylesheet" href="{$config['http_home_url']}engine/skins/codemirror/css/default.css">
<script type="text/javascript" src="{$config['http_home_url']}engine/skins/codemirror/js/code.js"></script>
<script type="text/javascript">
	var text_upload = "$lang[bb_t_up]";
</script>
<textarea id="short_story" name="short_story" class="wysiwygeditor" style="width:100%;height:200px;">{short-story}</textarea>
HTML;

} else {


	if ( $user_group[$member_id['user_group']]['allow_image_upload'] OR $user_group[$member_id['user_group']]['allow_file_upload'] ) $image_upload = "dleupload "; else $image_upload = "";

	$onload_scripts[] = <<<HTML
	tinymce.init({
		selector: 'textarea.wysiwygeditor',
		language : "{$lang['wysiwyg_language']}",
		width : "100%",
		height : "350",
		theme: "modern",
		plugins: ["advlist autolink lists link image charmap anchor searchreplace visualblocks visualchars fullscreen media nonbreaking table contextmenu emoticons paste textcolor colorpicker codemirror spellchecker dlebutton codesample"],
		relative_urls : false,
		convert_urls : false,
		remove_script_host : false,
		extended_valid_elements : "noindex,div[align|class|style|id|title]",
		custom_elements : 'noindex',
		toolbar_items_size: 'small',
		menubar: false,
		toolbar1: "fontselect fontsizeselect | table | link anchor dleleech unlink | {$image_upload}image dleemo dlemp dletube dlaudio | dlequote dlespoiler codesample dlebreak dlepage code",
		toolbar2: "undo redo | copy paste pastetext | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | subscript superscript | bullist numlist forecolor backcolor spellchecker removeformat",

		spellchecker_language : "ru",
		spellchecker_languages : "Russian=ru,Ukrainian=uk,English=en",
		spellchecker_rpc_url : "//speller.yandex.net/services/tinyspell",
		image_caption: true,

		dle_root : "{$config['http_home_url']}",
		dle_upload_area : "short_story",
		dle_upload_user : "{$p_name}",
		dle_upload_news : "{$id}",

		content_css : "{$config['http_home_url']}engine/editor/css/content.css"

	});
HTML;

$shortarea = <<<HTML
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
	var text_upload = "$lang[bb_t_up]";
</script>
    <textarea id="short_story" name="short_story" class="wysiwygeditor" style="width:98%;height:200px;">{short-story}</textarea>
HTML;


}

?>