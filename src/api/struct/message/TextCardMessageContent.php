<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\message;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class TextCardMessageContent
{
    public $msgtype = "textcard";
    public $title = null;       // string
    public $description = null; // string
    public $url = null;         // string
    public $btntxt = null;      // string

    /**
     * TextCardMessageContent constructor.
     *
     * @param null $title
     * @param null $description
     * @param null $url
     * @param null $btntxt
     */
    public function __construct($title = null, $description = null, $url = null, $btntxt = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->btntxt = $btntxt;
    }

    /**
     * @throws ParameterError
     */
    public function CheckMessageSendArgs()
    {
        Utils::checkNotEmptyStr($this->title, "title");
        Utils::checkNotEmptyStr($this->description, "description");
        Utils::checkNotEmptyStr($this->url, "url");
    }

    /**
     * @param $arr
     */
    public function MessageContent2Array(&$arr)
    {
        Utils::setIfNotNull($this->msgtype, "msgtype", $arr);

        $contentArr = array();
        {
            Utils::setIfNotNull($this->title, "title", $contentArr);
            Utils::setIfNotNull($this->description, "description", $contentArr);
            Utils::setIfNotNull($this->url, "url", $contentArr);
            Utils::setIfNotNull($this->btntxt, "btntxt", $contentArr);
        }
        Utils::setIfNotNull($contentArr, $this->msgtype, $arr);
    }
}