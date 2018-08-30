<?php

/**
 *
 * �ɹ�������Ʋ���
 * @author fengxw
 *
 */
class controller_asset_purchase_apply_apply extends controller_base_action
{
    function __construct()
    {
        $this->objName = "apply";
        $this->objPath = "asset_purchase_apply";
        parent::__construct();
    }

    /**
     * �����ƻ���ϸtab
     */
    function c_toViewTab()
    {
        $this->permCheck($_GET['applyId']); // ��ȫ����
        $this->assign('applyId', $_GET['applyId']);
        $this->display('viewtab');
    }

    /**
     * Ȩ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /*
     * ��ת���ɹ�����
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ת����ȷ�ϵĲɹ�����
     */
    function c_toConfirmProductList()
    {
        $this->view("confirm-list");
    }

    /**
     * ��ת��ȷ������ҳ��(�̶��ʲ�)
     */
    function c_toConfirmProduct()
    {
        // $this->permCheck ();//��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetUse', $this->getDataNameByCode($obj['assetUse']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $item = new model_asset_purchase_apply_applyItem ();
        $applyitem = $item->findAll(array(
            'applyId' => $obj['id'],
            'purchDept' => '1'
        ));
        $itemSum = 0;
        $itemIdArr = array();
        if (is_array($applyitem)) {
            foreach ($applyitem as $key => $val) {
                $itemSum = $itemSum + $val['applyAmount'];
                array_push($itemIdArr, $val['id']);
            }
        }
        $this->assign('list', $this->service->showConfirmEdit_s($applyitem));
        $this->assign('invnumber', count($applyitem));
        $this->assign('itemSum', $itemSum);
        $this->assign('itemIds', implode(",", $itemIdArr));
        $this->view("confirm");
    }

    /**
     * ription �����Ϸ���Ĳɹ�����
     */
    function c_myConfirmListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); // ����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['productSureUserId'] = $_SESSION['USER_ID'];
        // $service->searchArr['productSureStatus'] = 0;
        // $service->searchArr['ExaStatusArr'] = array("���","��������","����");

        $service->asc = true;

        // $rows = $service->pageBySqlId ("select_apply");
        $rows = $service->pageBySqlId("select_apply_all");
        // var_dump($service->searchArr['productSureUserId']);
        $rows = $this->sconfig->md5Rows($rows);
        // ���ýӿ�������齫��ȡһ��objAss��������
        /*
         * $interfObj = new model_common_interface_obj(); if(is_array($rows)){ foreach ( $rows as $key => $val ) { $rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows[$key]['state'] );	//״̬���� $rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//�������� } }
         */
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ription �ɹ������б�������Ȩ�ޣ�
     */
    function c_areaListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); // ����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->setCompany(0); // ���������Լ��Ĺ�˾���˹���

        // ��˾Ȩ�޻�ȡ
        $companyLimit = $this->service->this_limit['��˾Ȩ��'];
        if ($companyLimit) {
            $service->searchArr['deptCon'] = "sql: AND (createId = '" . $_SESSION['USER_ID'] .
                "' OR businessBelong IN(" . util_jsonUtil::strBuild($companyLimit) . "))";
        } else {
            $service->searchArr['createId'] = $_SESSION['USER_ID'];
        }

        $service->asc = true;
        $rows = $service->pageBySqlId("select_apply_all");
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
        // ��ȡ�����ֵ�
        $this->showDatadicts(array(
            'useType' => 'ZCYT'
        ));
        $this->showDatadicts(array(
            'purchaseType' => 'CGLX'
        ));
        $this->showDatadicts(array(
            'assetUse' => 'CGZL-FYFL'
        ));
        $this->showDatadicts(array(
            'assetClass' => 'CGFL'
        ));

        $this->assign('applyDetId', $_SESSION['DEPT_ID']);
        $this->assign('applicantId', $_SESSION['USER_ID']);
        $this->assign('applicantName', $_SESSION['USERNAME']);
        $deptDao = new model_deptuser_dept_dept ();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('applyDetName', $deptInfo['DEPT_NAME']);

        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);
        $this->assign('useDetId', $_SESSION['DEPT_ID']);
        $this->assign('useDetName', $deptInfo['DEPT_NAME']);

        $this->assign('applyTime', date("Y-m-d"));
        $this->view('add');
    }

    /**
     * ��ת������ҳ��
     */
    function c_toRequireAdd()
    {
        // ������֤
        $docItemDao = new model_asset_require_requireitem ();
        $docItemRs = $docItemDao->requireJsonApply_d($_GET['requireId']);
        if (empty ($docItemRs)) {
            msgRf('�ɹ�������������ϣ����ܼ�������');
            exit ();
        }
        $this->showDatadicts(array(
            'useType' => 'ZCYT'
        ));
        $docDao = new model_asset_require_requirement ();
        $requireObj = $docDao->get_d($_GET['requireId']);
        $requireUrl = $this->service->viewRelInfo($_GET['requireId']);
        $requirCodeURL = $requireObj['requireCode'] . $requireUrl;
        // echo "<pre>";
        // print_R($requireObj);
        $this->assign('requireCode', $requireObj['requireCode']);
        $this->assign('requireCodeURL', $requirCodeURL);
        $this->assign('requireId', $requireObj['id']);
        // ��ȡ�����ֵ�
        $this->showDatadicts(array(
            'purchaseType' => 'CGLX'
        ));
        $this->showDatadicts(array(
            'assetUse' => 'CGZL-FYFL'
        ));
        $this->showDatadicts(array(
            'assetClass' => 'CGFL'
        ));

        $this->assign('applyDetId', $_SESSION['DEPT_ID']);
        $this->assign('applicantId', $_SESSION['USER_ID']);
        $this->assign('applicantName', $_SESSION['USERNAME']);
        $deptDao = new model_deptuser_dept_dept ();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('applyDetName', $deptInfo['DEPT_NAME']);

        $this->assign('userTel', $requireObj['userPhone']);
        $this->assign('userId', $requireObj['userId']);
        $this->assign('userName', $requireObj['userName']);
        $this->assign('useDetId', $requireObj['userDeptId']);
        $this->assign('useDetName', $requireObj['userDeptName']);
        $this->assign('userCompanyCode', $requireObj['userCompanyCode']);
        $this->assign('userCompanyName', $requireObj['userCompanyName']);
        $this->assign('recognizeAmount', $requireObj['recognizeAmount']);
        $this->assign('address', $requireObj['address']);

        $this->assign('applyTime', date("Y-m-d"));
        $this->view('require-add', true);
    }

    /**
     * ��OA�´�ɹ�����չʾ����
     */
    function c_toRequireAddNew()
    {
        $requireId = $_GET['requireId'];
        $companyId = isset($_GET['companyId']) ? $_GET['companyId'] : '';
        $totalmanagerid = isset($_GET['totalmanagerid']) ? $_GET['totalmanagerid'] : '';// ���ʲ�����ԱID
        $branchDao = new model_deptuser_branch_branch();
        $branch = $branchDao->getBranchName_d($companyId);
        $company = $branch['NameCN'];

        // ����aws
        // ��aws��ȡ�ʲ�������������
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $requireId
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);

        // ��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        // �������ݴ���
        $data = $requireInfo['data']['requireInfo'];
        $this->assign('requireId', $requireId);
        $this->assign('requireCode', $data['num']);
        $this->assign('userId', $data['theuserid']);
        $this->assign('userName', $data['theuser']);
        $this->assign('useDetId', $data['udepartmentId']);
        $this->assign('useDetName', $data['udepartment']);
        $this->assign('userTel', $data['userTel']);
        $this->assign('applicantId', $data['applicantid']);
        $this->assign('applicantName', $data['applicantName']);
        $this->assign('applyDetId', $data['applydetid']);
        $this->assign('applyDetName', $data['applyDetName']);
        $this->assign('applyTime', date("Y-m-d"));
        $this->assign('amounts', $data['money']);
        $this->assign('recognizeAmount', $data['confirmmoney']);
        $this->assign('address', $data['address']);
        $this->assign('remark', $data['remark']);
        $this->assign('requireId', $requireId);
        $this->assign('companyId', $companyId);
        $this->assign('companyName', $company);
        $this->assign('totalmanagerid', $totalmanagerid);

        // ���������������ֿ����Ϣ
        $warehouseId = (isset($data['storageId']) && $data['storageId'])? $data['storageId'] : '';
        $warehouseName = (isset($data['storagename']) && $data['storagename'])? $data['storagename'] : '';
        $this->assign('warehouseId', $warehouseId);
        $this->assign('warehouseName', $warehouseName);
        $storageManagerId = (isset($data['storageManagerId']) && $data['storageManagerId'])? $data['storageManagerId'] : '';
        $storageManagerName = (isset($data['storageManagerName']) && $data['storageManagerName'])? $data['storageManagerName'] : '';
        $this->assign('storageManagerId', $storageManagerId);
        $this->assign('storageManagerName', $storageManagerName);
        $this->showDatadicts(array(
            'useType' => 'ZCYT'
        ), $data['usetype']); // �ʲ���;
        $this->view('require-add-new', true);
    }

    function c_getRequireDetail()
    {
        $requireDetails = array();
        // ��aws��ȡ�ʲ�������������
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $_POST['requireId']
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);
        // �ʲ�����������ϸ
        if (!empty ($requireInfo['data']['details'])) {
            $requireDetails = array();
            foreach ($requireInfo['data']['details'] as $k => $v) {
                $v['detailId'] = $k;
                array_push($requireDetails, $v);
            }
        }
        echo util_jsonUtil::encode($requireDetails);
    }

    function c_getPurchaseDetail()
    {
        $purchaseDetails = array();
        // ��aws��ȡ�ʲ�������������
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $_POST['requireId']
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);
        // �ʲ�����������ϸ
        if (!empty ($requireInfo['data']['details'])) {
            $requireDetails = array();
            foreach ($requireInfo['data']['details'] as $k => $v) {
                $v['detailId'] = $k;
                $v['pattem'] = $v['pattern'];
                array_push($requireDetails, $v);
            }

            // �����´�ɹ���ϸ
            if (!empty ($requireDetails)) {
                $applyItemDao = new model_asset_purchase_apply_applyItem (); // �ɹ�������ϸʵ����
                foreach ($requireDetails as $key => $val) {
                    $applyedNum = $applyItemDao->getApplyedNum_d($val['detailId']);
                    if ($applyedNum >= $val['amount']) {
                        unset ($requireDetails[$key]);
                    } else {
                        $requireDetails[$key]['maxAmount'] = $requireDetails[$key]['applyAmount'] = $val['amount'] - $applyedNum;
                        array_push($purchaseDetails, $requireDetails[$key]);
                    }
                }
            }
        }
        echo util_jsonUtil::encode($purchaseDetails);
    }

    /**
     * ȷ�ϲɹ���������
     * add by chengl 2012-04-07
     *
     * @param
     *            tags
     * @return return_type
     */
    function c_confirmProduct()
    {
        $object = $this->service->confirmProduct_d($_POST['apply']);
        if ($object) {
            msgGo('ȷ�ϳɹ�', 'index1.php?model=purchase_plan_basic&action=toConfirmProductList');
        } else {
            msgGo('ȷ��ʧ��', 'index1.php?model=purchase_plan_basic&action=toConfirmProductList');
        }
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET['perm']) && ($_GET['perm'] == 'view' || $_GET['perm'] == 'viewaudit')) {
            if (isset ($_GET['viewBtn'])) {
                $this->assign('showBtn', 1);
            } else {
                $this->assign('showBtn', 0);
            }
            // $this -> assign('purchaseType', $this -> getDataNameByCode($obj['purchaseType']));
            // $this -> assign('purchCategory', $this -> getDataNameByCode($obj['purchCategory']));
            $this->assign('assetUse', $this->getDataNameByCode($obj['assetUseCode']));
            // $this -> assign('assetClass', $this -> getDataNameByCode($obj['assetClass']));

            if ($obj['purchCategory'] == "CGZL-YFL") {
                $this->view('rd-view');
            } else {
                if ($_GET['perm'] == 'viewaudit') {
                    $this->view('viewaudit');
                } else {
                    $this->view('view');
                }
            }
        } else if (isset ($_GET['perm']) && $_GET['perm'] == 'toConfirm') {
            $this->view('confirmApply');
        } else {
            $this->showDatadicts(array(
                'purchaseType' => 'CGLX'
            ), $obj['purchaseType']);
            $this->showDatadicts(array(
                'assetUse' => 'ZCYT'
            ), $obj['assetUseCode']);
            $this->view('edit');
        }
    }

    /**
     * ��д��ȡ��ҳ����ת��Json����
     */
    function c_pageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        if (isset($_REQUEST['isSetMyList']) && $_REQUEST['isSetMyList'] === 'true') {
            $service->_isSetCompany = $service->_isSetMyList;
        }
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת�����˲ɹ�������Ϣ�б�
     */
    function c_pageMyList()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('list-myAp');
    }

    /**
     * ��ת�����˲ɹ�������Ϣ�б�
     */
    function c_pageConfirmList()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('list-noConfirmAp');
    }

    /**
     * ��ת���ɹ�������Ϣ�б� -- �����ʲ�����Ĳɹ��б�ҳ
     */
    function c_listByRequire()
    {
        $this->assign('requireId', $_GET['requireId']);
        $this->view('listbyrequire');
    }

    /**
     * ��ת���ɹ������б�
     */
    function c_toRequireList()
    {
        $this->view('requirement-list');
    }

    /**
     * ��ת���ɹ�ѯ��ҳ��
     */
    function c_initRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
            $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
            $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
            $this->view('require-view');
        } else {
            $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
            $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
            $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
            $this->view('require');
        }
    }

    /**
     * ��ת�������ɹ��鿴ҳ��
     */
    function c_purchView()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('purch-view');
    }

    /**
     * ��ת�������ɹ��ر�ҳ��
     */
    function c_toClose()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('close');
    }

    /**
     * �رղɹ����루�����ɹ���
     */
    function c_close()
    {
        if ($this->service->dealClose_d($_POST['apply'])) {
            msg("�رճɹ�");
        } else {
            msg("�ر�ʧ��");
        }
    }

    /**
     * ��ת������ɹ�ҳ��
     */
    function c_toAssignPurchaser()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('assign-purchaser');
    }

    /**
     * ��ת����������ֲɹ�ҳ��
     */
    function c_initAssign()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetUse', $this->getDataNameByCode($obj['assetUse']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('assign');
    }

    /**
     * ��ת����������ֲɹ�ҳ��
     */
    function c_initDelAssign()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetUse', $this->getDataNameByCode($obj['assetUse']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('del-assign');
    }

    /**
     * ��ת���������ɹ������б�
     */
    function c_toAdminReqList()
    {
        $this->view('administration-list');
    }

    /**
     * ��ת���������ɹ������б�
     */
    function c_toDeliReqList()
    {
        $this->view('delivery-list');
    }

    /**
     * ��ȡ��������ҳ����ת��Json
     */
    function c_deliJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        // $service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $condition = 'sql: and (select count(0) from oa_asset_purchase_apply_item ai where ai.applyId=c.id and ai.purchDept=1 )>0 ';
        $service->searchArr['deptCon'] = $condition;
        // $service->asc = false;
        $rows = $service->pageBySqlId("select_apply_all");
        // ���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        // count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת���������ɹ�����鿴ҳ��
     */
    function c_initDeliRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetUse', $this->getDataNameByCode($obj['assetUse']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('delivery-view');
    }

    /**
     * ��ת�����������ɹ�����鿴ҳ��
     */
    function c_initAdminRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('purchaseType', $this->getDataNameByCode($obj['purchaseType']));
        $this->assign('purchCategory', $this->getDataNameByCode($obj['purchCategory']));
        $this->assign('assetUse', $this->getDataNameByCode($obj['assetUse']));
        $this->assign('assetClass', $this->getDataNameByCode($obj['assetClass']));
        $this->view('administration-view');
    }

    /**
     * ��ɾ�ӱ���Ϣ����ɾ������Ϣ
     */
    function c_deletes()
    {
        $message = "";
        try {
            $Obj = $this->service->get_d($_GET['id']);
            $itemDao = new model_asset_purchase_apply_applyItem ();
            $condition = array(
                'applyId' => $Obj['id']
            );
            $itemDao->delete($condition);
            $this->service->deletes_d($_GET['id']);
            $message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';
        } catch (Exception $e) {
            $message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
        }
        if (isset ($_GET['url'])) {
            $event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
            showmsg($message, $event, 'button');
        } else if (isset ($_SERVER[HTTP_REFERER])) {
            $event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
            showmsg($message, $event, 'button');
        } else {
            $this->c_page();
        }
        msg('ɾ���ɹ���');
    }

    /**
     * �ı䵥��״̬
     */
    function c_submit()
    {
        try {
            $id = isset ($_GET['id']) ? $_GET['id'] : false;
            $object = array(
                "id" => $id,
                "state" => "���ύ"
            );
            $this->service->updateById($object);
            echo 1;
        } catch (Exception $e) {
            throw $e;
            echo 0;
        }
    }

    /**
     * �����ɹ�����,״̬Ϊδȷ�� PMS 2556
     *
     * @param bool $isAddInfo
     */
    function c_addBeforeConfirm($isAddInfo = false)
    {
        $this->checkSubmit(); // �����Ƿ��ظ��ύ
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        if ($actType == 'submit') {
            $object['state'] = 'δȷ��';
            $object['ExaStatus'] = '���ύ';
        }

        $id = $this->service->add_d($object, $isAddInfo);

        if ($id) {
            // ͳһ������ϸ��Ĳɹ����ű����ֶ���Ϣ ������´�ɹ������,��ϸ��Ĳɹ�������Ϣ��ȱ�����⣩
            $updateSql = "update oa_asset_purchase_apply_item i left join oa_asset_purchase_apply c on c.id = i.applyId set i.purchDept = c.purchaseDept where c.id = '{$id}';";
            $this->service->query($updateSql);

            // ����ƽ̨״̬
            $result = util_curlUtil::getDataFromAWS('asset', 'updateOperationStatus', array(
                "requireId" => $object['relDocId']
            ));

            if ($actType == 'submit') {
                echo "<script>alert('�ύ�ɹ�,�����ʲ�����Աȷ��!');window.close(); </script>";
            } else {
                echo "<script>alert('����ɹ�!');window.close(); </script>";
            }
        } else {
            if ($actType == 'submit') {
                msgRf('�ύʧ��!');
            } else {
                msgRf('����ʧ��!');
            }
        }
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit(); // �����Ƿ��ظ��ύ
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST[$this->objName];

        // ѡ�񽻸���������������ֱ�Ӹ�������״̬
        if ($actType == "noaudit") {
            $object['state'] = '���ύ';
            $object['ExaStatus'] = '���';
            $object['ExaDT'] = date("Y-m-d");
        }

        $id = $this->service->add_d($object, $isAddInfo);
        if ($id) {
            // ͳһ������ϸ��Ĳɹ����ű����ֶ���Ϣ ������´�ɹ������,��ϸ��Ĳɹ�������Ϣ��ȱ�����⣩
            $updateSql = "update oa_asset_purchase_apply_item i left join oa_asset_purchase_apply c on c.id = i.applyId set i.purchDept = c.purchaseDept where c.id = '{$id}';";
            $this->service->query($updateSql);

            // ����ƽ̨״̬
            $result = util_curlUtil::getDataFromAWS('asset', 'updateOperationStatus', array(
                "requireId" => $object['relDocId']
            ));
            if ("audit" == $actType) {
                succ_show('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $object['useDetId'] . '&flowMoney=' . $object['amounts']);
            } else if ($actType == "noaudit") { // ѡ�񽻸�����ȥ���������� ID2062
                $object['id'] = $id;
                $this->c_noAuditSendEmail($id);
                msgRf('�ύ�ɹ�!');
            } else {
                msgRf('����ɹ�!');
            }
        } else {
            if ("audit" == $actType) {
                msgRf('�ύʧ��!');
            } else {
                msgRf('����ʧ��!');
            }
        }
    }

    /**
     * �����������
     */
    function c_addchecker($isAddInfo = false)
    {
        // echo $_POST[$this->objName]['formCode'];
        // var_dump($_POST);
        $id = $this->service->update("id='" . $_POST[$this->objName]['id'] . "'", array(
            'productSureUserId' => $_POST[$this->objName]['productSureUserId'],
            'productSureUserName' => $_POST[$this->objName]['productSureUserName']
        ));

        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        if ($id) {
            if ("audit" == $actType) {
                succ_show('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id);
            } else {
                echo "<script>alert('������Ա�ɹ�!');history.back(-1);</script>";
            }
        } else {
            if ("audit" == $actType) {
                echo "<script>alert('��������ʧ��!');history.back(-1);</script>";
            } else {
                echo "<script>alert('����ʧ��!');history.back(-1);</script>";
            }
        }
    }

    /**
     * ȷ�ϲɹ����Ϸ�����
     *
     * @param
     *            tags
     * @return return_type
     */
    function c_confirmProductUser()
    {
        $object = $this->service->confirmProductUser_d($_POST[$this->objName]);
        if ($object) {
            msgGo('�´�ɹ�', 'index1.php?model=purchase_plan_basic&action=toTabList');
        } else {
            msgGo('�´�ʧ��', 'index1.php?model=purchase_plan_basic&action=toTabList');
        }
    }

    /**
     * ����ȷ�ϴ�ظ�������
     * add by chengl 2012-04-07
     *
     * @param
     *            tags
     * @return return_type
     */
    function c_backBasicToApplyUser()
    {
        // var_dump($_POST['apply']);
        // var_dump($_POST);
        $object = $this->service->backBasicToApplyUser_d($_POST['apply']);
        if ($object) {
            msgGo('��سɹ�', 'index1.php?model=asset_purchase_apply_apply&action=toDeliReqList');
        } else {
            msgGo('���ʧ��', 'index1.php?model=asset_purchase_apply_apply&action=toDeliReqList');
        }
    }

    /**
     * ����ɹ�����
     * add by chengl 2012-04-07
     *
     * @param
     *            tags
     * @return return_type
     */
    function c_assignPurchaser()
    {
        $object = $this->service->assignPurchaser_d($_POST['apply']);
        if ($object) {
            msg('����ɹ�!');
        } else {
            msg('���ʧ��');
        }
    }
    /**
     * �鿴����
     */
    /*
     * function c_read() { //$this->permCheck ();//��ȫУ�� $id = isset ( $_GET['id'] ) ? $_GET['id'] : exit (); $purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null; $readType=isset($_GET['actType'])?$_GET['actType']:null; $plan = $this->service->get_d ( $id ); //��ȡ���ϵ�ִ����� $equipmentDao = new model_purchase_plan_equipment (); $executRows=$equipmentDao->getEquExecute_d($id); $this->assign ( 'listExecute', $this->service->showExecute_s ($executRows)); $this->assign ( 'listEquExecute', $this->service->showEquExecuteList_s ( $plan["childArr"] )); // $purchType=$plan['purchType']; $this->assign ( 'readType',$readType ); foreach ( $plan as $key => $val ) { $this->assign ( $key, $val ); } //$this->assign ( 'list', $this->service->showRead_s ( $plan["childArr"] ) ); //$this->display ( 'apply-task-give' ); $this->assign ( 'list', $this->service->showAssetRead_s ( $plan["childArr"] ) ); $this->display ('assets-view' ); }
     */
    function c_toConfirmUser()
    {
        $this->permCheck(); // ��ȫУ��
        $id = isset ($_GET['id']) ? $_GET['id'] : null;

        $plan = $this->service->getPlan_d($id);
        if ($plan['isPlan'] == 1) {
            $this->assign('isPlanYes', 'checked');
        } else {
            $this->assign('isPlanNo', 'checked');
        }
        foreach ($plan as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('invnumber', count($plan["childArr"]));
        $this->assign('list', $this->service->showConfirmAssetRead_s($plan["childArr"]));
        $this->display('assets-view');
    }

    /**
     * �༭�ɹ�����,״̬Ϊδȷ�� PMS 2556
     */
    function c_editBeforeConfirm()
    {
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        if ($actType == 'submit') {
            $object['state'] = 'δȷ��';
            $object['ExaStatus'] = '���ύ';
        }
        $id = $this->service->edit_d($object, true);

        if ($id) {
            if ($actType == 'submit') {
                echo "<script>alert('�ύ�ɹ�,�����ʲ�����Աȷ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        } else {
            if ($actType == 'submit') {
                echo "<script>alert('�ύʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }
    }

    /**
     * �б��ύ�ɹ�����,״̬Ϊδȷ�� PMS 2978
     */
    function c_ajaxSubmitConfirm()
    {
        $object['id'] = isset($_GET['Id'])? $_GET['Id'] : '';
        $object['state'] = 'δȷ��';
        $object['ExaStatus'] = '���ύ';

        $object = $this->service->addUpdateInfo($object);

        $id = (empty($object['id']))? false : $this->service->updateById($object);

        if ($id) {
            echo "<script>alert('�ύ�ɹ�,�����ʲ�����Աȷ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('�ύʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * �޸Ķ������
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        // ѡ�񽻸���������������ֱ�Ӹ�������״̬
        if ($actType == "noaudit") {
            $object['state'] = '���ύ';
            $object['ExaStatus'] = '���';
            $object['ExaDT'] = date("Y-m-d");
        }

        $id = $this->service->edit_d($object, true);
        if ($id) {
            if ("audit" == $actType) {
                succ_show('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $object['useDetId'] . '&flowMoney=' . $object['amounts']);
            } else if ($actType == "noaudit") { // ѡ�񽻸�����ȥ���������� ID2062
                $object['id'] = $id;
                $this->c_noAuditSendEmail($id);
                msgRf('�ύ�ɹ�!');
            } else {
                echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        } else {
            if ("audit" == $actType) {
                echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }
    }

    /**
     * ��ֲɹ�
     */
    function c_assign()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->edit_d($object, true);
        $asignType = isset ($_GET['asignType']) ? $_GET['asignType'] : null;
        // �ж��Ƿ�����������ֲɹ�
        if ($id) {
            if ("audit" == $asignType) {
                $this->service->sendEmail_d($id);
                // �����ʼ���������
            }
            echo "<script>alert('��ֳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('���ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * �ʲ��ɹ����벻����������
     * @author haojin
     * 2016-9-23
     */
    function c_noAuditSendEmail($id)
    {
        $service = $this->service;
        $object = $service->get_d($id);
        // ��ȡ�ɹ���ϸ
        $applyItemDao = new model_asset_purchase_apply_applyItem ();
        $applyItemArr = $applyItemDao->getItem_d($object['id']);

        // ����aws
        // 1.�ı��������뵥״̬,��Ϊ���ɹ��С�
        $result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
            'requireId' => $object['relDocId'],
            'applyStatus' => '1040'
        ));

        // 2.�����ʲ�����ӱ�ɹ�������Ϣ
        if ($result) {
            $details = array();
            foreach ($applyItemArr as $key => $val) {
                array_push($details, array(
                    "detailId" => $val['requireItemId'],
                    "purchAmount" => $val['applyAmount']
                ));
            }
            $result = util_curlUtil::getDataFromAWS('asset', 'updateDetailByPurchase', $details);
            if ($result) {
                // �����ʼ�֪ͨ�ɹ�Ա
                $service->sendMailAtAdd($object);
            }
        }
    }

    /**
     * �ʲ��ɹ���������ͨ�������ʼ�
     * author can
     * 2011-6-16
     */
    function c_auditSendEmail()
    {
        if (!empty ($_GET['spid'])) {
            $service = $this->service;
            $otherdatas = new model_common_otherdatas ();
            $folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $object = $service->get_d($objId);
                if ($object['ExaStatus'] == "���") {
                    if($object['purchaseDept'] == 1){// �����ɹ�
                        if ($objId != '') {
                            $obj['id'] = $objId;
                            $obj['state'] = '���ύ';
                            $obj['ExaStatus'] = '���';
                            $obj['ExaDT'] = date("Y-m-d");
                            $result = ($this->service->updateById($obj)) ? 'ok' : 'fail';

                            // ��������ֱ�����Ʋɹ�ģ��
                            if($result == 'ok'){
                                $this->c_noAuditSendEmail($objId);
                            }
                        }

                    }else{// �����ɹ�
                        // ��ȡ�ɹ���ϸ
                        $applyItemDao = new model_asset_purchase_apply_applyItem ();
                        $applyItemArr = $applyItemDao->getItem_d($object['id']);
                        // ����aws
                        // 1.�ı��������뵥״̬,��Ϊ���ɹ��С�
                        $result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
                            'requireId' => $object['relDocId'],
                            'applyStatus' => '1040'
                        ));
                        // $requirement = new model_asset_require_requirement();
                        // $requirement->updateRecognize($object['relDocId']);
                        // 2.�����ʲ�����ӱ�ɹ�������Ϣ
                        if ($result) {
                            // $requireitemDao = new model_asset_require_requireitem();
                            $details = array();
                            foreach ($applyItemArr as $key => $val) {
                                array_push($details, array(
                                    "detailId" => $val['requireItemId'],
                                    "purchAmount" => $val['applyAmount']
                                ));
                                // $obj = array(mainId=>$object['relDocId'],purchDept=>$object['purchaseDept'],purchAmount=>$val['applyAmount'],productId=>$val['productId']);
                                // $requireitemDao->updatePurchInfo($obj);
                            }
                            $result = util_curlUtil::getDataFromAWS('asset', 'updateDetailByPurchase', $details);
                            if ($result) {
                                // �����ʼ�֪ͨ�ɹ�Ա
                                $service->sendMailAtAdd($object);
                            }
                        }
                    }
                }
            }
        }
        echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * �ɹ�ѯ��
     */
    function c_inquire()
    {
        if ($this->service->inquire_edit_d($_POST[$this->objName], true)) {
            echo "<script>alert('����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * ��ת���з�����ҳ��
     */
    function c_toRDAdd()
    {
        $this->assign('applyDetId', $_SESSION['DEPT_ID']);
        $this->assign('applicantId', $_SESSION['USER_ID']);
        $this->assign('applicantName', $_SESSION['USERNAME']);
        $deptDao = new model_deptuser_dept_dept ();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('applyDetName', $deptInfo['DEPT_NAME']);

        $this->assign('applyTime', date("Y-m-d"));
        $this->view('rd-add');
    }

    /**
     * ��ת���з��ɹ��б�
     */
    function c_toRDList()
    {
        $this->view('rd-list');
    }

    /**
     * ��ʼ������
     */
    function c_RDinit()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            if (isset ($_GET['viewBtn'])) {
                $this->assign('showBtn', 1);
            } else {
                $this->assign('showBtn', 0);
            }
            $this->view('rd-view');
        } else {
            $this->view('rd-edit');
        }
    }

    /**
     * ��ת���ɹ������ʲ�ȷ���б�
     */
    function c_toConfirmList()
    {
        $this->view('confirm-list');
    }

    /**
     * �鿴����
     */
    function c_toRead()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ת���ɹ������ʲ�ȷ��ҳ��
     */
    function c_toConfirm()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('rd-confirm');
    }

    /**
     * ��ת���ɹ������ʲ�ȷ���б� -- tab
     */
    function c_toConfirmTab()
    {
        $this->view('confirm-tab');
    }

    /**
     * ************** ���ز��� ***************
     */
    /**
     * �ж��Ƿ������������
     */
    function c_canBackForm()
    {
        // ֱ�ӷ��ؿɳ�������
        echo $this->service->canBackForm_d($_POST['id']);
    }

    /**
     * ��������
     */
    function c_backForm()
    {
        if ($this->service->backForm_d($_POST['id'])) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ���ϳ���
     */
    function c_toBackDetail()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('backdetail');
    }

    /**
     * ���ϳ���
     */
    function c_backDetail()
    {
        if ($this->service->backDetail_d($_POST[$this->objName])) {
            msg('���سɹ�');
        }
    }

    /**
     * ȷ���ʲ��ɹ����� created by PMS2556
     */
    function c_confirmApply()
    {
        $applyId = isset($_POST['id']) ? $_POST['id'] : '';
        $result = array();
        $applyArr = $this->service->find(array('id' => $applyId), null, 'purchaseDept');
        if ($applyId != '') {
            $obj['id'] = $applyId;
            $obj['state'] = '��ȷ��';
            if (isset($applyArr['purchaseDept'])) {
                if ($applyArr['purchaseDept'] == '1') {
                    $obj['ExaStatus'] = '���';
                    $obj['ExaDT'] = date("Y-m-d");
                }
                $result['result'] = ($this->service->updateById($obj)) ? 'ok' : 'fail';

                if ($applyArr['purchaseDept'] == '1') {// ��������ֱ�����Ʋɹ�ģ��
                    $this->c_noAuditSendEmail($applyId);
                }
            }
        } else {
            $result['result'] = 'fail';
        }
        echo json_encode($result);
    }

    /**
     * ����ʲ��ɹ����� created by PMS2556
     */
    function c_dispassApply()
    {
        $applyId = isset($_POST['id']) ? $_POST['id'] : '';
        $result = array();
        if ($applyId != '') {
            $obj['id'] = $applyId;
            $obj['state'] = '���';
            $obj['ExaStatus'] = '���ύ';
            $obj['ExaDT'] = '';

            $result['result'] = ($this->service->updateById($obj)) ? 'ok' : 'fail';
        } else {
            $result['result'] = 'fail';
        }
        echo json_encode($result);
    }
}