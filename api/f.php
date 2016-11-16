<?php
header('Access-Control-Allow-Origin: *'); 
$token = "538c085381fa5e4170c0433ac6c7a767fdf9ab051a43ef7d7f7d5239b56329a8872c9e43346a940b62e61f777636e";
$count_news = 10;
if ($token != NULL) {
	$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$token."&filters=post,photo&count=".$count_news."&v=5.34");
	$vk_answer = json_decode($vk_query);
	if ($vk_answer->{"error"}->{"error_code"} == '17') {
		$vk_key = "";
	}
	$_SESSION['vk_next'] = $vk_answer->{"response"}->{"next_from"};
	$vk_info_query = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&fields=photo_400_orig");
				$vk_info_answer = json_decode($vk_info_query);
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
	while ($news_count < $count_news) {
		$post_text[$news_count] = nl2br($vk_answer->{'response'}->{"items"}[$news_count]->{"text"});
		$news_count++;
	}
}else{
	echo "bad_feed_answer";
}
print_r(json_encode($vk_answer));
?>