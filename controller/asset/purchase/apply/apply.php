<?php

/**
 *
 * 采购申请控制层类
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
     * 发货计划详细tab
     */
    function c_toViewTab()
    {
        $this->permCheck($_GET['applyId']); // 安全检验
        $this->assign('applyId', $_GET['applyId']);
        $this->display('viewtab');
    }

    /**
     * 权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /*
     * 跳转到采购申请
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 跳转到待确认的采购申请
     */
    function c_toConfirmProductList()
    {
        $this->view("confirm-list");
    }

    /**
     * 跳转到确认物料页面(固定资产)
     */
    function c_toConfirmProduct()
    {
        // $this->permCheck ();//安全校验
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
     * ription 待物料分配的采购申请
     */
    function c_myConfirmListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); // 设置前台获取的参数信息
        $service->searchArr['productSureUserId'] = $_SESSION['USER_ID'];
        // $service->searchArr['productSureStatus'] = 0;
        // $service->searchArr['ExaStatusArr'] = array("完成","部门审批","物料");

        $service->asc = true;

        // $rows = $service->pageBySqlId ("select_apply");
        $rows = $service->pageBySqlId("select_apply_all");
        // var_dump($service->searchArr['productSureUserId']);
        $rows = $this->sconfig->md5Rows($rows);
        // 调用接口组合数组将获取一个objAss的子数组
        /*
         * $interfObj = new model_common_interface_obj(); if(is_array($rows)){ foreach ( $rows as $key => $val ) { $rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows[$key]['state'] );	//状态中文 $rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称 } }
         */
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ription 采购申请列表（含区域权限）
     */
    function c_areaListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); // 设置前台获取的参数信息
        $service->setCompany(0); // 这里启用自己的公司过滤规则

        // 公司权限获取
        $companyLimit = $this->service->this_limit['公司权限'];
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
     * 跳转到新增页面
     */
    function c_toAdd()
    {
        // 读取数据字典
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
     * 跳转到新增页面
     */
    function c_toRequireAdd()
    {
        // 加入验证
        $docItemDao = new model_asset_require_requireitem ();
        $docItemRs = $docItemDao->requireJsonApply_d($_GET['requireId']);
        if (empty ($docItemRs)) {
            msgRf('采购数量已申请完毕，不能继续申请');
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
        // 读取数据字典
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
     * 新OA下达采购申请展示界面
     */
    function c_toRequireAddNew()
    {
        $requireId = $_GET['requireId'];
        $companyId = isset($_GET['companyId']) ? $_GET['companyId'] : '';
        $totalmanagerid = isset($_GET['totalmanagerid']) ? $_GET['totalmanagerid'] : '';// 总资产管理员ID
        $branchDao = new model_deptuser_branch_branch();
        $branch = $branchDao->getBranchName_d($companyId);
        $company = $branch['NameCN'];

        // 接入aws
        // 从aws获取资产需求申请数据
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $requireId
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);

        // 获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        // 主表数据处理
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

        // 关联需求申请借出仓库的信息
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
        ), $data['usetype']); // 资产用途
        $this->view('require-add-new', true);
    }

    function c_getRequireDetail()
    {
        $requireDetails = array();
        // 从aws获取资产需求申请数据
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $_POST['requireId']
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);
        // 资产需求申请明细
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
        // 从aws获取资产需求申请数据
        $result = util_curlUtil::getDataFromAWS('asset', 'getRequireInfo', array(
            "requireId" => $_POST['requireId']
        ));
        $requireInfo = util_jsonUtil::decode($result['data'], true);
        // 资产需求申请明细
        if (!empty ($requireInfo['data']['details'])) {
            $requireDetails = array();
            foreach ($requireInfo['data']['details'] as $k => $v) {
                $v['detailId'] = $k;
                $v['pattem'] = $v['pattern'];
                array_push($requireDetails, $v);
            }

            // 本次下达采购明细
            if (!empty ($requireDetails)) {
                $applyItemDao = new model_asset_purchase_apply_applyItem (); // 采购申请明细实例化
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
     * 确认采购申请物料
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
            msgGo('确认成功', 'index1.php?model=purchase_plan_basic&action=toConfirmProductList');
        } else {
            msgGo('确认失败', 'index1.php?model=purchase_plan_basic&action=toConfirmProductList');
        }
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 重写获取分页数据转成Json方法
     */
    function c_pageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        if (isset($_REQUEST['isSetMyList']) && $_REQUEST['isSetMyList'] === 'true') {
            $service->_isSetCompany = $service->_isSetMyList;
        }
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到个人采购申请信息列表
     */
    function c_pageMyList()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('list-myAp');
    }

    /**
     * 跳转到个人采购申请信息列表
     */
    function c_pageConfirmList()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('list-noConfirmAp');
    }

    /**
     * 跳转到采购申请信息列表 -- 关联资产需求的采购列表页
     */
    function c_listByRequire()
    {
        $this->assign('requireId', $_GET['requireId']);
        $this->view('listbyrequire');
    }

    /**
     * 跳转到采购需求列表
     */
    function c_toRequireList()
    {
        $this->view('requirement-list');
    }

    /**
     * 跳转到采购询价页面
     */
    function c_initRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到交付采购查看页面
     */
    function c_purchView()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到交付采购关闭页面
     */
    function c_toClose()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 关闭采购申请（交付采购）
     */
    function c_close()
    {
        if ($this->service->dealClose_d($_POST['apply'])) {
            msg("关闭成功");
        } else {
            msg("关闭失败");
        }
    }

    /**
     * 跳转到分配采购页面
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
     * 跳转到行政部拆分采购页面
     */
    function c_initAssign()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到交付部拆分采购页面
     */
    function c_initDelAssign()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到行政部采购需求列表
     */
    function c_toAdminReqList()
    {
        $this->view('administration-list');
    }

    /**
     * 跳转到交付部采购需求列表
     */
    function c_toDeliReqList()
    {
        $this->view('delivery-list');
    }

    /**
     * 获取交付部分页数据转成Json
     */
    function c_deliJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        // $service->getParam ( $_POST ); //设置前台获取的参数信息

        $condition = 'sql: and (select count(0) from oa_asset_purchase_apply_item ai where ai.applyId=c.id and ai.purchDept=1 )>0 ';
        $service->searchArr['deptCon'] = $condition;
        // $service->asc = false;
        $rows = $service->pageBySqlId("select_apply_all");
        // 数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        // count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到交付部采购需求查看页面
     */
    function c_initDeliRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到行政部部采购需求查看页面
     */
    function c_initAdminRequire()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 先删从表信息，再删主表信息
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
            $message = '<div style="color:red" align="center">删除成功!</div>';
        } catch (Exception $e) {
            $message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
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
        msg('删除成功！');
    }

    /**
     * 改变单据状态
     */
    function c_submit()
    {
        try {
            $id = isset ($_GET['id']) ? $_GET['id'] : false;
            $object = array(
                "id" => $id,
                "state" => "已提交"
            );
            $this->service->updateById($object);
            echo 1;
        } catch (Exception $e) {
            throw $e;
            echo 0;
        }
    }

    /**
     * 新增采购申请,状态为未确认 PMS 2556
     *
     * @param bool $isAddInfo
     */
    function c_addBeforeConfirm($isAddInfo = false)
    {
        $this->checkSubmit(); // 检验是否重复提交
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        if ($actType == 'submit') {
            $object['state'] = '未确认';
            $object['ExaStatus'] = '已提交';
        }

        $id = $this->service->add_d($object, $isAddInfo);

        if ($id) {
            // 统一更新明细表的采购部门编码字段信息 （解决下达采购申请后,明细表的采购部门信息空缺的问题）
            $updateSql = "update oa_asset_purchase_apply_item i left join oa_asset_purchase_apply c on c.id = i.applyId set i.purchDept = c.purchaseDept where c.id = '{$id}';";
            $this->service->query($updateSql);

            // 更新平台状态
            $result = util_curlUtil::getDataFromAWS('asset', 'updateOperationStatus', array(
                "requireId" => $object['relDocId']
            ));

            if ($actType == 'submit') {
                echo "<script>alert('提交成功,待总资产管理员确认!');window.close(); </script>";
            } else {
                echo "<script>alert('保存成功!');window.close(); </script>";
            }
        } else {
            if ($actType == 'submit') {
                msgRf('提交失败!');
            } else {
                msgRf('保存失败!');
            }
        }
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit(); // 检验是否重复提交
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST[$this->objName];

        // 选择交付部，不走审批，直接更行审批状态
        if ($actType == "noaudit") {
            $object['state'] = '已提交';
            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = date("Y-m-d");
        }

        $id = $this->service->add_d($object, $isAddInfo);
        if ($id) {
            // 统一更新明细表的采购部门编码字段信息 （解决下达采购申请后,明细表的采购部门信息空缺的问题）
            $updateSql = "update oa_asset_purchase_apply_item i left join oa_asset_purchase_apply c on c.id = i.applyId set i.purchDept = c.purchaseDept where c.id = '{$id}';";
            $this->service->query($updateSql);

            // 更新平台状态
            $result = util_curlUtil::getDataFromAWS('asset', 'updateOperationStatus', array(
                "requireId" => $object['relDocId']
            ));
            if ("audit" == $actType) {
                succ_show('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $object['useDetId'] . '&flowMoney=' . $object['amounts']);
            } else if ($actType == "noaudit") { // 选择交付部，去掉审批流程 ID2062
                $object['id'] = $id;
                $this->c_noAuditSendEmail($id);
                msgRf('提交成功!');
            } else {
                msgRf('保存成功!');
            }
        } else {
            if ("audit" == $actType) {
                msgRf('提交失败!');
            } else {
                msgRf('保存失败!');
            }
        }
    }

    /**
     * 新增对象操作
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
                echo "<script>alert('分配人员成功!');history.back(-1);</script>";
            }
        } else {
            if ("audit" == $actType) {
                echo "<script>alert('审批新增失败!');history.back(-1);</script>";
            } else {
                echo "<script>alert('新增失败!');history.back(-1);</script>";
            }
        }
    }

    /**
     * 确认采购物料分配人
     *
     * @param
     *            tags
     * @return return_type
     */
    function c_confirmProductUser()
    {
        $object = $this->service->confirmProductUser_d($_POST[$this->objName]);
        if ($object) {
            msgGo('下达成功', 'index1.php?model=purchase_plan_basic&action=toTabList');
        } else {
            msgGo('下达失败', 'index1.php?model=purchase_plan_basic&action=toTabList');
        }
    }

    /**
     * 物料确认打回给申请人
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
            msgGo('打回成功', 'index1.php?model=asset_purchase_apply_apply&action=toDeliReqList');
        } else {
            msgGo('打回失败', 'index1.php?model=asset_purchase_apply_apply&action=toDeliReqList');
        }
    }

    /**
     * 分配采购区域
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
            msg('分配成功!');
        } else {
            msg('打回失败');
        }
    }
    /**
     * 查看方法
     */
    /*
     * function c_read() { //$this->permCheck ();//安全校验 $id = isset ( $_GET['id'] ) ? $_GET['id'] : exit (); $purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null; $readType=isset($_GET['actType'])?$_GET['actType']:null; $plan = $this->service->get_d ( $id ); //获取物料的执行情况 $equipmentDao = new model_purchase_plan_equipment (); $executRows=$equipmentDao->getEquExecute_d($id); $this->assign ( 'listExecute', $this->service->showExecute_s ($executRows)); $this->assign ( 'listEquExecute', $this->service->showEquExecuteList_s ( $plan["childArr"] )); // $purchType=$plan['purchType']; $this->assign ( 'readType',$readType ); foreach ( $plan as $key => $val ) { $this->assign ( $key, $val ); } //$this->assign ( 'list', $this->service->showRead_s ( $plan["childArr"] ) ); //$this->display ( 'apply-task-give' ); $this->assign ( 'list', $this->service->showAssetRead_s ( $plan["childArr"] ) ); $this->display ('assets-view' ); }
     */
    function c_toConfirmUser()
    {
        $this->permCheck(); // 安全校验
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
     * 编辑采购申请,状态为未确认 PMS 2556
     */
    function c_editBeforeConfirm()
    {
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        if ($actType == 'submit') {
            $object['state'] = '未确认';
            $object['ExaStatus'] = '已提交';
        }
        $id = $this->service->edit_d($object, true);

        if ($id) {
            if ($actType == 'submit') {
                echo "<script>alert('提交成功,待总资产管理员确认!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        } else {
            if ($actType == 'submit') {
                echo "<script>alert('提交失败!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }
    }

    /**
     * 列表提交采购申请,状态为未确认 PMS 2978
     */
    function c_ajaxSubmitConfirm()
    {
        $object['id'] = isset($_GET['Id'])? $_GET['Id'] : '';
        $object['state'] = '未确认';
        $object['ExaStatus'] = '已提交';

        $object = $this->service->addUpdateInfo($object);

        $id = (empty($object['id']))? false : $this->service->updateById($object);

        if ($id) {
            echo "<script>alert('提交成功,待总资产管理员确认!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('提交失败!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * 修改对象操作
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

        // 选择交付部，不走审批，直接更行审批状态
        if ($actType == "noaudit") {
            $object['state'] = '已提交';
            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = date("Y-m-d");
        }

        $id = $this->service->edit_d($object, true);
        if ($id) {
            if ("audit" == $actType) {
                succ_show('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $object['useDetId'] . '&flowMoney=' . $object['amounts']);
            } else if ($actType == "noaudit") { // 选择交付部，去掉审批流程 ID2062
                $object['id'] = $id;
                $this->c_noAuditSendEmail($id);
                msgRf('提交成功!');
            } else {
                echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        } else {
            if ("audit" == $actType) {
                echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }
    }

    /**
     * 拆分采购
     */
    function c_assign()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->edit_d($object, true);
        $asignType = isset ($_GET['asignType']) ? $_GET['asignType'] : null;
        // 判断是否由行政部拆分采购
        if ($id) {
            if ("audit" == $asignType) {
                $this->service->sendEmail_d($id);
                // 发送邮件到交付部
            }
            echo "<script>alert('拆分成功!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('拆分失败!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * 资产采购申请不走审批处理
     * @author haojin
     * 2016-9-23
     */
    function c_noAuditSendEmail($id)
    {
        $service = $this->service;
        $object = $service->get_d($id);
        // 获取采购明细
        $applyItemDao = new model_asset_purchase_apply_applyItem ();
        $applyItemArr = $applyItemDao->getItem_d($object['id']);

        // 接入aws
        // 1.改变需求申请单状态,置为【采购中】
        $result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
            'requireId' => $object['relDocId'],
            'applyStatus' => '1040'
        ));

        // 2.更新资产申请从表采购数量信息
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
                // 发送邮件通知采购员
                $service->sendMailAtAdd($object);
            }
        }
    }

    /**
     * 资产采购申请审批通过后发送邮件
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
                if ($object['ExaStatus'] == "完成") {
                    if($object['purchaseDept'] == 1){// 交付采购
                        if ($objId != '') {
                            $obj['id'] = $objId;
                            $obj['state'] = '已提交';
                            $obj['ExaStatus'] = '完成';
                            $obj['ExaDT'] = date("Y-m-d");
                            $result = ($this->service->updateById($obj)) ? 'ok' : 'fail';

                            // 交付部的直接下推采购模块
                            if($result == 'ok'){
                                $this->c_noAuditSendEmail($objId);
                            }
                        }

                    }else{// 行政采购
                        // 获取采购明细
                        $applyItemDao = new model_asset_purchase_apply_applyItem ();
                        $applyItemArr = $applyItemDao->getItem_d($object['id']);
                        // 接入aws
                        // 1.改变需求申请单状态,置为【采购中】
                        $result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
                            'requireId' => $object['relDocId'],
                            'applyStatus' => '1040'
                        ));
                        // $requirement = new model_asset_require_requirement();
                        // $requirement->updateRecognize($object['relDocId']);
                        // 2.更新资产申请从表采购数量信息
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
                                // 发送邮件通知采购员
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
     * 采购询价
     */
    function c_inquire()
    {
        if ($this->service->inquire_edit_d($_POST[$this->objName], true)) {
            echo "<script>alert('保存成功!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('保存失败!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    /**
     * 跳转到研发新增页面
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
     * 跳转到研发采购列表
     */
    function c_toRDList()
    {
        $this->view('rd-list');
    }

    /**
     * 初始化对象
     */
    function c_RDinit()
    {
        // echo "<pre>";
        // print_R($_GET);
        $this->permCheck();
        // 安全校验
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
     * 跳转到采购申请资产确认列表
     */
    function c_toConfirmList()
    {
        $this->view('confirm-list');
    }

    /**
     * 查看申请
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
     * 跳转到采购申请资产确认页面
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
     * 跳转到采购申请资产确认列表 -- tab
     */
    function c_toConfirmTab()
    {
        $this->view('confirm-tab');
    }

    /**
     * ************** 撤回部分 ***************
     */
    /**
     * 判断是否可以整单撤回
     */
    function c_canBackForm()
    {
        // 直接返回可撤销数量
        echo $this->service->canBackForm_d($_POST['id']);
    }

    /**
     * 整单撤回
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
     * 物料撤回
     */
    function c_toBackDetail()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('backdetail');
    }

    /**
     * 物料撤回
     */
    function c_backDetail()
    {
        if ($this->service->backDetail_d($_POST[$this->objName])) {
            msg('撤回成功');
        }
    }

    /**
     * 确认资产采购申请 created by PMS2556
     */
    function c_confirmApply()
    {
        $applyId = isset($_POST['id']) ? $_POST['id'] : '';
        $result = array();
        $applyArr = $this->service->find(array('id' => $applyId), null, 'purchaseDept');
        if ($applyId != '') {
            $obj['id'] = $applyId;
            $obj['state'] = '已确认';
            if (isset($applyArr['purchaseDept'])) {
                if ($applyArr['purchaseDept'] == '1') {
                    $obj['ExaStatus'] = '完成';
                    $obj['ExaDT'] = date("Y-m-d");
                }
                $result['result'] = ($this->service->updateById($obj)) ? 'ok' : 'fail';

                if ($applyArr['purchaseDept'] == '1') {// 交付部的直接下推采购模块
                    $this->c_noAuditSendEmail($applyId);
                }
            }
        } else {
            $result['result'] = 'fail';
        }
        echo json_encode($result);
    }

    /**
     * 打回资产采购申请 created by PMS2556
     */
    function c_dispassApply()
    {
        $applyId = isset($_POST['id']) ? $_POST['id'] : '';
        $result = array();
        if ($applyId != '') {
            $obj['id'] = $applyId;
            $obj['state'] = '打回';
            $obj['ExaStatus'] = '待提交';
            $obj['ExaDT'] = '';

            $result['result'] = ($this->service->updateById($obj)) ? 'ok' : 'fail';
        } else {
            $result['result'] = 'fail';
        }
        echo json_encode($result);
    }
}