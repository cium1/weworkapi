<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


use Cium\WeWorkApi\utils\Utils;

class GetUserDetailBy3rdRsp
{
    public $corpid = null;     // string
    public $userid = null;     // string
    public $name = null;       // string
    public $department = null; // uint array
    public $position = null;   // string
    public $mobile = null;     // string
    public $gender = null;     // string
    public $email = null;      // string
    public $avatar = null;     // string

    /**
     * @param $arr
     *
     * @return GetUserDetailBy3rdRsp
     */
    static public function ParseFromArray($arr)
    {
        $info = new GetUserDetailBy3rdRsp();

        $info->corpid = Utils::arrayGet($arr, "corpid");
        $info->userid = Utils::arrayGet($arr, "userid");
        $info->name = Utils::arrayGet($arr, "name");
        $info->department = Utils::arrayGet($arr, "department");
        $info->position = Utils::arrayGet($arr, "position");
        $info->mobile = Utils::arrayGet($arr, "mobile");
        $info->gender = Utils::arrayGet($arr, "gender");
        $info->email = Utils::arrayGet($arr, "email");
        $info->avatar = Utils::arrayGet($arr, "avatar");

        return $info;
    }
}