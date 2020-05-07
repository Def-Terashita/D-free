<?php
//---------------------------------------------------------
//  TOPページ  |  最終更新日:2018/09/14
//---------------------------------------------------------
// 各ページへ遷移
// 検索項目をPOSTでlist.phpへ送る
//---------------------------------------------------------

//セッション開始
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

$errmsg = array (); 		      // DBエラーメッセージ
$ret 	= FALSE;			     // 関数リターン値

// defineパス: インクルード
$ret = include_once("./fj/inc/define.php");
// 共通関数インクルード
$ret = include_once(FJ_Config);  // config.php

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
    <meta name="keywords" content="フリーランス,案件,エンジニア,関西">
    <meta name="description" content="関西の案件情報に強い！フリーランスエンジニアのための案件マッチングサイト。専任のキャリアアドバイザーが豊富な案件の中からからあなたに合ったお仕事をお届けいたします。ご登録は無料です。">
    <title>D-free ｜ 関西フリーエンジニア案件情報サイト</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Top_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>

    <script>
    // 検索条件をリセット
    function allclear( off ) {
    	   var ElementsCount = document.searchform.elements.length; // チェックボックスの数
    	   for( i=0 ; i<ElementsCount ; i++ ) {
    	      document.searchform.elements[i].checked = off; // OFFに
    	   }
    	   document.searchform.freeword.value = "";	// フリーワードをクリア
    	}
    </script>

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

        <!-- ナビゲーション -->
		<?php include (FJ_Header);?>
		

        <!-- メインコンテンツ -->

		<div id="mainwrap">
			<!-- メイン１ -->
			<div id="top-main-pc">
				<img alt="" src="<?= Fj_Img ?>top.jpg" style="width:100%;">
			</div>
			<div id="top-main-sp">
				<img alt="" src="<?= Fj_Img ?>top-sp.jpg" style="width:100%;">
			</div>

			<div id="top-main-regist-btn">
				<a href="<?= Fj_NewMember ?>" class="color-white">
					 <span class="registbtntext blink">
						今すぐ登録する！！
					 </span>
				</a>
			</div>


			<!-- メイン２ -->
			<div class="top-main top-innerspace">
				<h2 style="text-align: center">SUPPORT | 安心のサポート</h2>
				<p  style="text-align: center">- 担当者が一人ひとり対応いたします -</p>
				<div class="service">
					<div class="top-img"><img src="<?= Fj_Img ?>support1.png" alt="案件のイメージ画像"></div>
					<div>
						<h3>途切れることのない案件</h3>

						<p style="word-break:normal;">前案件終了時から次の案件開始までのタイムロスをできる限り削除し安定した仕事をご案内いたします。</p>
					</div>
				</div>
				<div class="service">
					<div class="top-img"><img src="<?= Fj_Img ?>support2.png" alt="スペシャリストによる支援のイメージ画像"></div>
					<div>
						<h3>スペシャリストによる支援</h3>
						<p>当社では現場でのしっかりとしたサポート体制により、就業しながらの最新の技能習得が可能です。</p>
					</div>
				</div>
				<div class="service">
					<div class="top-img"><img src="<?= Fj_Img ?>support3.png" alt="成長のイメージ画像"></div>
					<div>
						<h3>経験の浅いエンジニアも</h3>
						<p>当社では、経験の浅いエンジニアにも豊富に案件をご紹介しております。様々な案件の中で経験を積むことで成長を実感していただけます。</p>
					</div>
				</div>
				<div class="service">
					<div class="top-img"><img src="<?= Fj_Img ?>support4.png" alt="コンサルティングのイメージ画像"></div>
					<div>
						<h3>細やかなコンサルティング</h3>
						<p>専任キャリアアドバイザーが１対１であなたのお話を伺い業界の動向や求人傾向など様々な視点から幅広くアドバイスいたします。</p>
					</div>
				</div>
				<div class="clear"></div>


				<div>
					<a href="<?= Fj_NewMember ?>" class="btn-space btn registbtn bg-red color-white">
						<div class="registbtntext">
							無料求人サービスに登録
						</div>
						<div class="registbtnnote">
							豊富な案件数からあなたに合ったJOBをお届け！
						</div>
					</a>
				</div>

			</div>



			<!-- メイン３ -->
			<div class="section innerspace contentsbox descriptionboxwrap">
				<div class="descriptionbox descriptionimageright">
					<div class="descriptionimagebox">
						<img class="descriptionimage" src="<?= Fj_Img ?>support.png" alt="豊富な案件のイメージ画像">
					</div>
					<div class="descriptiontextbox">
						<h3 class="descriptiontextheadline">
							<span class="headline">
								<span class="headlinekanafont">Project | 豊富な案件</span>
							</span>
						</h3>
						<div class="descriptiontext">
							<p>弊社では営業を強化することにより、常に迅速にご希望に近い案件をご提案できるよう努めると同時に、事情により常駐出来ない技術者様に対し、在宅の案件もご紹介させていただいております。</p>
						</div>
						<div class="projectbtn-space projectbtn bg-yellow">
    						<a href="#top-bg" class="color-white">求人をCHECK!!</a>
    					</div>
					</div>
				</div>
			</div>






			<!-- メイン４ -->
			<div class="top-main top-innerspace top-main-end">
				<div id ="top-form">
					<div>
						<img src="<?= Fj_Img ?>form.png" alt="お問合せフローのイメージ画像">
					</div>


					<div>
    					<a href="<?= Fj_Form ?>" class="btn-space btn registbtn bg-red color-white">
    						<div class="registbtntext">
    							お問い合わせはコチラ
    						</div>
    						<div class="registbtnnote">
                            豊富な案件数からあなたに合ったJOBをお届け！
                        </div>
    					</a>
					</div>
				</div>
			</div>

			<!-- メイン１ -->
			<div id="top-bg" style="padding-top: 60px">
				<div class="color-white top-catch"  id="top-catch-serch">
    				<img src="<?= Fj_Img ?>searchmark.png">
    				<div class="top-catch-main">Search</div>
				</div>

				<form action="<?= Fj_List ?>" method="post" name="searchform">
    				<div class="searchbox bg-white" id="searchbox">
                        <!-- 検索バー -->
        				<div class="innerspace" style="text-align: left">

    						<div class="search-clear-btn-wrap">
        						<input type="button" value="検索条件をリセット" class="btn clear-btn bg-white color-bluegreen" onclick="allclear(false);">
        					</div>

        					<div class="search-title bg-bluegray">エリア</div>
        					<div style="margin-bottom:20px;">
        						<input type="checkbox" name="area_no[]" value="<?= Osaka ?>" id="Osaka" />
                                <label for="Osaka" class="check_css">大阪</label>
                                <input type="checkbox" name="area_no[]" value="<?= Kyoto ?>" id="Kyoto" />
                                <label for="Kyoto" class="check_css">京都</label>
                                <input type="checkbox" name="area_no[]" value="<?= Nara ?>" id="Nara" />
                                <label for="Nara" class="check_css">奈良</label>
                                <input type="checkbox" name="area_no[]" value="<?= Wakayama ?>" id="Wakayama" />
                                <label for="Wakayama" class="check_css">和歌山</label>
                                <input type="checkbox" name="area_no[]" value="<?= Hyogo ?>" id="Hyogo" />
                                <label for="Hyogo" class="check_css">兵庫</label>
                                <input type="checkbox" name="area_no[]" value="<?= Shiga ?>" id="Shiga" />
                                <label for="Shiga" class="check_css">滋賀</label>
        					</div>
        					<hr class="list-hr">
        					<div class="search-title bg-bluegray">フェーズ</div>
                            <div style="margin-bottom:20px;">
                                <input type="checkbox" name="phase_no[]" value="<?= Planning ?>" id="Planning" />
                                <label for="Planning" class="check_css">企画・提案</label>
                                <input type="checkbox" name="phase_no[]" value="<?=Requirement ?>" id="Requirement" />
                                <label for="Requirement" class="check_css">要件定義</label>
                                <input type="checkbox" name="phase_no[]" value="<?= Basic ?>" id="Basic" />
                                <label for="Basic" class="check_css">基本設計</label>
                                <input type="checkbox" name="phase_no[]" value="<?= Detail ?>" id="Detail" />
                                <label for="Detail" class="check_css">詳細設計</label>
                                <input type="checkbox" name="phase_no[]" value="<?= Develop ?>" id="Develop" />
                                <label for="Develop" class="check_css">構築・開発</label>
                                <input type="checkbox" name="phase_no[]" value="<?= Test ?>" id="Test" />
                                <label for="Test" class="check_css">テスト</label>
                                <input type="checkbox" name="phase_no[]" value="<?= Operation ?>" id="Operation" />
                                <label for="Operation" class="check_css">運用</label>
                                <input type="checkbox" name="phase_no[]" value="<?= OtherPhase ?>" id="OtherPhase" />
                                <label for="OtherPhase" class="check_css">その他</label>
                            </div>
                            <hr class="list-hr">
                            <div class="search-title bg-bluegray">キーワード</div>
                            <div>
                                <input type="checkbox" name="keyword[]" value="<?= JavaScript ?>" id="JavaScript" />
                                <label for="JavaScript" class="check_css">JavaScript</label>
                                <input type="checkbox" name="keyword[]" value="<?= Ruby ?>" id="Ruby" />
                                <label for="Ruby" class="check_css">Ruby</label>
                                <input type="checkbox" name="keyword[]" value="<?= Python ?>" id="Python" />
                                <label for="Python" class="check_css">Python</label>
                                <input type="checkbox" name="keyword[]" value="<?= PHP ?>" id="PHP" />
                                <label for="PHP" class="check_css">PHP</label>
                                <input type="checkbox" name="keyword[]" value="<?=Java ?>" id="Java" />
                                <label for="Java" class="check_css">Java</label>
                                <input type="checkbox" name="keyword[]" value="<?= Androidjava ?>" id="Androidjava" />
                                <label for="Androidjava" class="check_css">Android-java</label>
                                <input type="checkbox" name="keyword[]" value="<?= SQL ?>" id="SQL" />
                                <label for="SQL" class="check_css">SQL</label>
                                <input type="checkbox" name="keyword[]" value="<?= PLSQL ?>" id="PLSQL" />
                                <label for="PLSQL" class="check_css">PL/SQL</label>
                                <input type="checkbox" name="keyword[]" value="<?= C ?>" id="C" />
                                <label for="C" class="check_css">C言語</label>
                                <input type="checkbox" name="keyword[]" value="<?= Cplus ?>" id="Cplus" />
                                <label for="Cplus" class="check_css">C++</label>
                                <input type="checkbox" name="keyword[]" value="<?= VCplus ?>" id="VCplus" />
                                <label for="VCplus" class="check_css">VC++</label>
                                <input type="checkbox" name="keyword[]" value="<?= CNET ?>" id="CNET" />
                                <label for="CNET" class="check_css">C#.NET</label>
                                <input type="checkbox" name="keyword[]" value="<?= ObjectiveC ?>" id="ObjectiveC" />
                                <label for="ObjectiveC" class="check_css">Objective-C</label>
                                <input type="checkbox" name="keyword[]" value="<?= Swift ?>" id="Swift" />
                                <label for="Swift" class="check_css">Swift</label>
                                <input type="checkbox" name="keyword[]" value="<?= Oracle ?>" id="Oracle" />
                                <label for="Oracle" class="check_css">Oracle</label>
                                <input type="checkbox" name="keyword[]" value="<?= HTML ?>" id="HTML" />
                                <label for="HTML" class="check_css">HTML</label>
                                <input type="checkbox" name="keyword[]" value="<?= VB ?>" id="VB" />
                                <label for="VB" class="check_css">VB</label>
                                <input type="checkbox" name="keyword[]" value="<?= VBNET ?>" id="VBNET" />
                                <label for="VBNET" class="check_css">VB.NET</label>
                                <input type="checkbox" name="keyword[]" value="<?= VBA ?>" id="VBA" />
                                <label for="VBA" class="check_css">VBA</label>
                                <input type="checkbox" name="keyword[]" value="<?= COBOL ?>" id="COBOL" />
                                <label for="COBOL" class="check_css">COBOL</label>
                            </div>
                            <div>
                                <input type="checkbox" name="keyword[]" value="<?= Windows ?>" id="Windows" />
                                <label for="Windows" class="check_css">Windows</label>
                                <input type="checkbox" name="keyword[]" value="<?= UnixLinux ?>" id="UnixLinux" />
                                <label for="UnixLinux" class="check_css">Unix/Linux</label>
                                <input type="checkbox" name="keyword[]" value="<?= AIX ?>" id="AIX" />
                                <label for="AIX" class="check_css">AIX</label>
                                <input type="checkbox" name="keyword[]" value="<?= AWS ?>" id="AWS" />
                                <label for="AWS" class="check_css">AWS</label>
                                <input type="checkbox" name="keyword[]" value="<?= Solaris ?>" id="Solaris" />
                                <label for="Solaris" class="check_css">Solaris</label>
                            </div>
                            <div>
                                <input type="checkbox" name="keyword[]" value="<?= OtherLanguage ?>" id="OtherLanguage" />
                                <label for="OtherLanguage" class="check_css">その他</label>
        					</div>

        					<div  style="margin-top:20px;">
        						<input type="text" value="" name="freeword" placeholder="フリーワード検索（例：Java 大阪市）" class="freeword">
        					</div>

        				</div>

    					<button class="reset-btn searchbtn bg-yellow color-white" type="submit" name="submit">
    						<span class="searcharrow">検索</span><span class="searcharrow2"></span><span class="searcharrow2"></span><span class="searcharrow3">求人をCHECK!!</span>
    					</button>

    				</div>

				</form>
			</div>





 		</div>

        <!-- フッター -->
		<?php include (FJ_Footer);?>		

	</div>





</body>
</html>

