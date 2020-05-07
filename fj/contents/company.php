<?php
//---------------------------------------------------------
//  会社概要ページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
// フリーランスJOBSの会社概要
// mapもこのページ
//---------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


$errmsg = array ();      		                 // エラーメッセージ
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
    <meta name="description" content="D-freeの会社概要ページです。能力を持ち仕事をしたいと願う「人」に、その持てる力を発揮できる新しいステージを創りだすことこそが私たちの使命です。">
    <title>会社概要 - D-free</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Contents_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130283303-1"></script>
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
							<span class="mainvisual mainvisualenfont">COMPANY</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">会社概要</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">D-freeのポリシー</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">

    				<!-- CompanyPolicy -->
    				<div>
    					<h2 style="text-align: left;">企業理念</h2>
            			<hr>

            			<div class="companywrap">

            				<div class="companyimg">
								<img class="" src="<?= Fj_Img ?>company.jpg" alt="企業イメージの写真">
							</div>
                            <div  class="companynote">
                                私達は “ひと” がすべてと考えています。
                                「人がいて初めて、モノ・金・情報を動かすことができる」という発想で企業・組織のインフラとしてのあらゆる人材活用の問題に取り組みます。
                                激動する雇用環境は、実力主義や人財の流動化にさらに拍車をかけています。
                                経営資源としての「人｣について考えるとき、その人の能力を最大限に発揮させ、経営活動の中に役立てていくこと以外には、グローバルな競争激化する中では、生き残れない状況を呈しています。
                                私たちの仕事は、「人」と「仕事」の関係を量から質へ、固定から柔軟へ、変化という視点で見つめ直し、スピード感を持って、その能力を最大限発揮させるオリジナルな「仕事のあり方の創造」を追及することです。
                                能力を持ち仕事をしたいと願う「人」に、その持てる力を発揮できる新しいステージを創りだして、企業経営の強靭な動脈としての「人財インフラ」を提供していくことが、私たちの使命と考えています。
            				</div>
            				<div class="clear"></div>
            			</div>
    				</div>
    				<div class="space50"></div>

    				<!-- CompanyInfo -->

    				<div>
        				<h2 style="text-align: left;">企業情報</h2>
            			<hr>
        				<div class="tablewrap company_tablewrap">

                            <table  class="bg-white">
                            	<tr><th>社名</th><td>株式会社Def tribe（デフ　トライブ）</td></tr>
                            	<tr><th>取締役社長</th><td>上岡 天志</td></tr>
                            	<tr><th>代表取締役副社長</th><td>河野 浩明</td></tr>
                            	<tr><th>所在地</th><td>本社　〒542-0081　大阪市中央区南船場4丁目12番24号現代心斎橋ビル5階</td></tr>
                            	<tr><th>TEL</th><td>06-6643-9305</td></tr>
                            	<tr><th>従業員数</th><td>73名</td></tr>
                            	<tr><th>事業内容</th><td>経営コンサルティング業務　システムコンサルティング業務　システム開発全般　IT技術教育</td></tr>
                            </table>

                            <div class="space50"></div>
							<div class="map_wrapper">
						      <div id="map">
						          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.1653766523805!2d135.49652771471685!3d34.67577539206152!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6000e71ae5554689%3A0x90c412b0a14971f7!2z44CSNTQyLTAwODEg5aSn6Ziq5bqc5aSn6Ziq5biC5Lit5aSu5Yy65Y2X6Ii55aC077yU5LiB55uu77yR77yS4oiS77yS77yUIOePvuS7o-W_g-aWjuapi-ODk-ODqzXpmo4!5e0!3m2!1sja!2sjp!4v1543280759747" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen></iframe>
						      </div>
                        	</div>

                        </div>
                    </div>

				</div>


				<!-- Let's start! -->
				<div class="section innerspace contentsbox registbox">
					<h3 class="registtitle">
						<a href="<?= Fj_NewMember ?>">
							<div class="registtitletext blink">
								Let's　start!
							</div>
						</a>
						<div class="registtitlenote">
							あなたも始めてみませんか？ まずは無料登録から！
						</div>
					</h3>
					<a href="<?= Fj_NewMember ?>" class="btn registbtn bg-rightred">
						<div class="registbtntext">
							求人サービスに登録
						</div>
						<div class="registbtnnote">
							豊富な案件数からあなたに合ったJOBをお届け！
						</div>
					</a>
				</div>

			</div>
		</div>

        <!-- フッター -->
		<?php include (FJ_Footer);?>

	</div>





</body>
</html>

