<?php

/**
 * 部门映射表
 *
 * Class controller_bi_deptFee_deptMapping
 */
class controller_bi_deptFee_deptMapping extends controller_base_action
{

    function __construct()
    {
        $this->objName = "deptMapping";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * 列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 新增
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * 编辑
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        // 开始日期
        $filterStartDateY = empty($obj['filterStartDate'])? '' : date("Y",$obj['filterStartDate']);
        $filterStartDateM = empty($obj['filterStartDate'])? '' : date("m",$obj['filterStartDate']);
        $this->assign("filterStartDateM", $filterStartDateM);
        $this->assign("filterStartDateY", $filterStartDateY);

        // 截止日期
        $filterEndDateY = empty($obj['filterEndDate'])? '' : date("Y",$obj['filterEndDate']);
        $filterEndDateM = empty($obj['filterEndDate'])? '' : date("m",$obj['filterEndDate']);
        $this->assign("filterEndDateM", $filterEndDateM);
        $this->assign("filterEndDateY", $filterEndDateY);

        $this->view('edit');
    }

    /**
     * 查看
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 导入 - 显示页面
     */
    function c_toImport()
    {
        $this->view('import');
    }

    /**
     * 导入
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '项目导入结果列表';
        $head = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }

    /**
     * 导出
     */
    function c_export()
    {
        set_time_limit(0); // 设置不超时
        $rows = $this->service->list_d();

        //扩展数据处理
        foreach ($rows as $k => $v) {
            $rows[$k]['projectRate'] = bcmul($v['projectRate'], 100, 7);
            $rows[$k]['productRate'] = bcmul($v['productRate'], 100, 7);
        }
        $colCode = $_GET['colCode'];
        $colName = $_GET['colName'];
        $head = array_combine(explode(',', $colCode), explode(',', $colName));
        model_finance_common_financeExcelUtil::export2ExcelUtil($head, $rows, '部门关系配置', array(
            'projectRate', 'productRate'
        ));
    }

    /**
     * 【重写】新增
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        //开始日期
        $filterStartDateY = $filterStartDateM = '';
        if(isset($object['filterStartDateY']) && $object['filterStartDateY'] <> ''){
            $filterStartDateY = $object['filterStartDateY'];
            $filterStartDateM = empty($object['filterStartDateM'])? 1 : $object['filterStartDateM'];//如果没选月份,默认是1月
        }
        unset($object['filterStartDateY']);
        unset($object['filterStartDateM']);

        $filterStartDate = empty($filterStartDateY)? '' : strtotime($filterStartDateY . "-" . $filterStartDateM . "-1");
        $object['filterStartDateStr'] = ($filterStartDateY != '')? $filterStartDateY."-".$filterStartDateM : '';
        $object['filterStartDate'] = $filterStartDate;

        // 截止日期
        $filterEndDateY = $filterEndDateM = '';
        if(isset($object['filterEndDateY']) && $object['filterEndDateY'] <> ''){
            $filterEndDateY = $object['filterEndDateY'];
            $filterEndDateM = empty($object['filterEndDateM'])? 1 : $object['filterEndDateM'];//如果没选月份,默认是1月
        }
        unset($object['filterEndDateY']);
        unset($object['filterEndDateM']);

        $filterEndDate = empty($filterEndDateY)? '' : strtotime($filterEndDateY . "-" . $filterEndDateM . "-1");
        $object['filterEndDateStr'] = ($filterEndDateY != '')? $filterEndDateY."-".$filterEndDateM : '';
        $object['filterEndDate'] = $filterEndDate;

        $id = $this->service->add_d ( $object, $isAddInfo );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        if ($id) {
            msg ( $msg );
        }
    }

    /**
     * 【重写】编辑
     */
    function c_edit($isEditInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        //开始日期
        $filterStartDateY = $filterStartDateM = '';
        if(isset($object['filterStartDateY']) && $object['filterStartDateY'] <> ''){
            $filterStartDateY = $object['filterStartDateY'];
            $filterStartDateM = empty($object['filterStartDateM'])? 1 : $object['filterStartDateM'];//如果没选月份,默认是1月
        }
        unset($object['filterStartDateY']);
        unset($object['filterStartDateM']);

        $filterStartDate = empty($filterStartDateY)? '' : strtotime($filterStartDateY . "-" . $filterStartDateM . "-1");
        $object['filterStartDateStr'] = ($filterStartDateY != '')? $filterStartDateY."-".$filterStartDateM : '';
        $object['filterStartDate'] = $filterStartDate;

        // 截止日期
        $filterEndDateY = $filterEndDateM = '';
        if(isset($object['filterEndDateY']) && $object['filterEndDateY'] <> ''){
            $filterEndDateY = $object['filterEndDateY'];
            $filterEndDateM = empty($object['filterEndDateM'])? 1 : $object['filterEndDateM'];//如果没选月份,默认是1月
        }
        unset($object['filterEndDateY']);
        unset($object['filterEndDateM']);

        $filterEndDate = empty($filterEndDateY)? '' : strtotime($filterEndDateY . "-" . $filterEndDateM . "-1");
        $object['filterEndDateStr'] = ($filterEndDateY != '')? $filterEndDateY."-".$filterEndDateM : '';
        $object['filterEndDate'] = $filterEndDate;

        if ($this->service->edit_d ( $object, $isEditInfo )) {
            msg ( '编辑成功！' );
        }
    }
}