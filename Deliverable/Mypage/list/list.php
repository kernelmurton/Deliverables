<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Surijaya Blog</title>
        <link rel="stylesheet" href="list.css">
</head>
<body>
<header class="header">
	<div class="site">
		<img src="../img/page_name.png" alt="header image">
	</div>

	<nav class="nav">
		<ul>
			<li><a href="index.html">ホーム</a></li>
            <li><a href="index.html">一覧</a></li>
	</nav>

	<button type="button" class="nav-button" onClick="navFunc()">
		<span class="sr-only">MENU</span>
	</button>
</header>



<?php
$contents_id=htmlspecialchars($_POST['contents_id'],ENT_QUOTES, 'UTF-8');
if(is_null($contents_id)){
    ?><div class="title"><?php echo "error";?></div><?php
};
try{
    require_once('db.php');
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from Blogtable where id =:c_id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":c_id", $contents_id, PDO::PARAM_STR);
    $stmt->execute();
    $title_rows= $stmt->fetchAll();
    $title_row= $title_rows[0];
    //var_dump($title_rows);
    //var_dump($title_row);
}catch (PDOException $e){
    print('error:'.$e->getMessage());
    die();//エラーをはくようにする
}
try{
    require_once('db.php');
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from Linktable where contents_id =:c_id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":c_id", $contents_id, PDO::PARAM_STR);
    $stmt->execute();
    $link_rows= $stmt->fetchAll();
    //var_dump($link_rows);
}catch (PDOException $e){
    print('error:'.$e->getMessage());
    die();//エラーをはくようにする
}
?>
<div class="objects">
    <div class="title">
        <h1><?php echo $title_row['title'];?></h1>
    </div>
    <?php
    foreach($link_rows as $link_row){
        //var_dump($link_row);
    ?>
    <a href="<?php echo $link_row["url"];?>">
    <section class="link" id="link<?php echo $link_row["inner_num"];?>">
        <div class="article">
            <h2><?php echo $link_row['title'];?></h2>
            <h3><?php echo $link_row['discribe'];?></h3>
        </div>
        <img src="../img/excel.png" alt="icon">
    </section>
    </a>
    <?php
    }
    ?>
</div>


<footer class="footer">
        COPYRIGHT © REIYA OKADA
    </footer>
    
<script>
	function navFunc() {
		document.querySelector('html').classList.toggle('open');
	}
</script>
</body>
</html>