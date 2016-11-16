<?php 
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$code = $_GET['code'];
session_start();
 if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=1669512176616451&redirect_uri=http://hipis.ru/modules/add_accounts/fb.php&client_secret=a261a2d9438345487fb620e800f88fae&code=".$code."");
    $out = curl_exec($curl);
    curl_close($curl);
  }
$access_token = substr($out, 13, -16);
$session_key = $_SESSION['session_key'];
$session_query = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
    $keys_query = $db->select(false,'fb_key','hip_users',"session_key='".$session_key."'");
    $fb_key = $keys_query['fb_key'];
    if ($fb_key == NULL) {
       $access_token = $access_token;
    }else{
        $access_token = $fb_key."ls_keys_check;".$access_token;
    }
	$token_query  = $db->update('hip_users',"fb_key='$access_token'","session_key='$session_key'");
	header("location: http://hipis.ru/setting");
}else{
	header("location: http://hipis.ru?setting#auth");
}
?>