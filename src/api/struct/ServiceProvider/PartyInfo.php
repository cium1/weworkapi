<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\Utils;

class PartyInfo
{
    public $id = null;       // uint
    public $writable = null; // bool

    /**
     * @param $arr
     *
     * @return PartyInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new PartyInfo();

        $info->id = Utils::arrayGet($arr, "id");
        $info->writable = Utils::arrayGet($arr, "writable");

        return $info;
    }
}