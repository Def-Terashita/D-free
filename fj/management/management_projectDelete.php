<?php
//---------------------------------------------------------
//  案件情報削除  |  最終更新日:2018/09/26
//---------------------------------------------------------
//
// 掲載期間が過ぎた案件をDBから自動で削除。
// 毎月15日の午前3時に掲載終了日が前月末日以前の日付の案件をデリート
// 削除した日時と成功・失敗の内容が書かれたログファイル（年月日.log）を作成
//
//---------------------------------------------------------


date_default_timezone_set('Asia/Tokyo');


//=== ログ準備 ===
$dateObj = new DateTime();
$date = $dateObj->format('Ymd');
$fileName =  './project_deletelog/' . $date . '.log';

// 書き込む文字列を用意（アクセス時間）
$log = "\n".$dateObj->format('Y-m-d H:i:s');


$database_dsn		= "mysql:host=mysql620.db.sakura.ne.jp;dbname=freelance-jobs_2018";
//DBのユーザー名
$database_user		= "freelance-jobs";
//DBのパスワード
$database_password	= "f-jobs-deftr1be-2018";


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

try
{
    // ＤＢ接続
    $db = new PDO($database_dsn, $database_user , $database_password);

    // 文字コード：UTF8
    $db->exec("SET NAMES utf8");
    // カラム名（連想キー）：小文字設定
    $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

    // ＳＱＬ
    $sql = "DELETE pt, pst, pht
            	FROM project_tb pt
            	LEFT JOIN project_skill_tb pst
            	ON pt.project_id = pst.project_id
            	LEFT JOIN project_phase_tb pht
            	ON pt.project_id = pht.project_id
            	WHERE DATE_FORMAT(post_end_date, '%Y%m') < DATE_FORMAT(CURDATE(), '%Y%m'); ";

    // ＳＱＬ実行
    $result = $db->query($sql);

    if($result === FALSE)
    {
        // ＳＱＬ実行エラー情報
        $log .=  "\nＳＱＬ実行エラー ";
    }
    else
   {
        $log .=  "案件情報自動削除成功 ";
    }

    // ＤＢ接続開放
    $db = NULL;
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
        $log .=  "\nMSG:" . mb_convert_encoding($e->getMessage(), "UTF-8", "SJIS");
    }
    else
   {
        $log .=  "\nMSG:" . $e->getMessage();
    }
    // エラーコード
    $log .=  "\nCODE:" . $e->getCode();
    // エラーラインＮｏ.
    $log .=  "\nLINE:" . $e->getLine();
    // ＤＢ接続開放
    $db = NULL;
}


// ファイルを追記モードで開く
$fp = fopen($fileName, 'ab');
if (! is_resource($fp)) {
    die('ファイルを開けませんでした。');
}

// ファイルをロック（排他的ロック）
flock($fp, LOCK_EX);

// ログの書き込み
fwrite($fp, $log);

// ファイルのロックを解除
fflush($fp);
flock($fp, LOCK_UN);

// ファイルを閉じる
fclose($fp);




?>
