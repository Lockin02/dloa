<?php

/**
 * Class controller_contract_counting_counting
 */
class controller_contract_counting_counting extends controller_base_action
{

    function __construct()
    {
        $this->objName = "counting";
        $this->objPath = "contract_counting";
        parent:: __construct();
    }

    /**
     * 跳转到日期设置列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 重写获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;//chkResult
        if(isset($_REQUEST['chkResult'])){
            $chkResult = $_REQUEST['chkResult'];
            unset($_REQUEST['chkResult']);
            switch ($chkResult){
                case 'correct':
                    $_REQUEST['countingAndBuildChkCorrect'] = '正确';
                    break;
                case 'wrong':
                    $_REQUEST['countingAndBuildChk'] = '错误';
                    break;
            }
        }

        $service->getParam ( $_REQUEST );
        $service->searchArr['isDel'] = 0;
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $_SESSION['countingSearchArr'] = $service->searchArr;
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 更新页面
     */
    function c_toUpdate()
    {
        $this->assign('year', date('Y'));
        $this->view('update');
    }

    /**
     * 更新方法
     */
    function c_update()
    {
        if(isset($_POST['resetField']) && $_POST['resetField'] != ''){
            echo $this->service->resetRecord_d($_POST['resetField'],$_POST['contractCode'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
        }else{
            echo $this->service->update_d($_POST['contractCode'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
        }
    }

    /**
     * 导出汇总
     */
    function c_export() {
        set_time_limit(0); // 设置不超时
        $this->service->getParam($_REQUEST);
        if(isset($_SESSION['countingSearchArr']) && is_array($_SESSION['countingSearchArr'])){
            foreach ($_SESSION['countingSearchArr'] as $k => $v){
                if(!isset($this->service->searchArr[$k])){
                    $this->service->searchArr[$k] = $v;
                }
            }
        }
        $rows = $this->service->list_d();

        //扩展数据处理
        if ($rows) {
            foreach ($rows as $k => $v) {
                $rows[$k]['projectRate'] = bcmul($v['projectRate'], 100, 7);
                $rows[$k]['productRate'] = bcmul($v['productRate'], 100, 7);
            }
            $colCode = $_GET['colCode'];
            $colName = $_GET['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export07ExcelUtil($head, $rows, '合同分帐表', array(
                'projectRate', 'productRate'
            ));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 删除项目数据 (删除以合同为单位的所以数据)
     */
    function c_delProject(){
        $id = isset($_POST['id'])? $_POST['id'] : '';

        // 删除统计表数据
        $result = $this->service->update("contractId = {$id}",array("isDel"=>1));

        if($result){
            echo "ok";
        }else{
            echo "fail";
        }
    }

    /**
     * 标记项目立项检测结果为正确 (标记以合同为单位的所以数据)
     */
    function c_setProjectIsTrue(){
        $id = isset($_POST['id'])? $_POST['id'] : '';

        // 删除统计表数据
        $result = $this->service->update("contractId = {$id}",array("isTrue"=>1));

        if($result){
            echo "ok";
        }else{
            echo "fail";
        }
    }
}