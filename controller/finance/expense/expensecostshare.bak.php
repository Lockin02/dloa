<?php

/**
 * @author Show
 * @Date 2016年1月27日 星期三 11:07:46
 * @version 1.0
 * @description:报销申请分摊明细(部门报销) 控制层
 */
class controller_finance_expense_expensecostshare extends controller_base_action
{

    function __construct() {
        $this->objName = "expensecostshare";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     *  ajax获取发票信息
     */
    function c_ajaxGetCostDetail() {
        //获取明细信息
        $billDetail = $this->service->getBillDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initBillDetailViewEdit_d($billDetail));
    }

    /**
     * 编辑费用明细 － 附带金额和类型
     */
    function c_toEditDetail() {
        $this->assignFunc($_GET);
        // 获取费用金额
        $expensedetailDao = new model_finance_expense_expensedetail();
        $rs = $expensedetailDao->findAll(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'CostMoney');
        $costMoney = 0;
        if(!empty($rs)){
        	foreach ($rs as $v){
        		$costMoney += $v['CostMoney'];
        	}
        }
        $this->assign('costMoney',$costMoney);
        // 分摊说明
        $rs = $this->service->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
        		'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('editdetail');
    }

    /**
     * 编辑费用明细
     */
    function c_editDetail() {  	
        //处理修改
		$rs = $this->service->editDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('修改成功');parent.show_pageDetailCostshare();parent.tb_remove();</script>";
        } else {
            msg('修改失败');
        }
    }
}