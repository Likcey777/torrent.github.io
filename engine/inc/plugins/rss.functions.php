<?php @ini_set('display_errors', true);
if (!(defined('DATALIFEENGINE'))) {
    exit('Hacking attempt!');
}
if (!function_exists('mb_strtoupper')) {
    function mb_strtoupper($str) {
        return strtoupper($str);
    }
}
function text_html($s) {
    $s = preg_replace('/  ++/sxSX', "
", trim($s));
    $s = str_replace(array("
 ", " 
"), "
", $s);
    $s = preg_replace('/[
]{3,}+/sSX', "

", $s);
    return $s;
}
function keyword($str) {
    global $config;
    preg_match('|<meta.*name=[\'"]keywords[\'"].*content=[\'"](.*)[\'" ].*>|i', $str, $keyword);
    $quotes = array("'", '"');
    $story = str_replace($quotes, '', $keyword[1]);
    return $story;
}
function description($str) {
    global $config;
    preg_match('|<meta.*name=[\'"]description[\'"].*content=[\'"](.*)[\'" ].*>|i', $str, $description);
    $fastquotes = array("'", '"');
    $story = str_replace($fastquotes, '', $description[1]);
    return $story;
}
function dubl_news($selected = 0) {
    global $lang_grabber;
    $source = array($lang_grabber['no_pr_news'], 'url', $lang_grabber['zag_pr_news'], $lang_grabber['z_u_pr_news']);
    $buffer = '';
    for ($i = 0;$i <= 3;++$i) {
        if ($i == $selected) {
            $buffer.= '<option value="' . $i . '" selected>' . $source[$i] . '</option>
';
            continue;
        } else {
            $buffer.= '<option value="' . $i . '">' . $source[$i] . '</option>
';
            continue;
        }
    }
    return $buffer;
}
function relace_news_don($story, $title, $torrage) {
    $donlowd = new file_down();
    $donlowd->down_files = array();
    $story = str_replace('( get_file', '(get_file', $story);
    preg_match_all('#\[get_file=(.+?)\]#i', $story, $outs);
    if (count($outs[1]) == 0) preg_match_all('#\(get_file=(.*?)\)\]#is', $story, $outs);
    if (count($outs[1]) != 0) {
        foreach ($outs[1] as $item) {
            $file_inf = explode(',', $item);
            $donlowd->alt_name = $title;
            $donlowd->torrage = $torrage;
            $get_file = $donlowd->donlowd_serv(trim($file_inf[0]), trim($file_inf[1]), trim($file_inf[2]), trim($file_inf[3]), trim($file_inf[4]));
            $story = str_replace('(get_file=' . $item . ')]', $get_file . ']', $story);
            $story = str_replace('(get_file=' . $item . ')]', $get_file . ']', $story);
            $story = str_replace('[get_file=' . $item . ']', '[url=' . $get_file . ']', $story);
            $story = str_replace('[get_file=' . $item . ']', '[url=' . $get_file . ']', $story);
            $story = str_replace($file_inf[0], $get_file, $story);
        }
    }
    return array('story' => $story, 'files' => $donlowd->down_files, 'erors' => $donlowd->eror);
}
function get_proxy() {
    global $config_rss;
    $time = time() - filectime(ENGINE_DIR . '/inc/plugins/files/proxy.txt');
    if ($time > 900) {
        if ($config_rss['url_proxy'] == '') $config_rss['url_proxy'] = 'http://spys.ru/proxylist/';
        $link = get_urls($config_rss['url_proxy']);
        if ($config_rss['get_prox'] == true) $proxy_content = get_full($link[scheme], $link['host'], $link['path'], $link['query'], $cookies, $proxy);
        preg_match_all('!(\d+\.\d+\.\d+\.\d+<script type="text\/javascript">document.write\(.+?\)<\/script>)!', $proxy_content, $tran);
        if (!sizeof($tran[1])) preg_match_all('!(\d+\.\d+\.\d+\.\d+:\d+)!', $proxy_content, $tran);
        else preg_match('!<\/table><script type="text\/javascript">(.*)<\/script>!', $proxy_content, $an);
        if ($an[1] != '') {
            $coc = explode(";", $an[1]);
            $kl = array();
            foreach ($coc as $vl) {
                if (strpos($vl, "^")) {
                    $kl1[] = '(' . preg_replace('!=\d\^!', '^', $vl) . ')';
                    $kl2[] = preg_replace('!.*=(\d)\^.*!', "", $vl);
                }
            }
        }
        $tr = '';
        foreach ($tran[1] as $value) {
            $value = str_replace('<script type="text/javascript">document.write("<font class=spy2>:<\/font>"+', ":", $value);
            $value = str_replace(')</script>', "", $value);
            $value = str_replace(')+(', ')(', $value);
            if (!sizeof($kl)) $value = str_replace($kl1, $kl2, $value);
            $tr.= $value . '
';
        }
        openz(ENGINE_DIR . '/inc/plugins/files/proxy.txt', $tr);
    }
    if (trim($tr) != '') return true;
    else return false;
}
function close_dangling_tags($html) {
    preg_match_all("#\[([a-z]+)(.*?)\]#is", $html, $result);
    $openedtags = $result[1];
    preg_match_all("#\[/([a-z]+)\]#is", $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) != $len_opened) {
        $openedtags = array_reverse($openedtags);
        for ($i = 0;$i < $len_opened;$i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html.= '[/' . $openedtags[$i] . ']';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags) ]);
            }
        }
    }
    preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
    $openedtags = $result[1];
    preg_match_all("#</([a-z]+)>#iU", $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i = 0;$i < $len_opened;$i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html.= '</' . $openedtags[$i] . '>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags) ]);
        }
    }
    return $html;
}
function rss_xfields($t) {
    $va = array('0' => '');
    $list = array_map('trim', file(ENGINE_DIR . '/data/xfields.txt'));
    foreach ($list as $key) {
        $value = explode('|', $key);
        $va[$value[0]] = $value[$t];
    }
    return $va;
}
function convert($from, $to, $string) {
    global $config_rss;
    if ($from == 'utf-8' and $to == 'windows-1251' and $config_rss['convert'] == 'yes') {
        $strin = utf2win($string, 'w');
        return $strin;
    } elseif ($to == 'utf-8' and $from == 'windows-1251' and $config_rss['convert'] == 'yes') {
        $strin = utf2win($string, 'u');
        return $strin;
    } else {
        if (function_exists('iconv')) {
            if ($config_rss['convert'] == 'yes') {
                while (e_str(trim($string)) > 0) {
                    if (e_str($string) < 20000) {
                        $strin_result.= @iconv($from, $to . '//TRANSLIT//IGNORE', $string);
                        $string = '';
                    } else {
                        $string_pos = strrpos(e_sub($string, 0, 20000), ' ');
                        $strin_result.= @iconv($from, $to . '//TRANSLIT//IGNORE', e_sub($string, 0, $string_pos));
                        $string = e_sub($string, $string_pos);
                    }
                }
            } else {
                $strin_result = @iconv($from, $to . '//TRANSLIT//IGNORE', $string);
            }
            return $strin_result;
        } else {
            return $string;
        }
    }
}
$nds = $nd;
function get_title($full) {
    preg_match('#<title>(.*)&raquo;.*</title>#i', $full, $titls);
    if ($titls[1] == '') preg_match('#<title>(.*)</title>#i', $full, $titls);
    if (count($titls[1] != 0)) return $titls[1];
    else return false;
}
unset($nds[2], $nds[3]);
function get_tit($full) {
    preg_match("|.*?title=\"(.*?)\".*?|i", $full, $titls);
    if ($titls[1] == '') preg_match('|.*?title=\'(.*?)\'.*?|i', $full, $titls);
    if (count($titls[1] != 0)) return $titls[1];
    else return false;
}
function get_fullink($full) {
    global $lang_grabber;
    preg_match('|<a href=\"(.+)\">' . $lang_grabber['full_coment'] . '.*</a>|i', $full, $links);
    if ($links[1] != '') return $links[1];
    else return false;
}
function get_flink($full, $host, $id) {
    $host = addcslashes(stripslashes($host), '"[]!-.?*\()|/');
    preg_match("#<a.*?href[=]?[='\"](\S+?" . $id . "\S+?html)['\" >].*?>.*?<\/a>#is", $full, $links);
    if ($links[1] != '') return $links[1];
    else return false;
}
function get_link($full) {
    preg_match("|<div id=['\"]news-id-(\S+?)['\"].*>|i", $full, $links);
    if (count($links[1]) != 0) return $links[1];
    else return false;
}
function gen_date_format($selected = 0) {
    global $lang_grabber;
    $source = array($lang_grabber['date_flowing'], $lang_grabber['date_casual'], $lang_grabber['date_channel']);
    $buffer = '';
    for ($i = 0;$i <= 2;++$i) {
        if ($i == $selected) {
            $buffer.= '<option value="' . $i . '" selected>' . $source[$i] . '</option>
';
            continue;
        } else {
            $buffer.= '<option value="' . $i . '">' . $source[$i] . '</option>
';
            continue;
        }
    }
    return $buffer;
}
function sel($options, $selected = 0) {
    $output = '';
    if (count($options) != '0') {
        foreach ($options as $value => $description) {
            $description = strip_tags($description);
            $output.= "<option value=\"$value\"";
            if ($selected == $value) {
                $output.= ' selected ';
            }
            $output.= ">$description</option>
";
        }
    }
    return $output;
}
$ndr = $nd;
function gen_x($selected = 0, $k = 3) {
    global $lang, $lang_grabber;
    $source = array($lang['opt_sys_right'], $lang['opt_sys_center'], $lang['opt_sys_left'], $lang['opt_sys_none'], $lang_grabber['lang_donor']);
    $buffer = '';
    for ($i = 0;$i <= $k;++$i) {
        if ($i == $selected) {
            $buffer.= '<option value="' . $i . '" selected>' . $source[$i] . '</option>
';
            continue;
        } else {
            $buffer.= '<option value="' . $i . '">' . $source[$i] . '</option>
';
            continue;
        }
    }
    return $buffer;
}
function gen_y($selected = 0) {
    global $lang, $lang_grabber;
    $source = array($lang_grabber['opt_below'], $lang['opt_sys_center'], $lang_grabber['opt_above']);
    $buffer = '';
    for ($i = 0;$i <= 2;++$i) {
        if ($i == $selected) {
            $buffer.= '<option value="' . $i . '" selected>' . $source[$i] . '</option>
';
            continue;
        } else {
            $buffer.= '<option value="' . $i . '">' . $source[$i] . '</option>
';
            continue;
        }
    }
    return $buffer;
}
function deap($selected = 'yes') {
    global $lang;
    $yes_sel = '';
    $no_sel = '';
    if ($selected == 'yes') {
        $yes_sel = 'selected';
    } else {
        if ($selected == 'no') {
            $no_sel = 'selected';
        }
    }
    $buffer = ' <option value="0" ' . $yes_sel . ' style="color:blue">' . $lang['edit_dnews'] . '</option>
 <option value="1" ' . $no_sel . ' style="color:red">' . $lang['mass_edit_notapp'] . '</option>' . '';
    return $buffer;
}
function yesno($selected = 'yes') {
    global $lang;
    $yes_sel = '';
    $no_sel = '';
    if ($selected == 'yes') {
        $yes_sel = 'selected';
    } else {
        if ($selected == 'no') {
            $no_sel = 'selected';
        }
    }
    $buffer = ' <option value="1" ' . $yes_sel . ' style="color:blue">' . $lang['opt_sys_yes'] . '</option>
 <option value="0" ' . $no_sel . ' style="color:red">' . $lang['opt_sys_no'] . '</option>' . '';
    return $buffer;
}
function noyes($selected = 'yes') {
    global $lang;
    $yes_sel = '';
    $no_sel = '';
    if ($selected == 'yes') {
        $yes_sel = 'selected';
    } else {
        if ($selected == 'no') {
            $no_sel = 'selected';
        }
    }
    $buffer = ' <option value="0" ' . $yes_sel . ' style="color:blue">' . $lang['opt_sys_yes'] . '</option>
 <option value="1" ' . $no_sel . ' style="color:red">' . $lang['opt_sys_no'] . '</option>' . '';
    return $buffer;
}
$start_pos = spoiler(spoiler(mb_strtoupper(reset_url($_SERVER['HTTP_HOST']))) . reset_url($_SERVER['HTTP_HOST']));
function get_news($content, $start_template, $finish_template) {
    $start_pos = strpos($content, $start_template);
    $sub_content = e_sub($content, $start_pos, e_str($content));
    $finish_pos = strpos($sub_content, $finish_template) + e_str($finish_template);
    return e_sub($content, $start_pos, $finish_pos);
}
function get_im($content, $dop_sort = 0) {
    $img = array();
    $thumb = array();
    $img_siz = '';
    preg_match_all('#\[img.*?\](.+?)\[/img\]#i', $content, $img);
    preg_match_all('#\[thumb.*?\](.+?)\[/thumb\]#i', $content, $thumb);
    if ($dop_sort != 0) {
        if ($img[0][0] != '') {
            foreach ($img[1] as $key => $url) {
                $img_info = @getimagesize($url);
                if ($dop_sort == 2) {
                    if ($img_info[0] < $img_info[1]) {
                        $img_siz = $img[0][$key];
                        break;
                    }
                } else {
                    if ($img_info[0] > $img_info[1]) {
                        $img_siz = $img[0][$key];
                        break;
                    }
                }
            }
        }
        if ($thumb[0][0] != '') {
            foreach ($thumb[1] as $key => $url) {
                $img_info = @getimagesize($url);
                if ($dop_sort == 2) {
                    if ($img_info[0] < $img_info[1]) {
                        $img_siz = $thumb[0][$key];
                        break;
                    }
                } else {
                    if ($img_info[0] > $img_info[1]) {
                        $img_siz = $thumb[0][$key];
                        break;
                    }
                }
            }
        }
    }
    if ($img_siz == '') {
        if ($img[0][0] != '') return $img[0][0];
        else return $thumb[0][0];
    } else {
        return $img_siz;
    }
}
function get_full_news($content, $template, $tags = false) {
    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
    $template = str_replace('{get}', '(.*)', $template);
    $template = str_replace('{skip}', '.*', $template);
    $template = preg_replace("![

	]!s", '', $template);
    preg_match('!' . $template . '!iUs', $content, $found);
    $temp = array();
    for ($i = 1;$i < sizeof($found);$i++) {
        $temp[] = $found[$i];
    }
    if ($tags) {
        $content = implode(',', $temp);
        $content = preg_replace("!,[

	\s]+,!s", ",", $content);
    } else {
        $content = implode('', $temp);
    }
    return $content;
}
function spoiler($data) {
    return md5($data);
}
function get_short_news($content, $template) {
    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
    $template = str_replace('{get}', '(.*)', $template);
    $template = str_replace('{skip}', '.*', $template);
    $template = preg_replace("!['\"]!s", "['\"]", $template);
    $template = preg_replace("![

	]!s", '', $template);
    preg_match('!' . $template . '!mi', $content, $found);
    return $found[0];
}
function get_short_newss($content, $template) {
    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
    $template = str_replace('{get}', '(.*)', $template);
    $template = str_replace('{skip}', '.*', $template);
    $template = preg_replace("!['\"]!s", "['\"]", $template);
    $template = preg_replace("![

	]!s", '', $template);
    preg_match('!' . $template . '!mi', $content, $found);
    return $found[1];
}
$template = openz(($handl ? $handl : $dtr), false, 'r');
function get_dop_news($content, $template) {
    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
    $template = str_replace('{get}', '(.*)', $template);
    $template = str_replace('{skip}', '.*', $template);
    $template = preg_replace("![

	]!s", '', $template);
    preg_match('!' . $template . '!i', $content, $found);
    return $found[0];
}
function slash($matches = array()) {
    $story = $matches[1];
    if ($story == 'n' or $story == 't' or $story == 'r' or $story == 's') return addcslashes($story, "nrts");
    return stripslashes($story);
}
function get_full_replace($matches = array()) {
    return get_full_replace('http', $matches[1]);
}
function relace_news($story, $delete, $insert, $s_key = 0) {
    global $config, $charik;
    $story = unhtmlentities($story);
    $del = array();
    $ins = array();
    if (trim($delete) != '') {
        $del = explode('|||', $delete);
        if ($insert != '') $ins = explode('|||', $insert);
        foreach ($del as $key => $in) {
            preg_match('#{(1|2)}#is', $in, $st_key);
            if (intval($st_key[1]) != 0 and intval($st_key[1]) != $s_key) continue;
            $out = trim($ins[$key]);
            if (preg_match('#{get}#', $in) or preg_match('#{skip}#', $in)) {
                $in = addcslashes(stripslashes($in), "[]!-.#?*%+\/()|$");
                $in = str_replace('{' . intval($st_key[1]) . '}', '', $in);
                $in = str_replace('{get}', '(.*?)', $in);
                $in = str_replace('{skip}', '.*?', $in);
                $in = str_replace("{\(}", '(', $in);
                $in = str_replace("{\)}", ')', $in);
                $in = str_replace("{\|}", '|', $in);
                $in = preg_replace("![

	]!s", '', $in);
                $in = preg_replace_callback("!{{(.+?)}}!s", "slash", $in);
                if (preg_match('#{get.*}#', $out)) {
                    preg_match_all('#' . $in . '#is', $story, $url);
                    if (count($url[1])) {
                        foreach ($url[1] as $urls) {
                            $cont = get_full('http', $urls);
                            preg_match('#{get=(.*)}#', $out, $shab);
                            if ($shab[1] != '') {
                                $intus = get_full_news($cont, stripslashes($shab[1]));
                                if ($charik != strtolower($config['charset']) and trim($intus) != '' and trim($charik) != '') {
                                    $intus = convert($charik, strtolower($config['charset']), $intus);
                                }
                                $outs = preg_replace('#{get=(.*)}#', $intus, $out);
                                $urls = addcslashes(stripslashes($urls), "[]!-.#?*%+\/()|'");
                                $insi = str_replace('(.*?)', $urls, $in);
                                $story = preg_replace('#' . $insi . '#is', $outs, $story);
                            } else {
                                $story = preg_replace_callback('#' . $in . '#is', "get_full_replace('http', '')", $story);
                            }
                        }
                    }
                } else {
                    if ($out != '') $out = str_replace('{', "\\",$out);
if($out != '')	$out = str_replace('}','',$out);
if($out != '')	$out = str_replace('\zagolovok','{zagolovok}',$out);
if($out != '')	$out = str_replace('rag','{frag}',$out);
preg_match('#'.$in.'#is',$story,$out1);
$story = preg_replace('!'.$in.'!is',$out,$story);
}
}else{
$in = preg_replace("![]!s",'',$in);
$story = str_replace(stripslashes($in),$out,$story);
}}}
return stripslashes($story);}
$start_pos = spoiler($start_pos);
function get_urls ($news_link)
{
$parsed_url = parse_url ($news_link);
$scheme   = isset($parsed_url['scheme']) ?$parsed_url['scheme'] .'://': 'http://';
$host     = isset($parsed_url['host']) ?$parsed_url['host'] : '';
$port     = isset($parsed_url['port']) ?':'.$parsed_url['port'] : '';
$user     = isset($parsed_url['user']) ?$parsed_url['user'] : '';
$pass     = isset($parsed_url['pass']) ?':'.$parsed_url['pass']  : '';
$pass     = ($user ||$pass) ?"$pass@": '';
$path     = isset($parsed_url['path']) ?$parsed_url['path'] : '';
$query    = isset($parsed_url['query']) ?$parsed_url['query'] : '';
$fragment = isset($parsed_url['fragment']) ?'#'.$parsed_url['fragment'] : '';
return array ('scheme'=>$scheme,'host'=>$host,'path'=>$path,'query'=>$query);}
$str= $module_info['host'][1].$lang_grabber['pos'];
function charset($str) {
global $config;
rss_strip ($str);
preg_match ('|<meta.*?charset=[\'\"]?(.*?)[\'\"].*?>|i',$str,$charset);
if ($charset[1] == '')preg_match (" | < meta . * ? charset = ( . * ?) \'.*?>|i",$str,$charset);
if ($charset[1] == 'ISO - 8859 - 1')
{$char = 'utf - 8';}else{$char= $charset[1];}
if ($char == '')$char = $config['charset'];
return strtolower($char);}
function get_dle ($content)
{
preg_match_all("|(<div id=['\"]news-id-(.+)['\"].*>.+</div>)|mi", $content, $found);
                    return $found[0];
                }
                function get_page($content, $template) {
                    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
                    $template = str_replace('{get}', '(.*)', $template);
                    $template = str_replace('{skip}', '.*', $template);
                    $template = preg_replace("![

	]!s", '', $template);
                    preg_match_all('!' . $template . '!iUs', $content, $found);
                    $content = $found[0];
                    return $content;
                }
                function prv($data) {
                    $dt = filemtime($data);
                    $str = file_get_contents($data);
                }
                function get_rss_channel_info($rss_url, $proxy, $default_cp) {
                    global $db, $parse, $config;
                    $rss_parser = new rss_parser();
                    $rss_parser->default_cp = $default_cp;
                    $rss_parser->stripHTML = true;
                    $rss_result = $rss_parser->Get($rss_url, $proxy);
                    $channel_descr = str_replace('"', '', $rss_result['description']);
                    $channel_title = str_replace('"', '', $rss_result['title']);
                    $channel_html = str_replace('"', '', $rss_result['html_title']);
                    if (isset($rss_result['image_url'])) {
                        $channel_image = '<br/><img src=' . $rss_result['image_url'] . ' border=0><br/>';
                        $channel_descr = $channel_image . $channel_descr;
                    }
                    if ($channel_title == '') $channel_title = $channel_descr;
                    return array('title' => $channel_title, 'description' => $channel_descr, 'html' => $channel_html, 'charset' => $rss_result['charset']);
                }
                function check_disable_functions() {
                    global $lang_grabber;
                    $disable_functions = @ini_get('disable_functions');
                    $fun = explode(',', $disable_functions);
                    $functions = Array();
                    foreach ($fun as $item) {
                        $functions[] = trim($item);
                    }
                    $errors = '';
                    if (!function_exists('curl_init')) {
                        $errors.= '<li><font color=red><b>' . $lang_grabber['lang_er6'] . '</b></font></li>';
                    }
                    if (!ini_get('allow_url_fopen') and !function_exists('curl_init')) {
                        $errors.= '<li><font color=red><b>' . $lang_grabber['lang_er1'] . '</b></font></li>';
                    }
                    if (@ini_get('safe_mode') == 1) {
                        $errors.= '<li><font color=red><b>' . $lang_grabber['lang_er2'] . '</b></font></li>';
                    }
                    if (in_array('fopen', $functions)) {
                        $errors.= '<li>' . $lang_grabber['lang_er3'] . '</li>';
                    }
                    if (in_array('fsockopen', $functions) and !function_exists('curl_init')) {
                        $errors.= '<li>' . $lang_grabber['lang_er4'] . '</li>';
                    }
                    if (in_array('set_time_limit', $functions)) {
                        $errors.= '<li>' . $lang_grabber['lang_er5'] . '</li>';
                    }
                    if (trim($errors) != '') {
                        opentable($lang_grabber['lang_er0']);
                        echo '	<table cellpadding="4" cellspacing="0" width="100%">
	<tr><td style="padding:4px" class="navigation">
	' . $errors . '
	</td></tr>
	</table>';
                        closetable();
                    }
                }
                function openz($handl, $data, $wr = 'w+') {
                    $writable = chmod_file($handl);
                    if ($writable or $wr == 'r' or !@file_exists($handl)) {
                        $handle = fopen($handl, $wr);
                        if ($wr != 'r') fwrite($handle, $data);
                        else $c = fread($handle, filesize($handl));
                        fclose($handle);
                        chmod_file($handl);
                        if ($c != '') return $c;
                    }
                }
                function get_random_agent() {
                    $browsers = array('Mozilla/5.0 (compatible; YandexBot/3.0)', 'Mozilla/5.0 (compatible; YandexBot/3.0; MirrorDetector)', 'Mozilla/5.0 (compatible; YandexImages/3.0)', 'Mozilla/5.0 (compatible; YandexVideo/3.0)', 'Mozilla/5.0 (compatible; YandexMedia/3.0)', 'Mozilla/5.0 (compatible; YandexBlogs/0.99; robot)', 'Mozilla/5.0 (compatible; YandexAddurl/2.0)', 'Mozilla/5.0 (compatible; YandexFavicons/1.0)', 'Mozilla/5.0 (compatible; YandexDirect/3.0)', 'Mozilla/5.0 (compatible; YandexDirect/2.0; Dyatel)', 'Mozilla/5.0 (compatible; YandexMetrika/2.0)', 'Mozilla/5.0 (compatible; YandexCatalog/3.0; Dyatel)', 'Mozilla/5.0 (compatible; YandexNews/3.0)', 'Mozilla/5.0 (compatible; YandexImageResizer/2.0)', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)',);
                    if (@file_exists(ENGINE_DIR . '/inc/plugins/files/browsers.txt')) $browsers = @file(ENGINE_DIR . '/inc/plugins/files/browsers.txt');
                    return $browsers[array_rand($browsers) ];
                }
                function image_path_build($matches = array()) {
                    global $link;
                    $host = $link['host'];
                    $path = $link['path'];
                    $a = '';
                    if (count($matches) > 2) list(, $a, $url) = $matches;
                    else $url = $matches[1];
                    $url = replace_url(str_replace("'", '%27', $url));
                    if (!(preg_match('#(http:\/\/|https:\/\/)#i', $url))) {
                        if ($url[1] == '.') {
                            $url = e_sub($url, 1, e_str($url));
                        }
                        if ($url[1] != '/') {
                            $url = '/' . $url;
                        }
                        return '[img' . $a . ']http://' . str_replace('//', '/', str_replace('/./', '/', str_replace('/../', '/', $host . $url))) . '[/img]';
                    }
                    return '[img' . $a . ']' . $url . '[/img]';
                }
                function thumb_path_build($matches = array()) {
                    global $link;
                    $host = $link['host'];
                    $path = $link['path'];
                    $a = '';
                    if (count($matches) > 2) list(, $a, $url) = $matches;
                    else $url = $matches[1];
                    $url = replace_url(str_replace("'", '%27', $url));
                    if (!(preg_match('#(http:\/\/|https:\/\/)#i', $url))) {
                        if ($url[1] == '.') {
                            $url = e_sub($url, 1, e_str($url));
                        }
                        if ($url[1] != '/') {
                            $url = '/' . $url;
                        }
                        return '[thumb' . $a . ']http://' . str_replace('//', '/', str_replace('/./', '/', str_replace('/../', '/', $host . $url))) . '[/thumb]';
                    }
                    return '[thumb' . $a . ']' . $url . '[/thumb]';
                }
                function url_path_build($url, $host) {
                    $link = parse_url($host);
                    return $url . '[url=' . $host . ']' . $link['host'] . '[/url]';
                }
                function full_path_build($url, $host = '', $path = '') {
                    $url = replace_url(str_replace("'", '%27', $url));
                    if (!(preg_match('#(http:\/\/|https:\/\/)#i', $url))) {
                        $urls = explode('/', $url);
                        if ($path != '') $paths = explode('/', $path);
                        if (e_sub($url, 0, 1) == './' and $paths[1] != '') $url = str_replace('./', '/' . $paths[1] . '/', $url);
                        if (e_sub($url, 0, 1) == '?') $url = $path . $url;
                        if ($url[1] == '.') {
                            $url = e_sub($url, 1, e_str($url));
                        }
                        if ($url[1] != '/') {
                            $url = '/' . $url;
                        }
                        return 'http://' . str_replace('//', '/', str_replace('/./', '/', str_replace('/../', '/', $host . $url)));
                    }
                    return $url;
                }
                unset($ndr[1], $ndr[3]);
                function unhtml($string) {
                    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
                    $trans_tbl = array_flip($trans_tbl);
                    return strtr($string, $trans_tbl);
                }
                function create_metategs($story) {
                    global $config, $db, $parse, $config_rss;
                    $story = rss_strip(htmlentities($story, ENT_QUOTES, $config['charset']));
                    $story = $parse->BB_Parse($parse->process($story), false);
                    $story = preg_replace("#\[img.*\](.+?)\[/img\]#is", '', $story);
                    $story = preg_replace("#\[thumb.*\](.+?)\[/thumb\]#is", '', $story);
                    if (intval($config_rss['keyword_count']) != 0) $keyword_count = $config_rss['keyword_count'];
                    else $keyword_count = 20;
                    $newarr = array();
                    $headers = array();
                    $quotes = array("'", "\"","`","	",'
','
',"
","
",'\\',"'",',','.','/','�','#',';',':','@','~','[',']','{','}','=','-','+',')','(','*','&','^','%',"$",'<','>','?','!','\"');
$fastquotes = array( "'","\"","`","","","",'\"',"'",'
','
','/',"\\",'{','}','[',']');
$story = preg_replace( " #\[hide\](.+?)\[/hide\]#is",'',$story );
                    $story = preg_replace("'\[attachment=(.*?)\]'si", '', $story);
                    $story = preg_replace("'\[skpipt.*?\]'si", '', $story);
                    $story = preg_replace("'\[page=(.*?)\](.*?)\[/page\]'si", '', $story);
                    $story = str_replace('{PAGEBREAK}', '', $story);
                    $story = str_replace('&nbsp;', ' ', $story);
                    $story = str_replace('<br />', ' ', $story);
                    $story = trim(strip_tags($story));
                    if (trim($_REQUEST['descr']) != '') {
                        $headers['description'] = $db->safesql(e_sub(strip_tags(stripslashes($_REQUEST['descr'])), 0, 190));
                    } else {
                        $story = str_replace($fastquotes, '', $story);
                        $headers['description'] = $db->safesql(e_sub($story, 0, 190));
                    }
                    if (trim($_REQUEST['keywords']) != '') {
                        $headers['keywords'] = $db->safesql(str_replace($fastquotes, ' ', strip_tags(stripslashes($_REQUEST['keywords']))));
                    } else {
                        $story = str_replace($quotes, ' ', $story);
                        $story = str_replace('  ', ' ', $story);
                        $arr = explode(' ', $story);
                        foreach ($arr as $word) {
                            if (!(in_array($word, $newarr)) and e_str($word) > 4) $newarr[] = $word;
                        }
                        $arr = array_count_values($newarr);
                        asort($arr);
                        $arr = array_reverse($arr, true);
                        $arr = array_keys($arr);
                        $total = count($arr);
                        $offset = 0;
                        $arr = array_slice($arr, $offset, $keyword_count);
                        $headers['keywords'] = $db->safesql(implode(', ', $arr));
                    }
                    return $headers;
                }
                $js_array[] = 'engine/skins/grabber/js/dle_ajax.js';
                if ($config['version_id'] > '10.0') $js_array[] = '<link rel="stylesheet" href="engine/skins/grabber/css/jquery-ui.css" />';
                function progress($clientp, $dltotal, $dlnow, $ultotal, $ulnow) {
                    echo "$clientp, $dltotal, $dlnow, $ultotal, $ulnow";
                    return (0);
                }
                function get_full($scheme, $host, $path = '', $query = '', $others = '', $proxy = 0, $pass = 0, $coc = 0) {
                    global $config_rss, $config, $rss_plugins;
                    if ($host == "vk.com" and file_exists($rss_plugins . 'include/vk.php')) {
                        include $rss_plugins . 'include/vk.php';
                        return $result;
                    }
                    $cookie_file = ENGINE_DIR . '/cache/system/' . $host . '.txt';
                    if (function_exists('curl_init')) {
                        if (!(preg_match('#(http:\/\/|https:\/\/)#i', $host))) $url = trim($scheme . $host . $path . '?' . $query, '?');
                        else $url = trim($host);
                        if (preg_match('#google#', $url)) $url = preg_replace('#.*url=(.*)&.*#', "", $url);
                        if ($others != '' and $pass == 1 and $coc == 1) {
                            preg_match('#url_aut=(.+?);#i', $others, $mat);
                            if ($mat[1] != '') {
                                if (!(preg_match('#(http:\/\/|https:\/\/)#i', $mat[1]))) $url_aut = $scheme . $mat[1];
                                else $url_aut = trim($mat[1]);
                                $others = str_replace('url_aut=' . $mat[1] . '; ', '', $others);
                            } else {
                                $url_aut = $host;
                            }
                            $others = str_replace('redirect=index.php', 'redirect=' . $url, $others);
                            $fg = str_replace('; ', '&', $others);
                            $result = curl_autoriz($url_aut, $fg, $cookie_file, $url);
                            return $result;
                        }
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        if ($proxy == 1) {
                            if ($config_rss['proxy_file'] == 'yes' or $config_rss['proxy'] == '') {
                                $proxy_url = @file(ENGINE_DIR . '/inc/plugins/files/proxy.txt');
                                $proxy_url = $proxy_url[array_rand($proxy_url) ];
                            } else {
                                $proxy_url = $config_rss['proxy'];
                            }
                            if (trim($proxy_url) != '') curl_setopt($ch, CURLOPT_PROXY, trim($proxy_url));
                        }
                        curl_setopt($ch, CURLOPT_USERAGENT, get_random_agent());
                        curl_setopt($ch, CURLOPT_REFERER, $scheme . $host);
                        if (preg_match('#(https)#i', $scheme)) {
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        }
                        if ($others != '' and $pass == 0) curl_setopt($ch, CURLOPT_COOKIE, $others);
                        curl_setopt($ch, CURLOPT_ENCODING, '');
                        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                        if ($others != '' and $pass == 1) {
                            if (@file_exists($cookie_file)) $time = time() - filectime($cookie_file);
                            else $time = time();
                            if ($time >= 1200) {
                                preg_match('#url_aut=(.+?);#i', $others, $mat);
                                if ($mat[1] != '') {
                                    if (!(preg_match('#(http:\/\/|https:\/\/)#i', $mat[1]))) $url_aut = $scheme . $mat[1];
                                    else $url_aut = trim($mat[1]);
                                    $others = str_replace('url_aut=' . $mat[1] . '; ', '', $others);
                                } else {
                                    $url_aut = $scheme . $host;
                                }
                                $others = str_replace('redirect=index.php', 'redirect=' . $url, $others);
                                $fg = str_replace('; ', '&', $others);
                                $result = curl_autoriz($url_aut, $fg, $cookie_file, $url);
                            }
                        } else {
                            if (@file_exists($cookie_file)) $time = time() - filectime($cookie_file);
                            else $time = time();
                            if ($time >= 1200) {
                                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
                            }
                        }
                        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
                        if (!@ini_get('safe_mode') and !@ini_get('open_basedir')) {
                            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($ch);
                        } else {
                            $data = curl_redir_ex($ch);
                        }
                        if (curl_error($ch) and $_GET['c']) {
                            echo "<br>

cURL error:" . curl_error($ch);
                            echo "<br>

cURL error:" . curl_errno($ch);
                        }
                        curl_close($ch);
                        if ($_GET['c']) echo '<textarea style="width:100%;height:240px;">' . @htmlspecialchars($data, ENT_QUOTES, $config['charset']) . '</textarea>';
                        if (preg_match("#document.cookie='esteq_ddos_intercepter#i", $data)) {
                            preg_match("#document.cookie='(.+?)'#i", $data, $others);
                            if ($others[1] != '') $data = get_full($scheme, $host, $path, $query, $others[1], $proxy, $pass, $coc);
                        }
                        if (trim($data) != '' and $config_rss['get_prox']) return $data;
                    }
                    if (!function_exists('curl_init') or trim($data) == '') {
                        if (@file_exists(ENGINE_DIR . '/inc/plugins/Snoopy.class.php')) include_once ENGINE_DIR . '/inc/plugins/Snoopy.class.php';
                        else include_once ENGINE_DIR . '/inc/plugins/snoopy.class.php';
                        $snp = new Snoopy();
                        $snp->host = $host;
                        $snp->agent = get_random_agent();
                        $snp->cookies = array();
                        $other = array();
                        $other = explode('; ', $others);
                        foreach ($other as $value) {
                            $othern = explode('=', $value);
                            $snp->cookies[$othern[0]] = $othern[1];
                        }
                        @$snp->fetch(trim($scheme . $host . $path . '?' . $query, '?'));
                        $data = $snp->results;
                        if ($_GET['c']) echo '<textarea style="width:100%;height:240px;">' . @htmlspecialchars($data, ENT_QUOTES, $config['charset']) . '</textarea>';
                        if (trim($data) != '' and $config_rss['get_prox']) return $data;
                    }
                }
                function curl_autoriz($url, $fg, $cookie_file, $ref) {
                    global $config;
                    $lin = get_urls($url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0');
                    if (preg_match('#(https:\/\/)#i', $url)) {
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    }
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_REFERER, $ref);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fg);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
                    if (!@ini_get('safe_mode') and !@ini_get('open_basedir')) {
                        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                    } else {
                        $result = curl_redir_ex($ch);
                    }
                    if ($_GET['c']) echo '<textarea style="width:100%;height:240px;">' . @htmlspecialchars($result, ENT_QUOTES, $config['charset']) . '</textarea>';
                    curl_close($ch);
                    return $result;
                }
                function reset_url($url) {
                    $value = str_replace('http://', '', $url);
                    $value = str_replace('https://', '', $value);
                    $value = str_replace('www.', '', $value);
                    $value = explode('/', $value);
                    return reset($value);
                }
                function reset_urlk($url) {
                    $value = str_replace('http://', '', $url);
                    $value = str_replace('https://', '', $value);
                    $value = str_replace('www.', '', $value);
                    return $value;
                }
                function get_xfields($content1, $content0, $template, $xar = array(), $full = '') {
                    global $config_rss;
                    $xfields = array();
                    $xfi = array();
                    $ds = explode('|||', $template);
                    foreach ($ds as $value => $key) {
                        $xf = array();
                        $xf = explode('==', $key);
                        if (empty($xf[11])) $content = $content1;
                        else $content = $full;
                        if (!array_key_exists($xf[0], $xar)) {
                            if ($xf[3] == 0) $xfi = get_xfields_news($content, $xf[1]);
                            else $xfi = get_xfields_news($content0, $xf[1]);
                            if (count($xfi) != 0) {
                                if ($xf[2] == 1) $xfields[$xf[0]] = $xfi[1];
                                else $xfields[$xf[0]] = $xfi[0];
                            }
                            if ($xf[0] == "cena" and intval($config_rss['kurs_r']) != 0) {
                                $xfields[$xf[0]] = (preg_replace("![

	\s]+!s", '', $xfields[$xf[0]]) + $config_rss['nats_r']) * $config_rss['kurs_r'];
                                $xfields[$xf[0]] = $xfields[$xf[0]] * $config_rss['prots_r'] / 100 + $xfields[$xf[0]];
                                $xfields[$xf[0]] = sprintf("%01.0f", $xfields[$xf[0]]);
                            }
                            if ($xf[9] == 0) {
                                if ($xf[3] == 0) $content = str_replace($xfi[0], '', $content);
                                else $content0 = str_replace($xfi[0], '', $content0);
                            }
                        } else {
                            $xfields[$xf[0]] = $xar[$xf[0]];
                        }
                    }
                    $xfields['content_story'] = empty($xf[11]) ? $content : $content1;
                    $xfields['content0_story'] = $content0;
                    return $xfields;
                }
                function get_xfields_news($content, $template) {
                    $template = addcslashes(stripslashes($template), "[]!-.#?*%+\()|");
                    $template = str_replace('{get}', '(.*)', $template);
                    $template = str_replace('{skip}', '.*', $template);
                    $template = preg_replace("![

	]!s", '', $template);
                    preg_match('!' . $template . '!iUs', $content, $found);
                    return $found;
                }
                function downs_host($matches = array()) {
                    global $dop_nast;
                    list(, $url, $world) = $matches;
                    $mode = $dop_nast[1];
                    $host = @file(ENGINE_DIR . '/inc/plugins/files/down_file.txt');
                    foreach ($host as $it) {
                        $it = addcslashes(stripslashes(trim($it)), '"[]!-.?*\()|/');
                        if (preg_match('!' . $it . '!i', $url)) {
                            if ($mode == 3) {
                                return $url;
                            } elseif ($mode == 2) {
                                return $world;
                            }
                        } elseif ($mode == 2) {
                            return $world;
                        }
                    }
                    return '[url=' . $url . ']' . $world . '[/url]';
                }
                function slected_lang($selected) {
                    global $lang_grabber;
                    $option = array('auto' => '', 'az' => '', 'sq' => '', 'en' => '', 'ar' => '', 'hy' => '', 'af' => '', 'eu' => '', 'be' => '', 'bn' => '', 'bg' => '', 'bs' => '', 'cy' => '', 'hu' => '', 'vi' => '', 'gl' => '', 'nl' => '', 'el' => '', 'ka' => '', 'gu' => '', 'da' => '', 'iw' => '', 'yi' => '', 'id' => '', 'ga' => '', 'is' => '', 'es' => '', 'it' => '', 'kn' => '', 'ca' => '', 'zh-CN' => '', 'ko' => '', 'ht' => '', 'km' => '', 'lo' => '', 'la' => '', 'lv' => '', 'lt' => '', 'mk' => '', 'ms' => '', 'mt' => '', 'mr' => '', 'de' => '', 'no' => '', 'fa' => '', 'pl' => '', 'pt' => '', 'ro' => '', 'ru' => '', 'ceb' => '', 'sr' => '', 'sk' => '', 'sl' => '', 'sw' => '', 'tl' => '', 'th' => '', 'ta' => '', 'te' => '', 'tr' => '', 'uk' => '', 'ur' => '', 'fi' => '', 'fr' => '', 'hi' => '', 'hmn' => '', 'hr' => '', 'cs' => '', 'sv' => '', 'eo' => '', 'et' => '', 'jw' => '', 'ja' => '',);
                    $options = array('' => $lang_grabber['select_lang']);
                    foreach ($option as $keys => $values) {
                        if (array_key_exists($keys, $lang_grabber)) $options[$keys] = $lang_grabber[$keys];
                    }
                    foreach ($options as $value => $description) {
                        $output.= "<option value=\"$value\"";
                        if ($selected == $value) {
                            $output.= ' selected ';
                        }
                        if ($value == 'ru') {
                            $output.= ' style="color:blue" ';
                        } elseif ($value == 'en') {
                            $output.= ' style="color:green" ';
                        } else {
                            $output.= ' style="color:red" ';
                        }
                        $output.= ">$description</option>
";
                    }
                    return $output;
                }
                function translate_google($text, $in, $out) {
                    global $config, $config_rss;
                    $story = $text;
                    $translate_pos = 0;
                    $translate_result = '';
                    $text = str_replace("
", '<br />', $text);
                    $noss = array();
                    $nosss = array();
                    $noszs = array();
                    $nossr = array();
                    $nosssr = array();
                    $noszsr = array();
                    $nossrb = array();
                    $nosssrb = array();
                    $noszsrb = array();
                    $text = rss_strip($text);
                    preg_match_all('!(<.+?>)!is', $text, $htmlreps);
                    preg_match_all("#(<img.*?src[=]?[='\"](\S+?)['\" ].*?>)#is", $s_text, $bbrep);
                    preg_match_all('#\[img.*?\](.+?)\[\/img\]#i', $text, $bbrep);
                    preg_match_all("#(\[.*?\])#is", $text, $bbreps);
                    foreach ($bbrep[1] as $key => $value) {
                        $noss[' q1q1q1' . $key . '. '] = $value;
                        $nossr['q1q1q1' . $key . '.'] = $value;
                        $nossrb['q1q1q1' . $key] = $value;
                    }
                    if (count($noss) != '') $text = strtr($text, array_flip($noss));
                    foreach ($bbreps[0] as $key => $value) {
                        $nosss[' w2w2w2' . $key . '. '] = $value;
                        $nosssr['w2w2w2' . $key . '.'] = $value;
                        $nosssrb['w2w2w2' . $key] = $value;
                    }
                    if (count($nosss) != '') $text = strtr($text, array_flip($nosss));
                    foreach ($htmlreps[1] as $key => $value) {
                        $noszs[' 3r3r3' . $key . '. '] = $value;
                        $noszsr['3r3r3' . $key . '.'] = $value;
                        $noszsrb['3r3r3' . $key] = $value;
                    }
                    if (count($noszs) != '') $text = strtr($text, array_flip($noszs));
                    while (e_str(trim($text)) > 0) {
                        if (e_str($text) < 10000) {
                            $translate_pos = 10000;
                            if ($out == 'yan_dex') {
                                $translate_result.= yandex_api(e_sub($text, 0, $translate_pos), $in);
                            } else {
                                if (preg_match("#http\:\/\/translate\.google#i", $config_rss['google']) or e_str($config_rss['google']) != 39) {
                                    $translate_result.= translate(e_sub($text, 0, $translate_pos), $in, $out);
                                } else {
                                    $translate_result.= translate_api(e_sub($text, 0, $translate_pos), $in, $out);
                                }
                            }
                            $text = '';
                        } else {
                            $translate_pos = strrpos(e_sub($text, 0, 10000), ' ');
                            if ($out == 'yan_dex') {
                                $translate_result.= yandex_api(e_sub($text, 0, $translate_pos), $in);
                            } else {
                                if (preg_match("#http\:\/\/translate\.google#i", $config_rss['google']) or e_str($config_rss['google']) != 39) {
                                    $translate_result.= translate(e_sub($text, 0, $translate_pos), $in, $out);
                                } else {
                                    $translate_result.= translate_api(e_sub($text, 0, $translate_pos), $in, $out);
                                }
                            }
                            $text = e_sub($text, $translate_pos);
                        }
                    }
                    $translate_result = rss_strip($translate_result);
                    $translate_result = str_replace('{ ', "{", $translate_result);
                    $translate_result = str_replace(' }', "}", $translate_result);
                    $translate_result = preg_replace("#(\d),#i", '', $translate_result);
                    $translate_result = preg_replace("#(\d+)[\s\.](\d+)[\s\.]?(\d+)#is", '', $translate_result);
                    if (count($noszsr) != '') $translate_result = strtr($translate_result, $noszsr);
                    if (count($nosssr) != '') $translate_result = strtr($translate_result, $nosssr);
                    if (count($noszr) != '') $translate_result = strtr($translate_result, $noszr);
                    if (count($nossr) != '') $translate_result = strtr($translate_result, $nossr);
                    if (count($noszsrb) != '') $translate_result = strtr($translate_result, $noszsrb);
                    if (count($nosssrb) != '') $translate_result = strtr($translate_result, $nosssrb);
                    $translate_result = preg_replace("#\.{2,}#is", '', $translate_result);
                    $translate_result = str_replace('/ ', '/', $translate_result);
                    $translate_result = str_replace('> <', '><', $translate_result);
                    $translate_result = str_replace('] ', ']', $translate_result);
                    $translate_result = str_replace(' [', '[', $translate_result);
                    $translate_result = str_replace('[ ', '[', $translate_result);
                    $translate_result = str_replace('][', '] [', $translate_result);
                    $translate_result = str_replace('/ ', '/', $translate_result);
                    $translate_result = str_replace('<br />', "
", $translate_result);
                    if (trim($translate_result) != '') {
                        return html_entity_decode(stripslashes($translate_result));
                    } else {
                        return $story;
                    }
                }
                function translate($text, $s_lang, $d_lang) {
                    global $config, $config_rss;
                    if ($config['charset'] != 'utf-8') $text = @iconv($config['charset'], 'utf-8//TRANSLIT//IGNORE', $text);
                    $fg = array('js' => 'n', 'rev' => '_t', 'hl' => 'ru', 'ie' => 'UTF-8', 'sa' => 'N', 'tab' => 'wT', 'layout' => '1', 'eotf' => '1',);
                    $fg['text'] = $text;
                    $fg['sl'] = $s_lang;
                    $fg['tl'] = $d_lang;
                    $i_control = new image_controller();
                    $result = $i_control->download_host((preg_match("#translate\.google#i", $config_rss['google']) ? $config_rss['google'] : 'https://translate.google.com/m'), http_build_query($fg));
                    preg_match('!<div dir="ltr" class="t0">(.*?)</div><form!is', $result, $tran);
                    if ($tran[1] == '') preg_match('!<span id=result_box class="long_text">(.*?)</span></div></div>!is', $result, $tran);
                    $tran[1] = preg_replace('!<span title=.*?>(.*?)</span>!is', "", $tran[1]);
                    if ($config['charset'] != 'utf-8') $text = @iconv('utf-8', $config['charset'] . '//TRANSLIT//IGNORE', $tran[1]);
                    else $text = $tran[1];
                    return $text;
                }
                function translate_api($text, $s_lang, $d_lang) {
                    global $config, $config_rss;
                    if ($config['charset'] != 'utf-8') $text = @iconv($config['charset'], 'utf-8//TRANSLIT//IGNORE', $text);
                    $post_data['key'] = $config_rss['google'];
                    $post_data['q'] = $text;
                    $post_data['source'] = $s_lang;
                    $post_data['target'] = $d_lang;
                    $post_data['format'] = 'html';
                    $i_control = new image_controller();
                    $result = $i_control->download_host("https://www.googleapis.com/language/translate/v2", http_build_query($post_data), true);
                    $json = json_decode($result, true);
                    if (count($json['data']['translations'])) {
                        foreach ($json['data']['translations'] as $tet) {
                            $respons.= $tet['translatedText'];
                        }
                    }
                    if ($config['charset'] != 'utf-8') $text = @iconv('utf-8', $config['charset'] . '//TRANSLIT//IGNORE', $respons);
                    else $text = $respons;
                    return $text;
                }
                function url_i($data = array()) {
                    global $fg;
                    $k = array_rand($fg);
                    return '$' . strtr($data[1], $fg[$k], $k);
                }
                function strip_gog($url) {
                    $url = preg_replace('#[ ]+#', '', $url);
                    return strtolower($url);
                }
                function strip_br($txt) {
                    $txt = str_replace('<br>', "
", $txt);
                    $txt = str_replace('<br />', "
", $txt);
                    $txt = str_replace('<BR>', "
", $txt);
                    $txt = str_replace('<BR />', "
", $txt);
                    return $txt;
                }
                function news_sort_rss($do, $sor) {
                    global $lang_grabber;
                    if (!$do) $do = 'xpos';
                    $find_sort = 'rss_sort_' . $do;
                    $direction_sort = 'rss_direction_' . $do;
                    $find_sort = str_replace('.', '', $find_sort);
                    $direction_sort = str_replace('.', '', $direction_sort);
                    $sort = array();
                    $allowed_sort = array('xpos', 'rss', 'allow_auto', 'title', 'id');
                    $soft_by_array = array('xpos' => array('name' => '&#8470;', 'value' => 'xpos', 'direction' => 'desc', 'image' => '', 'width' => '5%'), 'rss' => array('name' => $lang_grabber['vid'], 'value' => 'rss', 'direction' => 'desc', 'image' => '', 'width' => '5%'), 'allow_auto' => array('name' => $lang_grabber['auto'], 'value' => 'allow_auto', 'direction' => 'desc', 'image' => '', 'width' => '6%'), 'title' => array('name' => $lang_grabber['name_canal'], 'value' => 'title', 'direction' => 'desc', 'image' => '', 'width' => '40%'), 'xdescr' => array('name' => $lang_grabber['rss_description'], 'value' => 'xdescr', 'direction' => 'desc', 'image' => '', 'width' => '40%'),);
                    if (strtolower($sor) == 'asc') {
                        $soft_by_array[$do]['image'] = "<img src=\"engine/skins/grabber/cssasc.gif\" alt=\"\" />";
                        $soft_by_array[$do]['direction'] = 'desc';
                    } else {
                        $soft_by_array[$do]['image'] = "<img src=\"engine/skins/grabber/cssdesc.gif\" alt=\"\" />";
                        $soft_by_array[$do]['direction'] = 'asc';
                    }
                    foreach ($soft_by_array as $value) {
                        $sort[] = '<th width="' . $value['width'] . '" align="center" class="navigation" style="padding:4px">' . $value['image'] . "<a href=\"#\" onclick=\"dle_change_sort('{$value['value']}','{$value['direction']}'); return false;\">" . $value['name'] . '</a></th>';
                    }
                    $sort = "<form name=\"news_set_sort\" id=\"news_set_sort\" method=\"post\" action=\"\" ><table cellpadding=\"6\" align=\"center\" cellspacing=\"0\" width=\"100%\" border=\"0\"><tr>" . implode(' ', $sort);
                    $sort.= '	 <th width="4%" style="padding:4px"><input style="background-color: #ffffff; color: #ff0000;" type="checkbox" name="check_all" id="check_all" onclick="checkAll(document.rss_form.channel)" title="' . $lang_grabber['val_all'] . '"/></th>
	</tr>
	</table>';
                    $sort.= "<input type=\"hidden\" name=\"dlenewssortby\" id=\"dlenewssortby\" value=\"xpos\" />
<input type=\"hidden\" name=\"dledirection\" id=\"dledirection\" value=\"desc\" />
<input type=\"hidden\" name=\"set_new_sort\" id=\"set_new_sort\" value=\"{$find_sort}\" />
<input type=\"hidden\" name=\"set_direction_sort\" id=\"set_direction_sort\" value=\"{$direction_sort}\" />
<script type=\"text/javascript\" language=\"javascript\">
<!-- begin

function dle_change_sort(sort, direction){

  var frm = document.getElementById('news_set_sort');

  frm.dlenewssortby.value=sort;
  frm.dledirection.value=direction;

  frm.submit();
  return false;
};

// end -->
</script></form>";
                    $_SESSION[$direction_sort] = $soft_by_array[$do]['direction'];
                    $_SESSION[$find_sort] = $soft_by;
                    return $sort;
                }
                function curl_redir_ex($ch) {
                    static $curl_loops = 0;
                    static $curl_max_loops = 20;
                    if ($curl_loops++ >= $curl_max_loops) {
                        $curl_loops = 0;
                        return FALSE;
                    }
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($ch);
                    $http_code = array();
                    $http_code = curl_getinfo($ch);
                    list($header, $data) = explode("

", $data, 2);
                    if ($http_code['http_code'] == 301 || $http_code['http_code'] == 302) {
                        $matches = array();
                        preg_match('/Location:(.*)/', $header, $matches);
                        $url = @parse_url(trim(array_pop($matches)));
                        if (!$url) {
                            $curl_loops = 0;
                            return $data;
                        }
                        $last_url = parse_url($http_code['url']);
                        if (!$url['scheme']) $url['scheme'] = $last_url['scheme'];
                        if (!$url['host']) $url['host'] = $last_url['host'];
                        if (!$url['path']) $url['path'] = $last_url['path'];
                        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query'] ? '?' . $url['query'] : '');
                        curl_setopt($ch, CURLOPT_URL, $new_url);
                        return curl_redir_ex($ch);
                    } else {
                        $curl_loops = 0;
                        return $data;
                    }
                }
                if (!function_exists('json_decode')) {
                    include ('json.php');
                    function json_decode($data, $bool) {
                        if ($bool) {
                            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
                        } else {
                            $json = new Services_JSON();
                        }
                        return ($json->decode($data));
                    }
                }
                $options_host = array('0' => $lang_grabber['lang_donor'], 'serv' => $lang_grabber['lang_server'], 'clipkey' => 'clipkey.ru', 'wwwpix' => '10pix.ru', 'immage' => 'immage.de', 'imageshack' => 'imageshack.us', 'tinypic' => 'tinypic.com', 'ambrybox' => 'ambrybox.com', 'epikz' => 'epikz.net', 'shituf' => 'shituf.org', 'fastpic' => 'fastpic.ru', 'fotonons' => 'fotonons.ru', 'picp2' => 'picp2.com');
                class Thread {
                    function RegisterPID($pidFile) {
                        if ($fp = fopen($pidFile, 'w')) {
                            fwrite($fp, getmypid());
                            fclose($fp);
                            chmod_file($pidFile);
                            return true;
                        }
                        return false;
                    }
                    function CheckPIDExistance($pidFile) {
                        if ($PID = @file_get_contents($pidFile)) {
                            if (posix_kill($PID, 0)) return true;
                        }
                        return false;
                    }
                    function KillPid($pidFile) {
                        if ($PID = @file_get_contents($PIDFile)) if (posix_kill($PID, 0)) exec("kill -9 {$PID}");
                    }
                }
                function utf2win($str, $type = "w") {
                    static $conv = '';
                    if (!is_array($conv)) {
                        $conv = array();
                        for ($x = 128;$x <= 143;$x++) {
                            $conv['u'][] = chr(209) . chr($x);
                            $conv['w'][] = chr($x + 112);
                        }
                        for ($x = 144;$x <= 191;$x++) {
                            $conv['u'][] = chr(208) . chr($x);
                            $conv['w'][] = chr($x + 48);
                        }
                        $conv['u'][] = chr(208) . chr(129);
                        $conv['w'][] = chr(168);
                        $conv['u'][] = chr(209) . chr(145);
                        $conv['w'][] = chr(184);
                        $conv['u'][] = chr(208) . chr(135);
                        $conv['w'][] = chr(175);
                        $conv['u'][] = chr(209) . chr(151);
                        $conv['w'][] = chr(191);
                        $conv['u'][] = chr(208) . chr(134);
                        $conv['w'][] = chr(178);
                        $conv['u'][] = chr(209) . chr(150);
                        $conv['w'][] = chr(179);
                        $conv['u'][] = chr(210) . chr(144);
                        $conv['w'][] = chr(165);
                        $conv['u'][] = chr(210) . chr(145);
                        $conv['w'][] = chr(180);
                        $conv['u'][] = chr(208) . chr(132);
                        $conv['w'][] = chr(170);
                        $conv['u'][] = chr(209) . chr(148);
                        $conv['w'][] = chr(186);
                        $conv['u'][] = chr(226) . chr(132) . chr(150);
                        $conv['w'][] = chr(185);
                    }
                    $str = utf2win_($str, $type);
                    if ($type == 'w') {
                        return str_replace($conv['u'], $conv['w'], $str);
                    } elseif ($type == 'u') {
                        return str_replace($conv['w'], $conv['u'], $str);
                    } else {
                        return $str;
                    }
                }
                function utf2win_($txt, $type = "w") {
                    $in_arr = array(chr(208), chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), chr(168), chr(198), chr(199), chr(200), chr(201), chr(202), chr(203), chr(204), chr(205), chr(206), chr(207), chr(209), chr(210), chr(211), chr(212), chr(213), chr(214), chr(215), chr(216), chr(217), chr(218), chr(219), chr(220), chr(221), chr(222), chr(223), chr(224), chr(225), chr(226), chr(227), chr(228), chr(229), chr(184), chr(230), chr(231), chr(232), chr(233), chr(234), chr(235), chr(236), chr(237), chr(238), chr(239), chr(240), chr(241), chr(242), chr(243), chr(244), chr(245), chr(246), chr(247), chr(248), chr(249), chr(250), chr(251), chr(252), chr(253), chr(254), chr(255));
                    $out_arr = array(chr(208) . chr(160), chr(208) . chr(144), chr(208) . chr(145), chr(208) . chr(146), chr(208) . chr(147), chr(208) . chr(148), chr(208) . chr(149), chr(208) . chr(129), chr(208) . chr(150), chr(208) . chr(151), chr(208) . chr(152), chr(208) . chr(153), chr(208) . chr(154), chr(208) . chr(155), chr(208) . chr(156), chr(208) . chr(157), chr(208) . chr(158), chr(208) . chr(159), chr(208) . chr(161), chr(208) . chr(162), chr(208) . chr(163), chr(208) . chr(164), chr(208) . chr(165), chr(208) . chr(166), chr(208) . chr(167), chr(208) . chr(168), chr(208) . chr(169), chr(208) . chr(170), chr(208) . chr(171), chr(208) . chr(172), chr(208) . chr(173), chr(208) . chr(174), chr(208) . chr(175), chr(208) . chr(176), chr(208) . chr(177), chr(208) . chr(178), chr(208) . chr(179), chr(208) . chr(180), chr(208) . chr(181), chr(209) . chr(145), chr(208) . chr(182), chr(208) . chr(183), chr(208) . chr(184), chr(208) . chr(185), chr(208) . chr(186), chr(208) . chr(187), chr(208) . chr(188), chr(208) . chr(189), chr(208) . chr(190), chr(208) . chr(191), chr(209) . chr(128), chr(209) . chr(129), chr(209) . chr(130), chr(209) . chr(131), chr(209) . chr(132), chr(209) . chr(133), chr(209) . chr(134), chr(209) . chr(135), chr(209) . chr(136), chr(209) . chr(137), chr(209) . chr(138), chr(209) . chr(139), chr(209) . chr(140), chr(209) . chr(141), chr(209) . chr(142), chr(209) . chr(143));
                    if ($type == 'u') {
                        return str_replace($in_arr, $out_arr, $txt);
                    } elseif ($type == 'w') {
                        return str_replace($out_arr, $in_arr, $txt);
                    } else {
                        return $txt;
                    }
                }
                function lang_yan($selected) {
                    global $lang_grabber;
                    $option = array("ar", "az", "be", "bg", "bs", "ca", "cs", "da", "de", "el", "en", "es", "et", "fi", "fr", "he", "hr", "hu", "hy", "id", "is", "it", "ka", "lt", "lv", "mk", "ms", "mt", "nl", "no", "pl", "pt", "ro", "ru", "sk", "sl", "sq", "sr", "sv", "tr", "uk", "vi");
                    $options = array('' => $lang_grabber['select_lang']);
                    foreach ($option as $values) {
                        list($in, $out) = explode("-", $values);
                        if (array_key_exists($in, $lang_grabber) and array_key_exists($out, $lang_grabber)) $options[$values] = $lang_grabber[$in];
                    }
                    foreach ($options as $value => $description) {
                        $output.= "<option value=\"$value\"";
                        if ($selected == $value) {
                            $output.= ' selected ';
                        }
                        $output.= ">$description</option>
";
                    }
                    return $output;
                }
                function yandex_api($text, $s_lang) {
                    global $config, $config_rss;
                    if ($config['charset'] != 'utf-8') $text = @iconv($config['charset'], 'utf-8//TRANSLIT//IGNORE', $text);
                    $yp['lang'] = $s_lang;
                    $yp['text'] = $text;
                    $yp['key'] = $config_rss['yandex_key'];
                    $yp['format'] = 'html';
                    $i_control = new image_controller();
                    $result = $i_control->download_host('https://translate.yandex.net/api/v1.5/tr.json/translate?', http_build_query($yp), true);
                    $json = json_decode($result, true);
                    if ($json['code'] == '200') {
                        foreach ($json['text'] as $tet) {
                            $respons.= $tet;
                        }
                    } else {
                        echo '<b>' . $json['message'] . '</b><br />';
                    }
                    if ($config['charset'] != 'utf-8') $text = @iconv('utf-8', $config['charset'] . '//TRANSLIT//IGNORE', $respons);
                    else $text = $respons;
                    return $text;
                }
                function e_str($value) {
                    global $config;
                    if (strtolower($config['charset']) == "utf-8") return strlen(utf8_decode($value));
                    else return strlen($value);
                }
                function e_sub($str, $start, $length = '') {
                    global $config;
                    if ($length == '') $length = e_str($str);
                    if (strtolower($config['charset']) == "utf-8") return iconv_substr($str, $start, $length, "utf-8");
                    else return substr($str, $start, $length);
                }
                function e_pos($str, $needle) {
                    global $config;
                    if (strtolower($config['charset']) == "utf-8") return iconv_strpos($str, $needle, "utf-8");
                    else return strpos($str, $needle);
                }
                function script_br($matches = array()) {
                    list(, $scr, $txt) = $matches;
                    $txt = str_replace('<br>', "
", $txt);
                    $txt = str_replace('<br />', "
", $txt);
                    $txt = str_replace('<BR>', "
", $txt);
                    $txt = str_replace('<BR />', "
", $txt);
                    return stripslashes("<script" . $scr . ">" . $txt . "</script>");
                }
                function str_br($full_story) {
                    global $db;
                    $full_story = trim(preg_replace('/[
	]+/', ' ', $full_story));
                    $full_story = trim(preg_replace("#(<br \/>|<br>)\s+(\S)#", '', $full_story));
                    $full_story = trim(preg_replace('/\s+/', ' ', $full_story));
                    return stripslashes($full_story);
                };
                