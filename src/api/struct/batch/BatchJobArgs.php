<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\batch;


class BatchJobArgs
{
    public $media_id = null;  // string
    public $to_invite = null; // bool, 是否邀请新建的成员使用企业微信（将通过微信服务通知或短信或邮件下发邀请，每天自动下发一次，最多持续3个工作日）
    public $callback = null;  // CallBack
} // class BatchJobRequest