<?php 
include_once '../../lib/Smarty.class.php';
include '../../connect.php';
include '../classes/db.class.php';
include '../functions/translite.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
$smarty = new Smarty();
session_start();
$session_key = $_SESSION['session_key'];
define('CONSUMER_KEY', 'MsIeQx1qtAcSsAt8IdGoi3HO3');
define('CONSUMER_SECRET', 'AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb');

define('REQUEST_TOKEN_URL', 'https://api.twitter.com/oauth/request_token');
define('AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');
define('ACCESS_TOKEN_URL', 'https://api.twitter.com/oauth/access_token');
define('ACCOUNT_DATA_URL', 'https://api.twitter.com/1.1/users/show.json');

define('CALLBACK_URL', 'http://hipis.ru/modules/add_accounts/twit.php');


define('URL_SEPARATOR', '&');

$oauth_nonce = md5(uniqid(rand(), true));
$oauth_timestamp = time();

$params = array(
    'oauth_callback=' . urlencode(CALLBACK_URL) . URL_SEPARATOR,
    'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
    'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
    'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
    'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
    'oauth_version=1.0'
);

$oauth_base_text = implode('', array_map('urlencode', $params));
$key = CONSUMER_SECRET . URL_SEPARATOR;
$oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(REQUEST_TOKEN_URL) . URL_SEPARATOR . $oauth_base_text;
$oauth_signature = base64_encode(hash_hmac('sha1', $oauth_base_text, $key, true));


$params = array(
    URL_SEPARATOR . 'oauth_consumer_key=' . CONSUMER_KEY,
    'oauth_nonce=' . $oauth_nonce,
    'oauth_signature=' . urlencode($oauth_signature),
    'oauth_signature_method=HMAC-SHA1',
    'oauth_timestamp=' . $oauth_timestamp,
    'oauth_version=1.0'
);
$url = REQUEST_TOKEN_URL . '?oauth_callback=' . urlencode(CALLBACK_URL) . implode('&', $params);

$response = file_get_contents($url);
parse_str($response, $response);

$oauth_token = $response['oauth_token'];
$oauth_token_secret = $response['oauth_token_secret'];



$link = AUTHORIZE_URL . '?oauth_token=' . $oauth_token;
if ($session_key != NULL) {
	$info_row = $db->select(false,'id,vk_key,fb_key,inst_key,twit_key,email,count_news,mail_key','hip_users',"session_key='".$session_key."'");
	if ($info_row == "0") {
		header("location: http://hipis.ru?setting#auth");
	}else{
		$id = $info_row['id'];
		$vk_array = explode("ls_keys_check;", $info_row['vk_key']);
		$vk_count = count($vk_array);
		$fb_array = explode("ls_keys_check;", $info_row['fb_key']);
		$fb_count = count($fb_array);
		$inst_array = explode("ls_keys_check;", $info_row['inst_key']);
		$inst_count = count($inst_array);
		$twit_array = explode("ls_keys_check;", $info_row['twit_key']);
		$twit_count = count($twit_array);
		$vk_keys = explode(";", $info_row['vk_key']);
		$vk_key = $vk_keys[0];
		$fb_key = $info_row['fb_key'];
		$inst_key = $info_row['inst_key'];
		$twit_key = $info_row['twit_key'];
		$mail_key = $info_row['mail_key'];
		$email = $info_row['email'];
		$count_news = $info_row['count_news'];
		if ($count_news == 0) {
			$count_news = "4 (по умолчанию)";
		}
		$mails_replace = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "c", "C");
		if ($vk_key != NULL) {
			$count_vk = 0;
			while ($vk_count > $count_vk) {
				$vk_keys = explode(";", $vk_array[$count_vk]);
				$vk_key = $vk_keys[0];
				$vk_response = file_get_contents("https://api.vk.com/method/users.get?user_ids=".$vk_keys[1]."&name_case=Nom&v=5.8&lang=ru");
				$vk_answer = json_decode($vk_response);
				$vk_name = $vk_answer->{"response"}[0]->{"first_name"}." ".$vk_answer->{"response"}[0]->{"last_name"};
				$vk_answer = "<a href='https://vk.com/id".$vk_keys[1]."' class='vk_key_".$vk_key."' id='auth_vk' target='_blank'>".$vk_name."</a>";
				$vk_delete = "<img src='img/setting_ico/delete.png' onclick=delete_key('vk_key','".$vk_key."'); id='vk_key_".$vk_key."'>";
				$vk_body .= "<div id='vk_".$vk_key."' class='vk'>
					<div id='social_icons'>
						<img src='img/setting_ico/vk.png'>
					</div>
					".$vk_answer."
					".$vk_delete."
				</div>";
				$count_vk++;
			}
			$vk_body .= "<div class='vk'>
					<div id='social_icons'>
						<img src='img/setting_ico/vk.png'>
					</div>
					<a class='vk_key' onclick='show_vk_auth();' id='auth_vk'>Подключить ещё</a>
				</div>";
		}else{
			$vk_answer = "<a class='vk_key' onclick='show_vk_auth();' id='auth_vk'>Не подключено</a>";
			$vk_delete = "";
			$vk_body = "<div class='vk'>
					<div id='social_icons'>
						<img src='img/setting_ico/vk.png'>
					</div>
					".$vk_answer."
					".$vk_delete."
				</div>";
		}
		if ($fb_key != NULL) {
			$count_fb = 0;
			while ($fb_count > $count_fb) {
				$fb_key = $fb_array[$count_fb];
				$fb_response = file_get_contents("https://graph.facebook.com/me?fields=name&version=v2.5&access_token=".$fb_key."");
				$fb_answer = json_decode($fb_response);
				$facebook_name = $fb_answer->{"name"};
				$fb_answer = "<a class='fb_key_".$fb_key."' >".$facebook_name."</a>";
				$fb_delete = "<img src='img/setting_ico/delete.png' onclick=delete_key('fb_key','".$fb_key."'); id='fb_key_".$fb_key."'>";
				$fb_body .= "<div id='fb_".$fb_key."' class='fb'>
					<div id='social_icons'>
						<img src='img/setting_ico/fb.png'>
					</div>
					".$fb_answer."
					".$fb_delete."
				</div>";
				$count_fb++;
			}
			$fb_body .= "<div class='fb'>
					<div id='social_icons'>
						<img src='img/setting_ico/fb.png'>
					</div>
					<a class='fb_key' href='https://www.facebook.com/dialog/oauth?client_id=1669512176616451&redirect_uri=http://hipis.ru/modules/add_accounts/fb.php&scope=publish_actions,manage_pages,publish_pages,user_posts'>Подключить ещё</a>
				</div>";
		}else{
			$fb_answer = "<a class='fb_key' href='https://www.facebook.com/dialog/oauth?client_id=1669512176616451&redirect_uri=http://hipis.ru/modules/add_accounts/fb.php&scope=publish_actions,manage_pages,publish_pages,user_posts'>Не подключено</a>";
			$fb_delete = "";
			$fb_body = "<div class='fb'>
					<div id='social_icons'>
						<img src='img/setting_ico/fb.png'>
					</div>
					".$fb_answer."
					".$fb_delete."
				</div>";
		}
		if ($inst_key != NULL) {
			$count_inst = 0;
			while ($inst_count > $count_inst) {
				$inst_key = $inst_array[$count_inst];
				$inst_user_id_array = explode(".", $inst_key);
				$inst_user_id = $inst_user_id_array[0];
				$inst_response = file_get_contents("https://api.instagram.com/v1/users/".$inst_user_id."?access_token=".$inst_key."");
				$inst_answer = json_decode($inst_response);
				$inst_name = $inst_answer->{"data"}->{"username"};
				$inst_answer = "<a class='inst_key_".$inst_key."' >@".$inst_name."</a>";
				$inst_delete = "<img src='img/setting_ico/delete.png' onclick=delete_key('inst_key','".$inst_key."'); id='inst_key_".$inst_key."'>";
				$inst_body .= "<div id='inst_".$inst_key."' class='inst'>
					<div id='social_icons'>
						<img src='img/setting_ico/inst.png'>
					</div>
					".$inst_answer."
					".$inst_delete."
				</div>";
				$count_inst++;
			}
			$inst_body .= "<div class='inst'>
					<div id='social_icons'>
						<img src='img/setting_ico/inst.png'>
					</div>
					<a class='inst_key' href='https://api.instagram.com/oauth/authorize/?client_id=5c82e7cb10db41e1827064e9630390ae&redirect_uri=http://hipis.ru/modules/add_accounts/inst.php&response_type=code&scope=comments+relationships+likes'>Подключить ещё</a>
				</div>";
		}else{
			$inst_answer = "<a class='inst_key' href='https://api.instagram.com/oauth/authorize/?client_id=5c82e7cb10db41e1827064e9630390ae&redirect_uri=http://hipis.ru/modules/add_accounts/inst.php&response_type=code&scope=comments+relationships+likes'>Не подключено</a>";
			$inst_delete = "";
			$inst_body = "<div class='inst'>
					<div id='social_icons'>
						<img src='img/setting_ico/inst.png'>
					</div>
					".$inst_answer."
					".$inst_delete."
				</div>";
		}
		if ($twit_key != NULL) {
			$count_twit = 0;
			require_once("../twit/twitteroauth/twitteroauth.php");
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
				$twit_answer = "<a class='twit_key_".$accesstoken."' >@".$twit_name."</a>";
				$twit_delete = "<img src='img/setting_ico/delete.png' onclick=delete_key('twit_key','".$accesstoken."'); id='twit_key_".$accesstoken."'>";
				$twit_body .= "<div id='twit_".$accesstoken."' class='twit'>
					<div id='social_icons'>
						<img src='img/setting_ico/twit.png'>
					</div>
					".$twit_answer."
					".$twit_delete."
				</div>";
				$count_twit++;
			}
			$twit_body .= "<div class='twit'>
					<div id='social_icons'>
						<img src='img/setting_ico/twit.png'>
					</div>
					<a class='twit_key' href='".$link."'>Подключить ещё</a>
				</div>";
		}else{
			$twit_answer = "<a class='twit_key' href='".$link."'>Не подключено</a>";
			$twit_delete = "";
			$twit_body = "<div class='twit'>
					<div id='social_icons'>
						<img src='img/setting_ico/twit.png'>
					</div>
					".$twit_answer."
					".$twit_delete."
				</div>";
		}
		if ($twit_key != NULL) {
			require_once("../twit/twitteroauth/twitteroauth.php");
	 		$twit_array = explode("&", $twit_key);
			$consumerkey = "MsIeQx1qtAcSsAt8IdGoi3HO3";
			$consumersecret = "AnRychPSSAVvZgd5aD2mTasr6fSoNXsYQB8hLkZfUWA857yrOb";
			$accesstoken = $twit_array[0];
			$t_user_id_array = explode("-", $twit_array[0]);
			$t_user_id = $t_user_id_array[0];
			$accesstokensecret = $twit_array[1]; 	 
			$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);	 
			$tweets = $connection->get("https://api.twitter.com/1.1/users/show.json?user_id=".$t_user_id."");
			$twit_name = $tweets->{"screen_name"};
		}
		if ($mail_key != NULL) {
			$sig = md5("app_id=738745method=users.getInfosecure=1session_key=".$mail_key."efcf9b2b109f03c024d6f153146f7f3e");
			$mail_response = file_get_contents("http://www.appsmail.ru/platform/api?method=users.getInfo&app_id=738745&session_key=".$mail_key."&secure=1&sig=".$sig."");
			$mail_answer = json_decode($mail_response);
			$mail_name = $mail_answer[0]->{"nick"};
		}
		$email = str_replace($mails_replace, "*", $email);
		if ($mail_key == NULL) {
			//$mail_answer = "<a class='inst_key' href='https://connect.mail.ru/oauth/authorize?client_id=738745&response_type=code&redirect_uri=http://hipis.ru/modules/add_accounts/mail.php&scope=stream%20messages%20events%20guestbook%20photos'>Не подключено</a>";
			$mail_delete = "";
			$mail_answer = "<a class='inst_key' href=''>Временно недоступно</a>";
		}else{
			$mail_answer = "<a class='mail_key' >Временно недоступно</a>";
			//$mail_delete = "<img src='img/setting_ico/delete.png' onclick=delete_key('mail_key'); id='mail_key'>";
			$mail_delete = "";
		}
		$body = "<div id='page'>
		<div id='main_bg'>
			<div class='main_auth' id='vk_auth'>
			<div id='auth_error'><img src='img/setting_ico/vk_auth.png'></div>
				<p>Авторизация</p>
				<div id='auth_inputs'>
					<p>Номер телефона</p>
					<input type='text' id='vk_login'>
					<p>Пароль</p>
					<input type='password' id='vk_password'>
				</div>
				<p id='auth_btn' onclick='vk_auth();'>Войти</p>
			</div>
		</div>
		<!--<input id='search' placeholder='Поиск...'>-->
		<div id='page_title'>
			<img src='img/menu_ico/setting_active_ico.png'>
			<p>Настройки</p>
		</div>
		<div id='block_title'>Аккаунты:</div>
		<div id='socials'>
			".$vk_body."
			".$fb_body."
			".$twit_body."
			".$inst_body."
			<div class='mail'>
				<div id='social_icons'>
					<img src='img/setting_ico/mail.png'>
				</div>
				".$mail_answer."
				".$mail_delete."
			</div>
			<div class='ok'>
				<div id='social_icons'>
					<img src='img/setting_ico/ok.png'>
				</div>
				<a class='key' >Времено недоступно</a>
			</div>
			
		</div>
		<div id='block_title' class='title_under_btn'>Приватность:</div>
		<div id='setting_inputs'>
			<p>Ваш номер в системе:<font> ".$id."</font></p>
		</div>
		<div id='setting_inputs'>
			<p>Смена пароля</p>
			<input type='password' id='pass' placeholder='Действующий пароль'><div class='setting_error' id='pass_error'><img src='img/setting_ico/setting_error_layer.png'><font>Неверный пароль</font></div>
			<input type='password' id='new_pass' placeholder='Новый пароль'><div class='setting_error' id='new_pass_error'><img src='img/setting_ico/setting_error_layer.png'><font>Неверный пароль</font></div>
			<input type='password' id='repeat_new_pass' placeholder='Повторите новый пароль'><div class='setting_error' id='repeat_new_pass_error'><img src='img/setting_ico/setting_error_layer.png'><font>Неверный пароль</font></div>
			<input type='button' value='Сменить пароль' onclick='change_pass();'>
		</div>
		<div id='setting_inputs'>
			<p>E-mail</p>
			<p id='setting_email'>Текущий:<font> ".$email."</font></p>
			<input type='text' placeholder='Новый E-mail' id='new_mail'><div class='setting_error' id='mail_error'><img src='img/setting_ico/setting_error_layer.png'><font>Неверный пароль</font></div>
			<input type='button' value='Сменить E-mail' onclick='change_mail();'>
		</div>
		<div id='setting_inputs'>
			<p>Количество новостей загружаемых за один раз (если поставить 0,то будет количество по умолчанию равное 4)</p>
			<p id='setting_count'>Текущее:<font> ".$count_news."</font></p>
			<input type='text' placeholder='Новое количество' id='new_count'><div class='setting_error' id='count_error'><img src='img/setting_ico/setting_error_layer.png'><font>Неверный пароль</font></div>
			<input type='button' value='Сменить количество' onclick='change_count();'>
		</div>
	</div>";
	}
}else{
	header("location: http://hipis.ru?setting#auth");
}
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
$menu = "<li><a href='/feed'><img src='img/menu_ico/news_ico.png'></a></li>
			<li><a href='/setting'><img src='img/menu_ico/setting_active_ico.png'></a></li>
			<li><a href='/posts'><img src='img/menu_ico/posts_ico.png'></a></li>";
$smarty->assign("menu", $menu);
$smarty->assign("title", "Настройки");
$smarty->assign("body", $body);
$smarty->display('../../templates/feed.tpl');
?>