<?php

/**
 * @author Show
 * @Date 2014年06月27日
 * @version 1.0
 * @description:项目费用维护(oa_esm_costmaintain)控制层
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
     * 跳转到项目费用维护
     */
    function c_page()
    {
        $this->assign('currentMonth', date("Y-m"));
        $this->view('list');
    }

    /**
     * 项目费用维护日志查询
     */
    function c_searchJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getSearchList_d($_POST);
        if (!empty($rows)) {
            //加载费用合计
            $objArr['projectCode'] = '项 目 费 用 合 计';
            $objArr['projectId'] = 'noId';
            $objArr['feeMoney'] = 0; //决算
            $objArr['feeWaitMoney'] = 0; //待审核决算
            foreach ($rows as $v) {
                $objArr['feeMoney'] = bcadd($objArr['feeMoney'], $v['fee'], 2);
                $objArr['feeWaitMoney'] = bcadd($objArr['feeWaitMoney'], $v['feeWait'], 2);
            }
            //金额千分位处理
            $objArr['feeMoney'] = number_format($objArr['feeMoney'], 2, '.', ',');
            $objArr['feeWaitMoney'] = number_format($objArr['feeWaitMoney'], 2, '.', ',');
            $rows[] = $objArr;
        }
        //这里转html
        $rows = $service->searchHtml_d($rows);
        echo util_jsonUtil::iconvGB2UTF($rows);
    }

    /**
     * 跳转到新增页面
     */
    function c_toAdd()
    {
        $this->view('add', true);
    }

    /**
     * 跳转到项目费用导入页面
     */
    function c_toImport()
    {
        $this->display('excelin');
    }

    /**
     * 项目费用导入
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '项目费用导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * ajax方式批量删除对象（应该把成功标志跟消息返回）
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
     * 项目费用维护详细
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view("detail-list");
    }

    /**
     * 获取所有数据返回json
     */
    function c_searhDetailJson()
    {
        echo util_jsonUtil::encode($this->service->searchDetailJson_d($_REQUEST['projectId'], $_REQUEST['parentCostType'], $_REQUEST['costType']));
    }
}