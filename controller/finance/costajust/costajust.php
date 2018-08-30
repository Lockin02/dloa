<?php

/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 10:30:13
 * @version 1.0
 * @description:�ɱ����������Ʋ� ��������
 * ����ɱ�������(���ڳ����������)
 * ���ɱ�������
 */
class controller_finance_costajust_costajust extends controller_base_action
{

    function __construct()
    {
        $this->objName = "costajust";
        $this->objPath = "finance_costajust";
        parent::__construct();
    }

    /**
     * ��ת���ɱ�������
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * ��дtoadd
     */
    function c_toAdd()
    {
        $this->showDatadicts(array('formType' => 'CBTZ'));
        $this->display('add');
    }

    /**
     * ��дinit
     */
    function c_init()
    {
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET ['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        if ($perm == 'view') {
            $this->assign('detail', $detailObj);
            $this->assign('formType', $this->getDataNameByCode($obj['formType']));
            $this->display('view');
        } else {
            $this->showDatadicts(array('formType' => 'CBTZ'), $obj['formType']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->display('edit');
        }
    }

    /**
     * �����ڳ����id�鿴��������
     */
    function c_initForStockBal()
    {
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->getByStockBal_d($_GET ['stockbalId'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);
        $this->assign('formType', $this->getDataNameByCode($obj['formType']));
        $this->assign('detail', $detailObj);
        $this->display('view');
    }

    /**
     * ������������ҳ��
     */
    function c_toAddInStockBal()
    {
        $ids = $_GET['ids'];
        $rs = $this->service->getStockBalance_d($ids);
        $this->assign('stockBalance', $rs);
        $this->display('addinstockbal');
    }

    /**
     * ��������
     */
    function c_addInStockBal()
    {
        if ($this->service->addInStockBal_d($_POST[$this->objName])) {
            msg('�����ɹ�');
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_deleteChange()
    {
        try {
            $this->service->deleteChange_d($_POST ['id'], $_POST['stockbalId']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ����ҳ��
     */
    function c_toImport()
    {
        $this->display('import');
    }

    function c_import()
    {
        echo util_excelUtil::showResult($this->service->import_d(), '�������б�', array('������Ϣ', '������'));
    }
}