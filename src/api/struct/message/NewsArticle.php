<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\message;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class NewsArticle
{
    public $title = null;       // string
    public $description = null; // string
    public $url = null;         // string
    public $picurl = null;      // string
    public $btntxt = null;      // string

    /**
     * NewsArticle constructor.
     *
     * @param null $title
     * @param null $description
     * @param null $url
     * @param null $picurl
     * @param null $btntxt
     */
    public function __construct($title = null, $description = null, $url = null, $picurl = null, $btntxt = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->picurl = $picurl;
        $this->btntxt = $btntxt;
    }

    /**
     * @throws ParameterError
     */
    public function CheckMessageSendArgs()
    {
        Utils::checkNotEmptyStr($this->title, "title");
        Utils::checkNotEmptyStr($this->url, "url");
    }

    /**
     * @return array
     */
    public function Article2Array()
    {
        $args = array();

        Utils::setIfNotNull($this->title, "title", $args);
        Utils::setIfNotNull($this->description, "description", $args);
        Utils::setIfNotNull($this->url, "url", $args);
        Utils::setIfNotNull($this->picurl, "picurl", $args);
        Utils::setIfNotNull($this->btntxt, "btntxt", $args);

        return $args;
    }
}