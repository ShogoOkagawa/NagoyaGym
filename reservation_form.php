<?php
 $reservedDate = $_POST["reservedDate"];
 $reservedDate1 = $_POST["reservedDate1"];
 $reservedTime = $_POST["reservedTime"];

//  $arr = array(
//   '' => '選択してください'
//   , '無料カウンセリングコース' => '無料カウンセリングコース'
//   , '体験レッスンコース' => '体験レッスンコース'
//   , '【会員様】レッスンご予約' => '【会員様】レッスンご予約'
// );

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
		
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/fitness-contact.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate text-center mb-4">
            <h1 class="mb-2 bread">ご予約画面</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span><a href="resevation.html">Reservation <i class="ion-ios-arrow-forward"></i></a></span></p>
          </div>
        </div>
			</div>
    </section>
		
		<section class="ftco-section ftco-no-pt ftco-no-pb">
			<div class="container-fluid px-0">
				<div class="row d-flex no-gutters"> 
          <div class="col-md-12 order-md-last ftco-animate makereservation p-4 p-md-5 pt-5">
          	<div class="py-md-8">
              <div class="heading-section ftco-animate mb-5">
                <span class="subheading text-center">Form</span>
		            <h2 class="mb-4 text-center">入力画面</h2>
							</div>
							<form id="formInfo" class="needs-validation"  novalidate>
                <div class="d-flex flex-column bd-highlight mb-6">
                  <div class="col-8 mx-auto border text-center">ご予約日時<div id="reserveDate" class="font-weight-bold my-3"><?php echo $_POST["reservedDate"]. $_POST["reservedDate1"]; ?>日</div></div>
                  <div class="col-8 mb-6 mx-auto border text-center">ご予約時間<div id="reserveHour" class="font-weight-bold my-3"><?php echo $_POST["reservedTime"]; ?>〜</div></div>
                </div>
                <div class="mt-5 mb-5 text-center"></div>
	              <div class="form-row col-10 mx-auto">
	                <div class="col-md-6 mb-3">
                    <div name="l1">
                      <label class="mb-0 mt-2">*フリガナ</label>
                      <input type="text" id="reserveFurigana" class="form-control" placeholder="フリガナ" required>
                      <div class="invalid-feedback">
                        フリガナをご記入下さい。
                      </div>
                    </div>
                    <div name="l2">
                      <label class="mb-0 mt-2" for="validationCustom01">*お名前</label>
                      <input type="text" class="form-control" id="reserveName" placeholder="お名前" required>
                      <div class="invalid-feedback">
                        お名前をご記入下さい。
                      </div>
                    </div>
									</div>

									<div class="col-md-6 mb-3">
                    <div name="l3">
                      <label class="mb-0 mt-2" for="validationCustom02">*Email</label>
                      <input type="email" class="form-control" id="reserveEmail" placeholder="メールアドレス" required>
                      <div class="invalid-feedback">
                        メールアドレスをご記入下さい。
                      </div>
                    </div>
                    <div name="l4">
                      <label class="mb-0 mt-2" for="validationCustom03">*PhoneNo</label>
                      <input type="text" class="form-control" id="reservePhone" placeholder="お電話番号" required>
                      <div class="invalid-feedback">
                        電話番号をご記入下さい。
                      </div>
                    </div>
                  </div>
                  <!-- display_name -->

									<div class="col-12 mb-3">
										<label class="col-12 px-0 mb-0 mt-2" for="inlineFormCustomSelectPref">*今回ご希望のコース</label>
										<select id="reserveCourse" name="selectCourse" class="custom-select col-12 my-1 mr-sm-2" required>
                    <!-- <?php foreach ($arr as $key => $val): ?>
                    <option value="<?= $key; ?>"><?= $val; ?></option>
                    <?php endforeach; ?> -->
											<option value="---" disabled selected>選択してください</option>
											<option value="無料カウンセリングコース">無料カウンセリングコース</option>
											<option value="体験レッスンコース">体験レッスンコース</option>
                      <option value="【会員様限定】レッスンのご予約">【会員様限定】レッスンのご予約</option>
                    </select>
                    <div class="invalid-feedback">
											希望するコースを選択してください。
										</div>
									</div>
									<div class="form-row col-md-6 mb-3 mr-0 ml-0">
										<label class="px-0 mb-0 mt-2">希望スタッフ</label>
										<select id="reserveStuff" class="custom-select my-1 mr-sm-2">
											<option value="---" selected>特にない</option>
											<option value="スタッフA">スタッフA</option>
											<option value="スタッフB">スタッフB</option>
											<option value="スタッフC">スタッフC</option>
											<option>(※体験コースではご希望に添えない場合もございます。)</option>
                    </select>
                  </div>
                  <div class="row col-md-6 mb-3 ml-0 mr-0">
										<label class="px-0 mb-0 mt-2">当サイトを知ったきっかけ</label>
										<select id="reserveChannel" class="custom-select my-1 mr-sm-2">
											<option disabled>選択してください</option>
											<option value="HP" selected>HP(当店ホームページ)</option>
											<option value="ご紹介">ご紹介</option>
											<option value="チラシ・広告">チラシ・広告</option>
											<option value="SNS">スタッフやお店のSNS</option>
										</select>
									</div>
									<div class="col-md-12 mb-3">
										<label class="mb-0 mt-2" for="validationCustom04">事前に聞きたい事<br>(ご紹介の場合はこちらにご紹介者をご記入下さい)</label>
										<textarea class="form-control" id="reserveMsg" rows="4" maxlength="500" placeholder="何か不安な事や、事前に聞きたい事があればご記入下さい"></textarea>
                  </div>
                  <div class="row col-md-12 mb-3 mt-4 mx-auto">

                    <div class="col-12 border text-center font-weight-bold">プライバシーポリシー</div>
                    <div class="border poricy-container" style="overflow-x: hidden; overflow-y: scroll;
                    width: 100hv; height: 150px">
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
												同意ならチェック
											</label>
											<div class="invalid-feedback">
												送信する前に同意する必要があります。
											</div>
                    </div>
                    <button type="button" class="btn btn-primary" id="modalSet" onclick="onButtonClick()">Launch demo modal</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

     <!-- Modal -->
      <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalScrollableTitle">確認画面</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="add.php">
                <div class="border d-flex flex-column bd-highlight p-3">
                  <div class="d-flex bd-highlight mb-3"><span>フリガナ</span><input type="hidden" id="form0" name="user_name_kana"><span id="form00" class="mr-4 ml-auto"></span></div>
                  <div class="d-flex bd-highlight mb-3"><span>お名前</span><input type="hidden" id="form1" name="user_name"><span id="form01" name="user_name" class="mr-4 ml-auto"></span></div>
                  <div class="d-flex bd-highlight mb-3"><span>ご予約日</span><input type="hidden" name="user_reserved_day" value="<?php echo $_POST["reservedDate"]. $_POST["reservedDate1"]; ?>日"><span  class="mr-4 ml-auto"><?php echo $_POST["reservedDate"]. $_POST["reservedDate1"]; ?>日</span></div>
                  <div class="d-flex bd-highlight mb-3"><span>ご予約時間</span><input type="hidden" name="user_reserved_time" value="<?php echo $_POST["reservedTime"]; ?>〜"><span name="user_reserved_time" class="mr-4 ml-auto"><?php echo $_POST["reservedTime"]; ?>〜</span></div>
                  <div class="d-flex bd-highlight mb-3"><span>ご予約コース</span><input type="hidden" id="form4"  name="user_course"><span id="form5" class="ml-auto mr-4"></span></p></div>
                  <div class="d-flex bd-highlight mb-3"><span>ご希望のスタッフ</span><input type="hidden" id="form6" name="stuff"><span id="form7" class="ml-auto mr-4"></span></div>
                  <div class="d-flex bd-highlight mb-3"><span>メールアドレス</span><input type="hidden" id="form8" name="user_email"><span id="form08" name="user_email" class="ml-auto mr-4"></span></div>
                  <div class="d-flex bd-highlight mb-3"><span>お電話番号</span><input type="hidden" id="form9" name="user_phone"><span id="form09" name="user_phone" class="ml-auto mr-4"></span></div>
                  <div class="d-flex bd-highlight mb-3"><span>きっかけ　</span><input type="hidden" id="form10" name="channel"><span id="form11" class="ml-auto mr-4"></span></div>
                  <div class="d-flex bd-highlight mt-0 mb-n1"><label class="mb-0 mt-2" for="validationCustom04">聞きたい事</label></div>
                  <textarea id="form12" class="ml-auto form-control" name="user_msg" rows="4" maxlength="500"></textarea>
                  <div aria-hidden="true" id="nowTime" name="user_registered"></div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">変更する</button>
                  <button type="submit" class="btn btn-primary">ご予約登録</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

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
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

    <script>
      (function() {
        'use strict';
        window.addEventListener('load', function() {
          // Bootstrap検証スタイルを適用するすべてのフォームをフェッチします
          var forms = document.getElementsByClassName('needs-validation');
          // ループして提出を防ぐ
          var validation = Array.prototype.filter.call(forms, function(form) {
  　　　　　　 // 2020.06.07 t.kawasumi
  　　　　　　 // ボタンのIDを使用してクリックイベント時にバリデーションを動かす
            var btn = document.getElementById('modalSet');
            btn.addEventListener('click', function(event)  {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              } else {
                // 2020.06.07 t.kawasumi
                // バリデーションエラーがない場合に限りモーダル起動
                // toggle : モーダル・ダイアログが開いていれば閉じる／モーダル・ダイアログが閉じていれば開く
                // show : 開く
                // hide : 閉じる
                $('#exampleModalScrollable').modal('toggle');
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
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
    <script>
      function onButtonClick() {
      target0 = document.getElementById('form0');
      target00 = document.getElementById('form00');
      target1 = document.getElementById('form1');
      target01 = document.getElementById('form01');
      target4 = document.getElementById('form4');
      target5 = document.getElementById('form5');
      target6 = document.getElementById('form6');
      target7 = document.getElementById('form7');
      target8 = document.getElementById('form8');
      target08 = document.getElementById('form08');
      target9 = document.getElementById('form9');
      target09 = document.getElementById('form09');
      target10 = document.getElementById('form10');
      target11 = document.getElementById('form11');
      target12 = document.getElementById('form12');
  
      hurigana = document.getElementById('reserveFurigana');
      namae = document.getElementById('reserveName');
      // yoyakubi = document.getElementById('reserveDate');
      // yoyakujikann = document.getElementById('reserveHour');
      mail = document.getElementById('reserveEmail');
      call = document.getElementById('reservePhone');
      // kikkake = document.getElementById('reserveChannel');
      message = document.getElementById('reserveMsg');
      // selectについてはjqueryにて値を取得。選択されている<option>要素を取り出す
      var selected = $("#reserveCourse").children("option:selected"); //「option」は省略可
      var selected1 = $("#reserveStuff").children("option:selected"); 
      var selected2 = $("#reserveChannel").children("option:selected"); 
  
      // 値とテキストを取り出す
      var yoyakucoerseValue = selected.val();
      var yoyakucoerseText = selected.text();
      var yoyakustuffValue = selected1.val();
      var yoyakustuffText = selected1.text();
      var kikkakeValue = selected2.val();
      var kikkakeText = selected2.text();
  
      target0.value = hurigana.value;
      target00.innerText = hurigana.value;
      target1.value = namae.value;
      target01.innerText = namae.value;
      // target2.value = yoyakubi.value;
      // target02.innerText = yoyakubi.text;
      // target3.value = yoyakujikann.value;
      // target03.innerText = yoyakujikann.text;
      target4.value = yoyakucoerseValue;
      target5.innerText = yoyakucoerseText;
      target6.value = yoyakustuffValue;
      target7.innerText = yoyakustuffText;
      target8.value = mail.value;
      target08.innerText = mail.value;
      target9.value = call.value;
      target09.innerText = call.value;
      target10.value = kikkakeValue;
      target11.innerText = kikkakeText;
      target12.innerText = message.value;
      }
    </script>
    
  </body>
</html>