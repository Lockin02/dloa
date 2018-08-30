<?php

/**
 * @author Show
 * @Date 2012年11月2日 星期五 11:43:46
 * @version 1.0
 * @description:费用类型表控制层
 */
class controller_finance_expense_costtype extends controller_base_action
{

    function __construct() {
        $this->objName = "costtype";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /****************** 列表部分 ******************/

    /**
     * 跳转到费用类型表列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 费用类型树表
     */
    function c_treeJson() {
        $service = $this->service;
        $_REQUEST['isNew'] = isset($_REQUEST['isNew']) ? $_REQUEST['isNew'] : 1;

        //如果是树级刷新，则特殊处理
        if ($_REQUEST['id']) {
            $idArr = $this->service->findTreeIds_d($_REQUEST['id'], array($_REQUEST['id'] => 'a'));
            $_REQUEST['ParentCostTypeIDs'] = implode(array_keys($idArr), ',');
            unset($_REQUEST['id']);
        }

        $service->getParam($_REQUEST); //设置前台获取的参数信息
        $service->asc = false;
        $service->sort = "c.orderNum";
        $arr = $service->listBySqlId();

        if (!empty($arr)) {
            //除去_parentId
            foreach ($arr as $key => $val) {
                if ($val['_parentId'] == 1) {
                    unset($arr[$key]['_parentId']);
                }
            }
        }
        //数组设值
        $rows['rows'] = $arr;

        echo util_jsonUtil:: encode($rows);
    }

    /******************* 增删查改 ********************/

    /**
     * 跳转到新增费用类型表页面
     */
    function c_toAdd() {
        //上级信息渲染
        $ParentCostTypeID = isset($_GET['ParentCostTypeID']) ? $_GET['ParentCostTypeID'] : '1';
        $ParentCostType = isset($_GET['ParentCostType']) ? $_GET['ParentCostType'] : '所有费用类别';

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

        //默认费用类型渲染
        $billArr = $this->service->getBillType_d();
        $this->assign('invoiceType', $this->service->initBillTypeClear_d($billArr, $parentArr['invoiceType']));

        // 数据字典调用
        $this->showDatadicts(array('budgetType' => 'YSLX'), null, true);

        $this->view('add');
    }

    /**
     * 跳转到编辑费用类型表页面
     */
    function c_toEdit() {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //默认费用类型渲染
        $billArr = $this->service->getBillType_d();
        $this->assign('invoiceType', $this->service->initBillTypeClear_d($billArr, $obj['invoiceType']));

        // 数据字典调用
        $this->showDatadicts(array('budgetType' => 'YSLX'), $obj['budgetType'], true);

        $this->view('edit');
    }

    /**
     * 跳转到查看费用类型表页面
     */
    function c_toView() {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /************************* 费用类型部分 ************************/
    /**
     * 获取费用表
     */
    function c_getCostType() {
        $isEsm = isset($_POST['isEsm']) ? $_POST['isEsm'] : "";
        $isAction = isset($_POST['isAction']) ? $_POST['isAction'] : 1;
        echo util_jsonUtil::iconvGB2UTF($this->service->getCostType_d($isEsm, $isAction));
    }

    /**
     * 获取费用表内单个费用明细的信息
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
     * 费用类型选取界面
     */
    function c_toSelCostType() {
        $this->assign('costTypeId', $_GET['costTypeId']);
        $this->view('selcosttype');
    }

    /**
     * 费用类型名称转ID
     */
    function c_nameToId() {
        echo $this->service->nameToId_d(util_jsonUtil::iconvUTF2GB($_POST['names']));
    }
}