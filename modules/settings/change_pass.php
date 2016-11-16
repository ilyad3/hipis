<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$pass = htmlentities($_POST['pass']);
$new_pass = htmlentities($_POST['new_pass']);
session_start();
$session_key = $_SESSION['session_key'];
$session_row = $db->select(false,"id,password,login","hip_users","session_key='$session_key'");
if ($session_row == 0) {
	echo "bad_session";
}else{
	$id = $session_row['id'];
	$pass_bd = $session_row['password'];
	$login = $session_row['login'];
	$password = md5(md5($login).md5($pass).md5($id));
	if ($pass_bd === $password) {
		$new_password = md5(md5($login).md5($new_pass).md5($id));
		$pass_query = $db->update('hip_users',"password='$new_password'","id='$id'");
		echo "success_pass";
	}else{
		echo "bad_pass";
	}
}
?>