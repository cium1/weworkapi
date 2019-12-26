<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\CheckinData;


class CheckinDataList
{
    public $checkindata = null; // CheckinData array

    /**
     * @param $arr
     *
     * @return CheckinDataList
     */
    static public function ParseFromArray($arr)
    {
        $info = new CheckinDataList();

        foreach ($arr["checkindata"] as $item) {
            $info->checkindata[] = CheckinData::ParseFromArray($item);
        }

        return $info;
    }
}