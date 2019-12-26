<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\ServiceProvider;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class GetRegisterCodeReq
{
    public $template_id = null;  // string
    public $corp_name = null;    // string
    public $admin_name = null;   // string
    public $admin_mobile = null; // string

    /**
     * @return array
     * @throws ParameterError
     */
    public function FormatArgs()
    {
        Utils::checkNotEmptyStr($this->template_id, "template_id");

        $args = array();

        Utils::setIfNotNull($this->template_id, "template_id", $args);
        Utils::setIfNotNull($this->corp_name, "corp_name", $args);
        Utils::setIfNotNull($this->admin_name, "admin_name", $args);
        Utils::setIfNotNull($this->admin_mobile, "admin_mobile", $args);

        return $args;
    }
}