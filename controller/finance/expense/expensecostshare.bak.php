<?php

/**
 * @author Show
 * @Date 2016��1��27�� ������ 11:07:46
 * @version 1.0
 * @description:���������̯��ϸ(���ű���) ���Ʋ�
 */
class controller_finance_expense_expensecostshare extends controller_base_action
{

    function __construct() {
        $this->objName = "expensecostshare";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     *  ajax��ȡ��Ʊ��Ϣ
     */
    function c_ajaxGetCostDetail() {
        //��ȡ��ϸ��Ϣ
        $billDetail = $this->service->getBillDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initBillDetailViewEdit_d($billDetail));
    }

    /**
     * �༭������ϸ �� ������������
     */
    function c_toEditDetail() {
        $this->assignFunc($_GET);
        // ��ȡ���ý��
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
        // ��̯˵��
        $rs = $this->service->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
        		'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('editdetail');
    }

    /**
     * �༭������ϸ
     */
    function c_editDetail() {  	
        //�����޸�
		$rs = $this->service->editDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('�޸ĳɹ�');parent.show_pageDetailCostshare();parent.tb_remove();</script>";
        } else {
            msg('�޸�ʧ��');
        }
    }
}