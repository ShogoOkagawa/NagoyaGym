<?php
//require_once 'config/nagoyagym_config.php';

$user = "**********";
$pass = "**********";

$DB_HOST ="localhost";
$DB_DATEBASE = "**********";
$DB_USERNAME ="**********";
$DB_PASSWORD = "**********";
$dsn = "mysql:dbname=**********;host=localhost;charset=utf8mb4";

// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');

// 前月・次月リンクが押された場合は、GETパラメーターから年月を取得
if (isset($_GET['ym'])) {
  $ym = $_GET['ym'];
} else {
  // 今月の年月を表示
  $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
  $ym = date('Y-m');
  $timestamp = strtotime($ym . '-01');
}

// 今日の日付 フォーマット　例）2018-07-3
$today = date('Y-m-d');

// カレンダーのタイトルを作成　例）2017年7月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
// 方法１：mktimeを使う mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));

// 方法２：strtotimeを使う
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// １日が何曜日か　0:日 1:月 2:火 ... 6:土
// 方法１：mktimeを使う
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
// 方法２
// $youbi = date('w', $timestamp);

// カレンダー作成の準備
$weeks = [];
$week = '';

try {
  //PDOを使ったデータベースへの接続
  $dbh = new PDO($dsn, $user, $pass);
  //PDOの実行モードの設定
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // 第１週目：空のセルを追加
  // 例）１日が水曜日だった場合、日曜日から火曜日の３つ分の空セルを追加する
  $week .= str_repeat('<td></td>', $youbi);

  for ($day = 1; $day <= $day_count; $day++, $youbi++) {
    $date = $ym . '-' . str_pad($day,2,0,STR_PAD_LEFT);

    //カウントデータ取得のSQLを生成
    $sql = "SELECT count(*) as rec_cnt FROM customer_list WHERE user_reserved_day = ?";
    $stmt = $dbh->prepare($sql);
    $stmt ->bindValue(1, $date, PDO::PARAM_STR);
    $stmt ->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // $dbh = null;

    $rec_flag = 0;
    $yoyaku = '';
    //予約の有無の判断
    if ($result['rec_cnt'] == 0) {
    } else {
      $rec_flag = 1;
      //予約があった場合は、予約時間の詳細を見にいく
      //user_reserved_timeを取りに行く
      $sql = "SELECT user_reserved_time FROM customer_list WHERE user_reserved_day = ?";
      $stmt = $dbh->prepare($sql);
      $stmt ->bindValue(1, $date, PDO::PARAM_STR);
      $stmt ->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result as $row) {
        $yoyaku = $yoyaku. $row['user_reserved_time']. ",";
      }

    }

    if ($today == $date) {
      // 今日の日付の場合は、class="today"をつける
      $week .= '<td class="today">' . $day;
    } else {
  //---------------------------------------------------------------------
      //過去ならリンクしない
      //---------------------------------------------------------------------
      if ($today < $date) {
      $tempdate = strtotime($ym. '-'. str_pad($day,2,0,STR_PAD_LEFT));
      // $tempdate = mktime(0,0,0,$day,8,2020);
      $tempyoubi = date('w',$tempdate);

      if ($tempyoubi == 4) {
        $week .= '<td class="holiday">' . $day;

      } else {
        $week .= '<td class="normal">' . $day;

        if ($rec_flag == 1) {
          $week .= "<p class='m-0 text-center'><button class='check btn btn-warning rounded-circle shadow-sm'></button></p>";
        } else {
          $week .= "<p class='m-0 text-center'><button class='check btn btn btn-primary rounded-circle shadow-sm'></button></p>";
        }
      }
      } else {
        $week .= '<td class="past">' . $day;
      }
    }

    $week .= '<input id="yoyaku_'.$day.'" type="hidden" value="'.$yoyaku.'"></td>';

    // 週終わり、または、月終わりの場合
    if ($youbi % 7 == 6 || $day == $day_count) {

      if ($day == $day_count) {
        // 月の最終日の場合、空セルを追加
        // 例）最終日が木曜日の場合、金・土曜日の空セルを追加
        $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
      }

      // weeks配列にtrと$weekを追加する
      $weeks[] = '<tr>' . $week . '</tr>';

      // weekをリセット
      $week = '';
    }
  }
} catch (Exception $e) {
  //エラ〜メッセージ出力
  echo "エラー発生: " . htmlspecialchars($e->getMessage(),ENT_QUOTES, 'UTF-8') . "<br>";
  die();
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <style>
    .calender_container {
      font-family: 'Open Sans', sans-serif;
      margin-bottom:5rem;
    }
    #sample1 {
      margin-left: 1rem;
      margin-right: 1rem;

    }
    h3 {
      margin-bottom: 30px;
    }

    th {
      height: 30px;
      text-align: center;
    }

    td {
      height: 100px;
      cursor: pointer;
      color:#B9B9B9;
    }

    .today {
      background: #96885F;
      color:white;
    }
    .past {
      background:#B9B9B9;
      color:white;
    }
    .holiday {
      background:#B9B9B9;
      color:white;
    }

    th:nth-of-type(1),
    td:nth-of-type(1) {
      color: red;
    }

    th:nth-of-type(7),
    td:nth-of-type(7) {
      color: blue;
    }
    .check {
      height:25px;
      width:25px;
    }
  </style>
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
	        	<li class="nav-item"><a href="https://nagoyagym.oka-show.site/#about_section" class="nav-link">About</a></li>
	        	<li class="nav-item"><a href="menu.html" class="nav-link">料金プラン</a></li>
	        	<li class="nav-item"><a href="https://nagoyagym.oka-show.site/#qa_section" class="nav-link">よくあるご質問</a></li>
	          <li class="nav-item"><a href="contact.html" class="nav-link">お問い合わせ</a></li>
	          <li class="nav-item cta"><a href="reservation.php" class="nav-link">体験レッスンはコチラ</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
  <!-- END nav -->





  <section class="hero-wrap hero-wrap-2" style="background-image: url('images/1389433_m.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text align-items-end justify-content-center">
        <div class="col-md-9 ftco-animate text-center mb-4">
          <h1 class="mb-2 bread">体験レッスンについて</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>体験レッスンについて <i class="ion-ios-arrow-forward"></i></span></p>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section ftco-no-pt ftco-no-pb">
    <div class="container-fluid px-0">
      <div class="row d-flex no-gutters">
       
        <div class="col-md-12 order-md-last ftco-animate makelesson p-4 p-md-5 pt-5" style="background-color:rgb(65, 63, 64)">
          <div class="heading-section ftco-animate" style="margin-bottom: 100px;">
            <span class="subheading text-center">How to Lesson</span>
            <h2 class="mb-4 text-center" style="color:#D4D4D4">体験の流れ</h2>
          </div>
          <div class="lesson-container" id="lesson-1">

           <div class="lesson">
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン"><br>
                  <div class="lesson__content"><img class="lesson__img" src="images/fitness-contact.jpg" />
                    <h2 class="lesson__content-title"><span style="font-size:3.5rem">1.</span><u>予約する</u></h2>
                    <p class="lesson__content-desc">直接お店にお電話いていただくか、PCまたはスマホにてご予約していただきます。</p>
                  </div>
              </div>
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン">
                  <div class="lesson__content"><img class="lesson__img" src="images/3868798_m.jpg" />
                      <h2 class="lesson__content-title"><span style="font-size:3.5rem">2.</span><u>実際に来店</u></h2>
                      <p class="lesson__content-desc">予約日時に当ジムまでお越しいただきます。到着しましたら、スタッフにお声がけください。当日は運動しやすい格好・運動シューズ・お水などをお持ちください。</p>
                  </div>
              </div>
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン">
                  <div class="lesson__content"><img class="lesson__img" src="images/1442077_m1.jpeg" />
                      <h2 class="lesson__content-title"><span style="font-size:3.5rem">3.</span><u>施設見学</u></h2>
                      <p class="lesson__content-desc">更衣室で着替えた後はまずはスタッフが施設内をご案内いたします。体組成計によるチェックをしカウンセリングを行います。貴方の目指す体型や目標などをお聞かせください。</p>
                  </div>
              </div>
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン">
                  <div class="lesson__content"><img class="lesson__img" src="images/2650429_m.jpg" />
                      <h2 class="lesson__content-title"><span style="font-size:3.5rem">4.</span><u>ｶｳﾝｾﾘﾝｸﾞ</u></h2>
                      <p class="lesson__content-desc">伺った項目を元に当インストラクターが貴方に適した鍛えるべき箇所、それに伴ったトレーニング方法や食事面で気をつける事、摂取した方がいい栄養素などわかりやすくご説明いたします。<br><b><u>※施設見学＆無料カウンセリングの方はここまでです。</u></b></p>
                  </div>
              </div>
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン">
                  <div class="lesson__content"><img class="lesson__img" src="images/1389699_m.png" />
                      <h2 class="lesson__content-title"><span style="font-size:3.5rem">5.</span><u>ﾊﾟｰｿﾅﾙﾚｯｽﾝ</u></h2>
                      <p class="lesson__content-desc">実際に先ほどのカウンセリングをもとにパーソナルトレーニング体験です。初めての方にも無理なく適切な負荷をかけると同時に、効かせたい箇所へのちゃんとした刺激、また怪我を防止するためのフォームを実践しながら指導いたします。</p>
                  </div>
              </div>
              <div class="lesson-item" data-text="カウンセリング＆体験レッスン">
                  <div class="lesson__content"><img class="lesson__img" src="images/1389433_m.jpg" />
                      <h2 class="lesson__content-title"><span style="font-size:3.5rem">6.</span><u>体験終了！</u></h2>
                      <p class="lesson__content-desc">お疲れ様です！パーソナル体験終了。シャワーで汗を流したい方は無料でバスタオルのレンタルがございます。次のレッスンをご予約される場合はその場で入会していただけます。</p>
                  </div>
              </div>
            </div>
         </div>

        </div>
          <style>
          .lesson {
            display: flex;
            margin: 0 auto;
            flex-wrap: wrap;
            flex-direction: column;
            max-width: 700px;
            position: relative;
          }
          .lesson__content-title {
            font-weight: normal;
            font-size: 2rem;
            margin: 20px 0 0 0 ;
            transition: 0 10px;
            box-sizing: border-box;
            color: #fff;
          }
          .lesson__content-desc {
            margin: 0;
            font-size:15px;
            box-sizing: border-box;
            color: rgba(255, 255, 255, 0.7);
            line-height: 25px;
          }
          .lesson:before {
            position: absolute;
            left: 50%;
            width: 2px;
            height: 100%;
            margin-left: -1px;
            content:"";
            background: rgba(255, 255, 255, 0.07);
          }
          @media only screen and (max-width: 767px) {
            .lesson:before {
              left:40px;
            }
          }
          .lesson-item {
            padding: 40px 0;
            opacity: 0.3;
            filter: blur(2px);
            transition: 0.5s;
            box-sizing: border-box;
            width: calc(50% - 40px);
            display: flex;
            position: relative;
            transform: translateY(-80px);
          }
          .lesson-item:before {
            content: attr(data-text);
            letter-spacing: 3px;
            width: 100%;
            position: absolute;
            color: rgba(255, 255, 255, 0.5);
            font-size:13px;
            border-left: 2px solid rgba(255, 255, 255, 0.5);
            top: 70%;
            margin-top: -5px;
            padding-left: 20px;
            opacity: 0;
            right: calc(-100% - 40px);
          }
          .lesson-item:nth-child(even) {
            align-self: flex-end;
          }
          .lesson-item:nth-child(even):before {
            right: auto;
            text-align: right;
            left: calc(-100% - 40px);
            padding-left: 0;
            border-left: none;
            border-right: 2px solid rgba(255, 255, 255, 0.5);
            padding-right: 15px;
          }
          .lesson-item--active {
            opacity:1;
            transform: translateY(0);
            filter: blur(0px);
          }
          .lesson-item--active:before {
            top: 50%;
            transition: 0.3s all 0.2s;
            opacity: 1;
          }
          .lesson-item--active .lesson__content-title {
            margin: -30px 0 20px 0;
          }
          @media only screen and (max-width: 767px) {
            .lesson-item {
              align-self: baseline !important;
              width: 100%;
              padding: 0 30px 150px 80px;
            }
            .lesson-item:before {
              left: 10px !important;
              padding: 0 !important;
              top: 50px;
              text-align: center !important;
              width: 60px;
              border: none !important;
            }
            .lesson-item:last-child {
              padding-bottom: 40px;
            }
          }
          .lesson__img {
            max-width: 100%;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.4);
          }
          .lesson-container {
            width: 100%;
            position: relative;
            padding: 80px 0;
            transition: 0.3s ease 0s;
            background-attachment: fixed;
            background-size: cover;
          }
          .lesson-container:before {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(99, 99, 99, 0.8);
            content: "";
          }
          .lesson-header__title {
            color: #fff;
            font-size: 46px;
            font-weight: normal;
            margin: 0;
          }
          .lesson-header__subtitle {
            color:rgba(255, 255, 255, 0.5);
            font-size: 16px;
            letter-spacing: 5px;
            margin: 10px 0 0 0;
          }
          </style>
          
        <div class="col-md-12 order-md-last ftco-animate p-4 p-md-5 pt-5">
          <div class="py-md-9">

            <!-- ここから予約カレンダー -->
            <div class="heading-section ftco-animate mb-5">
              <span class="subheading text-center">Reservation</span>
              <h2 class="mb-4 text-center" style="color:#D4D4D4">ご予約</h2>
            </div>
            <div class="calender_container p-0">
              <div class="calender-head-title">
                <h3 style="color:#D4D4D4"><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
              </div>
              <div class="calender-body">
                <table class="table table-bordered m-0" id="sample1" data-toggle="modal" data-target="#exampleModalScrollable">
                  <tr>
                    <th style="color:#C8A97D;">日</th>
                    <th style="color:#C8A97D;">月</th>
                    <th style="color:#C8A97D;">火</th>
                    <th style="color:#C8A97D;">水</th>
                    <th style="color:#C8A97D;">木</th>
                    <th style="color:#C8A97D;">金</th>
                    <th style="color:#C8A97D;">土</th>
                  </tr>
                  <?php
                  foreach ($weeks as $week) {
                    echo $week;
                  }
                  ?>
                </table>
              </div>
            </div>
          </div>
        </div>
            <!-- ここまでが予約カレンダー -->

            <!-- ここからは＜お問い合わせフォーム＞ -->
        <div class="col-md-12 order-md-last ftco-animate makecontact p-4 p-md-5 pt-5" style="background-color:#353A3F;">
          <div class="py-md-9">
            <div class="heading-section ftco-animate mb-5">
              <span class="subheading text-center">Contact</span>
              <h2 class="mb-4 text-center" style="color:#D4D4D4">お問い合わせ</h2>
            </div>
            <form method="post" action="contact.php" class="needs-validation" novalidate>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <label for="validationCustom00">フリガナ</label>
                  <input type="text" name="user_name_kana" class="form-control" id="validationCustom00" placeholder="フリガナ" required>
                  <div class="invalid-feedback">
                    フリガナをご記入下さい。
                  </div>
                  <label for="validationCustom01">お名前</label>
                  <input type="text" name="user_name" class="form-control" id="validationCustom01" placeholder="お名前" required>
                  <div class="invalid-feedback">
                    お名前をご記入下さい。
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="validationCustom02">Email</label>
                  <input type="email" name="user_email" class="form-control" id="validationCustom02" placeholder="メールアドレス" required>
                  <div class="invalid-feedback">
                    メールアドレスをご記入下さい。
                  </div>

                  <label for="validationCustom03">PhoneNo</label>
                  <input type="text" name="user_phone" class="form-control" id="validationCustom03" placeholder="電話番号" required>
                  <div class="invalid-feedback">
                    電話番号をご記入下さい。
                  </div>
                </div>
                <div class="col-md-5 mb-3">
                  <label for="inlineFormCustomSelectPref">問い合わせスタッフ</label>
                  <select name="stuff" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                    <option value="" disabled selected>選択してください</option>
                    <option value="--">特にない</option>
                    <option value="スタッフA">スタッフA</option>
                    <option value="スタッフB">スタッフB</option>
                    <option value="スタッフC">スタッフC</option>
                  </select>
                </div>
                <div class="col-md-9 mb-3">
                  <label for="validationCustom04">お問合せ内容</label>
                  <textarea class="form-control" id="validationCustom04" name="user_msg" rows="4" maxlength="500" placeholder="お問い合わせ内容" required></textarea>
                  <div class="invalid-feedback">
                    お問合せ内容をご記入ください。
                  </div>
                </div>
                <div class="row col-md-12 mb-3 mt-4 mx-auto">
                  <div class="col-12 border text-center font-weight-bold" style="background-color:white">プライバシーポリシー</div>
                  <div class="border poricy-container" style="overflow-x: hidden; overflow-y: scroll;
                  width: 100hv; height: 150px; background-color:white;" >
                    <p class="font-weight-bold">適用範囲</p>
                    <p>本プライバシーポリシーは、当サイトにおいてのみ適用されます。</p>
                    <p class="font-weight-bold">個人情報の取得と利用目的</p><p>当サイトで取得する訪問者の個人情報と利用目的、保存期間等は下記の通りです。</p>
                    <p class="font-weight-bold">お問合せされた個人情報を取得します</p>
                    <p>当サイトでは<a href="○○○○○○"><U>お問い合わせフォーム</U></a>を設けています。訪問者がそのお問い合わせフォームから問い合わせをされた際に入力された、以下の個人情報を取得します。</p>
                    <ul>
                      <li>お問い合わせフォームに入力された名前</li>
                      <li>お問い合わせフォームに入力されたメールアドレス</li>
                      <li>お問い合わせフォームに入力された電話番号</li>
                      <li>お問い合わせフォームに入力された住所</li>
                      <li>お問い合わせフォームに入力されたお問い合わせ内容</li>
                    </ul>
                    <p class="font-weight-bold">利用目的について</p>
                    <P>お問い合わせ対応をするためと、訪問者の管理のためです。訪問者からのお問い合わせ情報を保存しておくことによって、同じ訪問者が別のお問い合わせをした際に、過去の問い合わせ内容を踏まえた対応をすることが出来、より的確な対応をすることが出来ます。また、当サイト内で「このようなお問合せがありました」と紹介させていただく場合もあります。</P>
                    <p class="font-weight-bold">保存期間について</p>
                    <p>お問い合わせフォームに入力された個人情報は、3年間保存します。</p>
                    <p class="font-weight-bold">個人情報取得の同意について</p>
                    <p>当サイトでは、お問い合わせフォームからお問い合わせをする前に、当プライバシーポリシーをご一読いただくよう案内しています。お問い合わせをされた時点で、その訪問者は当プライバシーポリシーに同意されたとみなします。</p>
                    <p class="font-weight-bold">Cookieによる個人情報の取得</p>
                    <p>当サイトは、訪問者のコンピュータにCookieを送信することがあります。Cookie（クッキー）とは、ウェブサイトを利用したときに、ブラウザとサーバーとの間で送受信した利用履歴や入力内容などを、訪問者のコンピュータにファイルとして保存しておく仕組みです。</p>
                    <p class="font-weight-bold">利用目的について</p>
                    <p>訪問者の当サイト閲覧時の利便性を高めるためです。たとえば、次回同じページにアクセスするとCookieの情報を使って、ページの運営者は訪問者ごとに表示を変えることができます。たとえばあるサイトを利用していて、初回はログインパスワードを入力する画面が表示されたけど、2回目以降はログイン画面は表示されずにアクセスできた、という経験ありませんか？それはCookieによるものです。訪問者がブラウザの設定でCookieの送受信を許可している場合、ウェブサイトは、訪問者のブラウザからCookieキーを取得できます。なお、訪問者のブラウザはプライバシー保護のため、そのウェブサイトのサーバーが送受信したCookieのみを送信します。</p>
                    <p class="font-weight-bold">保存期間について</p>
                    <p>当サイトに残されたコメントの Cookie は、1年間保存されます。</p>
                    <p class="font-weight-bold">第三者によるCookie情報の取得について</p>
                    <p>当サイトでは、グーグル株式会社やヤフー株式会社などをはじめとする第三者から配信される広告が掲載される場合があり、これに関連して当該第三者が訪問者のCookie情報等を取得して、利用している場合があります。当該第三者によって取得されたCookie情報等は、当該第三者のプライバシーポリシーに従って取り扱われます。</p>
                    <p class="font-weight-bold">第三者へのCookie情報等の広告配信の利用停止について</p>
                    <p>訪問者は、当該第三者のウェブサイト内に設けられたオプトアウト（個人情報を第三者に提供することを停止すること）ページにアクセスして、当該第三者によるCookie情報等の広告配信への利用を停止することができます。</p>
                    <p class="font-weight-bold">Cookie情報の送受信の許可・拒否について</p>
                    <p>訪問者は、Cookieの送受信に関する設定を「すべてのCookieを許可する」、「すべてのCookieを拒否する」、「Cookieを受信したらユーザーに通知する」などから選択できます。設定方法は、ブラウザにより異なります。Cookieに関する設定方法は、お使いのブラウザの「ヘルプ」メニューでご確認ください。すべてのCookieを拒否する設定を選択されますと、認証が必要なサービスを受けられなくなる等、インターネット上の各種サービスの利用上、制約を受ける場合がありますのでご注意ください。</p>
                    <p class="font-weight-bold">個人情報の管理</p>
                    <p>訪問者は、Cookieの送受信に関する設定を「すべてのCookieを許可する」、「すべてのCookieを拒否する」、「Cookieを受信したらユーザーに通知する」などから選択できます。設定方法は、ブラウザにより異なります。Cookieに関する設定方法は、お使いのブラウザの「ヘルプ」メニューでご確認ください。すべてのCookieを拒否する設定を選択されますと、認証が必要なサービスを受けられなくなる等、インターネット上の各種サービスの利用上、制約を受ける場合がありますのでご注意ください。</p>
                    <p class="font-weight-bold">1). 情報の正確性の確保</p>
                    <p>訪問者からご提供いただいた情報については、常に正確かつ最新の情報となるよう努めます。</p>
                    <p class="font-weight-bold">2). 安全管理措置</p>
                    <p>当サイトは、個人情報の漏えいや滅失又は棄損を防止するために、適切なセキリュティ対策を実施して個人情報を保護します。</p>
                    <p class="font-weight-bold">3). 個人情報の廃棄</p>
                    <p>個人情報が不要となった場合には、すみやかに廃棄します。</p>
                    <p class="font-weight-bold">4). 個人情報の開示、訂正、追加、削除、利用停止</p>
                    <p>訪問者ご本人からの個人情報の開示、訂正、追加、削除、利用停止のご希望の場合には、ご本人であることを確認させていただいた上、速やかに対応させていただきます。</p>
                    <p class="font-weight-bold">個人情報の第三者への提供について</p>
                    <p>当サイトは、訪問者からご提供いただいた個人情報を、訪問者本人の同意を得ることなく第三者に提供することはありません。また、今後第三者提供を行うことになった場合には、提供する情報と提供目的などを提示し、訪問者から同意を得た場合のみ第三者提供を行います。</p>
                    <p class="font-weight-bold">成年の個人情報について</p>
                    <p>未成年者が当サイトにコメントをしたり、お問い合わせフォームから問い合わせをされたりする場合は必ず親権者の同意を得るものとし、コメントやお問い合わせをされた時点で、当プライバシーポリシーに対して親権者の同意があるものとみなします。</p>
                    <p class="font-weight-bold">アクセス解析ツールについて</p>
                    <p>当サイトでは、Googleによるアクセス解析ツール「Googleアナリティクス」を利用しています。このGoogleアナリティクスはアクセス情報の収集のためにCookieを使用しています。このアクセス情報は匿名で収集されており、個人を特定するものではありません。GoogleアナリティクスのCookieは、26ヶ月間保持されます。この機能はCookieを無効にすることで収集を拒否することが出来ますので、お使いのブラウザの設定をご確認ください。 Googleアナリティクスの利用規約に関して確認したい場合は、<a href="https://marketingplatform.google.com/about/analytics/terms/jp/"><u>ここをクリック</u></a>してください。また、「ユーザーが Google パートナーのサイトやアプリを使用する際の Google によるデータ使用」に関して確認したい場合は、<a href="https://policies.google.com/technologies/partner-sites?hl=ja"><u>ここをクリック</u></a>してください。</p>
                    <p class="font-weight-bold">プライバシーポリシーの変更について</p>
                    <p>当サイトは、個人情報に関して適用される日本の法令を遵守するとともに、本プライバシーポリシーの内容を適宜見直しその改善に努めます。修正された最新のプライバシーポリシーは常に本ページにて開示されます。</p>
                  </div>
                </div>
                <div class="col-md-6 mb-3 form-group">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                      同意する
                    </label>
                    <div class="invalid-feedback">
                      送信する前に同意する必要があります。
                    </div>
                  </div>
                  <button class="btn btn-primary" type="submit">お問い合わせ内容を送信する</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- <div class="col-md-6 d-flex align-items-stretch pb-5 pb-md-0">
							<div id="map"></div> -->
        <!-- </div> -->
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        
        <div class="modal-content">
          <div class="modal-header">
            <!-- 予約した日にちが入る -->
            <h5 class="modal-title" id="exampleModalScrollableTitle"><?php echo $html_title; ?><span id="dateNow"></span>日</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="reservation_form.php" name="reserveModal">
            <div class="d-flex flex-column bd-highlight mb-3">
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">am10:00〜am11:00</p>
                  <p id="reserveYorN1" class="mb-2" style="font-size: 0.7rem;"></p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0">
                    <button id="reserveBtn1" type="button" class="btn btn-primary" onclick="submitok('am10:00')">予約する</button>
                  <!-- time関数を作りreservedTimeのvalueに代入しそこで取得した値をreservation_form.phpにsubmitする -->
                </div>
              </div>
              <!-- 以下同様に -->
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">am11:00〜pm0:00</p>
                  <p id="reserveYorN2" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn2" value="am11:00" type="button" class="btn btn-primary" onclick="submitok('am11:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm0:00〜pm1:00</p>
                  <p id="reserveYorN3" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn3" type="button" class="btn btn-primary" onclick="submitok('pm0:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm1:00〜pm2:00</p>
                  <p id="reserveYorN4" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn4" type=" button" class="btn btn-primary"onclick="submitok('pm1:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm2:00〜pm3:00</p>
                  <p id="reserveYorN5" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn5" type=" button" class="btn btn-primary" onclick="submitok('pm2:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm3:00〜pm4:00</p>
                  <p id="reserveYorN6" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn6" type="button" class="btn btn-primary" onclick="submitok('pm3:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm4:00〜pm5:00</p>
                  <p id="reserveYorN7" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn7" type=" button" class="btn btn-primary" onclick="submitok('pm4:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm5:00〜pm6:00</p>
                  <p id="reserveYorN8" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn8" type=" button" class="btn btn-primary" onclick="submitok('pm5:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm6:00〜pm7:00</p>
                  <p id="reserveYorN9" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn9" type="button" class="btn btn-primary" onclick="submitok('pm6:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm7:00〜pm8:00</p>
                  <p id="reserveYorN10" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn10" type="button" class="btn btn-primary" onclick="submitok('pm7:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm8:00〜pm9:00</p>
                  <p id="reserveYorN11" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn11" type="button" class="btn btn-primary" onclick="submitok('pm8:00')">予約する</button></div>
              </div>
              <div class="border d-flex flex-row bd-highlight">
                <div class="col-8">
                  <p class="mt-2 mb-n1">pm9:00〜pm10:00</p>
                  <p id="reserveYorN12" class="mb-2" style="font-size: 0.7rem;">ご予約可能です。</p>
                </div>
                <div class="col-5 float-sm-right my-auto p-0"><button id="reserveBtn12" type="button" class="btn btn-primary" onclick="submitok('pm9:00')">予約する</button></div>
              </div>

              <input type="hidden" name="reservedDate" value="<?php echo $html_title; ?>"> <!-- 隠しデータで年月を渡す -->
              <input type="hidden" name="reservedDate1" id="reservedDate1" value=""> <!-- 隠しデータで日を渡す -->
              <input type="hidden" name="reservedTime" id="reservedTime" value=""> <!-- 隠しデータで時間を渡す -->
            </form>  
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- ModalEnd -->

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
   <!-- 必須項目に対して無記入の場合、警告を表示するスクリプト -->
   <script>
      (function() {
        'use strict';
        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
    <!-- ここまでJS -->
    <!--レッスンの流れのJS -->
  <script>
    (function($) {
      $.fn.lesson = function() {
        var selectors = {
          id: $(this),
          item: $(this).find(".lesson-item"),
          activeClass: "lesson-item--active",
          img: ".lesson__img"
        };
        selectors.item.eq(0).addClass(selectors.activeClass);
        selectors.id.css(
          "background-image",
          "url(" +
          selectors.item
          .first()
          .find(selectors.img)
          .attr("src") +
          ")"
          );
          var itemLength = selectors.item.length;
          $(window).scroll(function() {
            var max, min;
            var pos = $(this).scrollTop();
            selectors.item.each(function(i) {
              min = $(this).offset().top;
              max = $(this).height() + $(this).offset().top;
              var that = $(this);
              if (i == itemLength - 2 && pos > min + $(this).height() / 2) {
                selectors.item.removeClass(selectors.activeClass);
                selectors.id.css(
                  "background-image",
                  "url(" +
                  selectors.item
                  .last()
                  .find(selectors.img)
                  .attr("src") +
                  ")"
                  );
                  selectors.item.last().addClass(selectors.activeClass);
                } else if (pos <= max - 40 && pos >= min) {
                  selectors.id.css(
                    "background-image",
                    "url(" +
                    $(this)
                    .find(selectors.img)
                    .attr("src") +
                    ")"
                    );
                    selectors.item.removeClass(selectors.activeClass);
                    $(this).addClass(selectors.activeClass);
                  }
                });
              });
            };
          })(jQuery);
        
    $("#lesson-1").lesson();
              
  </script>
  <!-- ここまでスクリプト -->
  <!--カレンダーモーダルのスクリプト -->
  <script>
   $(function() {
      $("#sample1 td").on('click', function() {
        $("#reserveBtn1").prop("disabled",false);
        $("#reserveBtn2").prop("disabled",false);
        $("#reserveBtn3").prop("disabled",false);
        $("#reserveBtn4").prop("disabled",false);
        $("#reserveBtn5").prop("disabled",false);
        $("#reserveBtn6").prop("disabled",false);
        $("#reserveBtn7").prop("disabled",false);
        $("#reserveBtn8").prop("disabled",false);
        $("#reserveBtn9").prop("disabled",false);
        $("#reserveBtn10").prop("disabled",false);
        $("#reserveBtn11").prop("disabled",false);
        $("#reserveBtn12").prop("disabled",false);
        $("#reserveYorN1").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN2").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN3").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN4").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN5").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN6").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN7").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN8").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN9").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN10").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN11").text("ご予約可能です。").css("color", "green");
        $("#reserveYorN12").text("ご予約可能です。").css("color", "green");
        $("#reserveBtn1").text("予約する");
        $("#reserveBtn2").text("予約する");
        $("#reserveBtn3").text("予約する");
        $("#reserveBtn4").text("予約する");
        $("#reserveBtn5").text("予約する");
        $("#reserveBtn6").text("予約する");
        $("#reserveBtn7").text("予約する");
        $("#reserveBtn8").text("予約する");
        $("#reserveBtn9").text("予約する");
        $("#reserveBtn10").text("予約する");
        $("#reserveBtn11").text("予約する");
        $("#reserveBtn12").text("予約する");
        //alert($(this).attr("class"));
        if ($(this).hasClass("normal")) {
          //alert("normalです");
          var td_now = $(this).text();
          $("#dateNow").text(td_now).val();
          $("#reservedDate1").val(td_now);
          var yoyaku_date = $("#yoyaku_"+td_now).val();
          yoyaku_date.split(',').forEach( function( value ) {
          // alert(value);
          if (value == "am10:00〜") {
            $("#reserveBtn1").prop("disabled",true);
            $("#reserveBtn1").text("予約不可");
            $("#reserveYorN1").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "am11:00〜") {
            $("#reserveBtn2").prop("disabled",true);
            $("#reserveBtn2").text("予約不可");
            $("#reserveYorN2").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm0:00〜") {
            $("#reserveBtn3").prop("disabled",true);
            $("#reserveBtn3").text("予約不可");
            $("#reserveYorN3").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm1:00〜") {
            $("#reserveBtn4").prop("disabled",true);
            $("#reserveBtn4").text("予約不可");
            $("#reserveYorN4").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm2:00〜") {
            $("#reserveBtn5").prop("disabled",true);
            $("#reserveBtn5").text("予約不可");
            $("#reserveYorN5").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm3:00〜") {
            $("#reserveBtn6").prop("disabled",true);
            $("#reserveBtn6").text("予約不可");
            $("#reserveYorN6").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm4:00〜") {
            $("#reserveBtn7").prop("disabled",true);
            $("#reserveBtn7").text("予約不可");
            $("#reserveYorN7").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm5:00〜") {
            $("#reserveBtn8").prop("disabled",true);
            $("#reserveBtn8").text("予約不可");
            $("#reserveYorN8").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm6:00〜") {
            $("#reserveBtn9").prop("disabled",true);
            $("#reserveBtn9").text("予約不可");
            $("#reserveYorN9").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm7:00〜") {
            $("#reserveBtn10").prop("disabled",true);
            $("#reserveBtn10").text("予約不可");
            $("#reserveYorN10").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm8:00〜") {
            $("#reserveBtn11").prop("disabled",true);
            $("#reserveBtn11").text("予約不可");
            $("#reserveYorN11").text("この時間はご予約できません。").css("color", "red");
          } else if (value == "pm9:00〜") {
            $("#reserveBtn12").prop("disabled",true);
            $("#reserveBtn12").text("予約不可");
            $("#reserveYorN12").text("この時間はご予約できません。").css("color", "red");
          }
          })
          $("#my_modal").modal();
        } else {
          //ノーマル以外は予約できなくする（予約ボタンをdisable）
          var td_now = $(this).text();
          $("#dateNow").text(td_now).val();
          $("#reserveBtn1").prop("disabled",true);
          $("#reserveBtn1").text("予約不可");
          $("#reserveYorN1").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn2").prop("disabled",true);
          $("#reserveBtn2").text("予約不可");
          $("#reserveYorN2").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn3").prop("disabled",true);
          $("#reserveBtn3").text("予約不可");
          $("#reserveYorN3").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn4").prop("disabled",true);
          $("#reserveBtn4").text("予約不可");
          $("#reserveYorN4").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn5").prop("disabled",true);
          $("#reserveBtn5").text("予約不可");
          $("#reserveYorN5").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn6").prop("disabled",true);
          $("#reserveBtn6").text("予約不可");
          $("#reserveYorN6").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn7").prop("disabled",true);
          $("#reserveBtn7").text("予約不可");
          $("#reserveYorN7").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn8").prop("disabled",true);
          $("#reserveBtn8").text("予約不可");
          $("#reserveYorN8").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn9").prop("disabled",true);
          $("#reserveBtn9").text("予約不可");
          $("#reserveYorN9").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn10").prop("disabled",true);
          $("#reserveBtn10").text("予約不可");
          $("#reserveYorN10").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn11").prop("disabled",true);
          $("#reserveBtn11").text("予約不可");
          $("#reserveYorN11").text("この時間はご予約できません。").css("color", "red");

          $("#reserveBtn12").prop("disabled",true);
          $("#reserveBtn12").text("予約不可");
          $("#reserveYorN12").text("この時間はご予約できません。").css("color", "red");

        }
      });
      $('#modal_close').on('click', function() {
        $('#my_modal').modal('hide');
      });
    })
    function submitok(time) {
        $("#reservedTime").val(time);
        document.reserveModal.submit();
    } 
  </script>
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
