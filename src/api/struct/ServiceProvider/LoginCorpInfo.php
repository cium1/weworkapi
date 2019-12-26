<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\Utils;

class LoginCorpInfo
{
    public $corpid = null; // string

    /**
     * @param $arr
     *
     * @return LoginCorpInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new LoginCorpInfo();

        $info->corpid = Utils::arrayGet($arr, "corpid");

        return $info;
    }
}