<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ApprovalData;


use Cium\WeWorkApi\utils\Utils;

class ApprovalData
{
    public $spname = null;        // string
    public $apply_name = null;    // string
    public $apply_org = null;     // string
    public $approval_name = null; // string array
    public $notify_name = null;   // string array
    public $sp_status = null;     // uint
    public $sp_num = null;        // uint
    public $mediaids = null;      // string array
    public $apply_time = null;    // uint
    public $apply_user_id = null; // string
    public $expense = null;       // ExpenseEvent
    public $comm = null;          // CommApplyEvent
    public $leave = null;         // LeaveEvent

    /**
     * @param $arr
     *
     * @return ApprovalData
     */
    static public function ParseFromArray($arr)
    {
        $info = new ApprovalData();

        $info->spname = Utils::arrayGet($arr, "spname");
        $info->apply_name = Utils::arrayGet($arr, "apply_name");
        $info->apply_org = Utils::arrayGet($arr, "apply_org");
        $info->approval_name = Utils::arrayGet($arr, "approval_name");
        $info->notify_name = Utils::arrayGet($arr, "notify_name");
        $info->sp_status = Utils::arrayGet($arr, "sp_status");
        $info->sp_num = Utils::arrayGet($arr, "sp_num");
        $info->mediaids = Utils::arrayGet($arr, "mediaids");
        $info->apply_time = Utils::arrayGet($arr, "apply_time");
        $info->apply_user_id = Utils::arrayGet($arr, "apply_user_id");

        if (array_key_exists("expense", $arr)) {
            $info->expense = ExpenseEvent::ParseFromArray($arr["expense"]);
        }

        if (array_key_exists("comm", $arr)) {
            $info->comm = CommApplyEvent::ParseFromArray($arr["comm"]);
        }

        if (array_key_exists("leave", $arr)) {
            $info->leave = LeaveEvent::ParseFromArray($arr["leave"]);
        }

        return $info;
    }
}