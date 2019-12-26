<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\user;


class ExtattrItem
{
    public $name = null;
    public $value = null;

    /**
     * ExtattrItem constructor.
     *
     * @param null $name
     * @param null $value
     */
    public function __construct($name = null, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
