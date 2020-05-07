<?php

//-------------------------------------------------------------------
//  ユーザー情報確認・更新完了ページ  |  最終更新日:2018/09/07
//-------------------------------------------------------------------
//
// 編集内容表示
// 更新ボタン押下でアップデート
// 戻るボタン押下で編集画面(management_user.php)へ遷移
// ※アップデート完了後は戻るボタンはユーザー一覧(management_userList.php)へ遷移
//
//
//---------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);
$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード


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


date_default_timezone_set('Asia/Tokyo');

$update_id	= $_SESSION["adminid"];

$user_id    = $_SESSION["userupadate"]["user_id"];
$name       = $_SESSION["userupadate"]["name"];
$tel        = $_SESSION["userupadate"]["tel"];
$email      = $_SESSION["userupadate"]["email"];

$msg = "<p>以下の内容でよろしければ登録ボタンを押してください</p>";     // メッセージ

// 更新ボタン表示
$updatebtn = "<form action='". $_SERVER["SCRIPT_NAME"]. "' method='post'>
                <button type='submit' name='update' id='btn' class='btn btn-sm btn-warning' >登録</button>
              </form><br>";
// 戻るボタンユーザー編集ページへ
$returnbtn = "<form action='". Mg_User. "' method='post' name='update' >
                <button type='submit' name='return' id='btn' class='btn btn-sm btn-info'>再編集</button>
              </form><br>";


//===========================================================================================================


//更新ボタン押した後の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])){


    // ユーザー更新SQL
    try
    {
        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user , $database_password);
        // 文字コード：UTF8
        $db->exec("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        // ＳＱＬ

        $sql = "UPDATE user_tb SET
                            	  email = ?
                            	, name = ?
                            	, tel = ?
								, update_id = ?
                            	, update_datetime = now()
                            	WHERE user_id = $user_id;";

        $result = $db->prepare($sql);
        // ＳＱＬ実行
        if($result->execute(array
            ( $email
                ,$name
                ,$tel
                ,$update_id
            )) === false)
            {
                $errmsg[] = "ユーザー情報テーブル書き込みエラー";
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
            $errmsg[] = "MSG:" . $e->getMessage();
        }
        // エラーコード
        $errmsg[] = "CODE:" . $e->getCode();
        // エラーラインＮｏ.
        $errmsg[] = "LINE:" . $e->getLine();
        // ＤＢ接続開放
        $db = NULL;
    }

    if (!empty($errmsg))
    {
        $msg = "<p id='err'>エラー！</p>";
    }
    else
   {
       $msg = "<p id='success'>編集が完了しました！</p>";
    }


    $updatebtn = "";            // 更新ボタン表示しない
    $returnbtn = "<form action='". Mg_UserList. "' method='post' name='update' >
                 <button type='submit' name='return' id='btn' class='btn btn-sm btn-info'>ユーザー情報一覧へ戻る</button>
                 </form><br>";

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
	<p>ID : <span class="input_items"><?= $user_id ?></span></p>
	<p>名前：<span class="input_items"><?= $name ?></span></p>
	<p>メールアドレス：<span class="input_items"><?= $email ?></span></p>
	<p>電話番号：<span class="input_items"><?= $tel ?></span></p>

	<br>
	<div><?= $updatebtn ?></div>
	<div><?= $returnbtn ?></div>

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







