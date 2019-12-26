<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\menu;


use Cium\WeWorkApi\utils\Utils;

class ClickMenu
{
    public $type = "click";
    public $name = null; // string
    public $key = null;  // string

    /**
     * ClickMenu constructor.
     *
     * @param null $name
     * @param null $key
     * @param null $xxmenuArray
     */
    public function __construct($name = null, $key = null, $xxmenuArray = null)
    {
        $this->name = $name;
        $this->key = $key;
    }

    /**
     * @param $arr
     *
     * @return ClickMenu
     */
    public static function Array2Menu($arr)
    {
        $menu = new ClickMenu();

        $menu->type = Utils::arrayGet($arr, "type");
        $menu->name = Utils::arrayGet($arr, "name");
        $menu->key = Utils::arrayGet($arr, "key");

        return $menu;
    }
}