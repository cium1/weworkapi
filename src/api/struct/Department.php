<?php

namespace Cium\WeWorkApi\api\struct;

use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\Utils;

class Department
{
    public $name = null;     // string
    public $parentid = null; // uint
    public $order = null;    // uint
    public $id = null;       // uint

    /**
     * @param $department
     *
     * @return array
     */
    static public function Department2Array($department)
    {
        $args = array();

        Utils::setIfNotNull($department->name, "name", $args);
        Utils::setIfNotNull($department->parentid, "parentid", $args);
        Utils::setIfNotNull($department->order, "order", $args);
        Utils::setIfNotNull($department->id, "id", $args);

        return $args;
    }

    /**
     * @param $arr
     *
     * @return Department
     */
    static public function Array2Department($arr)
    {
        $department = new Department();

        $department->name = Utils::arrayGet($arr, "name");
        $department->parentid = Utils::arrayGet($arr, "parentid");
        $department->order = Utils::arrayGet($arr, "order");
        $department->id = Utils::arrayGet($arr, "id");

        return $department;
    }

    /**
     * @param $arr
     *
     * @return array
     */
    static public function Array2DepartmentList($arr)
    {
        $list = $arr["department"];

        $departmentList = array();
        if (is_array($list)) {
            foreach ($list as $item) {
                $department = self::Array2Department($item);
                $departmentList[] = $department;
            }
        }
        return $departmentList;
    }

    /**
     * @param $department
     *
     * @throws ParameterError
     */
    static public function CheckDepartmentCreateArgs($department)
    {
        Utils::checkNotEmptyStr($department->name, "department name");
        Utils::checkIsUInt($department->parentid, "parentid");
    }

    /**
     * @param $department
     *
     * @throws ParameterError
     */
    static public function CheckDepartmentUpdateArgs($department)
    {
        Utils::checkIsUInt($department->id, "department id");
    }

} // class
