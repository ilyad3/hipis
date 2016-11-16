<?php
header('Access-Control-Allow-Origin: *');
include 'connect.php';
$login = htmlspecialchars($_POST['login']);
$email = htmlspecialchars($_POST['email']);
$pass = htmlspecialchars($_POST['pass']);
if ($login != NULL AND $pass != NULL AND $email != NULL) {
	$mail_query = mysqli_query($con, "SELECT id FROM `users` WHERE email='$email'") or die(mysqli_error($con));
	if (mysqli_num_rows($mail_query) == 0) {
		$login_query = mysqli_query($con, "SELECT id FROM `users` WHERE login='$login'") or die(mysqli_error($con));
		if (mysqli_num_rows($login_query) == 0) {
			$pre_reg_query = mysqli_query($con, "INSERT INTO users (login,email) VALUES ('$login','$email')") or die(mysqli_error($con));
			$id_query = mysqli_query($con, "SELECT id FROM `users` WHERE login='$login'") or die(mysqli_error($con));
			$id_row = mysqli_fetch_array($id_query);
			$id = $id_row['id'];
			$password = md5(md5($login).md5($pass).md5($id));
			$session_key = md5(md5($login).md5($id).md5($login).md5("off").md5($id));
			$reg_query = mysqli_query($con, "UPDATE users SET session_key='$session_key', password='$password' WHERE id='$id'") or die(mysqli_error($con));
			$answer = array(
				'msg' => $session_key,
				'code' => '00',
			);
		}else{
			$answer = array(
				'msg' => 'Такой логин уже зарегистрирован',
				'code' => '1000',
			);
		}
	}else{
		$answer = array(
			'msg' => 'Такой E-mail уже зарегистрирован',
			'code' => '100',
		);
	}
}else{
	$answer = array(
		'msg' => 'Не заполнены обязательные поля',
		'code' => '10',
	);
}
print_r(json_encode($answer));
?>