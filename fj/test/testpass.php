<?php


$ret = FALSE;                                // 関数リターン値
$ret = include_once("../inc/define.php");   // defineパス: インクルード

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//=== 共通関数：インクルード ===
$ret = include_once(FJ_Config);  // config.php
$ret = include_once(FJ_Mg_Pass); // password_hash用
$hash = "";

if (isset($_POST["pass"])){
    $options = array('cost' => 10);
    $hash = password_hash($_POST["pass"], PASSWORD_DEFAULT, $options);
}

?>

<div>
<form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="post">
	<input type="text" name="pass">
	<input type="submit">
</form>
</div>
<br>
<div>パスワードハッシュ生成<br><?= $hash ?></div>





