<?php
function check_date($info_date)
{
	$moth_array = array(
		'1' => "Января",
		'2' => "Февраля",
		'3' => "Марта",
		'4' => "Апреля",
		'5' => "Мая",
		'6' => "Июня",
		'7' => "Июля",
		'8' => "Августа",
		'9' => "Сенября",
		'10' => "Октябя",
		'11' => "Ноября",
		'12' => "Декабря",
	);
	$date_now = new DateTime();
	$date = new DateTime();
	$date->modify('-1 day');
	$y_date = $date->format('d.m.Y');
	if (date('d.m.Y',$info_date) == date("d.m.Y")) {
		$time_post = date('d.m.Y H:i:s',$info_date +(7*3600));
		$time_post = new DateTime($time_post);
		$diff = $date->diff($time_post);
		$diff_s = 60 - $diff->format("%s");
		$diff_i = 59 - $diff->format("%i");
		$diff_h = $diff->format("%h");
		if ($diff_s < 60 AND $diff_i == 0 AND $diff_h == 6) {
			if ($diff_s < 3) {
				$c_date = "Только что";
			}else{
				$c_date = $diff_s." сек. назад";
			}
		}elseif ($diff_i < 16 AND $diff_h == 6) {
			if ($diff_i < 3 AND $diff_i != 1) {
				$c_date = "Пару минут назад";
			}elseif ($diff_i == 1) {
				$c_date = "Минуту назад";
			}else{
				$c_date = $diff_i." мин. назад";
			}
		}else{
			$c_date = "Сегодня в ".date('H:i',$info_date +(7*3600));
		}
	}elseif (date('d.m.Y',$info_date) == $y_date) {
		$c_date = "Вчера в ".date('H:i',$info_date +(7*3600));
	}else{
		if (date('Y',$info_date) == date("Y")) {
			$m_date = date('m',$info_date);
			$c_date = date('d ',$info_date).$moth_array[$m_date].date(' в H:i',$info_date +(7*3600));
		}else{
			$m_date = date('m',$info_date);
			$c_date = date('d ',$info_date).$moth_array[$m_date].date('Y г.',$info_date).date(' в H:i',$info_date +(7*3600));
		}
	}
	return $c_date;
}

function twit_date($info_date)
{
	$twit_moth_array = array(
		'Jan' => "1",
		'Feb' => "2",
		'Mar' => "3",
		'Apr' => "4",
		'May' => "5",
		'Jun' => "6",
		'Jul' => "7",
		'Aug' => "8",
		'Sep' => "9",
		'Oct' => "10",
		'Nov' => "11",
		'Dec' => "12",
	);
	$moth_array = array(
		'1' => "Января",
		'2' => "Февраля",
		'3' => "Марта",
		'4' => "Апреля",
		'5' => "Мая",
		'6' => "Июня",
		'7' => "Июля",
		'8' => "Августа",
		'9' => "Сенября",
		'10' => "Октябя",
		'11' => "Ноября",
		'12' => "Декабря",
	);
	$info_date_array = explode(" ", $info_date);
	$date_month = $twit_moth_array[$info_date_array[0]];
	$date_year = $info_date_array[4];
	$time_array = explode(":", $info_date_array[2]);
	$time = $time_array[0].":".$time_array[1];
	$date = new DateTime();
	$date->modify('-1 day');
	$y_date = $date->format('d.m.Y');
	$twit_date = $info_date_array[1].$date_month.$date_year;
	if ($twit_date == date("d.m.Y")) {
		$time_post = $time_array[0]."-".$time_array[1]."-".$time_array[2];
		$time_post = new DateTime($time_post);
		$diff = $date->diff($time_post);
		$diff_s = 60 - $diff->format("%s");
		$diff_i = 59 - $diff->format("%i");
		$diff_h = $diff->format("%h");
		if ($diff_s < 60 AND $diff_i == 0 AND $diff_h == 6) {
			if ($diff_s < 3) {
				$c_date = "Только что";
			}else{
				$c_date = $diff_s." сек. назад";
			}
		}elseif ($diff_i < 16 AND $diff_h == 6) {
			if ($diff_i < 3 AND $diff_i != 1) {
				$c_date = "Пару минут назад";
			}elseif ($diff_i == 1) {
				$c_date = "Минуту назад";
			}else{
				$c_date = $diff_i." мин. назад";
			}
		}else{
			$c_date = "Сегодня в ".date('H:i',$info_date +(7*3600));
		}
		$c_date = "Сегодня в ".$time;
	}elseif ($twit_date == $y_date) {
		$c_date = "Вчера в ".$time;
	}else{
		if ($date_year == date("Y")) {
			$c_date = $info_date_array[1]." ".$moth_array[$date_month]." в ".$time;
		}else{
			$c_date = $info_date_array[1]." ".$moth_array[$date_month]." ".$date_year." г. в ".$time;
		}
	}
	return $c_date;
}
?>