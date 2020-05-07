<?php
//---------------------------------------------------------
//  管理者追加画面  |  最終更新日:2018/08/31
//---------------------------------------------------------
//
// 管理者用パスの新規登録処理
// ID、パスワード、確認用パスワードを入力
// 既に登録されているものではないかチェック後
// admin_tbにインサート
// 登録完了メッセージ / エラーメッセージ表示
//---------------------------------------------------------


// セッション開始
session_start();

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');


//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id(TRUE);


$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//サインインから入っていなければはねる
if (!isset($_SESSION["adminid"]))
{
    header("Location:" .Mg_SignIn);
    exit();
}

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$errmsg = array (); 		                 // エラーメッセージ
$db     = NULL;                             // DBオブジェクト

//=== 共通関数：インクルード ===
$ret = include_once(FJ_Config);  // config.php
$ret = include_once(FJ_Mg_Pass); // password_hash用
$ret = include_once(FJ_Mg_Function); //共通関数

if ($ret === FALSE)
{
    $errmsg[] = "※設定情報ファイル 読み込みエラー！";
}
else
{
    require ("$config_path" . "$config_file");
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

date_default_timezone_set('Asia/Tokyo');
$admin_id = $_SESSION["adminid"]; // 現在ログインしている管理者ID
$new_adminid         = "";         // 新規登録管理者ID
$new_admin_pass      = "";         // 新規登録パスワード
$new_admin_passcheck = "";         // 新規登録確認用パスワード

$new_adminid_error  = "";   // ID入力エラーOFF
$password_error     = "";   // パスワード入力エラーOFF
$passcheck_error    = "";   // 確認パスワード入力エラーOFF

$errflg             = OK; 	// 入力エラーフラグOFF

$msg = "<p>管理者情報を新規追加される場合は、以下に記入後新規登録ボタンを押してください</p>";
$btn = "<input type='submit' name='submit' value='新規登録' class='btn btn-sm btn-warning'>";  //  新規登録用ボタン


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


// ===== ポスト：リクエスト処理 =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
{
    // -------- 入力チェック -------------------------------------------

    //IDチェック
    $new_adminid = htmlspecialchars($_POST["new_adminid"], ENT_QUOTES);

    if (inputCheck($new_adminid))
    {
        $new_adminid_error = IdError01;
        $errflg = NG;
    }
    else
   {//入力OK

        if (lengthCheck($new_adminid))
        {// 文字数NG
            $new_adminid_error = IdError02;
            $errflg = NG;
        }
        else
       {
           // 文字数OK
           if (pregCheck($new_adminid))
           {
               // 半角英数字NG
               $new_adminid_error = IdError02;
               $errflg = NG;
           }
           else
         {
                //OK
                $new_adminid = $new_adminid;
           }
        }
    }



    // パスワードチェック
    $new_admin_pass = htmlspecialchars ($_POST["password"], ENT_QUOTES );

    if (inputCheck($new_admin_pass))
    {
        $password_error = PassError01;
        $errflg = NG;
    }
    else
    {//入力OK

        if (lengthCheck($new_admin_pass))
        {// 文字数NG
            $password_error = PassError02;
            $errflg = NG;
        }
        else
        {
            // 文字数OK
            if (pregCheck($new_admin_pass ))
            {
                // 半角英数字NG
                $password_error = PassError02;
                $errflg = 1;
            }
            else
          {
                //OK
                $new_admin_pass = $new_admin_pass;
            }
        }
    }

    // 確認用パスワードチェック
    $new_admin_passcheck = htmlspecialchars($_POST["passcheck"], ENT_QUOTES );

    if (inputCheck($new_admin_passcheck))
    {
        $passcheck_error = PassError03;
        $errflg = NG;
    }
    elseif ($new_admin_pass !== $new_admin_passcheck)
    {
        $passcheck_error = PassError04;
        $errflg = NG;
    }


    // --------エラーがなければインサート--------------------------------------------------------
    if (!$errflg)
    {
        try
        {
            //パスワードハッシュを作る
            $options = array('cost' => 10);
            $hash = password_hash($new_admin_pass, PASSWORD_DEFAULT, $options);

            //========== ここからDB ==========

            // ＤＢ接続
            $db = new PDO($database_dsn, $database_user, $database_password);   //PDOアクセス

            // 文字コード
            $ret = $db->exec ("SET NAMES utf8");
            // カラム名（連想キー）：小文字設定
            $ret = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //CASE_LOWER 小文字の場合
            // ＳＱＬ
            $sql = "INSERT INTO admin_tb(admin_id, password, create_id, create_datetime)";
            $sql .= " VALUES(?, ?, ?, now());";

            $result = $db->prepare($sql);    //prepareメソッド

            if($result->execute(array
                ( $new_adminid
                    ,$hash
                    ,$admin_id
                )) === false)
            {
                $new_adminid_error = "既に登録されているIDです。";
            }
            else
          {
                $msg = "<p id='success'>新規登録完了！</p>";
                $btn = "";

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

            $btn = "";
        }
    }
}


?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>管理者情報</title>

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
            <h1 class="h2">管理者新規登録画面</h1>
          </div>
			<!-- ここにコンテンツ -->
        	<?= $msg ?>
        	<hr>

			<form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
				<label for="new_adminid">
            		<span class="label-text">管理者ID</span><br />
            		<input type="text" name="new_adminid" id="new_adminid" value="<?= $new_adminid ?>" placeholder="8～16文字半角英数字"><br />
            		<span id="new_adminid_error" class="error_m"><?= $new_adminid_error ?></span>
            	</label>
            	<br />
				<label for="password">
					<span class="label-text">パスワード</span><br />
					<input type="password" name="password" id="password" value="" placeholder="8～16文字半角英数字"><br />
					<span id="password_error" class="error_m"><?= $password_error ?></span>
				</label>
				<br />
				<label for="passcheck">
					<span class="label-text">確認用パス</span><br />
					<input type="password" name="passcheck" id="passcheck" value=""><br />
					<span id="passcheck_error" class="error_m"><?= $passcheck_error ?></span>
				</label>
				<br /><br />
				<?= $btn ?>
			</form>
			<br>
			<form action="<?= Mg_AdminLIST ?>" method="post">
				<button type='submit' name='rebtn' id='rebtn' value="" class='btn btn-sm btn-info'>管理者一覧画面へ戻る</button>
			</form>
        	<?php //設定情報エラー
        		if (!empty($errmsg))
        		{
        			foreach ($errmsg as $val)
        			{
        				echo $val;
        			}
        		}
        	?>
			<!-- ここまでコンテンツ -->
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







