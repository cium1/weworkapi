<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

use Cium\WeWorkApi\callback\XMLParse;
use Cium\WeWorkApi\callback\WXBizMsgCrypt;

$encodingAesKey = "vn5tOX4MsD6vnNdPYB6xWex0HXlHtAqUNfllF9SUtos";
$token = "b5WLBslo";
$corpId = "wwad8b92d6d49fb4b4";

$sVerifyMsgSig = $_GET['msg_signature'];
$sVerifyTimeStamp = $_GET['timestamp'];
$sVerifyNonce = $_GET['nonce'];
$sVerifyEchoStr = file_get_contents('php://input');
$sVerifyEchoStr = '';
$xml = new XMLParse();
$result = $xml->extract($sVerifyEchoStr);
if ($result[0]) {
    return $result;
}
$sVerifyEchoStr = $result[1];
$sEchoStr = "";
$wxCpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxCpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
if ($errCode == 0) {
    var_export($sEchoStr);
} else {
    print("ERR: " . $errCode . "\n\n");
}