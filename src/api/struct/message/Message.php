<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\message;


use Cium\WeWorkApi\utils\error\QyApiError;
use Cium\WeWorkApi\utils\Utils;

class Message
{
    public $sendToAll = false;     // bool, 是否全员发送, 即文档所谓 @all
    public $touser = array();      // string array
    public $toparty = array();     // uint array
    public $totag = array();       // uint array
    public $agentid = null;        // uint
    public $safe = null;           // uint, 表示是否是保密消息，0表示否，1表示是，默认0
    public $messageContent = null; // xxxMessageContent

    /**
     * @throws QyApiError
     */
    public function CheckMessageSendArgs()
    {
        if (count($this->touser) > 1000) throw new QyApiError("touser should be no more than 1000");
        if (count($this->toparty) > 100) throw new QyApiError("toparty should be no more than 100");
        if (count($this->totag) > 100) throw new QyApiError("toparty should be no more than 100");

        if (is_null($this->messageContent)) throw new QyApiError("messageContent is empty");
        $this->messageContent->CheckMessageSendArgs();
    }

    /**
     * @return array
     */
    public function Message2Array()
    {
        $args = array();

        if (true == $this->sendToAll) {
            Utils::setIfNotNull("@all", "touser", $args);
        } else {
            //
            $touser_string = null;
            foreach ($this->touser as $item) {
                $touser_string = $touser_string . $item . "|";
            }
            Utils::setIfNotNull($touser_string, "touser", $args);

            //
            $toparty_string = null;
            foreach ($this->toparty as $item) {
                $toparty_string = $toparty_string . $item . "|";
            }
            Utils::setIfNotNull($toparty_string, "toparty", $args);

            //
            $totag_string = null;
            foreach ($this->totag as $item) {
                $totag_string = $totag_string . $item . "|";
            }
            Utils::setIfNotNull($totag_string, "totag", $args);
        }

        Utils::setIfNotNull($this->agentid, "agentid", $args);
        Utils::setIfNotNull($this->safe, "safe", $args);

        $this->messageContent->MessageContent2Array($args);

        return $args;
    }
}