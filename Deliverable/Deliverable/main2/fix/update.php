<?php
$session_time = 86400;
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', $session_time );
session_start();
if(isset($_SESSION['user_id'])==false){
	header('Location:../login/login.php');
	exit();
}
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Tokyo');
$NAME = $_SESSION['NAME'];
$platoon = $_SESSION['platoon'];
$grade = $_SESSION['grade'];
$user_id= $_SESSION['user_id'];

$sdate = $_POST['sdate'];
$stime = $_POST['stime'];
$edate = $_POST['edate'];
$etime = $_POST['etime'];
$address = $_POST['address'];
$stay = $_POST['stay'];
$tel = $_POST['tel'];
$time = date("Y/m/d H:i:s");

$date_key = $_POST['date_key'];

try{
	$pdo = new PDO(
		'mysql:dbname=test;host=xxx.nda.ac.jp',
		'user',
		'pass/'
	);
	
		$stmt = "update test_record set address='".$address."',tel='".$tel."',sdate='".$sdate."',edate='".$edate."',stay='".$stay."',stime='".$stime."',etime='".$etime."',time='".$time."' where user_id='".$user_id."' and sdate=DATE('".$date_key."')";
		
	$result = $pdo->query($stmt);
	
	header('Location:fix.php');
	exit();
} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8',true, 500);
	exit($e->getMessage());
}

?>
