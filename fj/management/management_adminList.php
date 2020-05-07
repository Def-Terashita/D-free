
<?php
//---------------------------------------------------------
//  管理社情報一覧画面  |  最終更新日:2018/09/07
//---------------------------------------------------------
//
// 管理者IDを一覧表示
//
//「編集」押下で管理者ID情報編集ページへ。IDをPOSTで送る
//「新規登録」押下で管理者新規登録ページへ。
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


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!isset($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}
else
{
    $login_admin = $_SESSION["adminid"];
}



//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$errmsg = array (); 		      // エラーメッセージ
$db     = NULL;                  // DBオブジェクト

// 共通関数インクルード
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

$msg = "";                  // 管理者情報一覧

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


try{

    // ＤＢ接続
    $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

    // 文字コード
    $db->exec ("SET NAMES utf8");
    // カラム名（連想キー）：小文字設定
    $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合

    $sql = "SELECT admin_id FROM admin_tb;";

    $result = $db->query($sql);

    if ($result !== false)
    {
        // 行データ取得
        $row = $result->fetchall(PDO::FETCH_ASSOC);

        $msg = "<table border='1' class='tablesorter'>";
        $msg .= "<thead><tr>
                    <th>管理者ID</th>
                    <th style='width:30px;'></th>
                    <th style='width:30px;'></th>
         </tr></thead><tbody>";

        foreach ($row as $val)
        {
            $admin_id = $val["admin_id"];

            $msg .= "<tr>";
            $msg .= "<td>".$val["admin_id"]."</td>";
            $msg .= "<form action='";
            $msg .= Mg_AdminUpdate;
            $msg .= "' method='post'>";
            $msg .= "<td style='width:30px;'><button type='submit' name='edit' value='".$admin_id."' id='edit' class='list-btn'>編集</button></td>";
            $msg .= "</form>";

            if ($login_admin === $admin_id)
            {
                $msg .= "<td style='width:30px;'>ログイン中</td>";
            }
            else
          {
                $msg .= "<form action='";
                $msg .= Mg_AdminDeleat;
                $msg .= "' method='post'>";
                $msg .= "<td style='width:30px;'><button type='submit' name='del' value='".$admin_id."' id='del' class='list-btn'>削除</button></td>";
                $msg .= "</form>";
          };

            $msg .= "</tr>";
        }
        $msg .= "</tbody></table><br />";
    }
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
    <!-- tabelesoter styles for this template -->
    <link href="<?= Mg_Tablesoter_css ?>" rel="stylesheet">
    <link href="<?= Mg_Tablesoter_Pager_css ?>" rel="stylesheet">
    <!-- management core CSS -->
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
            <h1 class="h2">管理者一覧</h1>
              <div class="btn-group mr-2">
                  <form action="<?= Mg_Admin ?>" name='update' method="post">
                	<button class="btn btn-sm btn-outline-secondary">新規登録</button>
              	  </form>
              </div>
          </div>

          <!-- ここにコンテンツ -->

          <?= $msg ?>

        	<?php //設定情報エラー
        		if (!empty($errmsg))
        		{
        			foreach ($errmsg as $val)
        			{
        				echo $val;
        			}
        		}
        	?>

          <!-- ここにコンテンツ -->
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
    <script src="<?= Mg_Tablesoter_Latest_js ?>"></script>
    <script src="<?= Mg_Tablesorter_js ?>"></script>
    <script src="<?= Mg_Tablesorter_Metadata_js ?>"></script>
    <script src="<?= Mg_Tablesoter_Pager_js ?>"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

<!-- テーブルソーター -->
<script type="text/javascript">

$(function() {
  $("table")
   .tablesorter({widthFixed: true
				, widgets: ['zebra']
                , headers:{ 0:{sorter:false}
                           ,1:{sorter:false}
                           ,2:{sorter:false}
               			  }
				})
 });




</script>








