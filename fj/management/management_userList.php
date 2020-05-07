<?php

//---------------------------------------------------------
// ユーザー情報一覧ページ |  最終更新日:2018/09/07
//---------------------------------------------------------
//
// 表示：ID、名前、メール、電話
//
// 編集押下で個別編集画面（management_user.php）へ
// 削除押下で個別削除画面（management_userDelete.php）へ遷移。
// POSTでIDを送る
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


$msg    = "";               // ユーザー情報一覧
$errmsg = array();			// DBエラーメッセージ

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// --- 共通関数： インクルード ---
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


try
{
    // DB接続
    $db = new PDO($database_dsn, $database_user, $database_password);
    // 文字コード：UTF8
    $db->exec("SET NAMES utf8");
    // カラム名(連想キー)：小文字設定
    $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    // SQL
    $sql = "SELECT user_id, email, name, tel FROM user_tb ORDER BY user_id DESC;";
    // SQL準備
    $result = $db->prepare($sql);

    // SQL実行
    if ($result->execute() !== false)
    {
        // 取得行データ数の確認
        if (0 < $result->rowCount())
        {
            $msg = "<table border='1' id='userlistTable' class='tablesorter'>";
            $msg .= "<thead><tr><th>ユーザーID</th><th>氏名</th><th>Email</th><th>TEL</th><th style='width:30px;'></th><th style='width:30px;'></th></tr></thead><tbody>";

            // 行データ取得
            while ($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                $user_id = $row["user_id"];
                $msg .= "<tr><td>".$user_id."</td>";
                $msg .= "<td>".$row["name"]."</td>";
                $msg .= "<td>".$row["email"]."</td>";
                $msg .= "<td>".$row["tel"]."</td>";
                $msg .= "<form action='";
                $msg .= Mg_User;
                $msg .= "' method='post'>";
                $msg .= "<td style='width:30px;'><button type='submit' name='edit' value='$user_id' id='editbtn'  class='list-btn'>編集</button></td>";
                $msg .= "</form>";
                $msg .= "<form action='";
                $msg .= Mg_UserDeleat;
                $msg .= "' method='post'>";
                $msg .= "<td style='width:30px;'><button type='submit' name='del' value='$user_id' id='delbtn'  class='list-btn'>削除</button></td></form>";
                $msg .= "</tr>";
            }

            $msg .= "</tbody></table><br />";
        }

        else
       {
            $msg .= "該当データなし<br />";
        }
    }
    else
   {
        // SQL実行エラー情報
       $errmsg[] = "ユーザー情報テーブル取得エラー";
    }
    // DB接続開放
    $db = null;

//===== 例外処理 =====
}
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
		<?php include(Mg_Header);?>
		<!-- ------------------------------------------ -->

    <div class="container-fluid">
      <div class="row">

 		<!-- -------------- サイドバー -------------- -->
		<?php include(Mg_Sidebar);?>
		<!-- ------------------------------------------ -->

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">ユーザー情報一覧</h1>
          </div>

            <!-- ここにコンテンツ -->

            <div id="pager" class="pager">

            <form>
                <button type='button' class='first'>&lt;&lt;</button>
                <button type='button' class='prev'>&lt;</button>

                <span class="pagedisplay"></span>
                <input type="text" class="pagedisplay"/>

                <button type='button' class='next'>&gt;</button>
                <button type='button' class='last'>&gt;&gt;</button>

                <select class="pagesize">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </form>

            </div>

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


<script type="text/javascript">
$(function() {
	  $("table")
	   .tablesorter({widthFixed: true
					, widgets: ['zebra']
	                , headers:{ 1:{sorter:false}
	   						   ,2:{sorter:false}
	                           ,3:{sorter:false}
	                           ,4:{sorter:false}
	                           ,5:{sorter:false}
	               			  }
					})
	   .tablesorterPager({container: $("#pager")});
	 });
</script>





