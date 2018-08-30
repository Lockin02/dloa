<?php
/**
 * @author show
 * @Date 2014��8��26�� 14:35:45
 * @version 1.0
 * @description:��Ʊ����(���) Model��
 */
class model_finance_invoice_invoiceDetailCur extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoice_detail_currency";
        $this->sql_map = "finance/invoice/invoiceDetailCurSql.php";
        parent::__construct();
    }

    /**
     * ��ʾ��Ʊ��ϸ
     * 2010-12-25
     */
    function getDetailByInvoiceId($invoiceId) {
        return $this->findAll(array('invoiceId' => $invoiceId));
    }
}