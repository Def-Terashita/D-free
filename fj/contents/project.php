<?php
//---------------------------------------------------------
//  案件詳細ページ  |  最終更新日:2019/04/18
//---------------------------------------------------------
// indexページかlistページからGETで送られてきたIDの案件情報を詳細表示
// URLのリンクから飛んできて、かつ、その案件がない場合は案件リストページに飛ばす
// 非会員がURLのリンクから飛んできて会員限定の案件だった場合はログインページへ飛ばす
// 応募フォームへ遷移
//---------------------------------------------------------

//セッション開始
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$errmsg = array (); 		      // DBエラーメッセージ
$ret 	= FALSE;			     // 関数リターン値
$db     = NULL;                  // DBオブジェクト
date_default_timezone_set('Asia/Tokyo');

$ret 	= include_once("../inc/define.php");
$ret    = include_once(FJ_Config);  // config.php
$ret    = include_once(FJ_Mg_Function);


if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    require ("$config_path" . "$config_file");
}


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


if (isset($_GET['page']))
{
	$project_id = $_GET['page'];
}
else
{
    header ("Location:" .Fj_Top);  //トップ画面へ遷移
    exit();     //処理終了
}

try{

        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

        // 文字コード
        $db->exec ("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合


        $sql = "SELECT
				  project_id
				  , subject
				  , post_date
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
				FROM
				  project_tb 
				WHERE
				  project_id = {$project_id}";

        //SQL表示項目
        $result = $db->query($sql);

        if ($result !== false)
        {
            // 行データ取得
            $row = $result->fetchall(PDO::FETCH_ASSOC);

            if (0 < $result->rowCount())
            {

                $project_data = array();

                foreach ($row as $val)
                {

                    $project_id 	= $val["project_id"];
                    $subject        = $val["subject"];
                    $post_date      = $val["post_date"];
                    $area_no        = $val["area_no"];
                    $location       = $val["location"];
                    $price_lower    = $val["price_lower"];
                    $price_upper    = $val["price_upper"];
                    $period_lower   = $val["period_lower"];
                    $period_upper   = $val["period_upper"];
                    $content        = $val["content"];
                    $screening      = $val["screening"];
                    $member_flg     = $val["member_flg"];
                }
				
				// 手打ちでURLを打ち、案件はあるが非公表のもので、ゲストの場合
                if ($val["member_flg"] == Member && !isset ($_SESSION["name"]))
                {
	                header ("Location:" .Fj_LogIn);  //ログイン画面へ遷移
	    			exit();     //処理終了
                }

                // フェーズ
                $sql_phase = "SELECT project_id, phase_no FROM project_phase_tb WHERE project_id = {$project_id} ;";
                $result = $db->query($sql_phase);
                if ($result !== false)
                {
                    $row = $result->fetchall(PDO::FETCH_ASSOC);
                    // プロジェクトフェーズテーブルから該当IDのフェーズを取得";
                    foreach($row as $val)
                    {
                        $project_data["phase_no"][] = $val["phase_no"];
                    }
                }

                // スキル
                $sql_skill = "SELECT project_id, skill_no FROM project_skill_tb WHERE project_id = {$project_id} ;";
                $result = $db->query($sql_skill);
                if ($result !== false)
                {
                    $row = $result->fetchall(PDO::FETCH_ASSOC);
                    // スキルキーワードテーブルから該当IDのキーワードを取得";
                    foreach($row as $val)
                    {
                        $project_data["skill_no"][] = $val["skill_no"];
                    }
                }
            }

            else
            {
                // 手打ちでURLを打ち、案件番号がない場合
                header ("Location:" .Fj_Top);  //トップ画面へ遷移
    			exit();     //処理終了
            }

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


// table番号→日本語表示
// フェーズ
$phasestr   = phaseStr($project_data["phase_no"]);
// エリア
$areastr    = areaStr($area_no);
// キーワード
$keywordstr = keywordStr($project_data["skill_no"]);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="システム開発,インフラ構築,人材派遣,IT">
    <meta name="description" content="">
    <title>案件詳細 - フリーランスJOBS</title>
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
							<span class="mainvisual mainvisualenfont">PROJECT</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">案件情報</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">




					<div class="project_detailbox bg-gray">
                        <div class="project_subject"><?= $subject ?></div>
    					<div class="project_id">案件No：<?= $project_id ?>
    					<br>掲載日：<?= $post_date ?></div>
    					<div class="clear"></div>
    					<hr>

    					<div class="project_price">契約単価：<span><?= $price_lower ?>万円～<?= $price_upper ?>万円</span></div>

						<div class='tablewrap project_tablewrap bg-white'>
        						<div class='project_detail_title bg-gray'>内容</div>
        						<div class='project_detail_content'><?= nl2br($content) ?></div>
        						<div class='project_detail_title bg-gray'>エリア</div>
        						<div class='project_detail_content'><?= $areastr ?></div>
        						<div class='project_detail_title bg-gray'>勤務地</div>
        						<div class='project_detail_content'><?= $location ?></div>
        						<div class='project_detail_title bg-gray'>契約期間</div>
        						<div class='project_detail_content'><?= $period_lower ?>～<?= $period_upper ?></div>
        						<div class='project_detail_title bg-gray'>選考方法</div>
        						<div class='project_detail_content'><?= $screening ?></div>
        						<div class='project_detail_title bg-gray'>フェーズ</div>
        						<div class='project_detail_content'><?php foreach($phasestr as $row){echo $row."　";}  ?></div>
        						<div class='project_detail_title bg-gray'>スキルキーワード</div>
        						<div class='project_detail_content'><?php foreach($keywordstr as $row){echo $row."　";}  ?></div>
						</div>

					</div>

					<form action="<?= Fj_Form ?>" method="post">
                		<button type="submit" class="reset-btn form-btn bg-red color-white"><span>ご応募・お問い合わせはこちら<br>（TEL:06-6643-9305）</span></button>
                		<input type="hidden" name="form_project_id" value="<?= $project_id ?>"/>
                		<input type="hidden" name="form_project_subject" value="<?= $subject ?>"/>
					</form>



				</div>
			</div>
		</div>

        <!-- フッター -->
		<?php include (FJ_Footer);?>

	</div>

</body>
</html>

