<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


class LoginAuthInfo
{
    public $department = null; // PartyInfo Array

    /**
     * @param $arr
     *
     * @return LoginAuthInfo
     */
    static public function ParseFromArray($arr)
    {
        $info = new LoginAuthInfo();

        foreach ($arr["department"] as $item) {
            $info->department[] = PartyInfo::ParseFromArray($item);
        }
        return $info;
    }
}