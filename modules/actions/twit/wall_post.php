<?php
include '../../../connect.php';
include '../../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$post_text = htmlentities(nl2br($_GET['post_text']));
$session_key = htmlentities($_GET['session_key']);
$accounts = htmlentities($_GET['accounts']);
$accounts = explode(";", $accounts);
$count_accounts = count($accounts);
$account_count = 0;
$session_query = $db->select(false,'twit_key','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
	while ($count_accounts > $account_count) {
		if ($post_text != NULL) {
			$twit_key = $accounts[$account_count];
			require_once("../../twit/twitteroauth/twitteroauth.php");
			$twit_array = explode("_", $twit_key);
			$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
			$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
			$accesstoken = $twit_array[0];
			$accesstokensecret = $twit_array[1]; 	 
			$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
			$tweets = $connection->post("https://api.twitter.com/1.1/statuses/update.json?status=".urlencode($post_text)."");
			if ($tweets->{"errors"}[0]->{"code"} != NULL) {
				echo "bad_post";
			}else{
				echo "success_post";
			}
		}else{
			echo "need_post";
		}
		$account_count++;
	}
}else{
	echo "need_refresh";
}
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
?>