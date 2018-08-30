<?php

/**
 * @author Show
 * @Date 2010��12��21�� ���ڶ� 15:52:09
 * @version 1.0
 * @description:�ɹ���Ʊ���Ʋ�
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
     * ��ת���ɹ���Ʊ
     */
    function c_page()
    {
        $type = $_GET['type'];
        if ($type == 'assetPurOnly') {// �̶��ʲ��Ĳɹ���Ʊ��Ϣ
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
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->asc = false;
        if (isset($_POST['listType']) && $_POST['listType'] == 'assetPurOnly') {// �̶��ʲ��Ĳɹ���Ʊ��Ϣ
            $service->searchArr['inPruType'] = 'oa_asset_purchase_apply';
        } else {// �ǹ̶��ʲ��Ĳɹ���Ʊ��Ϣ
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
                $rsArr['objCode'] = '�ϼ�';
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
     * �ɹ���Ʊ�б�
     */
    function c_pageh()
    {
        $service = $this->service;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->sort = 'c.updateTime';
        $rows = $service->pageBySqlId();
        $this->pageShowAssign();
        $this->assign('status', $status);
        $this->assign('list', $service->showlistDetail($rows));
        $this->display('listh');
    }

    /**
     * �ڲɹ���ͬ����ʾ��زɹ���Ʊ
     */
    function c_showContractList()
    {
        $service = $this->service;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $service->sort = 'c.updateTime';
        $rows = $service->pageBySqlId();
        $this->pageShowAssign();
        $this->assign('status', $status);
        $this->assign('list', $service->showlistDetailInPurCont($rows));
        $this->display('contractlisth');
    }

    /**
     * �ɹ���ͬ��زɹ���Ʊ
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
     * �ɹ���ͬ�鿴��Ӧ�ɹ���Ʊ
     */
    function c_pageJsonHistory()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * ���ݹ�Ӧ�̲�Ʒ�ɹ���Ʊ��¼
     */
    function c_toListBySupplier()
    {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('listbysupplier');
    }

    /**
     * ���˲ɹ���Ʊ�鿴
     */
    function c_myList()
    {
        $this->display('mylist');
    }

    /**
     * �ҵĲɹ���ƱPAGEJSON
     */
    function c_myPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * ��ת������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));
        $this->showDatadicts(array('sourceType' => 'CGFPYD'), null, true);

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->display('add');
    }

    /**
     * ��д��������
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($object);
        if ($id) {
            if ($_GET['act'] == 'audit') {
                if ($this->service->audit_d($id)) {
                    msgRf('��˳ɹ���');
                } else {
                    msgRf('���ʧ�ܣ�');
                }
            } else {
                msgRf('��ӳɹ�!');
            }
        } else {
            msgRf('���ʧ��!');
        }
    }

    /**
     * ��ת����������ҳ��
     */
    function c_toAddDept()
    {
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));
        $this->showDatadicts(array('sourceType' => 'CGFPYD'), null, true);

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        $this->display('adddept');
    }

    /**
     * ������������,�������ת��ԭҳ��
     */
    function c_addDept()
    {
        if ($this->service->add_d($_POST[$this->objName])) {
            msgGo('��ӳɹ���', '?model=finance_invpurchase_invpurchase&action=toAddDept');
        } else {
            msgGo('���ʧ��!', '?model=finance_invpurchase_invpurchase&action=toAddDept');
        }
    }

    /**
     * �ɹ���ͬ��ѡ��¼�뷢Ʊҳ��
     */
    function c_selectInv()
    {
        $this->assign('applyId', $_GET['applyId']);
        $this->assign('applyCode', $_GET['applyCode']);
        $this->display('selectinv');
    }

    /**
     * �������ɲɹ���Ʊ
     */
    function c_toAddForPushDown()
    {
        $instockId = $_GET['id'];
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'));
        $this->showDatadicts(array('purType' => 'cgfs'));

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        $rows = $this->service->getInStock_d($instockId);
        if (empty($rows) || !is_array($rows)) {
            msgRf('�÷�Ʊ����������ϻ��ߵ���δ���');
            exit();
        }
        $pArr = $rows;
        $formInfo = array_pop($pArr);
        if ($instockId * 1 != $instockId) {
            $formInfo['purOrderCode'] = "";
            $formInfo['purOrderId'] = "";
            $formInfo['docCode'] = "";
        }
        //�����������п��ܴ��ڵĿ�����
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
            $this->assign('invpurro', 'û������豸�嵥');
        }

        //�⹺�����������Զ���������
        $otherdatasDao = new model_common_otherdatas();
        $rs = $otherdatasDao->getUserDatas($formInfo['purchaserCode'], array('DEPT_ID', 'DEPT_NAME'));
        $this->assign('departmentsId', $rs['DEPT_ID']);
        $this->assign('departments', $rs['DEPT_NAME']);

        //�ʼ���Ϣ��Ⱦ
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->view('addforpushdown');
    }

    /**
     * �ɹ���ͬ¼��ɹ���Ʊ
     */
    function c_toAddInPurCont()
    {
        $this->permCheck($_GET['applyId'], 'purchase_contract_purchasecontract');//��ȫУ��
        $purAppId = $_GET['applyId'];

        $rows = $this->service->getContractinfoById($purAppId); //���ݲɹ���ͬId��ȡδ������Ϣ
        $purchpros = $this->service->getNotArrPurchPros($purAppId);

        $invoiceTypeStr = '<span style="float:right;padding-right:10px;">' .
            '<font color="blue">��ɫ</font><input type="radio" name="invpurchase[formType]" value="blue" checked="checked" onchange="changeTitle(this.value)"/>' .
            '<font color="red">��ɫ</font><input type="radio" name="invpurchase[formType]" value="red" onchange="changeTitle(this.value)"/>' .
            '</span>';

        // ������ʲ��ɹ���Ĭ����ɫ ID2209 �ʲ��ɹ���¼��ɹ���Ʊ�ӿ� 2016-12-12
        if (isset($_GET['InvoiceType']) && $_GET['InvoiceType'] == 'assetsPurchase') {
            $invoiceTypeStr = '<span style="float:right;padding-right:10px;"><input type="hidden" name="invpurchase[formType]" value="blue"/></span>';

        }
        $this->assign('invoiceTypeStr', $invoiceTypeStr);

        if (!is_array($purchpros)) {
            msg('�Ѵ�¼������');
            exit();
        }
        $this->assignFunc($rows);

        $this->assign('applyId', $purAppId);
        $this->assign('thisDate', day_date);
        // $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType']);
        $this->showDatadicts(array('purType' => 'cgfs'));

        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['billingType'], null, array('expand4No' => '1'));
        }

        if ($purchpros) {
            $purchnotarripro = $this->service->showPurchAppProInfo($purchpros, $rows);
            $this->assign('invnumber', $purchnotarripro[0]);
            $this->assign('invpurro', $purchnotarripro[1]);
        } else {
            $this->assign('invpurro', 'û������豸�嵥');
        }

        $this->display('addinpurcon');
    }

    /**
     * ��д��ʼ������
     */
    function c_init()
    {
        //URLȨ�޿���
        $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

        $rows = $this->service->getInfo_d($_GET['id'], $perm);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '���ַ�Ʊ');
        } else {
            $this->assign('formTypeCN', '<span class="red">[���ַ�Ʊ]</span>');
        }
        if ($perm == 'view') {//�鿴ҳ��
            $this->assign('purSkey', $this->md5Row($rows['purcontId'], 'purchase_contract_purchasecontract'));
            $this->assign('invType', $this->getDataNameByCode($rows['invType']));
            $this->assign('purType', $this->getDataNameByCode($rows['purType']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($rows['sourceType']));

            $this->display('view');

        } else if ($perm == 'break') {//���ҳ��
            $this->assign('invTypeCN', $this->getDataNameByCode($rows['invType']));
            $this->assign('purTypeCN', $this->getDataNameByCode($rows['purType']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($rows['sourceType']));

            $this->display('break');

        } else {//�༭ҳ��

            // $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
            $otherDataDao = new model_common_otherdatas();
            $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
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
     * �ɹ���ͬ�б༭�ɹ���Ʊ
     */
    function c_initEditInPurCon()
    {
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

        $rows = $this->service->getInfo_d($_GET['id'], $perm);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '���ַ�Ʊ');
        } else {
            $this->assign('formTypeCN', '<span class="red">[���ַ�Ʊ]</span>');
        }


        //$this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rows['invType'], null, array('expand4No' => '1'));
        }

        $this->showDatadicts(array('purType' => 'cgfs'), $rows['purType']);

        $this->display('editinpurcon');
    }

    /**
     * ��ӡ
     */
    function c_toPrint()
    {
        //URLȨ�޿���
        $this->permCheck();
        $rows = $this->service->getInfo_d($_GET['id'], 'view');
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($rows['formType'] == 'blue') {
            $this->assign('formTypeCN', '');
        } else {
            $this->assign('formTypeCN', '<span class="red">[���ַ�Ʊ]</span>');
        }

        $this->assign('invType', $this->getDataNameByCode($rows['invType']));
        $this->assign('purType', $this->getDataNameByCode($rows['purType']));

        $this->display('print');
    }

    /**
     * ��д�༭����
     */
    function c_edit()
    {
        try {
            $object = $_POST[$this->objName];
            if ($this->service->edit_d($object)) {
                if ($_GET['act'] == 'audit') {
                    if ($this->service->audit_d($object['id'])) {
                        msgRf('��˳ɹ���');
                    } else {
                        msgRf('���ʧ�ܣ�');
                    }
                }
                msgRf('����ɹ���');
            } else {
                msgRf('����ʧ��!');
            }
        } catch (Exception $e) {
            msgRf('����ʧ�ܣ�ʧ��ԭ��' . $e->getMessage());
        }
    }

    /*
     * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_ajaxdeletes()
    {
        //$this->permDelCheck ();
        $id = $_POST['id'];
        if ($id * 1 != $id) {
            echo util_jsonUtil::iconvGB2UTF('��֧������ɾ����');
        } else {
            try {
                $rs = $this->service->find(array('id' => $id), null, 'ExaStatus');
                if (!empty($rs['ExaStatus'])) {
                    echo util_jsonUtil::iconvGB2UTF('�����Ѿ���ˣ����ܽ���ɾ������');
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
     *  ���
     */
    function c_break()
    {
        if ($this->service->break_d($_POST[$this->objName])) {
            msgRf('��ֳɹ���');
        } else {
            msgRf('���ʧ��!');
        }
    }

    /**
     * �ϲ�����
     */
    function c_merge()
    {
        echo $this->service->merge_d($_POST['id'], $_POST['belongId']) ? 1 : 0;
    }

    /**
     * �жϵ����Ƿ��Ѿ����
     */
    function c_isBreak()
    {
        echo $this->service->isBreak_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ���
     */
    function c_audit()
    {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �����
     */
    function c_unaudit()
    {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ����
     */
    function c_toHook()
    {
        $page = 'hook';
        $rs = $this->service->rtThisPeriod_d();
        $this->assign('sysYear', $rs['thisYear']);
        $this->assign('sysMonth', $rs['thisMonth']);

        /**
         * Ϊ�ʲ��ɹ�����¼��ķ�Ʊѡ������Ĺ���ҳ��
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
         * ԭ�ɹ���ƱĬ�ϴ�������
         */
        $supplier = $this->service->find(array('id' => $_GET['id']), null, 'supplierName,supplierId');
        $rows = $this->service->hookRows_d($_GET['id']);
        $this->assign('invList', $rows[0]);
        $this->assign('invCount', $rows[1]);

        /**
         * ����⹺��ⵥĬ�ϴ�������
         */
        $this->assign('supplierName', $supplier['supplierName']);
        $this->assign('supplierId', $supplier['supplierId']);
        $this->assign('invpurId', $_GET['id']);
        $this->display($page);
    }

    /**
     * ��ȡ�ɹ���ѡ��Json���� ID2209 2016-12-6
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
     * ��ʾ�ɹ���ѡ�� ID2209 2016-12-6
     * @param $invoiceIds
     * @return array
     */
    function showCardsToHook($invoiceIds)
    {
        $totalNum = 0;
        $productCodeStr = '';
        // ��ȡ��Ƭ����
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
                if (!in_array($v['detailId'], $detailIdArr)) {//������ͬ��������ͬ�����ϱ�ŵ������������ͳ�Ƴ�����ʱ������
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

        // �����Լ���Ӧ������ƴ��
        foreach ($productNoArr as $k => $v) {
            $productCodeStr .= $k . ':' . $v . ',';
        }

        $backArr['totalNum'] = $totalNum;
        $backArr['productCodeStr'] = rtrim($productCodeStr, ',');

        // ƴ�ӿ�Ƭ����html
        $str = '';
        $cardsArr = array();
        if (is_array($row) && !empty($row)) {
            // �ֽ⿨Ƭ����
            foreach ($row as $k => $v) {
                foreach ($v['detail'] as $dk => $dv) {
                    $check_sql = "select count(id) as num from oa_finance_assetscard_hookrecord where cardNo = '{$dk}' and productNo = '{$dv['productNo']}' and objCode = '{$dv['objCode']}'";
                    $hookedNum = $this->service->_db->getArray($check_sql);
                    if ($hookedNum[0]['num'] > 0) {// �ų����Ѿ������˵Ŀ�Ƭ
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

            // ƴ�ӿ�Ƭ����html
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
     * �ݹ����
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
     * ��Ʊ����ר���������
     */
    function c_pageJsonGrid()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $rows = $service->pageJsonGrid_d();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ʾ��Ӳɹ���Ʊҳ��
     */
    function c_instockHookPage()
    {
        $service = $this->service;
        $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * ��gird��ȡ�ɹ���Ʊ�嵥 ���� ��ⵥ��
     */
    function c_getItemList()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $list = $this->service->getEquList_d($id);
        echo util_jsonUtil::iconvGB2UTF($list);
    }

    /**
     * ��gird��ȡ�ɹ���Ʊ�嵥 ���� ��ⵥ��
     */
    function c_getItemListJson()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $list = $this->service->getEquListJson_d($id);
        echo util_jsonUtil::iconvGB2UTF($list);
    }

    /****************************���㲿��*******************************/

    /**
     * �⹺������ɹ��б�
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
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**************************�ɹ���Ʊ�б�***************************/

    /**
     * �ɹ���Ʊ�߼�����
     */
    function c_toSearch()
    {
        $this->showDatadicts(array('status' => 'CGFPZT'));
        $this->showDatadicts(array('invType' => 'FPLX'));
        $this->display('search');
    }

    /**************************���Ȩ��******************************/

    /**
     * �ɹ���Ʊ���Ȩ��
     */
    function c_hasLimitToAudit()
    {
        echo $this->service->this_limit['���'] ? 1 : 0;
    }

    /**
     * �ɹ���Ʊ���Ȩ��
     */
    function c_hasLimitToUnaudit()
    {
        echo $this->service->this_limit['�����'] ? 1 : 0;
    }

    /**
     * �ɹ���Ʊɾ��Ȩ��
     */
    function c_hasLimitToDelete()
    {
        echo $this->service->this_limit['ɾ��'] ? 1 : 0;
    }

    /**
     * �ɹ���Ʊ������Ȩ��
     */
    function c_hasLimitToUnHook()
    {
        echo $this->service->this_limit['������'] ? 1 : 0;
    }

    /**************************�����б�ҳ��***************************/
    /**
     * �����߲�ѯ�б�
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
     * �ɹ���Ʊ�߼�����
     */
    function c_toViewListSearch()
    {
        $this->showDatadicts(array('status' => 'CGFPZT'));
        $this->showDatadicts(array('invType' => 'FPLX'));
        $this->display('viewlist-search');
    }

    /**************************** �ɹ���Ʊ�����ݴ��� *********************/
    /**
     * �ɹ���Ʊ�����ݴ���
     */
    function c_toOldDataDeal()
    {
        $this->display('olddatadeal');
    }

    /**
     * ��ȡ�ظ�����
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
     * ��ȡ�ظ����� - ����
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
     * �ظ�������ʾ
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

    //����ҳ��
    function c_toDeal()
    {
        //��Ⱦ��Ʊ��Ϣ
        $obj = $this->service->getDetail($_GET['id']);
        $this->assign('dealInfo', $this->dealInfoShow($obj));

        //��Ⱦ�����Ϣ
        $stockInfo = $this->service->getStockInfo_d($obj);
        $this->assign('stockinfo', $this->stockInfoShow($stockInfo));

        //�����Ϣ id
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

    //������ʾ
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

    //��ⵥ��Ⱦ
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

    //������ - ��ʵ���Ǹ��� expand1
    function c_deal()
    {
        $rs = $this->service->deal_d($_POST[$this->objName]);
        if ($rs === true) {
            echo "<script>alert('���³ɹ�');self.parent.tb_remove();parent.show_page()</script>";
        } else {
            echo $rs;
        }
    }

    //����������Ϣ - �� Ψһ��Ӧ�ĵ���
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
     * ��Ʊ����ظ���֤
     */
    function c_ajaxCheck()
    {
        echo $this->service->find(array('objNo' => util_jsonUtil::iconvUTF2GB($_GET['objNo'])), null, 'id') ? 1 : 0;
    }
}