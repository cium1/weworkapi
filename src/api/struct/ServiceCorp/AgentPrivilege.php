<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


use Cium\WeWorkApi\utils\Utils;

class AgentPrivilege
{
    public $level = null;       // uint
    public $allow_party = null; // uint array
    public $allow_user = null;  // string array
    public $allow_tag = null;   // uint array
    public $extra_party = null; // uint array
    public $extra_user = null;  // string array
    public $extra_tag = null;   // uint array

    /**
     * @param $arr
     *
     * @return AgentPrivilege
     */
    static public function ParseFromArray($arr)
    {
        $info = new AgentPrivilege();

        $info->level = Utils::arrayGet($arr, "level");
        $info->allow_party = Utils::arrayGet($arr, "allow_party");
        $info->allow_user = Utils::arrayGet($arr, "allow_user");
        $info->allow_tag = Utils::arrayGet($arr, "allow_tag");
        $info->extra_party = Utils::arrayGet($arr, "extra_party");
        $info->extra_user = Utils::arrayGet($arr, "extra_user");
        $info->extra_tag = Utils::arrayGet($arr, "extra_tag");

        return $info;
    }
}