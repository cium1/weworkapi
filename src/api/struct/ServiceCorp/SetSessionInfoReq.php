<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceCorp;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class SetSessionInfoReq
{
    public $pre_auth_code = null; // string
    public $session_info = null;  // SessionInfo

    /**
     * @return array
     * @throws ParameterError
     */
    public function FormatArgs()
    {
        Utils::checkNotEmptyStr($this->pre_auth_code, "pre_auth_code");

        $args = array();

        $args["pre_auth_code"] = $this->pre_auth_code;
        $args["session_info"] = $this->session_info->FormatArgs();

        return $args;
    }
}