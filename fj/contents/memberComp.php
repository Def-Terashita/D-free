<?php
//---------------------------------------------------------
// 表側新規会員登録完了ページ  |  最終更新日:2019/01/29
//---------------------------------------------------------
//
// 表側新規会員登録ページ(newMember.php)で入力された内容を表示
// 登録ボタン押下で同じメールアドレスがDBになければ
// ユーザー情報をuser_tbにインサートし、
// 登録ユーザーと管理にメールを送信。
// 完了後、「登録」ボタンを消し「今すぐログイン」ボタンを表示。
//「今すぐログイン」ボタン押下でTOPページへ遷移。
// 同じメールアドレスがあれば再入力を促す。
// 戻るボタン押下で表側新規会員登録ページ(newMember.php)に戻る。
// メール送信失敗の場合ログに記録
//
//------------------------------------------------------------------

// セッション開始
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');



$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//新規登録画面からの遷移でなければTOPへ
if (!isset($_SESSION["newuser"]["name"]))
{
    header("Location:" .Fj_Top);
    exit();
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

date_default_timezone_set('Asia/Tokyo');

$errmsg     = array (); 		                 // エラーメッセージ
$errlog     = array (); 		                 // エラーログ
$db         = NULL;                             // DBオブジェクト
$title      = "以下の内容でよろしければ登録を押してください";                       // エラーフラグOFF
$msg        = "";
$membercomp = "";
$color      ="";
$userskillStr = "";

//=== 共通関数：インクルード ===
$ret = include_once(FJ_Config);  // config.php
$ret = include_once(FJ_Mg_Pass); // password_hash用

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    require ("$config_path" . "$config_file");
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


// 今すぐログインを押された場合
if (isset($_POST["login"]))
{
    $_SESSION["name"] = $_SESSION["newuser"]["name"];
    unset($_SESSION["newuser"]);
    header ("Location:" .Fj_Top);  //トップ画面へ遷移
    exit();     //処理終了
}



// 初回表示
for ($i=0,$passStr=""; $i<strlen($_SESSION["newuser"]["pass"]);$i++)
{
	//パスワードを＊で表示
    $passStr.="*";
}


foreach($_SESSION["newuser"]["skill"] as $val)
{
    //スキルカテゴリ表示
    $userskillStr .= $val."　";
} 


$msg = "<div class='form-box'>
            <div class='tablewrap newmember_tablewrap'>
                <table  class='bg-white'>
                    <tr>
        				<th>お名前</th>
        				<td>
        					". $_SESSION["newuser"]["name"] . "
        				</td>
        			</tr>

        			<tr>
        				<th>電話番号</th>
        				<td>
        					". $_SESSION["newuser"]["tel"] ."
        				</td>
        			</tr>

        			<tr>
        				<th>メールアドレス</th>
        				<td>
        					". $_SESSION["newuser"]["mail"] ."
        				</td>
        			</tr>
        			<tr>
        				<th>パスワード</th>
        				<td>
        					". $passStr ."
        				</td>
        			</tr>
        			<tr>
        				<th>希望スキル</th>
        				<td>
        					". $userskillStr ."
        				</td>
        			</tr>
        			<tr>
        				<th>現在のご職業</th>
        				<td>
        					". $_SESSION["newuser"]["job"] ."
        				</td>
        			</tr>
        			<tr>
        				<th>希望就業時期</th>
        				<td>
        					". $_SESSION["newuser"]["date"] ."
        				</td>
        			</tr>

                </table>
            <div>
        </div>
        <div class='space'></div>";


$btn = "<form action='". $_SERVER["SCRIPT_NAME"]. "' method='post'  onSubmit='return doublelClick(this)'>
            <button type='submit' class='reset-btn form-btn bg-darkblue color-white' name='signup'><span>登録する</span></button>
        </form>
        <div class='space'></div>
        <form action='". Fj_NewMember. "' method='post'>
            <button type='submit' class='reset-btn form-btn bg-bluegreen color-white' name='return'><span>再入力</span></button>
        </form>";


// 登録ボタン押下
if (isset($_POST["signup"]))
{
    $username = $_SESSION["newuser"]["name"];
    $tel      = $_SESSION["newuser"]["tel"];
    $mail     = $_SESSION["newuser"]["mail"];
    $pass     = $_SESSION["newuser"]["pass"];
	$userdate = $_SESSION["newuser"]["date"];
	$userjob  = $_SESSION["newuser"]["job"];
    
    try
    {

        //パスワードハッシュを作る
        $options = array('cost' => 10);
        $hash = password_hash($pass, PASSWORD_DEFAULT, $options);

        //========== ここからDB ==========

        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

        // 文字コード
        $ret = $db->exec ("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $ret = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合
        // ＳＱＬ
        $sql = "INSERT INTO user_tb(password, email, name, tel, create_datetime)";
        $sql .= " VALUES(?, ?, ?, ?, now());";

        $result = $db->prepare($sql);    //prepareメソッド

        if($result->execute(array
            ( $hash
                ,$mail
                ,$username
                ,$tel
            )) === false)
        {
            $title = "このメールアドレスは既に登録されています。";
            $color = "color-red";
            $btn = "<form action='". Fj_NewMember. "' method='post'>
                        <button type='submit' class='reset-btn form-btn bg-bluegreen color-white' name='return'><span>再編集</span></button>
                    </form>";
        }
        else
       {

            $title = "登録完了＆ログイン";
            $membercomp = "<div id='membercomp'>
                        <b>ご登録ありがとうございました。
                        <br>以下の内容で登録いたしました。
                        <br>ご入力いただきましたメールアドレスに登録完了メールをお送りいたしましたのでご確認くださいませ。</b>
                    </div>
                    ";
            $btn = "<div>
                        <form action='". $_SERVER["SCRIPT_NAME"]. "' method='post'>
                            <button type='submit' class='reset-btn form-btn bg-yellow color-white' name='login'><span>今すぐログイン</span></button>
                        </form>
                    </div>";

            // ----- メール送信 -----------------------------------------------------------------

            // メール送信用プログラム読み込み
            include_once(FJ_Mail);
            // 日本語設定
            mb_language('ja');
            mb_internal_encoding('UTF-8');


            //--- php.ini メール設定の変更指定 ---

            // SMTPポートNO
            ini_set("smtp_port",		Smt_PortNo);
            // 送信元(FROM)アドレス
            ini_set('sendmail_from',    Mail_FromAdress);

            // ユーザーへ送信
            $fromName_user   = Mail_FromName;
            $mailFrom_user   = Mail_FromAdress;
            $mailTo_user     = $mail;
            $subject_user    = Mail_Subj_usercomp;
            $comment_user    = $username. "様";
            $comment_user   .= "\r\n";
            $comment_user   .= "この度はご登録ありがとうございます。". "\r\n";
            $comment_user   .= "24時間以内に担当からご連絡を差し上げますのでしばらくお待ちくださいませ。（土日祝除く）";
            $comment_user   .= "\r\n";
            $comment_user   .= "\r\n";
            $comment_user   .= "■□■□■□■□■□■□■□■□■□■□■□\r\n";
            $comment_user   .= "株式会社Def tribe\r\n";
            $comment_user   .= "〒542-0081\r\n";
            $comment_user   .= "大阪市中央区南船場4丁目12番24号現代心斎橋ビル5階\r\n";            
            $comment_user   .= "TEL：06-6643-9305\r\n";
            $comment_user   .= "Email：".Mail_FromAdress."\r\n";
            $comment_user   .= "■□■□■□■□■□■□■□■□■□■□■□\r\n";

            $returnMail_user = $mailTo_user;
/*
            $result = sendmail($fromName_user, $mailFrom_user, $mailTo_user, $subject_user, $comment_user, $returnMail_user);
            if ($result) {
            } else {
                $errlog[] = '【新規登録】ユーザへの自動メール送信失敗';
                $membercomp = "<div id='membercomp'><b>ご登録ありがとうございました。<br>以下の内容で登録いたしました。</b></div>";
            }*/

            // 管理者へ送信
            $fromName   = Mail_FromName;
            $mailFrom   = Mail_FromAdress;
            $mailTo     = Mail_FromAdress;
            $subject    = Mail_Subj_New;
            $comment    = "お名前：".$username . "様"."\r\n";
            $comment   .= "電話番号：".$tel . "\r\n";
            $comment   .= "メールアドレス：".$mail . "\r\n";
            $comment   .= "希望スキル：".$userskillStr . "\r\n";
            $comment   .= "現在のご職業：".$userjob . "\r\n";
            $comment   .= "希望就業日：".$userdate . "\r\n";
            $returnMail = $mailTo;
            /*$result = sendmail($fromName, $mailFrom, $mailTo, $subject, $comment, $returnMail);
            if ($result) {
            } else {
                $errlog[] = '【新規登録】管理者への登録連絡メール送信失敗';
                $membercomp = "<div id='membercomp'><b>ご登録ありがとうございました。<br>以下の内容で登録いたしました。</b></div>";
            }*/

            // ------------------------------------------------------------------------------------
        }
        // ＤＢ接続開放
        $db = NULL;

    }
    //=== 例外処理 ===
    catch(Exception $e)
    {
        //--- エラーメッセージ、エラーコード、エラーラインＮｏ. ---
        // エラーメッセージ：ＯＳ別設定
        // PHP_OS : 「AIX / Darwin / MacOS / Linux / SunOS / WIN32 / WINNT / Windows」
        if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
        {
            // 全角変換  SJIS ---> UTF-8
            $errmsg[] = "MSG:" . mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS");
        }
        else
       {
            $errmsg[] = "MSG:" . $e->getMessage()."<br />";
        }
        // エラーコード
        $errmsg[] = "CODE:" . $e->getCode()."<br />";
        // エラーラインＮｏ.
        $errmsg[] = "LINE:" . $e->getLine()."<br />";
        // ＤＢ接続開放
        $db = NULL;
    }

}


// エラーメッセージをログに記録
if (!empty($errlog))
{

    //=== ログ準備 ===
    $dateObj = new DateTime();
    $date = $dateObj->format('Ymd');
    $fileName =  '../../errorlog/' . $date . '.log';

    $log = "\n".$dateObj->format('Y-m-d H:i:s');

    foreach ($errlog as $val)
    {
        $log .= "\n".$val;
    }

    // ファイルを追記モードで開く
    $fp = fopen($fileName, 'ab');

    // ファイルをロック（排他的ロック）
    flock($fp, LOCK_EX);

    // ログの書き込み
    fwrite($fp, $log);

    // ファイルのロックを解除
    fflush($fp);
    flock($fp, LOCK_UN);

    // ファイルを閉じる
    fclose($fp);
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="システム開発,インフラ構築,人材派遣,IT">
    <meta name="description" content="">
    <title>無料会員登録 - フリーランスJOBS</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Contents_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>

	<script>
    //ダブルクリック無効
    	var set=0;

    	function doublelClick() {
    		if(set==0){
    			set=1;
    		}
    		else
    		{
    			alert("ただいま登録中です。\nそのままお待ちください。");
    			return false;
    		}
    	}
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="<?= Fj_GooglAnalytics_async ?>"></script>
    <script src="<?= Fj_GooglAnalytics ?>"></script>



</head>


<body>
	<?php //設定情報エラー
		if (!empty($errmsg))
		{
// 			foreach ($errmsg as $val)
// 			{
// 				echo $val;
// 			}
			$color = "color-red";
			$title = "エラー";

			$msg = "<div>
                    <br><b>エラーが発生しました。
                    <br>申し訳ございませんが、お電話かメールにてお問い合わせください。
                    <br>TEL：06-6643-9305 / <a href='mailto:info@freelance-jobs.jp?subject=問い合わせ&amp;body=ご記入ください'>MAIL：info@freelance-jobs.jp</a></b>
                </div>
                <div class='space'></div>";
			$btn = "<form action='". Fj_Top. "' method='post'>
                    <button type='submit' class='reset-btn form-btn bg-bluegreen color-white' name='reset'><span>TOPページへ</span></button>
              </form>";


		}
	?>

	<div id="content">
        <!-- ナビゲーション -->
		<?php include (FJ_Header);?>
		

        <!-- メインコンテンツ -->
		<div id="mainwrap">
			<div id="main">

				<div class="section innerspace contentsbox sectionmainview">
					<div class="mainvisual">
						<h3 class="mainvisualfont">
							<span class="mainvisual mainvisualenfont">SIGN UP</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">新規会員登録</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">豊富な案件をご紹介いたします</span>
					</div>
				</div>

				<div class="innerspace newmember">



                    <h2 class="title-arrow <?= $color ?>"><?= $title ?></h2>

    				<div><?= $membercomp ?><?= $msg ?><?= $btn ?></div>



				</div>

			</div>
		</div>

        <!-- フッター -->
		<?php include (FJ_Footer);?>

	</div>

</body>
</html>