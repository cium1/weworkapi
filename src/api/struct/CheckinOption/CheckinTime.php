<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\CheckinOption;


use Cium\WeWorkApi\utils\Utils;

class CheckinTime
{
    public $work_sec = null;            // int
    public $off_work_sec = null;        // int
    public $remind_work_sec = null;     // int
    public $remind_off_work_sec = null; // int

    /**
     * @param $arr
     *
     * @return CheckinTime
     */
    public static function ParseFromArray($arr)
    {
        $info = new CheckinTime();

        $info->work_sec = Utils::arrayGet($arr, "work_sec");
        $info->off_work_sec = Utils::arrayGet($arr, "off_work_sec");
        $info->remind_work_sec = Utils::arrayGet($arr, "remind_work_sec");
        $info->remind_off_work_sec = Utils::arrayGet($arr, "remind_off_work_sec");

        return $info;
    }
}