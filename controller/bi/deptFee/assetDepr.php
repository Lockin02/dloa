<?php

/**
 * 部门折旧
 *
 * Class controller_bi_deptFee_assetDepr
 */
class controller_bi_deptFee_assetDepr extends controller_base_action
{

    function __construct()
    {
        $this->objName = "assetDepr";
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
        $thisYear = date('Y');
        $yearStr = "";
        while ($thisYear >= 2010) {
            $yearStr .= "<option>" . $thisYear . "</option>";
            $thisYear--;
        }
        $this->assign('yearStr', $yearStr);

        // 月份处理
        $thisMonth = date('m');
        $monthStr = "";
        for ($i = 1; $i <= 12; $i++) {
            if ($thisMonth == $i) {
                $monthStr .= "<option selected='selected'>" . $i . "</option>";
            } else {
                $monthStr .= "<option>" . $i . "</option>";
            }
        }
        $this->assign('monthStr', $monthStr);

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

        $thisYear = date('Y');
        $yearStr = "";
        while ($thisYear >= 2010) {
            if ($obj['thisYear'] == $thisYear) {
                $yearStr .= "<option selected='selected'>" . $thisYear . "</option>";
            } else {
                $yearStr .= "<option>" . $thisYear . "</option>";
            }
            $thisYear--;
        }
        $this->assign('yearStr', $yearStr);

        // 月份处理
        $monthStr = "";
        for ($i = 1; $i <= 12; $i++) {
            if ($obj['thisMonth'] == $i) {
                $monthStr .= "<option selected='selected'>" . $i . "</option>";
            } else {
                $monthStr .= "<option>" . $i . "</option>";
            }
        }
        $this->assign('monthStr', $monthStr);
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
}