<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\invoice;


class InvoiceInfo
{
    public $card_id = null;    // string
    public $begin_time = null; // string
    public $end_time = null;   // string
    public $openid = null;     // string
    public $type = null;       // string
    public $payee = null;      // string
    public $detail = null;     // string
    public $user_info = null;  // InvoiceUserInfo
}