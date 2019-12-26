<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\CheckinOption;


use Cium\WeWorkApi\utils\Utils;

class CheckinGroup
{
    public $grouptype = null;                // uint,  1 固定  2自定义  3自由签到
    public $groupid = null;                  // uint,
    public $checkindate = null;              // CheckinDate array
    public $spe_workdays = null;             // SpeWorkDays array
    public $spe_offdays = null;              // SpeOffDays array
    public $sync_holidays = null;            // bool, default true
    public $groupname = null;                // string
    public $need_photo = null;               // bool
    public $wifimac_infos = null;            // WifiMacInfo array
    public $note_can_use_local_pic = null;   // bool
    public $allow_checkin_offworkday = null; // bool
    public $allow_apply_offworkday = null;   // bool
    public $loc_infos = null;                // LocInfo  array

    /**
     * @param $arr
     *
     * @return CheckinGroup
     */
    public static function ParseFromArray($arr)
    {
        $info = new CheckinGroup();

        $info->grouptype = Utils::arrayGet($arr, "grouptype");
        $info->groupid = Utils::arrayGet($arr, "groupid");
        foreach ($arr["checkindate"] as $item) {
            $info->checkindate[] = CheckinDate::ParseFromArray($item);
        }
        foreach ($arr["spe_workdays"] as $item) {
            $info->spe_workdays[] = SpeWorkDays::ParseFromArray($item);
        }
        foreach ($arr["spe_offdays"] as $item) {
            $info->spe_offdays[] = SpeOffDays::ParseFromArray($item);
        }
        $info->sync_holidays = Utils::arrayGet($arr, "sync_holidays");
        $info->groupname = Utils::arrayGet($arr, "groupname");
        $info->need_photo = Utils::arrayGet($arr, "need_photo");
        foreach ($arr["wifimac_infos"] as $item) {
            $info->wifimac_infos[] = WifiMacInfo::ParseFromArray($item);
        }
        $info->note_can_use_local_pic = Utils::arrayGet($arr, "note_can_use_local_pic");
        $info->allow_checkin_offworkday = Utils::arrayGet($arr, "allow_checkin_offworkday");
        $info->allow_apply_offworkday = Utils::arrayGet($arr, "allow_apply_offworkday");
        foreach ($arr["loc_infos"] as $item) {
            $info->loc_infos[] = LocInfo::ParseFromArray($item);
        }

        return $info;
    }
}