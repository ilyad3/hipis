<?php
include '/var/www/connect_test.php';
$enter_date = date("j.m.Y");
$today_u_query = mysqli_query($con, "SELECT id FROM `hip_users` WHERE enter_date='$enter_date'") or die(mysqli_error($con));
$today_u = 0;
while ($today_u_row = mysqli_fetch_array($today_u_query)) {
	$today_u++;
}
$today_r_query = mysqli_query($con, "SELECT id FROM `hip_users` WHERE reg_date='$enter_date'") or die(mysqli_error($con));
$today_r = 0;
while ($today_r_row = mysqli_fetch_array($today_r_query)) {
	$today_r++;
}
echo $today_u;
$info_query = mysqli_query($con, "UPDATE `admin_info` SET yesterday_users='$today_u', reg_yesterday='$today_r'") or die(mysqli_error($con));
?>