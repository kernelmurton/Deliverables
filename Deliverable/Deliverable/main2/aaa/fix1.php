<?php
ini_set('display_errors', 1);
$user_id = htmlspecialchars( $_POST['user_id'], ENT_QUOTES, 'UTF-8');
$handle = fopen("table.data","r");
while ($data = fgetcsv($handle, 1000, "\t")) {
	if($data[33]==$user_id){
		$NAME = $data[1];
		$platoon = $data[32];
		$grade = $data[28];
	}
}

fclose($handle);

try{
	$pdo = new PDO(
		'mysql:dbname=test;host=xxx.nda.ac.jp',
		'user',
		'pass/'
	);

	$stmt = "SELECT address, tel, sdate, edate, stay, note, stime, etime FROM test_record WHERE user_id='".$user_id."'";
	
	$result = $pdo->query($stmt);
	
	$row = $result->fetch(PDO::FETCH_ASSOC);
	
	$address = $row["address"];
  	$tel = $row["tel"];
  	$sdate = $row["sdate"];
	$edate = $row["edate"];
	$stay = $row["stay"];
	$note = $row["note"];
	$stime = $row["stime"];
	$etime = $row["etime"];
	
} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8',true, 500);
	exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./fix1.css">
    <link rel="shortcut icon" href="icon.png" type="image/x-icon">
 
   <title>トップページ</title>
</head>
<body>
<div class="all">
    <div class="a">
        <a href="./form.php">申請</a>
        <a href="./fix.php">確認</a>
        <a href="./anal.html">集計</a>
        <a href="./sub.html">許可</a>
    </div>

    <div class="ID">
        <output class="user_id" for=""><?php echo "$user_id";?></output>
        <output class="name" for=""><?php echo $platoon."小隊　".$grade."学年　".$NAME;?></output>
    </div>

    <form enctype="multipart/form-data" action="update.php" method="POST">
<input type="hidden" name="NAME" value="<?php echo $NAME;?>">
<input type="hidden" name="platoon" value="<?php echo $platoon;?>">
<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden" name="grade" value="<?php echo $grade;?>">
        <div class="dur">
            <h1>期間</h1>
            <div class="durf">
                <div class="start">
                    <h2>出発</h2>
                    <input type="date" name="sdate" id="" value="<?php echo $sdate; ?>" required>
                    <input type="time" name="stime" id="" value="<?php echo $stime; ?>" required>
                </div>

                <div class="fin">
                    <h2>帰校</h2>
                    <input type="date" name="edate" id="" value="<?php echo $edate; ?>" required>
                    <input type="time" name="etime" id="" value="<?php echo $etime; ?>" required>
                </div>
            </div>
        </div>

        <div class="stay">
            <h1>宿泊地</h1>
            <div class="ad">
                <h2>住所</h2>
                <input type="text" name="address" id="" value="<?php echo $address; ?>" required>

                <input type="text" list="place" id="stay" placeholder="摘要" name="stay" value="<?php echo $stay; ?>" required>
                
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
            <input type="tel" name="tel" id="" value="<?php echo $tel; ?>" required>
        </div>
        <div class="under">
            <div class="recf">
                <input type="text" placeholder="備考" name="note" id="" value="<?php echo $note; ?>">
                <label for="file">ファイルを選択してください</label>
                <input type="file" name="" id="file" multiple>
            </div>

            <input type="submit" value="変更">
        </div>

    </form>
</div>
</body>
</html>
