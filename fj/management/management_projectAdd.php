<?php
//---------------------------------------------------------
//  新規案件追加ページ  |  最終更新日:2018/09/11
//---------------------------------------------------------
//
//  入力内容の確認と登録ができるページ。
// 「再編集」押下で入力内容を案件新規登録画面（management_newproject.php）へ渡す
// 「登録」押下でDBにアクセスしインサート。
//  完了後は完了メッセージを表示。
//  確認時に表示していた登録ボタンは消し、「続けて登録する」、「TOPへ戻る」ボタンを表示
//
//
//---------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード
date_default_timezone_set('Asia/Tokyo');

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!isset($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}
else
{
    $adminid = $_SESSION["adminid"];
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$errmsg = array (); 		      // エラーメッセージ
$db     = NULL;                  // DBオブジェクト

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

$msg = "<p>以下の内容でよろしければ、登録ボタンを押してください</p>";

$insertbtn = "<form action='". $_SERVER["SCRIPT_NAME"]. "' method='post'>
            <button type='submit' name='insertbtn' id='insertbtn' value='insertbtn' class='btn btn-block btn-warning'>登録</button>
            </form>";

$returnbtn = "<form action='". Mg_NewProject. "' method='post'>
            <button type='submit' name='return' id='return' value='' class='btn btn-block btn-info'>再編集</button>
            </form>";

$subject        = $_SESSION["newproject"]["subject"];
$post_date      = $_SESSION["newproject"]["post_date"];
$post_end_date  = $_SESSION["newproject"]["post_end_date"];
$price_lower    = $_SESSION["newproject"]["price_lower"];
$price_upper    = $_SESSION["newproject"]["price_upper"];
$period_lower   = $_SESSION["newproject"]["period_lower"];
$period_upper   = $_SESSION["newproject"]["period_upper"];
$phase_no       = $_SESSION["newproject"]["phase_no"];
$area_no        = $_SESSION["newproject"]["area_no"];
$location       = $_SESSION["newproject"]["location"];
$content        = $_SESSION["newproject"]["content"];
$screening      = $_SESSION["newproject"]["screening"];
$member_flg     = $_SESSION["newproject"]["memberflg"];
$reflect_flg    = $_SESSION["newproject"]["reflect_flg"];
$keyword        = $_SESSION["newproject"]["keyword"];
$keywordstr     = $_SESSION["newproject"]["keywordstr"];
$areastr        = $_SESSION["newproject"]["areastr"];
$phasestr       = $_SESSION["newproject"]["phasestr"];
$memberstr      = $_SESSION["newproject"]["member_flg"];
$reflectstr     = $_SESSION["newproject"]["reflectstr"];



if ($_SERVER["REQUEST_METHOD"] == "POST"){

    try
    {
        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

        // 文字コード
        $ret = $db->exec ("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $ret = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合

        // --トランザクション開始----------------------------------------------------------
        $db->beginTransaction();

        // 案件情報テーブルに書き出し
        $sql = "INSERT INTO project_tb (subject
                                      , post_date
                                      , post_end_date
                                      , area_no
                                      , location
                                      , price_lower
                                      , price_upper
                                      , period_lower
                                      , period_upper
                                      , content
                                      , screening
                                      , member_flg
                                      , reflect_flg
                                      , create_id
                                      , create_datetime
                                      , update_id
                                      , update_datetime
                                    )
                                      VALUES (?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , ?
                                            , now()
                                            , ?
                                            , now());";

        $result = $db->prepare($sql);    //prepareメソッド

        if ($ret = $result->execute(array(   //executeでprepareメソッドを実行
                        $subject
                        ,$post_date
                        ,$post_end_date
                        ,$area_no
                        ,$location
                        ,$price_lower
                        ,$price_upper
                        ,$period_lower
                        ,$period_upper
                        ,$content
                        ,$screening
                        ,$member_flg
                        ,$reflect_flg
                        ,$adminid
                        ,$adminid
                    )) === false)
        {

            $db->rollBack();
            throw new Exception('project_tb INSERT ERROR');
        }

        // 案件情報テーブルからIDを取得
        $sql = "SELECT project_id
                 FROM project_tb
                 WHERE subject = '" .$subject
                 ."' ORDER BY create_datetime DESC LIMIT 1;";

        $result = $db->prepare($sql);
        if ($result->execute() === false)
        {
            $db->rollBack();
            throw new Exception('project_id SELECT ERROR');
        }
        else
       {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $project_id = $row["project_id"];

            // プロジェクトスキルテーブルにフェーズを書き出し
            foreach($phase_no as $val)
            {
                $sql = "INSERT INTO project_phase_tb
                            SET project_id = $project_id
                                , phase_no = $val";
                // SQL準備
                $result = $db->prepare($sql);
                // SQL実行
                if ($result->execute() === false)
                {
                    $db->rollBack();
                    throw new Exception('project_phase_tb INSERT ERROR');
                }
            }

            // プロジェクトスキルテーブルにスキルキーワードを書き出し
            foreach($keyword as $val)
            {
                $sql = "INSERT INTO project_skill_tb
                            SET project_id = $project_id
                                , skill_no = $val";
                // SQL準備
                $result = $db->prepare($sql);
                // SQL実行
                if ($result->execute() === false)
                {
                    $db->rollBack();
                    throw new Exception('project_skill_tb INSERT ERROR');
                }
            }

        }

        $db->commit();

        $insertbtn = "<form action='". Mg_NewProject. "' method='post'>
                        <button type='submit' name='insertbtn' id='insertbtn' value='insertbtn' class='btn btn-block btn-warning'>続けて登録する</button>
                        </form>";
        $msg = "<p id='success'>以下の内容で登録が完了しました！</p>";


        $returnbtn = "<form action='". Mg_Top. "' method='post'>
                        <button type='submit' name='return' id='btn' value='' class='btn btn-block btn-info'>案件情報一覧へ戻る</button>
                        </form>";


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
            $errmsg[] = "MSG:" . mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS")."<br />";
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
        $msg = "<p class='error_m'>エラーが発生しました</p>";

        $insertbtn = "";


        $returnbtn = "<form action='". Mg_Top. "' method='post'>
                        <button type='submit' name='return' id='btn' value='' class='btn btn-block btn-info'>TOPへ戻る</button>
                        </form>";
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


    <title>案件情報</title>

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
            <h1 class="h2">案件新規登録画面</h1>
          </div>

            <!-- ここにコンテンツ -->
            <?= $msg ?>
            <hr>

            <div id="projectwrap">
            	<table class="projectinfo">
            		<tr><th>件名</th><td><?= $subject ?></td></tr>
            		<tr><th>契約単価</th><td><?= $price_lower ?>万円～<?= $price_upper ?>万円</td></tr>
            		<tr><th>契約期間</th><td><?= $period_lower ?>～<?= $period_upper ?></td></tr>
            		<tr><th>フェーズ</th><td><?php foreach($phasestr as $row){echo $row."　";}  ?></td></tr>
            		<tr><th>エリア</th><td><?= $areastr ?></td></tr>
            		<tr><th>勤務地</th><td><?= $location ?></td></tr>
            		<tr><th>内容</th><td><?= nl2br($content) ?></td></tr>
            		<tr><th>選考方法</th><td><?= $screening ?></td></tr>
            		<tr><th>キーワード</th><td><?php foreach($keywordstr as $row){echo $row."　";}  ?></td></tr>
            		<tr><th>この案件を見られるユーザー</th><td><?= $memberstr ?></td></tr>
            		<tr><th>サイト反映</th><td><?= $reflectstr ?></td></tr>
            		<tr><th>掲載期間</th><td><?= $post_date ?>～<?= $post_end_date ?></td></tr>
            		<tr class="projectinfo-btn"><td colspan="2"><?= $insertbtn ?></td></tr>
            		<tr class="projectinfo-btn"><td colspan="2"><?= $returnbtn ?></td></tr>
            	</table>
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




