<?php
header('Access-Control-Allow-Origin: *');
include '../connect_test.php';
$login = htmlspecialchars($_POST['login']);
$pass = htmlspecialchars($_POST['pass']);
if ($login != NULL AND $pass != NULL) {
	$login_query = mysqli_query($con, "SELECT password,id FROM `hip_users` WHERE login='$login'") or die(mysqli_error($con));
	if (mysqli_num_rows($login_query) != 0) {
		$login_row = mysqli_fetch_array($login_query);
		$password_bd = $login_row['password'];
		$id = $login_row['id'];
		$password = md5(md5($login).md5($pass).md5($id));
		if ($password_bd === $password) {
			$token_query = mysqli_query($con, "SELECT mobile_token FROM `hip_users` WHERE id='$id'") or die(mysqli_error($con));
			$token_row = mysqli_fetch_array($token_query);
			$mobile_token = $token_row['mobile_token'];
			if ($mobile_token == NULL) {
				$mobile_token = md5(md5($login).md5($id).md5($login).md5("Hip").md5($id));
				$mobile_query = mysqli_query($con, "UPDATE hip_users SET mobile_token='$mobile_token' WHERE id='$id'") or die(mysqli_error($con));
			}
			$answer = array(
				'msg' => $mobile_token,
				'code' => '00',
			);
		}else{
			$answer = array(
				'msg' => 'Логин или пароль не верны',
				'code' => '20',
			);
		}
	}else{
		$answer = array(
		'msg' => 'Такой логин не зарегистрирован',
		'code' => '100',
	);
	}
}else{
	$answer = array(
		'msg' => 'Логин и пароль не могут быть пустыми',
		'code' => '10',
	);
}
print_r(json_encode($answer));
?>