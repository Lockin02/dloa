<?php
/**
 * @author show
 * @Date 2014��8��26�� 14:35:45
 * @version 1.0
 * @description:��Ʊ����(���)���Ʋ�
 */
class controller_finance_invoice_invoiceDetailCur extends controller_base_action
{

    function __construct() {
        $this->objName = "invoiceDetailCur";
        $this->objPath = "finance_invoice";
        parent::__construct();
    }

    /**
     * ��ת����Ʊ����(���)�б�
     */
    function c_page() {
        $this->view('list');
    }
}