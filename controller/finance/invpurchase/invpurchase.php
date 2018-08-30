<?php

/**
 * @author Show
 * @Date 2010年12月21日 星期二 15:52:09
 * @version 1.0
 * @description:采购发票控制层
 */
class controller_finance_invpurchase_invpurchase extends controller_base_action
{

    function __construct()
    {
        $this->objName = "invpurchase";
        $this->objPath = "finance_invpurchase";
        parent::__construct();
    }

    /*
     * 跳转到采购发票
     */
    function c_page()
    {
        $type = $_GET['type'];
        if ($type == 'assetPurOnly') {// 固定资产的采购发票信息
            $this->assign('listType', $type);
        } else {
            $this->assign('listType', '');
        }
        $this->display('list');
    }

    /**
     * pagsjson
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
//		$service->asc = false;
        if (isset($_POST['listType']) && $_POST['listType'] == 'assetPurOnly') {// 固定资产的采购发票信息
            $service->searchArr['inPruType'] = 'oa_asset_purchase_apply';
        } else {// 非固定资产的采购发票信息
            $service->searchArr['noPruType'] = 'oa_asset_purchase_apply';
        }

        $arr = array();

        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr['pageSql'] = $service->listSql;
        $rows = $this->service->pageCount_d($rows);

        if (is_array($rows)) {
            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['objCode'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }


        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 采购发票列表
     */
    function c_pageh()
    {
        $service = $this->service;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $service->getParam($_GET); //设置前台获取的参数信息
        $service->sort = 'c.updateTime';
        $rows = $service->pageBySqlId();
        $this->pageShowAssign();
        $this->assign('status', $status);
        $this->assign('list', $service->showlistDetail($rows));
        $this->display('listh');
    }

    /**
     * 在采购合同中显示相关采购发票
     */
    function c_showContractList()
    {
        $service = $this->service;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $service->getParam($_GET); //设置前台获取的参数信息
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $service->sort = 'c.updateTime';
        $rows = $service->pageBySqlId();
        $this->pageShowAssign();
        $this->assign('status', $status);
        $this->assign('list', $service->showlistDetailInPurCont($rows));
        $this->display('contractlisth');
    }

    /**
     * 采购合同相关采购发票
     */
    function c_toHistory()
    {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], 'purchase_contract_purchasecontract');
        $this->assign('skey', $_GET['skey']);
        $this->assignFunc($obj);
        $this->display('history');
    }

    /**
     * 采购合同查看对应采购发票
     */
    function c_pageJsonHistory()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->groupBy = 'c.id';
        $rows = $service->page_d('history');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 根据供应商产品采购发票记录
     */
    function c_toListBySupplier()
    {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('listbysupplier');
    }

    /**
     * 个人采购发票查看
     */
    function c_myList()
    {
        $this->display('mylist');
    }

    /**
     * 我的采购发票PAGEJSON
     */
    function c_myPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['createId'] = $_SESSION['USER_ID'];

        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到新增页面
     */
    function c_toAdd()
    {
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));
        $this->showDatadicts(array('sourceType' => 'CGFPYD'), null, true);

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->display('add');
    }

    /**
     * 重写新增方法
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($object);
        if ($id) {
            if ($_GET['act'] == 'audit') {
                if ($this->service->audit_d($id)) {
                    msgRf('审核成功！');
                } else {
                    msgRf('审核失败！');
                }
            } else {
                msgRf('添加成功!');
            }
        } else {
            msgRf('添加失败!');
        }
    }

    /**
     * 跳转到独立新增页面
     */
    function c_toAddDept()
    {
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));
        $this->showDatadicts(array('sourceType' => 'CGFPYD'), null, true);

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        $this->display('adddept');
    }

    /**
     * 独立新增方法,保存后跳转到原页面
     */
    function c_addDept()
    {
        if ($this->service->add_d($_POST[$this->objName])) {
            msgGo('添加成功！', '?model=finance_invpurchase_invpurchase&action=toAddDept');
        } else {
            msgGo('添加失败!', '?model=finance_invpurchase_invpurchase&action=toAddDept');
        }
    }

    /**
     * 采购合同库选择录入发票页面
     */
    function c_selectInv()
    {
        $this->assign('applyId', $_GET['applyId']);
        $this->assign('applyCode', $_GET['applyCode']);
        $this->display('selectinv');
    }

    /**
     * 下推生成采购发票
     */
    function c_toAddForPushDown()
    {
        $instockId = $_GET['id'];
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        $rows = $this->service->getInStock_d($instockId);
        if (empty($rows) || !is_array($rows)) {
            msgRf('该发票己经处理完毕或者单据未审核');
            exit();
        }
        $pArr = $rows;
        $formInfo = array_pop($pArr);
        if ($instockId * 1 != $instockId) {
            $formInfo['purOrderCode'] = "";
            $formInfo['purOrderId'] = "";
            $formInfo['docCode'] = "";
        }
        //处理鼎利数据中可能存在的空数据
        if (!isset($formInfo['supplierName']) || empty($formInfo['supplierName'])) {
            $formInfo['supplierName'] = "";
            $formInfo['supplierId'] = "";
        }

        $formInfo['sourceType'] = 'CGFPYD-02';
        $formInfo['sourceTypeCN'] = $this->getDataNameByCode('CGFPYD-02');
        $this->assignFunc($formInfo);

        if ($rows) {
            $purchnotarripro = $this->service->showInStockProInfo($rows);
            $this->assign('invnumber', $purchnotarripro[0]);
            $this->assign('invpurro', $purchnotarripro[1]);
        } else {
            $this->assign('invpurro', '没有相关设备清单');
        }

        //外购入库下推添加自动带出部门
        $otherdatasDao = new model_common_otherdatas();
        $rs = $otherdatasDao->getUserDatas($formInfo['purchaserCode'], array('DEPT_ID', 'DEPT_NAME'));
        $this->assign('departmentsId', $rs['DEPT_ID']);
        $this->assign('departments', $rs['DEPT_NAME']);

        //邮件信息渲染
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->view('addforpushdown');
    }

    /**
     * 采购合同录入采购发票
     */
    function c_toAddInPurCont()
    {
        $this->permCheck($_GET['applyId'], 'purchase_contract_purchasecontract');//安全校验
        $purAppId = $_GET['applyId'];

        $rows = $this->service->getContractinfoById($purAppId); //根据采购合同Id获取未货到信息
        $purchpros = $this->service->getNotArrPurchPros($purAppId);

        $invoiceTypeStr = '<span style="float:right;padding-right:10px;">' .
            '<font color="blue">蓝色</font><input type="radio" name="invpurchase[formType]" value="blue" checked="checked" onchange="changeTitle(this.value)"/>' .
            '<font color="red">红色</font><input type="radio" name="invpurchase[formType]" value="red" onchange="changeTitle(this.value)"/>' .
            '</span>';

        // 如果是资产采购的默认蓝色 ID2209 资产采购的录入采购发票接口 2016-12-12
        if (isset($_GET['InvoiceType']) && $_GET['InvoiceType'] == 'assetsPurchase') {
            $invoiceTypeStr = '<span style="float:right;padding-right:10px;"><input type="hidden" name="invpurchase[formType]" value="blue"/></span>';

        }
        $this->assign('invoiceTypeStr', $invoiceTypeStr);

        if (!is_array($purchpros)) {
            msg('已达录入上限');
            exit();
        }
        $this->assignFunc($rows);

        $this->assign('applyId', $purAppId);
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType']);
        $this->showDatadicts(array('purType' => 'cgfs'));

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType'], null, array('expand4No' => '1'));
        }

        if ($purchpros) {
            $purchnotarripro = $this->service->showPurchAppProInfo($purchpros, $rows);
            $this->assign('invnumber', $purchnotarripro[0]);
            $this->assign('invpurro', $purchnotarripro[1]);
        } else {
            $this->assign('invpurro', '没有相关设备清单');
        }

        $this->display('addinpurcon');
    }

    /**
     * 重写初始化方法
     */
    function c_init()
    {
        //URL权限控制
        $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

        $rows = $this->service->getInfo_d($_GET['id'], $perm);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '蓝字发票');
        } else {
            $this->assign('formTypeCN', '<span class="red">[红字发票]</span>');
        }
        if ($perm == 'view') {//查看页面
            $this->assign('purSkey', $this->md5Row($rows['purcontId'], 'purchase_contract_purchasecontract'));
            $this->assign('invType', $this->getDataNameByCode($rows['invType']));
            $this->assign('purType', $this->getDataNameByCode($rows['purType']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($rows['sourceType']));

            $this->display('view');

        } else if ($perm == 'break') {//拆分页面
            $this->assign('invTypeCN', $this->getDataNameByCode($rows['invType']));
            $this->assign('purTypeCN', $this->getDataNameByCode($rows['purType']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($rows['sourceType']));

            $this->display('break');

        } else {//编辑页面

            // $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
            $otherDataDao = new model_common_otherdatas();
            $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
                $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
            }else{
                $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType'], null, array('expand4No' => '1'));
            }

            $this->showDatadicts(array('purType' => 'cgfs'), $rows['purType']);
            $this->assign('sourceTypeCN', $this->getDataNameByCode($rows['sourceType']));

            $this->display('edit');
        }
    }

    /**
     * 采购合同中编辑采购发票
     */
    function c_initEditInPurCon()
    {
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

        $rows = $this->service->getInfo_d($_GET['id'], $perm);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '蓝字发票');
        } else {
            $this->assign('formTypeCN', '<span class="red">[红字发票]</span>');
        }


        //$this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType'], null, array('expand4No' => '1'));
        }

        $this->showDatadicts(array('purType' => 'cgfs'), $rows['purType']);

        $this->display('editinpurcon');
    }

    /**
     * 打印
     */
    function c_toPrint()
    {
        //URL权限控制
        $this->permCheck();
        $rows = $this->service->getInfo_d($_GET['id'], 'view');
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '');
        } else {
            $this->assign('formTypeCN', '<span class="red">[红字发票]</span>');
        }

        $this->assign('invType', $this->getDataNameByCode($rows['invType']));
        $this->assign('purType', $this->getDataNameByCode($rows['purType']));

        $this->display('print');
    }

    /**
     * 重写编辑方法
     */
    function c_edit()
    {
        try {
            $object = $_POST[$this->objName];
            if ($this->service->edit_d($object)) {
                if ($_GET['act'] == 'audit') {
                    if ($this->service->audit_d($object['id'])) {
                        msgRf('审核成功！');
                    } else {
                        msgRf('审核失败！');
                    }
                }
                msgRf('保存成功！');
            } else {
                msgRf('保存失败!');
            }
        } catch (Exception $e) {
            msgRf('保存失败，失败原因：' . $e->getMessage());
        }
    }

    /*
     * ajax方式批量删除对象（应该把成功标志跟消息返回）
     */
    function c_ajaxdeletes()
    {
        //$this->permDelCheck ();
        $id = $_POST['id'];
        if ($id * 1 != $id) {
            echo util_jsonUtil::iconvGB2UTF('不支持批量删除！');
        } else {
            try {
                $rs = $this->service->find(array('id' => $id), null, 'ExaStatus');
                if (!empty($rs['ExaStatus'])) {
                    echo util_jsonUtil::iconvGB2UTF('单据已经审核，不能进行删除操作');
                    exit();
                }
                $this->service->deletes_d($_POST['id']);
                echo 1;
            } catch (Exception $e) {
                echo 0;
            }
        }
    }

    /**
     *  拆分
     */
    function c_break()
    {
        if ($this->service->break_d($_POST[$this->objName])) {
            msgRf('拆分成功！');
        } else {
            msgRf('拆分失败!');
        }
    }

    /**
     * 合并单据
     */
    function c_merge()
    {
        echo $this->service->merge_d($_POST['id'], $_POST['belongId']) ? 1 : 0;
    }

    /**
     * 判断单据是否已经拆分
     */
    function c_isBreak()
    {
        echo $this->service->isBreak_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 审核
     */
    function c_audit()
    {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 反审核
     */
    function c_unaudit()
    {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 钩稽
     */
    function c_toHook()
    {
        $page = 'hook';
        $rs = $this->service->rtThisPeriod_d();
        $this->assign('sysYear', $rs['thisYear']);
        $this->assign('sysMonth', $rs['thisMonth']);

        /**
         * 为资产采购订单录入的发票选择独立的勾稽页面
         * ID2209 2016-12-6
         */
        $cardsDetail = $this->showCardsToHook($_GET['id']);
        if (isset($cardsDetail['msg']) && $cardsDetail['msg'] == 'ok') {
            $this->assign('purchType', 'assets');
            $this->assign('cardsTable', $cardsDetail['str']);
            $this->assign('productCount', $cardsDetail['totalNum']);
            $this->assign('productCodeStr', $cardsDetail['productCodeStr']);
            $page = 'assetsHook';
        }

        /**
         * 原采购发票默认带出处理
         */
        $supplier = $this->service->find(array('id' => $_GET['id']), null, 'supplierName,supplierId');
        $rows = $this->service->hookRows_d($_GET['id']);
        $this->assign('invList', $rows[0]);
        $this->assign('invCount', $rows[1]);

        /**
         * 添加外购入库单默认带出处理
         */
        $this->assign('supplierName', $supplier['supplierName']);
        $this->assign('supplierId', $supplier['supplierId']);
        $this->assign('invpurId', $_GET['id']);
        $this->display($page);
    }

    /**
     * 获取可勾稽选项Json数据 ID2209 2016-12-6
     */
    function c_getCardsToHookJson()
    {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : '';
        $ids = rtrim($ids, ',');
        $backArr['msg'] = '';
        $backArr['data'] = array();
        if ($ids != '') {
            $data = $this->showCardsToHook($ids);
            $backArr['totalNum'] = $data['totalNum'];
            $backArr['productCodeStr'] = $data['productCodeStr'];
            if ($data['msg'] == 'ok') {
                $backArr['msg'] = 'ok';
                $backArr['data'] = $data['str'];
            }
        }
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * 显示可勾稽选项 ID2209 2016-12-6
     * @param $invoiceIds
     * @return array
     */
    function showCardsToHook($invoiceIds)
    {
        $totalNum = 0;
        $productCodeStr = '';
        // 获取卡片数据
        $backArr = $row = array();
        $sql = "SELECT
				c.id,c.productNumb,c.purchType,d.id as detailId,d.objId,d.number,d.objCode,d.invPurId,i.objCode as invPurCode
			FROM
				oa_finance_invpurchase_detail d
				LEFT JOIN oa_finance_invpurchase i ON d.invPurId = i.id
				LEFT JOIN oa_purch_apply_equ c ON c.basicId = d.objid
			WHERE
				d.invPurId IN ({$invoiceIds})
				AND c.productNumb = d.productNo
				AND c.purchType IN (
					'assets',
					'oa_asset_purchase_apply'
			) GROUP BY c.id,d.invPurId ORDER BY c.purchType DESC;";
        $hasAssetsType = $this->service->_db->getArray($sql);
        $purchaseEquDao = new model_purchase_contract_equipment();
        $backArr['msg'] = (!empty($hasAssetsType)) ? 'ok' : '';
        $detailIdArr = array();
        $productNoArr = array();
        $searchedArr = array();
//		echo "<pre>"; print_r($hasAssetsType);exit();
        foreach ($hasAssetsType as $k => $v) {
            if (!in_array($v['id'], $searchedArr)) {
                $searchedArr[] = $v['id'];
                if (!in_array($v['detailId'], $detailIdArr)) {//避免相同单据内有同样物料编号的情况导致数据统计出错临时处理方法
                    if (isset($productNoArr[$v['objCode'] . "_" . $v['productNumb']])) {
                        $productNoArr[$v['objCode'] . "_" . $v['productNumb']] += $v['number'];
                    } else {
                        $productNoArr[$v['objCode'] . "_" . $v['productNumb']] = $v['number'];
                    }

                    array_push($detailIdArr, $v['detailId']);
                    $totalNum += $v['number'];
                }
                if ($v['purchType'] != '') {
                    $cdata = $purchaseEquDao->searchAssetCardByOrderId($v['objId'], $v['id'], $v['productNumb']);
                    if ($cdata['linkStr'] != '') {
                        $cdata['number'] = $v['number'];
                        $cdata['productNo'] = $v['productNumb'];
                        $cdata['invPurId'] = $v['invPurId'];
                        $cdata['invPurCode'] = $v['invPurCode'];
                        $row[] = $cdata;
                    }
                }
            }
        }

        // 物料以及对应的数量拼接
        foreach ($productNoArr as $k => $v) {
            $productCodeStr .= $k . ':' . $v . ',';
        }

        $backArr['totalNum'] = $totalNum;
        $backArr['productCodeStr'] = rtrim($productCodeStr, ',');

        // 拼接卡片数据html
        $str = '';
        $cardsArr = array();
        if (is_array($row) && !empty($row)) {
            // 分解卡片数据
            foreach ($row as $k => $v) {
                foreach ($v['detail'] as $dk => $dv) {
                    $check_sql = "select count(id) as num from oa_finance_assetscard_hookrecord where cardNo = '{$dk}' and productNo = '{$dv['productNo']}' and objCode = '{$dv['objCode']}'";
                    $hookedNum = $this->service->_db->getArray($check_sql);
                    if ($hookedNum[0]['num'] > 0) {// 排除掉已经勾稽了的卡片
                        unset($v['detail'][$dk]);
                    } else {
                        $dv['linkStr'] = rtrim($dv['linkStr'], ',');
                        $dv['cardCode'] = $dk;
                        $dv['sql'] = $check_sql;
                        $dv['invPurId'] = $v['invPurId'];
                        $dv['invPurCode'] = $v['invPurCode'];
                        $cardsArr[] = $dv;
                    }
                }
            }
//			echo "<pre>"; print_r($cardsArr);exit();

            // 拼接卡片数据html
            $productIdsArr = array();
            foreach ($cardsArr as $k => $v) {
                $str .= <<<EOT
						<tr>
							<td height="25" align="center">
								<input type="checkbox" name="checkCards[]" id="$v[cardCode]" value="$v[productID],$v[productNo],$v[objCode],$v[cardCode],$v[bindId],$v[invPurId]">
							</td>
							<td align="center" ><b>$v[linkStr]</b></td>
							<td align="center" >$v[productNo]</td>
							<td align="center">$v[objCode]</td>
						</tr>
EOT;
            }
//			echo "<pre>"; print_r($backArr);exit();

            $backArr['cards'] = $cardsArr;
            $backArr['str'] = $str;
        }

        return $backArr;
    }

    /**
     * 暂估冲回
     */
    function c_toRelease()
    {
        $supplier = $this->service->find(array('id' => $_GET['id']), null, 'supplierName,supplierId');
        $rows = $this->service->hookRows_d($_GET['id'], false);
        $this->assign('invList', $rows[0]);
        $this->assign('invCount', $rows[1]);
        $this->assign('supplierName', $supplier['supplierName']);
        $this->assign('supplierId', $supplier['supplierId']);
        $this->assign('invpurId', $_GET['id']);
        $this->display('release');
    }

    /**
     * 发票钩稽专用下拉表格
     */
    function c_pageJsonGrid()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = false;
        $rows = $service->pageJsonGrid_d();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 显示添加采购发票页面
     */
    function c_instockHookPage()
    {
        $service = $this->service;
        $service->getParam($_GET); //设置前台获取的参数信息
        $service->searchArr['status'] = 0;
        $service->sort = 'c.formDate';
        $rows = $service->pageBySqlId('easy_list');
        $this->pageShowAssign();
        $this->assign('list', $service->showlistHook($rows));
        $this->assignFunc($_GET);
        $objNo = isset($_GET['objNo']) ? $_GET['objNo'] : null;
        $objCodeSearch = isset($_GET['objCodeSearch']) ? $_GET['objCodeSearch'] : null;
        $this->assign('searchId', empty($objNo) ? $objCodeSearch : $objNo);
        $this->display('hookpage');
    }

    /**
     * 从gird获取采购发票清单 －－ 入库单用
     */
    function c_getItemList()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $list = $this->service->getEquList_d($id);
        echo util_jsonUtil::iconvGB2UTF($list);
    }

    /**
     * 从gird获取采购发票清单 －－ 入库单用
     */
    function c_getItemListJson()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $list = $this->service->getEquListJson_d($id);
        echo util_jsonUtil::iconvGB2UTF($list);
    }

    /****************************核算部分*******************************/

    /**
     * 外购入库核算采购列表
     */
    function c_pageCalculate()
    {
        $rs = $this->service->rtThisPeriod_d();
        $this->assign('thisYear', $rs['thisYear']);
        $this->assign('thisMonth', $rs['thisMonth']);
        $this->display('listcalculate');
    }

    /**
     * pagsjson
     */
    function c_pageJsonCacu()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = false;
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**************************采购发票列表***************************/

    /**
     * 采购发票高级搜索
     */
    function c_toSearch()
    {
        $this->showDatadicts(array('status' => 'CGFPZT'));
        $this->showDatadicts(array('invType' => 'FPLX'));
        $this->display('search');
    }

    /**************************审核权限******************************/

    /**
     * 采购发票审核权限
     */
    function c_hasLimitToAudit()
    {
        echo $this->service->this_limit['审核'] ? 1 : 0;
    }

    /**
     * 采购发票审核权限
     */
    function c_hasLimitToUnaudit()
    {
        echo $this->service->this_limit['反审核'] ? 1 : 0;
    }

    /**
     * 采购发票删除权限
     */
    function c_hasLimitToDelete()
    {
        echo $this->service->this_limit['删除'] ? 1 : 0;
    }

    /**
     * 采购发票反钩稽权限
     */
    function c_hasLimitToUnHook()
    {
        echo $this->service->this_limit['反钩稽'] ? 1 : 0;
    }

    /**************************报表列表页面***************************/
    /**
     * 报表工具查询列表
     */
    function c_viewList()
    {
        unset($_GET['action']);
        unset($_GET['model']);
        $thisObj = !empty($_GET) ? $_GET : array('formDateBegin' => '', 'formDateEnd' => '', 'supplierId' => '', 'objNo' => '',
            'salesmanId' => '', 'exaManId' => '', 'status' => '', 'formType' => '', 'ExaStatus' => '', 'invType' => '', 'productNo' => '');
        $this->assignFunc($thisObj);
        $this->display('viewList');
    }

    /**
     * 采购发票高级搜索
     */
    function c_toViewListSearch()
    {
        $this->showDatadicts(array('status' => 'CGFPZT'));
        $this->showDatadicts(array('invType' => 'FPLX'));
        $this->display('viewlist-search');
    }

    /**************************** 采购发票旧数据处理 *********************/
    /**
     * 采购发票旧数据处理
     */
    function c_toOldDataDeal()
    {
        $this->display('olddatadeal');
    }

    /**
     * 获取重复数据
     */
    function c_getRepeatArr()
    {
        $sql = "
			select c.objNo,d.id,d.invPurId,d.productId,d.productNo,d.productName,d.number,d.expand1,d.objId,d.objCode,d.contractCode from
			oa_finance_invpurchase c inner join oa_finance_invpurchase_detail d on c.id = d.invPurId
			where
				d.objType = 'CGFPYD-02' and (d.expand1 = '' or d.expand1 is null) and d.objId in (
				select
					c.id
				from
					oa_stock_instock c inner join oa_stock_instock_item i on c.id = i.mainId
				where c.docType = 'RKPURCHASE'
				group by c.id,i.productId
				having count(1) > 1 )
			ORDER BY d.invPurId limit 0,50";
        $rs = $this->service->_db->getArray($sql);
        if (is_array($rs)) {
            echo $this->repeatInfoShow($rs);
        } else {
            echo 1;
        }
    }

    /**
     * 获取重复数据 - 数量
     */
    function c_getCount()
    {
        $sql = "
			select count(*) as countNum from
			oa_finance_invpurchase c inner join oa_finance_invpurchase_detail d on c.id = d.invPurId
			where
				d.objType = 'CGFPYD-02' and (d.expand1 = '' or d.expand1 is null) and d.objId in (
				select
					c.id
				from
					oa_stock_instock c inner join oa_stock_instock_item i on c.id = i.mainId
				where c.docType = 'RKPURCHASE'
				group by c.id,i.productId
				having count(1) > 1 )
			ORDER BY d.invPurId limit 0,50";
        $rs = $this->service->_db->getArray($sql);
        if (is_array($rs)) {
            echo $rs[0]['countNum'];
        } else {
            echo 0;
        }
    }

    /**
     * 重复数据显示
     */
    function repeatInfoShow($rows)
    {
        $str = null;
        $i = 0;
        foreach ($rows as $key => $val) {
            ++$i;
            $str .= <<<EOT
				<tr class="invpurchase$val[invpurId]">
					<td>$i</td>
					<td>$val[invPurId]</td>
					<td>$val[objNo]</td>
					<td>$val[productId]</td>
					<td>$val[productNo]</td>
					<td>$val[number]</td>
					<td>$val[objCode]</td>
					<td>$val[contractCode]</td>
					<td>
						<a href="javascript:void(0)" onclick="showThickboxWin('?model=finance_invpurchase_invpurchase&action=toDeal&id=$val[invPurId]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700')">
							Edit
						</a>
					</td>
				</tr>
EOT;
        }
        return $str;
    }

    //处理页面
    function c_toDeal()
    {
        //渲染发票信息
        $obj = $this->service->getDetail($_GET['id']);
        $this->assign('dealInfo', $this->dealInfoShow($obj));

        //渲染入库信息
        $stockInfo = $this->service->getStockInfo_d($obj);
        $this->assign('stockinfo', $this->stockInfoShow($stockInfo));

        //入库信息 id
        $objIdArr = array();
        foreach ($stockInfo as $key => $val) {
            if (!in_array($val['detailId'], $objIdArr)) {
                array_push($objIdArr, $val['detailId']);
            }
        }
        $ids = implode($objIdArr, ',');
        $this->assign('stockIds', $ids);

        $this->display('deal');
    }

    //单据显示
    function dealInfoShow($object)
    {
        $str = null;
        foreach ($object as $key => $val) {
            $str .= <<<EOT
				<tr>
					<td>$val[id]<input type="hidden" name="invpurchase[$key][id]" value="$val[id]"/></td>
					<td>$val[productId]</td>
					<td>$val[productNo]</td>
					<td>$val[productName]</td>
					<td>$val[productModel]</td>
					<td>$val[number]</td>
					<td>$val[price]</td>
					<td>$val[objCode]</td>
					<td><input type="text" name="invpurchase[$key][expand1]" style="width:50px" value="$val[expand1]" onblur="checkValue(this);"/></td>
				</tr>
EOT;
        }
        return $str;
    }

    //入库单渲染
    function stockInfoShow($object)
    {
        $str = null;
        foreach ($object as $key => $val) {
            $str .= <<<EOT
				<tr>
					<td>$val[docCode]</td>
					<td><span class="green">$val[detailId]</span></td>
					<td>$val[productId]</td>
					<td>$val[productCode]</td>
					<td>$val[productName]</td>
					<td>$val[pattern]</td>
					<td>$val[actNum]</td>
					<td>$val[price]</td>
				</tr>
EOT;
        }
        return $str;
    }

    //处理方法 - 其实就是更新 expand1
    function c_deal()
    {
        $rs = $this->service->deal_d($_POST[$this->objName]);
        if ($rs === true) {
            echo "<script>alert('更新成功');self.parent.tb_remove();parent.show_page()</script>";
        } else {
            echo $rs;
        }
    }

    //更新其他信息 - 即 唯一对应的单据
    function c_updateOther()
    {
        $sql = "update oa_finance_invpurchase_detail d inner join (
				select d.mainId,d.id,d.productId from oa_stock_instock c inner join oa_stock_instock_item d on c.id = d.mainId where c.docType = 'RKPURCHASE'
			) o on d.objId = o.mainId and d.productId = o.productId
			set d.expand1 = o.id
			where d.objType = 'CGFPYD-02' and (d.expand1 = '' or d.expand1 is null)
			";
        $rs = $this->service->_db->query($sql);
        if ($rs == 1) {
            echo '1';
        } else {
            echo util_jsonUtil::iconvUTF2GB($rs);
        }
    }

    /**
     * 发票编号重复验证
     */
    function c_ajaxCheck()
    {
        echo $this->service->find(array('objNo' => util_jsonUtil::iconvUTF2GB($_GET['objNo'])), null, 'id') ? 1 : 0;
    }
}