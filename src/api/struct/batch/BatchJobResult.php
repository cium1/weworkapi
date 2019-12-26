<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\batch;


class BatchJobResult
{
    const STATUS_PENDING = 1;
    const STATUS_STARTED = 2;
    const STATUS_FINISHED = 3;

    public $status = null;     // uint, 任务状态，整型，1表示任务开始，2表示任务进行中，3表示任务已完成
    public $type = null;       // string, 操作类型，目前分别有：sync_user(增量更新成员) replace_user(全量覆盖成员) replace_party(全量覆盖部门)
    public $total = null;      // uint, 任务运行总条数
    public $percentage = null; // uint, 目前运行百分比，当任务完成时为100
    public $result = null;     // 参考文档
} // BatchJobResult