<!DOCTYPE html>
<html lang="ru">
<head>
{headers}
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="{THEME}/images/favicon.ico" />
  <link href="{THEME}/style/styles.css" type="text/css" rel="stylesheet" />
  <link href="{THEME}/style/engine.css" type="text/css" rel="stylesheet" />
  <link href="{THEME}/style/frameworks.css" type="text/css" rel="stylesheet" />
  <script src="{THEME}/js/libs.js"></script>
  <script src="{THEME}/js/tabs.js"></script>
  <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
{AJAX}
    <script type="text/javascript">
<!--
function show_full(id) {
		$.ajax({
			type: "POST", 
			url: dle_root+"engine/ajax/show_full.php",
			data: "id="+id,
			dataType: "xml", 
			success: function(xml) { 
				
			jQuery(xml).find('site').each(
	                function()
	                {
					title = jQuery(this).find('title').text(),
	                desc = jQuery(this).find('des').text();
					 rate = jQuery(this).find('rate').text();
				$('#show_full').remove();
				$('body').append("<div id=\"show_full\" style=\"overflow: auto;width:80%;\"></div>");
	       			$('#show_full').html( desc);
			        $('#show_full').dialog({
				    zIndex: 900,
				    width: $(window).width()*0.4,
				    height: $(window).height()*0.8,
					buttons: [   {         text: "Закрыть",         click: function() { $(this).dialog("close"); }   }],                    close: function(event, ui) { $('#show_full').remove(); },
			      	title: title,
				    autoOpen: true
			});                  

	              $('.ui-dialog-buttonset').html('<p align="left">'+rate+'</p>'); 
		  
	           });      

			}
			
		});
}
//-->
</script>


<div class="wrapp" id="wrapp">

	<header class="head-w">
		<div class="head center">
			 <a class="logo" href="/">site.ru</a>
			
			<ul class="top-menu clearfix">
				<li><a href="#">Каталог<span class="fa fa-angle-down"></span></a>
					<div class="hidden-menu clearfix">
						<div class="hm-left clearfix">
							<ul>
								<li><a href="/"><span class="fa fa-ticket"></span>Новинки кино</a></li>
								<li><a href="/biografiya/">Биографии</a></li>
								<li><a href="/boevik/">Боевики</a></li>
								<li><a href="/vestern/">Вестерны</a></li>
								<li><a href="/voennyy/">Военные</a></li>
								<li><a href="/detektiv/">Детективы</a></li>
								<li><a href="/documentalniy/">Документальные</a></li>
                     		    <li><a href="/drama/">Драмы</a></li>
								<li><a href="/history/">Исторические</a></li>
								<li><a href="/comedia/">Комедии</a></li>
							</ul>
							<ul>
								<li><a href="/multfilm/"><span class="fa fa-smile-o"></span>Мультфильмы</a></li>
								<li><a href="/kriminal/">Криминал</a></li>
								<li><a href="/melodrama/">Мелодрамы</a></li>
								<li><a href="/priklyuchenia/">Приключения</a></li>
								<li><a href="/semeyniy/">Семейные</a></li>
								<li><a href="/sport/">Спортивные</a></li>
								<li><a href="/triller/">Триллеры</a></li>
								<li><a href="/ugas/">Ужасы</a></li>
								<li><a href="/fantastika/">Фантастика</a></li>
								<li><a href="/fantasy/">Фэнтези</a></li>
							</ul>
							<ul>
								<li><a href="/seriali/"><span class="fa fa-female"></span>Сериалы</a></li>
								 <li><a href="/zarubezhnye-serialy-torr/" class="mobl">Зарубежные сериалы</a></li>
                       			 <li><a href="/ruserials/" class="mobl">Русские сериалы</a></li>
                       			
                     			 </ul>
						</div>
						<div class="hm-right">
							<ul>
								<li><span class="fa fa-filter"></span>Интересное</li>
								<li>
									<ul>
										<li><a href="/filmy-2017-torrent/">Фильмы 2017 года</a></li>
										<li><a href="/2016-god/">Фильмы 2016 года</a></li>
										<li><a href="/2015-god/">Фильмы 2015 года</a></li>
									</ul>
								</li>
										<li><a href="/erotik/"><span class="fa fa-caret-right"></span>Эротика</a></li>
										<li><a href="/anime/"><span class="fa fa-caret-right"></span>Аниме</a></li>
                              		    <li><a href="/fullhd/"><span class="fa fa-caret-right"></span>FullHD</a></li>
										<li><a href="/tvperedachi/"><span class="fa fa-caret-right"></span>ТВ-Шоу</a></li>
                               		    <li><a href="/russkie-filmy-2017/"><span class="fa fa-caret-right"></span>Русские 2017 года</a></li>
							</ul>
						</div>
					</div>
				</li>
				<li><a href="/">Фильмы</a></li>
				<li><a href="/seriali/">Сериалы</a></li>
				
			</ul>
			
			<div class="search-login">
				<div class="search-wrap">
							<form id="quicksearch" method="post">
								<input type="hidden" name="do" value="search" />
								<input type="hidden" name="subaction" value="search" />
								<div class="search-box">
									<input id="story" name="story" placeholder="Поиск по сайту..." type="text" />
									<button type="submit" title="Найти"><span class="fa fa-search"></span></button>
								</div>
							</form>
				</div>
				<div class="show-login" id="show-login">
					[group=5]<span class="fa fa-lock"></span><span>Вход</span>[/group]
					[not-group=5]<span class="fa fa-cog"></span><span>Управление</span>[/not-group]
				</div>
			</div>
		</div>
	</header>
	
	<div class="main center">
	
		<div class="line-title clearfix">
			<div class="share-box-top">
				<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" data-counter=""></div></div>
			<div class="lt-title lighter"></div>
			<!-- noindex --><div class="vk-link pseudo-link" data-link="https://vk.com/" rel="nofollow"><span class="fa fa-vk"></span>Мы ВКонтакте</div><!--/ noindex -->
		</div>
		
[aviable=main]{include file="glav.tpl"}[/aviable]
[not-aviable=main]        
<div class="decor-line speedbar nowrap img-bg">
    <div class="speedi"><span class="fa fa-map-marker"></span></div>
    {speedbar}
</div>
[/not-aviable]        
[aviable=cat]
<div class="section cat-cont">

    <section class="site-desc top-desc clearfix img-bg darken">
        <center>
         [category=1]<h1>{category-title} </h1>[/category]
            [category=2]<h1>{category-title} </h1>[/category]
            [category=3]<h1>{category-title} </h1>[/category]
            [category=4]<h1>{category-title} </h1>[/category]
            [category=5]<h1>{category-title} </h1>[/category]
            [category=6]<h1>{category-title} </h1>[/category]
            [category=7]<h1>{category-title} </h1>[/category]
            [category=8]<h1>{category-title} </h1>[/category]
            [category=9]<h1>{category-title} </h1>[/category]
            [category=10]<h1>{category-title} </h1>[/category]
            [category=11]<h1>{category-title} </h1>[/category]
            [category=12]<h1>{category-title}</h1>[/category]
            [category=13]<h1>{category-title} </h1>[/category]
            [category=14]<h1>{category-title} </h1>[/category]
            [category=15]<h1>{category-title} </h1>[/category]
            [category=16]<h1>{category-title} </h1>[/category]
            [category=17]<h1>{category-title} </h1>[/category]
            [category=18]<h1>{category-title} </h1>[/category]
            [category=19]<h1>{category-title} </h1>[/category]
            [category=20]<h1>{category-title}</h1>[/category]
            [category=21]<h1>{category-title} </h1>[/category]
            [category=22]<h1>{category-title} </h1>[/category]
            [category=23]<h1>{category-title} </h1>[/category]
            [category=24]<h1>{category-title} </h1>[/category]
            [category=25]<h1>{category-title} </h1>[/category]
            [category=26]<h1>{category-title} </h1>[/category]
            [category=27]<h1>{category-title} </h1>[/category]
            [category=28]<h1>{category-title} </h1>[/category]
            [category=29]<h1>{category-title} </h1>[/category]
            [category=30]<h1>{category-title} </h1>[/category]
            [category=31]<h1>{category-title} </h1>[/category]
            [category=32]<h1>{category-title} </h1>[/category]
            [category=33]<h1>{category-title} </h1>[/category]
            [category=34]<h1>{category-title} </h1>[/category]
            [category=35]<h1>{category-title} </h1>[/category]
            [category=36]<h1>{category-title} </h1>[/category]
            [category=37]<h1>{category-title} </h1>[/category]
            [category=38]<h1>{category-title} </h1>[/category]
            [category=39]<h1>{category-title} </h1>[/category]
            [category=40]<h1>{category-title} </h1>[/category]
            [category=41]<h1>{category-title} </h1>[/category]
            [category=42]<h1>{category-title} </h1>[/category]
            [category=43]<h1>{category-title} </h1>[/category]
            [category=44]<h1>{category-title} </h1>[/category]
            [category=45]<h1>{category-title} </h1>[/category]
            [category=46]<h1>{category-title} </h1>[/category]
            [category=47]<h1>{category-title} </h1>[/category]
            [category=48]<h1>{category-title} </h1>[/category]
            [category=49]<h1>{category-title} </h1>[/category]
            [category=40]<h1>{category-title} </h1>[/category]
            [category=51]<h1>{category-title} </h1>[/category]
            [category=52]<h1>{category-title} </h1>[/category]
            [category=53]<h1>{category-title} </h1>[/category]            
        </center>
    </section>

    <!-- подключение верхних описаний -->
    <div class="cat-cols clearfix">
        <div class="section-items floaters clearfix cat-items">
		{info}{content}
        </div>
        <aside class="cat-side">
            <div class="top">
                <div class="top-t"><span class="fa fa-trophy"></span>ТОП в категории</div>
                <div class="top-c">
            [category=1]{custom category="1" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=2]{custom category="2" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=3]{custom category="3" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=4]{custom category="4" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=5]{custom category="5" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=6]{custom category="6" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=7]{custom category="7" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=8]{custom category="8" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=9]{custom category="9" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=10]{custom category="10" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=11]{custom category="11" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=12]{custom category="12" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=13]{custom category="13" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=14]{custom category="14" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=15]{custom category="15" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=16]{custom category="16" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=17]{custom category="17" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=18]{custom category="18" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=19]{custom category="19" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=20]{custom category="20" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=21]{custom category="21" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=22]{custom category="22" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=23]{custom category="23" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=24]{custom category="24" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=25]{custom category="25" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=26]{custom category="26" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=27]{custom category="27" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=28]{custom category="28" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=29]{custom category="29" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=30]{custom category="30" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=31]{custom category="31" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=32]{custom category="32" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=33]{custom category="33" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
			[category=34]{custom category="34" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=35]{custom category="35" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=36]{custom category="36" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=37]{custom category="37" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=38]{custom category="38" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=39]{custom category="39" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=40]{custom category="40" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=41]{custom category="41" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=42]{custom category="42" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=43]{custom category="43" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
			[category=44]{custom category="44" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=45]{custom category="45" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=46]{custom category="46" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=47]{custom category="47" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=48]{custom category="48" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=49]{custom category="49" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=50]{custom category="50" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=51]{custom category="51" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=52]{custom category="52" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
            [category=53]{custom category="53" template="1" aviable="global" from="0" limit="10" order="reads" cache="no"}[/category]
                </div>
            </div>
        </aside>
    </div>
</div>     
[/aviable]
        
[not-aviable=showfull|feedback|cat|main]
<div class="sluzh-cont clearfix">        
{info}{content}
</div>       
[/not-aviable]
                
[aviable=showfull|feedback]
<div class="page-cont">
{info}{content}
</div>
[/aviable]
		
		
		
		
		<div class="decor-line img-bg">
			<!--noindex-->{custom category="1-100" template="2" aviable="global" from="0" limit="1" order="rand" cache="no"}<!--/noindex-->
		</div>
		
		<footer class="foot">
		
			<div class="socials2">
				<div class="share-box"></div>
			</div>
			
			<div class="f-cols clearfix">
				<div class="f-col">
					<div class="f-col-title lighter"><span class="fa fa-info-circle"></span>Информация</div>
					<ul>
						<li><a href="/pravoobladatelyam.html">Правообладателям</a></li>
						<li><a href="/index.php?do=feedback">Служба поддержки</a></li>
						<li><a href="/sitemap.xml">Карта сайта</a></li>
					</ul>
				</div>
				<div class="f-col">
					<div class="f-col-title lighter"><span class="fa fa-map-marker"></span>Навигатор</div>
					<ul>
                        <li><a href="/">Онлайн</a></li>
						<li><a href="/">Торрент</a></li>	
						<li><a href="/">Трейлеры</a></li>
					</ul>
				</div>
				<div class="f-col">
					<div class="f-col-title lighter"><span class="fa fa-sitemap"></span>Разделы</div>
					<ul>
						<li><a href="/">Новое на сайте</a></li>
						<li><a href="/">Лучшие новинки</a></li>
						
					</ul>
				</div>
				<div class="f-col">
				
				Любой виджет или доп. инфор
				
				
				</div>
			</div>
			
			<div class="f-line clearfix">
				<div class="f-logo">
					<p> Copyright &copy; 2017 <nofollow><noindex> <a href="http://www.dle9.com" target="_blank">Твой сайт</a> </noindex></nofollow> <!-- При использование материала ссылка обязательна --></p> 
					Виджет Лайк</div>
				<div class="f-text" data-label="16+">
					<p><a href="/">Скачать фильмы в хорошем качестве</a></p>
					<p><a href="/">Скачать новые фильмы</a></p>
				</div>
				
				<div class="f-count">
                    
					Счетчики

                    
                    
				</div>
			</div>
			
		</footer>
		
	</div>

</div>
   
<!-- end wrapp -->

<!--noindex-->
{login}
<!--/noindex-->

</body>
</html>