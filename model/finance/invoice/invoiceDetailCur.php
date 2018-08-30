<?php
/**
 * @author show
 * @Date 2014年8月26日 14:35:45
 * @version 1.0
 * @description:开票内容(外币) Model层
 */
class model_finance_invoice_invoiceDetailCur extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoice_detail_currency";
        $this->sql_map = "finance/invoice/invoiceDetailCurSql.php";
        parent::__construct();
    }

    /**
     * 显示开票详细
     * 2010-12-25
     */
    function getDetailByInvoiceId($invoiceId) {
        return $this->findAll(array('invoiceId' => $invoiceId));
    }
}