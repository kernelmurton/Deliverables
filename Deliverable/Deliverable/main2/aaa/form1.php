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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./form1.css">
    <!--<link rel="shortcut icon" href="icon.png" type="image/x-icon">-->
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
        <output class="id" for=""><?php echo $user_id;?></output>
        <output class="name" for=""><?php echo $platoon."小隊　".$grade."学年　".$NAME;?></output>
    </div>

    <form enctype="multipart/form-data" action="record.php" method="POST">
	<input type="hidden" name="NAME" value="<?php echo $NAME;?>">
	<input type="hidden" name="platoon" value="<?php echo $platoon;?>">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
	<input type="hidden" name="grade" value="<?php echo $grade;?>">
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
                <input type="file" name="file" id="file" multiple>
            </div>

            <input type="submit" value="申請">
        </div>

    </form>
</div>
</body>
</html>
