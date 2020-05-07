<?php

//---------------------------------------------------------
//  お問い合わせ入力ページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
//
// お問い合わせ内容の入力ができるページ
// 入力：名前、メールアドレス、電話番号、内容
// エラーがなければお問い合わせ入力完了ページ(formComp.php)へ遷移
// あればエラーメッセージ表示
//
//---------------------------------------------------------

//セッション開始
session_start();
//:::::::::::::::::::::::::::::

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

//::::::::::::::::::::::::::::::


$errmsg = array (); 		      // エラーメッセージ
$ret 	= FALSE;			     // 関数リターン値

//インクルード（define.php）
$ret = include_once("../inc/define.php");
$ret = include_once(FJ_Mg_Function);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}

$username           = "";
$username_error     = "";
$tel                = "";
$tel_error          = "";
$mail               = "";
$mail_error         = "";
$checkmail          = "";
$checkmail_error    = "";
$content            = "";
$content_error      = "";
$subject            = "";

$errflg             = OK;
$mail_errflg        = OK;

if (isset($_POST["form_project_id"]))
{
    $project_id = $_POST["form_project_id"];
    $subject = $_POST["form_project_subject"];

    $content = "{$subject}(案件番号：{$project_id})についてのお問い合わせです。";

}
// 確認画面からやり直しボタンで戻ってきた場合
if (isset($_POST["reset"]))
{
    $subject = $_SESSION["contact"]["subject"];
    $username = $_SESSION["contact"]["username"];
    $tel      = $_SESSION["contact"]["tel"];
    $mail     = $_SESSION["contact"]["mail"];
    $content  = $_SESSION["contact"]["content"];
}




// ===== ポスト：リクエスト処理 =====
if ($_SERVER ["REQUEST_METHOD"] == "POST" && isset($_POST["check"]))
{
    $username   = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $tel        = htmlspecialchars($_POST["tel"], ENT_QUOTES);
    $mail       = htmlspecialchars($_POST["mail"], ENT_QUOTES);
    $checkmail  = htmlspecialchars($_POST["checkmail"], ENT_QUOTES);
    $content    = htmlspecialchars($_POST["content"], ENT_QUOTES);
    $subject    = $_POST["subject"];


    // 名前入力チェック
    if (inputCheck($username))
    {
        $username_error = "<br>".NameError;
        $errflg = NG;
    }

    // ---電話番号入力チェック---
    if (inputCheck($tel))
    {
        $tel_error = "<br>".TelError01;
        $errflg = NG;
    }
    else
    {
        if (pregCheck_tel($tel))
        {
            $tel_error = "<br>".TelError02;
            $errflg = NG;
        }
        else
        {
            $tel = $tel;
        }
    }

    // ---メールアドレスチェック---
    if (inputCheck($mail))
    {
        $errflg = NG;
        $mail_errflg = NG;
        $mail_error = "<br>".MailError01;
    }
    else
    {
        if (pregCheck_mail($mail))
        {
            $mail_errflg = 1;
            $errflg = 1;
            $mail_error = "<br>".MailError02;
        }
        else
       {
           $mail = $mail;
        }

    }

    // 確認用メールアドレスチェック
    if (inputCheck($checkmail))
    {
        $errflg = NG;
        $mail_errflg = NG;
        $checkmail_error = "<br>".MailError03;
    }

    if (!$mail_errflg)
    {
        if ($mail !== $checkmail)
        {
            $errflg = NG;
            $checkmail_error = "<br>".MailError04;
        }
    }

    //問い合わせ内容チェック
    if (inputCheck($content))
    {

        $errflg = NG;
        $content_error = "<br>".ContentError;

    }

    //==エラーがなければSESSIONに詰めて確認送信画面へおくる================================================
    if (!$errflg)
    {
        $_SESSION["contact"]["subject"]  = $subject;
        $_SESSION["contact"]["username"] = $username;
        $_SESSION["contact"]["tel"]      = $tel;
        $_SESSION["contact"]["mail"]     = $mail;
        $_SESSION["contact"]["content"]  = $content;

        header ("Location:" .Fj_FormComp);
        exit();
    }

}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="D-freeのお問い合わせページです。案件について、フリーランスについて、何でもお問い合わせください">
    <title>お問い合わせ - D-free</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Contents_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>

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



				<div class="innerspace form">



					<div class="form-box">
                    	<form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post" class='tablewrap form_tablewrap'>

                    		<table  class="bg-white">
                    			<tr>
                    				<th class="form-subject">件名</th>
                    				<td>
                    					<select name="subject" id="form-subject">
                            				<option value="案件について" <?php if($subject == "案件について"){echo 'selected';}?>>案件について</option>
                                        	<option value="フリーランスJOBSについて" <?php if($subject == "D-freeについて"){echo 'selected';}?>>D-freeについて</option>
                                        	<option value="その他" <?php if($subject == "その他"){echo 'selected';}?>>その他</option>
                                        </select>
                    				</td>
                    			</tr>
		            			<tr>
                    				<th>お名前</th>
                    				<td>
                    					<input type="text" name="username" value="<?= $username ?>" size="30"  />
                    					<span class="input-errmsg color-red"><?= $username_error ?></span>
                    				</td>
                    			</tr>

                    			<tr>
                    				<th>電話番号</th>
                    				<td>
                    					<input type="text" name="tel" value="<?= $tel ?>" size="30" placeholder=" 例）00-0000-0000"  />
                    					<span class="input-errmsg color-red"><?= $tel_error ?></span>
                    				</td>
                    			</tr>

                    			<tr>
                    				<th>メールアドレス</th>
                    				<td>
                    					<input type="text" name="mail" value="<?= $mail ?>" size="30" placeholder=" 例）info@f-d-free.net"  />
                    					<span class="input-errmsg color-red"><?= $mail_error ?></span>
                    				</td>
                    			</tr>

                    			<tr>
                    				<th>確認用メールアドレス</th>
                    				<td>
                    					<input type="text" name="checkmail" value="<?= $checkmail ?>" size="30" />
                    					<span class="input-errmsg color-red"><?= $checkmail_error ?></span>
                    				</td>
                    			</tr>

                    			<tr>
                    				<th>内容</th>
                    				<td>
                    					<textarea name="content" id="form-content"><?= $content ?></textarea>
                    					<span class="input-errmsg color-red"><?= $content_error ?></span>
                    				</td>
                    			</tr>

                    		</table>
                    		<div class="space"></div>

                    		<button type="submit" class="reset-btn form-btn bg-darkblue" name="check"><span class="color-white">入力内容を確認する</span></button>

                     	</form>
					</div>


				</div>
			</div>
		</div>


        <!-- フッター -->
		<?php include (FJ_Footer);?>

	</div>

</body>
</html>






