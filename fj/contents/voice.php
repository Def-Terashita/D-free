<?php
//---------------------------------------------------------
//  口コミページ  |  最終更新日:2019/01/23
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

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="関西に特化したフリーランスエンジニアのための案件情報サイト。豊富な案件の中からあなたに合ったお仕事をご紹介！登録は無料です。あなたも始めてみませんか？">
    <title>口コミ - D-free</title>
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
							<span class="mainvisual mainvisualenfont">VOICE</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">ユーザー様からの声</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">
                    <div>D-freeをご利用いただいている個人事業主の方々からお声を頂戴いたしました。</div>
					<hr>
					<!-- voice -->
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="40代男性のイメージ画像">40代男性
                        </div>
                        <div class='voice'>
                            対応がスピーディなので案件が終了してもすぐに次案件への参画ができています。
							<br>エージェントとの連携も密に取れており信頼できる会社だと感じています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="30代男性のイメージ画像">30代男性
                        </div>
                        <div class='voice'>
                            案件が豊富にあるので自分の好きな仕事ができている上、会社員の時と比べて収入が格段に上がり、毎日が充実しています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="20代女性のイメージ画像">20代女性
                        </div>
                        <div class='voice'>
                            登録後の面談では、自分の話をじっくり聞いてくださり、その後すぐにマッチする案件への面談を進めていただきました。
							<br>親身な対応と仕事の迅速さに魅力を感じています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="40代男性のイメージ画像">40代男性
                        </div>
                        <div class='voice'>
                            <div>手数料が一律10％と明瞭なのが誠実だと感じました。</div>
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="20代男性のイメージ画像">20代男性
                        </div>
                        <div class='voice'>
                            経験が浅く、本当に自分にできるのだろうかと不安でしたが、チャレンジするなら今のうちだと思い飛び込みました。
							<br>こちらは自分の経験に合った案件を紹介してくださるので技術者として成長を感じています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="60代男性のイメージ画像">60代男性
                        </div>
                        <div class='voice'>
                            サラリーマンの時と違い収入の面で不安もありましたが、案件が豊富であることと、支払いサイトが翌月末払いなので安心して生活できています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="30代女性のイメージ画像">30代女性
                        </div>
                        <div class='voice'>
                            会社員からキャリアチェンジをしたばかりで不安も大きかったですが、フリーランスのためのサポートが充実しているので心強いです。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="30代男性のイメージ画像">30代男性
                        </div>
                        <div class='voice'>
                        	他のエージェントの登録もしていましたが、初回面談時に担当してくださった方がとても話しやすかったことが決め手になりました。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="30代女性のイメージ画像">30代女性
                        </div>
                        <div class='voice'>
                        	契約後も継続的に案件情報の提供があるので常に自分に合ったやりがいのある仕事に就けています。
                        </div>
                    </div>
                    <div class='voice_listbox bg-rightgray'>
                        <div class="engineer">
                            <img src="../img/voice.png" alt="50代男性のイメージ画像">50代男性
                        </div>
                        <div class='voice'>
                        	登録後すぐに案件を紹介してくださり、前職から間をあけることなく働くことができました。
							<br>フリーランス特有の仕事が途切れる不安が軽減されるので、今は目の前の仕事に思い切り打ち込めています。
                        </div>
                    </div>
                    <!-- voice -->
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

