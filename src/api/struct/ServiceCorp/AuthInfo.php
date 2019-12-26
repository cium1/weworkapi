<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


use Cium\WeWorkApi\api\struct\AgentBriefEx;

class AuthInfo
{
    public $agent = null; // AgentBriefEx array

    /**
     * @param $arr
     *
     * @return AuthInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new AuthInfo();

        foreach ($arr["agent"] as $item) {
            $info->agent[] = AgentBriefEx::ParseFromArray($item);
        }

        return $info;
    }
}