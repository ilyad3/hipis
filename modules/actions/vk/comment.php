<?php
include '../../../connect.php';
include '../../classes/db.class.php';
include '../../functions/emoji.php';
include '../../functions/link.php';
include '../../functions/date.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$source_id = htmlspecialchars($_POST['source_id']);
$post_id = htmlspecialchars($_POST['post_id']);
$comment_text = htmlspecialchars($_POST['comment_text']);
$reply = htmlspecialchars($_POST['reply']);
session_start();
$session_key = $_SESSION['session_key'];
if ($session_key != NULL) {
	$session_row = $db->select(false,"vk_key","hip_users","session_key='$session_key'");
	if ($session_row != 0) {
		$vk_keys = explode(";", $session_row['vk_key']);
		$vk_key = $vk_keys[0];
		if ($reply == NULL) {
			$response = file_get_contents("https://api.vk.com/method/wall.addComment?owner_id=".$source_id."&post_id=".$post_id."&access_token=".$vk_key."&text=".urldecode($comment_text)."");
		}else{

		}
		$answer = json_decode($response);
		if (1 != NULL) {
			$vk_info_query = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&fields=photo_400_orig");
			$vk_info_answer = json_decode($vk_info_query);
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
					  		<input type='button' value='Ответить' class='answer_button' onclick=vk_comment('".$source_id."','".$post_id."');>
					  	</div>";
			}else{
				$answer_box = "";
			}
			$vk_comments = $vk_comments.$answer_box;
			echo $vk_comments;
		}else{
			echo "bad_comment";
		}
	}else{
		echo "need_refresh";
	}
}else{
	echo "need_refresh";
}
?>