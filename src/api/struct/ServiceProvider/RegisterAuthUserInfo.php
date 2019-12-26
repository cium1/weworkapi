<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\Utils;

class RegisterAuthUserInfo
{
    public $email = null;  // string
    public $mobile = null; // string
    public $userid = null; // string

    /**
     * @param $arr
     *
     * @return RegisterAuthUserInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new RegisterAuthUserInfo();

        $info->email = Utils::arrayGet($arr, "email");
        $info->mobile = Utils::arrayGet($arr, "mobile");
        $info->userid = Utils::arrayGet($arr, "userid");

        return $info;
    }
}