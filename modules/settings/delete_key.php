<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$key = htmlspecialchars($_POST['key']);
$access = htmlspecialchars($_POST['access']);
session_start();
$session_key = $_SESSION['session_key'];
$session_row = $db->select(false,"id,".$key."","hip_users","session_key='$session_key'");
if ($session_row == 0) {
	echo "bad_session";
}else{
	$id = $session_row['id'];
	$keys = $session_row[$key];
	$keys_array = explode("ls_keys_check;", $keys);
	$keys_count = count($keys_array);
	if ($keys_count > 1) {
		if ($key = "vk_key") {
			$vk_p = implode(";", $keys_array);
			$vk_arrays = explode(";", $vk_p);
			$vk_key = array_search($access, $vk_arrays) + 1;
			$vk_id = $vk_arrays[$vk_key];
			$access = "ls_keys_check;".$access.";".$vk_id;
			$keys = str_replace($access, "", $keys);
		}else{
			$keys = str_replace("ls_keys_check;".$access, "", $keys);
		}
		$delete_query = $db->update('hip_users',$key."='".$keys."'","id='$id'");
		echo "success_delete";
	}else{
		$delete_query = $db->update('hip_users',$key."=''","id='$id'");
		echo "success_delete";
	}
}
?>