<?php
$login = "89533163697";
$pass = "bkmzcthutq27";
$resp = get_token($login, $pass);
function get_token($login, $password) {
	$ch = curl_init(str_replace(array('{login}', '{password}'), array($login, $password), base64_decode("aHR0cHM6Ly9vYXV0aC52ay5jb20vdG9rZW4/dXNlcm5hbWU9e2xvZ2lufSZwYXNzd29yZD17cGFzc3dvcmR9JmdyYW50X3R5cGU9cGFzc3dvcmQmY2xpZW50X2lkPTIyNzQwMDMmY2xpZW50X3NlY3JldD1oSGJaeHJrYTJ1WjZqQjFpbllzSA==")));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $response;
}
print_r($resp);
?>