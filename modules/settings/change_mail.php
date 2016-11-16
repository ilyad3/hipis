<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$email = htmlspecialchars($_POST['email']);
session_start();
$session_key = $_SESSION['session_key'];
$session_row = $db->select(false,"id","hip_users","session_key='$session_key'");
if ($session_row == 0) {
	echo "bad_session";
}else{
	$id = $session_row['id'];
	$pre_email_query = $db->select(false,"id","hip_users","email='$email'");
	if ($pre_email_query == 0) {
		$pass_query = $db->update('hip_users',"email='$email'","id='$id'");
		echo "success_mail";
	}else{
		echo "bad_mail";
	}
}
?>