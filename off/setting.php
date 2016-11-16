<?php
header('Access-Control-Allow-Origin: *'); 
include 'connect.php';
$session_key = htmlspecialchars($_POST['session']);
$email = htmlspecialchars($_POST['email']);
$pass = htmlspecialchars($_POST['pass']);
if ($email != NULL OR $pass != NULL) {
	$login_query = mysqli_query($con, "SELECT password,id,login FROM `users` WHERE session_key='$session_key'") or die(mysqli_error($con));
	if (mysqli_num_rows($login_query) != 0) {
		$login_row = mysqli_fetch_array($login_query);
		$password_bd = $login_row['password'];
		$id = $login_row['id'];
		$login = $login_row['login'];
		if ($pass != NULL) {
			$password = md5(md5($login).md5($pass).md5($id));
		}
		if ($email != NULL AND $pass != NULL) {
			$mobile_query = mysqli_query($con, "UPDATE users SET email='$email', password='$password'  WHERE session_key='$session_key'") or die(mysqli_error($con));
		}else if ($email != NULL AND $pass == NULL) {
			$mobile_query = mysqli_query($con, "UPDATE users SET email='$email'  WHERE session_key='$session_key'") or die(mysqli_error($con));
		}else{
			$mobile_query = mysqli_query($con, "UPDATE users SET password='$password'  WHERE session_key='$session_key'") or die(mysqli_error($con));
		}
		$answer = array(
				'msg' => "Успешно сохранено!",
				'code' => '00',
		);
	}else{
		$answer = array(
			'msg' => 'Такой логин не зарегистрирован',
			'code' => '100',
		);
	}
}else{
	$answer = array(
			'msg' => 'Что-то пошло не так',
			'code' => '1000',
		);
}
print_r(json_encode($answer));
?>