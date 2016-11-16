<?php 
include '../../connect.php';
include_once '../../lib/Smarty.class.php';
include '../classes/db.class.php';
$db = new DB_class(db_host,db_name,db_user,db_pass);
session_start();
$session_key = $_SESSION['session_key'];
$smarty = new Smarty();
if ($session_key != NULL) {
	$session_row = $db->select(false,"news_info","hip_users","session_key='".$session_key."'");
	if ($session_row == 0) {
		header("location: http://hipis.ru?aa#auth");
	}else{
		$vk_key = $session_row['vk_key'];
		$fb_key = $session_row['fb_key'];
		$inst_key = $session_row['inst_key'];
		$twit_key = $session_row['twit_key'];
		if ($vk_key != NULL OR $fb_key != NULL OR $inst_key != NULL OR $twit_key != NULL) {
			header("location: http://hipis.ru/setting");
		}else{
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
			$body = "<link rel='stylesheet' type='text/css' href='css/vk.css'>
			<body class='aa_body'>
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
				<a href='' id='main_logo' class='reg_logo'>Hip</a>
				<div class='main_menu'>
					<a href='/' id='aa_btn'>Главная</a>
				</div>
				<div class='aa_info'>
					<p>Подключите аккаунт социальной сети</p>
					<p>Вы сможете подключить другие аккаунты позже</p>
					<a id='auth_vk' onclick='show_vk_auth();'>
						<img src='img/social_ico/vk.png'>
						<p>Вконтакте</p>
					</a>
					<a href='https://www.facebook.com/dialog/oauth?client_id=1669512176616451&redirect_uri=http://hipis.ru/modules/add_accounts/fb.php&scope=publish_actions,manage_pages,publish_pages'>
						<img src='img/social_ico/fb.png' class='fb_img'>
						<p>Facebook</p>
					</a>
					<a href='".$link."'>
						<img src='img/social_ico/twitter.png'>
						<p>Twitter</p>
					</a>
					<a href='https://api.instagram.com/oauth/authorize/?client_id=5c82e7cb10db41e1827064e9630390ae&redirect_uri=http://hipis.ru/modules/add_accounts/inst.php&response_type=code&scope=comments+relationships+likes'>
						<img src='img/social_ico/insta.png' class='insta_img'>
						<p>Instagram</p>
					</a>
				</div>
			</body>";
		}
	}
}else{
	header("location: http://hipis.ru?aa#auth");
}
$smarty->assign("body", $body);
$smarty->display('../../templates/main.tpl');
?>