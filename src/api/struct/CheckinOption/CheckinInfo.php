<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\CheckinOption;


use Cium\WeWorkApi\utils\Utils;

class CheckinInfo
{
    public $userid = null; // string
    public $group = null;  // CheckinGroup

    /**
     * @param $arr
     *
     * @return CheckinInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new CheckinInfo();

        $info->userid = Utils::arrayGet($arr, "userid");
        $info->group = CheckinGroup::ParseFromArray($arr["group"]);

        return $info;
    }
}