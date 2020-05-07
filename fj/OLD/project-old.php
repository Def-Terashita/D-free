<?php
//---------------------------------------------------------
//  案件詳細ページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
// indexページかlistページからPOSTで送られてきたIDの案件情報を詳細表示
// URLのリンクから飛んできて、かつ、セッションがない場合は案件リストページに飛ばす
// 応募フォームへ遷移
//---------------------------------------------------------


//セッション開始
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


$errmsg = array (); 		      // DBエラーメッセージ
$ret 	= FALSE;			     // 関数リターン値

//インクルード（define.php）
$ret = include_once("../inc/define.php");
$ret = include_once(FJ_Mg_Function);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (isset($_POST["project_id"]))
{
    $project_id = $_POST["project_id"];
    $_SESSION["project_form"] = $project_id;
}
elseif (isset($_SESSION["project_form"]))
{
    $project_id = $_SESSION["project_form"];

}
else
{
    header ("Location:" .Fj_Top);  //トップ画面へ遷移
    exit();     //処理終了
}


// table番号→日本語表示
// フェーズ
$phasestr   = phaseStr($_SESSION["project_data"]["project"][$project_id]["phase_no"]);
// エリア
$areastr    = areaStr($_SESSION["project_data"]["project"][$project_id]["area_no"]);
// キーワード
$keywordstr = keywordStr($_SESSION["project_data"]["project"][$project_id]["skill_no"]);


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
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130283303-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-130283303-1');
	</script>
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
        <!-- --------------ナビゲーション-------------- -->
		<?php include (FJ_Header);?>
		<!-- ------------------------------------------ -->


        <!-- --------------メインコンテンツ-------------- -->

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

<!-- ---------------------------------------------------------------------------------------------------- -->


					<div class="project_detailbox bg-gray">
                        <div class="project_subject"><?= $_SESSION["project_data"]["project"][$project_id]["subject"] ?></div>
    					<div class="project_id">案件No：<?= $_SESSION["project_data"]["project"][$project_id]["project_id"] ?><br>掲載日：<?= $_SESSION["project_data"]["project"][$project_id]["post_date"] ?></div>
    					<div class="clear"></div>
    					<hr>

    					<div class="project_price">契約単価：<span><?= $_SESSION["project_data"]["project"][$project_id]["price_lower"] ?>万円～<?= $_SESSION["project_data"]["project"][$project_id]["price_upper"] ?>万円</span></div>

						<div class='tablewrap project_tablewrap bg-white'>
        						<div class='project_detail_title bg-gray'>内容</div>
        						<div class='project_detail_content'><?= nl2br($_SESSION["project_data"]["project"][$project_id]["content"]) ?></div>
        						<div class='project_detail_title bg-gray'>エリア</div>
        						<div class='project_detail_content'><?= $areastr ?></div>
        						<div class='project_detail_title bg-gray'>勤務地</div>
        						<div class='project_detail_content'><?= $_SESSION["project_data"]["project"][$project_id]["location"] ?></div>
        						<div class='project_detail_title bg-gray'>契約期間</div>
        						<div class='project_detail_content'><?= $_SESSION["project_data"]["project"][$project_id]["period_lower"] ?>～<?= $_SESSION["project_data"]["project"][$project_id]["period_upper"] ?></div>
        						<div class='project_detail_title bg-gray'>選考方法</div>
        						<div class='project_detail_content'><?= $_SESSION["project_data"]["project"][$project_id]["screening"] ?></div>
        						<div class='project_detail_title bg-gray'>フェーズ</div>
        						<div class='project_detail_content'><?php foreach($phasestr as $row){echo $row."　";}  ?></div>
        						<div class='project_detail_title bg-gray'>スキルキーワード</div>
        						<div class='project_detail_content'><?php foreach($keywordstr as $row){echo $row."　";}  ?></div>
						</div>



					</div>

					<form action="<?= Fj_Form ?>" method="post">
                		<button type="submit" class="reset-btn form-btn bg-red color-white"><span>ご応募・お問い合わせはこちら<br>（TEL:06-6643-9305）</span></button>
                		<input type="hidden" name="form_project_id" value="<?= $project_id ?>"/>
					</form>



<!-- ---------------------------------------------------------------------------------------------------- -->

				</div>
			</div>
		</div>

        <!-- --------------フッター-------------- -->
		<?php include (FJ_Footer);?>
		<!-- ------------------------------------ -->
	</div>

</body>
</html>

