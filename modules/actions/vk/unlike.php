<?php
include '../../../connect.php';
include '../../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$source_id = htmlspecialchars($_POST['source_id']);
$post_id = htmlspecialchars($_POST['post_id']);
session_start();
$session_key = $_SESSION['session_key'];
if ($session_key != NULL) {
	$session_row = $db->select(false,"vk_key","hip_users","session_key='$session_key'");
	if ($session_row != 0) {
		$vk_keys = explode(";", $session_row['vk_key']);
		$vk_key = $vk_keys[0];
		$response = file_get_contents("https://api.vk.com/method/likes.delete?owner_id=".$source_id."&type=post&item_id=".$post_id."&access_token=".$vk_key."");
		$answer = json_decode($response);
		print_r($answer->{"response"}->{"likes"});
	}else{
		echo "need_refresh";
	}
}else{
	echo "need_refresh";
}
?>