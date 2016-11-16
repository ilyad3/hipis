<?php
include_once '../../lib/Smarty.class.php';
include '../../connect.php';
include '../classes/db.class.php';
$count = htmlspecialchars($_POST['count']);
session_start();
$session_key = $_SESSION['session_key'];
$db = new DB_class(db_host,db_name,db_user,db_pass);
$session_row = $db->select(false,"id","hip_users","session_key='$session_key'");
if ($session_row == 0) {
	echo "need_refresh";
}else{
	if ($count > 30) {
		echo "bad_count";
	}else{
		if ($count < 2 AND $count != 0) {
			echo "count_bad";
		}else{
			$id = $session_row['id'];
			$pass_query = $db->update('hip_users',"count_news='$count'","id='$id'");
			echo "success_count";
		}
	}
}
?>