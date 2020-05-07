<?php
//---------------------------------------------------------
//  案件新規登録画面  |  最終更新日:2018/09/18
//---------------------------------------------------------
//  新規案件の入力画面
//  入力チェック後OKならセッションに格納して
//  management_projectAdd.phpへ遷移
//  NGならエラーメッセージ表示
//--------------------------------------------------------


//セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード
$ret = include_once(FJ_Mg_Function);

$errmsg = array (); 		                  // エラーメッセージ

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!strlen($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$errflg = OK;                        // エラーフラグOFF

$subject            = "";			// 件名
$subject_error      = "";		    // 件名入力エラーOFF
$post_date          = "";           // 掲載開始日
$post_end_date      = "";           // 掲載終了日
$price_lower        = "";           // 契約単価(min)
$price_upper        = "";           // 契約単価(max)
$price_lower_error  = "";           // 契約単価(min)入力エラーOFF
$price_upper_error  = "";           // 契約単価(max)入力エラーOFF
$period_lower       = "";           // 契約開始日
$period_upper       = "";           // 契約終了日
$period_lower_error = "";           // 契約開始日入力エラーOFF
$period_upper_error = "";           // 契約終了日入力エラーOFF
$phase_no           = "";       	// フェーズ
$phase_error        = "";           // フェーズ入力エラーOFF
$area_no            = "";	        // エリア
$location           = "";           // 勤務地
$location_error     = "";           // 勤務地入力エラーOFF
$content            = "稼働時間："."\r\n"."必須スキル：". "\r\n"."あれば尚可：";           // コメント
$content_error      = "";           // コメント入力エラーOFF
$screening          = "面談";       // 選考方法
$screening_error    = "";           // 選考方法入力エラーOFF
$keyword            = "";           // スキルキーワード
$keyword_error      = "";           // スキルキーワード入力エラーOFF
$member_flg         = OK;            // 案件を見られるユーザー
$reflect_flg        = OK;            // サイト公開情報


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 確認ボタンが押された場合
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"]))
{


    // フォーム内容取り出し
    $subject            = htmlspecialchars ($_POST["subject"], ENT_QUOTES);         // 件名
    $post_date          = $_POST["post_date"];                                      // 掲載開始日
    $post_end_date      = $_POST["post_end_date"];                                  // 掲載終了日
    $price_lower        = htmlspecialchars ($_POST["price_lower"], ENT_QUOTES);     // 契約単価(min)
    $price_upper        = htmlspecialchars ($_POST["price_upper"], ENT_QUOTES);     // 契約単価(max)
    $period_lower       = $_POST["period_lower"];                                   // 契約期間
    $period_upper       = $_POST["period_upper"];                                   // 契約期間
    $area_no            = $_POST["area_no"];                                        // エリア
    $location           = htmlspecialchars ($_POST["location"], ENT_QUOTES);        // 勤務地
    $content            = htmlspecialchars ($_POST["content"], ENT_QUOTES);         // コメント
    $screening          = htmlspecialchars ($_POST["screening"], ENT_QUOTES);       // 選考方法
    $member_flg         = $_POST["memberflg"];                                      // この案件を見られるユーザー
    $reflect_flg        = $_POST["reflect_flg"];                                    // サイトの反映未反映


    // --入力チェック--------------------------------------------------------

    if (inputCheck($subject)){
        $subject_error = "<br />".subjectError;
        $errflg = NG;
    }

    if (inputCheck($price_lower)){
        $price_lower_error = "<br />".PriceLowerError01;
        $errflg = NG;
    }

    elseif (pregCheck_price($price_lower))
    {
        $price_lower_error = "<br />".PriceLowerError02;
        $errflg = NG;
    }

    if (inputCheck($price_upper)){
        $price_upper_error = "<br />".PriceUpperError01;
        $errflg = NG;
    }
    elseif (pregCheck_price($price_upper))
    {
        $price_upper_error = "<br />".PriceUpperError02;
        $errflg = NG;
    }

    if (inputCheck($location)){
        $location_error = "<br />".LocationError;
        $errflg = NG;
    }
    if (inputCheck($content)){
        $content_error = "<br />".CommentError;
        $errflg = NG;
    }
    if (inputCheck($screening)){
        $screening_error = "<br />".ScreeningError;
        $errflg = NG;
    }

    if (isset($_POST["phase_no"]))
    {
        $phase_no = $_POST["phase_no"];
    }
    else
    {
        $phase_no = "";
        $phase_error = "<br />".PhaseError;
        $errflg = NG;
    }

    if (isset($_POST["keyword"]))
    {
        $keyword = $_POST["keyword"];
    }
    else
    {
        $keyword = "";
        $keyword_error = "<br />".KeywordError;
        $errflg = NG;
    }


    // エラーがなければ確認画面へ遷移
    if (!$errflg)
    {

        //次画面表示のためのSWICH文(function.php)===========================
        // フェーズ
        $phasestr = phaseStr($phase_no);
        // エリア
        $areastr =  areaStr($area_no);
        // キーワード
        $keywordstr = keywordStr($keyword);
        // 会員フラグ
        $memberstr = memberdStr($member_flg);
        // 反映フラグ
        $reflectstr = reflectStr($reflect_flg);


        //==SESSIONに詰めて遷移先へおくる================================================

        $_SESSION["newproject"]["subject"]          = $subject;
        $_SESSION["newproject"]["post_date"]        = $post_date;
        $_SESSION["newproject"]["post_end_date"]    = $post_end_date;
        $_SESSION["newproject"]["price_lower"]      = $price_lower;
        $_SESSION["newproject"]["price_upper"]      = $price_upper;
        $_SESSION["newproject"]["period_lower"]     = $period_lower;
        $_SESSION["newproject"]["period_upper"]     = $period_upper;
        $_SESSION["newproject"]["phase_no"]         = $phase_no;
        $_SESSION["newproject"]["area_no"]          = $area_no;
        $_SESSION["newproject"]["location"]         = $location;
        $_SESSION["newproject"]["content"]          = $content;
        $_SESSION["newproject"]["screening"]        = $screening;
        $_SESSION["newproject"]["memberflg"]        = $member_flg;
        $_SESSION["newproject"]["reflect_flg"]      = $reflect_flg;
        $_SESSION["newproject"]["keyword"]          = $keyword;
        $_SESSION["newproject"]["keywordstr"]       = $keywordstr;
        $_SESSION["newproject"]["areastr"]          = $areastr;
        $_SESSION["newproject"]["phasestr"]         = $phasestr;
        $_SESSION["newproject"]["member_flg"]       = $memberstr;
        $_SESSION["newproject"]["reflectstr"]       = $reflectstr;

        header ("Location:" .Mg_NewProjectAdd);
        exit();

    }

}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 戻るボタンで戻ってきた場合
if (isset($_POST["return"]))
{

    $subject        = $_SESSION["newproject"]["subject"];
    $post_date      = $_SESSION["newproject"]["post_date"];
    $post_end_date  = $_SESSION["newproject"]["post_end_date"];
    $price_lower    = $_SESSION["newproject"]["price_lower"];
    $price_upper    = $_SESSION["newproject"]["price_upper"];
    $period_lower   = $_SESSION["newproject"]["period_lower"];
    $period_upper   = $_SESSION["newproject"]["period_upper"];
    $phase_no       = $_SESSION["newproject"]["phase_no"];
    $area_no        = $_SESSION["newproject"]["area_no"];
    $location       = $_SESSION["newproject"]["location"];
    $content        = $_SESSION["newproject"]["content"];
    $screening      = $_SESSION["newproject"]["screening"];
    $member_flg     = $_SESSION["newproject"]["memberflg"];
    $reflect_flg    = $_SESSION["newproject"]["reflect_flg"];
    $keyword        = $_SESSION["newproject"]["keyword"];

}


?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>案件情報</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= Mg_bootstrap_css ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= Mg_dashboard_css ?>" rel="stylesheet">

    <!-- management=common CSS -->
    <link href="<?= Mg_Common_css ?>" rel="stylesheet">
    
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
 		<!-- -------------- ヘッダー -------------- -->
		<?php include (Mg_Header);?>
		<!-- ------------------------------------------ -->

    <div class="container-fluid">
      <div class="row">

 		<!-- -------------- サイドバー -------------- -->
		<?php include (Mg_Sidebar);?>
		<!-- ------------------------------------------ -->

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">案件新規登録画面</h1>
          </div>

<!-- ここにコンテンツ -->

	<p>案件を新しく登録される場合はこちらに入力後、確認ボタンを押してください</p>
	<hr>
	<div id="projectwrap">

        <form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
        	<label>
        		<span class="label-text input_items">件名</span><br />
        		<input type="text" name="subject" id="subject" value="<?= $subject ?>"  placeholder="【キーワード】件名" style="width:500px;">
        		<span id="subject_error" class="error_m"><?= $subject_error ?></span>
        	</label>
        	<br />
        	<label>
        		<span class="label-text input_items">契約単価</span><br />
        		<input type="text" name="price_lower" value="<?= $price_lower ?>" placeholder="半角数字">
        	</label>
			<label>
				万円～
            	<input type="text" name="price_upper" value="<?= $price_upper ?>" placeholder="半角数字" >
            	万円
            </label>
            <span id="price_lower_error" class="error_m"><?= $price_lower_error ?></span>
            <span id="price_upper_error" class="error_m"><?= $price_upper_error ?></span>
			<br />

        	<label>
        		<span class="label-text input_items">契約期間</span><br />
        		<select name="period_lower">
                	<option value="<?= Sameday ?>" <?php if($period_lower == Sameday){echo 'selected';}?>><?= Sameday ?></option>
                	<option value="<?= Jan ?>" <?php if($period_lower == Jan){echo 'selected';}?>><?= Jan ?></option>
                	<option value="<?= Feb ?>" <?php if($period_lower == Feb){echo 'selected';}?>><?= Feb ?></option>
                	<option value="<?= Mar ?>" <?php if($period_lower == Mar){echo 'selected';}?>><?= Mar ?></option>
                	<option value="<?= Apr ?>" <?php if($period_lower == Apr){echo 'selected';}?>><?= Apr ?></option>
                	<option value="<?= May ?>" <?php if($period_lower == May){echo 'selected';}?>><?= May ?></option>
                	<option value="<?= Jun ?>" <?php if($period_lower == Jun){echo 'selected';}?>><?= Jun ?></option>
                	<option value="<?= Jul ?>" <?php if($period_lower == Jul){echo 'selected';}?>><?= Jul ?></option>
                	<option value="<?= Aug ?>" <?php if($period_lower == Aug){echo 'selected';}?>><?= Aug ?></option>
                	<option value="<?= Sep ?>" <?php if($period_lower == Sep){echo 'selected';}?>><?= Sep ?></option>
                	<option value="<?= Oct ?>" <?php if($period_lower == Oct){echo 'selected';}?>><?= Oct ?></option>
                	<option value="<?= Nov ?>" <?php if($period_lower == Nov){echo 'selected';}?>><?= Nov ?></option>
                	<option value="<?= Dec ?>" <?php if($period_lower == Dec){echo 'selected';}?>><?= Dec ?></option>
                </select>
        	</label>
        	<label>
			～
        		<select name="period_upper">
                	<option value="<?= Longterm ?>" <?php if($period_upper == Sameday){echo 'selected';}?>><?= Longterm ?></option>
                	<option value="<?= Jan ?>" <?php if($period_upper == Jan){echo 'selected';}?>><?= Jan ?></option>
                    <option value="<?= Feb ?>" <?php if($period_upper == Feb){echo 'selected';}?>><?= Feb ?></option>
                    <option value="<?= Mar ?>" <?php if($period_upper == Mar){echo 'selected';}?>><?= Mar ?></option>
                    <option value="<?= Apr ?>" <?php if($period_upper == Apr){echo 'selected';}?>><?= Apr ?></option>
                    <option value="<?= May ?>" <?php if($period_upper == May){echo 'selected';}?>><?= May ?></option>
                    <option value="<?= Jun ?>" <?php if($period_upper == Jun){echo 'selected';}?>><?= Jun ?></option>
                    <option value="<?= Jul ?>" <?php if($period_upper == Jul){echo 'selected';}?>><?= Jul ?></option>
                    <option value="<?= Aug ?>" <?php if($period_upper == Aug){echo 'selected';}?>><?= Aug ?></option>
                    <option value="<?= Sep ?>" <?php if($period_upper == Sep){echo 'selected';}?>><?= Sep ?></option>
                    <option value="<?= Oct ?>" <?php if($period_upper == Oct){echo 'selected';}?>><?= Oct ?></option>
                    <option value="<?= Nov ?>" <?php if($period_upper == Nov){echo 'selected';}?>><?= Nov ?></option>
                    <option value="<?= Dec ?>" <?php if($period_upper == Dec){echo 'selected';}?>><?= Dec ?></option>
                </select>
        	</label>
        	<span id="period_lower_error" class="error_m"><?= $period_lower_error ?></span>
            <span id="period_upper_error" class="error_m"><?= $period_upper_error ?></span>
			<br />
						<!-- フェーズ -->

            <span class="label-text input_items">フェーズ（複数選択可）</span><br />
            <label>
                <input type="checkbox" name="phase_no[]" value="<?= Planning ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Planning){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">企画・提案</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Requirement ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Requirement){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">要件定義</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Basic ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Basic){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">基本設計</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Detail ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Detail){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">詳細設計</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Develop ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Develop){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">構築・開発</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Test ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Test){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">テスト</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= Operation ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == Operation){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">運用</span>
    		</label>

    		<label>
                <input type="checkbox" name="phase_no[]" value="<?= OtherPhase ?>"
                    <?php
                    if ($phase_no == ""){}
                    else{foreach ($phase_no as $row){if($row == OtherPhase){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">その他</span>
    		</label>

    		<span id="phase_error" class="error_m"><?= $phase_error ?></span>
            <br />
        	<label>
        		<span class="label-text input_items">エリア</span><br />
        		<select name="area_no">
    				<option value="<?= Osaka ?>" <?php if($area_no == Osaka){echo 'selected';}?>>大阪</option>
                	<option value="<?= Kyoto ?>" <?php if($area_no == Kyoto){echo 'selected';}?>>京都</option>
                	<option value="<?= Nara ?>" <?php if($area_no == Nara){echo 'selected';}?>>奈良</option>
                	<option value="<?= Wakayama ?>" <?php if($area_no == Wakayama){echo 'selected';}?>>和歌山</option>
                	<option value="<?= Hyogo ?>" <?php if($area_no == Hyogo){echo 'selected';}?>>兵庫</option>
                	<option value="<?= Shiga ?>" <?php if($area_no == Shiga){echo 'selected';}?>>滋賀</option>
                </select>
        	</label>
        	<br />
        	<label>
        		<span class="label-text input_items">勤務地</span><br />
        		<input type="text" name="location" value="<?= $location ?>">
        		<span id="location_error" class="error_m"><?= $location_error ?></span>
        	</label>
        	<br />
			<label>
        		<span class="label-text input_items">内容</span><br />
        		<textarea name="content" style="width:500px;height:100px;" placeholder= "稼働時間：&#13;&#10;必須スキル：&#13;&#10;あれば尚可：&#13;&#10;"><?= $content ?></textarea>
        		<span id="content_error" class="error_m"><?= $content_error ?></span>
        	</label>
        	<br />
			<label>
        		<span class="label-text input_items">選考方法</span><br />
        		<input type="text" name="screening" value="<?= $screening ?>">
        		<span id="screening_err" class="error_m"><?= $screening_error ?></span>
        	</label>
        	<br />


        	<!-- キーワード -->

			<span class="label-text input_items">キーワード（複数選択可）</span><br />

            <!-- JavaScript -->
            <label>
                <input type="checkbox" name="keyword[]" value="<?= JavaScript ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == JavaScript){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">JavaScript</span>
    		</label>

    		<!-- Ruby -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Ruby ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Ruby){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Ruby</span>
    		</label>

			<!-- Python -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Python ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Python){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Python</span>
    		</label>

			<!-- PHP -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= PHP ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == PHP){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">PHP</span>
    		</label>

			<!-- Java -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Java ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Java){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Java</span>
    		</label>
<br />
			<!-- C -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= C ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == C){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">C</span>
    		</label>

			<!-- C++ -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Cplus ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Cplus){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">C++</span>
    		</label>

			<!-- VC++ -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= VCplus ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == VCplus){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">VC++</span>
    		</label>

			<!-- C#.NET -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= CNET ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == CNET){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">C#.NET</span>
    		</label>

			<!-- Objective-C -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= ObjectiveC ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == ObjectiveC){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Objective-C</span>
    		</label>
<br />
			<!-- Swift -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Swift ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Swift){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Swift</span>
    		</label>

			<!-- SQL -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= SQL ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == SQL){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">SQL</span>
    		</label>

			<!-- PL/SQL -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= PLSQL ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == PLSQL){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">PL/SQL</span>
    		</label>

			<!-- Android-java -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Androidjava ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Androidjava){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Android-java</span>
    		</label>

<br />
			<!-- VB -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= VB ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == VB){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">VB</span>
    		</label>


			<!-- VB.NET -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= VBNET ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == VBNET){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">VB.NET</span>
    		</label>


			<!-- VBA -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= VBA ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == VBA){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">VBA</span>
    		</label>

			<!-- COBOL -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= COBOL ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == COBOL){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">COBOL</span>
    		</label>


			<!-- Oracle -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Oracle ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Oracle){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Oracle</span>
    		</label>

			<!-- HTML -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= HTML ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == HTML){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">HTML</span>
    		</label>
<br />
			<!-- windows -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Windows ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Windows){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Windows</span>
    		</label>


			<!-- Unix/Linux -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= UnixLinux ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == UnixLinux){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Unix / Linux</span>
    		</label>


			<!-- AIX -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= AIX ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == AIX){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">AIX</span>
    		</label>


			<!-- AWS -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= AWS ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == AWS){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">AWS</span>
    		</label>


			<!-- Solaris -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= Solaris ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == Solaris){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">Solaris</span>
    		</label>

<br />
			<!-- その他 -->
    		<label>
                <input type="checkbox" name="keyword[]" value="<?= OtherLanguage ?>"
                    <?php
                    if ($keyword == ""){}
                    else{foreach ($keyword as $row){if($row == OtherLanguage){echo 'checked="checked"';}}}
                    ?>
    			><span class="label-text kw">その他</span>
    		</label>

			<span id="keyword_error" class="error_m"><?= $keyword_error ?></span>
			<br />

        	<label>
        		<span class="label-text input_items">この案件を見られるユーザー</span><br />
        		<select name="memberflg">
        			<option value="<?= Guest ?>" <?php if($member_flg == 0){echo 'selected';}?>>全て</option>
        			<option value="<?= Member ?>" <?php if($member_flg == 1){echo 'selected';}?>>会員のみ</option>
                </select>
        	</label>
        	<br />
        	<label>
        		<span class="label-text input_items">サイト反映</span><br />
        		<select name="reflect_flg">
        			<option value="<?= Release ?>" <?php if($reflect_flg == 0){echo 'selected';}?>>反映</option>
        			<option value="<?= NonRelease ?>" <?php if($reflect_flg == 1){echo 'selected';}?>>未反映</option>
                </select>
        	</label>
        	<br />

        	<label>
        		<span class="label-text input_items">掲載期間</span><br />
        		<input type="date" name="post_date" value="<?php if($post_date==""){$post_date=date("Y-m-d");}echo $post_date ?>" >
        	</label>

			<label>
				～
            	<input type="date" name="post_end_date" value="<?php if($post_end_date==""){$post_end_date=date("Y-m-d");}echo $post_end_date ?>" >
            </label>
        	<br /><br />

			<button type='submit' name='insert' value='insert' id='btn' class='btn btn-sm btn-warning'>入力内容を確認</button>

        </form>
        <br />
        <form action="<?= Mg_Top ?>" method="post">
        	<button type='submit' name='return' value='' id='btn'  class='btn btn-sm btn-info'>案件情報一覧へ戻る</button>
        </form>
	</div>

	<?php //設定情報エラー
		if (!empty($errmsg))
		{
			foreach ($errmsg as $val)
			{
				echo $val;
			}
		}
	?>
<!-- コンテンツここまで -->
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?= Mg_slim_js ?>"><\/script>')</script>
    <script src="<?= Mg_popper_js ?>"></script>
    <script src="<?= Mg_bootstrap_js ?>"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>


  </body>
</html>

