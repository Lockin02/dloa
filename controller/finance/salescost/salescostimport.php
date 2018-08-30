<?php
class controller_finance_salescost_salescostimport extends controller_base_action {
    function __construct() {
        $this->objName = "salescostimport";
        $this->objPath = "finance_salescost";
        parent::__construct ();
    }

    /**
     * ���б�ҳ
     */
    function c_index(){
        $act = isset($_GET['act'])? $_GET['act'] : '';
        $areaId = isset($_GET['areaId'])? $_GET['areaId'] : '';
        $userId = isset($_GET['userId'])? $_GET['userId'] : '';
        $year = isset($_GET['year'])? $_GET['year'] : '';
        $exeDeptCode = isset($_GET['exeDeptCode'])? $_GET['exeDeptCode'] : '';

        // �����Ƿ��е���Ȩ��
        $excelInLimit = 0;
        if ($this->service->this_limit['����Ȩ��'] != '') {
            $excelInLimit = $this->service->this_limit['����Ȩ��'];
        }

        $this->assign("excelInLimit",$excelInLimit);
        $this->assign("act",$act);
        $this->assign("areaId",$areaId);
        $this->assign("userId",$userId);
        $this->assign("year",$year);
        $this->assign("exeDeptCode",$exeDeptCode);
        $this->view('list');
    }

    /**
     * �����б�ҳjson����
     */
    function c_pageJson()
    {
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        $rows = $service->page_d();

        //��ȫ��
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ת���봰��
     */
    function c_toImportExcel(){
        $this->display('excelin');
    }

    /**
     * ִ�����ݵ���
     */
    function c_excelIn(){
        $resultData = $this->service->importSalesCostData_d();

        $title = '���۷��õ������б�';
        $thead = array( '������Ϣ','������' );
        echo util_excelUtil::showResult($resultData,$title,$thead);
    }
}