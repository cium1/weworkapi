<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


use Cium\WeWorkApi\utils\Utils;

class AuthCorpInfo
{
    public $corpid = null;               // string
    public $corp_name = null;            // string
    public $corp_type = null;            // string
    public $corp_square_logo_url = null; // string
    public $corp_user_max = null;        // uint
    public $corp_agent_max = null;       // uint
    public $corp_full_name = null;       // string
    public $verified_end_time = null;    // uint
    public $subject_type = null;         // uint
    public $corp_wxqrcode = null;        // string

    /**
     * @param $arr
     *
     * @return AuthCorpInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new AuthCorpInfo();

        $info->corpid = Utils::arrayGet($arr, "corpid");
        $info->corp_name = Utils::arrayGet($arr, "corp_name");
        $info->corp_type = Utils::arrayGet($arr, "corp_type");
        $info->corp_square_logo_url = Utils::arrayGet($arr, "corp_square_logo_url");
        $info->corp_user_max = Utils::arrayGet($arr, "corp_user_max");
        $info->corp_agent_max = Utils::arrayGet($arr, "corp_agent_max");
        $info->corp_full_name = Utils::arrayGet($arr, "corp_full_name");
        $info->verified_end_time = Utils::arrayGet($arr, "verified_end_time");
        $info->subject_type = Utils::arrayGet($arr, "subject_type");
        $info->corp_wxqrcode = Utils::arrayGet($arr, "corp_wxqrcode");

        return $info;
    }
}