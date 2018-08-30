<?php

/**
 * @author Show
 * @Date 2011年5月6日 星期五 16:17:38
 * @version 1.0
 * @description:应付付款单/应付预付款/应付退款单控制层
 */
class controller_finance_payables_payables extends controller_base_action
{
    private $unDeptExtFilter = "";// PMS377 此模块需要单独隐藏的部门选项
    function __construct() {
        $this->objName = "payables";
        $this->objPath = "finance_payables";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = rtrim($unDeptExtFilterArr,",");
    }

    /**
     * 跳转到应付付款单/应付预付款/应付退款单
     */
    function c_page() {
        //策略调用新增页面
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->assign('formType', $_GET['formType']);

        $this->display($thisObjCode . '-list');
    }

    /**
     * 新增页面
     */
    function c_toAdd() {
        //策略调用新增页面//策略调用新增页面
        $this->assign('formType', $_GET['formType']);
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->assign('formDate', day_date);

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('unDeptFilter', $this->unDeptExtFilter);

        $this->display($thisObjCode . '-add');
    }

    /**
     * 新增页面 - 从付款申请填写
     */
    function c_toAddForApply() {
        //策略调用新增页面
        $this->assign('formType', $_GET['formType']);
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $newClass = $this->service->getClass($_GET['formType']);
        $obj = $this->service->getObjInfo_d($_GET['objId'], new $newClass());
        $this->assignFunc($obj);

        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->assign('formDate', day_date);

        //处理扩展审批流
        if (empty($obj['exaCode'])) {
            $this->assign('exaId', $obj['id']);
            $this->assign('exaCode', 'oa_finance_payablesapply');
        }

        $this->display($thisObjCode . '-addforapply');
    }

    /**
     * 批量录入付款
     */
    function c_toAddInGroup() {
        $rows = $this->service->getPayapply_d($_GET['ids']);
        $this->assignFunc($rows);

        $this->display('addingroup');
    }

    /**
     * 批量录入付款
     */
    function c_addInGroup() {
        if ($this->service->addInGroup_d($_POST[$this->objName])) {
            msgRf('添加成功！');
        } else {
            msgRf('添加失败！');
        }
    }

    /**
     * 批量录入，无确认页面
     */
    function c_addInGroupOneKey() {
        $object = $this->service->getPayapplyOneKey_d($_POST['ids']);
        echo $this->service->addInGroup_d($object[$this->objName], $_POST['isEntrust']) ? 1 : 0;
    }

    /**
     * 重写c_add
     */
    function c_add() {
        $object = $_POST[$this->objName];
        //策略调用新增页面
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->add_d($object, new $thisClass())) {
            msg('添加成功！');
        } else {
            msg('添加失败！');
        }
    }

    /**
     * 重写c_add
     */
    function c_addForApply() {
        $object = $_POST[$this->objName];
        //策略调用新增页面
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->add_d($object, new $thisClass())) {
            msgRF('添加成功！');
        } else {
            msgRF('添加失败！');
        }
    }

    /**
     * 重写c_init
     */
    function c_init() {
        //URL权限控制
        $this->permCheck();

        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        $thisObjCode = $this->service->getBusinessCode($obj['formType']);

        if ($perm == 'view') {
            //查看页面读取付款申请的审批信息
            $payableapplyObj = $this->service->getPayapplyExaInfo_d($obj['payApplyId']);
            $this->assignFunc($payableapplyObj);

            $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
            $this->assign('detail', $detailObj);
            $this->assign('isEntrust', $this->service->rtYesOrNo_d($obj['isEntrust']));
            $this->display($thisObjCode . '-view');
        } else {
            $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->display($thisObjCode . '-edit');
        }
    }

    /**
     * 弹窗打开页面
     */
    function c_initWin() {
        //URL权限控制
        $this->permCheck();

        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'view';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        //查看页面读取付款申请的审批信息
        $payableapplyObj = $this->service->getPayapplyExaInfo_d($obj['payApplyId']);
        $this->assignFunc($payableapplyObj);

        $thisObjCode = $this->service->getBusinessCode($obj['formType']);
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('detail', $detailObj);
        $this->display($thisObjCode . '-viewwin');
    }

    /**
     * 重写c_edit
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        //策略调用新增页面
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->edit_d($object, new $thisClass())) {
            msg('编辑成功！');
        } else {
            msg('编辑失败！');
        }
    }

    /**
     * 审核
     */
    function c_audit() {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 反审核
     */
    function c_unaudit() {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 异步删除
     */
    function c_ajaxDelForPayment() {
        try {
            echo $this->service->delForApply_d($_POST['id']);
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 付款历史
     */
    function c_toHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], 'purchase_contract_purchasecontract');
        $this->assign('skey', $_GET['skey']);
        $this->assignFunc($obj);
        $this->display('history');
    }

    /**
     * 付款历史json
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);//不启用公司权限
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId('select_historyNew');
        unset($service->groupBy);
        if (!empty($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);
            $rsArr = array('money' => 0, 'amount' => 0);
            $rsArr['formNo'] = '选择合计';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //总计栏加载
            $service->groupBy = 'd.objId,d.objType';
            $objArr = $service->listBySqlId('count_money');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 付款历史
     */
    function c_toHistoryForObj() {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('historyforobj');
    }

    /**
     * 供应商 - 付款记录
     */
    function c_toListBySupplier() {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('listbysupplier');
    }

    /**
     * 付款单下推退款单
     */
    function c_toAddRefund() {
        //URL权限控制
        $this->permCheck();

        $obj = $this->service->getCanRefund_d($_GET['id'], 'detail', 'addRefund');

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        $this->assign('formType', 'CWYF-03');

        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->assign('detail', $detailObj[0]);
        $this->assign('coutNumb', $detailObj[1]);
        $this->display('refund-payablesadd');
    }

    /**
     * 付款下推
     */
    function c_checkIsRefundAll() {
        echo $this->service->checkIsRefundAll_d($_POST['id']) ? 1 : 0;
    }
}