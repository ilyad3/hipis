<?php
include '../../connect.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
define('CONSUMER_KEY', 'MsIeQx1qtAcSsAt8IdGoi3HO3');
define('CONSUMER_SECRET', 'AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb');

define('REQUEST_TOKEN_URL', 'https://api.twitter.com/oauth/request_token');
define('AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');
define('ACCESS_TOKEN_URL', 'https://api.twitter.com/oauth/access_token');
define('ACCOUNT_DATA_URL', 'https://api.twitter.com/1.1/users/show.json');

define('CALLBACK_URL', 'http://hipis.ru/modules/add_accounts/twit.php');


// формируем подпись для получения токена доступа
define('URL_SEPARATOR', '&');
$oauth_nonce = md5(uniqid(rand(), true));
$oauth_timestamp = time();
$oauth_token = $_GET['oauth_token'];
$oauth_verifier = $_GET['oauth_verifier'];


$oauth_base_text = "GET&";
$oauth_base_text .= urlencode(ACCESS_TOKEN_URL)."&";

$params = array(
    'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
    'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
    'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
    'oauth_token=' . $oauth_token . URL_SEPARATOR,
    'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
    'oauth_verifier=' . $oauth_verifier . URL_SEPARATOR,
    'oauth_version=1.0'
);

$key = CONSUMER_SECRET . URL_SEPARATOR;
$oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(ACCESS_TOKEN_URL) . URL_SEPARATOR . implode('', array_map('urlencode', $params));
$oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

// получаем токен доступа
$params = array(
    'oauth_nonce=' . $oauth_nonce,
    'oauth_signature_method=HMAC-SHA1',
    'oauth_timestamp=' . $oauth_timestamp,
    'oauth_consumer_key=' . CONSUMER_KEY,
    'oauth_token=' . urlencode($oauth_token),
    'oauth_verifier=' . urlencode($oauth_verifier),
    'oauth_signature=' . urlencode($oauth_signature),
    'oauth_version=1.0'
);
$url = ACCESS_TOKEN_URL . '?' . implode('&', $params);

$response = file_get_contents($url);
$auth_array = explode("&", $response);
$oauth_token = substr($auth_array[0], 12);
$oauth_token_secret = substr($auth_array[1], 19);
$twit_key = $oauth_token."&".$oauth_token_secret;
session_start();
$session_key = $_SESSION['session_key'];
if ($session_query != 0) {
    $keys_query = $db->select(false,'twit_key','hip_users',"session_key='".$session_key."'");
    $twit_keys = $keys_query['twit_key'];
    if ($twit_keys == NULL) {
       $twit_key = $twit_key;
    }else{
        $twit_key = $twit_keys."ls_keys_check;".$twit_key;
    }
    $token_query  = $db->update('hip_users',"twit_key='$twit_key'","session_key='$session_key'");
    header("location: http://hipis.ru/setting");
}else{
    header("location: http://hipis.ru?setting#auth");
}
?>