<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

<?php

// if( $curl = curl_init() ) {
//     curl_setopt($curl, CURLOPT_URL, 'https://api.instagram.com/v1/media/1033219753119696307/likes');
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
//     curl_setopt($curl, CURLOPT_POST, true);
//     curl_setopt($curl, CURLOPT_POSTFIELDS, "access_token=1950976039.5c82e7c.963c0fa44148439581d575bb5106ef0a");
//     $out = curl_exec($curl);
//     echo $out;
//     curl_close($curl);
  // }
// require_once("/twit/twitteroauth/twitteroauth.php");
// 	 			//$twit_array = explode("&", $twit_key);
// 				$notweets = 4;
// 				$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
// 				$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
// 				$accesstoken = "3301860471-azp2JmdokRpRtXZNFEYB6QpmEUkrhU84YaYSE43";
// 				$accesstokensecret = "lkpd0dVwT8B00LsR3jkISQtT8MvsGthn2nHnMrQNlNJMq"; 	 
// 				$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
// 				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=".$notweets."");
// print_r($tweets);
//print_r($response);

/**
* 
*/
// define('db_host','localhost');
// define('db_name','hip');
// define('db_user','root');
// define('db_pass','bkmzcthutq1527');

// class DB_class 
// {
// 	private $db_host,$db_name,$db_user,$db_pass,$db;
// 	function __construct($db_host,$db_name,$db_user,$db_pass)
// 	{
// 		if (!$this->db) {
// 			$con = @ new mysqli($db_host, $db_user, $db_pass, $db_name);
// 			if (!$con->connect_error) {
// 				$this->db = true;
// 				$con->set_charset("utf8");
// 				$this->con = $con;
// 				return true;
// 			}else{
// 				return false;
// 			}
// 		}
// 	}

// 	function select($while,$select,$from,$where = null,$order = null)
// 	{
// 		if ($where != NULL) {
// 			$where = "WHERE ".$where;
// 		}
// 		if ($select == "*") {
// 			$select_array = explode(",", "id,login,email,password,session_key,vk_key,fb_key,twit_key,inst_key,avatar,reg_date,enter_date,mobile_token");
// 		}elseif ($select == "mobile_info") {
// 			$select = "id,login,mobile_token";
// 			$select_array = explode(",", $select);
// 		}elseif ($select == "news_info") {
// 			$select = "session_key,vk_key,fb_key,twit_key,inst_key";
// 			$select_array = explode(",", $select);
// 		}else{
// 			$select_array = explode(",", $select);
// 		}
// 		$count_select = count($select_array);
// 		$sql = "SELECT ".$select." FROM `".$from."` ".$where."";
// 		$u_query = $this->con->query($sql);
// 		$count_row = 0;
// 		if ($u_query->num_rows != 0) {
// 			while ($query_row = $u_query->fetch_array(MYSQLI_ASSOC)) {
// 				$select_count = 0;
// 				while ($select_count < $count_select) {
// 					if ($while == true) {
// 						$return_array[$count_row][$select_array[$select_count]] = $query_row[$select_array[$select_count]];
// 						$select_count++;
// 					}else{
// 						$return_array[$select_array[$select_count]] = $query_row[$select_array[$select_count]];
// 						$select_count++;
// 					}
// 				}
// 				$count_row++;
// 			}
// 		}else{
// 			$return_array =  "0";
// 		}
// 		return $return_array;
// 	}

// 	function update($from,$set,$where)
// 	{
// 		if ($where != NULL) {
// 			$where = "WHERE ".$where;
// 		}
// 		$update_sql = "UPDATE ".$from." SET ".$set." ".$where."";
// 		$update_query = $this->con->query($update_sql);
// 	}

// 	function insert($from,$insert,$values)
// 	{
// 		$insert_sql = "INSERT INTO ".$from." (".$insert.") VALUES (".$values.")";
// 		$insert_query = $this->con->query($insert_sql);
// 	}

// 	function __destruct()
// 	{
// 		mysqli_close($this->con);
// 		$this->db = false;
// 	}
// }

// $db = new DB_class(db_host,db_name,db_user,db_pass);
// $test = $db->select(false,"id","hip_users","login='ilyad2'");
// $db->update('hip_users',"enter_date='15.08.2015'",'id=1');
// $db->insert('hip_users',"login","'ilyad2'");

// require_once("twit/twitteroauth/twitteroauth.php");
// $notweets = 1;
// $consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
// $consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
// $accesstoken = '3301860471-azp2JmdokRpRtXZNFEYB6QpmEUkrhU84YaYSE43';
// $accesstokensecret = 'lkpd0dVwT8B00LsR3jkISQtT8MvsGthn2nHnMrQNlNJMq'; 	 
// $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); 	 
// //$tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=".$notweets."");
// $tweets = $connection->post("https://api.twitter.com/1.1/statuses/retweet/632658763200249856.json");
// print_r($tweets->{"retweet_count"});

// function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
// 		$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
// 		return $connection;
// }

// preg_match_all('%#([^\\s.,!?]+)%', "#тест #123", $tags);
// print_r($tags);
// $response = file_get_contents("https://graph.facebook.com/v2.5/me/home?access_token=CAAXuaZAg8RAMBACV1goiPb6KgVeiAAqLT5D0E0dZB7mv6nnRVcYM5FgkOZCaElGSfyTdQX8QfbCpklG0SEbD5lwXlxfFoqHIlOLtfSuC8wNSTREorZBUZCVdtpVgJ0ZB9zZC96Iof4i6qAWDaEU4ryahxnbuNop3X8c4MtZAZAt7psP5h42bBxsAr68KZARhW4KHGt5YKO77ZCThgZDZD");
// print_r(json_decode($response));
$vk_response = file_get_contents("https://api.vk.com/method/users.get?user_ids=137534777&name_case=Nom&v=5.8&lang=ru&fields=photo_200");
$vk_answer = json_decode($vk_response);
print_r($vk_answer);
?>

