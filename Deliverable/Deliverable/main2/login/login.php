<?php
	session_start();
	$user_id  = htmlspecialchars( $_POST['user_id'], ENT_QUOTES, 'UTF-8');
	$conn_err = htmlspecialchars( $_POST['conn_err'], ENT_QUOTES, 'UTF-8');
	$bind_err = htmlspecialchars( $_POST['bind_err'], ENT_QUOTES, 'UTF-8');
	if(isset($_SESSION['user_id']) && isset($_SESSION['NAME']) && isset($_SESSION['platoon']) && isset($_SESSION['grade'])){
		header('Location:../form/form.php');
		exit();
	}
?>
﻿<html lang="ja">
<head>
<!--文字コードを決める-->
	<meta charset="UTF-8">
	<link rel="stylesheet" href="login.css">
    	<link rel="shortcut icon" href="icon.png" type="image/x-icon">
<!--タイトル-->
<title>ログイン</title>
</head>
<body>

<!--フォームの作成。必要な情報を入力させる-->
<div class="login-box">
        <form action="./login_exe.php" method = "post">
            <h1>ログイン</h1>
            <div class="textbox">
                <i class="fa fa-user" aria-hidden="true"></i>
                <input type="text" placeholder="ログイン名" name="user_id" id="user_id" size="20" value="<?php if(isset($user_id))echo $user_id;?>" required>
            </div>

            <div class="textbox">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" placeholder="パスワード" name="pass" id="pass" size="20" required>
            </div>

            <input class="btn" name="button" type="submit" value="ログイン">
        </form>
    </div>
<?php
	if(isset($conn_err))echo $conn_err;
	if(isset($bind_err))echo $bind_err;
?>
</body>
</html>
