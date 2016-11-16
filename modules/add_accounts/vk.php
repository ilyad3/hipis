<?php 
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$login = htmlspecialchars($_POST['login']);
$pass = htmlentities($_POST['pass']);
$resp = get_token($login, $pass);
session_start();
$session_key = $_SESSION['session_key'];
$access_token = $resp['access_token'].";".$resp['user_id'];
$session_query = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
	$keys_query = $db->select(false,'vk_key','hip_users',"session_key='".$session_key."'");
	$vk_key = $keys_query['vk_key'];
	if ($vk_key == NULL) {
		$access_token = $access_token;
	}else{
		$access_token = $vk_key."ls_keys_check;".$access_token;
	}
	$token_query  = $db->update('hip_users',"vk_key='$access_token'","session_key='$session_key'");
	$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$resp['access_token']."&filters=post,photo&count=1&v=5.34");
	$vk_answer = json_decode($vk_query);
	if ($vk_answer->{"error"}->{"error_code"} == '17') {
		echo $vk_answer->{"error"}->{"redirect_uri"};
	}
}else{
	echo "success_vk";
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
?>