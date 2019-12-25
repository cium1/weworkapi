<?php

namespace Cium\WeWorkApi\callback;

/**
 * XMLParse class
 *
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class XMLParse
{

    /**
     * 提取出xml数据包中的加密消息
     *
     * @param string $xmlText 待提取的xml字符串
     *
     * @return array 提取出的加密消息字符串
     */
    public function extract($xmlText)
    {
        try {
            $xml = new \DOMDocument();
            $xml->loadXML($xmlText);
            $array_e = $xml->getElementsByTagName('Encrypt');
            $encrypt = $array_e->item(0)->nodeValue;
            return array(0, $encrypt);
        } catch (\Exception $e) {
            print $e . "\n";
            return array(ErrorCode::$ParseXmlError, null);
        }
    }

    /**
     * 生成xml消息
     *
     * @param string $encrypt   加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce     随机字符串
     *
     * @return string
     */
    public function generate($encrypt, $signature, $timestamp, $nonce)
    {
        $format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

    /**
     * 数组转XML
     *
     * @param string $rootName
     * @param array  $arr
     *
     * @return string
     */
    public function Array2Xml($rootName, $arr)
    {
        $xml = "<" . $rootName . ">";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</" . $rootName . ">";
        return $xml;
    }

    /**
     * 将XML转为array
     *
     * @param string $xml
     *
     * @return mixed
     */
    public function Xml2Array($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

}

//
// Test 
/*
$sPostData = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><AgentID><![CDATA[toAgentID]]></AgentID><Encrypt><![CDATA[msg_encrypt]]></Encrypt></xml>";
$xmlparse = new XMLParse;
$array = $xmlparse->extract($sPostData);
var_dump($array);
*/


