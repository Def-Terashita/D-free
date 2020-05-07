<?php

//---------------------------------------------------------
// 管理者情報削除ページ  |  最終更新日:2018/09/07
//---------------------------------------------------------
//
// 管理者ID表示
// 削除ボタン押下でデリート処理
// 戻るボタン押下で管理者一覧(management_adminList.php)へ遷移
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

$msg = "<p>削除してよろしければ削除ボタンをクリックしてください</p>";     // メッセージ

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::



//===========================================================================================================

//管理者リストページから遷移直後
if (isset($_POST["del"])) {


    $del_adminid = $_POST["del"];
    // 削除ボタン表示
    $deletebtn = "<form action='". $_SERVER["SCRIPT_NAME"]. "' method='post'>
                <button type='submit' name='delbtn' id='delbtn' value='". $del_adminid."' class='btn btn-sm btn-warning'>削除</button>
              </form><br>";

}

//-削除-----------------------------------------------------------------------------

//削除ボタン押した後の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["del"])){

    $del_adminid = $_POST["delbtn"];

    try
    {
        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user , $database_password);


        // 文字コード：UTF8
        $db->exec("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        // ＳＱＬ
        $sql = "DELETE FROM admin_tb WHERE admin_id = '".$del_adminid."';";

        // ＳＱＬ実行
        $result = $db->query($sql);

        if($result === FALSE)
        {
            // ＳＱＬ実行エラー情報
            $errmsg[] = "管理者削除エラー ";
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
        $msg = "<p id='success'>削除しました！</p>";
        $del_adminid = "";
    }


    $deletebtn = "";
 }

//------------------------------------------------------------------------------



?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>管理者情報</title>

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
            <h1 class="h2">管理者情報削除画面</h1>
          </div>


<!-- ここにコンテンツ -->
	<?= $msg ?>
	<hr>
	<p>管理者ID : <span class="input_items"><?= $del_adminid ?></span></p>
	<br>
	<div><?= $deletebtn ?></div>
	<div>
        <form action="<?= Mg_AdminLIST ?>" name='update' method="post">
        	<button type='submit' name='return' value='' id='btn' class='btn btn-sm btn-info'>管理者一覧へ戻る</button>
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





