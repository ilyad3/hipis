<?php
$post_text = htmlspecialchars(nl2br($_POST['post_text']));
$vk_post = htmlspecialchars($_POST['vk_post']);
$fb_post = htmlspecialchars($_POST['fb_post']);
$twit_post = htmlspecialchars($_POST['twit_post']);
session_start();
$session_key = $_SESSION['session_key'];
if ($post_text != NULL) {
	if ($vk_post !== "") {
		$vk_response = file_get_contents("http://hipis.ru/modules/actions/vk/wall_post.php?post_text=".urlencode($post_text)."&session_key=".$session_key."&accounts=".urlencode($vk_post)."");
		$vk_answer = $vk_response;
		if ($vk_answer == "need_refresh") {
			exit($vk_answer);
		}
	}
	if ($fb_post !== "") {
		$fb_response = file_get_contents("http://hipis.ru/modules/actions/fb/wall_post.php?post_text=".urlencode($post_text)."&session_key=".$session_key."&accounts=".urlencode($fb_post)."");
		$fb_answer = $fb_response;
		echo $fb_answer;
		//print_r("http://hipis.ru/modules/actions/fb/wall_post.php?post_text=".urlencode($post_text)."&session_key=".$session_key."&accounts=".urldecode($fb_post)."");
		// if ($fb_answer == "need_refresh") {
		// 	exit($fb_answer);
		// }
	}
	if ($twit_post !== "") {
		$twit_response = file_get_contents("http://hipis.ru/modules/actions/twit/wall_post.php?post_text=".urlencode($post_text)."&session_key=".$session_key."&accounts=".urlencode($twit_post)."");
		$twit_answer = $twit_response;
		if ($twit_answer == "need_refresh") {
			exit($twit_answer);
		}
	}
	//echo "success_posting";
}else{
	echo "need_text";
}
?>