<?php
header('Access-Control-Allow-Origin: *'); 
include '../connect.php';
include '../modules/classes/db.class.php';
include '../functions/emoji.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$mobile_token = $_POST['token'];
if ($mobile_token != NULL) {
	$info_row = $db->select(false,"news_info","hip_users","mobile_token='".$mobile_token."'");
	if ($info_row != 0) {
		$vk_key = $info_row['vk_key'];
		$fb_key = $info_row['fb_key'];
		$inst_key = $info_row['inst_key'];
		$twit_key = $info_row['twit_key'];
		if ($vk_key == NULL AND $fb_key == NULL AND $inst_key == NULL AND $twit_key == NULL) {
			$answer = array(
				'msg' => 'Пожалуйста, перейдите на сайт и подключите хотябы одну соц.сеть',
				'code' => '20',
			);
		}else{
			$count_news = 4;
			if ($vk_key != NULL) {
				$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$vk_key."&filters=post&count=".$count_news."&v=5.34");
				$vk_answer = json_decode($vk_query);
				//$_SESSION['vk_next'] = $vk_answer->{"response"}->{"next_from"};
			}
			if ($inst_key != NULL) {
				$inst_query = file_get_contents("https://api.instagram.com/v1/users/self/feed?access_token=".$inst_key."&count=".$count_news."");
				$inst_array = json_decode($inst_query);
				//$_SESSION['inst_next'] = $inst_array->{"pagination"}->{"next_url"};
			}
			if ($twit_key != NULL) {
				require_once("../modules/twit/twitteroauth/twitteroauth.php");
	 			$twit_array = explode("&", $twit_key);
				$notweets = $count_news + 1;
				$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
				$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
				$accesstoken = $twit_array[0];
				$accesstokensecret = $twit_array[1]; 	 
				$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=".$notweets."");
				if ($tweets->{"errors"}[0]->{"code"} != "88") {
					//$_SESSION['twit_next'] = $tweets[$count_news - 1]->{"id_str"};
				}
			}
			$news_count = 0;
			$count_groups = 0;
			$count_users = 0;
			$gid = 1;
			$uid = 1;
			while ($gid > 0) {
				$gid = $vk_answer->{"response"}->{"groups"}[$count_groups]->{"id"};
				$name = $vk_answer->{"response"}->{"groups"}[$count_groups]->{"name"};
				$photo = $vk_answer->{"response"}->{"groups"}[$count_groups]->{"photo_200"};
				$info = $name."&".$photo;
				$id_groups[$gid] = array(
					'name' => $name,
					'photo' => $photo,
					 );
				$count_groups++;
			}
			while ($uid > 0) {
				$uid = $vk_answer->{"response"}->{"profiles"}[$count_users]->{"id"};
				$name = $vk_answer->{"response"}->{"profiles"}[$count_users]->{"first_name"}." ".$vk_answer->{"response"}->{"profiles"}[$count_users]->{"last_name"};
				$photo = $vk_answer->{"response"}->{"profiles"}[$count_users]->{"photo_100"};
				$info = $name."&".$photo;
				$id_users[$uid] = array(
					'name' => $name,
					'photo' => $photo,
					 );
				$count_users++;
			}
			$count_vk = 0;
			$count_twit = 0;
			$count_inst = 0;
			$count_fb = 0;
			while ($count_news > $news_count) {
				if ($vk_key != NULL) {
					$owner_id = $vk_answer->{"response"}->{"items"}[$news_count]->{"source_id"};
						$owner_id = str_replace("-", "", $owner_id);
						$name = $id_users[$owner_id]['name'];
						if ($name != NULL) {
							$photo = $id_users[$owner_id]['photo'];
						}else{
							$name = $id_groups[$owner_id]['name'];
							$photo = $id_groups[$owner_id]['photo'];
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
						$img = $vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"};
					}elseif ($vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
						$img = $vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"};
					}else{
						$img = "";
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"text"} != NULL) {
						$post_text = $vk_answer->{'response'}->{"items"}[$news_count]->{"text"}."<br><br>";
					}elseif ($vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"} != NULL) {
						$post_text = $vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"}."<br><br>";
					}else{
						$post_text = "";
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"likes"}->{"user_likes"} == 1) {
						$like = "like";
					}else{
						$like = "no_like";
					}
					$vk_array[$count_vk] = array(
						'code' => '0',
						'name' => $name,
						'photo' => $photo,
						'img' => $img,
						'post_text' => $post_text,
						'like' => $like,
						'date' => date('d.m.Y в H:i:s',$vk_answer->{'response'}->{"items"}[$news_count]->{"date"}),
						'likes_count' => $vk_answer->{'response'}->{"items"}[$news_count]->{"likes"}->{"count"},
						'reposts_count' => $vk_answer->{'response'}->{"items"}[$news_count]->{"reposts"}->{"count"},
						'comments_count' => $vk_answer->{'response'}->{"items"}[$news_count]->{"comments"}->{"count"},
					);
					$count_vk++;
				}else{
					$vk_answer = array(
						'code' => '5',
					);
				}
				if ($inst_key != NULL) {
					if ($inst_array->{"data"}[$news_count]->{"user_has_liked"} == 1) {
						$like = "like";
					}else{
						$like = "no_like";
					}
					$inst_answer[$count_inst] = array(
						'code' => '0',
						'name' => $inst_array->{"data"}[$news_count]->{"caption"}->{"from"}->{"username"},
						'photo' => $inst_array->{"data"}[$news_count]->{"caption"}->{"from"}->{"profile_picture"},
						'img' => $inst_array->{"data"}[$news_count]->{"images"}->{"low_resolution"}->{"url"},
						'post_text' => $inst_array->{"data"}[$news_count]->{"caption"}->{"text"},
						'like' => $like,
						'date' => date('d.m.Y в H:i:s',$inst_array->{"data"}[$news_count]->{"caption"}->{"created_time"}),
						'likes_count' => $inst_array->{"data"}[$news_count]->{"likes"}->{"count"},
					);
					$count_inst++;
				}else{
					$inst_answer = array(
						'code' => '5',
					);
				}
				if ($twit_key != NULL AND $tweets->{"errors"}[0]->{"code"} != "88") {
					if ($tweets[$news_count]->{'text'} == NULL AND $tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'} == NULL) {
						$twit_answer = array(
							'code' => '5',
						);
					}else{
						if ($tweets[$news_count]->{"favorited"} == 1) {
							$like = "twit_like";
						}else{
							$like = "no_twit_like";
						}
						$date_post = substr(str_replace("+0000", "", $tweets[$news_count]->{"created_at"}), 4);
						$twit_answer[$count_twit] = array(
							'code' => '0',
							'name' => $tweets[$news_count]->{'user'}->{'screen_name'},
							'photo' => $tweets[$news_count]->{'user'}->{'profile_image_url'},
							'img' => $tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'},
							'post_text' => $tweets[$news_count]->{'text'},
							'like' => $like,
							'date' => $date_post,
							'likes_count' => $tweets[$news_count]->{'favorite_count'},
							'reposts_count' => $tweets[$news_count]->{"retweet_count"},
						);
						$count_twit++;
					}
				}else{
					$twit_answer = array(
							'code' => '5',
					);
				}
				$news_count++;
			}
			$answer = array(
				'code' => '0',
				'vk_news' => json_encode($vk_array),
				'insta_news' => json_encode($inst_answer),
				'twit_news' => json_encode($twit_answer),
			);
		}
	}else{
		$answer = array(
			'msg' => 'Пожалуйста, авторизируйтесь',
			'code' => '100',
		);
	}
}else{
	$answer = array(
		'msg' => 'Пожалуйста пройдите авторизацию повторно',
		'code' => '10',
	);
}
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
print_r(json_encode($answer));
?>