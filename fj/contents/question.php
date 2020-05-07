<?php
//---------------------------------------------------------
//  FAQページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
// フリーランスJOBSのよくあるご質問
//
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
    <meta name="keywords" content="システム開発,インフラ構築,人材派遣,IT">
    <meta name="description" content="フリーランスエンジニアの方からよくいただく質問を解説。">
    <title>よくある質問 - フリーランスJOBS</title>
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
							<span class="mainvisual mainvisualenfont">FAQ</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">よくあるご質問</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">サービス全般に関するよくあるご質問</span>
					</div>
				</div>

				<!-- -FAQリスト -->
				<div class="section innerspace contentsbox questionbox">
					<ul class="questionlists">

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										案件を紹介頂くのに費用はかかるのでしょうか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										料金は頂いておりません。登録費および登録後においても全て無料にてご利用可能です。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ-- -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										会員登録はどのようにすればいいですか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										会員登録フォームから必要事項を入力し仮登録を行います。 その後、当社
										より面談希望日の連絡を差し上げます。登録に直接お越しいただきまして、コーディネーターによって業界動向のご案内、今後の説明、具体的なお仕事紹介、登録者様の詳細な希望お伺い等をさせていただきます。 登録者様によって希望する内容がさまざまですので、正式登録は直接お会いしてお伺いさせていただくことが前提となります。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										会員様限定の非公開案件とは何ですか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										会員様限定の非公開案件とは、一般公開せず会員の方のみにご紹介する案件のことです。非公開案件は、クライアント企業の新規事業や新商品開発に携わる機密 情報を含んだものが多く、一般公開して大々的にご紹介することができません。そのため、会員様限定でご紹介する形をとっております。会員様限定の非公開案件のご紹介を希望される方は会員登録していただきますようお願い致します。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										単価は税込みですか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										当サイトに記載されている単価は全て税込み（総額表示）となります。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										交通費は支給されるのでしょうか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										業務委託契約にて仕事をする場合、基本的に交通費支給は通常ございません。 ただし、出張や海外での作業を行う場合、交通費の精算や現地での経費精算が発生することがあります。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										初めてフリーエンジニアになるのですが、確定申告や税金について不安なのですが。
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										初めての人でも不安を抱えることなくしっかり申告することができるように、専属の税理士をご紹介致します。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										どうして待遇が良いのでしょう？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										ITエンジニアはその能力に見合った、十分な収入を得てこそ力を発揮できるものです。弊社では、担当コーディネーターが企業との交渉、会員様のキャリアレベルと希望にあった仕事内容、契約金額の仕事をご紹介しています。派遣社員や契約社員では、保険など諸費用が引かれ 源泉徴収に加えて受注手数料が30～50％かかるケースが多いようです。一方、弊社は無駄な経費（事務所費・営業費など）を最小限に抑える事により受注手数料も少なく、最大限エンジニアへ還元を行うことが可能となっております。また、改善点やクレーム、良いアイデアなども気軽にご指摘いただけますと助かります。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										案件が終わるときに仕事の間が空いてしまわないですか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										案件終了前の約１ヶ月前より担当コーディネーターが責任を持って豊富な案件の中から随時提案をさせて頂きます。エンジニアの皆様は案件終了まで安心してお仕事をしていただきます。
									</div>
								</dd>
							</dl>
						</li>

						<!-- FAQ -->
						<li class="questionlist">
							<dl class="questiondescriptionlist">
								<dt class="questiondescriptionterm">
									<div class="questiondescriptioninner">
										経験が浅くても、フリーエンジニア（個人事業主）になれるのでしょうか？
									</div>
								</dt>
								<dd class="questiondescription">
									<div class="questiondescriptioninner">
										独立すると言うと、豊富な経験値が必要だと考えている人もいるかもしれませんが、実際には新たな仕事経験を積むことによって、エンジニアとして成長を続けることができます。独立の目安としては、経験２年程度からと言われています。
									</div>
								</dd>
							</dl>
						</li>

					</ul>
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
							無料求人サービスに登録
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

