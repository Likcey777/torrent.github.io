<div class="form-wrap">
<header class="form-title"><h1>Регистрация</h1></header>

<div class="full-text sep-textarea">		
		
		[registration]
			Регистрация на нашем сайте позволит Вам быть его полноценным участником.
			Вы сможете добавлять новости на сайт, оставлять свои комментарии, просматривать скрытый текст и многое другое.
			<br>В случае возникновения проблем с регистрацией, обратитесь к <a href="/index.php?do=feedback">администратору</a> сайта.
		[/registration]
		[validation]
			Ваш аккаунт был зарегистрирован на нашем сайте,
			однако информация о Вас является неполной, поэтому ОБЯЗАТЕЛЬНО заполните дополнительные поля в Вашем профиле.<br>
		[/validation]
		
</div>		

[registration]
<div class="sep-input clearfix">
<div class="label"><label for="name">Логин:</label></div>
<div class="input"><input type="text" name="name" id="name" class="f_input" />
	<input class="register-check" title="Проверить доступность логина для регистрации" onclick="CheckLogin(); return false;" type="button" value="Проверить имя" />
</div>
</div>

<div class="sep-textarea" id='result-registration'></div>

<div class="sep-input clearfix">
<div class="label"><label for="password1">Пароль:</label></div>
<div class="input"><input type="password" name="password1" id="password1" class="f_input" /></div>
</div>

<div class="sep-input clearfix">
<div class="label"><label for="password2">Повторите пароль:</label></div>
<div class="input"><input type="password" name="password2" id="password2" class="f_input" /></div>
</div>

<div class="sep-input clearfix">
<div class="label"><label for="email">Ваш E-Mail:</label></div>
<div class="input"><input type="text" name="email" id="email" class="f_input" /></div>
</div>

<div class="sep-input secur clearfix">
<div class="label"><label>Защита от спама:</label></div>
<div class="input">
{recaptcha}
<div>


</div>
</div>
[/registration]
		[validation]
<div class="sep-input clearfix">
				<div class="label"><label for="fullname">Ваше имя</label></div>
				<div class="input"><input type="text" id="fullname" name="fullname" class="wide"></div>
</div>
<div class="sep-input clearfix">
				<div class="label"><label for="land">Место жительства</label></div>
				<div class="input"><input type="text" id="land" name="land" class="wide"></div>
</div>
<div class="sep-input clearfix">
				<div class="label"><label for="image">О себе</label></div>
				<div class="input"><input id="info" name="info" rows="5" class="wide"></div>
</div>
<div class="sep-input clearfix">
				<div class="label"><label for="image">Аватар</label></div>
				<div class="input"><input type="file" id="image" name="image" class="wide"></div>
</div>
		[/validation]

<div align="center">
<div class="sep-submit">
<button name="submit" class="fbutton" type="submit"><span>Отправить</span></button>
</div></div>			
		
</div>
