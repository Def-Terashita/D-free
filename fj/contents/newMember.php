<?php
//---------------------------------------------------------
//  表側新規会員登録ページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
//
// 入力項目：名前、電話番号、メールアドレス、確認用メールアドレス、確認用パスワード、パスワード
// 入力チェック後、OKで新規会員登録ページ(newMember.php)へ遷移。
// NGならエラーメッセージ表示。
//
//
//---------------------------------------------------------

// セッション開始
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード

//-------------------------------------------------------


$username   = "";     // 入力ユーザーの名前
$tel        = "";     // 電話番号
$mail       = "";     // メールアドレス
$checkmail  = "";     // 確認用メールアドレス
$pass       = "";     // パスワード
$checkpass  = "";     // 確認用パスワード
$userskill  = "";     // ユーザースキル
$userdate  	= "";     // 希望就業日
$userjob  	= "";     // 現在の職業

$username_error   = "";     // ユーザーネーム入力エラーOFF
$tel_error        = "";     // 電話番号入力エラーOFF
$mail_error       = "";     // メールアドレス入力エラーOFF
$checkmail_error  = "";     // 確認用メールアドレス入力エラーOFF
$skillerr         = "";     // スキル入力エラーOFF
$dateerr          = "";     // 希望就業日入力エラーOFF
$joberr           = "";     // 現在の職業入力エラーOFF			
$mail_errflg      = OK;     // メール、確認メール比較用フラグOFF
$pass_error       = "";     // パスワード入力エラーOFF
$checkpass_error  = "";     // 確認用パスワード入力エラーOFF
$pass_errflg      = OK;     // パスワード、確認パスワード比較用フラグOFF
$errflg           = OK;     // 入力エラーフラグOFF
$errmsg = array (); 	    // エラーメッセージ

$ret = include_once(FJ_Mg_Function);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}



//-------------------------------------------------------

// ===== ポスト：リクエスト処理 =====
if ($_SERVER ["REQUEST_METHOD"] == "POST" && isset($_POST["signup"]))
{
    $username   = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $tel        = htmlspecialchars($_POST["tel"], ENT_QUOTES);
    $mail       = htmlspecialchars($_POST["mail"], ENT_QUOTES);
    $checkmail  = htmlspecialchars($_POST["checkmail"], ENT_QUOTES);
    $pass       = htmlspecialchars($_POST["pass"], ENT_QUOTES);
    $checkpass  = htmlspecialchars($_POST["checkpass"], ENT_QUOTES);

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
            $mail_errflg = NG;
            $errflg = NG;
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


    if (inputCheck($pass))
    {
        $errflg = NG;
        $pass_errflg = NG;
        $pass_error = "<br>".PassError01;

    }
    else
    {//入力OK

        if (lengthCheck($pass))
        {
            $pass_error = "<br>".PassError02;
            $errflg = NG;
            $pass_errflg = NG;
        }
        else
       {// 文字数OK

            if (pregCheck($pass))
            {
                // 半角英数字NG
                $pass_error = "<br>".PassError02;
                $errflg = NG;
                $pass_errflg = NG;
            }
            else
          {
              //OK
              $pass = $pass;
            }
        }
    }

    if (inputCheck($checkpass))
    {
        $errflg = NG;
        $pass_errflg = NG;
        $checkpass_error = "<br>".PassError03;
    }

    if (!$pass_errflg)
    {
        if ($pass !== $checkpass)
        {
            $errflg = NG;
            $checkpass_error = "<br>".PassError04;
        }
    }

    //スキルカテゴリ
    if(isset($_POST["user_skill"]))
    {
        $userskill = $_POST["user_skill"];
    }
    else
    {
        $errflg = NG;
        $skillerr = SkillError;
    }

    //現在のご職業
    if(isset($_POST["user_job"]))
    {
        $userjob = $_POST["user_job"];
    }
    else
    {
        $errflg = NG;
        $joberr = JobError;
    }

    //希望就業時期
    if(isset($_POST["user_date"]))
    {
        $userdate = $_POST["user_date"];
    }
    else
    {
        $errflg = NG;
        $dateerr = DateError;
    }        



    // エラーがなければ確認画面へ遷移
    if (!$errflg)
    {

        $_SESSION["newuser"]["name"]       = $username;
        $_SESSION["newuser"]["tel"]        = $tel;
        $_SESSION["newuser"]["mail"]       = $mail;
        $_SESSION["newuser"]["pass"]       = $pass;
        $_SESSION["newuser"]["skill"]      = $userskill;
        $_SESSION["newuser"]["date"]       = $userdate;
        $_SESSION["newuser"]["job"]        = $userjob;

        header ("Location:" .Fj_MemberComp);
        exit();
    }
}

// 確認画面から戻ってきた場合
if (isset($_POST["return"]))
{
    $username  = $_SESSION["newuser"]["name"];
    $tel       = $_SESSION["newuser"]["tel"];
    $mail      = $_SESSION["newuser"]["mail"];
    $userskill = $_SESSION["newuser"]["skill"];
    $userdate  = $_SESSION["newuser"]["date"];
    $userjob   = $_SESSION["newuser"]["job"];

}




?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="会員登録は無料です！ご登録いただくことで、非公開求人のご紹介、関西に特化した豊富な案件の中から、よりあなたにマッチした案件をご紹介いただけます。">
    <title>無料会員登録 - フリーランスJOBS</title>
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
							<span class="mainvisual mainvisualenfont">SIGN UP</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">新規会員登録</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">豊富な案件をご紹介いたします</span>
					</div>
				</div>
			<div class="innerspace newmember">

					<div class="form-box">

                    	<form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post" class='tablewrap newmember_tablewrap'>

                            <div class="newmember_info">
                                ご登録で非公開案件の閲覧だけでなくサイトには掲載していない案件のご紹介が可能です。
                                <br>案件のご紹介は無料ですのでぜひお気軽にご活用くださいませ。ご登録後24時間以内にご連絡させていただきます。（土日祝除く）
                            </div>
                    		<table  class="bg-white">
		            			<tr>
                    				<th>お名前</th>
                    				<td>
                    					<input type="text" name="username" value="<?= $username ?>" size="30" placeholder="必須"  />
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
                    					<input type="text" name="mail" value="<?= $mail ?>" size="30" placeholder=" 例）info@freelance-jobs.jp"  />
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
                    				<th>パスワード</th>
                    				<td>
                    					<input type="password" name="pass" value="<?= $pass ?>" size="30" placeholder="半角英数8~16文字"  />
                    					<span class="input-errmsg color-red"><?= $pass_error ?></span>
                    				</td>
                    			</tr>
                    			<tr>
                    				<th>確認用パスワード</th>
                    				<td>
                    					<input type="password" name="checkpass" value="<?= $checkpass ?>" size="30" placeholder="半角英数8~16文字"  />
                    					<span class="input-errmsg color-red"><?= $checkpass_error ?></span>
                    				</td>
                    			</tr>
                                <tr>
                                    <th>希望スキル</th>
                                    <td>
                                        <span class="newmember_info">※複数選択可</span>
                                        <label>
                                            <input type="checkbox" name="user_skill[]" value="OPEN系" 
                                            <?php
                                                if ($userskill == ""){}
                                                else{foreach ($userskill as $row){if($row == "OPEN系"){echo 'checked="checked"';}}}
                                            ?> />
                                            OPEN系
                                        </label>                                        
                                        <label>
                                            <input type="checkbox" name="user_skill[]" value="汎用系"
                                            <?php
                                                if ($userskill == ""){}
                                                else{foreach ($userskill as $row){if($row == "汎用系"){echo 'checked="checked"';}}}
                                            ?>/>
                                            汎用系
                                        </label>
                                        <label>
                                            <input type="checkbox" name="user_skill[]" value="制御・組み込み系"
                                            <?php
                                                if ($userskill == ""){}
                                                else{foreach ($userskill as $row){if($row == "制御・組み込み系"){echo 'checked="checked"';}}}
                                            ?>/>
                                            制御・組み込み系
                                        </label>
                                        <label>
                                            <input type="checkbox" name="user_skill[]" value="インフラ系"
                                            <?php
                                                if ($userskill == ""){}
                                                else{foreach ($userskill as $row){if($row == "インフラ系"){echo 'checked="checked"';}}}
                                            ?>/>
                                            インフラ系
                                        </label>
                                        <label>
                                            <input type="checkbox" name="user_skill[]" value="その他"
                                            <?php
                                                if ($userskill == ""){}
                                                else{foreach ($userskill as $row){if($row == "その他"){echo 'checked="checked"';}}}
                                            ?>/>
                                            その他
                                        </label>
                                        <span class="input-errmsg color-red"><?= $skillerr ?></span>                                               
                                    </td>
                                </tr>
                                <tr>
                                    <th>現在のご職業</th>
                                    <td>
                                        <label>
                                            <input type="radio" name="user_job" value="フリーランス" 
                                            <?php
                                                if ($userjob == ""){}
                                                else{if($userjob == "フリーランス"){echo 'checked="checked"';}}
                                            ?> />
                                            フリーランス
                                        </label>                                        
                                        <label>
                                            <input type="radio" name="user_job" value="会社員"
                                            <?php
                                                if ($userjob == ""){}
                                                else{if($userjob == "会社員"){echo 'checked="checked"';}}
                                            ?>/>
                                            会社員
                                        </label>
                                        <label>
                                            <input type="radio" name="user_job" value="その他"
                                            <?php
                                                if ($userjob == ""){}
                                                else{if($userjob == "その他"){echo 'checked="checked"';}}
                                            ?>/>
                                            その他
                                        </label>
                                        <span class="input-errmsg color-red"><?= $joberr ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>希望就業時期</th>
                                    <td>
                                        <label>
                                            <input type="radio" name="user_date" value="即日" 
                                            <?php
                                                if ($userdate == ""){}
                                                else{if($userdate == "即日"){echo 'checked="checked"';}}
                                            ?> />
                                            即日
                                        </label>                                        
                                        <label>
                                            <input type="radio" name="user_date" value="１ヶ月以内"
                                            <?php
                                                if ($userdate == ""){}
                                                else{if($userdate == "１ヶ月以内"){echo 'checked="checked"';}}
                                            ?>/>
                                            １ヶ月以内
                                        </label>
                                        <label>
                                            <input type="radio" name="user_date" value="２ヶ月以内"
                                            <?php
                                                if ($userdate == ""){}
                                                else{if($userdate == "２ヶ月以内"){echo 'checked="checked"';}}
                                            ?>/>
                                            ２ヶ月以内
                                        </label>
                                        <label>
                                            <input type="radio" name="user_date" value="３ヶ月以上"
                                            <?php
                                                if ($userdate == ""){}
                                                else{if($userdate == "３ヶ月以上"){echo 'checked="checked"';}}
                                            ?>/>
                                            ３ヶ月以上
                                        </label>
                                        <label>
                                            <input type="radio" name="user_date" value="検討中のため未確定"
                                            <?php
                                                if ($userdate == ""){}
                                                else{if($userdate == "検討中のため未確定"){echo 'checked="checked"';}}
                                            ?>/>
                                            検討中のため未確定
                                        </label>
                                        <span class="input-errmsg color-red"><?= $dateerr ?></span>
                                    </td>
                                </tr>

                    		</table>

                    		<div class="space"></div>

                    		<button type="submit" class="reset-btn form-btn bg-darkblue" name="signup"><span class="color-white">入力内容を確認する</span></button>

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

