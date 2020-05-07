<?php
//---------------------------------------------------------
//  プライバシーポリシページ  |  最終更新日:2018/10/17
//---------------------------------------------------------
// フリーランスJOBSのプライバシーポリシ
//---------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$errmsg = array (); 		                     // エラーメッセージ
$ret    = FALSE;                                // 関数リターン値
$ret    = include_once("../inc/define.php");   // defineパス: インクルード
if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="フリーランスJOBSのプライバシーポリシのページです。">
    <title>プライバシーポリシ - フリーランスJOBS</title>
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
							<span class="mainvisual mainvisualenfont">PRIVACY POLICY</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">個人情報保護方針</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">

    				<!-- PrivacyPolicy -->
    				<div class="privacypolicy">
                        <div  class="privacypolicynote">
                            株式会社 Def tribe（以下、「当社」といいます。）は、システム開発・IT技術支援・システムコンサルティング・経営コンサルティングといった当社の各事業において取り扱う個人情報及び特定個人情報が、御客様のプライバシーを構成する重要な要素であることを深く認識し、個人情報の保護及び特定個人情報に関する法令、当社内部規程を全ての従業者が遵守することにより、御客様の当社に対する期待と信頼に応えてまいります。
        				</div>
    				</div>
					<div class="space"></div>
    				<hr>
					<div class="space"></div>
    				<div class="privacypolicy">
						<h2>個人情報及び特定個人情報の取得、利用及び提供</h2>
                        <div  class="privacypolicynote">
                            当社が個人情報を取得、利用及び提供するにあたっては、あらかじめ、その利用目的をでき得る限り特定し、ご本人様に通知し、同意を得て、目的の達成に必要な限度において取得、利用及び提供させて頂くとともに、その目的の達成に必要な範囲を超えた取得、利用及び提供を行わない為の措置を講じます。また、ご本人様が容易に認識できない方法、偽りその他の不正な方法により取得することはありません。
なお、特定個人情報の取得、利用及び提供にあたっては、本人確認を行い、同意を得た上で取得し、法令で定められた利用目的にのみ限定した取り扱いを行い、その目的の達成に必要な範囲を超えた取得、利用及び提供を行わない為の措置を講じます。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>法令、国が定める指針その他の規範の遵守</h2>
                        <div  class="privacypolicynote">
                            当社は、個人情報保護に関する法律、行政手続きにおける特定の個人情報を識別するための番号の利用等に関する法律、個人情報の取扱いに関する法令及び国が定める指針その他規範等を遵守し、全社的に適用して管理を徹底します。
                        </div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>個人情報及び特定個人情報の漏洩、滅失又は毀損の防止並びに是正</h2>
                        <div  class="privacypolicynote">
                           当社は、取り扱う個人情報及び特定個人情報に関するリスクを十分に認識及び分析し、必要かつ合理的な安全管理措置を講じることにより個人情報及び特定個人情報の漏洩、滅失又は毀損の防止を行います。また、個人情報及び特定個人情報の漏洩、滅失又は毀損が発生し得る、新たなリスクを察知した際には、遅滞なく是正処置を講じます。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>苦情及び相談への対応</h2>
                        <div  class="privacypolicynote">
                            個人情報及び特定個人情報を取得させて頂くご本人様の苦情及び相談については、以下のお問合せ窓口にて承ります。
                            <br />＜個人情報の取り扱いに関する苦情相談窓口＞
                            <br />a）責任者	：個人情報保護管理責任者　上岡 天志
                            <br />b）郵便	：〒542-0081 　大阪市中央区南船場4丁目12番24号現代心斎橋ビル5階 
                            <br />c）電話番号	：06-6643-9305
                            <br />d）e-mail	：kanri@e-deftribe.com
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>個人情報保護ルールの継続的改善</h2>
                        <div  class="privacypolicynote">
                            本方針を頂点として策定する個人情報保護ルールについては、技術動向、個人情報保護に関する社会情勢及び内外からよせられるご意見、苦情等の内容を十分考慮し、継続的に改善します。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">

                        <div  class="privacypolicynote" style="text-align: right;">
                            制定：2020年1月1日
                            <br />最終改定日：2020年1月1日
                            <br />代表取締役　河野 浩明

    					</div>
    				</div>


				</div>

			</div>
		</div>

        <!-- フッター -->
		<?php include (FJ_Footer);?>

	</div>





</body>
</html>

