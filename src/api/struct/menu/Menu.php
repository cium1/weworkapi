<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\menu;


class Menu
{
    public $button = null; // xxxMenu array, 即各种子菜单array

    /**
     * Menu constructor.
     *
     * @param null $xxmenuArray
     */
    public function __construct($xxmenuArray = null)
    {
        $this->button = $xxmenuArray;
    }

    /**
     * @param $agentid
     * @param $menu
     */
    public static function CheckMenuCreateArgs($agentid, $menu)
    {

    }

    /**
     * @param $arr
     *
     * @return Menu
     */
    public static function Array2Menu($arr)
    {
        $menu = new Menu();

        foreach ($arr["button"] as $item) {
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
            $menu->button[] = $subButton;
        }

        return $menu;
    }

} // class