<?php 
$session_time = 86400;
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', $session_time );
session_start();
ini_set('display_errors', 1);
if(isset($_POST['user_id']) && isset($_POST['pass']) && $_POST['user_id'] != '' && $_POST['pass'] != '' ){
	// Ldapサーバー
	$ldap_server = "xxx.nda.ac.jp";
    
	// アカウント
	$user_id    = htmlspecialchars( $_POST['user_id'], ENT_QUOTES, 'UTF-8');   // ユーザ名
	$user_password  = htmlspecialchars( $_POST['pass'], ENT_QUOTES, 'UTF-8'); // パスワード
	
	// ベースのDN
	$base_dn = "uid=".$user_id.",OU=private,OU=Users,DC=nda,DC=ac,DC=jp"; // DN
	
	// コネクション接続
	$ldapid = ldap_connect($ldap_server);
	
	if(!$ldapid){
		$conn_err =  "コネクション接続できません";
	} else {
		// バインド接続
		$ldapbind = ldap_bind($ldapid, $base_dn, $user_password);
     
		if(!$ldapbind){
			//コネクション切断
			ldap_unbind($ldapid);
 
			$bind_err = "バインド接続できません";
		} else {
			ldap_unbind($ldapid);
			$handle = fopen("../table.data","r");
			while ($data = fgetcsv($handle, 1000, "\t")) {
				if($data[33]==$user_id){
					$judge = $data[16];
					$NAME = $data[1];
					$platoon = $data[32];
					$grade = $data[28];
				}
			}
			fclose($handle);
			
			//if($grade>=2){
			//セッション関数に保存
			$_SESSION['user_id'] = $user_id;
			$_SESSION['NAME'] = $NAME;
			$_SESSION['platoon'] = $platoon;
			$_SESSION['grade'] = $grade;
			
			//指導官の判別
			if(strpos($judge,'小隊指導教官') !== false){
				$_SESSION['leader'] = 1;
				header('Location:/../analy/analy.php');
				exit();
			} else if(strpos($judge,'中隊指導教官') !== false){
				$_SESSION['leader'] = 2;
				header('Location:/../analy/analy.php');
				exit();
			} else if(strpos($judge,'大隊指導教官') !== false){
				$_SESSION['leader'] = 3;
				header('Location:/../analy/analy.php');
				exit();
			} else if(strpos($NAME,'週番学生') !== false){
				$_SESSION['syuban'] = 1;
				header('Location:/../analy/analy.php');
				exit();
			} else {
				header('Location:../form/form.php');
				exit();
			}
			//}else{
				//header('Location:./login.php');
				//exit();
			//}
		}
	}
}
?>

<html>
<head>
<!--使用文字コードを指定-->
<meta charset="UTF-8">
</head>
<body onload="document.all.jikkou.click();">
<?php
//エラー文表示
//if(isset($conn_err))echo $conn_err;
//if(isset($bind_err))echo $bind_err;
?>
<form action="<?php echo "login.php";?>" method = "post">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
	<input type="hidden" name="conn_err" value="<?php if(isset($conn_err))echo $conn_err;?>">
	<input type="hidden" name="bind_err" value="<?php if(isset($bind_err))echo $bind_err;?>">
	<input type="submit" value="実行" name="jikkou">
</form>
</body>
</html>
