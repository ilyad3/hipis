<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$login = htmlspecialchars($_POST['login']);
$login_query = $db->select(false,"id","hip_users","login='$login'");
if ($login_query != 0) {
	echo "bad_login";
}else{
	echo "succes_login";
}
?>