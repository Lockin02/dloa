<?php

/**
 * @author Show
 * @Date 2012��11��2�� ������ 11:43:46
 * @version 1.0
 * @description:�������ͱ���Ʋ�
 */
class controller_finance_expense_costtype extends controller_base_action
{

    function __construct() {
        $this->objName = "costtype";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /****************** �б��� ******************/

    /**
     * ��ת���������ͱ��б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ������������
     */
    function c_treeJson() {
        $service = $this->service;
        $_REQUEST['isNew'] = isset($_REQUEST['isNew']) ? $_REQUEST['isNew'] : 1;

        //���������ˢ�£������⴦��
        if ($_REQUEST['id']) {
            $idArr = $this->service->findTreeIds_d($_REQUEST['id'], array($_REQUEST['id'] => 'a'));
            $_REQUEST['ParentCostTypeIDs'] = implode(array_keys($idArr), ',');
            unset($_REQUEST['id']);
        }

        $service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "c.orderNum";
        $arr = $service->listBySqlId();

        if (!empty($arr)) {
            //��ȥ_parentId
            foreach ($arr as $key => $val) {
                if ($val['_parentId'] == 1) {
                    unset($arr[$key]['_parentId']);
                }
            }
        }
        //������ֵ
        $rows['rows'] = $arr;

        echo util_jsonUtil:: encode($rows);
    }

    /******************* ��ɾ��� ********************/

    /**
     * ��ת�������������ͱ�ҳ��
     */
    function c_toAdd() {
        //�ϼ���Ϣ��Ⱦ
        $ParentCostTypeID = isset($_GET['ParentCostTypeID']) ? $_GET['ParentCostTypeID'] : '1';
        $ParentCostType = isset($_GET['ParentCostType']) ? $_GET['ParentCostType'] : '���з������';

        $this->assign('ParentCostTypeID', $ParentCostTypeID);
        $this->assign('ParentCostType', $ParentCostType);

        if ($ParentCostTypeID == 1) {
            $parentArr = array(
                'showDays' => 0, 'isEqu' => 0,
                'isReplace' => 1, 'CostTypeLeve' => 1,
                'k3Code' => '', 'k3Name' => '',
                'invoiceTypeName' => '', 'invoiceType' => '', 'isSubsidy' => '0', 'isClose' => '0'
            );
        } else {
            $parentArr = $this->service->find(array('CostTypeID' => $ParentCostTypeID), null, 'showDays,isEqu,isReplace,CostTypeLeve,k3Code,k3Name,invoiceTypeName,invoiceType,isSubsidy,isClose');
            $parentArr['CostTypeLeve'] = $parentArr['CostTypeLeve'] + 1;
        }
        $this->assignFunc($parentArr);

        //Ĭ�Ϸ���������Ⱦ
        $billArr = $this->service->getBillType_d();
        $this->assign('invoiceType', $this->service->initBillTypeClear_d($billArr, $parentArr['invoiceType']));

        // �����ֵ����
        $this->showDatadicts(array('budgetType' => 'YSLX'), null, true);

        $this->view('add');
    }

    /**
     * ��ת���༭�������ͱ�ҳ��
     */
    function c_toEdit() {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //Ĭ�Ϸ���������Ⱦ
        $billArr = $this->service->getBillType_d();
        $this->assign('invoiceType', $this->service->initBillTypeClear_d($billArr, $obj['invoiceType']));

        // �����ֵ����
        $this->showDatadicts(array('budgetType' => 'YSLX'), $obj['budgetType'], true);

        $this->view('edit');
    }

    /**
     * ��ת���鿴�������ͱ�ҳ��
     */
    function c_toView() {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /************************* �������Ͳ��� ************************/
    /**
     * ��ȡ���ñ�
     */
    function c_getCostType() {
        $isEsm = isset($_POST['isEsm']) ? $_POST['isEsm'] : "";
        $isAction = isset($_POST['isAction']) ? $_POST['isAction'] : 1;
        echo util_jsonUtil::iconvGB2UTF($this->service->getCostType_d($isEsm, $isAction));
    }

    /**
     * ��ȡ���ñ��ڵ���������ϸ����Ϣ
     */
    function c_getSingleCostTypeInfoForFee(){
        $costTypeId = isset($_POST['costTypeId']) ? $_POST['costTypeId'] : "";;
        $sql = "select t1.CostTypeID,t1.CostTypeName,t2.CostTypeID as CostTypeParentID,t2.CostTypeName as CostTypeParentName,t2.isClose from cost_type t1 left join cost_type t2 on t1.ParentCostTypeID = t2.CostTypeID where 
        (t2.CostTypeLeve = 1 ) AND (t2.isNew = '1')
        AND (t1.isClose = '0' and t2.isClose = '0') and t1.CostTypeID = '{$costTypeId}'";
        $arr = $this->service->_db->getArray($sql);
        $arr = ($arr)? $arr[0] : array();
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��������ѡȡ����
     */
    function c_toSelCostType() {
        $this->assign('costTypeId', $_GET['costTypeId']);
        $this->view('selcosttype');
    }

    /**
     * ������������תID
     */
    function c_nameToId() {
        echo $this->service->nameToId_d(util_jsonUtil::iconvUTF2GB($_POST['names']));
    }
}