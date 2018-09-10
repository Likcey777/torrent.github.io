<div class="overlay" id="overlay">  
[group=5]
	<div class="login-box" id="login-box">
		<div class="login-title">Авторизация</div>
		<div class="login-social clearfix">
			<a href="{vk_url}" target="_blank"><img src="{THEME}/images/social/vkontakte.png" /></a>
		</div>
		<div class="login-form">
			<form method="post">
				<div class="login-input">
					<input type="text" name="login_name" id="login_name" placeholder="Ваш логин"/>
				</div>
				<div class="login-input">
					<input type="password" name="login_password" id="login_password" placeholder="Ваш пароль" />
				</div>
				<div class="login-button">
					<button onclick="submit();" type="submit" title="Вход">Войти на сайт</button>
					<input name="login" type="hidden" id="login" value="submit" />
				</div>
				<div class="login-checkbox">
					<input type="checkbox" name="login_not_save" id="login_not_save" value="1"/>
					<label for="login_not_save">&nbsp;Чужой компьютер</label> 
				</div>
				<div class="login-links clearfix">
					<a href="/?do=lostpassword">Забыли пароль?</a>
					<a href="/?do=register" class="log-register">Регистрация</a>
				</div>
			</form>
		</div>	
	</div>

[/group]
[not-group=5]
    
    <div class="login-box" id="login-box">
		<div class="login-title">{login}</div>
		<div class="login-avatar">
				<div class="avatar-box">
					<img src="{foto}" title="{login}" alt="{login}" />
				</div>
				
		</div>
		<ul class="login-menu">
						<li><a href="{addnews-link}">Добавить пост</a></li>
						<li><a href="{stats-link}">Статистика</a></li>
						<li><a href="{newposts-link}">Непрочитанное</a></li>
						<li><a href="{logout-link}">Выйти</a></li>
		</ul>
	</div>
[/not-group]
</div>