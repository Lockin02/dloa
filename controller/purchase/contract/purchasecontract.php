<?php

/**
 * @description: 采购合同
 * @date 2010-12-29 下午08:57:00
 */
class controller_purchase_contract_purchasecontract extends controller_base_action
{
    /*
	 * @desription 构造函数
	 * @author qian
	 * @date 2010-12-29 下午08:58:39
	 */
    function __construct()
    {
        $this->objName = "purchasecontract";
        $this->objPath = "purchase_contract";
        $this->datadictFieldArr = array("billingType", "paymentType", "paymentCondition");
        parent::__construct();
    }

    /*******************************************普通Action方法********************************************/

    /*
	 * @desription 跳转到生成采购合同页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-31 下午03:38:23
	 */
    function c_toAddPurchaseContract()
    {
        $this->permCheck($_GET['inquiryId'], 'purchase_inquiry_inquirysheet');//安全校验
        $service = $this->service;
        $inquiryId = $_GET ['inquiryId']; //询价单ID
        $suppId = $_GET ['suppId']; //指定供应商运营库的ID


        //根据采购询价单的ID值获取设备的数据
        $inquiryArr = $service->getInquirySheet_d($inquiryId);

        //得到供应商的报价
        $service->searchArr = array("inquiryId" => $inquiryId, "suppId" => $suppId);
        $suppArr = $service->listBySqlId("get_suppInfo");
        //获取备注信息
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //获取供应商银行信息
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($inquiryArr ['supplier']['id']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //供应商的数据
        foreach ($inquiryArr ['supplier'] as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //付款条件
            $this->assign('paymentConditionName', "");
        }

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppId);
        $this->assign('inquiryId', $inquiryId);
        $this->assign('dateHope', $suppArr['0']['arrivalDate']);

        $this->display('add');
    }

    /**
     * 多张采购询价单生成一张采购订单
     *
     */
    function c_toAddByMore()
    {
        $service = $this->service;
        $inquiryId = $_GET ['inquiryId']; //询价单ID
        $inquiryIdArr = explode(',', $inquiryId);
        $suppId = $_GET ['suppId']; //指定供应商运营库的ID


        //根据采购询价单的ID值获取设备的数据
        $inquiryArr = $service->getInquirySheet_d($inquiryIdArr[0]);

        //得到供应商的报价
        $service->searchArr = array("inquiryIdArr" => $inquiryIdArr, "suppId" => $suppId);
        $suppArr = $service->listBySqlId("get_suppInfo");
        //得到供应商的报价最小时间
        $minDate = $service->getMinHopeDate_d($inquiryIdArr, $suppId);
        //获取备注信息
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //获取供应商银行信息
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($inquiryArr ['supplier']['id']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //供应商的数据
        foreach ($inquiryArr ['supplier'] as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //付款条件
            $this->assign('paymentConditionName', "");
        }

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppId);
        $this->assign('inquiryId', $inquiryId);
        $this->assign('dateHope', $minDate);

        $this->display('add');
    }

    /**
     * 起草采购订单
     *
     */
//	function c_toAddOrder() {
//		$addType=isset($_GET['addType'])?$_GET['addType']:"";    //区分是在菜单添加还是在采购订单列表中添加
//
//		//配置数据字典的值
//		$this->showDatadicts ( array ('invoiceType' => 'FPLX' ) ); //发票类型
//		$this->showDatadicts ( array ('paymentType' => 'fkfs' ) ); //付款方式
//		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //付款条件
//
//
//		$this->assign ( 'sendName', $_SESSION ['USERNAME'] ); //起草人
//		$this->assign ( 'sendUserId', $_SESSION ['USER_ID'] );
//		$this->assign('addType',$addType);
//		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) ); //税率
//
//			//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//盖章类型
//
//		$this->display ( 'order-add' );
//	}
    function c_toAddOrder()
    {
        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('formDate', date("Y-m-d"));

        $this->display('inquiry-add');
    }

    /**
     * 起草采购订单,通过采购询价物料汇总
     *
     */
    function c_toAddByInquiryEqu()
    {
        $service = $this->service;
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $this->service->getParam($_GET);

        //得到供应商的报价
        $service->searchArr = array("inquiryIdEquArr" => $idsArry);
        $suppArr = $service->listBySqlId("get_suppInfo");
        $flibraryDao = new model_supplierManage_formal_flibrary();
        $supplier = $flibraryDao->get_d($suppArr[0]['suppId']);
        //得到供应商的报价最小时间
        $minDate = $service->getMinHopeDateByEqu_d($idsArry);
//		//获取备注信息
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //获取供应商银行信息
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($suppArr [0]['suppId']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //供应商的数据
        foreach ($supplier as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //付款条件
            $this->assign('paymentConditionName', "");
        }

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppArr[0]['suppId']);
        $this->assign('inquiryId', "");
        $this->assign('dateHope', $minDate);

        $this->display('add');
    }

    /**
     * 起草采购订单,通过采购任务
     *
     */
    function c_toAddOrderByTask()
    {
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->assign('type', $type);
        $this->assign('orderType', $orderType);    //判断是由我的采购中的采购任务下达订单还是由采购任务中的执行中页面下达
        $this->service->getParam($_GET);
        //获取采购任务的物料清单
        $equipmentDao = new model_purchase_task_equipment ();
        $listEqu = $equipmentDao->getTaskEqu_d($idsArry);
        //获取备注信息
        $remarkRow = $this->service->getTaskRemark_d($listEqu);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $this->service->showEquList($listEqu));

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
        $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //付款条件


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->showDatadicts(array('taxRate' => 'XJSL')); //税率

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型

        $this->display('task-add');
    }

    /**
     * 采购任务下推订单（订单与询价合并-第一步）
     *
     */
    function c_toAddOrderNew()
    {
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->service->getParam($_GET);
        //获取采购任务的物料清单
        $equipmentDao = new model_purchase_task_equipment ();
        $listEqu = $equipmentDao->getTaskEqu_d($idsArry);
//		echo "<pre>";
//		print_r($listEqu);exit();
        //获取备注信息
        $remarkRow = $this->service->getTaskRemark_d($listEqu);
        $formBelong = $businessBelong = 'dl';
        $formBelongName = $businessBelongName = '世纪鼎利';
        foreach ($listEqu as $k => $v) {
            if (isset($v['businessBelong']) && $v['businessBelong'] != '') {
                $formBelong = $businessBelong = $v['businessBelong'];
                $formBelongName = $businessBelongName = $v['businessBelongName'];
            }
        }
        $this->assign('formBelong', $formBelong);
        $this->assign('formBelongName', $formBelongName);
        $this->assign('businessBelong', $businessBelong);
        $this->assign('businessBelongName', $businessBelongName);

        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $this->service->showOrderEquList($listEqu));


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('formDate', date("Y-m-d"));

        $this->display('neworder-add');
    }

    /**
     * 采购任务下推订单（订单与询价合并）
     *
     */
    function c_toPushOrder()
    {
        $type = isset ($_GET ['type']) ? $_GET ['type'] : '';
        $id = isset ($_GET ['id']) ? $_GET ['id'] : "";
        $this->service->getParam($_GET);
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('orderType', $type);

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
//		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //付款条
        $this->showDatadicts(array('payType' => 'CWFKFS'));

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型

        $this->display('pushorder-add');
    }

    /**
     * 由资产采购任务下推采购订单
     *
     */
    function c_toAddOrderByAsset()
    {
        $applyId = isset ($_GET ['applyId']) ? $_GET ['applyId'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->assign('orderType', $orderType);
        $this->service->getParam($_GET);
        //获取资产采购任务的物料清单
        $equipmentDao = new model_asset_purchase_task_taskItem ();
        $listEqu = $equipmentDao->getItemByParent_d($applyId);
        $this->assign('list', $this->service->showAssetEquList($listEqu));

        //配置数据字典的值
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //发票类型
        $this->showDatadicts(array('paymentType' => 'fkfs')); //付款方式
        $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //付款条件


        $this->assign('sendName', $_SESSION ['USERNAME']); //起草人
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->showDatadicts(array('taxRate' => 'XJSL')); //税率

        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型

        $this->display('apply-add');
    }

    /*
	 * @desription 跳转到查看/编辑采购合同信息页面
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 下午03:07:32
	 */
    function c_init()
    {
        $this->permCheck();//安全校验
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
//		echo "<pre>";
//		print_r($rows);
        $equs = $service->getEquipments_d($_GET ['id']);
        if ($rows['instruction'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//获取采购任务的备注与采购说明
            $rows['instruction'] = $infoRow['instruction'] . "\n" . $rows['instruction'];
            $rows['remark'] = $infoRow['remark'] . "\n" . $rows['remark'];
        }
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'stock_fillup_fillup');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }

        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            if ($rows['originalId'] != "") {
                $skey = $this->md5Row($rows['originalId'], 'purchase_contract_purchasecontract');
            }
            $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
            $this->assign('list', $service->addContractEquList_s($newEqus));
            $equDao = new model_purchase_contract_equipment ();

            //获取采购申请信息
            $planEquRows = $equDao->getPlanEquForRead_d($_GET['id']);
            if (is_array($planEquRows)) {
                foreach ($planEquRows as $plankey => $planval) {
                    if ($planval['sourceID'] != "") {
                        switch ($planval['purchType']) {
                            case "oa_sale_order":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_order_order');
                                break;
                            case "oa_sale_lease":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'contract_rental_rentalcontract');
                                break;
                            case "oa_sale_service":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'engineering_serviceContract_serviceContract');
                                break;
                            case "oa_sale_rdproject":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'rdproject_yxrdproject_rdproject');
                                break;
                            case "stock":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'stock_fillup_fillup');
                                break;
                            case "oa_borrow_borrow":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_borrow_borrow');
                                break;
                            case "oa_present_present":
                                $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_present_present');
                                break;
                            default:
                                $planEquRows[$plankey]['skey_'] = '';
                                break;
                        }
                    }
                }

            }
            $this->assign('planList', $equDao->showPlanEquListForOrder($planEquRows));

            //获取供应商信息
            $suppDao = new model_purchase_contract_applysupp();
            $suppRows = $suppDao->getSuppByParentId($_GET['id']);
            //获取询价产品清单
            $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
            //显示报价详情

            $suppequDao = new model_purchase_contract_applysuppequ();
            if (is_array($suppRows)) {
                foreach ($suppRows as $key => $val) {
                    $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
                }
            }

            //获取订单中物料的总数量和协议价格
            $applybasicDao = new model_purchase_apply_applybasic();
            $materialequDao = new model_purchase_material_materialequ();
            $materialDao = new model_purchase_material_material();
            $suppProDao = new model_purchase_contract_applysuppequ();
            for ($i = 0; $i < count($uniqueEquRows); $i++) {
                $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

                $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
                $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
                $materialRow = $materialDao->get_d($materialequRow['parentId']);

                $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//没有当前数量
                $materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

                $uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow, $materialequRow, $materialequRow1);
            }

            $this->show->assign("listSee", $this->service->showSupp_s($suppRows, $uniqueEquRows));
            if (is_array($suppRows)) {
                $suppNumb = count($suppRows);
            } else {
                $suppNumb = 0;
            }
            $this->assign('suppNumb', $suppNumb);
            //发票类型
            $billingType = $this->getDataNameByCode($rows ['billingType']);
            $this->assign('bType', $billingType);

            //付款类型
            $paymetType = $this->getDataNameByCode($rows ['paymentType']);
            $this->assign('pType', $paymetType);

            //付款条件
            $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
            $this->assign('paymentCondition', $paymentCondition);

            //对签约状态的值进行转换
            $signStatus = $service->signStatus_d($rows ['signStatus']);
            $this->assign('signStatus', $signStatus);

            //开户银行
            $suppBank = $this->getDataNameByCode($rows ['suppBank']);
            $this->assign('suppBank', $suppBank);
            $this->assign('skey', $skey);
            if ($rows ['isNeedStamp'] == 1) {
                $this->assign('isNeedStamp', "是");
            } else {
                $this->assign('isNeedStamp', "否");
            }
            if ($rows ['isStamp'] == 1) {
                $this->assign('isStamp', "是");
            } else {
                $this->assign('isStamp', "否");
            }

            //判断是否隐藏关闭按钮
            if (isset($_GET['hideBtn'])) {
                $this->assign('hideBtn', 1);
            } else {
                $this->assign('hideBtn', 0);
            }

            $this->display('order-view');
        } else {
            $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], true));
            $this->assign('list', $service->editContractEquList_s($equs));
            $this->showDatadicts(array('billingType' => 'FPLX'), $rows ['billingType']);
            $this->showDatadicts(array('paymetType' => 'fkfs'), $rows ['paymetType']);
//			$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ), $rows ['paymentCondition'] );
            //设置盖章类型
            $stampConfigDao = new model_system_stamp_stampconfig();
            $stampArr = $stampConfigDao->getStampType_d();
            $this->showSelectOption('stampType', $rows ['stampType'], true, $stampArr);//盖章类型
            $this->showDatadicts(array('payType' => 'CWFKFS'), $rows ['payType']);
//			$length=count($equs); //获取物料数组的长度
//			for($i=1;$i<=$length;$i++){
//				$j=$i-1;
//				$this->showDatadicts ( array ('taxRate'.$i => 'XJSL' ), $equs [$j]['taxRate'] );
//			}
            $this->assign('allMoney', bcadd(0, $rows ['allMoney'], 2));
            $this->assign('allMoneyView', number_format(bcadd(0, $rows ['allMoney'], 2), 2));

            $this->display('order-edit');
        }
    }

    //中止时的查看页面
    function c_toCloseView()
    {
        $this->permCheck();//安全校验
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //中止类型
        if ($rows['closeType'] == 1) {
            $this->assign('closeTypeC', '订单结束');
        } else {
            $this->assign('closeTypeC', '订单删除');
        }

        $equs = $service->getEquipments_d($_GET ['id']);
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }
        if ($rows['originalId'] != "") {
            $skey = $this->md5Row($rows['originalId'], 'purchase_contract_purchasecontract');
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->assign('list', $service->addContractEquList_s($newEqus));
        //发票类型
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //付款类型
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //付款条件
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //对签约状态的值进行转换
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //开户银行
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "是");
        } else {
            $this->assign('isNeedStamp', "否");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "是");
        } else {
            $this->assign('isStamp', "否");
        }

        $this->display('close-view');

    }

    //关闭时的查看页面
    function c_toCloseOrderView()
    {
        $this->permCheck();//安全校验
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $equs = $service->getEquipments_d($_GET ['id']);
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }
        if ($rows['originalId'] != "") {
            $skey = $this->md5Row($rows['originalId'], 'purchase_contract_purchasecontract');
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->assign('list', $service->addContractEquList_s($newEqus));
        //发票类型
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //付款类型
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //付款条件
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //对签约状态的值进行转换
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //开户银行
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "是");
        } else {
            $this->assign('isNeedStamp', "否");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "是");
        } else {
            $this->assign('isStamp', "否");
        }

        $this->display('closeorder-view');

    }

    //关闭时的查看页面
    function c_toCloseRead()
    {
        $this->permCheck();//安全校验
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('close-read');
    }

    /**
     * @description 跳转到审批时查看合同信息的Tab页
     * @date 2011-2-22
     */
    function c_toTabView()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        /*	//根据采购订单Id，获取采购询价物料id
		$condition="basicId=".$id;
		$inquEquId=$this->service->findAll($condition,'oa_purch_apply_equ','inquiryEquId');
		if($inquEquId){     //获取采购询价单ID
			$inquCondition="id=".$inquEquId;
			$inquId=$this->service->get_table_fields('oa_purch_inquiry_equ',$inquCondition,'parentId');
			$inquSkey=$this->md5Row($inquId,'purchase_inquiry_inquirysheet');
		}else{
			$inquId="";
			$inquSkey="";
		}*/
        $this->assign('inquiryId', $inquId);
        //	$this->assign('inquSkey',$inquSkey);
        $this->display('tab-view');
    }

    /**
     * @description 跳转到查看合同信息的Tab页
     */
    function c_toTabRead()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('tab-read');
    }

    /**
     * @description 跳转到中止审批查看合同信息的Tab页
     */
    function c_toCloseTabRead()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('close-tab-read');
    }

    /**
     * @description 跳转到关闭审批查看合同信息的Tab页
     */
    function c_toCloseOrderTabRead()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('closeorder-tab-read');
    }

    /**
     * @description 跳转到查看合同信息的Tab页
     */
    function c_toReadTab()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        //根据采购订单Id，获取采购询价物料id
        /*	$condition="basicId=".$id;
		$inquEquId=$this->service->get_table_fields('oa_purch_apply_equ',$condition,'inquiryEquId');
		if($inquEquId){     //获取采购询价单ID
			$inquCondition="id=".$inquEquId;
			$inquId=$this->service->get_table_fields('oa_purch_inquiry_equ',$inquCondition,'parentId');
			$inquSkey=$this->md5Row($inquId,'purchase_inquiry_inquirysheet');
		}else{
			$inquId="";
			$inquSkey="";
		}*/
        $this->assign('inquiryId', $inquId);
        //	$this->assign('inquSkey',$inquSkey);
        $this->display('view-tab');
    }

    function c_toTabHistory()
    {
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);

        $this->display('list-history');
    }

    /*
	 * @desription 添加采购合同
	 * @param tags
	 * @author qian
	 * update by can
	 * @date 2010-12-31 下午03:33:24
	 */
    function c_add()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $contract ['ExaStatus'] = "未审核";
        $equs = $contract ['equs'];

        $url = "?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab";
        if (is_array($contract)) {
            $id = $service->add_d($contract);

            if ($id) {
                //调用询价单接口
                if ($_GET ['act'] == "app") {
                    switch ($_GET ['type']) {
                        case 'order' :
                            if ($_GET ['appType'] == "order") {
                                succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                                break;
                            } else {
                                succ_show('controller/purchase/contract/ewf_index_menu.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                                break;
                            }
                        case 'inquiry' :
                            succ_show('controller/purchase/contract/ewf_index_inquiry.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                            break;
                        case 'task' :
                            if ($_GET ['orderTypes'] == "manager") {
                                succ_show('controller/purchase/contract/ewf_index_task2.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                                break;
                            } else {
                                succ_show('controller/purchase/contract/ewf_index_task.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                                break;
                            }
                        default :
                            break;
                    }

                } else if ($_GET ['addType'] == "alone") {
                    if ($_GET ['isList'] == "order") {
                        msgGo('保存成功', "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2");
                    } else {
                        msgGo('保存成功', "?model=purchase_contract_purchasecontract&action=toAddOrder");
                    }
                } else if ($_GET ['addType'] == "task") {
                    if ($_GET ['orderType'] == "manager") {
                        msgGo('保存成功', "index1.php?model=purchase_task_basic&action=executionList");
                    } else {
                        msgGo('保存成功', "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1");
                    }
                } else {
                    msgGo('保存成功', $url);
                }
            } else {
                msgGo('保存不成功,设备信息不完整', $url);
            }
        }
    }

    /**添加采购询价单方法
     *author can
     *2010-12-28
     */
    function c_addOrder()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $objectId = $this->service->addOrder_d($_POST['contract']);
        if ($objectId) {
            succ_show("?model=purchase_contract_purchasecontract&action=toPushOrder&id=$objectId&type=$type");
        } else if ($type == '') {
            msgGo('物料信息不完整', "?model=purchase_contract_purchasecontract&action=toAddOrder");
        } else {
            msgGo('物料信息不完整', "?model=purchase_task_basic&action=taskMyList");
        }
    }

    /**添加供应商
     */
    function c_addSupp()
    {
        $supplier = $this->service->addSupp_d($_POST);
        if ($supplier) {
            echo $supplier;      //输出用于判断是否添加成功
        }

    }

    /**如果已选供应商，则重新保存
     */
    function c_suppAdd()
    {
        $supplier = $this->service->suppAdd_d($_POST);
        echo $supplier;
    }

    /**
     *
     *由资产任务下推采购订单保存方法
     */
    function c_addAssetOrder()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $contract ['ExaStatus'] = "未审核";
        $equs = $contract ['equs'];

        $url = "?model=asset_purchase_task_task&action=pageMyList";
        if (is_array($contract)) {
            $id = $service->add_d($contract);

            if ($id) {
                //调用询价单接口
                if ($_GET ['act'] == "asset") {
                    succ_show('controller/purchase/contract/ewf_index_asset.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=采购合同审批');
                } else {
                    msgGo('保存成功', $url);
                }
            } else {
                msgGo('保存不成功,设备信息不完整', $url);
            }
        }

    }

    /*
	 * @desription 保存采购合同的编辑内容
	 * @param 编辑,保存
	 * @author qian
	 * @date 2011-1-5 上午10:28:42
	 *
	 * 这种写法是错误的，应该把设备的添加方法写到$service->edit_d ( $rows, true );中去
	 */
    function c_editContract()
    {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $rows = $_POST ['contract'];
        $equs = $_POST ['equs'];

        if ($rows ['paymentCondition'] != "YFK") {  //判断付款条件是否为预付款
            $rows ['payRatio'] = "";
        }

        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict ();
        $rows ['paymentConditionName'] = $datadictDao->getDataNameByCode($rows['paymentCondition']);
        $rows ['paymentTypeName'] = $datadictDao->getDataNameByCode($rows['paymentType']);
        $rows ['billingTypeName'] = $datadictDao->getDataNameByCode($rows['billingType']);
        $service = $this->service;
        if (is_array($rows)) {
            $id = $service->edit_d($rows, true);
        }
        $taskEquDao = new model_purchase_task_equipment ();
        if (is_array($equs)) {
            $equDao = new model_purchase_contract_equipment ();
            foreach ($equs as $key => $val) {
                $equDao->edit_d($val, true);
                if ($val['applyEquId'] != "" && $val['applyEquId'] != 0) {
                    $taskItemDao = new model_asset_purchase_task_taskItem();
                    $taskItemDao->updateIssuedAmount($val['applyEquId'], $val['amountAll'], $val['amountOld']);
                } else {
                    if ($val ['taskEquId'] > 0) {
                        //更新采购任务设备的已下达/合同申请数量
                        $taskEquDao->updateContractAmount($val ['taskEquId'], $val ['amountAll'], $val ['amountOld']);
                    }

                }
            }
        }
        if ($id) {
            if ($act == 'audit') {
                $newId = $rows['id'];
                $orderRows = $this->service->get_d($rows['id']);
                if ($orderRows['ExaStatus'] == "打回") {
                    $newId = $this->service->dealApproval_d($orderRows);
                }
                succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $newId . '&examCode=oa_purch_apply_basic&flowMoney=' . $orderRows['allMoney'] . '&formName=采购合同审批');
            } else {
                $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
                msgGo('编辑成功', $url);
            }
        } else {
            $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
            msgGo('设备信息不完整，编辑失败', $url);
        }

    }

    /*
	 * @desription 保存采购合同的编辑内容
	 */
    function c_addOrderEdit()
    {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $rows = $_POST ['contract'];
//		echo "<pre>";
//		print_r($rows);
        $id = $this->service->addOrderEdit_d($rows);
        if ($id) {
            if ($act == 'audit') {
                $newId = $rows['id'];
                $orderRows = $this->service->get_d($rows['id']);

                if ($rows['isApplyPay'] == 1) {
                    if ($_POST['orderType'] == 'neworder') {
                        succ_show('controller/purchase/contract/ewf_index_menu.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] . '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=采购订单审批(含付款申请)');
                    } else {
                        succ_show('controller/purchase/contract/ewf_index_pay.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=采购订单审批(含付款申请)');
                    }
                } else {
                    if ($_POST['orderType'] == 'neworder') {
                        succ_show('controller/purchase/contract/ewf_index_menu.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=采购合同审批');
                    } else {
                        succ_show('controller/purchase/contract/ewf_index_task.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=采购合同审批');
                    }
                }
            } else {
                if ($_POST['orderType'] == 'neworder') {
                    $url = "?model=purchase_contract_purchasecontract&action=toAddOrder";
                } else {
                    $url = "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1";
                }
                msgGo('保存成功', $url);
            }
        } else {
            if ($_POST['orderType'] == 'neworder') {
                $url = "?model=purchase_contract_purchasecontract&action=toAddOrder";
            } else {
                $url = "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1";
            }
            msgGo('保存失败', $url);
        }

    }

    /*
	 * @desription 保存采购合同的编辑内容
	 */
    function c_orderEdit()
    {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $rows = $_POST ['contract'];
        $id = $this->service->addOrderEdit_d($rows);
        if ($id) {
            if ($act == 'audit') {
                $newId = $rows['id'];
                $orderRows = $this->service->get_d($rows['id']);
                if ($rows['isApplyPay'] == 1) {
                    succ_show('controller/purchase/contract/ewf_index_payedit.php?actTo=ewfSelect&billId=' . $newId .
                        '&billCompany=' . $orderRows['businessBelong'] .
                        '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=采购订单审批(含付款申请)');
                } else {
                    succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $newId .
                        '&billCompany=' . $orderRows['businessBelong'] .
                        '&examCode=oa_purch_apply_basic&flowMoney=' . $orderRows['allMoney'] . '&formName=采购合同审批');
                }
            } else {
                $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
                msgGo('编辑成功', $url);
            }
        } else {
            $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
            msgGo('保存失败', $url);
        }

    }

    /*
	 * @desription 删除采购合同
	 * @param tags
	 * @author qian
	 * @date 2011-1-7 下午03:38:53
	 */
    function c_deletes()
    {
        $this->permCheck();//安全校验
        $deleteId = isset ($_GET ['id']) ? $_GET ['id'] : exit ();
        $delete = $this->service->delete_d($deleteId);
        if ($delete) {
            msgGo('删除成功');
        }

    }

    /*
	 * @desription 跳转到采购管理-采购合同/合同管理-合同中心-采购合同的跳转TAB页
	 * @author qian
	 * @date 2010-12-30 下午08:07:37
	 */
    function c_toPCManage()
    {
        $this->display('tab-pc');
    }

    /*********************************************以下是“我的采购-采购合同”************************************************************/

    /*
	 * @desription 跳转到“我的采购-采购合同”Tab跳转页面
	 * @author qian
	 * @date 2010-12-29 下午09:19:45
	 */
    function c_myPurchaseContractTab()
    {
        $this->display('tab-mycontract');
    }

    /**##########################OY版合同状态位的方法##############################*/

    /*
	 * @desription 我的采购合同--待提交审批
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午07:08:14
	 */
    function c_myWaitList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("save") . "," . $service->stateToSta("fightback")); //"ExaStatus" => "未审核".","."打回"
        //实现搜索功能
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//搜索值
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//搜索字段
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->sort = "c.updateTime";
        $service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分

        $this->assign('applyNumb', $searchvalue);
        $this->assign('searchCol', $searchCol);
        $rows = $service->getContracts();
        $rows = $this->sconfig->md5Rows($rows);
        $this->assign('list', $service->showMyWaitList_s($rows));
        $this->pageShowAssign();
        $this->display('list-mywait');
        unset ($service);

    }

    /*
	 * @desription 我的采购合同--审批中的采购合同
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午07:14:54
	 */
    function c_myApprovalList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("approval"));
        //实现搜索功能
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//搜索值
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//搜索字段
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
        $rows = $service->getContracts();
        $rows = $this->sconfig->md5Rows($rows);
        $this->assign('applyNumb', $searchvalue);
        $this->assign('searchCol', $searchCol);

        $this->assign('list', $service->showApprovalList_s($rows));
        $this->pageShowAssign();
        $this->display('list-myapproval');
        unset ($service);

    }

    /*
	 * @desription 我的采购合同--执行中的采购合同
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午07:15:11
	 */
    function c_myExecutionList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("wite") . "," . $service->stateToSta("execute") . "," . $service->stateToSta("changeAuditing"));


        //实现搜索功能
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//搜索值
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//搜索字段
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        if (!isset($_GET['sortArr'])) {
            $service->sort = "c.ExaDT";
        }
        $service->__SET('searchArr', $searchArr);
        $service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分

        /*s:-----------------排序控制-------------------*/
        $sortField = isset($_GET['sortField']) ? $_GET['sortField'] : "";
        $sortType = isset($_GET['sortType']) ? $_GET['sortType'] : "";
        if (!isset($_GET['sortArr']) && !empty($sortField)) {
            if ($sortField == "payed" || $sortField == "applyed" || $sortField == "handInvoiceMoney") {
                $service->sort = $sortField;
            } else {
                $service->sort = "c." . $sortField;
            }
            $service->asc = $sortType;
        }
        $this->assign("sortType", $sortType);
        $this->assign("sortField", $sortField);
        /*e:-----------------排序控制-------------------*/

        $rows = $service->getOrderList_d();
        $rows = $this->sconfig->md5Rows($rows);
        //对列表按照接收时间和更新时间来进行排序
//		if(is_array($rows)){
//			foreach($rows as $key=>$val){
//				$ExaDT[$key]=$val['ExaDT'];
//				$updateTime[$key]=$val['updateTime'];
//			}
//			array_multisort($ExaDT,SORT_DESC,$updateTime,SORT_DESC,$rows);
//		}
        $this->assign('applyNumb', $searchvalue);
        $this->assign('searchCol', $searchCol);

        $this->assign('list', $service->showMyExecutionList_s($rows));
        $this->pageShowAssign();
        $this->display('list-myexecution');
        unset ($service);

    }

    /*
	 * @desription 执行中的采购合同
	 */
    /*	function c_toExecutionList() {
		$service = $this->service;
		$searchArr = array ("stateArr" => $service->stateToSta ( "wite" ) . "," . $service->stateToSta ( "execute" ) );

		//实现搜索功能
		$applyNumb = isset ( $_GET ['applyNumb'] ) ? $_GET ['applyNumb'] : null;
		if ($applyNumb != "") {
			$searchArr ['seachApplyNumb'] = $applyNumb;
		}
		$service->getParam ( $_GET );
		$service->sort = "c.updateTime";
		$service->__SET ( 'searchArr', $searchArr );
		$service->__SET ( 'groupBy', "Id" );
		$rows = $service->getContracts ();
		$rows = $this->sconfig->md5Rows ( $rows );

		$this->assign ( 'applyNumb', $applyNumb );

		$this->assign ( 'list', $service->showExecutionList_s ( $rows ) );
		$this->pageShowAssign ();
		$this->display ( 'list-execution' );
		unset ( $service );

	}*/

    /*
	 * @desription 我的采购合同--已关闭的采购合同
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午07:15:33
	 */
    function c_myCloseList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], //已关闭的采购合同的状态为--“关闭”或者“完成”
            "stateArr" => $service->stateToSta("close") . "," . $service->stateToSta("end") . "," . $service->stateToSta("closeOrder"));

        //实现搜索功能
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//搜索值
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//搜索字段
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->sort = "c.dateFact";
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
        $rows = $service->getContracts();
        $rows = $this->sconfig->md5Rows($rows);

        $this->assign('applyNumb', $searchvalue);
        $this->assign('searchCol', $searchCol);

        $this->assign('list', $service->showCloseList_s($rows));
        $this->pageShowAssign();
        $this->display('list-myclose');
        unset ($service);

    }

    /*
	 * @desription已关闭的采购合同
	 */
    /*	function c_toCloseList() {
		$service = $this->service;
		$searchArr = array (//已关闭的采购合同的状态为--“关闭”或者“完成”
		"stateArr" => $service->stateToSta ( "close" ) . "," . $service->stateToSta ( "end" ) );

		//实现搜索功能
		$applyNumb = isset ( $_GET ['applyNumb'] ) ? $_GET ['applyNumb'] : null;
		if ($applyNumb != "") {
			$searchArr ['seachApplyNumb'] = $applyNumb;
		}
		$service->getParam ( $_GET );
		$service->__SET ( 'searchArr', $searchArr );
		$service->__SET ( 'groupBy', "Id" );
		$rows = $service->getContracts ();
		$rows = $this->sconfig->md5Rows ( $rows );

		$this->assign ( 'applyNumb', $applyNumb );

		$this->assign ( 'list', $service->showCloseList_s ( $rows ) );
		$this->pageShowAssign ();
		$this->display ( 'list-close' );
		unset ( $service );

	}*/

    /**我的采购合同，申请变更
     *author can
     *2011-1-12
     */
    function c_toChange()
    {
        $this->permCheck();//安全校验
        $changeLogDao = new model_common_changeLog('purchasecontract');
        if ($changeLogDao->isChanging($_GET['id'])) {
            msgGo('该采购订单已经在变更审批中，无法变更');
        }
        $service = $this->service;
        $returnObj = $this->objName;

        $rows = $this->service->get_d($_GET ['id']);
        $rows['allMoney'] = bcadd(0, $rows ['allMoney'], 2);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $equs = $service->getEquipments_d($_GET ['id']);

        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->assign('id', $_GET ['id']);
        $this->assign('list', $service->chageContractList_s($equs));
        $this->showDatadicts(array('billingType' => 'FPLX'), $rows ['billingType']);
        $this->showDatadicts(array('paymetType' => 'fkfs'), $rows ['paymetType']);
        $this->showDatadicts(array('paymentCondition' => 'FKTJ'), $rows ['paymentCondition']);
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $length = count($equs); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        if (is_array($equs)) {
            $this->assign('TraNumber', count($equs));
        } else {
            $this->assign('TraNumber', 0);
        }
        $this->showDatadicts(array('taxRate' => 'XJSL')); //税率
        $this->display('change');
    }

    /**
     * add by chengl 2011-05-18
     * 修改合同变更
     */
    function c_toEditChange()
    {

        $service = $this->service;
        $returnObj = $this->objName;

        $rows = $this->service->get_d($_GET ['id']);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $equs = $service->getEquipments_d($_GET ['id']);
        $this->assign('oldId', $rows['originalId']);
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], true));
        $this->assign('list', $service->chageContractList_s($equs));
        $this->showDatadicts(array('billingType' => 'FPLX'), $rows ['billingType']);
        $this->showDatadicts(array('paymetType' => 'fkfs'), $rows ['paymetType']);
        $this->showDatadicts(array('paymentCondition' => 'FKTJ'), $rows ['paymentCondition']);
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $length = count($equs); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        if (is_array($equs)) {
            $this->assign('TraNumber', count($equs));
        } else {
            $this->assign('TraNumber', 0);
        }
        $this->showDatadicts(array('taxRate' => 'XJSL')); //税率
        $this->display('change-edit');
    }

    /**
     *
     * 修改订单变更
     */
    function c_editChange()
    {
        $object = $_POST ['contract'];
        $id = $this->service->change_d($object);
        echo "<script>this.location='controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
    }

    /**
     * 采购订单提交审批
     *
     */
    function c_approvalOrder()
    {
        $id = isset ($_GET ['id']) ? $_GET ['id'] : null;
        $order = $this->service->get_d($id);
        if ($order['ExaStatus'] == "打回") {     //如果采购订单的审批状态为“打回”，刚新增一条数据
            $id = $this->service->dealApproval_d($order);
        }
        if ($order['isApplyPay'] == 1) {
            succ_show('controller/purchase/contract/ewf_index_payedit.php?actTo=ewfSelect&billId=' . $id .
                '&examCode=oa_purch_apply_basic&flowMoney=' . $order['allMoney'] .
                '&billCompany=' . $order['businessBelong'] . '&formName=采购订单审批(含付款申请)');
        } else {
            succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                '&examCode=oa_purch_apply_basic&flowMoney=' . $order['allMoney'] .
                '&billCompany=' . $order['businessBelong'] . '&formName=采购合同审批');
        }
    }

    /**
     * 申请变更添加方法
     * author can
     * 2011-1-12
     */
    function c_change()
    {
        try {
            $id = $this->service->change_d($_POST ['contract']);

            echo "<script>this.location='controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }

    /**签收订单
     */
    function c_signOrder()
    {
        $id = $this->service->signOrder_d($_POST ['contract']);
        if ($id) {
            msgGo('签收成功', "?model=purchase_contract_purchasecontract&action=toSignList");
        } else {
            msgGo('签收失败', "?model=purchase_contract_purchasecontract&action=toSignList");
        }
    }

    /**
     * 针对采购合同的搜索方法
     * @return 列表
     */
    function c_searchFun()
    {
        $val = $_GET ['searchVal'];
        $service = $this->service;
        $service->searchArr = array('applyNumb', $val);
        $rows = $service->listBySqlId();

    }

    /**
     * 跳转到签收列表
     */
    function c_toSignList()
    {
        $this->display('list-sign');

    }

    /**
     * 跳转到采购订单信息高级搜索页面
     *
     */
    function c_toAllList()
    {
        $beginDate = date("Y-m") . "-01";
        $this->assign("beginDate", $beginDate);
        $this->display('equipment-search');
//		$this->display('alllist');
    }

    /**
     * 跳转到审批中的采购订单信息页面
     *
     */
    function c_toAuditList()
    {
        $this->display('audit-list');
    }

    /**
     * 跳转到采购单查看列表
     */
    function c_toOrderListList()
    {
        $this->assignFunc($_GET[$this->objName]);
        $this->display('equipment-list');

    }

    /**
     * 跳转到采购单查看列表
     */
    function c_toExecutequList()
    {
//		$this->assignFunc( $_GET[$this->objName]);
        $this->display('executequ-list');

    }

    /**
     * 跳转到采购单明细列表（供应商评估）
     */
    function c_toViewEquList()
    {
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : null;
        $assessType = isset ($_GET ['assessType']) ? $_GET ['assessType'] : null;
        $assesYear = isset ($_GET ['assesYear']) ? $_GET ['assesYear'] : null;
        $assesQuarter = isset ($_GET ['assesQuarter']) ? $_GET ['assesQuarter'] : null;
        $this->assign('suppId', $suppId);
        $this->assign('assessType', $assessType);
        $this->assign('assesYear', $assesYear);
        $this->assign('assesQuarter', $assesQuarter);
        $this->display('purchequ-list');

    }

    /**
     * 采购信息列表pagejson
     */
    function c_executEquJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr ['sendUserId'] = $_SESSION ['USER_ID'];
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->asc = true;
        $service->groupBy = 'p.id';
        $service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
        $rows = $service->pageBySqlId('executEquList');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExecutequRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 采购信息列表pagejson
     */
    function c_purchEquJson()
    {
        $service = $this->service;
        $suppId = isset($_POST['suppId']) ? $_POST['suppId'] : "";
        $assessType = isset($_POST['assessType']) ? $_POST['assessType'] : "";
        $assesYear = isset($_POST['assesYear']) ? $_POST['assesYear'] : "";
        $assesQuarter = isset($_POST['assesQuarter']) ? $_POST['assesQuarter'] : "";
        $service->searchArr['csuppId'] = $suppId;
        if ($assessType == "gysjd") {//季度查询
            $orderMonth = "";
            switch ($assesQuarter) {
                case '1':
                    $orderMonth = $assesYear . '-01,' . $assesYear . '-02,' . $assesYear . '-03';
                    break;
                case '2':
                    $orderMonth = $assesYear . '-04,' . $assesYear . '-05,' . $assesYear . '-06';
                    break;
                case '3':
                    $orderMonth = $assesYear . '-07,' . $assesYear . '-08,' . $assesYear . '-09';
                    break;
                case '4':
                    $orderMonth = $assesYear . '-10,' . $assesYear . '-11,' . $assesYear . '-12';
                    break;
                default:
                    "";
            };
            $service->searchArr['orderMonth'] = $orderMonth;
        } else if ($assessType == "gysnd") {//年度查询
            $service->searchArr['orderTime'] = $assesYear;
        }
        $service->groupBy = 'p.id';
        $rows = $service->pageBySqlId('executEquList');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExecutequRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 采购信息列表pagejson
     */
    function c_orderEquJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->asc = true;
        $service->groupBy = 'p.id';
        $rows = $service->listBySqlId('exportItem');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealEquRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到签收页面
     */
    function c_toSign()
    {
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $equs = $service->getEquipments_d($_GET ['id']);
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], true));
        $this->assign('list', $service->chageContractList_s($equs));
        $this->showDatadicts(array('billingType' => 'FPLX'), $rows ['billingType']);
        $this->showDatadicts(array('paymetType' => 'fkfs'), $rows ['paymetType']);
        $this->showDatadicts(array('paymentCondition' => 'FKTJ'), $rows ['paymentCondition']);
        $length = count($equs); //获取物料数组的长度
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        $this->display('sign');

    }

    /**
     * 跳转到订单申请盖章页面
     *
     */
    function c_toApplySeal()
    {
        $this->assign("serviceId", $_GET ['id']);
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//盖章类型
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));

        //当前盖章申请人
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);
        $this->assign('applyDate', date("Y-m-d"));
        $this->display('seal');
    }

    /**##########################OY版合同状态位的方法##############################*/

    /*
	 * @desription 启动采购合同
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 下午03:14:46
	 */
    function c_beginPurchaseContract()
    {
        //启动成功，则合同状态改变
        $contractId = $_GET ['id'];
        $state = 2;
        $condiction = array('id' => $contractId);
        $flag = $this->service->updateField($condiction, 'state', $state);
        if ($flag) {
            //启动成功后，页面跳转到“执行中的采购合同页”
            msgGo("启动采购合同成功!", "?model=purchase_contract_purchasecontract&action=myUnExecuteList");

            //echo "<script>alert('启动成功');location='?model=purchase_contract_purchasecontract&action=myExecutionList'</script>";
        }

    }

    /**
     * 跳转到采购订单中止页面
     *
     */
    function c_toClose()
    {
        $hwapplyNumb = isset($_GET['hwapplyNumb']) ? $_GET['hwapplyNumb'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        //判断订单是否已有物料入库或者订单是否已申请付款
        $flag = $this->service->isPayApply_d($id);
        $this->assign('flag', $flag);
        $rows = $this->service->get_d($id);
        $this->assign('hwapplyNumb', $rows['hwapplyNumb']);
        $this->assign('closeMan', $_SESSION ['USERNAME']);
        $this->assign('closeManId', $_SESSION ['USER_ID']);
        $this->assign('closeDate', date("Y-m-d"));
        $this->assign('id', $id);
        $this->display('close');
    }

    /**
     * 跳转到采购订单中止页面
     *
     */
    function c_toCloseOrder()
    {
        $hwapplyNumb = isset($_GET['hwapplyNumb']) ? $_GET['hwapplyNumb'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $rows = $this->service->get_d($id);
        $this->assign('hwapplyNumb', $rows['hwapplyNumb']);
        $this->assign('closeMan', $_SESSION ['USERNAME']);
        $this->assign('closeManId', $_SESSION ['USER_ID']);
        $this->assign('closeDate', date("Y-m-d"));
        $this->assign('id', $id);
        $this->display('order-close');
    }

    /*
	 * @desription 关闭采购订单
	 */
    function c_close()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $flag = $this->service->edit_d($contract, true);
        if ($flag) {
            if ($contract['closeType'] == 1) {//订单结束
                succ_show('controller/purchase/contract/ewf_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=采购订单中止审批');
            } else if ($contract['closeType'] == 2) {//删除订单
                succ_show('controller/purchase/contract/ewf_del_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=采购订单中止审批');
            }
        }
//		msg("提交成功","?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");

    }

    /*
	 * @desription 关闭采购订单
	 */
    function c_closeOrder()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $flag = $this->service->edit_d($contract, true);
        if ($flag) {
            succ_show('controller/purchase/contract/ewf_order_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=采购订单关闭审批');
        }
//		msg("提交成功","?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");

    }

    /*
	 * @desription 采购合同-完成
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 下午03:35:37
	 */
    function c_finishPurchaseContract()
    {
        $id = isset ($_GET ["id"]) ? $_GET ["id"] : exit ();
        $val = $this->service->end_d($id);
        if ($val == 1) {
            msgGo("操作成功");
        } else if ($val == 2) {
            msgGo("存在未完成业务，完成失败");
        } else {
            msgGo("操作失败！！可能是服务器错误，请稍后再试");
        }

    }

    /*********************************************以上是“我的采购-采购合同”************************************************************/

    /**#########################################以下是“采购合同”的审批工作流#########################################*/

    /*
	 * @desription 审批工作流里的查看页面
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午02:14:18
	 */
    function c_toViewVontract()
    {
//		$this->permCheck ();//安全校验
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        $equs = $service->getEquipments_d($_GET ['id']);
        if ($rows['instruction'] == "" || $rows['remark'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//获取采购任务的备注与采购说明
            $rows['instruction'] = $infoRow['instruction'] . "\n" . $rows['instruction'];
            $rows['remark'] = $infoRow['remark'] . "\n" . $rows['remark'];
        }
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'stock_fillup_fillup');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }
        if ($rows['originalId'] != "") {
            $skey = $this->md5Row($rows['originalId'], 'purchase_contract_purchasecontract');
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->assign('list', $service->addContractEquList_s($newEqus));
        $equDao = new model_purchase_contract_equipment ();

        //获取采购申请信息
        $planEquRows = $equDao->getPlanEquForRead_d($_GET['id']);
        if (is_array($planEquRows)) {
            foreach ($planEquRows as $plankey => $planval) {
                if ($planval['sourceID'] != "") {
                    switch ($planval['purchType']) {
                        case "oa_sale_order":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_order_order');
                            break;
                        case "oa_sale_lease":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'contract_rental_rentalcontract');
                            break;
                        case "oa_sale_service":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'engineering_serviceContract_serviceContract');
                            break;
                        case "oa_sale_rdproject":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'rdproject_yxrdproject_rdproject');
                            break;
                        case "stock":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'stock_fillup_fillup');
                            break;
                        case "oa_borrow_borrow":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_borrow_borrow');
                            break;
                        case "oa_present_present":
                            $planEquRows[$plankey]['skey_'] = $this->md5Row($planval['sourceID'], 'projectmanagent_present_present');
                            break;
                    }
                }
            }

        }
        $this->assign('planList', $equDao->showPlanEquListForOrder($planEquRows));

        //获取供应商信息
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);

        //获取询价产品清单
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //显示报价详情

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }

        //获取订单中物料的总数量和协议价格
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//没有当前数量
            $materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

            $uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow, $materialequRow, $materialequRow1);
        }

        $this->show->assign("listSee", $this->service->showSupp_s($suppRows, $uniqueEquRows));
        $this->assign('suppNumb', count($suppRows));
        //发票类型
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //付款类型
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //付款条件
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //对签约状态的值进行转换
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //开户银行
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('isAudit', '1');
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "是");
        } else {
            $this->assign('isNeedStamp', "否");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "是");
        } else {
            $this->assign('isStamp', "否");
        }

        $this->display('order-view');
    }

    /*
	 * @desription 审批工作流里的查看页面
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午02:14:18
	 */
    function c_toViewChange()
    {
        $service = $this->service;
        $returnObj = $this->objName;
        $id = isset ($_GET ['id']) ? $_GET ['id'] : $_GET ['pjId'];
        $rows = $this->service->get_d($id);
        $equs = $service->getEquipments_d($id);
        if ($rows['instruction'] == "" || $rows['remark'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//获取采购任务的备注与采购说明
            $rows['instruction'] = $infoRow['instruction'] . "\n" . $rows['instruction'];
            $rows['remark'] = $infoRow['remark'] . "\n" . $rows['remark'];
        }
        $applicant = $_SESSION ['USERNAME'];
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('applicant', $applicant);
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }
        $this->assign("file", $this->service->getFilesByObjId($id, false));

        $this->assign('list', $service->addContractEquList_s($newEqus));
        //发票类型
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //付款类型
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //付款条件
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //开户银行
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);

        //对签约状态的值进行转换
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "是");
        } else {
            $this->assign('isNeedStamp', "否");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "是");
        } else {
            $this->assign('isStamp', "否");
        }

        $this->display('change-view');
    }

    /**查看Tab页
     *
     *
     */
    function c_approViewTab()
    {
        $this->permCheck();//安全校验
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //获取采购询价单ID
        $inquId = $this->service->getInquiryId_d($id);
        /*//根据采购订单Id，获取采购询价物料id
		$condition="basicId=".$id;
		$inquEquId=$this->service->get_table_fields('oa_purch_apply_equ',$condition,'inquiryEquId');
		if($inquEquId){     //获取采购询价单ID
			$inquCondition="id=".$inquEquId;
			$inquId=$this->service->get_table_fields('oa_purch_inquiry_equ',$inquCondition,'parentId');
			$inquSkey=$this->md5Row($inquId,'purchase_inquiry_inquirysheet');
		}else{
			$inquId="";
			$inquSkey="";
		}*/
        $this->assign('inquiryId', $inquId);
        //	$this->assign('inquSkey',$inquSkey);
        $this->display('appro-view-tab');

    }

    /*
	 * @desription 审核列表Tab页
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午02:54:33
	 */
    function c_toMyApprovalTab()
    {
        $this->display('approval-index');
    }

    /*
	 * @desription 待审批采购合同列表
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午03:08:34
	 */
    function c_pcApprovalNo()
    {
        $service = $this->service;
        $service->getParam($_GET); //设置前台获取的参数信息
        $service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
        $service->searchArr ['Flag'] = 0;
        $service->searchArr ['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $rows = $service->pageBySqlId('sql_examine');
        $this->display('approval-No');
    }

    /*
	 * @desription 已审批采购合同列表
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午03:09:05
	 */
    function c_pcApprovalYes()
    {
        $this->display('approval-Yes');
    }

    /**
     * 在执行的订单(新列表，与之前的在执行的采购订单列表页面类似)
     *
     */
    function c_toExecutionList()
    {
        $this->display('managexe-list');
    }

    /**
     * 在执行的订单pagejson
     */
    function c_managPageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->asc = true;
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId('manageExeList');
        $sumrows = $service->listBySqlId('manageExeList');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExeRows_d($rows, $sumrows);
        $arr = array();
        $arr ['collection'] = $newRows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 执行完的订单(新列表，与之前的已关闭的采购订单列表页面类似)
     *
     */
    function c_toCloseList()
    {
        $this->display('managend-list');
    }

    /**
     * 在执行的订单pagejson
     */
    function c_managEndPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->asc = true;
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId("manageEndList");
        $sumrows = $service->listBySqlId('manageEndList');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealEndRows_d($rows, $sumrows);
        $arr = array();
        $arr ['collection'] = $newRows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /*
	 * @desription 获取分页数据转成JSON--待审批
	 * @author qian
	 * @date 2011-1-6 下午03:22:48
	 */
    function c_pageJsonNo()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
        $service->searchArr ['Flag'] = 0;
        $service->searchArr ['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $rows = $service->pageBySqlId('sql_examine');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * @desription 获取分页数据转成JSON--已审批
     * @author qian
     * @date 2011-1-6 下午03:23:39
     */
    function c_pageJsonYes()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
        $service->searchArr ['Flag'] = 1;
        $service->searchArr ['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId('sql_examine2');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**#########################################以上是“采购合同”的审批工作流#########################################*/

    /*******************************************Ajax与JSON方法********************************************/
    /**
     * Enter 对采购合同编号进行统一改天验证
     *
     * @param 采购合同号
     * @return return_type
     */
    function c_ajaxContractNumb()
    {
        $service = $this->service;
        $hwapplyNumb = isset ($_GET ['hwapplyNumb']) ? $_GET ['hwapplyNumb'] : false;
        $searchArr = array("ajaxContractNumb" => $hwapplyNumb);

        $isRepeat = $service->isRepeat($searchArr, "");
        if ($isRepeat) {
            echo "0";
        } else {
            echo "1";
        }
    }

    /**更新合同的在途数量
     *author can
     *2011-6-16
     */
    function c_updateOnWayNumb()
    {
        if (!empty ($_GET ['spid'])) {
            //审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {

            //防止重复刷新,审批后的跳转页面
            echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
        }
    }

    /**中止订单审批后处理方法
     *author can
     */
    function c_dealClose()
    {
        if (!empty ($_GET ['spid'])) {
            //审批流回调方法
            $this->service->workflowCallBack_close($_GET['spid']);
        }
        echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * 添加入库单时，动态添加从表模板
     */
    function c_getItemList()
    {
        $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($orderId);  //根据订单ID获取物料信息
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $equRows = $productinfoDao->k3CodeFormatter_d($equRows);
        $list = $equDao->showAddList($equRows);       //获取物料模板
        echo $list;
    }

    /**
     * 添加采购发票时，动态添加从表
     */
    function c_getDetail()
    {
        $orderId = isset($_POST['objId']) ? $_POST['objId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusForInvpurchase($orderId);  //根据订单ID获取物料信息
        $list = $equDao->showInvpurchaseList($equRows, $_POST);       //获取物料模板
        echo $list;
    }

    /************************付款申请下拉采购订单表格**********************/
    /**
     * 获取分页数据转成Json
     */
    function c_purDetailPageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息


        //$service->asc = false;
        $rows = $service->page_d('payapply_list');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 单独附件上传页面
     */
    function c_toUploadFile()
    {
        $this->assign("serviceId", $_GET ['id']);
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->display('uploadfile');
    }

    /**
     * 单独附件上传方法
     */
    function c_uploadfile()
    {
        $service = $_POST ['contract'];
        $id = $this->service->uploadfile_d($service);
        if ($id) {
            msg('保存成功！');
        }
    }

    /**
     * 盖章申请
     */
    function c_seal()
    {
        $service = $_POST ['contract'];
        $id = $this->service->seal_d($service);
        if ($id) {
            msg('提交成功！');
        }
    }

    /**根据采购订单的物料id.查看物料的收料情况
     *
     */
    function c_itemView()
    {
        $orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
        $contEquDao = new model_purchase_contract_equipment();
        $purchRows = $this->service->get_d($orderId);
        $contEquRow = $contEquDao->getEqusByContractId($orderId);
        //根据采购订单ID,获取物料入库信息
//	 	$stockRows=$this->service->getStockInfo_d($orderId);
        $this->assign('list', $contEquDao->showEquList($contEquRow));
        $this->assign('orderCode', $purchRows['hwapplyNumb']);
        $this->display('stock-list');
    }

    /**
     *
     * 导出物料信息EXCEL
     */
    function c_toExportExcel()
    {
        $isPerson = isset($_GET['exportType']) ? $_GET['exportType'] : 0;
        if ($isPerson) {  //判断是个人的单据导出 还是全部单据导出
            $this->service->searchArr ['sendUserId'] = $_SESSION ['USER_ID'];
        }
        $rows = $_POST['purchasecontract'];
        if ($rows ['beginTime']) {
            $this->service->searchArr ['beginTime'] = $rows ['beginTime'];
        }
        if ($rows ['endTime']) {
            $this->service->searchArr ['endTime'] = $rows ['endTime'];
        }
        if ($rows ['suppName']) {
            $this->service->searchArr ['suppName'] = $rows ['suppName'];
        }
        if ($rows ['ExaStatus']) {
            $exastatus = implode(',', $rows ['ExaStatus']);
            $this->service->searchArr ['ExaStatus'] = $exastatus;
        } else {
            $this->service->searchArr ['ExaStatus'] = '部门审批,打回,完成';
        }
        if ($rows ['productId']) {
            $this->service->searchArr ['productId'] = $rows ['productId'];
        }
        if ($rows ['applyDeptName']) {
            $this->service->searchArr ['applyDeptName'] = $rows ['applyDeptName'];
        }
        $this->service->asc = true;
        $this->service->groupBy = 'c.createTime';
        $dataArr = $this->service->listBySqlId("exportItem");
        $dao = new model_purchase_contract_purchaseUtil ();
        foreach ($dataArr as $key => $val) {
            switch ($val['ExaStatus']) {
                case '部门审批':
                    $dataArr[$key]['ExaStatus'] = "审批中";
                    break;
                case '打回':
                    $dataArr[$key]['ExaStatus'] = "不通过";
                    break;
                case '完成':
                    $dataArr[$key]['ExaStatus'] = "通过";
                    break;
            }
        }
        return $dao->exportItemExcel($dataArr);
    }

    //跳转到导出订单物料数据过滤页面
    function c_toExporttFilter()
    {
        if (!$this->service->this_limit['导出订单物料信息']) {
            echo "<script>alert('没有权限进行操作!');window.close();</script>";
            exit();
        }
        $exportType = isset($_GET['exportType']) ? $_GET['exportType'] : ""; //导出类型，区分是业务员还是管理者
        $this->assign('exportType', $exportType);
        $this->display('exportexcel');
    }

    //根据订单ID获取物料信息模板
    function c_getItemModel()
    {
        $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($orderId);  //根据订单ID获取物料信息
        $list = $equDao->showItemModel($equRows);       //获取物料模板
        echo $list;
    }

    //根据订单ID获取付款物料信息模板
    function c_getPayStr()
    {
        $orderId = isset($_POST['id']) ? $_POST['id'] : "";
        $suppId = isset($_POST['suppId']) ? $_POST['suppId'] : "";
        $list = $this->service->payEquList_d($orderId, $suppId);
        echo $list;
    }

    //根据订单ID获取付款物料信息模板
    function c_getPayEditStr()
    {
        $orderId = isset($_POST['id']) ? $_POST['id'] : "";
        $list = $this->service->payEquEditList_d($orderId);
        echo $list;
    }

    /**
     * 导出采购订单
     *
     */
    function c_exportPurchaseOrder()
    {
        if (!$this->service->this_limit['导出采购订单']) {
            echo "<script>alert('没有权限进行操作!');history.back(-1)</script>";
            exit();
        }
        $this->permCheck();//安全校验
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        //获取主表数据
        $rows = $this->service->get_d($id);
        $equRows = $this->service->getEquipments_d($id);  //根据订单ID获取物料信息

        $dao = new model_purchase_contract_purchaseUtil ();
        return $dao->exportPurchaseOrder($rows, $equRows); //导出Excel
    }

    /**
     * 跳转到关联订单的单据信息查看页面（审批通过后）
     *
     */
    function c_toViewRelationBill()
    {
        $this->permCheck();//安全校验
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $readType = isset($_GET['readType']) ? $_GET['readType'] : "";
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $applyDeptLabel = '申请部门';
        $applyManLabel = '申请人';

        //对签约状态的值进行转换
        $signStatus = $this->service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        $equs = $this->service->getEquipments_d($_GET ['id']);

        $equDao = new model_purchase_contract_equipment();
        //获取采购申请信息
        $planEquRows = $equDao->getPlanEqu_d($id);
        if ($planEquRows) {
            if($planEquRows[0]["purchType"] == "assets" || $planEquRows[0]["purchType"] == "oa_asset_purchase_apply"){
                $applyDeptLabel = '仓库';
                $applyManLabel = '仓管员';
            }
        }
        $this->assign('planList', $equDao->showPlanEquList($planEquRows));

        //获取采购询价信息
        $inquiryEquRow = $equDao->getInquiryEqu_d($id);
        $this->assign('inquiryList', $equDao->showInquiryList($inquiryEquRow));

        //获取采购订单物料唯一信息,显示物料历史价格信息
        $groupByEqus = $this->service->getUniqueEqus_d($_GET ['id']);
        $this->assign('historyList', $equDao->showHistoryList($groupByEqus));

        //获取付款申请信息
        $payApplyDao = new model_finance_payablesapply_payablesapply();
        $payApplyRow = $payApplyDao->getApplyByPur_d($id, 'YFRK-01');
        $this->assign('payApplyList', $equDao->showPayApplyList($payApplyRow));

        //获取付款记录信息
        $payedDao = new model_finance_payables_payables();
        $payedRow = $payedDao->getPayedByPur_d($id, 'YFRK-01');
        $this->assign('payedList', $equDao->showPayedList($payedRow));

        //获取发票信息
        $invoiceDao = new model_finance_invpurchase_invpurchase();
        $invoiceRow = $invoiceDao->getInvByPur_d($id);
        $this->assign('invoiceList', $equDao->showInvoiceList($invoiceRow));
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'stock_fillup_fillup');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }

        //判断是否隐藏关闭按钮
        if (isset($_GET['hideBtn'])) {
            $this->assign('hideBtn', 1);
        } else {
            $this->assign('hideBtn', 0);
        }
        //获取供应商信息
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);
        //获取询价产品清单
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //显示报价详情

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }
        //获取订单中物料的总数量和协议价格
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//没有当前数量
            $materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

            $uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow, $materialequRow, $materialequRow1);
        }

        $this->show->assign("listSee", $this->service->showSupp_s($suppRows, $uniqueEquRows));
        if (is_array($suppRows)) {
            $suppNumb = count($suppRows);
        } else {
            $suppNumb = 0;
        }
        $this->assign('suppNumb', $suppNumb);

        $this->assign('readType', $readType);//区分是查看还审批，隐藏关闭按钮
        $this->assign('list', $this->service->addContractEquList_s($newEqus));

        $this->assign('applyDeptLabel', $applyDeptLabel);
        $this->assign('applyManLabel', $applyManLabel);
        $this->display('relationbill-view');
    }

    /**
     * 跳转到关联订单的单据信息查看页面（审批前）
     *
     */
    function c_toReadRelationBill()
    {
        $this->permCheck();//安全校验
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $readType = isset($_GET['readType']) ? $_GET['readType'] : "";
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $applyDeptLabel = '申请部门';
        $applyManLabel = '申请人';

        //对签约状态的值进行转换
        $signStatus = $this->service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        $equs = $this->service->getEquipments_d($_GET ['id']);

        $equDao = new model_purchase_contract_equipment();
        //获取采购申请信息
        $planEquRows = $equDao->getPlanEqu_d($id);
        if ($planEquRows) {
            if($planEquRows[0]["purchType"] == "assets" || $planEquRows[0]["purchType"] == "oa_asset_purchase_apply"){
                $applyDeptLabel = '仓库';
                $applyManLabel = '仓管员';
            }
        }
        $this->assign('planList', $equDao->showPlanEquList($planEquRows));

        //获取采购询价信息
        $inquiryEquRow = $equDao->getInquiryEqu_d($id);
        $this->assign('inquiryList', $equDao->showInquiryList($inquiryEquRow));

        //获取采购订单物料唯一信息,显示物料历史价格信息
        $groupByEqus = $this->service->getUniqueEqus_d($_GET ['id']);
        $this->assign('historyList', $equDao->showHistoryList($groupByEqus));
        $newEqus = array();
        foreach ($equs as $key => $val) {
            if ($val['sourceID'] != "") {
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_order_order');
                        break;
                    case "oa_sale_lease":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'contract_rental_rentalcontract');
                        break;
                    case "oa_sale_service":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'engineering_serviceContract_serviceContract');
                        break;
                    case "oa_sale_rdproject":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'rdproject_yxrdproject_rdproject');
                        break;
                    case "stock":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'stock_fillup_fillup');
                        break;
                    case "oa_borrow_borrow":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_borrow_borrow');
                        break;
                    case "oa_present_present":
                        $val['skey_'] = $this->md5Row($val['sourceID'], 'projectmanagent_present_present');
                        break;
                }
            }
            array_push($newEqus, $val);
        }
        $this->assign('readType', $readType);//区分是查看还审批，隐藏关闭按钮
        $this->assign('list', $this->service->addContractEquList_s($newEqus));
        //获取供应商信息
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);
        //获取询价产品清单
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //显示报价详情

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }
        //获取订单中物料的总数量和协议价格
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//加上当前购买数量
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//没有当前数量
            $materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

            $uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow, $materialequRow, $materialequRow1);
        }

        $this->show->assign("listSee", $this->service->showSupp_s($suppRows, $uniqueEquRows));
        if (is_array($suppRows)) {
            $suppNumb = count($suppRows);
        } else {
            $suppNumb = 0;
        }
        $this->assign('applyDeptLabel', $applyDeptLabel);
        $this->assign('applyManLabel', $applyManLabel);
        $this->assign('suppNumb', $suppNumb);
        $this->display('relationbill-read');
    }

    /**
     * 采购订单报表
     */
    function c_toListInfo()
    {
        $logic = isset($_GET["logic"]) ? $_GET["logic"] : "and";
        $field = isset($_GET["field"]) ? $_GET["field"] : "createTime";
        $relation = isset($_GET["relation"]) ? $_GET["relation"] : "greater";
        $last = strtotime("-1 month", time());
        $last_lastday = date("Y-m-t", $last);//上个月最后一天
        $values = isset($_GET["values"]) ? $_GET["values"] : $last_lastday;
        $this->assign("logic", $logic);
        $this->assign("field", $field);
        $this->assign("relation", $relation);
        $this->assign("values", $values);
        $this->display('listinfo');
    }

    /**
     *采购订单报表 高级搜索
     */
    function c_listinfoSearch()
    {
        $logic = isset($_GET['logic']) ? $_GET['logic'] : "";
        $field = isset($_GET['field']) ? $_GET['field'] : "";
        $relation = isset($_GET['relation']) ? $_GET['relation'] : "";
        $values = isset($_GET['values']) ? $_GET['values'] : "";
        $this->assign("logic", $logic);
        $this->assign("field", $field);
        $this->assign("relation", $relation);
        $this->assign("values", $values);
        $this->view('listinfo-search');
    }

    /**
     *判断是否已提交盖章申请
     *
     */
    function c_isApplySeal()
    {
        $id = $_POST['id'];
        $row = $this->service->get_d($id);
        if ($row['isStamp'] != 1 && $row['isNeedStamp'] != 1) {//未申请盖章
            echo 1;
        } else if ($row['isStamp'] == 0 && $row['isNeedStamp'] == 1) {//已申请盖章，但未盖章
            echo 0;
        } else if ($row['isStamp'] == 1 && $row['isNeedStamp'] == 1) {//已申请盖章，已盖章
            echo 2;
        }
    }


    /************************付款申请下拉采购订单表格**********************/

    /************************ 采购付款申请可申请验证 ********************/
    /**
     * 验证方法
     */
    function c_canPayapply()
    {
        $id = $_POST['id'];
        $rs = $this->service->canPayapply_d($id);
        echo $rs;
        exit();
    }

    /**
     * 退款申请验证
     */
    function c_canPayapplyBack()
    {
        $id = $_POST['id'];
        $rs = $this->service->canPayapplyBack_d($id);
        echo $rs;
        exit();
    }

    /************************ 采购付款申请可申请验证 ********************/

    /**
     * 采购订单统计报表
     */
    function c_toStatistics()
    {
        $logic = isset($_GET["logic"]) ? $_GET["logic"] : "and";
        $field = isset($_GET["field"]) ? $_GET["field"] : "createTime";
        $relation = isset($_GET["relation"]) ? $_GET["relation"] : "greater";
        $lastMonth = strtotime("-1 month", time());
        $lastMonth_lastDay = date("Y-m-t", $lastMonth);//上个月最后一天
        $values = isset($_GET["values"]) ? $_GET["values"] : $lastMonth_lastDay;
        $this->assign("logic", $logic);
        $this->assign("field", $field);
        $this->assign("relation", $relation);
        $this->assign("values", $values);
        $this->view('statistics');
    }

    /**
     *采购订单报表 高级搜索
     */
    function c_statisticsSearch()
    {
        $beginDate = isset($_GET['beginDate']) ? $_GET['beginDate'] : "";
        $logic = isset($_GET['logic']) ? $_GET['logic'] : "";
        $field = isset($_GET['field']) ? $_GET['field'] : "";
        $relation = isset($_GET['relation']) ? $_GET['relation'] : "";
        $values = isset($_GET['values']) ? $_GET['values'] : "";
        $this->assign("beginDate", $beginDate);
        if (!empty($logic)) {//判断是否传入查询条件
            $logicArr = explode(',', $logic);
            $fieldArr = explode(',', $field);
            $relationArr = explode(',', $relation);
            $valuesArr = explode(',', $values);
            $list = $this->service->selectList($logicArr, $fieldArr, $relationArr, $valuesArr);//查询条件模板
            $this->assign('list', $list);
        } else {
            $this->assign('list', "");
        }
        if (!empty($logic)) {
            $number = count($logicArr) - 1;
        } else {
            $number = 0;
        }
        $this->assign('invnumber', $number);
        $this->view('statistics-search');
    }

    /**
     * 采购订单取消审批
     */
    function c_cancelApproval()
    {
        $id = isset ($_GET ['id']) ? $_GET ['id'] : null;
        $orderRows = $this->service->get_d($id);
        if ($orderRows['isApplyPay'] == 1) {//采购订单审批(含付款申请)
            echo "<script>parent.location='controller/purchase/contract/ewf_index_payedit.php?actTo=delWork&billId='+$id+'&examCode=oa_purch_apply_basic&formName=采购订单审批(含付款申请)'</script>";
        } else {//采购合同审批
            echo "<script>parent.location='controller/purchase/contract/ewf_index.php?actTo=delWork&billId='+$id+'&examCode=oa_purch_apply_basic'</script>";
        }
    }

    /**
     * 检查订单变更表单的总金额是否在可变范围内
     */
    function c_chkChangeForm(){
        $chkResult = '';
        $id = isset($_POST ['oldId'])? $_POST ['oldId'] : '';
        $allMoney = isset($_POST ['allMoney'])? $_POST ['allMoney'] : 0;

        //获取已申请金额，如果已申请完成，返回不能继续申请，否则进入付款期限判断
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payedMoney = $payablesapplyDao->getApplyMoneyByPur_d($id, 'YFRK-01');

        $payedMoney = bcmul($payedMoney,1);
        $allMoney = bcmul($allMoney,1);
        $chkResult =  ($payedMoney > $allMoney)? 'No' : 'Yes';
        $chkResultMsg =  ($payedMoney > $allMoney)? '订单金额不得小于已申请付款金额。' : '';

        $arr = array();
        $arr ['oldId'] = $id;
        $arr ['allMoney'] = $allMoney;
        $arr ['payedMoney'] = $payedMoney;
        $arr ['chkResult'] = $chkResult;
        $arr ['msg'] = $chkResultMsg;
        echo util_jsonUtil::encode($arr);
    }
}