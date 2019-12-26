<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ExternalContact;


use Cium\WeWorkApi\api\struct\ExternalContact\messages\LinkMsg;
use Cium\WeWorkApi\api\struct\ExternalContact\messages\MiniprogramMsg;
use Cium\WeWorkApi\api\struct\ExternalContact\messages\TextMsg;
use Cium\WeWorkApi\api\struct\ExternalContact\messages\ImageMsg;
use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class WelcomeMsg
{
    /**
     * @var string
     */
    public $welcome_code;
    /**
     * @var TextMsg
     */
    public $text = null;
    /**
     * @var ImageMsg
     */
    public $image = null;
    /**
     * @var LinkMsg
     */
    public $link = null;

    /**
     * @var MiniprogramMsg
     */
    public $miniprogram = null;

    /**
     * 数组to欢迎消息
     *
     * @param array $arr
     *
     * @return WelcomeMsg
     */
    static public function array2WelcomeMsg(array $arr)
    {
        $welcomeMsg = new WelcomeMsg();
        $welcomeMsg->welcome_code = Utils::arrayGet($arr, "welcome_code");
        if (array_key_exists("text", $arr) && $text = $arr['text']) {
            if (is_array($text)) {
                $welcomeMsg->text = new TextMsg();
                $welcomeMsg->text->content = Utils::arrayGet($text, 'content');
            }
        }
        if (array_key_exists("image", $arr) && $image = $arr['image']) {
            if (is_array($image)) {
                $welcomeMsg->image = new ImageMsg();
                $welcomeMsg->image->media_id = Utils::arrayGet($image, 'media_id');
            }
        }
        if (array_key_exists("link", $arr) && $link = $arr['link']) {
            if (is_array($link)) {
                $welcomeMsg->link = new LinkMsg();
                $welcomeMsg->link->title = Utils::arrayGet($link, 'title');
                $welcomeMsg->link->picurl = Utils::arrayGet($link, 'picurl');
                $welcomeMsg->link->desc = Utils::arrayGet($link, 'desc');
                $welcomeMsg->link->url = Utils::arrayGet($link, 'url');
            }
        }
        if (array_key_exists("miniprogram", $arr) && $miniprogram = $arr['miniprogram']) {
            if (is_array($miniprogram)) {
                $welcomeMsg->miniprogram = new MiniprogramMsg();
                $welcomeMsg->miniprogram->title = Utils::arrayGet($miniprogram, 'title');
                $welcomeMsg->miniprogram->pic_media_id = Utils::arrayGet($miniprogram, 'pic_media_id');
                $welcomeMsg->miniprogram->appid = Utils::arrayGet($miniprogram, 'appid');
                $welcomeMsg->miniprogram->page = Utils::arrayGet($miniprogram, 'page');
            }
        }

        return $welcomeMsg;
    }

    /**
     * 检查Args
     *
     * @param WelcomeMsg $welcomeMsg
     *
     * @throws ParameterError
     */
    static public function checkArgs(WelcomeMsg $welcomeMsg)
    {
        Utils::checkNotEmptyStr($welcomeMsg->welcome_code, "welcome_code");
        if (is_null($welcomeMsg->text) && is_null($welcomeMsg->image) && is_null($welcomeMsg->link) && is_null($welcomeMsg->miniprogram)) {
            throw new ParameterError("text, image, link, and miniprogram cannot be empty at the same time");
        }
    }
}