<?php
include '../../connect.php';
include '../classes/db.class.php';
include '../functions/emoji.php';
include '../functions/link.php';
include '../functions/date.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
session_start();
$session_key = $_SESSION['session_key'];
if ($session_key == NULL) {
	header("Location: http://hipis.ru/#auth");
}else{
	$user_row = $db->select(false,"news_info","hip_users","session_key='".$session_key."'");
	if ($user_row == 0) {
		header("Location: http://hipis.ru/#auth");
	}else{
		$vk_keys = explode(";", $user_row['vk_key']);
		$vk_key = $vk_keys[0];
		$fb_key = $user_row['fb_key'];
		$inst_key = $user_row['inst_key'];
		$twit_key = $user_row['twit_key'];
		$count_news = $user_row['count_news'];
		if ($count_news == 0) {
			$count_news = 4;
		}
		if ($vk_key == NULL AND $fb_key == NULL AND $inst_key == NULL AND $twit_key == NULL) {
			header("Location: http://hipis.ru/setting");
		}else{
			if ($vk_key != NULL) {
				$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$vk_key."&filters=post,phot&count=".$count_news."&v=5.34&start_from=".$_SESSION['vk_next']."");
				$vk_answer = json_decode($vk_query);
				$_SESSION['vk_next'] = $vk_answer->{"response"}->{"next_from"};
				$vk_info_query = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&fields=photo_400_orig");
				$vk_info_answer = json_decode($vk_info_query);
			}
			if ($inst_key != NULL) {
				$inst_query = file_get_contents($_SESSION['inst_next']);
				$inst_array = json_decode($inst_query);
				$_SESSION['inst_next'] = $inst_array->{"pagination"}->{"next_url"};
			}
			if ($twit_key != NULL) {
				require_once("../twit/twitteroauth/twitteroauth.php");
	 			$twit_array = explode("&", $twit_key);
				$notweets = $count_news + 1;
				$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
				$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
				$accesstoken = $twit_array[0];
				$accesstokensecret = $twit_array[1]; 	 
				$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=".$notweets."&max_id=".$_SESSION['twit_next']."");
				if ($tweets->{"errors"}[0]->{"code"} != "88") {
					$_SESSION['twit_next'] = $tweets[$count_news - 1]->{"id_str"};
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
				$source_id = $vk_answer->{"response"}->{"items"}[$news_count]->{"source_id"};
				$post_id = $vk_answer->{"response"}->{"items"}[$news_count]->{"post_id"};
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
						$vk_count_img = 0;
						while ($vk_count_img <= 10) {
							if ($vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"} != NULL) {
								$vk_img = "<img class='open_img' onclick=show_feed_img('".$vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"}."'); src='".$vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"}."'>";
							}else{
								$vk_img = "";
							}
							$img .= $vk_img;
							$vk_count_img++;
						}
					}elseif ($vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
						$vk_count_img = 0;
						while ($vk_count_img <= 10) {
							if ($vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"} != NULL) {
								$vk_img = "<img class='open_img' onclick=show_feed_img('".$vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"}."'); src='".$vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[$vk_count_img]->{"photo"}->{"photo_604"}."'>";
							}else{
								$vk_img = "";
							}
							$img .= $vk_img;
							$vk_count_img++;
						}
					}else{
						$img = "";
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"text"} != NULL) {
						$post_text = nl2br($vk_answer->{'response'}->{"items"}[$news_count]->{"text"})."<br><br>";
						$post_text = emoji_docomo_to_unified($post_text);
						$post_text = emoji_unified_to_html($post_text);
					}elseif ($vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"} != NULL) {
						$post_text = nl2br($vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"})."<br><br>";
						$post_text = emoji_docomo_to_unified($post_text);
						$post_text = emoji_unified_to_html($post_text);
					}else{
						$post_text = "";
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"likes"}->{"user_likes"} == 1) {
						$like = "like";
						$vk_button = "vk_unlike";
					}else{
						$like = "no_like";
						$vk_button = "vk_like";
					}
					if ($vk_answer->{'response'}->{"items"}[$news_count]->{"comments"}->{"can_post"} != 0) {
						$comments_btn = "
							<div id='footer_reposts' class='vk_comment_btn_".$post_id."' onclick=show_comments('".$post_id."');>
								<img src='img/post_icons/comment.png'><p>".$vk_answer->{'response'}->{"items"}[$news_count]->{"comments"}->{"count"}."</p>
							</div>";
					}else{
						$comments_btn = "";
					}
					$vk_date = check_date($vk_answer->{'response'}->{"items"}[$news_count]->{"date"});
					$body_vk = "<div class='wall_post' id='".$owner_id."'>
						<div id='post_head'>
							<img id='author_avatar' src='".$photo."'>
							<p>".$name."<br><font>".$vk_date."</font></p>
							<a href='https://vk.com/feed?w=wall".$source_id."_".$post_id."'  target='_blank' title='Сылка на пост в социальной сети'><img src='img/post_icons/vk.png'></a>
						</div>
						<div id='post_body' class='vk_body_".$post_id."' onDblClick=".$vk_button."('".$source_id."','".$post_id."');>
							".src_url($post_text)."
							".$img."
						</div>
						<div id='post_footer'>
							<div id='vk_like_post_".$post_id."' onclick=".$vk_button."('".$source_id."','".$post_id."');>
								<img src='img/post_icons/".$like.".png'><p>".$vk_answer->{'response'}->{"items"}[$news_count]->{"likes"}->{"count"}."</p>
							</div>
							<div id='footer_reposts' class='vk_repost_".$post_id."' onclick=vk_repost('".$source_id."','".$post_id."');>
								<img src='img/post_icons/repost.png'><p>".$vk_answer->{'response'}->{"items"}[$news_count]->{"reposts"}->{"count"}."</p>
							</div>
							".$comments_btn."
						</div>
					</div>";
					$vk_comment_response = file_get_contents("https://api.vk.com/method/wall.getComments?owner_id=".$source_id."&post_id=".$post_id."&need_likes=1&sort=asc&extended=1&v=5.34&lang=ru");
					$vk_comment = json_decode($vk_comment_response);
					$cuid = 1;
					$count_cusers = 0;
					while ($cuid > 0) {
						$cuid = $vk_comment->{"response"}->{"profiles"}[$count_cusers]->{"id"};
						$name = $vk_comment->{"response"}->{"profiles"}[$count_cusers]->{"first_name"}." ".$vk_comment->{"response"}->{"profiles"}[$count_cusers]->{"last_name"};
						$photo = $vk_comment->{"response"}->{"profiles"}[$count_cusers]->{"photo_100"};
						$info = $name."&".$photo;
						$cid_users[$cuid] = array(
							'name' => $name,
							'photo' => $photo,
							 );
						$count_cusers++;
					}
					$vk_count_comments = 0;
					while ($vk_count_comments < 15) {
						$vk_c_date = check_date($vk_comment->{'response'}->{"items"}[$vk_count_comments]->{"date"});
						if ($vk_comment->{"response"}->{"items"}[$vk_count_comments]->{"text"} != NULL) {
							$comment_owner = $vk_comment->{"response"}->{"items"}[$vk_count_comments]->{"from_id"};
							$comment_id = $vk_comment->{"response"}->{"items"}[$vk_count_comments]->{"id"};
							$vk_c_text = $vk_comment->{"response"}->{"items"}[$vk_count_comments]->{"text"};
							$vk_com_array = array('[',']');
							$vk_c_array = explode("]", $vk_c_text);
							$vk_c_to_id_array = str_replace($vk_com_array, "", $vk_c_array[0]);
							$vk_c_to_id_array = explode("|", $vk_c_to_id_array);
							$vk_c_to_name = str_replace($vk_com_array, " ", $vk_c_array[1]);
							if ($vk_c_array[0] == "[".$vk_c_to_id_array[0]."|".$vk_c_to_id_array[1]) {
								unset($vk_c_array[0]);
								$vk_c_text = implode(",", $vk_c_array);
								$vk_c_text = nl2br(emoji_unified_to_html(emoji_docomo_to_unified(src_url($vk_c_text))));
								$vk_c_text = "<a target='_blank' href='https://vk.com/".$vk_c_to_id_array[0]."'>".$vk_c_to_id_array[1]."</a>".$vk_c_text;
							}
							$vk_comments .= "
						  	<div class='comment'>
						  		<div class='comment_author_avatar'>
						  			<a href='https://vk.com/id".$comment_owner."'><img src='".$cid_users[$comment_owner]['photo']."'></a>
						  		</div>
						  		<a class='comment_author_name' href='https://vk.com/id".$comment_owner."'>".$cid_users[$comment_owner]['name']."</a>
						  		<p class='comment_time'>".$vk_c_date."</p>
						  		<div class='comment_text'>
						  			".$vk_c_text."
						  		</div>
						  		<div class='comment_events'>
						  			<div class='like_comment'>
						  				<img src='img/comment/no_like.png'>
						  				<font class='count_like'>".$vk_comment->{"response"}->{"items"}[$vk_count_comments]->{"likes"}->{"count"}."</font>
						  			</div>
						  			<div id='comment_layer'></div>
						  			<div class='answer_comment' onclick=vk_answer_to_comment('".$post_id."','".$source_id."','".$comment_id."')>Ответить</div>
						  		</div>
						  	</div>";
						}else{
							$vk_comments .= "";
						}
						$vk_count_comments++;
					}
					if ($source_id < 0) {
						$answer_box = "<div class='answer_box'>
							  		<div class='answer_img' id='answer_to_".$post_id."_".$source_id."'>
							  			<img src='".$vk_info_answer->{"response"}[0]->{"photo_400_orig"}."'>
							  		</div>
							  		<textarea class='answer_area' id='comment_area_to_".$post_id."_".$source_id."' placeholder='Прокомментируйте...'></textarea>
							  		<input type='button' value='Ответить' class='answer_button' id='vk_answer_btn_to_".$post_id."' onclick=vk_comment('".$source_id."','".$post_id."');>
							  	</div>";
					}else{
						$answer_box = "";
					}
					$vk_comments = "<div class='comment_box'  id='commewnt_to_".$post_id."'><div class='slide_c' id='vk_c_to_".$post_id."'>".$vk_comments."</div>".$answer_box."</div>";
					$body_vk = $body_vk.$vk_comments;
					$img = "";
					$vk_comments = "";
					unset($cid_users);
				}else{
					$body_vk = "";
				}
				// if ($fb_key != NULL) {
				// 	$fb_query = file_get_contents("https://graph.facebook.com/me/feed?filter=nf&access_token=".$fb_key."");
				// 	print_r(json_decode($fb_query));
				// }else{
					$body_fb = "";
				// }
				if ($inst_key != NULL) {
					$inst_c_count = 0;
					$inst_post_id = $inst_array->{"data"}[$news_count]->{"id"};
					if ($inst_array->{"data"}[$news_count]->{"user_has_liked"} == 1) {
						$like = "like";
					}else{
						$like = "no_like";
					}
					if ($inst_array->{"data"}[$news_count]->{"caption"}->{"from"}->{"username"} == NULL) {
						$user_name_inst = $inst_array->{"data"}[$news_count]->{"user"}->{"username"};
						$user_photo_inst = $inst_array->{"data"}[$news_count]->{"user"}->{"profile_picture"};
						$created_time = $inst_array->{"data"}[0]->{"created_time"};
					}else{
						$user_name_inst = $inst_array->{"data"}[$news_count]->{"caption"}->{"from"}->{"username"};
						$user_photo_inst = $inst_array->{"data"}[$news_count]->{"caption"}->{"from"}->{"profile_picture"};
						$created_time = $inst_array->{"data"}[$news_count]->{"caption"}->{"created_time"};
					}
					$post_text = emoji_docomo_to_unified(nl2br($inst_array->{"data"}[$news_count]->{"caption"}->{"text"}));
					$post_text = emoji_unified_to_html($post_text);
					$inst_date = check_date($created_time);
					$body_inst = "<div class='wall_post'>
						<div id='post_head'>
							<img id='author_avatar' src='".$user_photo_inst."'>
							<p>".$user_name_inst."<br><font>".$inst_date."</font></p>
							<a href='".$inst_array->{"data"}[$news_count]->{"link"}."' target='_blank' title='Сылка на пост в социальной сети'><img src='img/post_icons/insta.png'></a>
						</div>
						<div id='post_body'>
							".src_url($post_text)."<br><br>
							<img class='open_img' onclick=show_feed_img('".$inst_array->{"data"}[$news_count]->{"images"}->{"standard_resolution"}->{"url"}."'); src='".$inst_array->{"data"}[$news_count]->{"images"}->{"standard_resolution"}->{"url"}."'>
						</div>
						<div id='post_footer'>
							<div>
								<img src='img/post_icons/".$like.".png'><p>".$inst_array->{"data"}[$news_count]->{"likes"}->{"count"}."</p>
							</div>
							<div id='footer_reposts'>
								<img src='img/post_icons/repost.png'><p>0</p>
							</div>
							<div id='footer_reposts' class='inst_comment_btn_".$inst_post_id."' onclick=inst_show_comments('".$inst_post_id."');>
								<img src='img/post_icons/comment.png'><p></p>
						</div>
						</div>
					</div>";
					$inst_comments_answer = file_get_contents("https://api.instagram.com/v1/media/".$inst_post_id."/comments?access_token=".$inst_key."");
					$inst_c_answer = json_decode($inst_comments_answer);
					if ($inst_c_answer->{"data"}[$inst_c_count]->{"text"} != NULL) {
						while ($inst_c_count < 15) {
							$inst_c_date = check_date($inst_c_answer->{"data"}[$inst_c_count]->{"created_time"});
							if ($inst_c_date != NULL) {
								$inst_comments .= "
							  	<div class='comment'>
							  		<div class='comment_author_avatar'>
							  			<a href='https://instagram.com/".$inst_c_answer->{"data"}[$inst_c_count]->{"from"}->{"username"}."'><img src='".$inst_c_answer->{"data"}[$inst_c_count]->{"from"}->{"profile_picture"}."'></a>
							  		</div>
							  		<a class='comment_author_name' href='https://instagram.com/".$inst_c_answer->{"data"}[$inst_c_count]->{"from"}->{"username"}."'>".$inst_c_answer->{"data"}[$inst_c_count]->{"from"}->{"username"}."</a>
							  		<p class='comment_time'>".$inst_c_date."</p>
							  		<div class='comment_text'>
							  			".nl2br(emoji_unified_to_html(emoji_docomo_to_unified(src_url($inst_c_answer->{"data"}[$inst_c_count]->{"text"}))))."
							  		</div>
							  		<div class='comment_events'>
							  			<div class='like_comment'>
							  			</div>
							  		</div>
							  	</div>";
							}else{
								$inst_comments .= "";
							}
							$inst_c_count++;
						}
					}else{
						$inst_comments = "";
					}
					$inst_comments = "<div class='comment_box'  id='inst_comment_to_".$inst_post_id."'>
					<div class='slide_c' id='inst_c_to_".$inst_post_id."'>".$inst_comments."</div>
					<div class='answer_box'>
					  		<div class='answer_img'>
					  			<img src='256.png'>
					  		</div>
					  		<textarea class='answer_area' id='comment_area_to_".$inst_post_id."' disabled placeholder='В данный момент эту запись комментировать нельзя'></textarea>
					  		<input type='button' value='Ответить' disabled class='answer_button'>
					  	</div>
					  	</div>";
					$body_inst = $body_inst.$inst_comments;
				}else{
					$body_inst = "";
				}
				if ($twit_key != NULL AND $tweets->{"errors"}[0]->{"code"} != "88") { 
					$date_post = substr(str_replace("+0000", "", $tweets[$news_count]->{"created_at"}), 4);
					$date_post = twit_date($date_post);
					if ($tweets[$news_count]->{'text'} == NULL AND $tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'} == NULL) {
						$body_twit = "";
					}else{
						if ($tweets[$news_count]->{"favorited"} == 1) {
							$like = "twit_like";
							$twit_button = "twit_unlike";
						}else{
							$like = "no_twit_like";
							$twit_button = "twit_like";
						}
						if ($tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'} != NULL) {
							$twit_img = "<img class='open_img' onclick=show_feed_img('".$tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'}."'); src='".$tweets[$news_count]->{'entities'}->{'media'}[0]->{'media_url'}."'>";
						}else{
							$twit_img = '';
						}
						$post_text = emoji_docomo_to_unified(nl2br($tweets[$news_count]->{'text'}));
						$post_text = emoji_unified_to_html($post_text);	
						$body_twit = "<div class='wall_post'>
							<div id='post_head'>
								<img id='author_avatar' src='".$tweets[$news_count]->{'user'}->{'profile_image_url'}."'>
								<p>".$tweets[$news_count]->{'user'}->{'screen_name'}."<br><font>".$date_post."</font></p>
								<a  target='_blank' href='https://twitter.com/".$tweets[$news_count]->{'user'}->{'screen_name'}."/status/".$tweets[$news_count]->{"id_str"}."' title='Сылка на пост в социальной сети'><img src='img/post_icons/twit.png'></a>
							</div>
							<div id='post_body' class=twit_body_".$tweets[$news_count]->{"id_str"}."' onDblClick=".$twit_button."('".$tweets[$news_count]->{"id_str"}."');>
								".src_url($post_text)."<br><br>
								".$twit_img."
							</div>
							<div id='post_footer'>
								<div id='twit_like_".$tweets[$news_count]->{"id_str"}."' onclick=".$twit_button."('".$tweets[$news_count]->{"id_str"}."');>
									<img src='img/post_icons/".$like.".png'><p>".$tweets[$news_count]->{'favorite_count'}."</p>
								</div>
								<div id='footer_reposts' class='twit_repost repost_twit_".$tweets[$news_count]->{"id_str"}."' onclick=twit_repost('".$tweets[$news_count]->{"id_str"}."');>
									<img src='img/post_icons/repost.png'><p>".$tweets[$news_count]->{"retweet_count"}."</p>
								</div>
							</div>
					</div>";
					}
				}else{
					$body_twit = "";
				}
				$news_count++;
				$body .= $body_vk.$body_fb.$body_inst.$body_twit;
			}
		}
	}
}
			function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
				$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
				return $connection;
			}
echo $body;
?>