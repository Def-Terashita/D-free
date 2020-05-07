<?php
//---------------------------------------------------------
//  表側ログインページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
//
// ログインボタン押下入力チェック
// OKでセッションにユーザー名を格納しTOPへ遷移
// NGでエラーメッセージ表示
//
//---------------------------------------------------------

// セッション開始
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$input_email      = "";           // 入力メールアドレス
$input_pass       = "";           // 入力パスワード
$email_err        = "";           // メールアドレス入力エラー
$pass_err         = "";           // パスワード入力エラー
$errflg           = OK;           // エラーフラグ
$errmsg           = array();     // エラーメッセージ


$ret = include_once(FJ_Mg_Function);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//===== ポスト：リクエスト処理  =====
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $input_email = htmlspecialchars($_POST["email"], ENT_QUOTES );          // 入力メールアドレス
    $input_pass  = htmlspecialchars($_POST["pass"], ENT_QUOTES );           // 入力パスワード

    // ---入力チェック---
    if (inputCheck($input_email))
    {
        $errflg = NG;
        $email_err = MailError01;
    }
    if (inputCheck($input_pass))
    {
        $errflg = NG;
        $pass_err = PassError01;
    }

    if ($errflg == OK){

        try
        {
            //========== ここからDB ==========

            $ret = include_once(FJ_Mg_Pass); // password_hash用
            $ret = include_once(FJ_Config);  // config.php

            if ($ret === FALSE)
            {
                $errmsg[] = "※設定情報ファイル 読み込みエラー！";
            }
            else
          {
                require ("$config_path" . "$config_file");
            }

            // ＤＢ接続
            $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

            // 文字コード：UTF8
            $ret = $db->exec("SET NAMES utf8");

            // カラム名（連想キー）
            $ret = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);    //CASE_LOWER 小文字の場合

            // ＳＱＬ
            $sql = "SELECT * FROM user_tb WHERE email = ?";
            $result = $db->prepare($sql);


            if($result->execute(array($input_email)) === false)
            {
                $errmsg[] = "DB実行エラー";
            }
            else
          {
                $count = $result->rowCount();
                if ($count === 1)
                {
                    //登録IDあり。パスワード比較
                    if($ret = $result->fetch(PDO::FETCH_ASSOC)) //fetchメソッド executeメソッドの結果から1行ずつ返す
                    {                                           //PDO::FETCH_ASSOC→列名を添字とする配列で返す

                        if(password_verify($input_pass, $ret["password"]))  //password_verify()パスワードがハッシュにマッチするか調べる
                        {
                            $_SESSION["name"] = $ret["name"];

                            header ("Location:" .Fj_Top);  //トップ画面へ遷移
                            exit();     //処理終了
                        }
                        else
                    {
                            //認証失敗
                            $pass_err = PassError05;
                        }
                    }
                }
                else
              {
                  $pass_err = InputError;
                }
            }
        }
        catch(Exception $e)
        {
            //--- エラーメッセージ、エラーコード、エラーラインＮｏ. ---
            $get_errmsg = $e->getMessage();
            // エラーメッセージ編集：ＯＳ別設定
            // PHP_OS : 「AIX / Darwin / MacOS / Linux / SunOS / WIN32 / WINNT / Windows」
            if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
            {
                // 全角変換  SJIS ---> UTF-8
                $get_errmsg = mb_convert_encoding($get_errmsg,"UTF-8", "SJIS");
            }
            $dberrmsg  = "DB ERROR (catch)";
            $dberrmsg .=  "  MSG:" . $get_errmsg;

            // エラーコード
            $dberrmsg .= "  CODE:" . $e->getCode();

            // エラーラインＮｏ.
            $dberrmsg .= "  LINE:" . $e->getLine();

            // エラーメッセージ出力
            $errmsg[] = $dberrmsg;

            // ＤＢ接続開放
            $db = NULL;
        }

    }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="D-freeの会員様はこちらからログイン。">
    <title>ログイン - D-free</title>
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
							<span class="mainvisual mainvisualenfont">LOGIN</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">ログイン</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">会員専用ページ</span>
					</div>
				</div>

				<div class="innerspace contentsbox">




                    <div class="loginbox bg-darkblue color-white">
                    	<form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
                            <div class="space"></div>
                            <div><b>メールアドレス</b></div>
                            <input type="text" name="email" value="<?= $input_email ?>" class="loginbox-input"/>
                            <div class="input-errmsg color-yellow"><?= $email_err ?></div>
                            <div class="space"></div>
                            <div><b>パスワード</b></div>
                            <input type="password" name="pass" value="<?= $input_pass ?>" placeholder="半角英数8~16文字"  class="loginbox-input"/>
                            <div class="input-errmsg color-yellow"><?= $pass_err ?></div>
                            <div class="space40"></div>
                            <input type="submit" name="login" value="ログイン" class="btn loginbox-btn bg-yellow color-white"/>
    						<div class="space"></div>
                    	</form>
                    </div>
                    <div class="space"></div>
                    <div class="newmember-link"><a href="<?= Fj_NewMember ?>" class="color-red"><b>＞＞　新規会員登録はこちら（無料）＜＜</b></a></div>



				</div>

			</div>
		</div>


        <!-- フッター -->
		<?php include (FJ_Footer);?>
	</div>





</body>
</html>

