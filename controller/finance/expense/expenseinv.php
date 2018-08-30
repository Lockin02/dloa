<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:18:08
 * @version 1.0
 * @description:目测是发票金额汇总表控制层
 */
class controller_finance_expense_expenseinv extends controller_base_action
{

    function __construct() {
        $this->objName = "expenseinv";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * 跳转到目测是发票金额汇总表列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到新增目测是发票金额汇总表页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $rows = $service->list_d ();

        if($rows && is_array($rows)){
            foreach ($rows as $k => $v){
                //获取发票类型
                $sql = "select id,name from bill_type where ID = '{$v['BillTypeID']}';";
                $billType = $this->service->_db->get_one($sql);
                $rows[$k]['BillType'] = ($billType && isset($billType['name']))? $billType['name'] : '';
            }
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
     * 跳转到编辑目测是发票金额汇总表页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看目测是发票金额汇总表页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 跳转到编辑发票类型部分
     */
    function c_toEitBillTypeID() {
        $this->assignFunc($_GET);
        $this->assign('newBillTypeID', $this->service->initBillOption_d($_GET['BillTypeID']));
        $this->view('editbilltypeid');
    }

    /**
     * 编辑方法
     */
    function c_editBillTypeID() {
        echo $this->service->editBillTypeID_d($_POST['billTypeId'], $_POST['newBillTypeId'], $_POST['BillNo']) ? 1 : 0;
    }

    /**
     *  ajax获取发票信息
     */
    function c_ajaxGetBillDetail() {
        //获取明细信息
        $billDetail = $this->service->getInvDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initInvDetailViewEdit_d($billDetail));
    }
}