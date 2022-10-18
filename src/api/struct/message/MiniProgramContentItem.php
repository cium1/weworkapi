<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\message;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;
use Cium\WeWorkApi\utils\error\QyApiError;

class MiniProgramContentItem
{
    public $key = null;              // string
    public $value = null;     // string
    

    /**
     * MiniProgramContentItem constructor.
     *
     * @param null $key
     * @param null $value
     */
    public function __construct($key = null, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @throws ParameterError
     */
    public function CheckMessageSendArgs()
    {
        Utils::checkNotEmptyStr($this->key, "key");
        Utils::checkNotEmptyStr($this->value, "value");
        $keyLen = mb_strlen($this->key??'');
        if ($keyLen == 0 || $keyLen > 10) {
            throw new QyApiError("invalid content length " . $keyLen);
        }
        $valueLen=mb_strlen($this->value??'');
        if ($valueLen == 0 || $valueLen > 30) {
            throw new QyApiError("invalid content length " . $valueLen);
        }
    }

    /**
     * @return array
     */
    public function Content2Array()
    {
        $args = array();

        Utils::setIfNotNull($this->key, "key", $args);
        Utils::setIfNotNull($this->value, "value", $args);
        return $args;
    }
}