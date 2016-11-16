<?php 
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$login = htmlspecialchars($_POST['login']);
$email = htmlspecialchars($_POST['email']);
$pass = htmlspecialchars($_POST['pass']);
$mail_query = $db->select(false,"id","hip_users","email='".$email."'");
if ($mail_query == 0) {
	$login_query = $db->select(false,"id","hip_users","login='".$login."'");
	if ($login_query == 0) {
		$reg_query = $db->insert('hip_users',"login,email","'$login','$email'");
		$id_row = $db->select(false,"id","hip_users","login='".$login."'");
		$id = $id_row['id'];
		$password = md5(md5($login).md5($pass).md5($id));
		session_start();
		$session_key = md5(md5($login).md5(date("H:i:s")).md5($id));
		$_SESSION['session_key'] = $session_key;
		$reg_date = date("j.m.Y");
		$enter_date = $reg_date;
		$register_query = $db->update('hip_users',"password='$password', session_key='$session_key', reg_date='$reg_date', enter_date='$enter_date'","id='".$id."'");
		$reg_msg = "Благодарим за регистрацию в сервисе Hip!\n
		Вы были зарегистрированы на сайте: http://hipis.ru/.\n
		Логин,который Вы указали при регистрации:".$login.".\n
		При регистрации Вы указали данный E-mail(".$email.") для получения уведомлений.\n
		Никому не сообщайте Ваш пароль! Это может доставить Вам неприятности.\n
		С уважением, команда проекта Hip!";
		mail($email, 'Благодарим за регистрацию!', $reg_msg);
		echo "succes_register";
	}else{
		echo "bad_login";
	}
}else{
	echo "bad_email";
}
?>