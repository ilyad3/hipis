<?php
header('Access-Control-Allow-Origin: *');
include 'connect.php';
$mark  = htmlspecialchars($_POST['mark']);
$gid = htmlspecialchars($_POST['gid']);
$check_query = mysqli_query($con, "SELECT bad,good FROM `passability` WHERE gid='$gid'") or die("error: 1");
if (mysqli_num_rows($check_query) != 0) {
	$check_row = mysqli_fetch_array($check_query);
	$good = $check_row['good'];
	$bad = $check_row['bad'];
	if ($mark == "good") {
		$good = $good + 1;
	}elseif ($mark == "bad") {
		$bad = $bad + 1;
	}
	$all_marks = $good + $bad;
	$persent = $all_marks / 100;
	$persent = $good / $persent;
	$answer_query = mysqli_query($con, "UPDATE passability SET bad='$bad', good='$good', persent='$persent' WHERE gid='$gid'") or die("error: 1");
}else{
	if ($mark == "good") {
		$good = 1;
		$bad = 0;
	}elseif ($mark == "bad") {
		$bad = 1;
		$good = 0;
	}
	$all_marks = $good + $bad;
	$persent = $all_marks / 100;
	$persent = $good / $persent;
	$answer_query = mysqli_query($con, "INSERT INTO passability (gid,good,bad,persent) VALUES ('$gid','$good','$bad','$persent')") or die("error: 1");
}
echo "error: 0";
?>