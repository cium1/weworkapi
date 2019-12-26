<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct\invoice;


class BatchUpdateInvoiceStatusReq
{
    public $openid = null;           // string
    public $reimburse_status = null; // string
    public $invoice_list = null;     // InvoiceItem array
}