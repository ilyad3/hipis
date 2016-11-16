<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$pass = htmlspecialchars($_POST['pass']);
session_start();
$email = $_SESSION['recovery_mail'];
if ($email != NULL) {
	$mail_row = $db->select(false,"id,login","hip_users","email='$email'");
	$id = $mail_row['id'];
	$login = $mail_row['login'];
	$password = md5(md5($login).md5($pass).md5($id));
	$pass_query = $db->update('hip_users',"password='$password'","id='$id'");
	echo "success_pass";
}else{
	echo "start_recovery";
}
?>