<?php
//require_once 'nagoyagym_config.php';

$user = "shogo0922_wp1";
$pass = "okagawa1981";

$DB_HOST ="localhost";
$DB_DATEBASE = "shogo0922_nagoyagym";
$DB_USERNAME ="shogo0922_wp1";
$DB_PASSWORD = "okagawa1981";
$dsn = "mysql:dbname=shogo0922_nagoyagym;host=localhost;charset=utf8mb4";

$user_name_kana = $_POST['user_name_kana'];
$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];
$user_phone = $_POST['user_phone'];

//Y年m月d日のデータをY-m-dに統一する。
//ユーザの予約画面から取得したデータ
$reserved_date = $_POST['user_reserved_day'];

//変換処理
//返り値 = preg_match(/正規表現パターン/,検索対象の文字列,[配列],[動作フラグ],[検索開始位置])
preg_match( "/([0-9]*)年([0-9]*)月([0-9]*)日/", $reserved_date, $spilit_date );

//字数合わせをする(0埋めをする関数)
//str_pad
//('ターゲット', '桁数', '埋めるもの', どこに(右揃えや左揃え)
// str_pad('9', 2, '0', STR_PAD_LEFT)

//INSERT文用に加工
$user_reserved_day = $spilit_date[1]."-".str_pad($spilit_date[2], 2, '0', STR_PAD_LEFT)."-".str_pad($spilit_date[3], 2, '0', STR_PAD_LEFT);

//デバッグ用
// echo $user_reserved_day;

$user_reserved_time = $_POST['user_reserved_time'];
$user_course = $_POST['user_course'];
$stuff = $_POST['stuff'];
$channel = $_POST['channel'];
$user_msg = $_POST['user_msg'];
//submitした時の日付を取得
date_default_timezone_set('Asia/Tokyo');
$user_registered = date('Y/m/d');


try {
  $dbh = new PDO($dsn, $user, $pass);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
  //必要な数字をselectで呼び出してからinsertする

  //予約番号の処理　　SQL文作成
  $sql = "SELECT MAX(reserve_no) AS maxNo FROM customer_list";

  //クエリの実行
  $stmt = $dbh->query($sql);
  // $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  //予約した最後の予約番号に1を加算して予約番号を作成
  $reserve_no = (int)$result["maxNo"] + 1;

  //登録した日時はここでmysqlで取得する。
  $sql = "INSERT INTO customer_list (
    reserve_no, user_name_kana, user_name, user_email, user_phone, user_reserved_day, user_reserved_time, user_course, stuff, channel, user_msg, user_registered
    ) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

  //クエリの実行
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(1, $reserve_no, PDO::PARAM_INT);
  $stmt->bindValue(2, $user_name_kana, PDO::PARAM_STR);
  $stmt->bindValue(3, $user_name, PDO::PARAM_STR);
  $stmt->bindValue(4, $user_email, PDO::PARAM_STR);
  $stmt->bindValue(5, $user_phone, PDO::PARAM_STR);
  $stmt->bindValue(6, $user_reserved_day, PDO::PARAM_STR);
  $stmt->bindValue(7, $user_reserved_time, PDO::PARAM_STR);
  $stmt->bindValue(8, $user_course, PDO::PARAM_STR);
  $stmt->bindValue(9, $stuff, PDO::PARAM_INT);
  $stmt->bindValue(10, $channel, PDO::PARAM_INT);
  $stmt->bindValue(11, $user_msg, PDO::PARAM_STR);
  $stmt->bindValue(12, $user_registered, PDO::PARAM_INT);
  $stmt->execute();

  $dbh = null;

} catch (Exception $e) {
  echo "エラー発生！:申し訳ありません。\n
  リクエストの送信中に何か問題が発生しました。\n後でもう一度やり直して頂くか,直接お店にご連絡ください。\n
  NagoyaGym:090-0000-2222\n
  open　10:00 〜 close　22:00\n
  定休日：木曜日\n" . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8') . "<br>";
  die();
}

//管理者にメールを送る
$from = $user_email;
	$to = 'okagawa1981@gmail.com';  // 管理者 email idを入れる
	$subject = 'Appointment Form : ホームページよりご予約';

  $body = "$user_name 様よりご予約が入りました。\n
    【ご予約情報】\n
    ご予約ID：$reserve_no \n
    氏名（フリガナ）: $user_name_kana\n
    氏名：$user_name\n
    ご予約日: $user_reserved_day . $user_reserved_time\n
    ご予約コース: $user_course\n
    希望スタッフ: $stuff\n
    ご連絡先: $user_phone\n
    E-mail: $user_email\n
    お店への連絡事項: $user_msg \n \n";

	$headers = "From: ".$from;
  //send the email

  //メール関数の実行
	$result = '';
	if (mail ($to, $subject, $body, $headers)) {
    $retflag = true;
   
	} else {
    $retflag = false;

	}
 //adminに遅れた場合お客様にメールが送信される
if ($retflag == false) {
  $result = '申し訳ありません!!<br>リクエストの送信中に<br>何か問題が発生しました。<br>後でもう一度やり直して頂くか、<br>直接お店にご連絡ください。';

  //メインのコメントもケースによって変わる様にする。
  $rettitle1 = "Sorry";
  $rettitle = "申し訳ございません！";

} else {
  //予約者に予約の完了メールを送る
  $from = 'nagoyagym@email.com';
  $to = $user_email; 
  $subject = 'NagoyaGymより : ご予約完了のお知らせ';

  $body = "From: $user_name 様\nご予約ID：$reserve_no \n \nこの度は当店のご予約頂き、誠にありがとうございます。\n
  以下の内容でのご予約が完了いたしました。\n \n
  $user_name 様のご来店を楽しみにお待ちしております。 \n \n
  ご予約日: $user_reserved_day . $user_reserved_time\n
  ご予約コース: $user_course\n
  希望スタッフ: $stuff\n
  ご連絡先: $user_phone\n
  お店への連絡事項: $user_msg \n \n
  日時やコースのご変更などある場合は、直接お電話ください。\n
  NagoyaGym:090-0000-2222\n
  open　10:00 〜 close　22:00\n
  定休日：木曜日\n"; 
  $headers = "From:".$user_name."様";

  //send the email
  $result = '';
  if (mail ($to, $subject, $body, $headers)) {
    
    $result .= 'ご予約ありがとうございます!<br><br>確認メールを送信しました。<br>ご確認ください。';

    $rettitle1 = "Completely";
    $rettitle = "送信完了";

  } else {
    
    $result .= '申し訳ありません!!<br>リクエストの送信中に<br>何か問題が発生しました。<br>後でもう一度やり直して頂くか、<br>直接お店にご連絡ください。';

    $rettitle1 = "Sorry";
    $rettitle = "申し訳ございません！";
    
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <title>NagoyaGym ご予約画面</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" href="favicon (1).ico">
  <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.css">

  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">

  <link rel="stylesheet" href="css/aos.css">

  <link rel="stylesheet" href="css/ionicons.min.css">

  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">


  <link rel="stylesheet" href="css/flaticon.css">
  <link rel="stylesheet" href="css/icomoon.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body style="background-color: rgb(37,37,37); color: rgb(134,135,134);">
    <div class="py-1 bg-black top">
    	<div class="container">
    		<div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
	    		<div class="col-lg-12 d-block">
		    		<div class="row d-flex">
		    			<div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
						    <span class="text">052-961-1111</span>
					    </div>
					    <div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
						    <span class="text">nagoyagym@email.com</span>
					    </div>
					    <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right justify-content-end">
						    <p class="mb-0 register-link"><span>営業時間:</span>  <span>10:00AM - 10:00PM</span> <span>　定休日:</span> <span>木曜日</span></p>
					    </div>
				    </div>
			    </div>
		    </div>
		  </div>
    </div>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="https://nagoyagym.oka-show.site/">NagoyaGym(仮)</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item active"><a href="https://nagoyagym.oka-show.site/" class="nav-link">Home</a></li>
	        	<li class="nav-item"><a href="#about_section" class="nav-link">About</a></li>
	        	<li class="nav-item"><a href="menu.html" class="nav-link">料金プラン</a></li>
	        	<li class="nav-item"><a href="#qa_section" class="nav-link">よくあるご質問</a></li>
	          <li class="nav-item"><a href="contact.html" class="nav-link">お問い合わせ</a></li>
	          <li class="nav-item cta"><a href="reservation.php" class="nav-link">体験レッスンはコチラ</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
  <!-- END nav -->
  
  <section class="contents">
        <div class="col-md-12 order-md-last p-4 p-md-5 pt-5" style="background-color:#413F40;">
          <div style="height:200px"></div>  
          <div class="heading-section ftco-animate mb-5">
            <div class="col-md-9 ftco-animate mx-auto my-auto">
              <span class="subheading text-center"><?php echo $rettitle1; ?></span>
              <h1 class="text-center"  style="color:white;"><?php echo $rettitle; ?></h2>
            </div>
          </div>     
        </div>
    <div class="container">
      <div class="box5">
        <p class="text-center"><br><?php echo $result; ?><br>NagoyaGym:052-961-1111<br>open　10:00 〜 close　22:00<br>定休日：木曜日</p><br>
      </div>
    </div>

    <style>
      .box5 {
        padding: 0.5em 1em;
        margin: 2em 0;
        border: double 5px #C8A97D;
      }
      .box5 p {
        margin: 0; 
        padding: 0;
      }
    </style>
  </section>

  <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">NagoyaGym(仮)</h2>
              <p>Tel:052-961-1111<br>E-mail:nagoyagym@gmail.com</p>
              
            </div>
          </div>

          <div class="col-md-6 ml-auto mt-auto d-flex flex-column">
            <div class="ml-auto">
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
            <div class="ftco-footer-widget ml-auto">
              <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved |  by Oka-Show & <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
            </div>
          </div>
        </div>
      </div>
    </footer>


  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" /></svg></div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- jQuery UI -->
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

</body>

</html>



