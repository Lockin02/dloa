<?php
/**
 * @author show
 * @Date 2014年8月26日 14:35:45
 * @version 1.0
 * @description:开票内容(外币)控制层
 */
class controller_finance_invoice_invoiceDetailCur extends controller_base_action
{

    function __construct() {
        $this->objName = "invoiceDetailCur";
        $this->objPath = "finance_invoice";
        parent::__construct();
    }

    /**
     * 跳转到开票内容(外币)列表
     */
    function c_page() {
        $this->view('list');
    }
}