<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$code = $_GET['code'];
if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'https://connect.mail.ru/oauth/token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=738745&client_secret=efcf9b2b109f03c024d6f153146f7f3e&grant_type=authorization_code&code=".$code."&redirect_uri=http://hipis.ru/modules/add_accounts/mail.php");
    $out = curl_exec($curl);
    curl_close($curl);
  }
$answer = json_decode($out);
$mail_key = $answer->{"access_token"};
session_start();
$session_key = $_SESSION['session_key'];
$session_query = $db->update('hip_users',"mail_key='$mail_key'","session_key='$session_key'");
$session_query = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
	$token_query  = $db->update('hip_users',"mail_key='$mail_key'","session_key='$session_key'");
	header("location: http://hipis.ru/setting");
}else{
	header("location: http://hipis.ru?setting#auth");
}
?>