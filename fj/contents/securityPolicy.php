<?php
//---------------------------------------------------------
//  プライバシーポリシページ  |  最終更新日:2018/10/17
//---------------------------------------------------------
// フリーランスJOBSの情報セキュリティーポリシ
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
    <meta name="description" content="フリーランスJOBSの情報セキュリティポリシのページです。">
    <title>情報セキュリティポリシ - フリーランスJOBS</title>
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
							<span class="mainvisual mainvisualenfont">SECURITY POLICY</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">情報セキュリティ方針</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">

    				<!-- PrivacyPolicy -->
    				<div class="privacypolicy">
						<h2>1　目的</h2>
                        <div  class="privacypolicynote">
                            株式会社Def tribe（以下、「当社」といいます）は、システム開発・IT技術支援・システムコンサルティング・経営コンサルティングなどの提供並びに従業者の管理（以下、「事業」といいます）を実施するに当たり、多くの情報資産を利用していることから、情報セキュリティを適切に実現し、情報資産の保護に努めることは、社会の信頼のもとに企業活動を推進するための必要不可欠な要件であるとともに、重大な社会的責務であると認識しております。よって、当社は情報セキュリティの重要性を鑑み、この情報セキュリティ方針（以下、「本方針」といいます）を定め、具体的に実施するための情報セキュリティマネジメントシステムを確立し、実施し、維持し、且つ改善してまいります。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>2　情報セキュリティの定義</h2>
                        <div  class="privacypolicynote">
                            情報セキュリティとは、機密性、完全性及び可用性を維持することと定義しています。
                            <h4>(1)　機密性</h4>
                            情報資産を不正アクセスなどから保護し、参照する権限のないものに漏洩しないことを意味します。（認可されていない個人、エンティティ又はプロセスに対して、情報を使用させず、また、開示しない特性）
                            <h4>(2)　完全性</h4>
                            情報資産を改竄や間違いから保護し、正確かつ完全に維持されることを意味します。（正確さ及び完全さの特性）
                            <h4>(3)　可用性</h4>
                            情報資産を紛失・破損やシステムの停止などから保護し、必要なときに利用できることを意味します。（認可されたエンティティが要求したときに、アクセス及び使用が可能である特性）
                        </div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>3　適用範囲</h2>
                        <div  class="privacypolicynote">
							本方針を当社の管理する情報資産の全てに対して適用します。情報資産の範囲は、電子的機器並びに電子データにとどまらず、紙媒体を含めた全ての形態を含みます。
    						<h4>(1)　組織</h4>
    						株式会社Def tribe（本社）
    						<h4>(2)　施設</h4>
    						本社（大阪市中央区南船場4丁目12番24号現代心斎橋ビル5階）
    						<h4>(3)　事業</h4>
    						コンピューターソフトウェアの受託開発・運用・保守の管理、WEBサイト制作、および人材派遣サービス
    						<h4>(4)　資産</h4>
    						上記事業、各種サービスにかかわる書類、データ、情報システム及びネットワーク
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>4　実施事項</h2>
                        <div  class="privacypolicynote">
                           本方針並びに当社の情報セキュリティマネジメントシステムに従い、下記の事項を実施します。
                            <h4>(1)　情報セキュリティ目的について</h4>
                            本方針と整合性を有し、適用される情報セキュリティ要求事項、並びにリスクアセスメント及びリスク対応の結果を考慮した情報セキュリティ目的を策定し、全従業者に周知するとともに、当社の環境の変化に応じて随時、変化がなくとも定期的な見直しを行います。
                            <h4>(2)　情報資産の取り扱いについて</h4>
                             a)　アクセス権限は、その権限を必要とする者のみに与えることとします。
			                <br />b)　法的及び規制の要求事項並びに契約上の要求事項、当社の情報セキュリティマネジメントシステムの規定に従い管理を行います。
			                <br />c)　情報資産の価値、機密性、完全性、可用性の観点から、それらの重要性に応じて適切に分類し管理を行います。
			                <br />d)　情報資産が適切に管理されていることを確認するために、継続的に監視を実施します。
    						<h4>(3)　リスクアセスメントについて</h4>
    						a)　リスクアセスメントを行い、事業の特性から最も重要と判断する情報資産について適切なリスク対応を実施し、管理策を導入します。
              				<br />b)　情報セキュリティに関連する事故原因を分析し、再発防止策を講じます。
    						<h4>(4)　情報セキュリティ継続について</h4>
    						災害や故障などによる影響を最小限に抑え、情報セキュリティ継続能力を確保します。
    						<h4>(5)　教育について</h4>
    						全従業者に対し、情報セキュリティ教育および訓練を実施します。
    						<h4>(6)　規定並びに手順の順守</h4>
    						情報セキュリティマネジメントシステムの規定並びに手順を順守します。
    						<h4>(7)　法的及び規制の要求事項並びに契約上の要求事項の順守</h4>
    						情報セキュリティに関する法的及び規制の要求事項並びに契約上の要求事項を順守します。
    						<h4>(8)　継続的改善</h4>
    						情報セキュリティマネジメントシステムの継続的な改善に取り組みます。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>5　責任と義務及び罰則</h2>
                        <div  class="privacypolicynote">
                           本方針を含めた情報セキュリティマネジメントシステムに対する責任は代表取締役が負うこととし、適用範囲の従業者は策定された規定や手順を順守する義務を負うものとします。なお義務を怠り、違反行為を行った従業者は就業規則に定めるところにより処分します。協力会社社員については個別に定めた契約などに従って、対応を行います。
    					</div>
    				</div>
    				<div class="space"></div>
    				<hr>
    				<div class="space"></div>
    				<div class="privacypolicy">
						<h2>6　定期的見直し</h2>
                        <div  class="privacypolicynote">
                          情報セキュリティマネジメントシステムの見直しは、定期的および必要に応じてレビューし、維持・管理するものとします。
    					</div>
    				</div>
					<div class="space"></div>
    				<hr>
                    <div class="space"></div>
                    <div class="privacypolicy">
                        <h2>7　当社WEBサイトのセキュリティについて</h2>
                        <div  class="privacypolicynote">
                            <h4>(1)　個人情報収集時のSSLの使用</h4>
                            当社が運営するWEBサイトを通じた個人情報の取得では、個人情報を第三者による不正アクセスから守るため、SSL（Secure Sockets Layer）暗号化通信により保護し、安全性の確保に努めております。
                            <h4>(2)　Cookieの利用について</h4>
                            当社WEBサイトではGoogle社のGoogle Analyticsを使用し、通常のアクセスログの収集ならびに解析に加え、データ計測（年齢、性別、興味や関心、行動履歴など）を行なっております。これらの収集・解析・計測にあたっては、ファーストパーティCookieや冗長性の確保を目的としたサードパーティCookieを使用しており、個人を特定する情報は保存されず、一意の識別子のみが保存されます。
                            <br />なお、オプトアウト手続きを利用してそれぞれのトラッキングを拒否することが出来ますので、希望される場合は、下記URLより手続きをお願い致します。
                            <br /><a href="https://tools.google.com/dlpage/gaoptout?hl=ja" style="text-decoration: underline;color:blue;">Googleアナリティクスオプトアウトアドオン</a>
                            <h4>(3)　クロスサイトスクリプティング（CSS/XSS）やSQLインジェクションへのセキュリティ対策</h4>
                            当社WEBサイトは、開発時より適切な検査・処置を行い、クロスサイトスクリプティング（CSS/XSS）やSQLインジェクションへの対策を講じています。
                            <h4>(4)　その他のセキュリティ対策</h4>
                            当社WEBサイトをホストするサーバは、ファイヤ・ウォールの設置、ウィルス対策の整備等により、個人情報へのアクセス、紛失、破壊、改ざん、漏洩、ウィルス感染等の防止策を講じています。
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

