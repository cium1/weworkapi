<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\oauth;


use Cium\WeWorkApi\utils\Utils;

class UserInfoByCode
{
    public $UserId = null;      // string
    public $DeviceId = null;    // string
    public $user_ticket = null; // string
    public $expires_in = null;  // uint
    public $OpenId = null;      // string

    /**
     * @param $arr
     *
     * @return UserInfoByCode
     */
    static public function Array2UserInfoByCode($arr)
    {
        $info = new UserInfoByCode();

        $info->UserId = Utils::arrayGet($arr, "UserId");
        $info->DeviceId = Utils::arrayGet($arr, "DeviceId");
        $info->user_ticket = Utils::arrayGet($arr, "user_ticket");
        $info->expires_in = Utils::arrayGet($arr, "expires_in");
        $info->OpenId = Utils::arrayGet($arr, "OpenId");

        return $info;
    }
}