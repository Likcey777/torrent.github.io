<div class="form-wrap">
<header class="form-title"><h1>Обратная связь</h1></header>

[not-logged]
<div class="sep-input clearfix">
<div class="label"><label>Ваше имя:<span class="impot">*</span></label></div>
<div class="input"><input type="text" maxlength="35" name="name" placeholder="Ваше имя" /></div>
</div>

<div class="sep-input clearfix">
<div class="label"><label>Ваш E-Mail:<span class="impot">*</span></label></div>
<div class="input"><input type="text" maxlength="35" name="email" placeholder="Ваш емейл" /></div>
</div>
[/not-logged]

<div class="sep-input clearfix">
<div class="label"><label>Выберите кому:</label></div>
<div class="input">{recipient}</div>
</div>

<div class="sep-input clearfix">
<div class="label"><label>Тема сообщения:</label></div>
<div class="input"><input type="text" maxlength="45" name="subject" placeholder="Тема сообщения" /></div>
</div>

<div class="sep-textarea">
<div class="textarea-title">Ваше письмо:</div>
<div><textarea name="message" style="height: 160px" class="f_textarea" ></textarea></div>
</div>

[recaptcha]
<div class="sep-input secur clearfix">
<div class="label"><label>Защита от спама:</label></div>
<div class="input">

<div>{recaptcha}</div>

</div>
</div>[/recaptcha]


<div class="sep-submit">
<button name="send_btn" class="fbutton" type="submit"><span>Отправить</span></button>
</div>

</div>