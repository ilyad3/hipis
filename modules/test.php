<meta charset='utf-8'>
<?php
include 'functions/date.php';
// $response = file_get_contents("https://api.vk.com/method/wall.repost?object=wall-30666517_1194799&access_token=a874acddbfaaaf6d709d35409415b8d9733050b07c204b69fb10c10d2ef646d1376d1a05d646fb71155ee7f3b2877");
// $answer = json_decode($response);
// print_r($answer->{"response"}->{"reposts_count"});
// print_r($vk_answer);
//$response = file_get_contents("https://api.vk.com/method/friends.get?user_id=137534777&fields=nickname,domain,sex,bdate,city,country,timezone,photo_50,photo_100,photo_200_orig,has_mobile,contacts,education,online,relation,last_seen,status,can_write_private_message,can_see_all_posts,can_post,universities");
// $response = file_get_contents("https://api.vk.com/method/users.get?user_ids=137534777&fields=photo_400_orig");
// $answer = json_decode($response);
// print_r($answer->{"response"}[0]->{"photo_400_orig"});
// $response = file_get_contents("https://api.instagram.com/v1/media/1097231368495940611_1192546904/comments?access_token=1950976039.5c82e7c.963c0fa44148439581d575bb5106ef0a");
// $answer = json_decode($response);
// print_r($answer);
// $test = check_date("1445539853");
// echo $test;
// $test = "https://connect.mail.ru/oauth/authorize?client_id=738745&response_type=code&redirect_uri=http://hipis.ru/modules/add_accounts/mail.php&scope=stream%20messages%20events%20guestbook%20photos";
// $sig = md5("app_id=738745method=stream.getsecure=1session_key=457f7d42b7b002cf16af422531263c8aefcf9b2b109f03c024d6f153146f7f3e");
// echo $sig;
// создаем оба ресурса cURL
$ch1 = curl_init();
$ch2 = curl_init();

// устанавливаем URL и другие соответствующие опции
curl_setopt($ch1, CURLOPT_URL, "https://api.vk.com/method/newsfeed.get?access_token=8a5424eb3ed42a4d3f7d32d5ad901f3bd0014ca703dc4d27f0da5d82628ddc08424acf1e31913157eb7fe3f318988&filters=post,photo&count=4&v=5.34");
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_URL, "https://api.instagram.com/v1/users/self/feed?access_token=1950976039.5c82e7c.963c0fa44148439581d575bb5106ef0a&count=4");
curl_setopt($ch2, CURLOPT_HEADER, 0);

//создаем набор дескрипторов cURL
$mh = curl_multi_init();

//добавляем два дескриптора
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);

$active = null;
//запускаем дескрипторы
do {
   $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}
//закрываем все дескрипторы
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);

?>