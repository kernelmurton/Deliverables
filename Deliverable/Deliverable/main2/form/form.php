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
$user_id = $_SESSION['user_id'];
$NAME = $_SESSION['NAME'];
$platoon = $_SESSION['platoon'];
$grade = $_SESSION['grade'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./form.css">
	<title>トップページ‐申請</title>
</head>

<body>
	<div class="all_in">
		<div class="debug">
			<form id="debug">
				<a>debug info</a>
				<a>
					<!--デバッグログを表示-->
				</a>
			</form>
		</div>
		<div class="log_out">
			<a href="../login/logout.php">ログアウト</a>
		</div>
		<div class="head">
			<div class="a">
				<a href="./form.php">申請</a>
				<a href="../fix/fix.php">確認</a>
			</div>
			<div class="aa">
				<form method="POST" name="form_analy" action="../analy/analy.php">
  				<input type="hidden" name="key" value="all">
  				<a href="#" onclick="document.form_analy.submit();">集計</a>
				</form>
			</div>
		</div>
		<div class="ID">
			<output class="user" for=""><?php echo $user_id;?></output>
			<output class="name" for=""><?php echo $platoon."小隊　".$grade."学年　".$NAME;?></output>
		</div>
		<form enctype="multipart/form-data" action="record.php" method="POST">
		<div class="dur">
		<h1>期間</h1>
		<div class="durf">
					<div class="start">
						<h2>出発</h2>
						<input type="date" name="sdate" id="" required>
						<input type="time" name="stime" id="" value="08:00" required>
					</div>

					<div class="fin">
						<h2>帰校</h2>
						<input type="date" name="edate" id="" value="" required>
						<input type="time" name="etime" id="" value="22:20" required>
					</div>
				</div>
			</div>

			<div class="stay">
				<h1>宿泊地</h1>
				<div class="ad">
					<h2>住所</h2>
					<input type="text" name="address" id="" required>
					<input type="text" list="place" id="stay" placeholder="摘要" name="stay" required>
					<datalist id="place">
						<option value="実家">
						<option value="親戚宅">
						<option value="友人宅">
						<option value="下宿">
						<option value="その他">
					</datalist>
				</div>
			</div>
			<div class="tel">
				<h1>緊急連絡先</h1>
				<input type="tel" name="tel" id="" required>
			</div>
			<div class="under">
				<div class="recf">
					<input type="text" placeholder="備考" name="note" id="">
					<label for="file">ファイルを選択してください</label>
					<input type="file" name="" id="file" multiple>
				</div>
				<input type="submit" value="申請">
			</div>
		</form>
	</div>
</body>

</html>
