<?php
include '../../../connect.php';
include '../../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$post_text = htmlspecialchars(nl2br($_GET['post_text']));
$session_key = htmlspecialchars($_GET['session_key']);
$accounts = $_GET['accounts'];
$accounts = explode("+", $accounts);
$count_accounts = count($accounts);
$account_count = 0;
$session_query = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
	while ($count_accounts > $account_count) {
		$vk_keys = $accounts[$account_count];
		$vk_keys = explode("_", $vk_keys);
		$vk_key = $vk_keys[0];
		$vk_key = str_replace(" ", "", $vk_key);
		$vk_id = $vk_keys[1];
		if ($post_text != NULL) {
			$parametrs = "https://api.vk.com/method/wall.post?owner_id=".$vk_id."&message=".urlencode($post_text)."&access_token=".$vk_key."";
			$response = file_get_contents($parametrs);
			$answer = json_decode($response);
			if ($answer->{"error"}->{"error_code"} != NULL) {
				echo "bad_post";
			}elseif ($answer->{"response"}->{"post_id"} != NULL) {
				echo "success_post";
			}
		}else{
			echo "need_text";
		}
		$account_count++;
	}
}else{
	echo "need_refresh";
}
?>