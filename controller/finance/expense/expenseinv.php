<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:18:08
 * @version 1.0
 * @description:Ŀ���Ƿ�Ʊ�����ܱ���Ʋ�
 */
class controller_finance_expense_expenseinv extends controller_base_action
{

    function __construct() {
        $this->objName = "expenseinv";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * ��ת��Ŀ���Ƿ�Ʊ�����ܱ��б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת������Ŀ���Ƿ�Ʊ�����ܱ�ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $rows = $service->list_d ();

        if($rows && is_array($rows)){
            foreach ($rows as $k => $v){
                //��ȡ��Ʊ����
                $sql = "select id,name from bill_type where ID = '{$v['BillTypeID']}';";
                $billType = $this->service->_db->get_one($sql);
                $rows[$k]['BillType'] = ($billType && isset($billType['name']))? $billType['name'] : '';
            }
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
     * ��ת���༭Ŀ���Ƿ�Ʊ�����ܱ�ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴Ŀ���Ƿ�Ʊ�����ܱ�ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ת���༭��Ʊ���Ͳ���
     */
    function c_toEitBillTypeID() {
        $this->assignFunc($_GET);
        $this->assign('newBillTypeID', $this->service->initBillOption_d($_GET['BillTypeID']));
        $this->view('editbilltypeid');
    }

    /**
     * �༭����
     */
    function c_editBillTypeID() {
        echo $this->service->editBillTypeID_d($_POST['billTypeId'], $_POST['newBillTypeId'], $_POST['BillNo']) ? 1 : 0;
    }

    /**
     *  ajax��ȡ��Ʊ��Ϣ
     */
    function c_ajaxGetBillDetail() {
        //��ȡ��ϸ��Ϣ
        $billDetail = $this->service->getInvDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initInvDetailViewEdit_d($billDetail));
    }
}