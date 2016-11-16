<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
session_start();
$session_key = $_SESSION['session_key'];
$session_row = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_row == 0) {
	header("Location: http://hipis.ru");
}else{
	$id = $session_row['id'];
	$desession_query = $db->update('hip_users',"session_key='Hip_@2015_Ilya_Oleg_thank_you_for_use_hip'","id='".$id."'");
	unset($_SESSION['session_key']);
	header("Location: http://hipis.ru");
}
?>