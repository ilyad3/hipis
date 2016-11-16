<?php
include_once '../../lib/Smarty.class.php';
include '../../connect.php';
include '../classes/db.class.php';
require_once("../twit/twitteroauth/twitteroauth.php");
$db = new DB_class(db_host,db_name,db_user,db_pass);
$smarty = new Smarty();
session_start();
$session_key = $_SESSION['session_key'];
$user_row = $db->select(false,"news_info","hip_users","session_key='".$session_key."'");
if ($user_row == 0) {
		header("Location: http://hipis.ru/?posts#auth");
}else{
	$vk_array = explode("ls_keys_check;", $user_row['vk_key']);
	$vk_count = count($vk_array);
	$fb_array = explode("ls_keys_check;", $user_row['fb_key']);
	$fb_count = count($fb_array);
	$inst_array = explode("ls_keys_check;", $user_row['inst_key']);
	$inst_count = count($inst_array);
	$twit_array = explode("ls_keys_check;", $user_row['twit_key']);
	$twit_count = count($twit_array);
	$keys_array = array($vk_key,$fb_key,$inst_key,$twit_key);
	$count_keys = 0;
	$count_vk = 0;
	$count_fb = 0;
	$count_twit = 0;
	while ($vk_count > $count_vk) {
		$vk_keys = explode(";", $vk_array[$count_vk]);
		$vk_key = $vk_keys[0];
		$vk_id = $vk_keys[1];
		$vk_response = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&name_case=Nom&v=5.8&lang=ru");
		$vk_answer = json_decode($vk_response);
		$vk_name = $vk_answer->{"response"}[0]->{"first_name"}." ".$vk_answer->{"response"}[0]->{"last_name"};
		if ($vk_key != NULL) {
			$vk_body .= "<div onclick=change_post('vk','".$vk_key."');>
						<div class='change_btn change_btn_active' id='vk_post_change_".$vk_key."'></div>
						<font  title='".$vk_name."'>Vkontakte</font>
					</div><br>";
			$vk_check .= "<input type='checkbox' id='vk_check_".$vk_key."' class='vk_button ".$vk_key."_".$vk_id."' value='1' checked='checked'>";
		}else{
			$vk_body .="";
			$vk_check .= "";
		}
		$count_vk++;
	}
	while ($fb_count > $count_fb) {
		$fb_keys = explode(";", $fb_array[$count_fb]);
		$fb_key = $fb_keys[0];
		$fb_id = $fb_keys[1];
		$fb_response = file_get_contents("https://graph.facebook.com/me?fields=name&version=v2.5&access_token=".$fb_key."");
		$fb_answer = json_decode($fb_response);
		$facebook_name = $fb_answer->{"name"};
		if ($fb_key != NULL) {
			$fb_body .= "<div onclick=change_post('fb','".$fb_key."');>
						<div class='change_btn change_btn_active' id='fb_post_change_".$fb_key."'></div>
						<font  title='".$facebook_name."'>Facebook</font>
					</div><br>";
			$fb_check .= "<input type='checkbox' id='fb_check_".$fb_key."' class='fb_button ".$fb_key."'  value='1' checked='checked'>";
		}else{
			$fb_body .="";
			$fb_check .= "";
		}
		$count_fb++;
	}
	while ($twit_count > $count_twit) {
		$twit_arrays = explode("&", $twit_array[$count_twit]);
		$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
		$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
		$accesstoken = $twit_arrays[0];
		$t_user_id_array = explode("-", $twit_arrays[0]);
		$t_user_id = $t_user_id_array[0];
		$accesstokensecret = $twit_arrays[1]; 	 
		$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
		$tweets = $connection->get("https://api.twitter.com/1.1/users/show.json?user_id=".$t_user_id."");
		$twit_name = $tweets->{"screen_name"};
		if ($t_user_id != NULL) {
			$twit_body .= "<div onclick=change_post('twit','".$t_user_id."');>
						<div class='change_btn change_btn_active' id='twit_post_change_".$t_user_id."'></div>
						<font  title='".$twit_name."'>Twitter</font>
					</div><br>";
			$twit_check .= "<input type='checkbox' id='twit_check_".$t_user_id."' class='twit_button ".$t_user_id."_".$accesstokensecret."' value='1' checked='checked'>";
		}else{
			$twit_body .="";
			$twit_check .= "";
		}
		$count_twit++;
	}
	while ($count_keys < 4) {
		if ($keys_array[$count_keys] != NULL) {
			$answer_array[$count_keys] = "change_btn_active";
			$check_array[$count_keys] = "checked='checked'";
		}else{
			$answer_array[$count_keys] = "change_btn_decided";
			$check_array[$count_keys] = "";
		}
		$count_keys++;
	}
}
$body = "<div id='post_area'>
		<textarea id='post_text_area' placeholder='Что у Вас нового?'></textarea>
		<input type='button' id='post_btn' value='Отправить' onclick='posting();'>
		<div id='post_change'>
			<font>Отправить в:</font>
			<div id='change_post'>
				<font id='change_text' onclick='show_post_menu();'>Все соц.сети</font> <img src='img/post_layer.png' onclick='show_post_menu();'>
				<div id='post_menu'>
					<div id='post_inputs'>
						".$vk_check."
						<input type='checkbox' id='inst_check' value='1'>
						".$fb_check."
						".$twit_check."
					</div>
					".$vk_body."
					<div onclick=change_post('inst');>
						<div class='change_btn change_btn_decided' id='inst_post_change'></div>
						<font>Instagram</font>
					</div><br>
					".$fb_body."
					".$twit_body."
				</div>
			</div>
		</div>
	</div>";
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
$menu = "<li><a href='/feed'><img src='img/menu_ico/news_ico.png'></a></li>
			<li><a href='/setting'><img src='img/menu_ico/setting_ico.png'></a></li>
			<li><a href='/posts'><img src='img/menu_ico/posts_active_ico.png'></a></li>";
$smarty->assign("menu", $menu);
$smarty->assign("title", "Страница отправки постов");
$body = "<div id='post_page'>".$body."</div><div id='js'><script language='javascript'>var scroll_id = '0'</script></div>";
$smarty->assign("body", $body);
$smarty->display('../../templates/feed.tpl');
?>