<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\CheckinOption;

use Cium\WeWorkApi\utils\Utils;

class WifiMacInfo
{
    public $wifiname = null; // string
    public $wifimac = null;  // string

    /**
     * @param $arr
     *
     * @return WifiMacInfo
     */
    public static function ParseFromArray($arr)
    {
        $info = new WifiMacInfo();

        $info->wifiname = Utils::arrayGet($arr, "wifiname");
        $info->wifimac = Utils::arrayGet($arr, "wifimac");

        return $info;
    }
}