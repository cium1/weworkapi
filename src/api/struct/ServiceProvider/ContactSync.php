<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\Utils;

class ContactSync
{
    public $access_token = null; // string
    public $expires_in = null;   // uint

    /**
     * @param $arr
     *
     * @return ContactSync
     */
    static public function ParseFromArray($arr)
    {
        $info = new ContactSync();

        $info->access_token = Utils::arrayGet($arr, "access_token");
        $info->expires_in = Utils::arrayGet($arr, "expires_in");

        return $info;
    }
}