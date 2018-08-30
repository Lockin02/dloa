<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:07:46
 * @version 1.0
 * @description:报销申请费用明细(部门报销)控制层
 */
class controller_finance_expense_expensedetail extends controller_base_action
{

    function __construct() {
        $this->objName = "expensedetail";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * 跳转到报销申请费用明细(部门报销)列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到新增报销申请费用明细(部门报销)页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 跳转到编辑报销申请费用明细(部门报销)页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看报销申请费用明细(部门报销)页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 跳转到编辑发票类型部分
     */
    function c_toEitCostTypeID() {
        $this->assignFunc($_GET);
        $this->assign('newCostTypeID', $this->service->initCostOption_d($_GET['CostTypeID']));
        $this->view('editcosttypeid');
    }

    /**
     * 编辑方法
     */
    function c_editCostTypeID() {
        //原发票类型id
        $costTypeId = $_POST['costTypeId'];
        //新发票类型id
        $newCostTypeId = $_POST['newCostTypeId'];
        $newMainTypeId = $_POST['newMainTypeId'];
        $newMainType = util_jsonUtil::iconvUTF2GB($_POST['newMainType']);
        //发票号码
        $BillNo = $_POST['BillNo'];

        echo $this->service->editCostTypeID_d($costTypeId, $newCostTypeId, $BillNo, $newMainTypeId, $newMainType) ?
            1 : 0;
    }

    /**
     *  ajax获取发票信息
     */
    function c_ajaxGetCostDetail() {
        //获取明细信息
        $billDetail = $this->service->getBillDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initBillDetailViewEdit_d($billDetail));
    }

    /**
     * 编辑费用明细 － 附带金额和类型
     */
    function c_toEditDetail() {
        $this->assignFunc($_GET);
        $rs = $this->service->findAll(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'ID,CostMoney,Remark,AssID');
        if (count($rs) > 1) {
            $newRs = array('ID' => array(), 'CostMoney' => 0, 'Remark' => array(), 'AssID' => '');
            foreach ($rs as $k => $v) {
                array_push($newRs['ID'], $v['ID']);
                $newRs['CostMoney'] = bcadd($newRs['CostMoney'], $v['CostMoney'],2);
                array_push($newRs['AssID'], $v['AssID']);
                if (!$newRs['AssID']) $newRs['AssID'] = $v['AssID'];
                if (!in_array($v['Remark'], $newRs['Remark'])) array_push($newRs['Remark'], $v['Remark']);
            }
            $newRs['ID'] = implode(',', $newRs['ID']);
            $newRs['Remark'] = implode(';', $newRs['Remark']);
            $this->assignFunc($newRs);
        } else {
            $this->assignFunc($rs[0]);
        }
        //渲染原类型
        $this->assign('newCostTypeID', $this->service->initCostOption_d($_GET['CostTypeID']));
        // 获取分摊说明
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        $rs = $expensecostshareDao->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('editdetail');
    }

    /**
     * 编辑费用明细
     */
    function c_editDetail() {
        //处理修改
        $rs = $this->service->editDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('修改成功');parent.show_pageExpense('$rs');parent.tb_remove();</script>";
        } else {
            msg('修改失败');
        }
    }

    /**
     * 新增费用明细 － 附带金额和类型
     */
    function c_toAddDetail() {
        $billNo = isset($_GET['BillNo'])? $_GET['BillNo'] : '';
        $this->assignFunc($_GET);
        $specialGroupId = 347;// 税费大类ID

        // 渲染原类型选项
        $canTakeOutTypeOpts = "";
        $canTakeOutTypeArr = $this->service->_db->getArray("select GROUP_CONCAT(d.ID) as IDs,d.HeadID,d.AssID,d.MainType,t.costTypeID,t.costTypeName,sum(d.CostMoney) as CostMoney from cost_detail d left join cost_type t on t.CostTypeID = d.CostTypeID where d.BillNo = '{$billNo}' group by d.costTypeID;");
        if($canTakeOutTypeArr && is_array($canTakeOutTypeArr)){
            foreach ($canTakeOutTypeArr as $v){
                $canTakeOutTypeOpts .= "<option value='{$v['IDs']}' costtypeid='{$v['costTypeID']}' amount='{$v['CostMoney']}' headid='{$v['HeadID']}' assid='{$v['AssID']}'>{$v['costTypeName']}(金额: {$v['CostMoney']})</option>";
            }
        }
        $this->assign('canTakeOutTypeOpts', $canTakeOutTypeOpts);

        // 渲染税费类型选项
        $newCostTypeID = $this->service->initCostOption_d('',$specialGroupId);
        $this->assign('newCostTypeID', $newCostTypeID);
        // 获取分摊说明
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        $rs = $expensecostshareDao->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('adddetail');
    }

    /**
     * 新增费用明细
     */
    function c_addDetail() {
        //处理数据
        $rs = $this->service->addDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('保存成功');parent.show_pageExpense('$rs');parent.tb_remove();</script>";
        } else {
            msg('保存失败');
        }
    }

    /**
     * 删除新增的费用类型
     */
    function c_deleteDetail(){
        $CostTypeId = isset($_REQUEST['CostTypeId'])? $_REQUEST['CostTypeId'] : "";
        $BillNo = isset($_REQUEST['BillNo'])? $_REQUEST['BillNo'] : "";
        $chkSql = "select GROUP_CONCAT(ID) as IDs,sum(CostMoney) as CostMoney,GROUP_CONCAT(toTakeOutTypeId) as toTakeOutTypeIds from cost_detail where BillNo = '{$BillNo}' and CostTypeID = '{$CostTypeId}' and isAddByAuditor = 1;";
        $relativeCostTypeData = $this->service->_db->get_one($chkSql);
        if($relativeCostTypeData){
            $newCostDetailIds = isset($relativeCostTypeData['IDs'])? $relativeCostTypeData['IDs'] : '';
            $costMoney = isset($relativeCostTypeData['CostMoney'])? $relativeCostTypeData['CostMoney'] : 0;
            $toTakeOutTypeIds = isset($relativeCostTypeData['toTakeOutTypeIds'])? $relativeCostTypeData['toTakeOutTypeIds'] : '';
            $toTakeOutTypeIdsArr = explode(",",$toTakeOutTypeIds);
            if(is_array($toTakeOutTypeIdsArr) && !empty($toTakeOutTypeIdsArr)){
                // 查询原来抵充了的费用项的一条发票记录
                $billDetailChkSql = "select * from bill_detail where BillDetailID = '{$toTakeOutTypeIdsArr[0]}' limit 1;";
                $needUpdatebillDetail = $this->service->_db->get_one($billDetailChkSql);
                // 查询原来抵充了的费用项的一条分摊记录
                $financeShareChkSql = "select c.* from oa_finance_costshare c left join cost_detail d on (c.BillNo = d.BillNo and c.CostTypeID = d.CostTypeID) where d.ID = '{$toTakeOutTypeIdsArr[0]}' limit 1;";
                $needUpdateFinanceShare = $this->service->_db->get_one($financeShareChkSql);

                // 将所有新增税费的金额,退回到原抵充费用类型的一条记录中
                $updateCostDetailSql = "update cost_detail set CostMoney = (CostMoney + {$costMoney}) where ID = '{$toTakeOutTypeIdsArr[0]}';";
                $this->service->_db->query($updateCostDetailSql);

                // 将所有新增税费的金额,退回到原抵充费用类型的一条发票记录中
                if($needUpdatebillDetail && isset($needUpdatebillDetail['ID'])){
                    $updateBillDetailSql = "update bill_detail set Amount = (Amount + {$costMoney}) where ID = '{$needUpdatebillDetail['ID']}';";
                    $this->service->_db->query($updateBillDetailSql);
                }

                // 将所有新增税费的金额,退回到原抵充费用类型的一条分摊记录中
                if($needUpdateFinanceShare && isset($needUpdateFinanceShare['ID'])){
                    $updateFinanceShareSql = "update oa_finance_costshare set CostMoney = (CostMoney + {$costMoney}) where ID = '{$needUpdateFinanceShare['ID']}';";
                    $this->service->_db->query($updateFinanceShareSql);
                }

                // 删除新增的费用项
                if($newCostDetailIds != ''){
                    $deleteCostDetailSql = "delete from cost_detail where ID in ({$newCostDetailIds});";
                    $this->service->_db->query($deleteCostDetailSql);
                    $deleteBillDetailSql = "delete from bill_detail where BillDetailID in ({$newCostDetailIds});";
                    $this->service->_db->query($deleteBillDetailSql);
                    $deleteFinanceShareSql = "delete from oa_finance_costshare where relativeBillDetailId in ({$newCostDetailIds});";
                    $this->service->_db->query($deleteFinanceShareSql);
                }
            }
            echo 'ok';
        }else{
            echo 'fail';
        }
    }
}