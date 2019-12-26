<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\menu;


use Cium\WeWorkApi\utils\Utils;

class ScanCodeWaitMsgMenu
{
    public $type = "scancode_waitmsg";
    public $name = null;       // string
    public $key = null;        // string
    public $sub_button = null; // xxxMenu array

    /**
     * ScanCodeWaitMsgMenu constructor.
     *
     * @param null $name
     * @param null $key
     * @param null $xxmenuArray
     */
    public function __construct($name = null, $key = null, $xxmenuArray = null)
    {
        $this->name = $name;
        $this->key = $key;
        $this->sub_button = $xxmenuArray;
    }

    /**
     * @param $arr
     *
     * @return ScanCodeWaitMsgMenu
     */
    public static function Array2Menu($arr)
    {
        $menu = new ScanCodeWaitMsgMenu();

        $menu->type = Utils::arrayGet($arr, "type");
        $menu->name = Utils::arrayGet($arr, "name");
        $menu->key = Utils::arrayGet($arr, "key");

        foreach ($arr["sub_button"] as $item) {
            $subButton = null;
            if (!array_key_exists("type", $item)) {
                $subButton = SubMenu::Array2Menu($item);
            } else {
                $type = $item["type"];
                if ($type == "click") $subButton = ClickMenu::Array2Menu($item);
                if ($type == "view") $subButton = viewMenu::Array2Menu($item);
                if ($type == "scancode_push") $subButton = ScanCodePushMenu::Array2Menu($item);
                if ($type == "scancode_waitmsg") $subButton = ScanCodeWaitMsgMenu::Array2Menu($item);
                if ($type == "pic_sysphoto") $subButton = PicSysPhotoMenu::Array2Menu($item);
                if ($type == "pic_photo_or_album") $subButton = PicPhotoOrAlbumMenu::Array2Menu($item);
                if ($type == "pic_weixin") $subButton = PicWeixinMenu::Array2Menu($item);
                if ($type == "location_select") $subButton = LocationSelectMenu::Array2Menu($item);
            }
            $menu->sub_button[] = $subButton;
        }

        return $menu;
    }
}