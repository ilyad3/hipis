<?php
header('Access-Control-Allow-Origin: *'); 
include 'connect.php';
$login = htmlspecialchars($_POST['login']);
$pass = htmlspecialchars($_POST['pass']);
$session_key = htmlspecialchars($_POST['session']);
$resp = get_token($login, $pass);
$access_token = $resp['access_token'].";".$resp['user_id'];
$vk_keys = explode(";", $access_token);
$token_query = mysqli_query($con, "SELECT id FROM `users` WHERE session_key='$session_key'") or die(mysqli_error($con));
if (mysqli_num_rows($token_query) == 0) {
	$answer = array(
		'msg' => 'Пожалуйста авторизуйтесь!',
		'code' => '10',
	);
}else{
	$reg_query = mysqli_query($con, "UPDATE users SET vk_key='$access_token' WHERE session_key='$session_key'") or die(mysqli_error($con));
	$vk_response = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&name_case=Nom&v=5.8&lang=ru&fields=photo_200");
	$vk_answer = json_decode($vk_response);
	$vk_name = $vk_answer->{"response"}[0]->{"first_name"}." ".$vk_answer->{"response"}[0]->{"last_name"};
	$vk_img = $vk_answer->{"response"}[0]->{"photo_200"};
	$path = $vk_img;
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$vk_img = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$answer = array(
		'vk_account_name' => $vk_name,
		'vk_account_avatar' => $vk_img,
		'code' => '00',
	);
}
function get_token($login, $password) {
	$ch = curl_init(str_replace(array('{login}', '{password}'), array($login, $password), base64_decode("aHR0cHM6Ly9vYXV0aC52ay5jb20vdG9rZW4/dXNlcm5hbWU9e2xvZ2lufSZwYXNzd29yZD17cGFzc3dvcmR9JmdyYW50X3R5cGU9cGFzc3dvcmQmY2xpZW50X2lkPTIyNzQwMDMmY2xpZW50X3NlY3JldD1oSGJaeHJrYTJ1WjZqQjFpbllzSA==")));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $response;
}
print_r(json_encode($answer));
?>