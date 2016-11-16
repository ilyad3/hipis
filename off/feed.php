<?php
header('Access-Control-Allow-Origin: *');
include '../modules/functions/date.php';
include 'connect.php';
$token = "d52ef0c93ca381a3464b60a6df8d1574e3697d32e10390a6fb2770965b5939432d9bf9695be4cbfdf9bdf925a0898";
$end_time = htmlspecialchars($_POST['end_times']);
$next = htmlspecialchars($_POST['next']);
$persent = htmlspecialchars($_POST['passability']);
if ($persent != NULL AND $persent != "") {
	$persent_query = mysqli_query($con, "SELECT gid FROM `passability` WHERE persent='$persent' OR persent>'$persent'") or die(mysqli_error());
	if (mysqli_num_rows($persent_query) == 0) {
		$post_array[0] = array(
			'msg' => "Ой,похоже в нашей базе нет подходящих сообществ =( Попробуйте задать другие параметры",
			'error_number' => "001",
		);
		print_r(json_encode($post_array));
		exit();
	}
	while ($persent_row = mysqli_fetch_array($persent_query)) {
		$gid .= "g".$persent_row['gid'].",";
	}
	$gid = substr($gid, 0, -1);
	$query = "&source_ids=".$gid; 
}
$count_news = 20;
if ($token != NULL) {
	if ($next != NULL) {
		$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$token.$query."&filters=post,photo&count=".$count_news."&v=5.34&start_from=".$next."");
	}else if ($end_time != NULL) {
		$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$token.$query."&filters=post,photo&count=".$count_news."&v=5.34&start_time=".$end_time."");
	}else{
		$vk_query = file_get_contents("https://api.vk.com/method/newsfeed.get?access_token=".$token.$query."&filters=post,photo&count=".$count_news."&v=5.34");
	}
	$vk_answer = json_decode($vk_query);
	if ($vk_answer->{"error"}->{"error_code"} == '17') {
		$vk_key = "";
	}
	$vk_next = $vk_answer->{"response"}->{"next_from"};
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
		$owner_id = $vk_answer->{"response"}->{"items"}[$news_count]->{"source_id"};
		$owner_id = str_replace("-", "", $owner_id);
		$name = $id_users[$owner_id]['name'];
		if ($name != NULL) {
			$photo = $id_users[$owner_id]['photo'];
		}else{
			$name = $id_groups[$owner_id]['name'];
			$photo = $id_groups[$owner_id]['photo'];
		}
		if ($vk_answer->{'response'}->{"items"}[$news_count]->{"text"} != NULL) {
			$post_text = nl2br($vk_answer->{'response'}->{"items"}[$news_count]->{"text"})."<br><br>";
		}elseif ($vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"} != NULL) {
			$post_text = nl2br($vk_answer->{"response"}->{"items"}[$news_count]->{"copy_history"}[0]->{"text"})."<br><br>";
		}else{
			$post_text = "";
		}
		if ($vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
			if ($vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
				$vk_img = $vk_answer->{'response'}->{"items"}[$news_count]->{"attachments"}[0]->{"photo"}->{"photo_604"};
			}else{
				$vk_img = "";
			}
			$img = $vk_img;
		}elseif ($vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
			if ($vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"} != NULL) {
				$vk_img = $vk_answer->{'response'}->{"items"}[$news_count]->{"copy_history"}[0]->{"attachments"}[0]->{"photo"}->{"photo_604"};
			}else{
				$vk_img = "";
			}
			$img = $vk_img;
		}else{
			$img = "";
		}
		if ($img != NULL) {
			$path = $img;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$img = 'data:image/' . $type . ';base64,' . base64_encode($data);
		}
		if ($photo != NULL) {
			$path = $photo;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$photo = 'data:image/' . $type . ';base64,' . base64_encode($data);
		}
		$vk_date = check_date($vk_answer->{'response'}->{"items"}[$news_count]->{"date"});
		$pre_relise_query = mysqli_query($con, "SELECT persent FROM `passability` WHERE gid='$owner_id'") or die(mysqli_error($con));
		$pre_relise_row = mysqli_fetch_array($pre_relise_query);
		if ($pre_relise_row['persent'] != NUll) {
			$passability_per = $pre_relise_row['persent']."%";
		}else{
			$passability_per = "Неизвестна";
		}
		if ($news_count == 0) {
			$post_array[$news_count] = array(
				'post_avatar' => $photo,
				'post_name' => $name,
				'post_text' => $post_text,
				'post_img' => $img,
				'post_date' => $vk_date,
				'post_id' => $owner_id,
				'post_next' => $vk_next,
				'passability' => $passability_per,
				'post_end' => $vk_answer->{'response'}->{"items"}[0]->{"date"},
			);
		}else{
			$post_array[$news_count] = array(
				'post_avatar' => $photo,
				'post_name' => $name,
				'post_text' => $post_text,
				'post_img' => $img,
				'post_date' => $vk_date,
				'post_id' => $owner_id,
				'passability' => $passability_per,
			);
		}
		$news_count++;
		$img = "";
	}
}else{
	echo "bad_feed_answer";
}
print_r(json_encode($post_array));
?>