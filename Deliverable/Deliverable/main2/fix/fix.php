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

try{
	$pdo = new PDO(
		'mysql:dbname=test;host=xxx.nda.ac.jp',
		'user',
		'pass/'
	);

	$stmt = "SELECT address, tel, sdate, edate, stay, note, stime, etime FROM test_record WHERE user_id='".$user_id."'";
	
	$result = $pdo->query($stmt);
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$rows[] = $row;
	}
	
	if(isset($_POST['date_key'])){
		$date_key = $_POST['date_key'];
		$stmt1 = "SELECT address, tel, sdate, edate, stay, note, stime, etime FROM test_record WHERE user_id='".$user_id."' and sdate=DATE('".$date_key."')";
	
		$result1 = $pdo->query($stmt1);
	
		$row1 = $result1->fetch(PDO::FETCH_ASSOC);
	
		$address = $row1["address"];
  		$tel = $row1["tel"];
  		$sdate = $row1["sdate"];
		$edate = $row1["edate"];
		$stay = $row1["stay"];
		$note = $row1["note"];
		$stime = $row1["stime"];
		$etime = $row1["etime"];
	}
	
} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8',true, 500);
	exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./fix.css">
	<title>トップページ-確認-</title>
</head>

<body>
	<div class="all_in">
		<div class="log_out">
			<a href="../login/logout.php">ログアウト</a>
		</div>
		<div class="debug">
			<form id="debug">
				<a>debug info</a>
				<a>
					<!--デバッグログを表示-->
				</a>
			</form>
		</div>
		<div class="head">
			<div class="a">
				<a href="../form/form.php">申請</a>
				<a href="./fix.php">確認</a>
			</div>
			<div class="aa">
				<form method="POST" name="form_analy" action="../analy/analy.php">
  				<input type="hidden" name="key" value="all">
  				<a href="#" onclick="document.form_analy.submit();">集計</a>
				</form>
			</div>
		</div>
		<div class="ID">
			<output class="user" for=""><?php echo "$user_id";?></output>
			<output class="name" for=""><?php echo $platoon."小隊　".$grade."学年　".$NAME;?></output>
		</div>
		<form enctype="multipart/form-data" action="update.php" method="POST">
			<input type="hidden" name="date_key" value="<?php echo $date_key;?>">
			<div class="dur">
				<h1>期間</h1>
				<div class="durf">
					<div class="start">
						<h2>出発</h2>
						<input type="date" name="sdate" id="" value="<?php if(isset($sdate))echo $sdate; ?>" required>
						<input type="time" name="stime" id="" value="<?php if(isset($stime))echo $stime; ?>"  required>
					</div>

					<div class="fin">
						<h2>帰校</h2>
						<input type="date" name="edate" id="" value="<?php if(isset($edate))echo $edate; ?>" required>
						<input type="time" name="etime" id="" value="<?php if(isset($etime))echo $etime; ?>" required>
					</div>
				</div>
			</div>

			<div class="stay">
				<h1>宿泊地</h1>
				<div class="ad">
					<h2>住所</h2>
					<input type="text" name="address" id="" value="<?php if(isset($address))echo $address; ?>" required>

					<input type="text" list="place" id="stay" placeholder="摘要" name="stay" value="<?php if(isset($stay))echo $stay; ?>" required>

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
				<input type="tel" name="tel" id="" value="<?php if(isset($tel))echo $tel; ?>" required>
			</div>
			<div class="under">
				<div class="recf">
					<input type="text" placeholder="備考" name="note" id="" value="<?php if(isset($note))echo $note; ?>">
					<label for="file">ファイルを選択してください</label>
					<input type="file" name="" id="file" multiple>
				</div>
				<div class="trance">
					<input class="chg" type="submit" formaction="update.php" value="変更" >
					<input class="fix" type="submit" formaction="delete.php" value="取消" enabled>
				</div>
			</div>

		</form>
	<div class="main" id="">
		<table>
			<thead>
				<tr>
					<th style="width: 40px;">NO.</th>
					<th style="width: 50px;">小隊</th>
					<th style="width: 40px;">学年</th>
					<th style="width: 150px;">氏名</th>
					<th style="width: 220px;">出発日時</th>
					<th style="width: 220px;">終了日時</th>
					<th style="width: 165px;">緊急連絡先</th>
					<th style="width: 260px;">住所</th>
					<th style="width: 70px;">摘要</th>
					<th style="width: 70px;">備考</th>
					<th style="width: 70px;">回数</th>
				</tr>
			</thead>
				<tbody>
				<?php
				if(isset($rows)){
					$no = 0; 
					foreach($rows as $row){
						$no += 1;
						$date_key = $row['sdate'];
				?>
					<tr bgcolor="#EC5959">
						<td>
						<form enctype="multipart/form-data" action="update.php" method="POST">
							<input type="hidden" name="date_key" value="<?php echo $date_key;?>">
							<input type="submit" formaction="fix.php" value="<?php echo $no;?>">
							</form>
						</td>
						<td><?php echo $platoon;?></td>
						<td><?php echo $grade;?></td>
						<td><?php echo $NAME;?></td>
						<td><?php echo $row['sdate']."/".$row['stime'];?></td>
						<td><?php echo $row['edate']."/".$row['etime'];?></td>
						<td><?php echo $row['tel'];?></td>
						<td><?php echo $row['address'];?></td>
						<td><?php echo $row['stay'];?></td>
						<td><?php echo $row['note'];?></td>
						<td>回数</td>
					</tr>
				<?php
					}
				}
				?>
				</tbody>
		</table>
		<div class="comment">
			
		</div>
	</div>
</body>

</html>
