<?php
//---------------------------------------------------------
//  メール送信用プログラム  |  最終更新日:2018/08/01
//---------------------------------------------------------


function sendmail($fromName, $from, $to, $subject, $body, $returnPath = null)
{

        if (is_null($returnPath)) {
            $returnPath = $from;
        }

        // Fromヘッダー。
        $header = 'From: ' . mb_encode_mimeheader($fromName) . ' <' . $from . '>';

        // メールを送信し、結果を返す。
        // セーフモードがOnの場合は第5引数が使えない。
//         if (ini_get('safe_mode')) {
//             $result = mb_send_mail($to, $subject, $body, $header);
//         } else {
            $result = mb_send_mail($to, $subject, $body, $header, '-f' . $returnPath);
//        }
        return $result;
}
 ?>
