<?php


if(Serve_Type === Deve_Test_Serve)
{
    //開発・ローカルテストサーバ
    $config_path =  $_SERVER['DOCUMENT_ROOT'] . "/" ;

}
else
{
    //本番・運用サーバ
    //echo "本番";
    $config_path = "/home/defreelance/f-d-free.net/public_html/fj/" ;
    //$config_path =  $_SERVER['DOCUMENT_ROOT'] . "/" ;
}


//fj_config.phpのファイル名
$config_file = "fj_config.php";	


// $config_path .  $config_file;


//echo "__OK!";


//------------------------
//インクルード用return値
return "config.php インクルード成功";

?>