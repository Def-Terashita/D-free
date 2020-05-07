<?php
//---------------------------------------------------------
//  サービスページ  |  最終更新日:2018/09/19
//---------------------------------------------------------
// フリーランスJOBSのサービスの内容
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
							<span class="mainvisual mainvisualenfont">SERVICE</span>
							<span class="mainvisual mainvisuallinefont">|</span>
							<span class="mainvisual mainvisualkanafont">サービス</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">ご利用いただける４つのサービス</span>
					</div>
				</div>

				<!-- SERVICE1 -->
				<div class="section innerspace contentsbox descriptionboxwrap">
					<div class="descriptionbox descriptionimageleft">
						<div class="descriptionimagebox">
							<img class="descriptionimage" src="<?= Fj_Img ?>service1.jpg" alt="SERVICE1">
							<h3 class="descriptionimagecaption">SERVICE1</h3>
						</div>
						<div class="descriptiontextbox">
							<h3 class="descriptiontextheadline">
								<span class="headline">
									<span class="headlineenfont">Tax Account</span>
									<span class="headlinelinefont">|</span>
									<span class="headlinekanafont">税理士紹介</span>
								</span>
								<span class="headlinenote">- ややこしい税務関係もご心配なく-</span>
							</h3>
							<div class="descriptiontextleft">
								<p>専属の税理士を抱えております。フリーの方が安心して就業できるように、税務関係のプロフェッショナルをご紹介しております。サービス内容に応じて、予算も変わってきますので、気兼ねなく、ご相談ください。毎月の顧問～確定申告のみの依頼も可能です。</p>
							</div>
						</div>
					</div>
				</div>
				<!-- SERVICE2 -->
				<div class="section innerspace contentsbox descriptionboxwrap">
					<div class="descriptionbox descriptionimageright">
						<div class="descriptionimagebox">
							<img class="descriptionimage" src="<?= Fj_Img ?>service2.jpg" alt="SERVICE2">
							<h3 class="descriptionimagecaption">SERVICE2</h3>
						</div>
						<div class="descriptiontextbox">
							<h3 class="descriptiontextheadline">
								<span class="headline">
									<span class="headlinekanafont">労務サービス</span>
								</span>
								<span class="headlinenote">- 保険や助成金の事でもお任せを-</span>
							</h3>
							<div class="descriptiontextleft">
								<p>転職、フリーランス活動、独立をお考えの方に対して保険関係、就職助成金関係その他以外に手間がかかることが多々あるかと思います。もちろん初めての方はどうすれば良いか、本当に手続きの流れは合っているのか、より良い手続きの仕方など労務に関する様々なご提案をさせていただくことが可能です。</p>
							</div>
						</div>
					</div>
				</div>

				<!-- SERVICE3 -->
				<div class="section innerspace contentsbox descriptionboxwrap">
					<div class="descriptionbox descriptionimageleft">
						<div class="descriptionimagebox">
							<img class="descriptionimage" src="<?= Fj_Img ?>service3.jpg" alt="SERVICE3">
							<h3 class="descriptionimagecaption">SERVICE3</h3>
						</div>
						<div class="descriptiontextbox">
							<h3 class="descriptiontextheadline">
								<span class="headline">
									<span class="headlinekanafont">エンジニア交流会</span>
								</span>
								<span class="headlinenote">- 交流を経て、次の仕事に繋げる-</span>
							</h3>
							<div class="descriptiontextleft">
								<p>エンジニア同士の出会いや情報交換はフリーランスにとって大きな財産となります。そのような財産を作っていただくべく、当社では定期的に交流会を開催しております。また交流会には当社の社員も参加いたしますので、改善点やクレーム、アイデアなどもお気軽にご指摘いただければ幸いです。</p>
							</div>
						</div>
					</div>
				</div>

				<!-- SERVICE4 -->
				<div class="section innerspace contentsbox descriptionboxwrap">
					<div class="descriptionbox descriptionimageright">
						<div class="descriptionimagebox">
							<img class="descriptionimage" src="<?= Fj_Img ?>service4.jpg" alt="SERVICE4">
							<h3 class="descriptionimagecaption">SERVICE4</h3>
						</div>
						<div class="descriptiontextbox">
							<h3 class="descriptiontextheadline">
								<span class="headline">
									<span class="headlinekanafont">ステップアップ</span>
								</span>
								<span class="headlinenote">- 将来まで見据えてのバックアップ-</span>
							</h3>
							<div class="descriptiontextleft">
								<p>フリーランスとして実績を作った後、さらにキャリアを積みたいというご希望にもお応えします。個人事業主から法人設立をめざす技術者のバックアップも行っております。またフリーランスを終えた後の転職支援も行っております。</p>
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

