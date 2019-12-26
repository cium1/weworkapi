<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


class GetAuthInfoRsp
{
    public $auth_corp_info = null; // AuthCorpInfo
    public $auth_info = null;      // AuthInfo

    /**
     * @param $arr
     *
     * @return GetAuthInfoRsp
     */
    static public function ParseFromArray($arr)
    {
        $info = new GetAuthInfoRsp();

        if (array_key_exists("auth_corp_info", $arr)) {
            $info->auth_corp_info = AuthCorpInfo::ParseFromArray($arr["auth_corp_info"]);
        }
        if (array_key_exists("auth_info", $arr)) {
            $info->auth_info = AuthInfo::ParseFromArray($arr["auth_info"]);
        }

        return $info;
    }
}