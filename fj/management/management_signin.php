
<?php
//---------------------------------------------------------
//  管理画面ログイン用画面＆プログラム  |  最終更新日:2018/08/27
//---------------------------------------------------------
//
// ID、パスワード入力チェック
// OK→TOP画面(management_top.php)へ遷移
// NG→エラー表示
//
//---------------------------------------------------------

// セッション開始
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$m_id 		= "";					            // 管理者用ID
$m_pass 	= "";					            // 管理者用パスワード
$ret 		= FALSE;					          // 関数リターン値
$errmsg		= array();				        // エラーメッセージ
$inputerr   = "";                   // 入力エラーメッセージ

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// --- 共通関数： インクルード ---
$ret = include_once("../inc/define.php");  // defineパス
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

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//===== ポスト：リクエスト処理  =====
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    try
   {
       //========== ここからDB ==========

       $admin_id = $_POST["inputID"];
           // ＤＢ接続
       $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

       // 文字コード：UTF8
       $ret = $db->exec("SET NAMES utf8");

       // カラム名（連想キー）：小文字設定
       $ret = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);    //CASE_LOWER 小文字の場合

       // ＳＱＬ
       $sql = "SELECT * FROM admin_tb WHERE admin_id = ?";
       $result = $db->prepare($sql);   //prepareメソッド

       if($result->execute(array($admin_id)) === false)
       {
           $errmsg[] = "DB実行エラー";
       }
       else
      {
           $count = $result->rowCount();

           if ($count === 1)
           {
               //登録IDあり。パスワード比較
               $user_pass = $_POST["inputPassword"];

               if($ret = $result->fetch(PDO::FETCH_ASSOC)) //fetchメソッド executeメソッドの結果から1行ずつ返す
               {                                           //PDO::FETCH_ASSOC→列名を添字とする配列で返す

                   //if(password_verify($user_pass, $ret["password"]))  //password_verify()パスワードがハッシュにマッチするか調べる
                   if($user_pass == $ret["password"])
                   {
                       $_SESSION["adminid"] = $admin_id;

                       header ("Location:" .Mg_Top);  //トップ画面へ遷移
                       exit();     //処理終了
                   }
                   else
                   {
                       //認証失敗
                       $inputerr = "パスワードが間違っています。";
                       echo "string";
                   }
               }

           }
           else
         {
             $inputerr = "IDかパスワードが間違っています";
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

//**********************************************************************************




?>



<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ログイン</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= Mg_bootstrap_css ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= Mg_signin_css ?>" rel="stylesheet">
  
  	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130283303-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-130283303-1');
	</script>

  </head>

  <body class="text-center">

    <form class="form-signin" action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputID" class="sr-only">ID</label>
      <input type="text" id="inputID" name="inputID" class="form-control" placeholder="id" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted"><?= $inputerr ?></p>
      <div id="err">
    	<?php //設定情報エラー
    		if (!empty($errmsg))
    		{
    			foreach ($errmsg as $val)
    			{
    				echo $val;
    			}
    		}
    	?>
      </div>

    </form>

  </body>
</html>