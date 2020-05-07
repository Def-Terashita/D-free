<?php
//---------------------------------------------------------
//  表側共通ヘッダーナビゲーション  |  最終更新日:2018/07/27
//---------------------------------------------------------
// ログイン状態でこんにちは○○さんと表記
// ログインボタンはログアウトボタンに表示変更
// 各ページへ遷移
// ログアウトでセッション切断
//---------------------------------------------------------


// 基本
$headermsg = "<div class='navbtn bg-yellow'><a href='". Fj_LogIn."'>ログイン</a></div><div class='clear'></div>";
$headermsg_sp = "<div class='navbtn bg-yellow'><a href='". Fj_LogIn."'>ログイン</a></div><div class='clear'></div>";
$headermsg_sp_user = "";



// ログインしていたら基本部分の表示変更
if (isset ($_SESSION["name"])){
    $headermsg = "<form action='". Fj_LogOut. "' method='post'>";
    $headermsg .= "<div id='login-msg'><span>こんにちは、". $_SESSION['name']. "さん</span>";
    $headermsg .= "<input type='submit' name='logout' value='ログアウト' id='logout' class='bg-yellow'/></div><div class='clear'></div>";
    $headermsg .="</form>";

    // -------------------------------------------------------------------------------------------------

    $headermsg_sp = "<form action='". Fj_LogOut. "' method='post'>";
    $headermsg_sp .= "<div id='login-msg'><input type='submit' name='logout' value='ログアウト' id='logout'  class='bg-yellow'/></div><div class='clear'></div>";
    $headermsg_sp .="</form>";
    $headermsg_sp_user = "<div style='text-align:right;margin-right:5px;'>こんにちは、". $_SESSION['name']. "さん</div>";

}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::




?>
<div class="nav bg-white" id="navi">
    <!-- 休業お知らせ 
    <p id="holidy_info">・・・ゴールデンウィーク休業のお知らせ・・・<br />平素よりご利用いただき誠にありがとうございます。<br />当サイトは4月27日(土)から5月6日(月)まで休業とさせていただきます。休業中にいただきましたお問い合わせは、4月7日(火)
    より順次対応させていただきます。ご不便をおかけいたしますが何卒よろしくお願い申し上げます。</p>-->
    <nav>

        <div class="nav-contact bg-pink color-white">
    		<a class="color-white" href="tel:0666439305">TEL 06-6643-9305</a> / <a  class="color-white" href="<?= Fj_Form ?>">MAIL info@f-d-free.net</a>
    	</div>

	    <!-- スマホ用ナビゲーション -->
	    <div id="navi-sp">
	    	<div style="background-color:white;padding:5px 0;">
                <div id='nav-drawer' style='float:left;'>
                    <input id='nav-input' type='checkbox' class='nav-unshown'>
                    <label id='nav-open' for='nav-input'><span></span></label>
                    <label class='nav-unshown' id='nav-close' for='nav-input'></label>
                    <div id='nav-content' class='bg-white'>
                        <!--  ナビゲーション中身 -->
                        <div id="sp-title" ><a href="<?= Fj_Top ?>#navi"><img alt="D-freeのロゴ" src="<?= Fj_Img ?>D-free横.png"></a></div>
                	    <ul>
                	        <li><a href="<?= Fj_Top ?>#searchbox">仕事を探す</a></li>
                	        <li><a href="<?= Fj_Service ?>">初めての方へ</a></li>
                	        <li><a href="<?= Fj_FAQ ?>">FAQ</a></li>
                	        <li><a href="<?= Fj_Voice ?>">口コミ</a></li>
                	        <li><a href="<?= Fj_CorporateInfo ?>">会社概要</a></li>
                	        <li><a href="<?= Fj_PrivacyPolicy ?>">プライバシーポリシ</a></li>
                	        <li><a href="<?= Fj_CorporateInfo ?>#map">アクセス</a></li>
                	        <li><a href="<?= Fj_Form ?>">お問い合わせ</a></li>
                	    	<li><a href="<?= Fj_NewMember ?>">新規会員登録</a></li>
                	    </ul>
                        <!-- 中身ここまで -->
                    </div>

                </div>
                <div style='text-align:right;margin-right:5px;'>
                	<?= $headermsg_sp_user ?>
                </div>
                <div style='margin-right:5px;'><?= $headermsg_sp ?></div>
            </div>
	    </div>
	    <!-- スマホ用ここまで -->

	    <!-- PC・タブレット用ナビゲーション -->
	    <div id="navi-pc">
    	    <div>
    		    <?= $headermsg ?>
    	    </div>
    	    <div style="width:100%;">
        	    <div id="title">
        	    	<a href="<?= Fj_Top ?>"><img alt="D-freeのロゴ" src="<?= Fj_Img ?>D-free横.png" style="width:100%;"></a>
        	    </div>
                <font  style="text-align: left" size="5"　face="ＭＳ Ｐゴシック,ＭＳ ゴシック">　～ フリーランスのための情報サイト ～</font>
        	    <div>
            	    <ul id="list">
            	    	<li><a href="<?= Fj_Service ?>"><img alt="初めての方への画像" src="<?= Fj_Img ?>h1.png">初めての方へ</a></li>
            	        <li><a href="<?= Fj_Top ?>#searchbox"><img alt="仕事を探すの画像" src="<?= Fj_Img ?>h2.png">仕事を探す</a></li>
            	        <li><a href="<?= Fj_FAQ ?>"><img alt="FAQの画像" src="<?= Fj_Img ?>h3.png">FAQ</a></li>
            	        <li><a href="<?= Fj_Form ?>"><img alt="お問い合わせの画像" src="<?= Fj_Img ?>h4.png">お問い合わせ</a></li>
            	    </ul>
        	    </div>

        	    <div class="clear"></div>
    	    </div>
    	</div>

	    <!-- ここまで -->

    </nav>
</div>






