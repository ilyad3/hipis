<?php 
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$code = $_GET['code'];
if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=5c82e7cb10db41e1827064e9630390ae&client_secret=06c6d25cd640456b81ed3aa4be88a605&grant_type=authorization_code&redirect_uri=http://hipis.ru/modules/add_accounts/inst.php&code=".$code."");
    $out = curl_exec($curl);
    $response = json_decode($out);
    curl_close($curl);
}
session_start();
$session_key = $_SESSION['session_key'];
$token = $response->{'access_token'};
$session_query = $db->select(false,'id','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
    $keys_query = $db->select(false,'inst_key','hip_users',"session_key='".$session_key."'");
    $inst_key = $keys_query['inst_key'];
    if ($inst_key == NULL) {
        $token = $token;
    }else{
        $token = $inst_key."ls_keys_check;".$token;
    }
    $token_query  = $db->update('hip_users',"inst_key='$token'","session_key='$session_key'");
    header("location: http://hipis.ru/setting");
}else{
    header("location: http://hipis.ru?setting#auth");
}
?>