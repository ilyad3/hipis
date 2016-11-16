<?php
header('Access-Control-Allow-Origin: *'); 
include 'connect.php';
$session_key = htmlspecialchars($_POST['session']);
$reg_query = mysqli_query($con, "UPDATE users SET vk_key='' WHERE session_key='$session_key'") or die(mysqli_error($con));
$answer = array(
		'msg' => 'Успешно!',
		'code' => '0',
	);
print_r(json_encode($answer));
?>