<?php

//===================================================================
// サーバ種別判定
//===================================================================

// サーバ種別定数：開発・テストサーバ
// OS : Windows
define("Deve_Test_Serve", "Development Test Server");

// サーバ種別定数：運用・本番サーバ
// エックスサーバー : sv8020.xserver.jp
define("Operation_Serve", "Operation Server");

// サーバ種別判定
if(FALSE === strpos($_SERVER["SERVER_NAME"], "f-d-free.net"))
{
	// サーバ種別：開発・テストサーバ
	define("Serve_Type", Deve_Test_Serve);
}

else
{
	// 運用・本番サーバ
	define("Serve_Type", Operation_Serve);
}


//===================================================================
// ドキュメント・ルート 定数
//===================================================================
if(Serve_Type === Deve_Test_Serve)
{
	// 開発・テストサーバ
    // SERVER : localhost
    define("Document_Root", $_SERVER["DOCUMENT_ROOT"]."/D-free/fj/");
}
else
{
	// 運用・本番サーバ
	// エックスサーバー : sv8020.xserver.jp
    define("Document_Root", $_SERVER["DOCUMENT_ROOT"]."/fj/");
}


//===================================================================
// 本番環境でPHPエラー表示しない
//===================================================================
// ini_set("display_errors", 0);
// ini_set("log_errors", 1);
// ini_set("error_log", "syslog");


//------------------------------------------------------
//  メール送信用定数
//------------------------------------------------------

//--- 送信元(from)メールアドレス ---
define("Mail_FromAdress", 		"info@f-d-free.net");
define("Smt_PortNo",            "587");
define("Mail_FromName", 		"D-free");
define("Mail_Subj_user",		"【D-free】自動返送メール");
define("Mail_Subj_usercomp",	"【D-free】登録完了");
define("Mail_Subj_New",     	"【D-free】新規登録がありました。");
define("Mail_Subj_Form",	    "【D-free】お問い合わせが入りました。");

//------------------------------------------------------
//  画面ＵＲＬルート判定
//------------------------------------------------------
if(Serve_Type === Deve_Test_Serve)
{
	// 開発・テストサーバ
	define("Url_Root", "/D-free/fj/");
}
else
{
    // 運用・本番サーバ
	define("Url_Root", "/fj/");
}


//------------------------------------------------------
//  インクルード用定数
//------------------------------------------------------
//config.php
define("FJ_Config",             Document_Root ."config.php");
//関数
define("FJ_Mg_Function",        Document_Root ."inc/function.php");

//header.php
define("FJ_Header",             Document_Root ."inc/header.php");
//footer.php
define("FJ_Footer",             Document_Root ."inc/footer.php");

//管理側ヘッダー
define("Mg_Header",				Document_Root ."inc/management_header.php");
//管理側サイドバー
define("Mg_Sidebar",			Document_Root ."inc/management_sidebar.php");
//ハッシュ化用プログラム
define("FJ_Mg_Pass",			Document_Root ."inc/password.php");
//メール送信用プログラム
define("FJ_Mail",   			Document_Root ."inc/sendmail.php");

//------------------------------------------------------
//  画面ＵＲＬ定数
//------------------------------------------------------
// トップ画面
define("Fj_Top",				Url_Root ."../index.php");
// 案件情報一覧
define("Fj_List",				Url_Root ."contents/list.php");
// 案件情報（個別）
define("Fj_Project",			Url_Root ."contents/project.php");
// サービス
define("Fj_Service",			Url_Root ."contents/service.php");
// 口コミ
define("Fj_Voice",				Url_Root ."contents/voice.php");
// FAQ
define("Fj_FAQ",				Url_Root ."contents/question.php");
// 新規登録（入力）
define("Fj_NewMember",			Url_Root ."contents/newMember.php");
// 新規登録（完了）
define("Fj_MemberComp",			Url_Root ."contents/memberComp.php");
// ログイン
define("Fj_LogIn",				Url_Root ."contents/logIn.php");
// ログアウト
define("Fj_LogOut",				Url_Root ."contents/logOut.php");
// 会社概要
define("Fj_CorporateInfo",		Url_Root ."contents/company.php");
// プライバシーポリシ
define("Fj_PrivacyPolicy",		Url_Root ."contents/privacyPolicy.php");
// 情報セキュリティポリシ
define("Fj_SecurityPolicy",		Url_Root ."contents/securityPolicy.php");
// 問合せ（入力）
define("Fj_Form",				Url_Root ."contents/form.php");
// 問合せ（送信）
define("Fj_FormComp",			Url_Root ."contents/formComp.php");
// スタッフ
define("Fj_Staff",				Url_Root ."contents/staff.php");

//画像
define("Fj_Img",				Url_Root ."img/");




//管理側：ログイン画面
define("Mg_SignIn",				Url_Root ."management/management_signin.php");
//管理側：ログアウト
define("Mg_SignOut",			Url_Root ."management/management_signout.php");


//管理側：TOP画面（案件情報一覧）
define("Mg_Top",				Url_Root ."management/management_top.php");
//管理側：案件新規登録画面
define("Mg_NewProject",  		Url_Root ."management/management_newproject.php");
//管理側：案件新規登録画面
define("Mg_NewProjectAdd",  	Url_Root ."management/management_projectAdd.php");
//管理側：案件情報（個別編集）
define("Mg_Project",			Url_Root ."management/management_project.php");
//管理側：案件情報アップデート確認・更新ページ
define("Mg_ProjectUpdate",  	Url_Root ."management/management_projectEdit.php");


//管理側：ユーザー情報一覧
define("Mg_UserList",			Url_Root ."management/management_userList.php");
//管理側：ユーザー情報（編集入力）
define("Mg_User",				Url_Root ."management/management_user.php");
//管理側：ユーザー情報アップデート確認・更新ページ
define("Mg_UserUpdate",			Url_Root ."management/management_userEdit.php");
//管理側：ユーザー情報（削除）
define("Mg_UserDeleat",  		Url_Root ."management/management_userDeleat.php");


//管理側：管理者一覧
define("Mg_AdminLIST",  		Url_Root ."management/management_adminList.php");
//管理側：管理者新規登録
define("Mg_Admin",  			Url_Root ."management/management_adminAdd.php");
//管理側：管理者更新
define("Mg_AdminUpdate",  		Url_Root ."management/management_adminEdit.php");
//管理側：管理者情報削除
define("Mg_AdminDeleat",		Url_Root ."management/management_adminDeleat.php");




//------------------------------------------------------
//  CSS定数
//------------------------------------------------------
//ユーザー側：共通css
define("Fj_Common_css",		      	Url_Root ."css/common.css");
//ユーザー側：index.php用css
define("Fj_Top_css",    			Url_Root ."css/top.css");
//ユーザー側：TOPページ以外の各コンテンツページ用css
define("Fj_Contents_css",   		Url_Root ."css/contents.css");


//管理側：共通css(management_common.css)
define("Mg_Common_css",		        Url_Root ."css/management_common.css");
//管理側：テーブルソーター用css(slick-them.css)
define("Mg_Tablesoter_css",		    Url_Root ."css/tablesorter-style.css");
//管理側：テーブルソーター用css(jquery.tablesorter.pager.css)
define("Mg_Tablesoter_Pager_css",   Url_Root ."css/jquery.tablesorter.pager.css");
//管理側：ブートストラップ基本css(dashboard.css)
define("Mg_bootstrap_css",		Url_Root ."css/bootstrap.min.css");
//管理側：ログイン(signin.css)
define("Mg_signin_css",         Url_Root ."css/signin.css");
//管理側：レイアウト(dashboard.css)
define("Mg_dashboard_css",		Url_Root ."css/dashboard.css");



//------------------------------------------------------
//  JS定数
//------------------------------------------------------
//jquery-3.2.1.min.js
define("Fj_Jq321_js",			Url_Root ."js/jquery-3.2.1.min.js");
//jquery-migrate-3.0.0.min.js
define("Fj_Migrate_js",			Url_Root ."js/jquery-migrate-3.0.0.min.js");
//scroll.js
define("Fj_Scroll_js",			Url_Root ."js/scroll.js");
//テーブルソーター(jquery.tablesorter.min.js)
define("Mg_Tablesorter_js", 	            Url_Root ."js/jquery.tablesorter.min.js");
//テーブルソーター(jquery.metadata.js)
define("Mg_Tablesorter_Metadata_js",		Url_Root ."js/jquery.metadata.js");
//テーブルソーター(jquery.tablesorter.pager.js)
define("Mg_Tablesoter_Pager_js",			Url_Root ."js/jquery.tablesorter.pager.js");
//テーブルソーター(jquery.docs.js)※なくても動く
define("Mg_Tablesoter_Docs_js",			    Url_Root ."js/docs.js");
//テーブルソーター(jquery-latest.js)
define("Mg_Tablesoter_Latest_js",			Url_Root ."js/jquery-latest.js");
//ブートストラップ(jquery-slim.min.js)
define("Mg_slim_js",            Url_Root ."js/jquery-slim.min.js");
//ブートストラップ(popper.min.js)
define("Mg_popper_js",          Url_Root ."js/popper.min.js");
//ブートストラップ(bootstrap.min.js)
define("Mg_bootstrap_js",		Url_Root ."js/bootstrap.min.js");

//グーグルアナリティクス(googlanalytics)
define("Fj_GooglAnalytics_async",		"https://www.googletagmanager.com/gtag/js?id=UA-130283303-1");
//グーグルアナリティクス(googlanalytics)
define("Fj_GooglAnalytics",		Url_Root ."js/googlanalytics.js");



//------------------------------------------------------
//  変数定義
//------------------------------------------------------
//エリア(大阪)
define("Osaka",		         100);
//エリア(京都)
define("Kyoto",              101);
//エリア(奈良)
define("Nara",               102);
//エリア(和歌山)
define("Wakayama",           103);
//エリア(兵庫)
define("Hyogo",              104);
//エリア(滋賀)
define("Shiga",              105);


//契約期間(即日)
define("Sameday",           "即日");
//契約期間(長期)
define("Longterm",          "長期");
//契約期間(1月)
define("Jan",               "1月");
//契約期間(2月)
define("Feb",               "2月");
//契約期間(3月)
define("Mar",               "3月");
//契約期間(4月)
define("Apr",               "4月");
//契約期間(5月)
define("May",               "5月");
//契約期間(6月)
define("Jun",               "6月");
//契約期間(7月)
define("Jul",               "7月");
//契約期間(8月)
define("Aug",               "8月");
//契約期間(9月)
define("Sep",               "9月");
//契約期間(10月)
define("Oct",               "10月");
//契約期間(11月)
define("Nov",               "11月");
//契約期間(12月)
define("Dec",               "12月");



//フェーズ(企画・提案)
define("Planning",           100);
//フェーズ(要件定義)
define("Requirement",        101);
//フェーズ(基本設計)
define("Basic",              102);
//フェーズ(詳細設計)
define("Detail",             103);
//フェーズ(構築・開発)
define("Develop",            104);
//フェーズ(テスト)
define("Test",               105);
//フェーズ(運用)
define("Operation",          106);
//フェーズ(その他)
define("OtherPhase",         107);



//キーワード
define("JavaScript",        100);
//キーワード
define("Ruby",              101);
//キーワード
define("Python",            102);
//キーワード
define("PHP",               103);
//キーワード
define("Java",              104);
//キーワード
define("C",                 105);
//キーワード(C++)
define("Cplus",             106);
//キーワード(VC++)
define("VCplus",            107);
//キーワード(C#.NET)
define("CNET",              108);
//キーワード(Objective-C)
define("ObjectiveC",        109);
//キーワード
define("Swift",             110);
//キーワード
define("SQL",               111);
//キーワード(PL/SQL)
define("PLSQL",             112);
//キーワード(Android-java)
define("Androidjava",       113);
//キーワード
define("VB",                114);
//キーワード(VB.NET)
define("VBNET",             115);
//キーワード
define("VBA",               116);
//キーワード
define("COBOL",             117);
//キーワード
define("Oracle",            118);
//キーワード
define("HTML",              119);
//キーワード
define("Windows",           120);
//キーワード(Unix / Linux)
define("UnixLinux",         121);
//キーワード
define("AIX",               122);
//キーワード
define("AWS",               123);
//キーワード
define("Solaris",           124);
//キーワード(その他)
define("OtherLanguage",     125);


//案件表示範囲(すべてのユーザー)
define("Guest",             0);
//案件表示範囲(登録ユーザーのみ)
define("Member",            1);

//案件公開(公開)
define("Release",           0);
//案件公開(公開しない)
define("NonRelease",        1);


//最大記事数（1ページにいくつの記事を表示するか）
define("ARTICLE_MAX_NUM",   20);
//最初のページ番号（初めて表示したときに何ページ目を表示するか）
define("FIRST_VISIT_PAGE",  1);
//選択項目カウント
define("COUNT",             0);
//ソート新着順（デフォルト）
define("SORT_NEW",          0);
//ソート単価順順
define("SORT_PRICE",        1);

//ページャ（最大マス）
define("PAGE_BOX",          5);

//新着記事日数
define("NEW_DAY",          432000);

//エラーフラグ（OFF）
define("OK",                0);
//エラーフラグ（ON）
define("NG",                1);
//エラーフラグ（入力ミス）
define("InputError",             "入力内容に誤りがあります");
//エラーフラグ（名前）
define("NameError",              "お名前を入力してください");
//エラーフラグ（電話番号）
define("TelError01",             "電話番号を入力してください");
//エラーフラグ（電話番号）
define("TelError02",             "電話番号はハイフンを含む半角数字で正しく入力してください");
//エラーフラグ（メールアドレス）
define("MailError01",            "メールアドレスを入力してください");
//エラーフラグ（メールアドレス）
define("MailError02",            "メールアドレスを正しく入力してください");
//エラーフラグ（メールアドレス）
define("MailError03",            "確認用メールアドレスを入力してください");
//エラーフラグ（メールアドレス）
define("MailError04",            "確認用メールアドレスが一致しません");
//エラーフラグ（内容）
define("ContentError",           "内容を入力してください");
//エラーフラグ（パスワード）
define("PassError01",            "パスワードを入力してください");
//エラーフラグ（パスワード）
define("PassError02",            "パスワードは8～16文字の半角英数字で登録してください");
//エラーフラグ（パスワード）
define("PassError03",            "確認用パスワードを入力してください");
//エラーフラグ（パスワード）
define("PassError04",            "確認用パスワードが一致しません");
//エラーフラグ（パスワード）
define("PassError05",            "パスワードが違います");
//エラーフラグ（スキルカテゴリ）
define("SkillError",             "希望スキルを選択してください");
//エラーフラグ（現在のご職業）
define("JobError",             "現在のご職業を選択してください");
//エラーフラグ（希望就業時期）
define("DateError",             "ご希望の就業時期を選択してください");

//エラーフラグ（件名）
define("subjectError",           "件名を入力してください");
//エラーフラグ（契約単価MIN）
define("PriceLowerError01",      "契約単価（MIN）を入力してください");
//エラーフラグ（契約単価MIN）
define("PriceLowerError02",      "契約単価（MIN）は半角数字で入力してください");
//エラーフラグ（契約単価MAX）
define("PriceUpperError01",      "契約単価（MAX）を入力してください");
//エラーフラグ（契約単価MAX）
define("PriceUpperError02",      "契約単価（MAX）は半角数字で入力してください");
//エラーフラグ（勤務地）
define("LocationError",          "勤務地を入力してください");
//エラーフラグ（選考方法）
define("ScreeningError",         "選考方法を入力してください");

//エラーフラグ（フェーズ）
define("PhaseError",             "フェーズを入力してください");
//エラーフラグ（キーワード）
define("KeywordError",           "キーワードを入力してください");
//エラーフラグ（コメント）
define("CommentError",           "内容を入力してください");
//エラーフラグ（ID）
define("IdError01",              "ＩＤを入力してください");
//エラーフラグ（ID）
define("IdError02",              "ＩＤは8～16文字の半角英数字で登録してください。");






//------------------------
//インクルード用リターン値
//------------------------
return "定数定義";


?>

