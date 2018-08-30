<?php

/**
 * @author Show
 * @Date 2014��06��27��
 * @version 1.0
 * @description:��Ŀ����ά��(oa_esm_costmaintain)���Ʋ�
 */
class controller_engineering_cost_esmcostmaintain extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmcostmaintain";
        $this->objPath = "engineering_cost";
        parent::__construct();
    }

    /**
     * ��ת����Ŀ����ά��
     */
    function c_page()
    {
        $this->assign('currentMonth', date("Y-m"));
        $this->view('list');
    }

    /**
     * ��Ŀ����ά����־��ѯ
     */
    function c_searchJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getSearchList_d($_POST);
        if (!empty($rows)) {
            //���ط��úϼ�
            $objArr['projectCode'] = '�� Ŀ �� �� �� ��';
            $objArr['projectId'] = 'noId';
            $objArr['feeMoney'] = 0; //����
            $objArr['feeWaitMoney'] = 0; //����˾���
            foreach ($rows as $v) {
                $objArr['feeMoney'] = bcadd($objArr['feeMoney'], $v['fee'], 2);
                $objArr['feeWaitMoney'] = bcadd($objArr['feeWaitMoney'], $v['feeWait'], 2);
            }
            //���ǧ��λ����
            $objArr['feeMoney'] = number_format($objArr['feeMoney'], 2, '.', ',');
            $objArr['feeWaitMoney'] = number_format($objArr['feeWaitMoney'], 2, '.', ',');
            $rows[] = $objArr;
        }
        //����תhtml
        $rows = $service->searchHtml_d($rows);
        echo util_jsonUtil::iconvGB2UTF($rows);
    }

    /**
     * ��ת������ҳ��
     */
    function c_toAdd()
    {
        $this->view('add', true);
    }

    /**
     * ��ת����Ŀ���õ���ҳ��
     */
    function c_toImport()
    {
        $this->display('excelin');
    }

    /**
     * ��Ŀ���õ���
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '��Ŀ���õ������б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_ajaxdeletes()
    {
        if ($this->service->ajaxdeletes_d($_POST['id'])) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ��Ŀ����ά����ϸ
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view("detail-list");
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_searhDetailJson()
    {
        echo util_jsonUtil::encode($this->service->searchDetailJson_d($_REQUEST['projectId'], $_REQUEST['parentCostType'], $_REQUEST['costType']));
    }
}