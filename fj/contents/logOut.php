<?php
//セッション開始
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
include_once("../inc/define.php");
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// ログアウトを押されたとき

    $_SESSION = array();        //セッションを全て削除
    if(isset($_COOKIE[session_name()])) {       //セッションを切断するにはクッキーも削除
        setcookie(session_name(), '', time()-43200, '/');   // 43200 : 12時間
    }
    //セッション破壊
    session_destroy();

    header ("Location:" .Fj_Top);  //トップ画面へ遷移
    exit();     //処理終了

?>