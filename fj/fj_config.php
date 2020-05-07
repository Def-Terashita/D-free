<?php 
 
//---------------------------
//      ドキュメントルートに作成
//      fj_config.php
//---------------------------
 
if(Serve_Type === Deve_Test_Serve)
{
    //開発・ローカルテストサーバ
	//DBのDSN
	$database_dsn		= "mysql:host=localhost;dbname=defreelance_2019";
	//DBのユーザー名
	$database_user 		= "root";
	//DBのパスワード
	$database_password	= "";
}
else
{
    //本番・運用サーバ
    //DBのDSN
	$database_dsn		= "mysql:host=mysql8002.xserver.jp;dbname=defreelance_2019";
	//DBのユーザー名
	$database_user		= "defreelance_user";
	//DBのパスワード
	$database_password	= "deftr1be2019";
}

 
//ログインの可否       0=不可    1=可
$database_login=1;
 
//新規登録の可否       0=不可    1=可
$config_registration=1;
 
//インクルード用return値
return "fj_config.php インクルード成功";
 
 
//------------------------
 
 
?>