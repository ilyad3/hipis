<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$email = htmlspecialchars($_POST['email']);
$mail_row = $db->select(false,"id","hip_users","email='$email'");
if ($mail_row == 0) {
	echo "not_mail";
}else{
	$id = $mail_row['id'];
	$code = md5(md5($id).md5(date("Hsi")));
	$code = substr($code, 0, 8);
	session_start();
	$_SESSION['code'] = $code;
	$_SESSION['recovery_mail'] = $email;
	$recovery_msg = "С вашего аккаунта было запрошено восстановление доступа.\n
	Ваш код для восстановления доступа:.\n\n
	".$code."\n\n
	Введите его в соответствующее поле на нашем сайте и следуйте дальнейшим инструкциям.\n
	Никому не сообщайте этот код!.\n
	Если Вы не запрашивали восстановление доступа к аккаунту,то можете проигнорировать это письмо.\n
	С уважением, команда проекта Hip!";
	mail($email, 'Восстановление доступа', $recovery_msg);
	echo "success_code";
}
?>