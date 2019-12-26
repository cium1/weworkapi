<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\menu;


use Cium\WeWorkApi\utils\Utils;

class SubMenu
{
    public $name = null;       // string
    public $sub_button = null; // xxxMenu array

    /**
     * SubMenu constructor.
     *
     * @param null $name
     * @param null $xxmenuArray
     */
    public function __construct($name = null, $xxmenuArray = null)
    {
        $this->name = $name;
        $this->sub_button = $xxmenuArray;
    }

    /**
     * @param $arr
     *
     * @return SubMenu
     */
    public static function Array2Menu($arr)
    {
        $menu = new SubMenu();

        $menu->name = Utils::arrayGet($arr, "name");
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