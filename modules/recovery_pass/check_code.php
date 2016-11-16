<?php
$email = htmlspecialchars($_POST['email']);
$code = htmlspecialchars($_POST['code']);
session_start();
if ($email == $_SESSION['recovery_mail']) {
	if ($code == $_SESSION['code']) {
		echo "success_code";
		unset($_SESSION['code']);
	}else{
		echo "bad_code";
	}
}else{
	echo "bad_mail";
}
?>