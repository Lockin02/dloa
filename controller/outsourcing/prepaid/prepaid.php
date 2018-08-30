<?php

/**
 * @author Acan
 * @Date 2014��10��30�� 11:03:15
 * @version 1.0
 * @description:���Ԥ����Ʋ�
 */
class controller_outsourcing_prepaid_prepaid extends controller_base_action
{

    function __construct()
    {
        $this->objName = "prepaid";
        $this->objPath = "outsourcing_prepaid";
        parent::__construct();
    }

    /**
     * ��ת�����Ԥ���б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��Ŀ��ѯ�б�
     */
    function c_projectPage() {
        $this->assignFunc($_GET);
        $this->view("projectPage");
    }

    /**
     * ��ȡ�б�����
     */
    function c_summaryList()
    {
        echo util_jsonUtil::encode($this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['searchVal'], $_REQUEST['projectId'], $_REQUEST['sort'], $_REQUEST['order']));
    }

    /**
     * ����excel
     */
    function c_toExcelIn()
    {
        $this->display('excelin', true);
    }

    /**
     * ����excel
     */
    function c_excelIn()
    {
        $this->checkSubmit(); //����Ƿ��ظ��ύ
        set_time_limit(0);
        $resultArr = $this->service->excelIn_d();

        $title = '���Ԥ�ᵼ�����б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * ����excel
     */
    function c_export()
    {
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['searchVal']);

        if (!empty($data)) {
            $colCode = $_REQUEST['colCode'];
            $colName = $_REQUEST['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, 'Ԥ�����ݵ���', array('taxRate'), array('yearMonth','payDT','invoiceDT'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }
}