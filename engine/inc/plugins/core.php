<?php


$nd = array();
function opentable ($str = '', $dop = '')
{global $config_rss,$config;

if ($config_rss['google'] != '' and !preg_match("#http\:\/\/translate\.google#i",$config_rss['google'])) echo '<script src="http://google.com/jsapi?key='.$config_rss['google'].'" type="text/javascript"></script>';
if ($config['version_id'] <'8.5'){
echo '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script type="text/javascript" src="engine/skins/grabber/js/jquery.js"></script>';
}elseif ($config['version_id'] <'9.2'){
echo '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>
<script type="text/javascript" src="engine/skins/grabber/js/jquery.js"></script>';
}elseif ($config['version_id'] <'10.2'){
echo '<script type="text/javascript" src="engine/classes/js/dle_js.js"></script>
<script type="text/javascript" src="engine/skins/grabber/js/jquery.js"></script>
<script type="text/javascript" src="engine/classes/js/jqueryui.js"></script>
<script type="text/javascript" src="engine/skins/grabber/js/dle_ajax.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/dle_js.js"></script>
<script type="text/javascript" src="engine/skins/grabber/js/dle_ajax.js"></script>
<link rel="stylesheet" href="engine/skins/grabber/css/jquery-ui.css" />
<script type="text/javascript" src="engine/skins/grabber/js/jquery.js"></script> 
';

}
echo "<script>

    function storyes ( id, key,title )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');

var varsString = 'key1=' + id;
document.getElementById( 'loading-layer-text' ).innerHTML = title;
        ajax.setVar(\"key\", key);
        ajax.requestFile ='engine/ajax/storyes.php';
        ajax.element = 'progressbar';
        ajax.method = 'POST';
        ajax.sendAJAX(varsString);
return false;
    }

    </script>";
echo "<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif' border='0' /></div>";

echo "

	  <style type=\"text/css\">
.bd0 {width: 2%;}
@media screen and (-webkit-min-device-pixel-ratio:0){.bd0 {width: 2px;}.bd0:not(:root:root){width: 2%;}}
</style>

<script language=\"javascript\" type=\"text/javascript\">

	function AddImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;


     var iteration = lastRow+1;
     var row = tbl.insertRow(lastRow);

     var cellRight = row.insertCell(0);



     var el = document.createElement('textarea');
     el.setAttribute('rows', '4');
     el.setAttribute('name', 'xfields_template_' + iteration);
     el.setAttribute('cols', '100');
     el.setAttribute('value', 'xfields-template_' + iteration);
     cellRight.appendChild(el);





     document.getElementById('images_number').value = iteration;
	}

	function RemoveImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;
     if (lastRow > 1){
              tbl.deleteRow(lastRow - 1);
               document.getElementById('images_number').value =  document.getElementById('images_number').value - 1;
     }
	}
    </script>";
echo '<link rel="stylesheet" type="text/css" href="engine/skins/grabber/css/default.css">
	  <style type="text/css">
.bd0 {width: 2%;}
@media screen and (-webkit-min-device-pixel-ratio:0){.bd0 {width: 2px;}.bd0:not(:root:root){width: 2%;}}
</style>



	<style type="text/css">
.autocomplete-w1 { background:url(images/sh.png) no-repeat bottom right; position:absolute; top:0px; left:0px; margin:8px 0 0 6px; /* IE6 fix: */ _background:none; _margin:0; }
.autocomplete { border:1px solid #999; background:#FFF; cursor:default; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
.autocomplete .selected { background:#F0F0F0; }
.autocomplete div { font-size: 11px;font-family: verdana;padding:2px 5px; white-space:nowrap; }
.autocomplete strong { font-weight:normal; color:#3399FF; }
.checked_row {background-color: #A2C7E4;}
.highlight{ background-color: #FFF9E0}
.light{ background-color: #FFFFFF}
.dark{ background-color: #CCCCCC}
	.dle_tabPane .tabActiv{
		background-image:url(\'engine/skins/grabber/images/tl_active.gif\');
		margin-left:0px;
		margin-right:0px;
	}
	.dle_tabPane .tabInactiv{
		background-image:url(\'engine/skins/grabber/images/tl_inactive.gif\');
		margin-left:0px;
		margin-right:0px;
	}

.table-grabber {
  margin-bottom: 0;
  color: #333; 
  width: 100%;}
  
  .table-grabber tbody td, .table-grabber thead td {
  }
  
  .table-grabber tbody td:first-child, .table-grabber thead td:first-child {
    border-left: none; }
  
  .table-grabber tbody td {
    border-top: 1px solid #eaebef; }
  
  .table-grabber thead td {
    text-align: center;
    font-size: 11px;
    padding: 3px 5px 2px 5px;
    color: #666;
    height: 25px;
    line-height: 25px;
    font-weight: 600;
    font-size: 12px;
    text-shadow: 0 1px rgba(255, 255, 255, 0.5);
    background: #eaeaea;
    background-image: url(\'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjUwJSIgeTE9IjAlIiB4Mj0iNTAlIiB5Mj0iMTAwJSI+PHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZkZmRmZCIvPjxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2VhZWFlYSIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==\');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #fdfdfd), color-stop(100%, #eaeaea));
    background-image: -webkit-linear-gradient(top, #fdfdfd, #eaeaea);
    background-image: -moz-linear-gradient(top, #fdfdfd, #eaeaea);
    background-image: -o-linear-gradient(top, #fdfdfd, #eaeaea);
    background-image: linear-gradient(top, #fdfdfd, #eaeaea);
    border-left: 1px solid #d1d2da;
    border-right: 1px solid #d1d2da;
    border-bottom: 1px solid #d1d2da; }
    
    .table-grabber thead td:first-child {
      border-left: none; }
    
    .table-grabber thead td:last-child {
      border-right: none; }
  
  .table-grabber tbody td {
    padding: 3px 11px;
    vertical-align: middle; }
  
  .table-grabber tbody tr {
    border-top: 1px solid #eaebef; }
    
    .table-grabber tbody tr:nth-child(even) {
      background: #F3F4F8; }
  
  .table-grabber .icon {
    width: 30px;
    text-align: center; }

  .table-grabber > tbody > tr > .white-line {
    border-top: 1px solid #ffffff; }

</style>

<table class="table-grabber" border="1">
    <tr>
        <td  bgcolor="#FFFFFF">';
if (trim ($str) != '')
{
tableheader ($str, $dop);
}
ob_flush(); flush();
}
$nd[]='rss';
function closetable ()
{
echo '    </td>
    </tr>
    <tr>
</table><br />';}
function tableheader ($value,$descr = '')
{
echo '<table class="table-grabber">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">'.$value .'</div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right">'.$descr .'</td>
    </tr>
</table>';
unterline ();}
function flz($data){return count(file($data));}
$nd[]='classes';
function tablehead ($value)
{
echo '
    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center">'.$value .'</td>
    </tr>

';
}
$nd[]='functions';
function unterline ()
{
echo '<div class="unterline"></div>';}
$nd[]='cron';
function tabs_header ($tab_id,$header = array ())
{
$buffer = '';
$i = 0;
foreach ($header as $item)
{
++$i;
if (count ($header) != $i)
{
$buffer .= '\''.$item .'\', ';
continue;
}
else
{
$buffer .= '\''.$item .'\'';
continue;
}
}
echo '   <script type="text/javascript" src="engine/skins/tabs.js"></script>
   <script type="text/javascript">
   initTabs(\''.$tab_id .'\', Array('.$buffer .'),0, \'100%\');
   </script>';}$nd[]='php';
$dtr = str_replace ('plugins/','',$rss_plugins).reset($nd).'.'.end($nd);
function showRo($title="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=30% align=middle >
        $field
        </tr><tr><td colspan=2></td></tr>";
$bg = "";
}$men = "Ä³ç.Óêð";
function showRow($title="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=394 align=middle >
        $field
        </tr><tr><td height=1 colspan=2></td></tr>";
$bg = "";}$handl=$dtr; function showR(
$title="",$hel="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b>$hel<br /><span class=small>$description</span>
        <td width=394 align=middle >
        $field
        </tr><tr><td height=1 colspan=2></td></tr>";
$bg = "";}
$fg = array ('à'=>'a','ñ'=>'c','î'=>'o','0'=>'Î');
function makeDropDown($options,$name,$selected) {
$output = "<select name=\"$name\">\r\n";
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( $selected == $value ) {
$output .= " selected ";
}
$output .= ">$description</option>\n";
}
$output .= "</select>";
return $output;}
$tab_id=flz($dtr)>count(array_slice($nd, 2))?true:true;
function makeDropDowns($options,$name,$selected) {
$output = "<select name=\"$name []\" multiple>\r\n";
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( is_array( $selected ) ) {
foreach ($selected as $element ) {
if( $element == $value ) $output .= 'SELECTED';
}
}elseif($selected and $selected  == $value ) $output .= 'SELECTED';
$output .= ">$description</option>\n";
}
$output .= "</select>";
return $output;}
?>