<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:07:46
 * @version 1.0
 * @description:�������������ϸ(���ű���)���Ʋ�
 */
class controller_finance_expense_expensedetail extends controller_base_action
{

    function __construct() {
        $this->objName = "expensedetail";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * ��ת���������������ϸ(���ű���)�б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת�������������������ϸ(���ű���)ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭�������������ϸ(���ű���)ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴�������������ϸ(���ű���)ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ת���༭��Ʊ���Ͳ���
     */
    function c_toEitCostTypeID() {
        $this->assignFunc($_GET);
        $this->assign('newCostTypeID', $this->service->initCostOption_d($_GET['CostTypeID']));
        $this->view('editcosttypeid');
    }

    /**
     * �༭����
     */
    function c_editCostTypeID() {
        //ԭ��Ʊ����id
        $costTypeId = $_POST['costTypeId'];
        //�·�Ʊ����id
        $newCostTypeId = $_POST['newCostTypeId'];
        $newMainTypeId = $_POST['newMainTypeId'];
        $newMainType = util_jsonUtil::iconvUTF2GB($_POST['newMainType']);
        //��Ʊ����
        $BillNo = $_POST['BillNo'];

        echo $this->service->editCostTypeID_d($costTypeId, $newCostTypeId, $BillNo, $newMainTypeId, $newMainType) ?
            1 : 0;
    }

    /**
     *  ajax��ȡ��Ʊ��Ϣ
     */
    function c_ajaxGetCostDetail() {
        //��ȡ��ϸ��Ϣ
        $billDetail = $this->service->getBillDetail_d($_POST['BillNo']);
        echo util_jsonUtil::iconvGB2UTF($this->service->initBillDetailViewEdit_d($billDetail));
    }

    /**
     * �༭������ϸ �� ������������
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
        //��Ⱦԭ����
        $this->assign('newCostTypeID', $this->service->initCostOption_d($_GET['CostTypeID']));
        // ��ȡ��̯˵��
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        $rs = $expensecostshareDao->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('editdetail');
    }

    /**
     * �༭������ϸ
     */
    function c_editDetail() {
        //�����޸�
        $rs = $this->service->editDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('�޸ĳɹ�');parent.show_pageExpense('$rs');parent.tb_remove();</script>";
        } else {
            msg('�޸�ʧ��');
        }
    }

    /**
     * ����������ϸ �� ������������
     */
    function c_toAddDetail() {
        $billNo = isset($_GET['BillNo'])? $_GET['BillNo'] : '';
        $this->assignFunc($_GET);
        $specialGroupId = 347;// ˰�Ѵ���ID

        // ��Ⱦԭ����ѡ��
        $canTakeOutTypeOpts = "";
        $canTakeOutTypeArr = $this->service->_db->getArray("select GROUP_CONCAT(d.ID) as IDs,d.HeadID,d.AssID,d.MainType,t.costTypeID,t.costTypeName,sum(d.CostMoney) as CostMoney from cost_detail d left join cost_type t on t.CostTypeID = d.CostTypeID where d.BillNo = '{$billNo}' group by d.costTypeID;");
        if($canTakeOutTypeArr && is_array($canTakeOutTypeArr)){
            foreach ($canTakeOutTypeArr as $v){
                $canTakeOutTypeOpts .= "<option value='{$v['IDs']}' costtypeid='{$v['costTypeID']}' amount='{$v['CostMoney']}' headid='{$v['HeadID']}' assid='{$v['AssID']}'>{$v['costTypeName']}(���: {$v['CostMoney']})</option>";
            }
        }
        $this->assign('canTakeOutTypeOpts', $canTakeOutTypeOpts);

        // ��Ⱦ˰������ѡ��
        $newCostTypeID = $this->service->initCostOption_d('',$specialGroupId);
        $this->assign('newCostTypeID', $newCostTypeID);
        // ��ȡ��̯˵��
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        $rs = $expensecostshareDao->find(array('CostTypeID' => $_GET['CostTypeID'], 'BillNo' => $_GET['BillNo']), null,
            'Remark');
        $this->assign('RemarkCostshare',empty($rs) ? "" : $rs['Remark']);
        $this->view('adddetail');
    }

    /**
     * ����������ϸ
     */
    function c_addDetail() {
        //��������
        $rs = $this->service->addDetail_d($_POST[$this->objName]);
        if ($rs) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('����ɹ�');parent.show_pageExpense('$rs');parent.tb_remove();</script>";
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ɾ�������ķ�������
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
                // ��ѯԭ���ֳ��˵ķ������һ����Ʊ��¼
                $billDetailChkSql = "select * from bill_detail where BillDetailID = '{$toTakeOutTypeIdsArr[0]}' limit 1;";
                $needUpdatebillDetail = $this->service->_db->get_one($billDetailChkSql);
                // ��ѯԭ���ֳ��˵ķ������һ����̯��¼
                $financeShareChkSql = "select c.* from oa_finance_costshare c left join cost_detail d on (c.BillNo = d.BillNo and c.CostTypeID = d.CostTypeID) where d.ID = '{$toTakeOutTypeIdsArr[0]}' limit 1;";
                $needUpdateFinanceShare = $this->service->_db->get_one($financeShareChkSql);

                // ����������˰�ѵĽ��,�˻ص�ԭ�ֳ�������͵�һ����¼��
                $updateCostDetailSql = "update cost_detail set CostMoney = (CostMoney + {$costMoney}) where ID = '{$toTakeOutTypeIdsArr[0]}';";
                $this->service->_db->query($updateCostDetailSql);

                // ����������˰�ѵĽ��,�˻ص�ԭ�ֳ�������͵�һ����Ʊ��¼��
                if($needUpdatebillDetail && isset($needUpdatebillDetail['ID'])){
                    $updateBillDetailSql = "update bill_detail set Amount = (Amount + {$costMoney}) where ID = '{$needUpdatebillDetail['ID']}';";
                    $this->service->_db->query($updateBillDetailSql);
                }

                // ����������˰�ѵĽ��,�˻ص�ԭ�ֳ�������͵�һ����̯��¼��
                if($needUpdateFinanceShare && isset($needUpdateFinanceShare['ID'])){
                    $updateFinanceShareSql = "update oa_finance_costshare set CostMoney = (CostMoney + {$costMoney}) where ID = '{$needUpdateFinanceShare['ID']}';";
                    $this->service->_db->query($updateFinanceShareSql);
                }

                // ɾ�������ķ�����
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