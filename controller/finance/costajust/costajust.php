<?php

/**
 * @author Show
 * @Date 2011年5月31日 星期二 10:30:13
 * @version 1.0
 * @description:成本调整单控制层 单据类型
 * 出库成本调整单(存在出库调整类型)
 * 入库成本调整单
 */
class controller_finance_costajust_costajust extends controller_base_action
{

    function __construct()
    {
        $this->objName = "costajust";
        $this->objPath = "finance_costajust";
        parent::__construct();
    }

    /**
     * 跳转到成本调整单
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * 重写toadd
     */
    function c_toAdd()
    {
        $this->showDatadicts(array('formType' => 'CBTZ'));
        $this->display('add');
    }

    /**
     * 重写init
     */
    function c_init()
    {
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET ['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        if ($perm == 'view') {
            $this->assign('detail', $detailObj);
            $this->assign('formType', $this->getDataNameByCode($obj['formType']));
            $this->display('view');
        } else {
            $this->showDatadicts(array('formType' => 'CBTZ'), $obj['formType']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->display('edit');
        }
    }

    /**
     * 根据期初余额id查看余额调整单
     */
    function c_initForStockBal()
    {
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->getByStockBal_d($_GET ['stockbalId'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);
        $this->assign('formType', $this->getDataNameByCode($obj['formType']));
        $this->assign('detail', $detailObj);
        $this->display('view');
    }

    /**
     * 批量出单新增页面
     */
    function c_toAddInStockBal()
    {
        $ids = $_GET['ids'];
        $rs = $this->service->getStockBalance_d($ids);
        $this->assign('stockBalance', $rs);
        $this->display('addinstockbal');
    }

    /**
     * 批量出单
     */
    function c_addInStockBal()
    {
        if ($this->service->addInStockBal_d($_POST[$this->objName])) {
            msg('出单成功');
        } else {
            msg('出单失败');
        }
    }

    /**
     * ajax方式批量删除对象（应该把成功标志跟消息返回）
     */
    function c_deleteChange()
    {
        try {
            $this->service->deleteChange_d($_POST ['id'], $_POST['stockbalId']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 导入页面
     */
    function c_toImport()
    {
        $this->display('import');
    }

    function c_import()
    {
        echo util_excelUtil::showResult($this->service->import_d(), '导入结果列表', array('数据信息', '导入结果'));
    }
}