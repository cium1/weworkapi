<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ApprovalData;


use Cium\WeWorkApi\utils\Utils;

class ExpenseItem
{
    public $expenseitem_type = null; // int
    public $time = null;             // int
    public $sums = null;             // int
    public $reason = null;           // string

    /**
     * @param $arr
     *
     * @return ExpenseItem
     */
    static public function ParseFromArray($arr)
    {
        $info = new ExpenseItem();

        $info->expenseitem_type = Utils::arrayGet($arr, "expenseitem_type");
        $info->time = Utils::arrayGet($arr, "time");
        $info->sums = Utils::arrayGet($arr, "sums");
        $info->reason = Utils::arrayGet($arr, "reason");

        return $info;
    }
}