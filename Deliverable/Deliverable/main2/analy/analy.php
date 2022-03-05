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
$time = date("Y/m/d H:i:s");

if(isset($_POST['unit_key']) && isset($_POST['unit'])){
	$_SESSION['unit_key'] = $_POST['unit_key'];
	$_SESSION['unit'] = $_POST['unit'];
}
if(isset($_POST['grade_key'])){
	if($_POST['grade_key'] == 'all'){
		if(isset($_SESSION['grade_key'])){
			unset($_SESSION['grade_key']);
		}
	} else {
	$_SESSION['grade_key'] = $_POST['grade_key'];
	}
}
if(isset($_POST['cancel_key'])){
	$_SESSION['cancel_key'] = $_POST['cancel_key'];
}

try{
	$pdo = new PDO(
		'mysql:dbname=test;host=xxx.nda.ac.jp',
		'user',
		'pass/'
	);

	if(isset($_POST['key'])){
		if(isset($_SESSION['unit_key'])){
			unset($_SESSION['unit_key']);
		}
		if(isset($_SESSION['unit'])){
			unset($_SESSION['unit']);
		}
		if(isset($_SESSION['grade_key'])){
			unset($_SESSION['grade_key']);
		}
		if(isset($_SESSION['cancel_key'])){
			unset($_SESSION['cancel_key']);
		}
		$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_record";
	}else{
		if(isset($_SESSION['cancel_key']) && $_SESSION['cancel_key'] == 1){
			if(isset($_SESSION['grade_key'])){
				if(isset($_SESSION['unit_key'])){
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_del_record where grade =".$_SESSION['grade_key']." and ".$_SESSION['unit_key'];
				} else {
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_del_record where grade =".$_SESSION['grade_key'];
				}
			} else {
				if(isset($_SESSION['unit_key'])){
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_del_record where ".$_SESSION['unit_key'];
				} else {
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_del_record";
				}
			}
		} else {
			if(isset($_SESSION['grade_key'])){
				if(isset($_SESSION['unit_key'])){
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_record where grade =".$_SESSION['grade_key']." and ".$_SESSION['unit_key'];
				} else {
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_record where grade =".$_SESSION['grade_key'];
				}
			} else {
				if(isset($_SESSION['unit_key'])){
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_record where ".$_SESSION['unit_key'];
				} else {
					$stmt = "SELECT user_id, platoon, grade, NAME, address, tel, sdate, edate, stay, note, stime, etime FROM test_record";
				}
			}
		}
	}
	
	$result = $pdo->query($stmt);
	
	if($result != false){
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$rows[] = $row;
		}
	}
	
} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8',true, 500);
	exit($e->getMessage());
}
?>
<DOCTYPE html>
	<html lang="ja">

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="./analy.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>トップページ‐集計</title>
	</head>

	<body>
		<div class="all">
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
				<a href="../form/form.php">申請</a>
				<a href="../fix/fix.php">確認</a>
			</div>
			<div class="aa">
			<?php 
			if(isset($_SESSION['leader'])){
			?>
				<form action="../sub/sub.php">
					<button type="submit">許可</button>
				</form>
			<?php
			}
			?>
				<form method="POST" name="form_analy" action="./analy.php">
  				<input type="hidden" name="key" value="all">
  				<a href="#" onclick="document.form_analy.submit();">集計</a>
				</form>
			</div>
		</div>
			<div class="top">
				<div class="trance">
					<div class="tran">
						<ul class="gnav">
							<li>
								<a>所属</a>
								<ul>
									<li>
										<form method="POST" name="form_1bn" action="./analy.php">
  										<input type="hidden" name="unit_key" value="platoon >= 100 and platoon <200">
  										<input type="hidden" name="unit" value="1Bn">
  										<a href="#" onclick="document.form_1bn.submit();">1Bn</a>
										</form>
										<ul>
											<li>
												<form method="POST" name="form_11co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 110 and platoon <120">
  												<input type="hidden" name="unit" value="11Co">
  												<a href="#" onclick="document.form_11co.submit();">11Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_111pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 111">
  														<input type="hidden" name="unit" value="111Pt">
  														<a href="#" onclick="document.form_111pt.submit();">111Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_112pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 112">
  														<input type="hidden" name="unit" value="112Pt">
  														<a href="#" onclick="document.form_112pt.submit();">112Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_113pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 113">
  														<input type="hidden" name="unit" value="113Pt">
  														<a href="#" onclick="document.form_113pt.submit();">113Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_12co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 120 and platoon <130">
  												<input type="hidden" name="unit" value="12Co">
  												<a href="#" onclick="document.form_12co.submit();">12Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_121pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 121">
  														<input type="hidden" name="unit" value="121Pt">
  														<a href="#" onclick="document.form_121pt.submit();">121Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_122pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 122">
  														<input type="hidden" name="unit" value="122Pt">
  														<a href="#" onclick="document.form_122pt.submit();">122Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_123pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 123">
  														<input type="hidden" name="unit" value="123Pt">
  														<a href="#" onclick="document.form_123pt.submit();">123Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_13co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 130 and platoon <140">
  												<input type="hidden" name="unit" value="13Co">
  												<a href="#" onclick="document.form_13co.submit();">13Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_131pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 131">
  														<input type="hidden" name="unit" value="131Pt">
  														<a href="#" onclick="document.form_131pt.submit();">131Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_132pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 132">
  														<input type="hidden" name="unit" value="132Pt">
  														<a href="#" onclick="document.form_132pt.submit();">132Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_133pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 133">
  														<input type="hidden" name="unit" value="133Pt">
  														<a href="#" onclick="document.form_133pt.submit();">133Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_14co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 140 and platoon <150">
  												<input type="hidden" name="unit" value="14Co">
  												<a href="#" onclick="document.form_14co.submit();">14Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_141pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 141">
  														<input type="hidden" name="unit" value="141Pt">
  														<a href="#" onclick="document.form_141pt.submit();">141Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_142pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 142">
  														<input type="hidden" name="unit" value="142Pt">
  														<a href="#" onclick="document.form_142pt.submit();">142Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_143pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 143">
  														<input type="hidden" name="unit" value="143Pt">
  														<a href="#" onclick="document.form_143pt.submit();">143Pt</a>
														</form>
													</li>
												</ul>
											</li>
										</ul>
									</li>
									<li>
										<form method="POST" name="form_2bn" action="./analy.php">
  										<input type="hidden" name="unit_key" value="platoon >= 200 and platoon <300">
  										<input type="hidden" name="unit" value="2Bn">
  										<a href="#" onclick="document.form_2bn.submit();">2Bn</a>
										</form>
										<ul>
											<li>
												<form method="POST" name="form_21co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 210 and platoon <220">
  												<input type="hidden" name="unit" value="21Co">
  												<a href="#" onclick="document.form_21co.submit();">21Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_211pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 211">
  														<input type="hidden" name="unit" value="211Pt">
  														<a href="#" onclick="document.form_211pt.submit();">211Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_212pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 212">
  														<input type="hidden" name="unit" value="212Pt">
  														<a href="#" onclick="document.form_212pt.submit();">212Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_213pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 213">
  														<input type="hidden" name="unit" value="213Pt">
  														<a href="#" onclick="document.form_213pt.submit();">213Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_22co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 220 and platoon <230">
  												<input type="hidden" name="unit" value="22Co">
  												<a href="#" onclick="document.form_22co.submit();">22Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_221pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 221">
  														<input type="hidden" name="unit" value="221Pt">
  														<a href="#" onclick="document.form_221pt.submit();">221Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_222pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 222">
  														<input type="hidden" name="unit" value="222Pt">
  														<a href="#" onclick="document.form_222pt.submit();">222Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_223pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 223">
  														<input type="hidden" name="unit" value="223Pt">
  														<a href="#" onclick="document.form_223pt.submit();">223Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_23co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 230 and platoon <240">
  												<input type="hidden" name="unit" value="23Co">
  												<a href="#" onclick="document.form_23co.submit();">23Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_231pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 231">
  														<input type="hidden" name="unit" value="231Pt">
  														<a href="#" onclick="document.form_231pt.submit();">231Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_232pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 232">
  														<input type="hidden" name="unit" value="232Pt">
  														<a href="#" onclick="document.form_232pt.submit();">232Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_233pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 233">
  														<input type="hidden" name="unit" value="233Pt">
  														<a href="#" onclick="document.form_233pt.submit();">233Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_24co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 240 and platoon <250">
  												<input type="hidden" name="unit" value="24Co">
  												<a href="#" onclick="document.form_24co.submit();">24Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_241pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 241">
  														<input type="hidden" name="unit" value="241Pt">
  														<a href="#" onclick="document.form_241pt.submit();">241Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_242pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 242">
  														<input type="hidden" name="unit" value="242Pt">
  														<a href="#" onclick="document.form_242pt.submit();">242Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_243pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 243">
  														<input type="hidden" name="unit" value="243Pt">
  														<a href="#" onclick="document.form_243pt.submit();">243Pt</a>
														</form>
													</li>
												</ul>
											</li>
										</ul>
									</li>
									<li>
										<form method="POST" name="form_3bn" action="./analy.php">
  										<input type="hidden" name="unit_key" value="platoon >= 300 and platoon <400">
  										<input type="hidden" name="unit" value="3Bn">
  										<a href="#" onclick="document.form_3bn.submit();">3Bn</a>
										</form>
										<ul>
											<li>
												<form method="POST" name="form_31co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 310 and platoon <320">
  												<input type="hidden" name="unit" value="31Co">
  												<a href="#" onclick="document.form_31co.submit();">31Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_311pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 311">
  														<input type="hidden" name="unit" value="311Pt">
  														<a href="#" onclick="document.form_311pt.submit();">311Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_312pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 312">
  														<input type="hidden" name="unit" value="312Pt">
  														<a href="#" onclick="document.form_312pt.submit();">312Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_313pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 313">
  														<input type="hidden" name="unit" value="313Pt">
  														<a href="#" onclick="document.form_313pt.submit();">313Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_32co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 320 and platoon < 330">
  												<input type="hidden" name="unit" value="32Co">
  												<a href="#" onclick="document.form_32co.submit();">32Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_321pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 321">
  														<input type="hidden" name="unit" value="321Pt">
  														<a href="#" onclick="document.form_321pt.submit();">321Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_322pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 322">
  														<input type="hidden" name="unit" value="322Pt">
  														<a href="#" onclick="document.form_322pt.submit();">322Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_323pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 323">
  														<input type="hidden" name="unit" value="323Pt">
  														<a href="#" onclick="document.form_323pt.submit();">323Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_33co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 330 and platoon < 340">
  												<input type="hidden" name="unit" value="33Co">
  												<a href="#" onclick="document.form_33co.submit();">33Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_331pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 331">
  														<input type="hidden" name="unit" value="331Pt">
  														<a href="#" onclick="document.form_331pt.submit();">331Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_332pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 332">
  														<input type="hidden" name="unit" value="332Pt">
  														<a href="#" onclick="document.form_332pt.submit();">332Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_333pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 333">
  														<input type="hidden" name="unit" value="333Pt">
  														<a href="#" onclick="document.form_333pt.submit();">333Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_34co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 340 and platoon < 350">
  												<input type="hidden" name="unit" value="34Co">
  												<a href="#" onclick="document.form_34co.submit();">34Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_341pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 341">
  														<input type="hidden" name="unit" value="341Pt">
  														<a href="#" onclick="document.form_341pt.submit();">341Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_342pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 342">
  														<a href="#" onclick="document.form_342pt.submit();">342Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_343pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 343">
  														<input type="hidden" name="unit" value="343Pt">
  														<a href="#" onclick="document.form_343pt.submit();">343Pt</a>
														</form>
													</li>
												</ul>
											</li>
										</ul>
									</li>
									<li>
										<form method="POST" name="form_4bn" action="./analy.php">
  										<input type="hidden" name="unit_key" value="platoon >= 400 and platoon <500">
  										<input type="hidden" name="unit" value="4Bn">
  										<a href="#" onclick="document.form_4bn.submit();">4Bn</a>
										</form>
										<ul>
											<li>
												<form method="POST" name="form_41co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 410 and platoon <420">
  												<input type="hidden" name="unit" value="41Co">
  												<a href="#" onclick="document.form_41co.submit();">41Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_411pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 411">
  														<input type="hidden" name="unit" value="411Pt">
  														<a href="#" onclick="document.form_411pt.submit();">411Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_412pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 412">
  														<input type="hidden" name="unit" value="412Pt">
  														<a href="#" onclick="document.form_412pt.submit();">412Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_413pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 413">
  														<input type="hidden" name="unit" value="413Pt">
  														<a href="#" onclick="document.form_413pt.submit();">413Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_42co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 420 and platoon <430">
  												<input type="hidden" name="unit" value="42Co">
  												<a href="#" onclick="document.form_42co.submit();">42Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_421pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 421">
  														<input type="hidden" name="unit" value="421Pt">
  														<a href="#" onclick="document.form_421pt.submit();">421Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_422pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 422">
  														<input type="hidden" name="unit" value="422Pt">
  														<a href="#" onclick="document.form_422pt.submit();">422Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_423pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 423">
  														<input type="hidden" name="unit" value="423Pt">
  														<a href="#" onclick="document.form_423pt.submit();">423Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_43co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 430 and platoon <440">
  												<input type="hidden" name="unit" value="43Co">
  												<a href="#" onclick="document.form_43co.submit();">43Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_431pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 431">
  														<input type="hidden" name="unit" value="431Pt">
  														<a href="#" onclick="document.form_431pt.submit();">431Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_432pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 432">
  														<input type="hidden" name="unit" value="432Pt">
  														<a href="#" onclick="document.form_432pt.submit();">432Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_433pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 433">
  														<input type="hidden" name="unit" value="433Pt">
  														<a href="#" onclick="document.form_433pt.submit();">433Pt</a>
														</form>
													</li>
												</ul>
											</li>
											<li>
												<form method="POST" name="form_44co" action="./analy.php">
  												<input type="hidden" name="unit_key" value="platoon >= 440 and platoon <450">
  												<input type="hidden" name="unit" value="44Co">
  												<a href="#" onclick="document.form_44co.submit();">44Co</a>
												</form>
												<ul>
													<li>
														<form method="POST" name="form_441pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 441">
  														<input type="hidden" name="unit" value="441Pt">
  														<a href="#" onclick="document.form_441pt.submit();">441Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_442pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 442">
  														<input type="hidden" name="unit" value="442Pt">
  														<a href="#" onclick="document.form_442pt.submit();">442Pt</a>
														</form>
													</li>
													<li>
														<form method="POST" name="form_443pt" action="./analy.php">
  														<input type="hidden" name="unit_key" value="platoon = 443">
  														<input type="hidden" name="unit" value="443Pt">
  														<a href="#" onclick="document.form_443pt.submit();">443Pt</a>
														</form>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li><a class="non">中隊</a></li>
							<li><a class="non">小隊</a></li>
						</ul>
						<ul class="grade">
							<li>
								<a href="">学年</a>
								<ul>
									<li>
										<form method="POST" name="form_grade" action="./analy.php">
  										<input type="hidden" name="grade_key" value="all">
  										<a href="#" onclick="document.form_grade.submit();">全学年</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_grade_0" action="./analy.php">
  										<input type="hidden" name="grade_key" value="0">
  										<a href="#" onclick="document.form_grade_0.submit();">0学年</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_grade_1" action="./analy.php">
  										<input type="hidden" name="grade_key" value="1">
  										<a href="#" onclick="document.form_grade_1.submit();">1学年</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_grade_2" action="./analy.php">
  										<input type="hidden" name="grade_key" value="2">
  										<a href="#" onclick="document.form_grade_2.submit();">2学年</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_grade_3" action="./analy.php">
  										<input type="hidden" name="grade_key" value="3">
  										<a href="#" onclick="document.form_grade_3.submit();">3学年</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_grade_4" action="./analy.php">
  										<input type="hidden" name="grade_key" value="4">
  										<a href="#" onclick="document.form_grade_4.submit();">4学年</a>
										</form>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="cancel">
							<li>
								<a href="">取消有無</a>
								<ul>
									<li>
										<form method="POST" name="form_cancel_0" action="./analy.php">
  										<input type="hidden" name="cancel_key" value="0">
  										<a href="#" onclick="document.form_cancel_0.submit();">申請者</a>
										</form>
									</li>
									<li>
										<form method="POST" name="form_cancel_1" action="./analy.php">
  										<input type="hidden" name="cancel_key" value="1">
  										<a href="#" onclick="document.form_cancel_1.submit();">取消者</a>
										</form>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="class">
						<!--現在表示している表の属性を取得-->
						<?php
						if(isset($_SESSION['unit'])){
						?>
						<a>所属:<?php echo $_SESSION['unit'];?></a>
						<?php
						} else {
						?>
						<a>所属:全大隊</a>
						<?php
						}
						?>
						<?php
						if(isset($_SESSION['grade_key']) && $_SESSION['grade_key'] != 'all'){
						?>
						<a>学年:<?php echo $_SESSION['grade_key'];?>学年</a>
						<?php
						} else {
						?>
						<a>学年:全学年</a>
						<?php
						}
						?>
					</div>
				</div>
				<div class="table">
					<table>
						<thead>
							<tr>
								<th>学年</th>
								<th>実家</th>
								<th>親戚宅</th>
								<th>友人宅</th>
								<th>下宿</th>
								<th>その他</th>
								<th>合計</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>0</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<th>1</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<th>2</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<th>3</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<th>4</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th>合計</th>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="tstamp">
				<a>最終更新：2020年13月33日　00:00</a>
			</div>
			<div class="serch">
				<input type="text" placeholder="検索">
			</div>
			<div class="main" id="table_m">
				<table>
					<thead>
						<tr>
							<th style="width: 50px;">NO.</th>
							<th style="width: 80px;">小隊</th>
							<th style="width: 50px;">学年</th>
							<th style="width: 150px;">氏名</th>
							<th style="width: 200px;">出発日時</th>
							<th style="width: 200px;">終了日時</th>
							<th style="width: 70px;">備考</th>
							<th style="width: 170px;">緊急連絡先</th>
							<th style="width: 220px;">住所</th>
							<th style="width: 80px;">摘要</th>
							<th style="width: 70px;">回数</th>
						</tr>
					</thead>
					<tfoot>
						<th>NO.</th>
						<th>小隊</th>
						<th>学年</th>
						<th>氏名</th>
						<th>出発日時</th>
						<th>終了日時</th>
						<th>備考</th>
						<th>緊急連絡先</th>
						<th>住所</th>
						<th>摘要</th>
						<th>回数</th>
					</tfoot>
					<tbody>
					<?php
					if(isset($rows)){
						$no = 0; 
						foreach($rows as $row){
							$no += 1;
							$date_key = $row['sdate'];
						?>
						<tr>
							<td><?php echo $no;?></td>
							<td><?php echo $row['platoon'];?></td>
							<td><?php echo $row['grade'];?></td>
							<td>
							<?php
							if(isset($_SESSION['syuban'])==false){
							?>
								<!--ID番号-->
								<a href="<?php echo "#".$no;?>">
								<!--ここに申請者名を入れる-->
									<?php echo $row['NAME'];?>
								</a>
								<section class="modal-window" id="<?php echo $no;?>">
									<!--ここのidの値は申請者名のIDと結びつける？-->
									<div class="modal-inner">
									<p>
										<div>氏名:<?php echo $row['NAME'];?>,期間:<?php echo $row['sdate']."/".$row['stime'];?>〜<?php echo $row['edate']."/".$row['etime'];?></div>
										<form action="../fix/delete.php" method="POST">
											<div class="section">
												<input type="hidden" name="date_key" value="<?php echo $date_key;?>">
												<input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>">
												<input class="del" type="submit" value="取り消し" >
											</div>
										</form>
									</p>
								</div>
								<a href="#!" class="modal-close">&times;</a>
							</section>
							<?php
							}else{
								echo $row['NAME'];
							}
							?>
							</td>
							<td><?php echo $row['sdate']."/".$row['stime'];?></td>
							<td><?php echo $row['edate']."/".$row['etime'];?></td>
							<td><?php echo $row['note'];?></td>
							<td><?php echo $row['tel'];?></td>
							<td><?php echo $row['address'];?></td>
							<td><?php echo $row['stay'];?></td>
							<td>0</td>
						</tr>
						<?php
							}
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
	</html>
