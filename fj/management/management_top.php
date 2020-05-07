<?php
//---------------------------------------------------------
//  管理画面TOPページ  |  最終更新日:2018/09/07
//---------------------------------------------------------
//
// 案件情報を一覧表示（初回：更新日降順、tablesorterの使用で20件表示）
// 表示項目：ID 案件名 勤務地 契約単価（MAX）契約期間
//           表示ユーザー(会員の場合赤色に) サイト反映（未反映の場合赤色に）掲載終了日
//
//「編集」押下で案件情報編集ページへ。案件のIDをPOSTで送る
//「新規登録」押下で新規登録ページへ。
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

date_default_timezone_set('Asia/Tokyo');
$today = date('Y-m-d');

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!isset($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$errmsg = array();			// エラーメッセージ
$msg    = "";               // 案件情報一覧

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

    $sql = "SELECT
                 project_id
                 , subject
                 , post_date
                 , post_end_date
                 , location
                 , price_upper
                 , period_lower
                 , period_upper
                 , member_flg
                 , reflect_flg
                 FROM project_tb
                 ORDER BY update_datetime DESC;";

    // SQL準備
    $result = $db->prepare($sql);

    // SQL実行
    if ($result->execute() !== false)
    {
        // 取得行データ数の確認
        if (0 < $result->rowCount())
        {

            $msg = "<table border='1' class='tablesorter'>";
            $msg .= "<thead><tr>
                    <th>ID</th>
                    <th>件名</th>
                    <th>勤務場所</th>
                    <th>MAX単価</th>
                    <th>契約期間</th>
                    <th>閲覧ユーザー</th>
                    <th>サイト反映</th>
                    <th>掲載終了日</th>
                    <th style='width:30px;'></th>
                    </tr></thead><tbody>";

            // 行データ取得
            while ($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                $project_id = $row["project_id"];

                //閲覧可能ユーザー振り分け
                if ($row["member_flg"] == 0){
                    $member_flg = "<td>全て</td>";
                }
                else{
                    //登録ユーザーのみ
                    $member_flg = "<td><span style='color:red;'>会員のみ</span></td>";
                }
                //サイト公開未公開状態振り分け
                if ($row["reflect_flg"] == 0)
                {
                    $reflect_flg = "<td>反映</td>";
                }
                else
              {
                    $reflect_flg = "<td><span style='color:red;'>未反映</span></td>";
                }

                $post_date =  $row["post_date"];
                if ($post_date > $today)
                {
                    $reflect_flg = "<td><span style='color:blue;'>反映待ち</span></td>";
                }

                $post_end_date = $row["post_end_date"];
                if ($post_end_date < $today)
                {
                    $reflect_flg = "<td><span style='color:red;'>終了</span></td>";
                }




                $msg .= "<tr>";
                $msg .= "<td>".$row["project_id"]."</td>";
                $msg .= "<td style='white-space: nowrap;overflow: hidden;text-overflow: ellipsis;max-width: 250px; width: 250px;'>".$row["subject"]."</td>";
                $msg .= "<td>".$row["location"]."</td>";
                $msg .= "<td>".$row["price_upper"]."</td>";
                $msg .= "<td>".$row["period_lower"]."～".$row["period_upper"]."</td>";
                $msg .= $member_flg;
                $msg .= $reflect_flg;
                $msg .= "<td>".$post_end_date."</td>";
                $msg .= "<form action='";
                $msg .= Mg_Project;
                $msg .= "' method='post'>";
                $msg .= "<td style='width:30px;'><button type='submit' name='edit' value='$project_id' id='editbtn' class='list-btn'>編集</button></td>";
                $msg .= "</form>";
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
        $errmsg[] = "案件情報テーブル取得エラー";
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

    <title>案件情報</title>

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
            <h1 class="h2">案件情報一覧</h1>
              <div class="btn-group mr-2">
                  <form action="<?= Mg_NewProject ?>" name='update' method="post">
                	<button class="btn btn-sm btn-outline-secondary">新規登録</button>
              	  </form>
              </div>
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
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="10">10</option>
                    </select>
                </form>

            </div>

            <?= $msg ?>


            <!-- ここにコンテンツ -->
        	<?php //設定情報エラー
        		if (!empty($errmsg))
        		{
        			foreach ($errmsg as $val)
        			{
        				echo $val;
        			}
        		}
        	?>
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
                , headers:{ 1:{sorter:false}
   							,2:{sorter:false}
   							,5:{sorter:false}
   							,6:{sorter:false}
                           	,8:{sorter:false}
               			  }
				})
   .tablesorterPager({container: $("#pager"),size: 20}); // size→初回20件表示
 });




</script>








