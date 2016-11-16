<?php 
include_once 'lib/Smarty.class.php';

$smarty = new Smarty();
$body = "<body>
	<div id='main_bg'>
		<div class='main_auth'>
		<div id='auth_error'>Неверный пароль</div>
			<p>Авторизация</p>
			<div id='auth_inputs' class='auth_dialog'>
				<p>Логин,E-mail или номер в системе</p>
				<input type='text' id='login'>
				<p>Пароль</p>
				<input type='password' id='password'>
				<a onclick='recovery_pass();'>Забыли пароль?</a>
			</div>
			<p id='auth_btn' onclick='auth();'>Войти</p>
		</div>
	</div>
	<a href='' id='main_logo'>Hip</a>
	<div class='main_menu'>
		<a id='main_in' onclick='show_auth();'>Войти</a>
		<a onclick=$('#reg_login').focus();>Регистрация</a>
	</div>
	<div id='main_about'>
		<b>hip</b> поможет вам объединить все социальные<br>
		сети в один сервис для<br>
		быстрого и удобного общения со своими<br>
		близкими и друзьями.
	</div>
	<div class='main_scope'>
		<p>Начните прямо сейчас</p>
		<div class='main_reg'>
			<div class='main_error'>
				<img src='img/main_error_layer.png' id='main_error_layer'>
				<p>Этот логин занят</p>
			</div>
			<input type='text' placeholder='Логин' id='reg_login' onblur='check_login();'>
			<div class='main_error'>
				<img src='img/main_error_layer.png' id='main_error_layer'>
				<p>Этот логин занят</p>
			</div>
			<input type='text' placeholder='E-mail' id='reg_mail'>
			<div class='main_error'>
				<img src='img/main_error_layer.png' id='main_error_layer'>
				<p>Этот логин занят</p>
			</div>
			<input type='password' placeholder='Пароль' id='reg_pass'>
			<div class='main_error'>
				<img src='img/main_error_layer.png' id='main_error_layer'>
				<p>Этот логин занят</p>
			</div>
			<input type='password' placeholder='Повторите пароль' id='reg_repeat_pass'>
			<input type='button' onclick='register();' value='Зарегистрироваться'>
		</div>
	</div>
</body>";
$smarty->assign("body", $body);
$smarty->display('main.tpl');
?>