<?php
//---------------------------------------------------------
// 本番環境アップ用テストページ
// インクルード系確認
//---------------------------------------------------------


//セッション開始
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$errmsg = array ();      		                 // エラーメッセージ
$ret    = FALSE;                                // 関数リターン値
include_once("../inc/define.php");   // defineパス: インクルード

$ret  = include_once(FJ_Config);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    //echo "$config_path" . "$config_file";
     require ("$config_path" . "$config_file");
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="フリーランスJOBSでは、税理士の紹介、保険や助成金の事など技術者の方が安心して就業できるよう様々なサービスを提供しております。">
    <title>サービス紹介 - フリーランスJOBS</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Contents_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>
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
			只今テスト中です。お待ちください。


		</div>

				

        <!-- --------------フッター-------------- -->
		<?php include (FJ_Footer);?>
		<!-- ------------------------------------ -->
	</div>





</body>
</html>

