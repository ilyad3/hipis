<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$login = htmlentities($_POST['login']);
$pass = htmlentities($_POST['pass']);
$enter_date = date("j.m.Y");
$login_row = $db->select(false,"id,password","hip_users","login='".$login."'");
if ($login_row != 0) {
	$pass_bd = $login_row['password'];
	$id = $login_row['id'];
	$password = md5(md5($login).md5($pass).md5($id));
	if ($pass_bd === $password) {
		$session_key = md5(md5($login).md5(date("H:i:s")).md5($id));
		session_start();
		$_SESSION['session_key'] = $session_key;
		$register_query = $db->update('hip_users',"session_key='$session_key', enter_date='$enter_date'","login='".$login."'");
		setcookie("session_key", $session_key, time()+86400000000);
		echo "success_auth";
	}else{
		echo "bad_pass";
	}
}else{
	$login_row = $db->select(false,"id,password,login","hip_users","email='".$login."'");
	if ($login_row != 0) {
		$pass_bd = $login_row['password'];
		$id = $login_row['id'];
		$login_bd = $login_row['login'];
		$password = md5(md5($login_bd).md5($pass).md5($id));
		if ($pass_bd === $password) {
			$session_key = md5(md5($login_bd).md5(date("H:i:s")).md5($id));
			session_start();
			$_SESSION['session_key'] = $session_key;
			$register_query = $db->update('hip_users',"session_key='$session_key', enter_date='$enter_date'","email='".$login."'");
			setcookie("session_key", $session_key, time()+86400000000);
			echo "success_auth";
		}else{
			echo "bad_pass";
		}
	}else{
		$login_row = $db->select(false,"password,login","hip_users","id='".$login."'");
		if ($login_row != 0) {
			$pass_bd = $login_row['password'];
			$id = $login_row['id'];
			$login_bd = $login_row['login'];
			$password = md5(md5($login_bd).md5($pass).md5($login));
			if ($pass_bd === $password) {
				$session_key = md5(md5($login_bd).md5(date("H:i:s")).md5($id));
				session_start();
				$_SESSION['session_key'] = $session_key;
				$register_query = $db->update('hip_users',"session_key='$session_key', enter_date='$enter_date'","id='".$login."'");
				setcookie("session_key", $session_key, time()+86400000000);
				echo "success_auth";
			}else{
				echo "bad_pass";
			}
		}else{
			echo "bad_login";
		}
	}
}
?>