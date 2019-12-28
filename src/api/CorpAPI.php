<?php
/*
 * Copyright (C) 2017 All rights reserved.
 *   
 * @File CorpAPI.php
 * @Brief : 为企业开放的接口
 * @Author abelzhu, abelzhu@tencent.com
 * @Version 1.0
 * @Date 2017-12-26
 *
 */

namespace Cium\WeWorkApi\api;

use Cium\WeWorkApi\api\struct\Agent;
use Cium\WeWorkApi\api\struct\ApprovalData\ApprovalDataList;
use Cium\WeWorkApi\api\struct\batch\Batch;
use Cium\WeWorkApi\api\struct\batch\BatchJobArgs;
use Cium\WeWorkApi\api\struct\CheckinData\CheckinDataList;
use Cium\WeWorkApi\api\struct\CheckinOption\CheckinOption;
use Cium\WeWorkApi\api\struct\ExternalContact\WelcomeMsg;
use Cium\WeWorkApi\api\struct\invoice\BatchGetInvoiceInfoReq;
use Cium\WeWorkApi\api\struct\invoice\BatchUpdateInvoiceStatusReq;
use Cium\WeWorkApi\api\struct\menu\Menu;
use Cium\WeWorkApi\api\struct\message\Message;
use Cium\WeWorkApi\api\struct\oauth\UserDetailByUserTicket;
use Cium\WeWorkApi\api\struct\oauth\UserInfoByCode;
use Cium\WeWorkApi\api\struct\pay\PayWwSptrans2PocketReq;
use Cium\WeWorkApi\api\struct\pay\QueryWorkWxRedpackReq;
use Cium\WeWorkApi\api\struct\pay\QueryWwSptrans2PocketReq;
use Cium\WeWorkApi\api\struct\pay\SendWorkWxRedpackReq;
use Cium\WeWorkApi\api\struct\tag\Tag;
use Cium\WeWorkApi\api\struct\user\User;
use Cium\WeWorkApi\utils\error\HttpError;
use Cium\WeWorkApi\utils\HttpUtils;
use Cium\WeWorkApi\utils\error\InternalError;
use Cium\WeWorkApi\utils\error\NetWorkError;
use Cium\WeWorkApi\utils\error\ParameterError;
use Cium\WeWorkApi\utils\error\QyApiError;
use Cium\WeWorkApi\utils\error\SysError;
use Cium\WeWorkApi\utils\Redis;
use Cium\WeWorkApi\utils\Utils;
use Cium\WeWorkApi\api\struct\Department;


class CorpAPI extends API
{
    private $corpId = null;
    private $secret = null;
    private $redis = null;
    protected $accessToken = null;

    /**
     * CorpAPI constructor.
     *
     * @brief __construct : 构造函数，
     * @note  企业进行自定义开发调用, 请传参 corpid + secret, 不用关心accesstoken，本类会自动获取并刷新
     *
     * @param null              $corpId
     * @param null              $secret
     * @param struct\Redis|null $redis
     *
     * @throws ParameterError
     */
    public function __construct($corpId = null, $secret = null, \Cium\WeWorkApi\api\struct\Redis $redis = null)
    {
        Utils::checkNotEmptyStr($corpId, "corpid");
        Utils::checkNotEmptyStr($secret, "secret");

        $this->corpId = $corpId;
        $this->secret = $secret;
        $this->redis = $redis;
    }


    // ------------------------- access token ---------------------------------

    /**
     * @brief  GetAccessToken : 获取 accesstoken，不用主动调用
     * @return string|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    protected function GetAccessToken()
    {
        if (!Utils::notEmptyStr($this->accessToken)) {
            $this->RefreshAccessToken();
        }
        return $this->accessToken;
    }

    /**
     * @return string|void
     * @throws ParameterError
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws QyApiError
     */
    protected function RefreshAccessToken()
    {
        if (!Utils::notEmptyStr($this->corpId) || !Utils::notEmptyStr($this->secret)) {
            throw new ParameterError("invalid corpid or secret");
        }
        $redis = Redis::getInstance([
            'host'     => $this->redis->host,
            'port'     => $this->redis->port,
            'timeout'  => $this->redis->timeout,
            'password' => $this->redis->password,
        ]);
        if (!$redis->exists($this->corpId)) {
            $url = HttpUtils::MakeUrl("/cgi-bin/gettoken?corpid={$this->corpId}&corpsecret={$this->secret}");
            $this->_HttpGetParseToJson($url, false);
            $this->_CheckErrCode();
            $redis->set($this->corpId, $this->rspJson["access_token"], $this->rspJson['expires_in']);
        }
        $this->accessToken = $redis->get($this->corpId);
        //$this->accessToken = $this->rspJson["access_token"];
    }

    // ------------------------- 成员管理 -------------------------------------

    /**
     * @brief UserCreate : 创建成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10018
     *
     * @param User $user
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserCreate(User $user)
    {
        User::CheckUserCreateArgs($user);
        $args = Utils::Object2Array($user);
        self::_HttpCall(self::USER_CREATE, 'POST', $args);
    }


    /**
     * @brief  UserGet : 读取成员
     *
     * @link   https://work.weixin.qq.com/api/doc#10019
     *
     * @param string $userid
     *
     * @return User
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserGet($userid)
    {
        Utils::checkNotEmptyStr($userid, "userid");
        self::_HttpCall(self::USER_GET, 'GET', array('userid' => $userid));
        return User::Array2User($this->rspJson);
    }


    /**
     * @brief UserUpdate : 更新成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10020
     *
     * @param User $user
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserUpdate(User $user)
    {
        User::CheckUserUpdateArgs($user);
        $args = Utils::Object2Array($user);
        self::_HttpCall(self::USER_UPDATE, 'POST', $args);
    }


    /**
     * @brief UserDelete : 删除成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10030
     *
     * @param string $userid
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserDelete($userid)
    {
        Utils::checkNotEmptyStr($userid, "userid");
        self::_HttpCall(self::USER_DELETE, 'GET', array('userid' => $userid));
    }


    /**
     * @brief UserBatchDelete : 批量删除成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10060
     *
     * @param array $userIdList
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserBatchDelete(array $userIdList)
    {
        User::CheckUserBatchDeleteArgs($userIdList);
        $args = array("useridlist" => $userIdList);
        self::_HttpCall(self::USER_BATCH_DELETE, 'POST', $args);
    }


    /**
     * @brief  UserSimpleList : 获取部门成员
     *
     * @link   https://work.weixin.qq.com/api/doc#10061
     *
     * @param int $department_id
     * @param int $fetchChild 1/0是否递归获取子部门下面的成员
     *
     * @return array
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserSimpleList($department_id, $fetchChild)
    {
        Utils::checkIsUInt($department_id, "department_id");
        self::_HttpCall(self::USER_SIMPLE_LIST, 'GET', array('department_id' => $department_id, 'fetch_child' => $fetchChild));
        return User::Array2UserList($this->rspJson);
    }

    /**
     * @brief UserList : 获取部门成员详情
     *
     * @link  https://work.weixin.qq.com/api/doc#10063
     *
     * @param int $departmentId
     * @param int $fetchChild 1/0是否递归获取子部门下面的成员
     *
     * @return array
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserList($departmentId, $fetchChild)
    {
        Utils::checkIsUInt($departmentId, "departmentId");
        self::_HttpCall(self::USER_LIST, 'GET', array('department_id' => $departmentId, 'fetch_child' => $fetchChild));
        return User::Array2UserList($this->rspJson);
    }


    /**
     * @brief UserId2OpenId : userid转openid
     *
     * @link  https://work.weixin.qq.com/api/doc#11279
     *
     * @param string      $userid
     * @param string      $openId
     * @param string|null $agentid
     * @param string|null $appId
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserId2OpenId($userid, &$openId, $agentid = null, &$appId = null)
    {
        Utils::checkNotEmptyStr($userid, "userid");
        if (is_null($agentid)) {
            $args = array("userid" => $userid);
        } else {
            $args = array("userid" => $userid, "agentid" => $agentid);
        }
        self::_HttpCall(self::USER_ID_TO_OPENID, 'POST', $args);
        $openId = Utils::arrayGet($this->rspJson, "openid");
        $appId = Utils::arrayGet($this->rspJson, "appid");
    }


    /**
     * @brief openId2UserId : openid转userid
     *
     * @link  https://work.weixin.qq.com/api/doc#11279
     *
     * @param string $openId
     * @param string $userid
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function openId2UserId($openId, &$userid)
    {
        Utils::checkNotEmptyStr($openId, "openid");
        $args = array("openid" => $openId);
        self::_HttpCall(self::OPENID_TO_USER_ID, 'POST', $args);
        $userid = Utils::arrayGet($this->rspJson, "userid");
    }


    /**
     * @brief UserAuthSuccess : 二次验证
     *
     * @link  https://work.weixin.qq.com/api/doc#11378
     *
     * @param string $userid
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UserAuthSuccess($userid)
    {
        Utils::checkNotEmptyStr($userid, "userid");
        self::_HttpCall(self::USER_AUTH_SUCCESS, 'GET', array('userid' => $userid));
    }

    // ------------------------- 部门管理 -------------------------------------

    /**
     * @brief   DepartmentCreate : 创建部门
     *
     * @link    https://work.weixin.qq.com/api/doc#10076
     *
     * @param Department $department
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function DepartmentCreate(Department $department)
    {
        Department::CheckDepartmentCreateArgs($department);
        $args = Department::Department2Array($department);
        self::_HttpCall(self::DEPARTMENT_CREATE, 'POST', $args);
        return Utils::arrayGet($this->rspJson, "id");
    }


    /**
     * @brief DepartmentUpdate : 更新部门
     *
     * @link  https://work.weixin.qq.com/api/doc#10077
     *
     * @param Department $department
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function DepartmentUpdate(Department $department)
    {
        Department::CheckDepartmentUpdateArgs($department);
        $args = Department::Department2Array($department);
        self::_HttpCall(self::DEPARTMENT_UPDATE, 'POST', $args);
    }


    /**
     * @brief DepartmentDelete : 删除部门
     *
     * @link  https://work.weixin.qq.com/api/doc#10079
     *
     * @param int $departmentId
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function DepartmentDelete($departmentId)
    {
        Utils::checkIsUInt($departmentId, "departmentId");
        self::_HttpCall(self::DEPARTMENT_DELETE, 'GET', array('id' => $departmentId));
    }


    /**
     * @brief  DepartmentList : 获取部门列表
     *
     * @link   https://work.weixin.qq.com/api/doc#10093
     *
     * @param null $departmentId
     *
     * @return array
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function DepartmentList($departmentId = null)
    {
        self::_HttpCall(self::DEPARTMENT_LIST, 'GET', array('id' => $departmentId));
        return Department::Array2DepartmentList($this->rspJson);
    }

    // ------------------------- 标签管理 -------------------------------------

    /**
     * @brief  TagCreate : 创建标签
     *
     * @link   https://work.weixin.qq.com/api/doc#10915
     *
     * @param Tag $tag
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagCreate(Tag $tag)
    {
        Tag::CheckTagCreateArgs($tag);
        $args = Tag::Tag2Array($tag);
        self::_HttpCall(self::TAG_CREATE, 'POST', $args);
        return Utils::arrayGet($this->rspJson, "tagid");
    }


    /**
     * @brief TagUpdate : 更新标签名字
     *
     * @link  https://work.weixin.qq.com/api/doc#10919
     *
     * @param Tag $tag
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagUpdate(Tag $tag)
    {
        Tag::CheckTagUpdateArgs($tag);
        $args = Tag::Tag2Array($tag);
        self::_HttpCall(self::TAG_UPDATE, 'POST', $args);
    }


    /**
     * @brief TagDelete : 删除标签
     *
     * @link  https://work.weixin.qq.com/api/doc#10920
     *
     * @param $tagid
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagDelete($tagid)
    {
        Utils::checkIsUInt($tagid, "tagid");
        self::_HttpCall(self::TAG_DELETE, 'GET', array('tagid' => $tagid));
    }


    /**
     * @brief   TagGetUser : 获取标签成员
     *
     * @link    https://work.weixin.qq.com/api/doc#10921
     *
     * @param $tagid
     *
     * @return Tag
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagGetUser($tagid)
    {
        Utils::checkIsUInt($tagid, "tagid");
        self::_HttpCall(self::TAG_GET_USER, 'GET', array('tagid' => $tagid));
        return Tag::Array2Tag($this->rspJson);
    }


    /**
     * @brief TagAddUser : 增加标签成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10923
     *
     * @note  1: userIdList/partyIdList 不能同时为空
     * @note  2: 如果存在不合法的 userid/partyid, 不会throw Exception，但是会填充invalidUserIdList/invalidPartyIdList
     *
     * @param int         $tagid
     * @param array       $userIdList
     * @param array       $partyIdList
     * @param             $invalidUserIdList
     * @param             $invalidPartyIdList
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagAddUser($tagid, $userIdList, $partyIdList, &$invalidUserIdList, &$invalidPartyIdList)
    {
        Tag::CheckTagAddUserArgs($tagid, $userIdList, $partyIdList);
        $args = Tag::ToTagAddUserArray($tagid, $userIdList, $partyIdList);

        self::_HttpCall(self::TAG_ADD_USER, 'POST', $args);

        $invalidUserIdList_string = Utils::arrayGet($this->rspJson, "invalidlist");
        $invalidUserIdList = explode('|', $invalidUserIdList_string);
        $invalidPartyIdList = Utils::arrayGet($this->rspJson, "invalidparty");
    }


    /**
     * @brief TagDeleteUser : 删除标签成员
     *
     * @link  https://work.weixin.qq.com/api/doc#10925
     *
     * @note  1: userIdList/partyIdList 不能同时为空
     * @note  2: 如果存在不合法的 userid/partyid, 不会throw Exception，但是会填充invalidUserIdList/invalidPartyIdList
     *
     * @param int        $tagid
     * @param            $userIdList
     * @param int|array  $partyIdList
     * @param            $invalidUserIdList
     * @param            $invalidPartyIdList
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagDeleteUser($tagid, $userIdList, $partyIdList, &$invalidUserIdList, &$invalidPartyIdList)
    {
        Tag::CheckTagAddUserArgs($tagid, $userIdList, $partyIdList);
        $args = Tag::ToTagAddUserArray($tagid, $userIdList, $partyIdList);

        self::_HttpCall(self::TAG_DELETE_USER, 'POST', $args);

        $invalidUserIdList_string = utils::arrayGet($this->rspJson, "invalidlist");
        $invalidUserIdList = explode('|', $invalidUserIdList_string);
        $invalidPartyIdList = utils::arrayGet($this->rspJson, "invalidparty");
    }


    /**
     * @brief   TagGetList : 获取标签列表
     *
     * @link    https://work.weixin.qq.com/api/doc#10926
     *
     * @return array
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TagGetList()
    {
        self::_HttpCall(self::TAG_GET_LIST, 'GET', array());
        return Tag::Array2TagList($this->rspJson);
    }


    // ------------------------- 异步任务 -------------------------------------

    /**
     * @param BatchJobArgs $batchJobArgs
     * @param string       $jobType
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    private function BatchJob(BatchJobArgs $batchJobArgs, $jobType)
    {
        Batch::CheckBatchJobArgs($batchJobArgs);

        $args = Utils::Object2Array($batchJobArgs);

        $url = HttpUtils::MakeUrl("/cgi-bin/batch/{$jobType}?access_token=ACCESS_TOKEN");
        $this->_HttpPostParseToJson($url, $args);
        $this->_CheckErrCode();

        return Utils::arrayGet($this->rspJson, "jobid");
    }


    /**
     * @brief  BatchSyncUser : 增量更新成员
     *
     * @link   https://work.weixin.qq.com/api/doc#10138/增量更新成员
     *
     * @param BatchJobArgs $batchJobArgs
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchSyncUser(BatchJobArgs $batchJobArgs)
    {
        return self::BatchJob($batchJobArgs, "syncuser");
    }


    /**
     * @brief   BatchReplaceUser : 全量覆盖成员
     *
     * @link    https://work.weixin.qq.com/api/doc#10138/全量覆盖成员
     *
     * @param BatchJobArgs $batchJobArgs
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchReplaceUser(BatchJobArgs $batchJobArgs)
    {
        return self::BatchJob($batchJobArgs, "replaceuser");
    }


    /**
     * @brief   BatchReplaceParty : 全量覆盖部门
     *
     * @link    https://work.weixin.qq.com/api/doc#10138/全量覆盖部门
     *
     * @param BatchJobArgs $batchJobArgs
     *
     * @return mixed|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchReplaceParty(BatchJobArgs $batchJobArgs)
    {
        return self::BatchJob($batchJobArgs, "replaceparty");
    }


    /**
     * @brief   BatchJobGetResult : 获取异步任务结果
     *
     * @link    https://work.weixin.qq.com/api/doc#10138/获取异步任务结果
     *
     * @param $jobId
     *
     * @return struct\batch\BatchJobResult
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchJobGetResult($jobId)
    {
        self::_HttpCall(self::BATCH_JOB_GET_RESULT, 'GET', array('jobid' => $jobId));
        return Batch::Array2BatchJobResult($this->rspJson);
    }

    // ------------------------- 邀请成员 --------------------------------------


    /**
     * @brief BatchInvite : 邀请成员
     *
     * @link  https://work.weixin.qq.com/api/doc#12543
     *
     * @param           $userIdList
     * @param int|array $partyIdList
     * @param int|array $tagIdList
     * @param           $invalidUserIdList
     * @param int|array $invalidPartyIdList
     * @param int|array $invalidTagIdList
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchInvite($userIdList, $partyIdList, $tagIdList, &$invalidUserIdList, &$invalidPartyIdList, &$invalidTagIdList)
    {
        if (is_null($userIdList) && is_null($partyIdList) && is_null($tagIdList)) {
            throw new QyApiError("input can not be all null");
        }
        $args = array('user' => $userIdList, 'party' => $partyIdList, 'tag' => $tagIdList);
        self::_HttpCall(self::BATCH_INVITE, 'POST', $args);

        $invalidUserIdList = Utils::arrayGet($this->rspJson, 'invaliduser');
        $invalidPartyIdList = Utils::arrayGet($this->rspJson, 'invalidparty');
        $invalidTagIdList = Utils::arrayGet($this->rspJson, 'invalidtag');
    }

    // ------------------------- 应用管理 --------------------------------------

    /**
     * @brief   AgentGet : 获取应用
     *
     * @link    https://work.weixin.qq.com/api/doc#10087
     *
     * @param $agentid
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function AgentGet($agentid)
    {
        self::_HttpCall(self::AGENT_GET, 'GET', array('agentid' => $agentid));
        return Agent::Array2Agent($this->rspJson);
    }


    /**
     * @brief AgentSet : 设置应用
     *
     * @link  https://work.weixin.qq.com/api/doc#10088
     *
     * @param $agent
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function AgentSet($agent)
    {
        Agent::CheckAgentSetArgs($agent);
        $args = Agent::Agent2Array($agent);
        self::_HttpCall(self::AGENT_SET, 'POST', $args);
    }


    /**
     * @brief  AgentList : 获取应用列表
     *
     * @link   https://work.weixin.qq.com/api/doc#11214
     * @return array
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function AgentGetList()
    {
        self::_HttpCall(self::AGENT_GET_LIST, 'GET', array());
        return Agent::Array2AgentList($this->rspJson);
    }

    // ------------------------- 自定义菜单 -----------------------------------


    /**
     * @brief MenuCreate : 创建菜单
     *
     * @link  https://work.weixin.qq.com/api/doc#10786
     *
     * @param      $agentid
     * @param Menu $menu
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MenuCreate($agentid, Menu $menu)
    {
        Menu::CheckMenuCreateArgs($agentid, $menu);
        $args = Utils::Object2Array($menu);
        self::_HttpCall(self::MENU_CREATE . "&agentid={$agentid}", 'POST', $args);
    }


    /**
     * @brief  MenuGet : 获取菜单
     *
     * @link   https://work.weixin.qq.com/api/doc#10787
     *
     * @param $agentid
     *
     * @return Menu
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MenuGet($agentid)
    {
        self::_HttpCall(self::MENU_GET, 'GET', array('agentid' => $agentid));
        return Menu::Array2Menu($this->rspJson);
    }


    /**
     * @brief MenuGet : 删除菜单
     *
     * @link  https://work.weixin.qq.com/api/doc#10788
     *
     * @param $agentid
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MenuDelete($agentid)
    {
        self::_HttpCall(self::MENU_DELETE, 'GET', array('agentid' => $agentid));
    }

    // --------------------------- 消息推送 -----------------------------------


    /**
     * @brief MessageSend : 发送消息
     *
     * @link  https://work.weixin.qq.com/api/doc#10167
     *
     * @param Message $message
     * @param         $invalidUserIdList
     * @param         $invalidPartyIdList
     * @param         $invalidTagIdList
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MessageSend(Message $message, &$invalidUserIdList, &$invalidPartyIdList, &$invalidTagIdList)
    {
        $message->CheckMessageSendArgs();
        $args = $message->Message2Array();

        self::_HttpCall(self::MESSAGE_SEND, 'POST', $args);

        $invalidUserIdList_string = Utils::arrayGet($this->rspJson, "invaliduser");
        $invalidUserIdList = explode('|', $invalidUserIdList_string);

        $invalidPartyIdList_string = Utils::arrayGet($this->rspJson, "invalidparty");
        $temp = explode('|', $invalidPartyIdList_string);
        foreach ($temp as $item) {
            $invalidPartyIdList[] = intval($item);
        }

        $invalidTagIdList_string = Utils::arrayGet($this->rspJson, "invalidtag");
        $temp = explode('|', $invalidTagIdList_string);
        foreach ($temp as $item) {
            $invalidTagIdList[] = intval($item);
        }
    }

    // --------------------------- 素材管理 -----------------------------------

    /**
     * @brief  MediaUpload : 上传临时素材
     *
     * @link   https://work.weixin.qq.com/api/doc#10112
     *
     * @param string $filePath 文件路径
     * @param string $type     媒体文件类型，分别有图片（image）、语音（voice）、视频（video），普通文件（file）
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MediaUpload($filePath, $type)
    {
        Utils::checkNotEmptyStr($filePath, "filePath");
        Utils::checkNotEmptyStr($type, "type");
        if (!file_exists($filePath)) {
            throw new QyApiError("file not exists");
        }

        // 兼容php5.3-5.6 curl模块的上传操作
        $args = array();
        if (class_exists('\CURLFile')) {
            $args = array('media' => new \CURLFile(realpath($filePath), 'application/octet-stream', basename($filePath)));
        } else {
            $args = array('media' => '@' . realpath($filePath));
        }

        $url = HttpUtils::MakeUrl("/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type={$type}");
        $this->_HttpPostParseToJson($url, $args, true, true/*isPostFile*/);
        $this->_CheckErrCode();

        return $this->rspJson["media_id"];
    }

    /**
     * @param $buffer
     * @param $type
     *
     * @return mixed
     * @throws \Exception
     */
    public function MediaUploadByBuffer($buffer, $type)
    {
        $tmpPath = self::WriteTmpFile($buffer);

        try {
            $ret = $this->mediaUpload($tmpPath, $type);
            unlink($tmpPath);
            return $ret;
        } catch (\Exception $ex) {
            unlink($tmpPath);
            throw $ex;
        }
    }


    /**
     * @brief  MediaGet : 获取临时素材
     *
     * @link   https://work.weixin.qq.com/api/doc#10115
     *
     * @param $media_id
     *
     * @return string|null
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function MediaGet($media_id)
    {
        Utils::checkNotEmptyStr($media_id, "media_id");
        self::_HttpCall(self::MEDIA_GET, 'GET', array('media_id' => $media_id));
        return $this->rspRawStr;
    }


    /**
     * @brief  MediaGet : 上传永久图片
     *
     * @link   https://work.weixin.qq.com/api/doc#13219
     *
     * @param string $filePath 图片文件路径
     * @param string $md5      图片文件的md5.可以不填
     *
     * @return mixed|string 上传图片后，得到的图片永久URL。注意仅能用于图文消息（mpnews）正文中的图片展示
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UploadImage($filePath, $md5 = null)
    {
        Utils::checkNotEmptyStr($filePath, "filePath");
        if (!file_exists($filePath)) {
            throw new QyApiError("file not exists");
        }

        // 兼容php5.3-5.6 curl模块的上传操作
        $args = array();
        if (class_exists('\CURLFile')) {
            $args = array('media' => new \CURLFile(realpath($filePath), 'application/octet-stream', basename($filePath)));
        } else {
            $args = array('media' => '@' . $filePath);//realpath($filePath));
        }

        var_dump($args);

        $url = HttpUtils::MakeUrl("/cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN");
        if ($md5 != null) {
            $url = $url . "&md5={$md5}";
        }

        $this->_HttpPostParseToJson($url, $args, true, true/*isPostFile*/);
        $this->_CheckErrCode();

        return $this->rspJson["url"];
    }

    // --------------------------- 身份验证 -----------------------------------


    /**
     * @brief  GetUserInfoByCode : 根据code获取成员信息
     *
     * @link   https://work.weixin.qq.com/api/doc#10028/根据code获取成员信息
     *
     * @param string $code
     *
     * @return UserInfoByCode
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function GetUserInfoByCode($code)
    {
        Utils::checkNotEmptyStr($code, "code");
        self::_HttpCall(self::GET_USER_INFO_BY_CODE, 'GET', array('code' => $code));
        return UserInfoByCode::Array2UserInfoByCode($this->rspJson);
    }

    /**
     * @brief  GetUserDetailByUserTicket : 使用user_ticket获取成员详情
     *
     * @link   https://work.weixin.qq.com/api/doc#10028/使用user_ticket获取成员详情
     *
     * @param string $ticket
     *
     * @return UserDetailByUserTicket
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function GetUserDetailByUserTicket($ticket)
    {
        Utils::checkNotEmptyStr($ticket, "ticket");
        $args = array("user_ticket" => $ticket);
        self::_HttpCall(self::GET_USER_DETAIL, 'POST', $args);
        return UserDetailByUserTicket::Array2UserDetailByUserTicket($this->rspJson);
    }

    // ---------------------- 移动端SDK ---------------------------------------

    /**
     * @brief  TicketGet : 获取电子发票ticket
     *
     * @link   https://work.weixin.qq.com/api/doc#10029/获取电子发票ticket
     * @return string
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function TicketGet()
    {
        self::_HttpCall(self::GET_TICKET, 'GET', array('type' => 'wx_card'));
        return $this->rspJson["ticket"];
    }

    /**
     * @brief  JsApiTicketGet : 获取jsapi_ticket
     *
     * @link   https://work.weixin.qq.com/api/doc#10029/获取jsapi_ticket
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function JsApiTicketGet()
    {
        self::_HttpCall(self::GET_JSAPI_TICKET, 'GET', array());
        return $this->rspJson["ticket"];
    }

    /**
     * @brief  JsApiSignatureGet : 计算jsapi的签名
     *
     * @link   https://work.weixin.qq.com/api/doc#10029/%E7%AD%BE%E5%90%8D%E7%AE%97%E6%B3%95
     *
     * @param string $jsapiTicket
     * @param string $nonceStr
     * @param string $timestamp
     * @param string $url
     *
     * @return string
     */
    public function JsApiSignatureGet($jsapiTicket, $nonceStr, $timestamp, $url)
    {
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        return sha1($string);
    }

    // ---------------------- OA数据接口 --------------------------------------

    /**
     * @brief   CheckinOptionGet : 获取打卡规则
     *
     * @link    https://work.weixin.qq.com/api/doc#12423
     *
     * @param int $datetime
     * @param     $useridlist
     *
     * @return CheckinOption
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function CheckinOptionGet($datetime, $useridlist)
    {
        Utils::checkIsUInt($datetime, "datetime");
        Utils::checkNotEmptyArray($useridlist, "useridlist");
        if (count($useridlist) > 100) throw new QyApiError("no more than 100 user once");
        $args = array("datetime" => $datetime, "useridlist" => $useridlist);

        self::_HttpCall(self::GET_CHECKIN_OPTION, 'POST', $args);

        return CheckinOption::ParseFromArray($this->rspJson);
    }


    /**
     * @brief   CheckinDataGet : 获取打卡数据
     *
     * @link    https://work.weixin.qq.com/api/doc#11196
     *
     * @param int $opencheckindatatype
     * @param int $starttime
     * @param int $endtime
     * @param     $useridlist
     *
     * @return CheckinDataList
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function CheckinDataGet($opencheckindatatype, $starttime, $endtime, $useridlist)
    {
        Utils::checkIsUInt($opencheckindatatype, "opencheckindatatype");
        Utils::checkIsUInt($starttime, "starttime");
        Utils::checkIsUInt($endtime, "endtime");
        Utils::checkNotEmptyArray($useridlist, "useridlist");
        if (count($useridlist) > 100) throw new QyApiError("no more than 100 user once");

        $args = array(
            "opencheckindatatype" => $opencheckindatatype,
            "starttime"           => $starttime,
            "endtime"             => $endtime,
            "useridlist"          => $useridlist,
        );
        self::_HttpCall(self::GET_CHECKIN_DATA, 'POST', $args);
        return CheckinDataList::ParseFromArray($this->rspJson);
    }


    /**
     * @brief   ApprovalDataGet : 获取审批数据
     *
     * @link    https://work.weixin.qq.com/api/doc#11228
     *
     * @param      $starttime
     * @param      $endtime
     * @param null $next_spnum
     *
     * @return ApprovalDataList
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function ApprovalDataGet($starttime, $endtime, $next_spnum = null)
    {
        Utils::checkIsUInt($starttime, "starttime");
        Utils::checkIsUInt($endtime, "endtime");

        $args = array();
        Utils::setIfNotNull($starttime, "starttime", $args);
        Utils::setIfNotNull($endtime, "endtime", $args);
        Utils::setIfNotNull($next_spnum, "next_spnum", $args);

        self::_HttpCall(self::GET_APPROVAL_DATA, 'POST', $args);
        return ApprovalDataList::ParseFromArray($this->rspJson);
    }

    // ---------------------- 企业支付 ----------------------------------------

    /**
     * @param $url
     * @param $args
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     */
    static private function _HttpPostXml($url, $args)
    {
        $postData = Utils::Array2Xml("xml", $args);
        $rspRawStr = HttpUtils::httpPost($url, $postData);
        return Utils::Xml2Array($rspRawStr);
    }

    /**
     * @param $rsp
     *
     * @throws QyApiError
     */
    static private function _CheckXmlRetCode($rsp)
    {
        if ($rsp["return_code"] != "SUCCESS") {
            throw new QyApiError("response error:" . $rsp);
        }
    }


    /**
     * @brief  SendWorkWxRedpack : 发放企业红包
     *
     * @link   https://work.weixin.qq.com/api/doc#11543
     *
     * @note   : 本接口只检查通信是否正常，业务结果需调用方自行判断，参看文档
     *
     * @param SendWorkWxRedpackReq $SendWorkWxRedpackReq
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws QyApiError
     */
    static public function SendWorkWxRedpack(SendWorkWxRedpackReq $SendWorkWxRedpackReq)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendworkwxredpack";
        $args = Utils::Object2Array($SendWorkWxRedpackReq);
        $SendWorkWxRedpackRsp = self::_HttpPostXml($url, $args);
        self::_CheckXmlRetCode($SendWorkWxRedpackRsp);
        return $SendWorkWxRedpackRsp;
    }

    /**
     * @brief  QueryWorkWxRedpack : 查询红包记录
     *
     * @link   https://work.weixin.qq.com/api/doc#11544
     *
     * @param QueryWorkWxRedpackReq $QueryWorkWxRedpackReq
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws QyApiError
     */
    static public function QueryWorkWxRedpack(QueryWorkWxRedpackReq $QueryWorkWxRedpackReq)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/queryworkwxredpack";
        $args = Utils::Object2Array($QueryWorkWxRedpackReq);
        $QueryWorkWxRedpackRsp = self::_HttpPostXml($url, $args);
        self::_CheckXmlRetCode($QueryWorkWxRedpackRsp);
        return $QueryWorkWxRedpackRsp;
    }


    /**
     * @brief  PayWwSptrans2Pocket : 向员工付款
     *
     * @link   https://work.weixin.qq.com/api/doc#11545
     *
     * @param PayWwSptrans2PocketReq $PayWwSptrans2PocketReq
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws QyApiError
     */
    static public function PayWwSptrans2Pocket(PayWwSptrans2PocketReq $PayWwSptrans2PocketReq)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/paywwsptrans2pocket";
        $args = Utils::Object2Array($PayWwSptrans2PocketReq);
        $PayWwSptrans2PocketRsp = self::_HttpPostXml($url, $args);
        self::_CheckXmlRetCode($PayWwSptrans2PocketRsp);
        return $PayWwSptrans2PocketRsp;
    }

    /**
     * @brief  QueryWwSptrans2Pocket : 查询付款记录
     *
     * @link   https://work.weixin.qq.com/api/doc#11546
     *
     * @param QueryWwSptrans2PocketReq $QueryWwSptrans2PocketReq
     *
     * @return mixed
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws QyApiError
     */
    static public function QueryWwSptrans2Pocket(QueryWwSptrans2PocketReq $QueryWwSptrans2PocketReq)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/querywwsptrans2pocket";
        $args = Utils::Object2Array($QueryWwSptrans2PocketReq);
        $QueryWwSptrans2Pocketsp = self::_HttpPostXml($url, $args);
        self::_CheckXmlRetCode($QueryWwSptrans2Pocketsp);
        return $QueryWwSptrans2Pocketsp;
    }

    // ---------------------- 电子发票 ----------------------------------------


    /**
     * @brief  GetInvoiceInfo : 查询电子发票
     *
     * @link   https://work.weixin.qq.com/api/doc#11631
     *
     * @param $card_id
     * @param $encrypt_code
     *
     * @return object
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function GetInvoiceInfo($card_id, $encrypt_code)
    {
        Utils::checkNotEmptyStr($card_id, "card_id");
        Utils::checkNotEmptyStr($encrypt_code, "encrypt_code");
        $args = array("card_id" => $card_id, "encrypt_code" => $encrypt_code);
        self::_HttpCall(self::GET_INVOICE_INFO, 'POST', $args);
        return Utils::Array2Object($this->rspJson);
    }

    /**
     * @brief UpdateInvoiceStatus : 更新发票状态
     *
     * @link  https://work.weixin.qq.com/api/doc#11633
     *
     * @param $card_id
     * @param $encrypt_code
     * @param $reimburse_status
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function UpdateInvoiceStatus($card_id, $encrypt_code, $reimburse_status)
    {
        Utils::checkNotEmptyStr($card_id, "card_id");
        Utils::checkNotEmptyStr($encrypt_code, "encrypt_code");
        Utils::checkNotEmptyStr($reimburse_status, "reimburse_status");
        $args = array("card_id" => $card_id, "encrypt_code" => $encrypt_code, "reimburse_status" => $reimburse_status);
        self::_HttpCall(self::UPDATE_INVOICE_STATUS, 'POST', $args);
    }

    /**
     * @brief BatchUpdateInvoiceStatus : 批量更新发票状态
     *
     * @link  https://work.weixin.qq.com/api/doc#11634
     *
     * @param BatchUpdateInvoiceStatusReq $BatchUpdateInvoiceStatusReq
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchUpdateInvoiceStatus(BatchUpdateInvoiceStatusReq $BatchUpdateInvoiceStatusReq)
    {
        $args = Utils::Object2Array($BatchUpdateInvoiceStatusReq);
        self::_HttpCall(self::BATCH_UPDATE_INVOICE_STATUS, 'POST', $args);
    }

    /**
     * @brief  BatchGetInvoiceInfo : 批量查询电子发票
     *
     * @link   https://work.weixin.qq.com/api/doc#11974
     *
     * @param BatchGetInvoiceInfoReq $BatchGetInvoiceInfoReq
     *
     * @return object
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function BatchGetInvoiceInfo(BatchGetInvoiceInfoReq $BatchGetInvoiceInfoReq)
    {
        $args = Utils::Object2Array($BatchGetInvoiceInfoReq);
        self::_HttpCall(self::BATCH_GET_INVOICE_INFO, 'POST', $args);
        return Utils::Array2Object($this->rspJson);
    }

    // ---------------------- 外部联系人 ----------------------------------------

    /**
     * @brief ExternalContactSendNewCustomerGreeting : 发送新客户欢迎语
     *
     * @link  https://work.weixin.qq.com/api/doc/90000/90135/92137
     *
     * @param WelcomeMsg $welcomeMsg
     *
     * @throws HttpError
     * @throws InternalError
     * @throws NetWorkError
     * @throws ParameterError
     * @throws QyApiError
     */
    public function ExternalContactSendNewCustomerGreeting(WelcomeMsg $welcomeMsg)
    {
        WelcomeMsg::checkArgs($welcomeMsg);
        $args = Utils::Object2Array($welcomeMsg);
        self::_HttpCall(self::EXTERNAL_CONTACT_SEND_WELCOME_MSG, 'POST', $args);
    }

    // ------------------------- private --------------------------------------

    /**
     * @param $buffer
     *
     * @return false|string
     * @throws ParameterError
     * @throws \Exception
     */
    static private function WriteTmpFile($buffer)
    {
        Utils::checkNotEmptyStr($buffer, "buffer");

        $tmpPath = tempnam(sys_get_temp_dir(), "qytmpfile");
        $handle = null;

        try {
            $handle = fopen($tmpPath, "wb");
            if ($handle === false) {
                throw new SysError("create tmp file failed");
            }

            $writeBytes = fwrite($handle, $buffer);
            if ($writeBytes === false) {
                throw new SysError("write tmp file failed");
            }
            while ($writeBytes < count($buffer)) {
                $n = fwrite($handle, substr($buffer, $writeBytes));
                if ($n === false) {
                    throw new SysError("write tmp file failed");
                }
                $writeBytes += $n;
            }
            fclose($handle);
        } catch (\Exception $ex) {
            if (!is_null($handle)) {
                fclose($handle);
                unlink($tmpPath);
            }
            throw $ex;
        }
        return $tmpPath;
    }
}
