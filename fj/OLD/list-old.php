<?php
//---------------------------------------------------------
//  案件リストページ  |  最終更新日:2018/09/26
//---------------------------------------------------------
// 初回、検索条件なしで表示　件数も表示
// 登録ユーザ：全件表示可
// ▲未登録ユーザ：非公開案件表示不可！！▲
// 会員しか見れない情報 member_flg=1  //全員見られる案件 member_flg=0
// 検索結果のIDを案件詳細ページへ飛ばす
// 結果がない場合は見つかりませんと表示
// ある場合は検索結果数とリストページ用項目を表示
// リストページ用項目：案件タイトル、勤務地、給与上限
//
//---------------------------------------------------------

//セッション開始
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$msg        = "";                     // 検索結果表示
$serch_item = "";                     // 検索項目表示用
$page       = "";                     // ページャー
$sql_price  = "";                     // 単価ソート
$count      = COUNT;                  // 検索結果カウント
$errmsg     = array (); 		      // エラーメッセージ
$db         = NULL;                  // DBオブジェクト
date_default_timezone_set('Asia/Tokyo');

// 共通関数インクルード
$ret        = include_once(FJ_Config);  // config.php
$ret        = include_once(FJ_Mg_Function);

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    require ("$config_path" . "$config_file");
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


if (isset($_POST["sort"]))
{

    if ($_POST["select_sort"] == SORT_NEW)
    {
        $sql_price .= "";
        $select_sort = SORT_NEW;
    }
    if ($_POST["select_sort"] == SORT_PRICE)
    {
        $sql_price .= " pt.price_upper DESC,";
        $select_sort = SORT_PRICE;
    }

    // 単価・新着のソートボタンselectedの判別用にセッションで覚えておく
    $_SESSION["sql_price"] = $sql_price;
    $_SESSION["select_sort"] = $select_sort;

}


//====================================================================================
if(!isset($_GET['page']) && !isset($_POST["sort"]))
{
    // 初回初期化（初めて訪れた時にはpageが設定されていない）
    $now_page = FIRST_VISIT_PAGE;

    $_SESSION["sql_price"]      = "";
    $_SESSION["select_sort"]    = SORT_NEW;
    $_SESSION["serch_item"]     = "未選択";


    //==SQL文===============================

    //ページャーのための行数取得用SQL
    $sql_count = "SELECT COUNT(*) AS count FROM (";

    //検索表示用SQL

    $sql= "SELECT
            pt.project_id
            , pt.subject
            , pt.post_date
            , pt.post_end_date
            , pt.area_no
            , pt.location
            , pt.price_lower
            , pt.price_upper
            , pt.period_lower
            , pt.period_upper
            , pt.content
            , pt.screening
            , pt.member_flg
            , GROUP_CONCAT(pst.skill_no)
            , GROUP_CONCAT(pht.phase_no)
            FROM
            project_tb pt
			LEFT JOIN project_skill_tb pst
            ON pt.project_id = pst.project_id
		    LEFT JOIN project_phase_tb pht
            ON pt.project_id = pht.project_id
            WHERE
            DATE_FORMAT(post_date, '%Y%m%d') <= DATE_FORMAT(CURDATE(), '%Y%m%d')
            AND DATE_FORMAT(post_end_date, '%Y%m%d') >= DATE_FORMAT(CURDATE(), '%Y%m%d')
            AND pt.reflect_flg = 0";

    if (isset($_POST["submit"]))
    {

        // エリア
        if (isset($_POST["area_no"]))
        {

            // 検索項目表示
            foreach($_POST["area_no"] as $val)
            {
                $serch_item .= areaStr($val)."　";
            }

            // SQL
            $serch = " AND area_no IN (";
            $sql .= sqlStatement($_POST["area_no"],$serch);

        }

        // フェーズ
        if (isset($_POST["phase_no"]))
        {
            // 検索項目表示
            $phasestr = phaseStr($_POST["phase_no"]);
            foreach($phasestr as $val)
            {
                $serch_item .=  $val."　";
            }

            $serch = " AND phase_no IN (";
            $sql .= sqlStatement($_POST["phase_no"],$serch);

        }

        // スキルキーワード
        if (isset($_POST["keyword"]))
        {

            // 検索項目表示
            $keywordstr = keywordStr($_POST["keyword"]);
            foreach($keywordstr as $val)
            {
                $serch_item .=  $val."　";
            }

            // SQL
            $serch = " AND skill_no IN (";
            $sql .= sqlStatement($_POST["keyword"],$serch);

        }

        // フリーワード
        if (strlen($_POST["freeword"]))
        {
            //--フリーワードを配列に格納-----
            $freeword = str_replace(['，', '、', '､'], ',', $_POST["freeword"]);       // 全角カンマを半角に
            $freeword = mb_convert_kana($freeword, 's');                                // 全角スペースを半角スペースに(文字化け防止)
            $freeword = preg_split('/[\s,]+/', $freeword, -1, PREG_SPLIT_NO_EMPTY);     // PREG_SPLIT_NO_EMPTY」を指定して、空文字列以外だけを返す


            $i = 0;    // ループカウント
            $sql .= " AND (";

            foreach ($freeword as $val)
            {

                // 検索項目表示
                $serch_item .=  "　".$val;

                if ($i < 1)
                {
                    $sql .= " pt.subject LIKE '%{$val}%'";
                }
                else
                {
                    $sql .= " OR pt.subject LIKE '%{$val}%'";
                }

                $sql .= " OR pt.location LIKE '%{$val}%'
                      OR pt.content LIKE '%{$val}%'";

                $i++;

            }
            $sql .= ")";

        }


        if (inputCheck($serch_item))
        {
            $serch_item = "未選択";
        }

        $_SESSION["serch_item"] = $serch_item;

    }


    $sql .= " GROUP BY pt.project_id
                , pt.subject
                , pt.post_date
                , pt.post_end_date
                , pt.area_no
                , pt.location
                , pt.price_lower
                , pt.price_upper
                , pt.period_lower
                , pt.period_upper
                , pt.content
                , pt.screening
                , pt.member_flg
                ";

    $_SESSION["sql"] = $sql;

    $sql_count .= $sql.") AS table_row;";
    $sql .= " ORDER BY";
    $sql .= $sql_price;
    $sql .= " pt.update_datetime DESC";



    //====件数取得=========================================================================

    try{

        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

        // 文字コード
        $db->exec ("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合


        // 該当するものが何件あるか
        $result = $db->query($sql_count);

        if ($result !== false)
        {

            $row = $result->fetch(PDO::FETCH_ASSOC);
            $count = $row["count"];
            $_SESSION["count"] = $count;


            if ($count > COUNT){
                //ページ最大
                $max_page = ceil($count / ARTICLE_MAX_NUM);
                $_SESSION["max_page"] = $max_page;

                //SQL　LIMIT
                $sql .= " LIMIT ".ARTICLE_MAX_NUM;
                $sql .= ";";
            }
            else
          {
                $msg    = "只今お探しの案件はございません。検索条件を変更してください。";   // 検索結果表示
            }

        }


    }
    //=== 例外処理 ===
    catch(Exception $e)
    {
        //--- エラーメッセージ、エラーコード、エラーラインＮｏ. ---
        // エラーメッセージ：ＯＳ別設定
        // PHP_OS : 「AIX / Darwin / MacOS / Linux / SunOS / WIN32 / WINNT / Windows」
        if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
        {
            // 全角変換  SJIS ---> UTF-8
            $errmsg[] = "MSG:" . mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS");
        }
        else
        {
            $errmsg[] = "MSG:" . $e->getMessage()."<br />";
        }
        // エラーコード
        $errmsg[] = "CODE:" . $e->getCode()."<br />";
        // エラーラインＮｏ.
        $errmsg[] = "LINE:" . $e->getLine()."<br />";
        // ＤＢ接続開放
        $db = NULL;
    }
}
else if(isset($_GET['page']) && isset($_SESSION["sql"]))
{//ページャーを押されたとき

    if(isset($_SESSION["sql_price"]))
    {
        $sql_price = $_SESSION["sql_price"];
    }


    $sql    = $_SESSION["sql"];
    $sql .= " ORDER BY";
    $sql .= $sql_price;
    $sql .= " pt.update_datetime DESC";
    $count  = $_SESSION["count"];
    $max_page = $_SESSION["max_page"];

    //有効な数値かチェックして、念のためエスケープしてGETを格納する
    $now_page = htmlspecialchars($_GET['page'],ENT_QUOTES,'UTF-8');

    $limit_start  = ARTICLE_MAX_NUM * $now_page - ARTICLE_MAX_NUM;

    $sql .= " LIMIT ".$limit_start." ,".ARTICLE_MAX_NUM;
    $sql .= ";";

}
else
{
    if (!isset($_SESSION["sql"]))
    {
        header("Location:" .Fj_Top);
        exit();
    }

    $now_page = FIRST_VISIT_PAGE;

    if(isset($_SESSION["sql_price"]))
    {
        $sql_price = $_SESSION["sql_price"];
    }

    $sql    = $_SESSION["sql"];
    $sql .= " ORDER BY";
    $sql .= $sql_price;
    $sql .= " pt.update_datetime DESC";
    $sql .= " LIMIT ".ARTICLE_MAX_NUM;
    $sql .= ";";

    $count      = $_SESSION["count"];
    $max_page   = $_SESSION["max_page"];

}


//表示できる項目があれば取得・表示＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
if ($count > COUNT)
{
    try{

        // ＤＢ接続
        $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

        // 文字コード
        $db->exec ("SET NAMES utf8");
        // カラム名（連想キー）：小文字設定
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合


        //SQL表示項目
        $result = $db->query($sql);

        if ($result !== false)
        {
            // 行データ取得
            $row = $result->fetchall(PDO::FETCH_ASSOC);

            if (0 < $result->rowCount())
            {

                $project_data = array();

                foreach ($row as $val)
                {

                    $project_id = $val["project_id"];
                    $project_data["project"][$project_id]["project_id"]       = $val["project_id"];
                    $project_data["project"][$project_id]["subject"]          = $val["subject"];
                    $project_data["project"][$project_id]["post_date"]        = $val["post_date"];
                    $project_data["project"][$project_id]["post_end_date"]    = $val["post_end_date"];
                    $project_data["project"][$project_id]["area_no"]          = $val["area_no"];
                    $project_data["project"][$project_id]["location"]         = $val["location"];
                    $project_data["project"][$project_id]["price_lower"]      = $val["price_lower"];
                    $project_data["project"][$project_id]["price_upper"]      = $val["price_upper"];
                    $project_data["project"][$project_id]["period_lower"]     = $val["period_lower"];
                    $project_data["project"][$project_id]["period_upper"]     = $val["period_upper"];
                    $project_data["project"][$project_id]["content"]          = $val["content"];
                    $project_data["project"][$project_id]["screening"]        = $val["screening"];
                    $project_data["project"][$project_id]["member_flg"]       = $val["member_flg"];

                }


                foreach ($project_data["project"] as $val)
                {

                    // フェーズ
                    $sql_phase = "SELECT project_id, phase_no FROM project_phase_tb WHERE project_id = {$val["project_id"]} ;";
                    $result = $db->query($sql_phase);
                    if ($result !== false)
                    {
                        $row = $result->fetchall(PDO::FETCH_ASSOC);
                        // プロジェクトフェーズテーブルから該当IDのフェーズを取得";
                        foreach($row as $val)
                        {
                            $project_id = $val["project_id"];
                            $project_data["project"][$project_id]["phase_no"][]       = $val["phase_no"];
                        }
                    }


                    // スキル
                    $sql_skill = "SELECT project_id, skill_no FROM project_skill_tb WHERE project_id = {$val["project_id"]} ;";
                    $result = $db->query($sql_skill);
                    if ($result !== false)
                    {
                        $row = $result->fetchall(PDO::FETCH_ASSOC);
                        // スキルキーワードテーブルから該当IDのキーワードを取得";
                        foreach($row as $val)
                        {
                            $project_id = $val["project_id"];
                            $project_data["project"][$project_id]["skill_no"][]       = $val["skill_no"];
                        }
                    }


                    $_SESSION["project_data"] = $project_data;

                }

                // 表示項目作成
                $msg = listStr($project_data["project"]);

            }

            else
            {
                $msg    = "該当データがありません。";   // 検索結果表示

            }

        }
    }
    //=== 例外処理 ===
    catch(Exception $e)
    {
        //--- エラーメッセージ、エラーコード、エラーラインＮｏ. ---
        // エラーメッセージ：ＯＳ別設定
        // PHP_OS : 「AIX / Darwin / MacOS / Linux / SunOS / WIN32 / WINNT / Windows」
        if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
        {
            // 全角変換  SJIS ---> UTF-8
            $errmsg[] = "MSG:" . mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS");
        }
        else
        {
            $errmsg[] = "MSG:" . $e->getMessage()."<br />";
        }
        // エラーコード
        $errmsg[] = "CODE:" . $e->getCode()."<br />";
        // エラーラインＮｏ.
        $errmsg[] = "LINE:" . $e->getLine()."<br />";
        // ＤＢ接続開放
        $db = NULL;
    }


    // ページャー

    if($now_page > 1) // 現在のページが２ページ目以降
    {
        $page .= "<a href='".Fj_List."?page=1' class='page-btn color-white bg-darkblue'>TOP</a>";
        $page .= "<a href='".Fj_List."?page=".($now_page-1)."' class='page-btn bg-darkblue color-white'>BACK</a>";
    }


    if ($max_page > 1) //ページ数が２ページ以上
    {
        $i = 1;

        if ($now_page < PAGE_BOX)
        {
            $page_no = $i;
        }
        else
        {
            $page_no = $i * $now_page;
            $j = $page_no % 4;
            if ($j != 1)
            {
                $page_no = $page_no - $j;
            }
        }

        while (true)
        {
            $color = "";

            if ($page_no > $max_page){
                break;
            }

            if ($i == PAGE_BOX){
                $page .= "<a href='".Fj_List."?page=".($page_no)."' class='page-btn color-white bg-darkblue'>…</a>";
                break;
            }

            if ($page_no == $now_page)
            {
                $color = " bg-pink'";
            }


            $page .= "<a href='".Fj_List."?page=".($page_no)."' class='page-btn bg-darkblue color-white".$color."'>".$page_no."</a>";

            $i++;
            $page_no++;

        }
    }

    if($now_page < $max_page)
    {
        $page .= "<a href='".Fj_List."?page=".($now_page+1)."' class='page-btn bg-darkblue color-white'>NEXT</a>";
        $page .= "<a href='".Fj_List."?page=".($max_page)."' class='page-btn bg-darkblue color-white'>LAST</a>";
    }


}

//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

//SQL　INの中身を繋げる処理（※condition = 条件）
function sqlStatement($condition, $sql){

    $length = count($condition);
    $i = 0;    // ループカウント

    foreach ($condition as $val)
    {
        $i++;
        $sql .= $val;

        if ($i !== $length)
        {
            $sql .= ", ";
        }
    }
    $sql .= ")";

    return $sql;
}


// 案件リスト表示生成
function listStr($project_data)
{

    $msg = "";
    foreach ($project_data as $val)
    {

        $project_id = $val["project_id"];


        if ($val["member_flg"] == Member && !isset ($_SESSION["name"]))
        {
            $msg .="<form action='".Fj_Project."' method='post'>
                    <div class='project_listbox bg-gray ".newStamp($val["post_date"])."'>
	                    <div>
							<div class='listbox_post_date'>掲載日：{$val["post_date"]}</div>
	                        <div class='project_member'><span>{$val["subject"]}</span></div>
	                        <div class='clear'></div>
	                    </div>
                        <div class='project_info'>
                            <div>勤務場所：{$val["location"]}</div>
                            <div>契約単価：MAX{$val["price_upper"]}万円</div>
                        </div>
                        <div class='project_content color-red bg-white'>この案件は非公開案件です。</div>
                    </div>";
        }
        else
        {
            $mark = "";
            if ($val["member_flg"] == Member && isset ($_SESSION["name"]))
            {
                $mark = "<div class='member-icon bg-pink color-white'>会員様限定</div>";
            }


            $msg .= "<form action='".Fj_Project."' method='post'>


                    <div class='project_listbox bg-gray ".newStamp($val["post_date"])."'>
                        <div>
                        	<div class='listbox_post_date'>掲載日：{$val["post_date"]}</div>
                            <button type='submit' name='project_id' value='{$project_id}' class='project_listbtn'><span>{$val["subject"]}</span></button>
                            <div class='clear'></div>
                        </div>
                        <div class='project_info'>
                            <div>勤務場所：{$val["location"]}</div>
                            <div>契約単価：MAX{$val["price_upper"]}万円</div>
                        </div>
                        <div class='project_content bg-white'>{$mark}<div>".nl2br($val["content"])."</div></div>
                    </div>
                    ";
        }

        $msg .=  "</form>";
    }
    return $msg;
}

// 新着記事要用NEWマーク
function newStamp($post_date){
	//掲載記事日付文字列をタイムスタンプに変換
	$new_css = "";
	$dateObj = new Datetime($post_date);
	$post_date_timeStamp = $dateObj->getTimestamp();
	//今日と掲載日時を比較
	if(time() - $post_date_timeStamp < NEW_DAY){
		
		$new_css = "new-stamp";
	}
	return $new_css;
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="フリーランス,関西,エンジニア,プログラマ,IT">
    <meta name="description" content="関西に特化したフリーランスエンジニアのための案件情報サイト。豊富な案件の中からあなたに合ったお仕事をご紹介！登録は無料です。あなたも始めてみませんか？">
    <title>案件情報 - フリーランスJOBS</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

 	<link rel="stylesheet" href="<?= Fj_Common_css ?>">
 	<link rel="stylesheet" href="<?= Fj_Contents_css ?>">

    <!-- jquery -->
    <script src="<?= Fj_Jq321_js ?>"></script>
    <script src="<?= Fj_Migrate_js ?>"></script>
	<script src="<?= Fj_Scroll_js ?>"></script>
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
        <!-- --------------ナビゲーション-------------- -->
		<?php include (FJ_Header);?>
		<!-- ------------------------------------------ -->

        <!-- --------------メインコンテンツ-------------- -->

		<div id="mainwrap">
			<div id="main">
							<div class="section innerspace contentsbox sectionmainview">
					<div class="mainvisual">
						<h3 class="mainvisualfont">
							<span class="mainvisual mainvisualenfont">PROJECT-LIST</span>
						</h3>
						<span class="mainvisual mainvisualnotefont">案件情報一覧</span>
					</div>
				</div>


				<div class="innerspace subpage-contentswrap">

                    <h2>検索結果<?php echo $count."件"; ?></h2>
					<hr>

					<div id="sort-box">
						<div class='project_listbox bg-bluegreen color-white'>
							<div>検索項目</div>
							<div><?= $_SESSION["serch_item"] ?></div>
							<div id="pc-sort">
        						<form action="<?= $_SERVER["SCRIPT_NAME"]?>" method='post'>
        							<span id="sortbox_select">
	                            		<select name="select_sort" class="page-btn bg-darkblue color-white page-btn-select">
	                                    	<option value="<?= SORT_NEW ?>" <?php if(isset($_SESSION['select_sort'])){if ($_SESSION['select_sort'] == SORT_NEW){echo 'selected';}}else{echo 'selected';} ?>>新着順</option>
	                                    	<option value="<?= SORT_PRICE ?>" <?php if(isset($_SESSION['select_sort'])){if ($_SESSION['select_sort'] == SORT_PRICE){echo 'selected';}} ?>>単価順</option>
	                                    </select>
                                    </span>
            						<input type="submit" name="sort" value="並び替え" class="page-btn  page-btn-sort bg-darkblue color-white" />
        						</form>
        					</div>
        					<div id="pc-reset">
        						<form action="<?= Fj_Top?>#top-catch-serch" method='post'>
        							<input type="submit" name="reset" value="検索条件を変更する" class="page-btn  page-btn-sort bg-yellow color-white" />
        						</form>
        					</div>
        					<div class="clear"></div>
						</div>
					</div>

					<div><?= $msg ?></div>
					<div style="text-align: center"><?= $page ?></div>
				</div>

				<!-- --------------Let's start!-------------- -->
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
							関西No.1の案件数からあなたに合ったJOBをお届け！
						</div>
					</a>
				</div>

			</div>
		</div>

        <!-- --------------フッター-------------- -->
		<?php include (FJ_Footer);?>
		<!-- ------------------------------------ -->
	</div>





</body>
</html>

