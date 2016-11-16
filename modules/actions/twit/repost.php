<?php
include '../../../connect.php';
include '../../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$twit_id = htmlspecialchars($_POST['twit_id']);
session_start();
$session_key = $_SESSION['session_key'];
if ($session_key != NULL) {
	$session_row = $db->select(false,"twit_key","hip_users","session_key='$session_key'");
	if ($session_row != 0) {
		$twit_key = $session_row['twit_key'];
		$twit_array = explode("&", $twit_key);
		require_once("../../twit/twitteroauth/twitteroauth.php");
		$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
		$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
		$accesstoken = $twit_array[0];
		$accesstokensecret = $twit_array[1];  
		$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); 	 
		$tweets = $connection->post("https://api.twitter.com/1.1/statuses/retweet/".$twit_id.".json");
		print_r($tweets->{"retweet_count"});
	}else{
		echo "need_refresh";
	}
}else{
	echo "need_refresh";
}

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
?>