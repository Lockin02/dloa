<?php

/**
 * @author Acan
 * @Date 2014年10月30日 11:03:15
 * @version 1.0
 * @description:外包预提控制层
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
     * 跳转到外包预提列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 项目查询列表
     */
    function c_projectPage() {
        $this->assignFunc($_GET);
        $this->view("projectPage");
    }

    /**
     * 获取列表数据
     */
    function c_summaryList()
    {
        echo util_jsonUtil::encode($this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['searchVal'], $_REQUEST['projectId'], $_REQUEST['sort'], $_REQUEST['order']));
    }

    /**
     * 导入excel
     */
    function c_toExcelIn()
    {
        $this->display('excelin', true);
    }

    /**
     * 导入excel
     */
    function c_excelIn()
    {
        $this->checkSubmit(); //检查是否重复提交
        set_time_limit(0);
        $resultArr = $this->service->excelIn_d();

        $title = '外包预提导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * 导出excel
     */
    function c_export()
    {
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['searchVal']);

        if (!empty($data)) {
            $colCode = $_REQUEST['colCode'];
            $colName = $_REQUEST['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, '预提数据导出', array('taxRate'), array('yearMonth','payDT','invoiceDT'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 获取权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }
}