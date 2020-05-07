<?php

//---------------------------------------------------------
//  個別ユーザー情報ページ  |  最終更新日:2018/08/31
//---------------------------------------------------------
//
// ユーザーリスト画面（management_userList.php）
// またはユーザー情報確認・更新完了ページ(management_userEdit.php)から遷移
//
// ユーザーリスト画面からの場合ポストIDを使用しユーザー情報を表示
// ユーザー情報確認・更新完了ページから戻ってきた場合はSESSIONでユーザー情報を表示
// 確認ボタン押下で入力内容をチェック後エラーがなければユーザー情報確認・更新完了ページへ遷移
//
//---------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');



//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

$ret 	= FALSE;		                	// 関数リターン値

// defineパス: インクルード
$ret = include_once("../inc/define.php");
$ret = include_once(FJ_Mg_Function);

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!isset($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$errmsg = array (); 		                 // エラーメッセージ
$db     = NULL;                             // DBオブジェクト

//=== 共通関数：インクルード ===
$ret = include_once(FJ_Config);  // config.php

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    require ("$config_path" . "$config_file");
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::



$msg = "<p>ユーザー情報を変更される場合は以下に記入後、確認ボタンを押してください</p>";     // メッセージ

$errflg             = OK;	// 入力エラーフラグOFF
$name_error			= ""; 	// 名前入力エラーOFF
$email_error        = ""; 	// メール入力エラーOFF
$tel_error          = ""; 	// 電話番号エラーOFF

//===========================================================================================================
//ユーザーリストページから遷移

if (isset($_POST["edit"])) {

    $user_id = $_POST["edit"];
    try
    {
        // DB接続
        $db = new PDO($database_dsn, $database_user, $database_password);
        // 文字コード：UTF8
        $db->exec("SET NAMES utf8");
        // カラム名(連想キー)：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        // SQL
        $sql = "SELECT user_id, email, name, tel FROM user_tb WHERE user_id = ".$user_id.";";
        // SQL準備
        $data = $db->prepare($sql);
        // SQL実行
        if ($data->execute() !== false)
        {
            //取得してきたデータを変数に代入
            foreach ($data as $row)
            {
                $usert_id   = $row["user_id"];
                $name       = $row["name"];
                $tel        = $row["tel"];
                $email      = $row["email"];
            }
        }
        else
        {
            // SQL実行エラー情報
            $errmsg[] = "ユーザー情報取得エラー ";
        }
        // DB接続開放
        $db = null;

    }
    //===== 例外処理 =====
    catch (Exception $e)
    {
        //--- エラーメッセージ、エラーコード、エラーラインNo. ---
        // エラーメッセージ：OS別設定
        if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
        {
            // 全角変換 SJIS---> UTF-8
            $errmsg[] = "MSG:".mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS");
        }
        else
        {
            $errmsg[] = "MSG:".$e->getMessage();
        }
        // エラーコード
        $errmsg[] = "CODE:".$e->getCode();
        // エラーラインNo.
        $errmsg[] = "LINE:".$e->getLine();
        // DB接続開放
        $db = null;
    }
}

//===========================================================================================================

// 確認ページから戻ってきた場合
if (isset($_POST["return"])){
    $user_id    = $_SESSION["userupadate"]["user_id"];
    $name       = $_SESSION["userupadate"]["name"];
    $tel        = $_SESSION["userupadate"]["tel"];
    $email      = $_SESSION["userupadate"]["email"];
}

//===========================================================================================================


//確認ボタン押した後の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["edit"]) && !isset($_POST["return"])){

    // フォーム内容取り出し
    $user_id    = $_POST["user_id"];
    $name       = htmlspecialchars ($_POST["name"], ENT_QUOTES );
    $tel        = htmlspecialchars ($_POST["tel"], ENT_QUOTES );
    $email      = htmlspecialchars ($_POST["email"], ENT_QUOTES );
    $tel		= htmlspecialchars ($_POST["tel"], ENT_QUOTES );


    // 名前入力チェック
    if (inputCheck($name))
    {
        $name_error = "<br>".NameError;
        $errflg = NG;
    }



    // ---メールアドレスチェック---
    if (inputCheck($email))
    {
        $errflg = NG;
        $mail_errflg = NG;
        $email_error = "<br>".MailError01;
    }
    else
    {
        if (pregCheck_mail($email))
        {
            $mail_errflg = NG;
            $errflg = NG;
            $email_error = "<br>".MailError02;
        }
        else
        {
            $email = $email;
        }

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



    // エラーがなければ確認画面へ遷移
    if (!$errflg)
    {
        $_SESSION["userupadate"]["user_id"]    = $user_id;
        $_SESSION["userupadate"]["name"]       = $name;
        $_SESSION["userupadate"]["tel"]        = $tel;
        $_SESSION["userupadate"]["email"]      = $email;

        header ("Location:" .Mg_UserUpdate);
        exit();

    }

}

?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>ユーザー情報</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= Mg_bootstrap_css ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= Mg_dashboard_css ?>" rel="stylesheet">

    <!-- management=common CSS -->
    <link href="<?= Mg_Common_css ?>" rel="stylesheet">
  	
  	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130283303-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-130283303-1');
	</script>

  </head>

  <body>
 		<!-- -------------- ヘッダー -------------- -->
		<?php include (Mg_Header);?>
		<!-- ------------------------------------------ -->

    <div class="container-fluid">
      <div class="row">

 		<!-- -------------- サイドバー -------------- -->
		<?php include (Mg_Sidebar);?>
		<!-- ------------------------------------------ -->

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">ユーザー情報変更画面</h1>
          </div>

<!-- ここにコンテンツ -->

	<?= $msg ?>
	<hr>
	<div id="user_info">
        <form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
        	<label>
        		<span class="label-text">ID</span><br />
        		<input type="text"  value="<?= $user_id ?>" disabled>
        		<input type="hidden" name="user_id" value="<?= $user_id ?>" >
        	</label>
        	<br />
        	    	<label>
        		<span class="label-text">名前</span><br />
        		<input type="text" name="name" value="<?= $name ?>" >
        		<span id="name_error" class="error_m"><?= $name_error ?></span>
        	</label>
        	<br />
        	<label>
        		<span class="label-text">メールアドレス</span><br />
        		<input type="text" name="email" value="<?= $email ?>" >
        		<span id="email_error" class="error_m"><?= $email_error ?></span>
        	</label>
        	<br />
        	<label>
        		<span class="label-text">電話番号</span><br />
        		<input type="text" name="tel" value="<?= $tel ?>" >
        		<span id="tel_error" class="error_m"><?= $tel_error ?></span>
        	</label>
        	<br /><br />
        	<div>
        		<button type='submit' name='update' value='' id='btn' class='btn btn-sm btn-warning'>入力内容を確認する</button>
        	</div>
        </form>
        <br />
        <form action="<?= Mg_UserList ?>" method="post">
        	<button type='submit' name='return' value='' id='btn' class='btn btn-sm btn-info'>ユーザー情報一覧へ戻る</button>
        </form>
	</div>
	<?php //設定情報エラー
		if (!empty($errmsg))
		{
			foreach ($errmsg as $val)
			{
				echo $val;
			}
		}
	?>

<!-- ここまでコンテンツ -->
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?= Mg_slim_js ?>"><\/script>')</script>
    <script src="<?= Mg_popper_js ?>"></script>
    <script src="<?= Mg_bootstrap_js ?>"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>


  </body>
</html>







