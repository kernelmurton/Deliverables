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
if(isset($_POST['user_id'])){
	$user_id= $_POST['user_id'];
}else{
	$user_id= $_SESSION['user_id'];
}

$time = date("Y/m/d H:i:s");

$date_key = $_POST['date_key'];

try{
	$pdo = new PDO(
		'mysql:dbname=test;host=xxx.nda.ac.jp',
		'user',
		'pass/'
	);
	
		$stmt1 = "insert into test_del_record select * from test_record where user_id='".$user_id."' and sdate=DATE('".$date_key."')";
		
	$result1 = $pdo->query($stmt1);
	
		$stmt = "delete from test_record where user_id='".$user_id."' and sdate=DATE('".$date_key."')";
		
	$result = $pdo->query($stmt);
	
		$stmt2 = "update test_del_record set time='".$time."' where user_id='".$user_id."' and sdate=DATE('".$date_key."')";
	
	$result2 = $pdo->query($stmt2);
	
	if(isset($_POST['user_id'])){
		header('Location:../analy/analy.php');
		exit();
	}else{
		header('Location:../form/form.php');
		exit();
	}
} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8',true, 500);
	exit($e->getMessage());
}

?>
