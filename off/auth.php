<?php
header('Access-Control-Allow-Origin: *');
include 'connect.php';
$login = htmlspecialchars($_POST['login']);
$pass = htmlspecialchars($_POST['pass']);
$login = iconv('CP1251','UTF-8',$login);
$pass = iconv('CP1251','UTF-8',$pass);
if ($login != NULL AND $pass != NULL) {
	$login_query = mysqli_query($con, "SELECT password,id FROM `users` WHERE login='$login'") or die(mysqli_error($con));
	if (mysqli_num_rows($login_query) != 0) {
		$login_row = mysqli_fetch_array($login_query);
		$password_bd = $login_row['password'];
		$id = $login_row['id'];
		$password = md5(md5($login).md5($pass).md5($id));
		if ($password_bd === $password) {
			$token_query = mysqli_query($con, "SELECT session_key,vk_key FROM `users` WHERE id='$id'") or die(mysqli_error($con));
			$token_row = mysqli_fetch_array($token_query);
			$session_key = $token_row['session_key'];
			$vk_key = $token_row['vk_key'];
			$vk_keys = explode(";", $vk_key);
			if ($session_key == NULL) {
				$session_key = md5(md5($login).md5($id).md5($login).md5("off").md5($id));
				$mobile_query = mysqli_query($con, "UPDATE users SET session_key='$session_key' WHERE id='$id'") or die(mysqli_error($con));
			}
			if ($vk_keys[0] != NULL) {
				$vk_response = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&name_case=Nom&v=5.8&lang=ru&fields=photo_200");
				$vk_answer = json_decode($vk_response);
				$vk_name = $vk_answer->{"response"}[0]->{"first_name"}." ".$vk_answer->{"response"}[0]->{"last_name"};
				$vk_img = $vk_answer->{"response"}[0]->{"photo_200"};
				$path = $vk_img;
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$vk_img = 'data:image/' . $type . ';base64,' . base64_encode($data);
				$answer = array(
					'msg' => $session_key,
					'vk_account_name' => $vk_name,
					'vk_account_avatar' => $vk_img,
					'vk' => "",
					'code' => '00',
				);
			}else{
				$answer = array(
					'msg' => $session_key,
					'vk' => "need_vk",
					'code' => '00',
				);
			}
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