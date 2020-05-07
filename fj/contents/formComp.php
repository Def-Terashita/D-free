<?php
//---------------------------------------------------------
//  お問い合わせ入力確認送信  |  最終更新日:2018/09/19
//---------------------------------------------------------
//
// form.phpで入力されたお問い合わせ内容を表示
// 送信でユーザーと管理者へメール送信
// 再編集でform.phpへ戻る
// 送信完了後は完了メッセージを表示し、TOPへ戻るボタンを表示
// 送信失敗の場合失敗メッセージを表示、ログに記録
//
//---------------------------------------------------------

//セッション開始
session_start();
//:::::::::::::::::::::::::::::

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

//::::::::::::::::::::::::::::::

$errmsg     = array (); 		      // エラーメッセージ
$errlog     = array (); 		      // エラーログ
$ret 	    = FALSE;			      // 関数リターン値


//インクルード（define.php）
$ret = include_once("../inc/define.php");

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}


if (!empty($_SESSION["contact"]))
{
    $subject  = $_SESSION["contact"]["subject"];
    $username = $_SESSION["contact"]["username"];
    $tel      = $_SESSION["contact"]["tel"];
    $mail     = $_SESSION["contact"]["mail"];
    $content  = $_SESSION["contact"]["content"];
}
else
{
    header ("Location:" .Fj_Top);  //トップ画面へ遷移
    exit();     //処理終了
}

$title = "以下の内容でよろしければ送信を押してください";
$msg = "";
$button = "<form action='". $_SERVER["SCRIPT_NAME"]. "' method='post' onSubmit='return doublelClick(this)'>
            <button type='submit' class='reset-btn form-btn bg-darkblue color-white' name='submit'><span>この内容で送信</span></button>
     	</form>
            <div class='space'></div>
        <form action='". Fj_Form. "' method='post'>
            <button type='submit' class='reset-btn form-btn bg-bluegreen color-white' name='reset'><span>再編集</span></button>
     	</form>";




// ===== メール送信 =====
if ($_SERVER ["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
{
    //----- メール送信 -----------------------------------------------------------------

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
    $fromName_user        = Mail_FromName;
    $mailFrom_user        = Mail_FromAdress;
    $mailTo_user          = $mail;
    $subject_user         = Mail_Subj_user;
    $comment_user         = $username. "様";
    $comment_user        .= "\r\n";
    $comment_user        .= "この度はお問い合わせいただきありがとうございました。". "\r\n";
    $comment_user        .= "24時間以内に担当からご連絡を差し上げますのでしばらくお待ちくださいませ。（土日祝除く）";
    $comment_user        .= "\r\n";
    $comment_user        .= "\r\n";
    $comment_user        .= "■□■□■□■□■□■□■□■□■□■□■□\r\n";
    $comment_user        .= "株式会社Def tribe\r\n";
    $comment_user        .= "〒542-0081\r\n";
    $comment_user        .= "大阪市中央区南船場4丁目12番24号現代心斎橋ビル5階\r\n";
    $comment_user        .= "TEL：06-6643-9305\r\n";
    $comment_user        .= "Email：".Mail_FromAdress."\r\n";
    $comment_user        .= "■□■□■□■□■□■□■□■□■□■□■□\r\n";

    $returnMail_user = $mailTo_user;

    $result = sendmail($fromName_user, $mailFrom_user, $mailTo_user, $subject_user, $comment_user, $returnMail_user);
    if ($result) {
    } else {
        $errlog[] = '【問い合わせ】ユーザへの自動メール送信失敗';
    }

    // 管理者へ送信
    $fromName         = Mail_FromName;
    $mailFrom         = Mail_FromAdress;
    $mailTo           = Mail_FromAdress;
    $subject_admin    = Mail_Subj_Form;
    $comment          = "お名前：".$username . "様"."\r\n";
    $comment         .= "電話番号：".$tel . "\r\n";
    $comment         .= "メールアドレス：".$mail . "\r\n";
    $comment         .= "件名：".$subject . "\r\n";
    $comment         .= "問合せ内容：". "\r\n";
    $comment         .= $content;

    $returnMail = $mailTo;

    $result = sendmail($fromName, $mailFrom, $mailTo, $subject_admin, $comment, $returnMail);
    if ($result) {
    } else {
        $errlog[] = '【問い合わせ】管理者への連絡メール送信失敗';
    }


    $_SESSION["contact"]["subject"]  = "";
    $_SESSION["contact"]["username"] = "";
    $_SESSION["contact"]["tel"]      = "";
    $_SESSION["contact"]["mail"]     = "";
    $_SESSION["contact"]["content"]  = "";



    // エラーメッセージをログに記録
    if (!empty($errlog))
    {

        $title = "送信失敗！";
        $msg = "<div id='success'>
                    <b>メールの送信に失敗しました。
                    <br>申し訳ございませんが、お電話にてお問い合わせください。
                    <br>TEL 06-6643-9305</b>
                </div>
                <div class='space'></div>";



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
    else
   {
       $title = "送信完了！";
       $msg = "<div id='success'>
                    <b>お問い合わせありがとうございます。
                    <br>以下の内容で承りました。
                    <br>ご入力いただきましたメールアドレスに自動返信メールをお送りいたしましたのでご確認くださいませ。
                    <br>24時間以内に担当からご連絡いたします（土日祝除く）。</b>
                </div>
                <div class='space'></div>";
     }

    $button = "<form action='". Fj_Top. "' method='post'>
                    <button type='submit' class='reset-btn form-btn bg-bluegreen color-white' name='reset'><span>TOPページへ</span></button>
              </form>";



    $_SESSION["contact"] = array();

}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="システム開発,インフラ構築,人材派遣,IT">
    <meta name="description" content="">
    <title>お問い合わせ - D-free</title>
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
    			alert("ただいまメール送信中です。\nそのままお待ちください。");
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
			foreach ($errmsg as $val)
			{
				echo $val;
			}
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
							<span class="mainvisual mainvisualenfont">CONTACT</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">お問い合わせ</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">TEL:06-6643-9305</span>
					</div>
				</div>


				<div class="innerspace  form">


					<h2 class="title-arrow"><?= $title ?></h2>

					<div class="form-box">
						<div class="tablewrap form_tablewrap">
    						<?= $msg ?>
    						<table style="width:100%;" class="bg-white">
                    			<tr>
                    				<th class="form-subject">件名</th>
                    				<td><?= $subject ?></td>
                    			</tr>
    	            			<tr>
                    				<th>お名前</th>
                    				<td><?= $username ?></td>
                    			</tr>
                    			<tr>
                    				<th>電話番号</th>
                    				<td><?= $tel ?></td>
                    			</tr>
                    			<tr>
                    				<th>メールアドレス</th>
                    				<td><?= $mail ?></td>
                    			</tr>
                    			<tr>
                    				<th>内容</th>
                    				<td><?= preg_replace("/(\r\n|\n|\r)/", "<br>", $content) ?></td>
                    			</tr>
                    		</table>

                            <div class="space"></div>
                            <div><?= $button ?></div>
                        </div>
					</div>

				</div>
			</div>
		</div>


        <!-- フッター--->
		<?php include (FJ_Footer);?>
		
	</div>

</body>
</html>
