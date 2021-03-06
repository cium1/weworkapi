<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\message;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class ImageMessageContent
{
    public $msgtype = "image";
    private $media_id = null; // string

    /**
     * ImageMessageContent constructor.
     *
     * @param null $media_id
     */
    public function __construct($media_id = null)
    {
        $this->media_id = $media_id;
    }

    /**
     * @throws ParameterError
     */
    public function CheckMessageSendArgs()
    {
        Utils::checkNotEmptyStr($this->media_id, "media_id");
    }

    /**
     * @param $arr
     */
    public function MessageContent2Array(&$arr)
    {
        Utils::setIfNotNull($this->msgtype, "msgtype", $arr);

        $contentArr = array("media_id" => $this->media_id);
        Utils::setIfNotNull($contentArr, $this->msgtype, $arr);
    }
}