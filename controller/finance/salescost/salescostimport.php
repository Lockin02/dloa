<?php
class controller_finance_salescost_salescostimport extends controller_base_action {
    function __construct() {
        $this->objName = "salescostimport";
        $this->objPath = "finance_salescost";
        parent::__construct ();
    }

    /**
     * 主列表页
     */
    function c_index(){
        $act = isset($_GET['act'])? $_GET['act'] : '';
        $areaId = isset($_GET['areaId'])? $_GET['areaId'] : '';
        $userId = isset($_GET['userId'])? $_GET['userId'] : '';
        $year = isset($_GET['year'])? $_GET['year'] : '';
        $exeDeptCode = isset($_GET['exeDeptCode'])? $_GET['exeDeptCode'] : '';

        // 检验是否有导入权限
        $excelInLimit = 0;
        if ($this->service->this_limit['导入权限'] != '') {
            $excelInLimit = $this->service->this_limit['导入权限'];
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
     * 返回列表页json数据
     */
    function c_pageJson()
    {
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        $rows = $service->page_d();

        //安全码
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 跳转导入窗口
     */
    function c_toImportExcel(){
        $this->display('excelin');
    }

    /**
     * 执行数据导入
     */
    function c_excelIn(){
        $resultData = $this->service->importSalesCostData_d();

        $title = '销售费用导入结果列表';
        $thead = array( '数据信息','导入结果' );
        echo util_excelUtil::showResult($resultData,$title,$thead);
    }
}