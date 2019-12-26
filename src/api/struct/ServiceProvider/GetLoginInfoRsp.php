<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\Utils;

class GetLoginInfoRsp
{
    public $usertype = null;  // uint
    public $user_info = null; // LoginUserInfo
    public $corp_info = null; // LoginCorpInfo
    public $agent = null;     // LoginAgentInfo array
    public $auth_info = null; // LoginAuthInfo

    /**
     * @param $arr
     *
     * @return GetLoginInfoRsp
     */
    static public function ParseFromArray($arr)
    {
        $info = new GetLoginInfoRsp();

        $info->usertype = Utils::arrayGet($arr, "usertype");

        if (array_key_exists("user_info", $arr)) {
            $info->user_info = LoginUserInfo::ParseFromArray($arr["user_info"]);
        }
        if (array_key_exists("corp_info", $arr)) {
            $info->corp_info = LoginCorpInfo::ParseFromArray($arr["corp_info"]);
        }
        foreach ($arr["agent"] as $item) {
            $info->agent[] = LoginAgentInfo::ParseFromArray($item);
        }
        if (array_key_exists("auth_info", $arr)) {
            $info->auth_info = LoginAuthInfo::ParseFromArray($arr["auth_info"]);
        }

        return $info;
    }
}