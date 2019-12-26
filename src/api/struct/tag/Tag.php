<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\tag;


use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\error\QyApiError;
use Cium\WeWorkApi\utils\Utils;

class Tag
{
    public $tagname = null;   // string
    public $tagid = null;     // uint
    public $userlist = null;  // TagUser array
    public $partylist = null; // uint array

    /**
     * @param $tag
     *
     * @return array
     */
    static public function Tag2Array($tag)
    {
        $args = array();

        Utils::setIfNotNull($tag->tagname, "tagname", $args);
        Utils::setIfNotNull($tag->tagid, "tagid", $args);

        return $args;
    }

    /**
     * @param $arr
     *
     * @return Tag
     */
    static public function Array2Tag($arr)
    {
        $tag = new Tag();

        $tag->tagname = Utils::arrayGet($arr, "tagname");
        $tag->tagid = Utils::arrayGet($arr, "tagid");

        $userListArr = Utils::arrayGet($arr, "userlist");
        if (!is_null($userListArr)) {
            foreach ($userListArr as $userArr) {
                $user = new TagUser();
                $user->userid = Utils::arrayGet($userArr, "userid");
                $user->name = Utils::arrayGet($userArr, "name");

                $tag->userlist[] = $user;
            }
        }

        $partyListArr = Utils::arrayGet($arr, "partylist");
        if (!is_null($partyListArr)) {
            foreach ($partyListArr as $partyid) {
                $tag->partylist[] = $partyid;
            }
        }

        return $tag;
    }

    /**
     * @param $arr
     *
     * @return array
     */
    static public function Array2TagList($arr)
    {
        $tagList = array();

        $tagListArr = $arr["taglist"];
        foreach ($tagListArr as $item) {
            $tag = self::Array2Tag($item);
            $tagList[] = $tag;
        }

        return $tagList;
    }

    /**
     * @param $tag
     *
     * @throws ParameterError
     */
    static public function CheckTagCreateArgs($tag)
    {
        Utils::checkNotEmptyStr($tag->tagname, "tagname");
    }

    /**
     * @param $tag
     *
     * @throws ParameterError
     */
    static public function CheckTagUpdateArgs($tag)
    {
        Utils::checkIsUInt($tag->tagid, "tagid");
        Utils::checkNotEmptyStr($tag->tagname, "tagname");
    }


    /**
     * @param $tagId
     * @param $userIdList
     * @param $partyIdList
     *
     * @throws ParameterError
     * @throws QyApiError
     */
    static public function CheckTagAddUserArgs($tagId, $userIdList, $partyIdList)
    {
        Utils::checkIsUInt($tagId, "tagid");

        if (0 == count($userIdList) && 0 == count($partyIdList)) {
            throw new QyApiError("userIdList and partyIdList should not be both empty");
        }
    }

    /**
     * @param $tagId
     * @param $userIdList
     * @param $partyIdList
     *
     * @return array
     */
    static public function ToTagAddUserArray($tagId, $userIdList, $partyIdList)
    {
        $args = array();

        Utils::setIfNotNull($tagId, "tagid", $args);
        Utils::setIfNotNull($userIdList, "userlist", $args);
        Utils::setIfNotNull($partyIdList, "partylist", $args);

        return $args;
    }

} // class
