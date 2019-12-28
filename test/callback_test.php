<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Cium\WeWorkApi\callback\XMLParse;
use Cium\WeWorkApi\callback\WXBizMsgCrypt;
use Cium\WeWorkApi\utils\Utils;

$encodingAesKey = "vn5tOX4MsD6vnNdPYB6xWex0HXlHtAqUNfllF9SUtos";
$token = "b5WLBslo";
$corpId = "wwad8b92d6d49fb4b4";
$wxCpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);

// 验证回调URL
$sVerifyMsgSig = '66d372a4a1a2224e6a8f28bb65cc8ce6788ad48a';
$sVerifyTimeStamp = '1577181426';
$sVerifyNonce = '1577943130';
$sVerifyEchoStr = '<xml><ToUserName><![CDATA[wwad8b92d6d49fb4b4]]></ToUserName><Encrypt><![CDATA[tgvbRqQEUPY4AR/tI4m+87zSpNQ2MDI09W71MWvqUqJvvF8FW6Ba/+/JkIohl+C/m/+6N/0ykx5zzg/qzEhDj6LyIwUtT7r7Sm0a2H8CRMHk/twsFaRq+BnYPNictB6WIikvEQOxkFiRdXA9jJSzKtnYEM4SEsMUfh4132xoHzo2YZNTYQb0Zzq0tJ6d+XnO2XD5PN5F3fTWX+qYO7t5txLoPga9SfoHUi+o9Ee4oOAKlbv35nvbsvoa0mhfAEH5oOBohox+MiI1AlrjdzUpsiAalRITaA1yuD3XBtMbXh0GJbvPWux0Qy4m06A47y6DoOjAb8vjLnkKbVy85bTpORTfDZ+2WA6sKdyMn2Fn4wYvdxl5o0Br6UWk1BrWAE2EwogIf84kDKdc1I53LHhytVl4xUAZ56O12xXmOb8pbhKdfsxD1A17//YsGKdIoJC4aXFZW1WoFFWg+sH+No8IBPEbBFLVnf0g0kTpGf2Z6llP6OsuQ4DXeknWmxD7wCn67ydEclsmoZ0+dRK30gR5DaY0mzVwdtdi880HV8vxF83pgGvzh+UW0p0wyytKOvm0CQI0/daMEjd8/I4ROInjar3A5u/PzO8YFt9G9JkyBvRLMU4PllSqFC7Ik0YLHR4ONilgwtwXs6YjdMN8aNCiNakDIe+cHZH6PI7+phcOpxcJINa4axLv5VXuib9e2VP9DsutAiec6DI4vBA3J0SiWQ==]]></Encrypt><AgentID><![CDATA[]]></AgentID></xml>';
$xml = new XMLParse();
$result = $xml->extract($sVerifyEchoStr);
if ($result[0]) {
    return $result;
}
$sVerifyEchoStr = $result[1];
$errCode = $wxCpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
if ($errCode == 0) {
    var_export($sEchoStr);
    echo PHP_EOL;
    var_export(Utils::Xml2Array($sEchoStr));
    echo PHP_EOL;
} else {
    echo "ERR", $errCode;
}

echo PHP_EOL;

// 对用户回复的消息解密
$sReqMsgSig = '66d372a4a1a2224e6a8f28bb65cc8ce6788ad48a';
$sReqTimeStamp = '1577181426';
$sReqNonce = '1577943130';
$sReqData = '<xml><ToUserName><![CDATA[wwad8b92d6d49fb4b4]]></ToUserName><Encrypt><![CDATA[tgvbRqQEUPY4AR/tI4m+87zSpNQ2MDI09W71MWvqUqJvvF8FW6Ba/+/JkIohl+C/m/+6N/0ykx5zzg/qzEhDj6LyIwUtT7r7Sm0a2H8CRMHk/twsFaRq+BnYPNictB6WIikvEQOxkFiRdXA9jJSzKtnYEM4SEsMUfh4132xoHzo2YZNTYQb0Zzq0tJ6d+XnO2XD5PN5F3fTWX+qYO7t5txLoPga9SfoHUi+o9Ee4oOAKlbv35nvbsvoa0mhfAEH5oOBohox+MiI1AlrjdzUpsiAalRITaA1yuD3XBtMbXh0GJbvPWux0Qy4m06A47y6DoOjAb8vjLnkKbVy85bTpORTfDZ+2WA6sKdyMn2Fn4wYvdxl5o0Br6UWk1BrWAE2EwogIf84kDKdc1I53LHhytVl4xUAZ56O12xXmOb8pbhKdfsxD1A17//YsGKdIoJC4aXFZW1WoFFWg+sH+No8IBPEbBFLVnf0g0kTpGf2Z6llP6OsuQ4DXeknWmxD7wCn67ydEclsmoZ0+dRK30gR5DaY0mzVwdtdi880HV8vxF83pgGvzh+UW0p0wyytKOvm0CQI0/daMEjd8/I4ROInjar3A5u/PzO8YFt9G9JkyBvRLMU4PllSqFC7Ik0YLHR4ONilgwtwXs6YjdMN8aNCiNakDIe+cHZH6PI7+phcOpxcJINa4axLv5VXuib9e2VP9DsutAiec6DI4vBA3J0SiWQ==]]></Encrypt><AgentID><![CDATA[]]></AgentID></xml>';

$errCode = $wxCpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
    var_export($sMsg);
    echo PHP_EOL;
    var_export(Utils::Xml2Array($sMsg));
    echo PHP_EOL;
} else {
    echo "ERR", $errCode;
}

echo PHP_EOL;

// 企业回复用户消息的加密
$sRespData = "<xml><ToUserName><![CDATA[mycreate]]></ToUserName><FromUserName><![CDATA[wx5823bf96d3bd56c7]]></FromUserName><CreateTime>1348831860</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[this is a test]]></Content><MsgId>1234567890123456</MsgId><AgentID>128</AgentID></xml>";
$errCode = $wxCpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
if ($errCode == 0) {
    var_export(Utils::Xml2Array($sRespData));
    echo PHP_EOL;
    var_export($sEncryptMsg);
    echo PHP_EOL;
    var_export(Utils::Xml2Array($sEncryptMsg));
    echo PHP_EOL;
} else {
    echo "ERR", $errCode;
}