<?php
include '../../../connect.php';
include '../../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$post_text = htmlspecialchars(nl2br($_GET['post_text']));
$session_key = htmlspecialchars($_GET['session_key']);
$accounts = $_GET['accounts'];
$accounts = explode("+", $accounts);
$count_accounts = count($accounts);
$account_count = 0;
print_r($accounts);
$session_query = $db->select(false,'fb_key','hip_users',"session_key='".$session_key."'");
if ($session_query != 0) {
	while ($count_accounts > $account_count) {
		$access_token = str_replace(" ", "", $accounts[$account_count]);
		$id_response = file_get_contents("https://graph.facebook.com/me?fields=id&version=v2.5&access_token=".$access_token."");
		$id_answer = json_decode($id_response);
		$facebook_id = $id_answer->{"id"};
		if ($post_text != NULL) {
			if( $curl = curl_init() ) {
		    curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/'.$facebook_id.'/feed?');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, "message=".$post_text."&access_token=".$access_token."");
		    $out = curl_exec($curl);
		    curl_close($curl);
		    $answer = json_decode($out);
		    if ($answer->{"id"} != NULL) {
		    	echo "success_post";
		    }else{
		    	echo "bad_post";
		    }
		}
	  }else{
		echo "need_text";
		}
		$account_count++;
	}
}else{
	echo "need_refresh";
}
?>