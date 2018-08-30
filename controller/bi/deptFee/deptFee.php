<?php

/**
 * 部门费用表
 *
 * Class controller_bi_deptFee_deptFee
 */
class controller_bi_deptFee_deptFee extends controller_base_action
{

    function __construct()
    {
        $this->objName = "deptFee";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * tabs
     */
    function c_tabs()
    {
        $this->view('tabs');
    }

    /**
     * 列表
     */
    function c_page()
    {
        $this->assign('year', date('Y'));
        $this->assign('month', date('n'));

        // 获取部门显示层级
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');
        $this->assign('deptLevel', $deptLevel ? $deptLevel : 0);

        $this->view('list');
    }

    /**
     * 获取权限
     */
    function c_getLimit()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 获取本次统计的费用类型
     */
    function c_getCostTypeList()
    {
        echo util_jsonUtil::encode($this->service->getCostTypeList_d($_POST['beginYear'], $_POST['beginMonth'],
            $_POST['endYear'], $_POST['endMonth']));
    }

    /**
     * 费用统计列表
     */
    function c_summaryList()
    {
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth']);

        if ($data) {
            // 列头获取
            $detailTitle = $this->service->getCostTypeList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                $_REQUEST['endYear'], $_REQUEST['endMonth']);

            // 合计列
            $countRow = array('business' => '合计', 'budget' => 0.00, 'fee' => 0.00);

            // 数据加载
            foreach ($data as $k => $v) {
                // 明细数据获取
                $detailData = $this->service->summaryDetail_d($k, $_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                    $_REQUEST['endYear'], $_REQUEST['endMonth'], $v['business'], $v['secondDept'], $v['thirdDept'],
                    $v['fourthDept']);

                foreach ($detailTitle as $vi) {
                    if (isset($detailData['rows'][$vi['costType']])) {
                        $data[$k][$vi['costType']] = $detailData['rows'][$vi['costType']];
                    } else {
                        $data[$k][$vi['costType']] = 0;
                    }
                    $countRow[$vi['costType']] = bcadd($data[$k][$vi['costType']], $countRow[$vi['costType']], 2);
                }
                $countRow['budget'] = bcadd($data[$k]['budget'], $countRow['budget'], 2);
                $countRow['fee'] = bcadd($data[$k]['fee'], $countRow['fee'], 2);
            }
            $countRow['feeProcess'] = $countRow['budget'] ?
                round(bcmul(bcdiv($countRow['fee'], $countRow['budget'], 6), 100, 4), 2) : 0.00;

            // 加入合计
            $data[] = $countRow;
        }
        echo util_jsonUtil::encode($data);
    }

    /**
     * 费用统计明细
     */
    function c_summaryDetail()
    {
        echo util_jsonUtil::encode($this->service->summaryDetail_d($_REQUEST['rowNum'], $_REQUEST['beginYear'],
            $_REQUEST['beginMonth'], $_REQUEST['endYear'], $_REQUEST['endMonth'],
            util_jsonUtil::iconvUTF2GB($_REQUEST['business']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['secondDept']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['thirdDept']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['fourthDept'])));
    }

    /**
     * 导出汇总
     */
    function c_exportSummary()
    {
        set_time_limit(0);
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth']);

        if ($data) {
            // 列头获取
            $detailTitle = $this->service->getCostTypeList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                $_REQUEST['endYear'], $_REQUEST['endMonth']);

            // 合计列
            $countRow = array('business' => '合计', 'budget' => 0.00, 'fee' => 0.00);

            // 数据加载
            foreach ($data as $k => $v) {
                // 明细数据获取
                $detailData = $this->service->summaryDetail_d($k, $_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                    $_REQUEST['endYear'], $_REQUEST['endMonth'], $v['business'], $v['secondDept'], $v['thirdDept'],
                    $v['fourthDept']);

                foreach ($detailTitle as $vi) {
                    if (isset($detailData['rows'][$vi['costType']])) {
                        $data[$k][$vi['costType']] = $detailData['rows'][$vi['costType']];
                    } else {
                        $data[$k][$vi['costType']] = 0;
                    }
                    $countRow[$vi['costType']] = bcadd($data[$k][$vi['costType']], $countRow[$vi['costType']], 2);
                }
                $countRow['budget'] = bcadd($data[$k]['budget'], $countRow['budget'], 2);
                $countRow['fee'] = bcadd($data[$k]['fee'], $countRow['fee'], 2);
            }
            $countRow['feeProcess'] = $countRow['budget'] ?
                round(bcmul(bcdiv($countRow['fee'], $countRow['budget'], 6), 100, 4), 2) : 0.00;

            // 加入合计
            $data[] = $countRow;

            $colCode = $_REQUEST['colCode'];
            $colName = $_REQUEST['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, $_REQUEST['thisYear'] . '部门费用汇总', array('feeProcess'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 明细导出
     */
    function c_exportDetail()
    {
        set_time_limit(0);
        $data = $this->service->getDetail_d($_REQUEST['exportYear'], $_REQUEST['exportMonth'], $_REQUEST['exportItems']);
        if ($data) {
            // 表头模板
            $headsTemplate = array(
                'person' => array('deptName' => '归属部门', 'fee' => '决算'),
                'expense' => array('deptName' => '归属部门', 'module' => '板块', 'BillNo' => '报销单号', 'fee' => '决算'),
                'pay' => array('deptName' => '归属部门', 'module' => '板块', 'objCode' => '单据编号', 'fee' => '决算'),
                'pk' => array('deptName' => '归属部门', 'projectCode' => '项目编号', 'feeMonth' => '本月转正当月决算',
                    'feeAll' => '本月转正所有决算', 'feeNotTurn' => '未转正当月决算')
            );
            $heads = array();
            $sheets = explode(',', $_REQUEST['exportItems']);
            foreach ($sheets as $k => $v) {
                // 构建表头
                $heads[] = $headsTemplate[$v];

                // 重构sheet的中文
                $sheets[$k] = $this->service->feeType[$v];
            }

            model_finance_common_financeExcelUtil::exportExcelMulUtil($sheets, $heads, $data, $_REQUEST['exportYear'] . '费用明细导出');
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 费用名目过滤设置、部门层级显示设置
     */
    function c_otherSetting()
    {
        $this->assign('year', date('Y'));
        $this->assign('month', date('n'));
        $this->view('otherSetting');
    }

    /**
     * 更新费用
     */
    function c_updateFee()
    {
        echo util_jsonUtil::encode($this->service->updateFee_d($_REQUEST['year'], $_REQUEST['month'], $_REQUEST['feeType']));
    }

    /**
     * 其他费用维护
     */
    function c_otherFee()
    {
        $this->assign('beginYear', isset($_REQUEST['beginYear']) ? $_REQUEST['beginYear'] :date('Y'));
        $this->assign('beginMonth', isset($_REQUEST['beginMonth']) ? $_REQUEST['beginMonth'] :date('n'));
        $this->assign('endYear', isset($_REQUEST['endYear']) ? $_REQUEST['endYear'] :date('Y'));
        $this->assign('endMonth', isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] :date('n'));
        $this->view('otherFee');
    }

    /**
     * 其他费用统计视图
     */
    function c_otherFeeSummary()
    {
        echo util_jsonUtil::encode($this->service->otherFeeSummary_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']));
    }

    /**
     * 其他费用统计视图
     */
    function c_otherFeeDetail()
    {
        echo util_jsonUtil::encode($this->service->otherFeeDetail_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']));
    }

    /**
     * 跳转到导入界面
     */
    function c_toImport()
    {
        $this->view('import');
    }

    /**
     * 导入功能
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '部门数据导入';
        $head = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }

    /**
     * 导出
     */
    function c_export()
    {
        set_time_limit(0);
        // 获取数据
        $data = $this->service->otherFeeSummary_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']);
        if ($data) {
            $head = array(
                'business' => '事业部', 'secondDept' => '二级部门', 'thirdDept' => '三级部门', 'fourthDept' => '四级部门',
                'costType' => '费用类型', 'budget' => '预算', 'fee' => '决算'
            );
            // 生成月存储字段
            foreach ($data as $k => $v) {
                for ($i = $_REQUEST['beginYear']; $i <= $_REQUEST['endYear']; $i++) {
                    $begin = 1;
                    $end = 12;
                    if ($i == $_REQUEST['beginYear']) {
                        $begin = $_REQUEST['beginMonth'];
                    }
                    if ($i == $_REQUEST['endYear']) {
                        $end = $_REQUEST['endMonth'];
                    }
                    // 月份列渲染
                    for ($j = $begin; $j <= $end; $j++) {
                        // 生成月份
                        $yearMonth = $j >= 10 ? $i . $j : $i . str_pad($j, 2, 0, STR_PAD_LEFT);

                        // 载入月的数据
                        $head['d' . $yearMonth] = $yearMonth;
                    }
                }
            }
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, $_REQUEST['thisYear'] . '部门其他费用');
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }
}