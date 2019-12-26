<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ApprovalData;


use Cium\WeWorkApi\utils\Utils;

class CommApplyEvent
{
    public $apply_data = null; // string TODO, 文档太烂，看不懂, 无法解析！！待相关人员更新

    /**
     * @param $arr
     *
     * @return CommApplyEvent
     */
    static public function ParseFromArray($arr)
    {
        $info = new CommApplyEvent();

        $info->apply_data = Utils::arrayGet($arr, "apply_data");

        return $info;
    }
}