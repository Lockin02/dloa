<?php

/**
 * @description: �ɹ���ͬ
 * @date 2010-12-29 ����08:57:00
 */
class controller_purchase_contract_purchasecontract extends controller_base_action
{
    /*
	 * @desription ���캯��
	 * @author qian
	 * @date 2010-12-29 ����08:58:39
	 */
    function __construct()
    {
        $this->objName = "purchasecontract";
        $this->objPath = "purchase_contract";
        $this->datadictFieldArr = array("billingType", "paymentType", "paymentCondition");
        parent::__construct();
    }

    /*******************************************��ͨAction����********************************************/

    /*
	 * @desription ��ת�����ɲɹ���ͬҳ��
	 * @param tags
	 * @author qian
	 * @date 2010-12-31 ����03:38:23
	 */
    function c_toAddPurchaseContract()
    {
        $this->permCheck($_GET['inquiryId'], 'purchase_inquiry_inquirysheet');//��ȫУ��
        $service = $this->service;
        $inquiryId = $_GET ['inquiryId']; //ѯ�۵�ID
        $suppId = $_GET ['suppId']; //ָ����Ӧ����Ӫ���ID


        //���ݲɹ�ѯ�۵���IDֵ��ȡ�豸������
        $inquiryArr = $service->getInquirySheet_d($inquiryId);

        //�õ���Ӧ�̵ı���
        $service->searchArr = array("inquiryId" => $inquiryId, "suppId" => $suppId);
        $suppArr = $service->listBySqlId("get_suppInfo");
        //��ȡ��ע��Ϣ
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //��ȡ��Ӧ��������Ϣ
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($inquiryArr ['supplier']['id']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //��Ӧ�̵�����
        foreach ($inquiryArr ['supplier'] as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //��������
            $this->assign('paymentConditionName', "");
        }

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppId);
        $this->assign('inquiryId', $inquiryId);
        $this->assign('dateHope', $suppArr['0']['arrivalDate']);

        $this->display('add');
    }

    /**
     * ���Ųɹ�ѯ�۵�����һ�Ųɹ�����
     *
     */
    function c_toAddByMore()
    {
        $service = $this->service;
        $inquiryId = $_GET ['inquiryId']; //ѯ�۵�ID
        $inquiryIdArr = explode(',', $inquiryId);
        $suppId = $_GET ['suppId']; //ָ����Ӧ����Ӫ���ID


        //���ݲɹ�ѯ�۵���IDֵ��ȡ�豸������
        $inquiryArr = $service->getInquirySheet_d($inquiryIdArr[0]);

        //�õ���Ӧ�̵ı���
        $service->searchArr = array("inquiryIdArr" => $inquiryIdArr, "suppId" => $suppId);
        $suppArr = $service->listBySqlId("get_suppInfo");
        //�õ���Ӧ�̵ı�����Сʱ��
        $minDate = $service->getMinHopeDate_d($inquiryIdArr, $suppId);
        //��ȡ��ע��Ϣ
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //��ȡ��Ӧ��������Ϣ
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($inquiryArr ['supplier']['id']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //��Ӧ�̵�����
        foreach ($inquiryArr ['supplier'] as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //��������
            $this->assign('paymentConditionName', "");
        }

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppId);
        $this->assign('inquiryId', $inquiryId);
        $this->assign('dateHope', $minDate);

        $this->display('add');
    }

    /**
     * ��ݲɹ�����
     *
     */
//	function c_toAddOrder() {
//		$addType=isset($_GET['addType'])?$_GET['addType']:"";    //�������ڲ˵���ӻ����ڲɹ������б������
//
//		//���������ֵ��ֵ
//		$this->showDatadicts ( array ('invoiceType' => 'FPLX' ) ); //��Ʊ����
//		$this->showDatadicts ( array ('paymentType' => 'fkfs' ) ); //���ʽ
//		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //��������
//
//
//		$this->assign ( 'sendName', $_SESSION ['USERNAME'] ); //�����
//		$this->assign ( 'sendUserId', $_SESSION ['USER_ID'] );
//		$this->assign('addType',$addType);
//		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) ); //˰��
//
//			//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//��������
//
//		$this->display ( 'order-add' );
//	}
    function c_toAddOrder()
    {
        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('formDate', date("Y-m-d"));

        $this->display('inquiry-add');
    }

    /**
     * ��ݲɹ�����,ͨ���ɹ�ѯ�����ϻ���
     *
     */
    function c_toAddByInquiryEqu()
    {
        $service = $this->service;
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $this->service->getParam($_GET);

        //�õ���Ӧ�̵ı���
        $service->searchArr = array("inquiryIdEquArr" => $idsArry);
        $suppArr = $service->listBySqlId("get_suppInfo");
        $flibraryDao = new model_supplierManage_formal_flibrary();
        $supplier = $flibraryDao->get_d($suppArr[0]['suppId']);
        //�õ���Ӧ�̵ı�����Сʱ��
        $minDate = $service->getMinHopeDateByEqu_d($idsArry);
//		//��ȡ��ע��Ϣ
        $remarkRow = $this->service->getTaskRemarkByInquiry_d($suppArr);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $service->showEquipmentList($suppArr));

        //��ȡ��Ӧ��������Ϣ
        $bankDao = new model_supplierManage_formal_bankinfo ();
        $bankRows = $bankDao->getBankInfoBySuppId($suppArr [0]['suppId']);
        if (is_array($bankRows)) {
            $this->assign('suppAccount', $bankRows ['0']['accountNum']);
            $this->assign('suppBankName', $bankRows ['0']['bankName']);
        } else {
            $this->assign('suppAccount', "");
            $this->assign('suppBankName', "");
        }

        //��Ӧ�̵�����
        foreach ($supplier as $key => $val) {
            $this->assign($key, $val);
        }
        $length = count($suppArr); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $suppArr [$j]['taxRate']);
        }

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
        if ($suppArr[0]['paymentCondition']) {
            $this->assign('paymentConditionName', $suppArr[0]['paymentConditionName']);
            $this->assign('paymentCondition', $suppArr[0]['paymentCondition']);
            $this->assign('payRatio', $suppArr[0]['payRatio']);
        } else {
            $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //��������
            $this->assign('paymentConditionName', "");
        }

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('suppId', $suppArr[0]['suppId']);
        $this->assign('inquiryId', "");
        $this->assign('dateHope', $minDate);

        $this->display('add');
    }

    /**
     * ��ݲɹ�����,ͨ���ɹ�����
     *
     */
    function c_toAddOrderByTask()
    {
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->assign('type', $type);
        $this->assign('orderType', $orderType);    //�ж������ҵĲɹ��еĲɹ������´ﶩ�������ɲɹ������е�ִ����ҳ���´�
        $this->service->getParam($_GET);
        //��ȡ�ɹ�����������嵥
        $equipmentDao = new model_purchase_task_equipment ();
        $listEqu = $equipmentDao->getTaskEqu_d($idsArry);
        //��ȡ��ע��Ϣ
        $remarkRow = $this->service->getTaskRemark_d($listEqu);
        $this->assign('instruction', $remarkRow['instruction']);
        $this->assign('remark', $remarkRow['remark']);
        $this->assign('list', $this->service->showEquList($listEqu));

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
        $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //��������


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->showDatadicts(array('taxRate' => 'XJSL')); //˰��

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������

        $this->display('task-add');
    }

    /**
     * �ɹ��������ƶ�����������ѯ�ۺϲ�-��һ����
     *
     */
    function c_toAddOrderNew()
    {
        $idsArry = isset ($_GET ['idsArry']) ? substr($_GET ['idsArry'], 1) : exit ();
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->service->getParam($_GET);
        //��ȡ�ɹ�����������嵥
        $equipmentDao = new model_purchase_task_equipment ();
        $listEqu = $equipmentDao->getTaskEqu_d($idsArry);
//		echo "<pre>";
//		print_r($listEqu);exit();
        //��ȡ��ע��Ϣ
        $remarkRow = $this->service->getTaskRemark_d($listEqu);
        $formBelong = $businessBelong = 'dl';
        $formBelongName = $businessBelongName = '���Ͷ���';
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


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->assign('formDate', date("Y-m-d"));

        $this->display('neworder-add');
    }

    /**
     * �ɹ��������ƶ�����������ѯ�ۺϲ���
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

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
//		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //������
        $this->showDatadicts(array('payType' => 'CWFKFS'));

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������

        $this->display('pushorder-add');
    }

    /**
     * ���ʲ��ɹ��������Ʋɹ�����
     *
     */
    function c_toAddOrderByAsset()
    {
        $applyId = isset ($_GET ['applyId']) ? $_GET ['applyId'] : null;
        $orderType = isset ($_GET ['orderType']) ? $_GET ['orderType'] : null;
        $this->assign('orderType', $orderType);
        $this->service->getParam($_GET);
        //��ȡ�ʲ��ɹ�����������嵥
        $equipmentDao = new model_asset_purchase_task_taskItem ();
        $listEqu = $equipmentDao->getItemByParent_d($applyId);
        $this->assign('list', $this->service->showAssetEquList($listEqu));

        //���������ֵ��ֵ
        $this->showDatadicts(array('invoiceType' => 'FPLX')); //��Ʊ����
        $this->showDatadicts(array('paymentType' => 'fkfs')); //���ʽ
        $this->showDatadicts(array('paymentCondition' => 'FKTJ')); //��������


        $this->assign('sendName', $_SESSION ['USERNAME']); //�����
        $this->assign('sendUserId', $_SESSION ['USER_ID']);
        $this->showDatadicts(array('taxRate' => 'XJSL')); //˰��

        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������

        $this->display('apply-add');
    }

    /*
	 * @desription ��ת���鿴/�༭�ɹ���ͬ��Ϣҳ��
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 ����03:07:32
	 */
    function c_init()
    {
        $this->permCheck();//��ȫУ��
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
//		echo "<pre>";
//		print_r($rows);
        $equs = $service->getEquipments_d($_GET ['id']);
        if ($rows['instruction'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//��ȡ�ɹ�����ı�ע��ɹ�˵��
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

            //��ȡ�ɹ�������Ϣ
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

            //��ȡ��Ӧ����Ϣ
            $suppDao = new model_purchase_contract_applysupp();
            $suppRows = $suppDao->getSuppByParentId($_GET['id']);
            //��ȡѯ�۲�Ʒ�嵥
            $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
            //��ʾ��������

            $suppequDao = new model_purchase_contract_applysuppequ();
            if (is_array($suppRows)) {
                foreach ($suppRows as $key => $val) {
                    $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
                }
            }

            //��ȡ���������ϵ���������Э��۸�
            $applybasicDao = new model_purchase_apply_applybasic();
            $materialequDao = new model_purchase_material_materialequ();
            $materialDao = new model_purchase_material_material();
            $suppProDao = new model_purchase_contract_applysuppequ();
            for ($i = 0; $i < count($uniqueEquRows); $i++) {
                $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

                $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
                $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
                $materialRow = $materialDao->get_d($materialequRow['parentId']);

                $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//û�е�ǰ����
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
            //��Ʊ����
            $billingType = $this->getDataNameByCode($rows ['billingType']);
            $this->assign('bType', $billingType);

            //��������
            $paymetType = $this->getDataNameByCode($rows ['paymentType']);
            $this->assign('pType', $paymetType);

            //��������
            $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
            $this->assign('paymentCondition', $paymentCondition);

            //��ǩԼ״̬��ֵ����ת��
            $signStatus = $service->signStatus_d($rows ['signStatus']);
            $this->assign('signStatus', $signStatus);

            //��������
            $suppBank = $this->getDataNameByCode($rows ['suppBank']);
            $this->assign('suppBank', $suppBank);
            $this->assign('skey', $skey);
            if ($rows ['isNeedStamp'] == 1) {
                $this->assign('isNeedStamp', "��");
            } else {
                $this->assign('isNeedStamp', "��");
            }
            if ($rows ['isStamp'] == 1) {
                $this->assign('isStamp', "��");
            } else {
                $this->assign('isStamp', "��");
            }

            //�ж��Ƿ����عرհ�ť
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
            //���ø�������
            $stampConfigDao = new model_system_stamp_stampconfig();
            $stampArr = $stampConfigDao->getStampType_d();
            $this->showSelectOption('stampType', $rows ['stampType'], true, $stampArr);//��������
            $this->showDatadicts(array('payType' => 'CWFKFS'), $rows ['payType']);
//			$length=count($equs); //��ȡ��������ĳ���
//			for($i=1;$i<=$length;$i++){
//				$j=$i-1;
//				$this->showDatadicts ( array ('taxRate'.$i => 'XJSL' ), $equs [$j]['taxRate'] );
//			}
            $this->assign('allMoney', bcadd(0, $rows ['allMoney'], 2));
            $this->assign('allMoneyView', number_format(bcadd(0, $rows ['allMoney'], 2), 2));

            $this->display('order-edit');
        }
    }

    //��ֹʱ�Ĳ鿴ҳ��
    function c_toCloseView()
    {
        $this->permCheck();//��ȫУ��
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ֹ����
        if ($rows['closeType'] == 1) {
            $this->assign('closeTypeC', '��������');
        } else {
            $this->assign('closeTypeC', '����ɾ��');
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
        //��Ʊ����
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //��������
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //��������
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //��������
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "��");
        } else {
            $this->assign('isNeedStamp', "��");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "��");
        } else {
            $this->assign('isStamp', "��");
        }

        $this->display('close-view');

    }

    //�ر�ʱ�Ĳ鿴ҳ��
    function c_toCloseOrderView()
    {
        $this->permCheck();//��ȫУ��
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
        //��Ʊ����
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //��������
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //��������
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //��������
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "��");
        } else {
            $this->assign('isNeedStamp', "��");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "��");
        } else {
            $this->assign('isStamp', "��");
        }

        $this->display('closeorder-view');

    }

    //�ر�ʱ�Ĳ鿴ҳ��
    function c_toCloseRead()
    {
        $this->permCheck();//��ȫУ��
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('close-read');
    }

    /**
     * @description ��ת������ʱ�鿴��ͬ��Ϣ��Tabҳ
     * @date 2011-2-22
     */
    function c_toTabView()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        /*	//���ݲɹ�����Id����ȡ�ɹ�ѯ������id
		$condition="basicId=".$id;
		$inquEquId=$this->service->findAll($condition,'oa_purch_apply_equ','inquiryEquId');
		if($inquEquId){     //��ȡ�ɹ�ѯ�۵�ID
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
     * @description ��ת���鿴��ͬ��Ϣ��Tabҳ
     */
    function c_toTabRead()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('tab-read');
    }

    /**
     * @description ��ת����ֹ�����鿴��ͬ��Ϣ��Tabҳ
     */
    function c_toCloseTabRead()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('close-tab-read');
    }

    /**
     * @description ��ת���ر������鿴��ͬ��Ϣ��Tabҳ
     */
    function c_toCloseOrderTabRead()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        $this->assign('inquiryId', $inquId);
        $this->display('closeorder-tab-read');
    }

    /**
     * @description ��ת���鿴��ͬ��Ϣ��Tabҳ
     */
    function c_toReadTab()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        //���ݲɹ�����Id����ȡ�ɹ�ѯ������id
        /*	$condition="basicId=".$id;
		$inquEquId=$this->service->get_table_fields('oa_purch_apply_equ',$condition,'inquiryEquId');
		if($inquEquId){     //��ȡ�ɹ�ѯ�۵�ID
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
	 * @desription ��Ӳɹ���ͬ
	 * @param tags
	 * @author qian
	 * update by can
	 * @date 2010-12-31 ����03:33:24
	 */
    function c_add()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $contract ['ExaStatus'] = "δ���";
        $equs = $contract ['equs'];

        $url = "?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab";
        if (is_array($contract)) {
            $id = $service->add_d($contract);

            if ($id) {
                //����ѯ�۵��ӿ�
                if ($_GET ['act'] == "app") {
                    switch ($_GET ['type']) {
                        case 'order' :
                            if ($_GET ['appType'] == "order") {
                                succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                                break;
                            } else {
                                succ_show('controller/purchase/contract/ewf_index_menu.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                                break;
                            }
                        case 'inquiry' :
                            succ_show('controller/purchase/contract/ewf_index_inquiry.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                            break;
                        case 'task' :
                            if ($_GET ['orderTypes'] == "manager") {
                                succ_show('controller/purchase/contract/ewf_index_task2.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                                break;
                            } else {
                                succ_show('controller/purchase/contract/ewf_index_task.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                                break;
                            }
                        default :
                            break;
                    }

                } else if ($_GET ['addType'] == "alone") {
                    if ($_GET ['isList'] == "order") {
                        msgGo('����ɹ�', "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2");
                    } else {
                        msgGo('����ɹ�', "?model=purchase_contract_purchasecontract&action=toAddOrder");
                    }
                } else if ($_GET ['addType'] == "task") {
                    if ($_GET ['orderType'] == "manager") {
                        msgGo('����ɹ�', "index1.php?model=purchase_task_basic&action=executionList");
                    } else {
                        msgGo('����ɹ�', "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1");
                    }
                } else {
                    msgGo('����ɹ�', $url);
                }
            } else {
                msgGo('���治�ɹ�,�豸��Ϣ������', $url);
            }
        }
    }

    /**��Ӳɹ�ѯ�۵�����
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
            msgGo('������Ϣ������', "?model=purchase_contract_purchasecontract&action=toAddOrder");
        } else {
            msgGo('������Ϣ������', "?model=purchase_task_basic&action=taskMyList");
        }
    }

    /**��ӹ�Ӧ��
     */
    function c_addSupp()
    {
        $supplier = $this->service->addSupp_d($_POST);
        if ($supplier) {
            echo $supplier;      //��������ж��Ƿ���ӳɹ�
        }

    }

    /**�����ѡ��Ӧ�̣������±���
     */
    function c_suppAdd()
    {
        $supplier = $this->service->suppAdd_d($_POST);
        echo $supplier;
    }

    /**
     *
     *���ʲ��������Ʋɹ��������淽��
     */
    function c_addAssetOrder()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $contract ['ExaStatus'] = "δ���";
        $equs = $contract ['equs'];

        $url = "?model=asset_purchase_task_task&action=pageMyList";
        if (is_array($contract)) {
            $id = $service->add_d($contract);

            if ($id) {
                //����ѯ�۵��ӿ�
                if ($_GET ['act'] == "asset") {
                    succ_show('controller/purchase/contract/ewf_index_asset.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic&flowMoney=' . $contract['allMoney'] . '&formName=�ɹ���ͬ����');
                } else {
                    msgGo('����ɹ�', $url);
                }
            } else {
                msgGo('���治�ɹ�,�豸��Ϣ������', $url);
            }
        }

    }

    /*
	 * @desription ����ɹ���ͬ�ı༭����
	 * @param �༭,����
	 * @author qian
	 * @date 2011-1-5 ����10:28:42
	 *
	 * ����д���Ǵ���ģ�Ӧ�ð��豸����ӷ���д��$service->edit_d ( $rows, true );��ȥ
	 */
    function c_editContract()
    {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $rows = $_POST ['contract'];
        $equs = $_POST ['equs'];

        if ($rows ['paymentCondition'] != "YFK") {  //�жϸ��������Ƿ�ΪԤ����
            $rows ['payRatio'] = "";
        }

        //���������ֵ��ֶ�
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
                        //���²ɹ������豸�����´�/��ͬ��������
                        $taskEquDao->updateContractAmount($val ['taskEquId'], $val ['amountAll'], $val ['amountOld']);
                    }

                }
            }
        }
        if ($id) {
            if ($act == 'audit') {
                $newId = $rows['id'];
                $orderRows = $this->service->get_d($rows['id']);
                if ($orderRows['ExaStatus'] == "���") {
                    $newId = $this->service->dealApproval_d($orderRows);
                }
                succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $newId . '&examCode=oa_purch_apply_basic&flowMoney=' . $orderRows['allMoney'] . '&formName=�ɹ���ͬ����');
            } else {
                $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
                msgGo('�༭�ɹ�', $url);
            }
        } else {
            $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
            msgGo('�豸��Ϣ���������༭ʧ��', $url);
        }

    }

    /*
	 * @desription ����ɹ���ͬ�ı༭����
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
                            '&billCompany=' . $orderRows['businessBelong'] . '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=�ɹ���������(����������)');
                    } else {
                        succ_show('controller/purchase/contract/ewf_index_pay.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=�ɹ���������(����������)');
                    }
                } else {
                    if ($_POST['orderType'] == 'neworder') {
                        succ_show('controller/purchase/contract/ewf_index_menu.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=�ɹ���ͬ����');
                    } else {
                        succ_show('controller/purchase/contract/ewf_index_task.php?actTo=ewfSelect&billId=' . $newId .
                            '&billCompany=' . $orderRows['businessBelong'] .
                            '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=�ɹ���ͬ����');
                    }
                }
            } else {
                if ($_POST['orderType'] == 'neworder') {
                    $url = "?model=purchase_contract_purchasecontract&action=toAddOrder";
                } else {
                    $url = "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1";
                }
                msgGo('����ɹ�', $url);
            }
        } else {
            if ($_POST['orderType'] == 'neworder') {
                $url = "?model=purchase_contract_purchasecontract&action=toAddOrder";
            } else {
                $url = "?model=purchase_task_basic&action=taskMyList&clickNumb=1#tab1";
            }
            msgGo('����ʧ��', $url);
        }

    }

    /*
	 * @desription ����ɹ���ͬ�ı༭����
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
                        '&examCode=oa_purch_apply_basic&flowMoney=' . $rows['allMoney'] . '&formName=�ɹ���������(����������)');
                } else {
                    succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $newId .
                        '&billCompany=' . $orderRows['businessBelong'] .
                        '&examCode=oa_purch_apply_basic&flowMoney=' . $orderRows['allMoney'] . '&formName=�ɹ���ͬ����');
                }
            } else {
                $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
                msgGo('�༭�ɹ�', $url);
            }
        } else {
            $url = "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab2";
            msgGo('����ʧ��', $url);
        }

    }

    /*
	 * @desription ɾ���ɹ���ͬ
	 * @param tags
	 * @author qian
	 * @date 2011-1-7 ����03:38:53
	 */
    function c_deletes()
    {
        $this->permCheck();//��ȫУ��
        $deleteId = isset ($_GET ['id']) ? $_GET ['id'] : exit ();
        $delete = $this->service->delete_d($deleteId);
        if ($delete) {
            msgGo('ɾ���ɹ�');
        }

    }

    /*
	 * @desription ��ת���ɹ�����-�ɹ���ͬ/��ͬ����-��ͬ����-�ɹ���ͬ����תTABҳ
	 * @author qian
	 * @date 2010-12-30 ����08:07:37
	 */
    function c_toPCManage()
    {
        $this->display('tab-pc');
    }

    /*********************************************�����ǡ��ҵĲɹ�-�ɹ���ͬ��************************************************************/

    /*
	 * @desription ��ת�����ҵĲɹ�-�ɹ���ͬ��Tab��תҳ��
	 * @author qian
	 * @date 2010-12-29 ����09:19:45
	 */
    function c_myPurchaseContractTab()
    {
        $this->display('tab-mycontract');
    }

    /**##########################OY���ͬ״̬λ�ķ���##############################*/

    /*
	 * @desription �ҵĲɹ���ͬ--���ύ����
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����07:08:14
	 */
    function c_myWaitList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("save") . "," . $service->stateToSta("fightback")); //"ExaStatus" => "δ���".","."���"
        //ʵ����������
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//����ֵ
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//�����ֶ�
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->sort = "c.updateTime";
        $service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

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
	 * @desription �ҵĲɹ���ͬ--�����еĲɹ���ͬ
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����07:14:54
	 */
    function c_myApprovalList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("approval"));
        //ʵ����������
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//����ֵ
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//�����ֶ�
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
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
	 * @desription �ҵĲɹ���ͬ--ִ���еĲɹ���ͬ
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����07:15:11
	 */
    function c_myExecutionList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], "stateArr" => $service->stateToSta("wite") . "," . $service->stateToSta("execute") . "," . $service->stateToSta("changeAuditing"));


        //ʵ����������
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//����ֵ
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//�����ֶ�
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        if (!isset($_GET['sortArr'])) {
            $service->sort = "c.ExaDT";
        }
        $service->__SET('searchArr', $searchArr);
        $service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

        /*s:-----------------�������-------------------*/
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
        /*e:-----------------�������-------------------*/

        $rows = $service->getOrderList_d();
        $rows = $this->sconfig->md5Rows($rows);
        //���б��ս���ʱ��͸���ʱ������������
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
	 * @desription ִ���еĲɹ���ͬ
	 */
    /*	function c_toExecutionList() {
		$service = $this->service;
		$searchArr = array ("stateArr" => $service->stateToSta ( "wite" ) . "," . $service->stateToSta ( "execute" ) );

		//ʵ����������
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
	 * @desription �ҵĲɹ���ͬ--�ѹرյĲɹ���ͬ
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����07:15:33
	 */
    function c_myCloseList()
    {
        $service = $this->service;
        $searchArr = array("createId" => $_SESSION ['USER_ID'], //�ѹرյĲɹ���ͬ��״̬Ϊ--���رա����ߡ���ɡ�
            "stateArr" => $service->stateToSta("close") . "," . $service->stateToSta("end") . "," . $service->stateToSta("closeOrder"));

        //ʵ����������
        $searchvalue = isset ($_GET ['searchvalue']) ? $_GET ['searchvalue'] : "";//����ֵ
        $searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//�����ֶ�
        $applyNumb = isset ($_GET ['applyNumb']) ? $_GET ['applyNumb'] : null;
        if ($searchvalue != "") {
            $searchArr [$searchCol] = $searchvalue;
        }
        $service->getParam($_GET);
        $service->sort = "c.dateFact";
        $service->__SET('searchArr', $searchArr);
//		$service->__SET ( 'groupBy', "Id" );
        $service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
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
	 * @desription�ѹرյĲɹ���ͬ
	 */
    /*	function c_toCloseList() {
		$service = $this->service;
		$searchArr = array (//�ѹرյĲɹ���ͬ��״̬Ϊ--���رա����ߡ���ɡ�
		"stateArr" => $service->stateToSta ( "close" ) . "," . $service->stateToSta ( "end" ) );

		//ʵ����������
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

    /**�ҵĲɹ���ͬ��������
     *author can
     *2011-1-12
     */
    function c_toChange()
    {
        $this->permCheck();//��ȫУ��
        $changeLogDao = new model_common_changeLog('purchasecontract');
        if ($changeLogDao->isChanging($_GET['id'])) {
            msgGo('�òɹ������Ѿ��ڱ�������У��޷����');
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
        $length = count($equs); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        if (is_array($equs)) {
            $this->assign('TraNumber', count($equs));
        } else {
            $this->assign('TraNumber', 0);
        }
        $this->showDatadicts(array('taxRate' => 'XJSL')); //˰��
        $this->display('change');
    }

    /**
     * add by chengl 2011-05-18
     * �޸ĺ�ͬ���
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
        $length = count($equs); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        if (is_array($equs)) {
            $this->assign('TraNumber', count($equs));
        } else {
            $this->assign('TraNumber', 0);
        }
        $this->showDatadicts(array('taxRate' => 'XJSL')); //˰��
        $this->display('change-edit');
    }

    /**
     *
     * �޸Ķ������
     */
    function c_editChange()
    {
        $object = $_POST ['contract'];
        $id = $this->service->change_d($object);
        echo "<script>this.location='controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
    }

    /**
     * �ɹ������ύ����
     *
     */
    function c_approvalOrder()
    {
        $id = isset ($_GET ['id']) ? $_GET ['id'] : null;
        $order = $this->service->get_d($id);
        if ($order['ExaStatus'] == "���") {     //����ɹ�����������״̬Ϊ����ء���������һ������
            $id = $this->service->dealApproval_d($order);
        }
        if ($order['isApplyPay'] == 1) {
            succ_show('controller/purchase/contract/ewf_index_payedit.php?actTo=ewfSelect&billId=' . $id .
                '&examCode=oa_purch_apply_basic&flowMoney=' . $order['allMoney'] .
                '&billCompany=' . $order['businessBelong'] . '&formName=�ɹ���������(����������)');
        } else {
            succ_show('controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                '&examCode=oa_purch_apply_basic&flowMoney=' . $order['allMoney'] .
                '&billCompany=' . $order['businessBelong'] . '&formName=�ɹ���ͬ����');
        }
    }

    /**
     * ��������ӷ���
     * author can
     * 2011-1-12
     */
    function c_change()
    {
        try {
            $id = $this->service->change_d($_POST ['contract']);

            echo "<script>this.location='controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
        } catch (Exception $e) {
            msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
        }
    }

    /**ǩ�ն���
     */
    function c_signOrder()
    {
        $id = $this->service->signOrder_d($_POST ['contract']);
        if ($id) {
            msgGo('ǩ�ճɹ�', "?model=purchase_contract_purchasecontract&action=toSignList");
        } else {
            msgGo('ǩ��ʧ��', "?model=purchase_contract_purchasecontract&action=toSignList");
        }
    }

    /**
     * ��Բɹ���ͬ����������
     * @return �б�
     */
    function c_searchFun()
    {
        $val = $_GET ['searchVal'];
        $service = $this->service;
        $service->searchArr = array('applyNumb', $val);
        $rows = $service->listBySqlId();

    }

    /**
     * ��ת��ǩ���б�
     */
    function c_toSignList()
    {
        $this->display('list-sign');

    }

    /**
     * ��ת���ɹ�������Ϣ�߼�����ҳ��
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
     * ��ת�������еĲɹ�������Ϣҳ��
     *
     */
    function c_toAuditList()
    {
        $this->display('audit-list');
    }

    /**
     * ��ת���ɹ����鿴�б�
     */
    function c_toOrderListList()
    {
        $this->assignFunc($_GET[$this->objName]);
        $this->display('equipment-list');

    }

    /**
     * ��ת���ɹ����鿴�б�
     */
    function c_toExecutequList()
    {
//		$this->assignFunc( $_GET[$this->objName]);
        $this->display('executequ-list');

    }

    /**
     * ��ת���ɹ�����ϸ�б���Ӧ��������
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
     * �ɹ���Ϣ�б�pagejson
     */
    function c_executEquJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr ['sendUserId'] = $_SESSION ['USER_ID'];
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->asc = true;
        $service->groupBy = 'p.id';
        $service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
        $rows = $service->pageBySqlId('executEquList');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExecutequRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �ɹ���Ϣ�б�pagejson
     */
    function c_purchEquJson()
    {
        $service = $this->service;
        $suppId = isset($_POST['suppId']) ? $_POST['suppId'] : "";
        $assessType = isset($_POST['assessType']) ? $_POST['assessType'] : "";
        $assesYear = isset($_POST['assesYear']) ? $_POST['assesYear'] : "";
        $assesQuarter = isset($_POST['assesQuarter']) ? $_POST['assesQuarter'] : "";
        $service->searchArr['csuppId'] = $suppId;
        if ($assessType == "gysjd") {//���Ȳ�ѯ
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
        } else if ($assessType == "gysnd") {//��Ȳ�ѯ
            $service->searchArr['orderTime'] = $assesYear;
        }
        $service->groupBy = 'p.id';
        $rows = $service->pageBySqlId('executEquList');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExecutequRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �ɹ���Ϣ�б�pagejson
     */
    function c_orderEquJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->asc = true;
        $service->groupBy = 'p.id';
        $rows = $service->listBySqlId('exportItem');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealEquRows_d($rows);
        $arr = array();
        $arr ['collection'] = $newRows;
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת��ǩ��ҳ��
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
        $length = count($equs); //��ȡ��������ĳ���
        for ($i = 1; $i <= $length; $i++) {
            $j = $i - 1;
            $this->showDatadicts(array('taxRate' . $i => 'XJSL'), $equs [$j]['taxRate']);
        }
        $this->display('sign');

    }

    /**
     * ��ת�������������ҳ��
     *
     */
    function c_toApplySeal()
    {
        $this->assign("serviceId", $_GET ['id']);
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr);//��������
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));

        //��ǰ����������
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);
        $this->assign('applyDate', date("Y-m-d"));
        $this->display('seal');
    }

    /**##########################OY���ͬ״̬λ�ķ���##############################*/

    /*
	 * @desription �����ɹ���ͬ
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 ����03:14:46
	 */
    function c_beginPurchaseContract()
    {
        //�����ɹ������ͬ״̬�ı�
        $contractId = $_GET ['id'];
        $state = 2;
        $condiction = array('id' => $contractId);
        $flag = $this->service->updateField($condiction, 'state', $state);
        if ($flag) {
            //�����ɹ���ҳ����ת����ִ���еĲɹ���ͬҳ��
            msgGo("�����ɹ���ͬ�ɹ�!", "?model=purchase_contract_purchasecontract&action=myUnExecuteList");

            //echo "<script>alert('�����ɹ�');location='?model=purchase_contract_purchasecontract&action=myExecutionList'</script>";
        }

    }

    /**
     * ��ת���ɹ�������ֹҳ��
     *
     */
    function c_toClose()
    {
        $hwapplyNumb = isset($_GET['hwapplyNumb']) ? $_GET['hwapplyNumb'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        //�ж϶����Ƿ��������������߶����Ƿ������븶��
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
     * ��ת���ɹ�������ֹҳ��
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
	 * @desription �رղɹ�����
	 */
    function c_close()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $flag = $this->service->edit_d($contract, true);
        if ($flag) {
            if ($contract['closeType'] == 1) {//��������
                succ_show('controller/purchase/contract/ewf_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=�ɹ�������ֹ����');
            } else if ($contract['closeType'] == 2) {//ɾ������
                succ_show('controller/purchase/contract/ewf_del_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=�ɹ�������ֹ����');
            }
        }
//		msg("�ύ�ɹ�","?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");

    }

    /*
	 * @desription �رղɹ�����
	 */
    function c_closeOrder()
    {
        $service = $this->service;
        $contract = $_POST ['contract'];
        $flag = $this->service->edit_d($contract, true);
        if ($flag) {
            succ_show('controller/purchase/contract/ewf_order_close.php?actTo=ewfSelect&billId=' . $contract['id'] . '&examCode=oa_purch_apply_basic&formName=�ɹ������ر�����');
        }
//		msg("�ύ�ɹ�","?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab");

    }

    /*
	 * @desription �ɹ���ͬ-���
	 * @param tags
	 * @author qian
	 * @date 2011-1-1 ����03:35:37
	 */
    function c_finishPurchaseContract()
    {
        $id = isset ($_GET ["id"]) ? $_GET ["id"] : exit ();
        $val = $this->service->end_d($id);
        if ($val == 1) {
            msgGo("�����ɹ�");
        } else if ($val == 2) {
            msgGo("����δ���ҵ�����ʧ��");
        } else {
            msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
        }

    }

    /*********************************************�����ǡ��ҵĲɹ�-�ɹ���ͬ��************************************************************/

    /**#########################################�����ǡ��ɹ���ͬ��������������#########################################*/

    /*
	 * @desription ������������Ĳ鿴ҳ��
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����02:14:18
	 */
    function c_toViewVontract()
    {
//		$this->permCheck ();//��ȫУ��
        $service = $this->service;
        $rows = $service->get_d($_GET ['id']);
        $equs = $service->getEquipments_d($_GET ['id']);
        if ($rows['instruction'] == "" || $rows['remark'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//��ȡ�ɹ�����ı�ע��ɹ�˵��
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

        //��ȡ�ɹ�������Ϣ
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

        //��ȡ��Ӧ����Ϣ
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);

        //��ȡѯ�۲�Ʒ�嵥
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //��ʾ��������

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }

        //��ȡ���������ϵ���������Э��۸�
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//û�е�ǰ����
            $materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

            $uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow, $materialequRow, $materialequRow1);
        }

        $this->show->assign("listSee", $this->service->showSupp_s($suppRows, $uniqueEquRows));
        $this->assign('suppNumb', count($suppRows));
        //��Ʊ����
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //��������
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //��������
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        //��������
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);
        $this->assign('isAudit', '1');
        $this->assign('skey', $skey);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "��");
        } else {
            $this->assign('isNeedStamp', "��");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "��");
        } else {
            $this->assign('isStamp', "��");
        }

        $this->display('order-view');
    }

    /*
	 * @desription ������������Ĳ鿴ҳ��
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����02:14:18
	 */
    function c_toViewChange()
    {
        $service = $this->service;
        $returnObj = $this->objName;
        $id = isset ($_GET ['id']) ? $_GET ['id'] : $_GET ['pjId'];
        $rows = $this->service->get_d($id);
        $equs = $service->getEquipments_d($id);
        if ($rows['instruction'] == "" || $rows['remark'] == "") {
            $infoRow = $this->service->getRemark_d($equs);//��ȡ�ɹ�����ı�ע��ɹ�˵��
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
        //��Ʊ����
        $billingType = $this->getDataNameByCode($rows ['billingType']);
        $this->assign('bType', $billingType);

        //��������
        $paymetType = $this->getDataNameByCode($rows ['paymentType']);
        $this->assign('pType', $paymetType);

        //��������
        $paymentCondition = $this->getDataNameByCode($rows ['paymentCondition']);
        $this->assign('paymentCondition', $paymentCondition);

        //��������
        $suppBank = $this->getDataNameByCode($rows ['suppBank']);
        $this->assign('suppBank', $suppBank);

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);
        if ($rows ['isNeedStamp'] == 1) {
            $this->assign('isNeedStamp', "��");
        } else {
            $this->assign('isNeedStamp', "��");
        }
        if ($rows ['isStamp'] == 1) {
            $this->assign('isStamp', "��");
        } else {
            $this->assign('isStamp', "��");
        }

        $this->display('change-view');
    }

    /**�鿴Tabҳ
     *
     *
     */
    function c_approViewTab()
    {
        $this->permCheck();//��ȫУ��
        $id = $_GET ['id'];
        $this->assign('id', $id);
        $applyNumb = $_GET ['applyNumb'];
        $this->assign('applyNumb', $applyNumb);
        $rows = $this->service->get_d($id);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡ�ɹ�ѯ�۵�ID
        $inquId = $this->service->getInquiryId_d($id);
        /*//���ݲɹ�����Id����ȡ�ɹ�ѯ������id
		$condition="basicId=".$id;
		$inquEquId=$this->service->get_table_fields('oa_purch_apply_equ',$condition,'inquiryEquId');
		if($inquEquId){     //��ȡ�ɹ�ѯ�۵�ID
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
	 * @desription ����б�Tabҳ
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����02:54:33
	 */
    function c_toMyApprovalTab()
    {
        $this->display('approval-index');
    }

    /*
	 * @desription �������ɹ���ͬ�б�
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����03:08:34
	 */
    function c_pcApprovalNo()
    {
        $service = $this->service;
        $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
        $service->searchArr ['Flag'] = 0;
        $service->searchArr ['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $rows = $service->pageBySqlId('sql_examine');
        $this->display('approval-No');
    }

    /*
	 * @desription �������ɹ���ͬ�б�
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 ����03:09:05
	 */
    function c_pcApprovalYes()
    {
        $this->display('approval-Yes');
    }

    /**
     * ��ִ�еĶ���(���б���֮ǰ����ִ�еĲɹ������б�ҳ������)
     *
     */
    function c_toExecutionList()
    {
        $this->display('managexe-list');
    }

    /**
     * ��ִ�еĶ���pagejson
     */
    function c_managPageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->asc = true;
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId('manageExeList');
        $sumrows = $service->listBySqlId('manageExeList');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealExeRows_d($rows, $sumrows);
        $arr = array();
        $arr ['collection'] = $newRows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ִ����Ķ���(���б���֮ǰ���ѹرյĲɹ������б�ҳ������)
     *
     */
    function c_toCloseList()
    {
        $this->display('managend-list');
    }

    /**
     * ��ִ�еĶ���pagejson
     */
    function c_managEndPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->asc = true;
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId("manageEndList");
        $sumrows = $service->listBySqlId('manageEndList');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $newRows = $service->dealEndRows_d($rows, $sumrows);
        $arr = array();
        $arr ['collection'] = $newRows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count($newRows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /*
	 * @desription ��ȡ��ҳ����ת��JSON--������
	 * @author qian
	 * @date 2011-1-6 ����03:22:48
	 */
    function c_pageJsonNo()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * @desription ��ȡ��ҳ����ת��JSON--������
     * @author qian
     * @date 2011-1-6 ����03:23:39
     */
    function c_pageJsonYes()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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

    /**#########################################�����ǡ��ɹ���ͬ��������������#########################################*/

    /*******************************************Ajax��JSON����********************************************/
    /**
     * Enter �Բɹ���ͬ��Ž���ͳһ������֤
     *
     * @param �ɹ���ͬ��
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

    /**���º�ͬ����;����
     *author can
     *2011-6-16
     */
    function c_updateOnWayNumb()
    {
        if (!empty ($_GET ['spid'])) {
            //�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {

            //��ֹ�ظ�ˢ��,���������תҳ��
            echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
        }
    }

    /**��ֹ��������������
     *author can
     */
    function c_dealClose()
    {
        if (!empty ($_GET ['spid'])) {
            //�������ص�����
            $this->service->workflowCallBack_close($_GET['spid']);
        }
        echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * �����ⵥʱ����̬��Ӵӱ�ģ��
     */
    function c_getItemList()
    {
        $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($orderId);  //���ݶ���ID��ȡ������Ϣ
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $equRows = $productinfoDao->k3CodeFormatter_d($equRows);
        $list = $equDao->showAddList($equRows);       //��ȡ����ģ��
        echo $list;
    }

    /**
     * ��Ӳɹ���Ʊʱ����̬��Ӵӱ�
     */
    function c_getDetail()
    {
        $orderId = isset($_POST['objId']) ? $_POST['objId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusForInvpurchase($orderId);  //���ݶ���ID��ȡ������Ϣ
        $list = $equDao->showInvpurchaseList($equRows, $_POST);       //��ȡ����ģ��
        echo $list;
    }

    /************************�������������ɹ��������**********************/
    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_purDetailPageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


        //$service->asc = false;
        $rows = $service->page_d('payapply_list');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ���������ϴ�ҳ��
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
     * ���������ϴ�����
     */
    function c_uploadfile()
    {
        $service = $_POST ['contract'];
        $id = $this->service->uploadfile_d($service);
        if ($id) {
            msg('����ɹ���');
        }
    }

    /**
     * ��������
     */
    function c_seal()
    {
        $service = $_POST ['contract'];
        $id = $this->service->seal_d($service);
        if ($id) {
            msg('�ύ�ɹ���');
        }
    }

    /**���ݲɹ�����������id.�鿴���ϵ��������
     *
     */
    function c_itemView()
    {
        $orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
        $contEquDao = new model_purchase_contract_equipment();
        $purchRows = $this->service->get_d($orderId);
        $contEquRow = $contEquDao->getEqusByContractId($orderId);
        //���ݲɹ�����ID,��ȡ���������Ϣ
//	 	$stockRows=$this->service->getStockInfo_d($orderId);
        $this->assign('list', $contEquDao->showEquList($contEquRow));
        $this->assign('orderCode', $purchRows['hwapplyNumb']);
        $this->display('stock-list');
    }

    /**
     *
     * ����������ϢEXCEL
     */
    function c_toExportExcel()
    {
        $isPerson = isset($_GET['exportType']) ? $_GET['exportType'] : 0;
        if ($isPerson) {  //�ж��Ǹ��˵ĵ��ݵ��� ����ȫ�����ݵ���
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
            $this->service->searchArr ['ExaStatus'] = '��������,���,���';
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
                case '��������':
                    $dataArr[$key]['ExaStatus'] = "������";
                    break;
                case '���':
                    $dataArr[$key]['ExaStatus'] = "��ͨ��";
                    break;
                case '���':
                    $dataArr[$key]['ExaStatus'] = "ͨ��";
                    break;
            }
        }
        return $dao->exportItemExcel($dataArr);
    }

    //��ת�����������������ݹ���ҳ��
    function c_toExporttFilter()
    {
        if (!$this->service->this_limit['��������������Ϣ']) {
            echo "<script>alert('û��Ȩ�޽��в���!');window.close();</script>";
            exit();
        }
        $exportType = isset($_GET['exportType']) ? $_GET['exportType'] : ""; //�������ͣ�������ҵ��Ա���ǹ�����
        $this->assign('exportType', $exportType);
        $this->display('exportexcel');
    }

    //���ݶ���ID��ȡ������Ϣģ��
    function c_getItemModel()
    {
        $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : "";
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($orderId);  //���ݶ���ID��ȡ������Ϣ
        $list = $equDao->showItemModel($equRows);       //��ȡ����ģ��
        echo $list;
    }

    //���ݶ���ID��ȡ����������Ϣģ��
    function c_getPayStr()
    {
        $orderId = isset($_POST['id']) ? $_POST['id'] : "";
        $suppId = isset($_POST['suppId']) ? $_POST['suppId'] : "";
        $list = $this->service->payEquList_d($orderId, $suppId);
        echo $list;
    }

    //���ݶ���ID��ȡ����������Ϣģ��
    function c_getPayEditStr()
    {
        $orderId = isset($_POST['id']) ? $_POST['id'] : "";
        $list = $this->service->payEquEditList_d($orderId);
        echo $list;
    }

    /**
     * �����ɹ�����
     *
     */
    function c_exportPurchaseOrder()
    {
        if (!$this->service->this_limit['�����ɹ�����']) {
            echo "<script>alert('û��Ȩ�޽��в���!');history.back(-1)</script>";
            exit();
        }
        $this->permCheck();//��ȫУ��
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        //��ȡ��������
        $rows = $this->service->get_d($id);
        $equRows = $this->service->getEquipments_d($id);  //���ݶ���ID��ȡ������Ϣ

        $dao = new model_purchase_contract_purchaseUtil ();
        return $dao->exportPurchaseOrder($rows, $equRows); //����Excel
    }

    /**
     * ��ת�����������ĵ�����Ϣ�鿴ҳ�棨����ͨ����
     *
     */
    function c_toViewRelationBill()
    {
        $this->permCheck();//��ȫУ��
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $readType = isset($_GET['readType']) ? $_GET['readType'] : "";
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $applyDeptLabel = '���벿��';
        $applyManLabel = '������';

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $this->service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        $equs = $this->service->getEquipments_d($_GET ['id']);

        $equDao = new model_purchase_contract_equipment();
        //��ȡ�ɹ�������Ϣ
        $planEquRows = $equDao->getPlanEqu_d($id);
        if ($planEquRows) {
            if($planEquRows[0]["purchType"] == "assets" || $planEquRows[0]["purchType"] == "oa_asset_purchase_apply"){
                $applyDeptLabel = '�ֿ�';
                $applyManLabel = '�ֹ�Ա';
            }
        }
        $this->assign('planList', $equDao->showPlanEquList($planEquRows));

        //��ȡ�ɹ�ѯ����Ϣ
        $inquiryEquRow = $equDao->getInquiryEqu_d($id);
        $this->assign('inquiryList', $equDao->showInquiryList($inquiryEquRow));

        //��ȡ�ɹ���������Ψһ��Ϣ,��ʾ������ʷ�۸���Ϣ
        $groupByEqus = $this->service->getUniqueEqus_d($_GET ['id']);
        $this->assign('historyList', $equDao->showHistoryList($groupByEqus));

        //��ȡ����������Ϣ
        $payApplyDao = new model_finance_payablesapply_payablesapply();
        $payApplyRow = $payApplyDao->getApplyByPur_d($id, 'YFRK-01');
        $this->assign('payApplyList', $equDao->showPayApplyList($payApplyRow));

        //��ȡ�����¼��Ϣ
        $payedDao = new model_finance_payables_payables();
        $payedRow = $payedDao->getPayedByPur_d($id, 'YFRK-01');
        $this->assign('payedList', $equDao->showPayedList($payedRow));

        //��ȡ��Ʊ��Ϣ
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

        //�ж��Ƿ����عرհ�ť
        if (isset($_GET['hideBtn'])) {
            $this->assign('hideBtn', 1);
        } else {
            $this->assign('hideBtn', 0);
        }
        //��ȡ��Ӧ����Ϣ
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);
        //��ȡѯ�۲�Ʒ�嵥
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //��ʾ��������

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }
        //��ȡ���������ϵ���������Э��۸�
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//û�е�ǰ����
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

        $this->assign('readType', $readType);//�����ǲ鿴�����������عرհ�ť
        $this->assign('list', $this->service->addContractEquList_s($newEqus));

        $this->assign('applyDeptLabel', $applyDeptLabel);
        $this->assign('applyManLabel', $applyManLabel);
        $this->display('relationbill-view');
    }

    /**
     * ��ת�����������ĵ�����Ϣ�鿴ҳ�棨����ǰ��
     *
     */
    function c_toReadRelationBill()
    {
        $this->permCheck();//��ȫУ��
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $readType = isset($_GET['readType']) ? $_GET['readType'] : "";
        $rows = $this->service->get_d($_GET ['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $applyDeptLabel = '���벿��';
        $applyManLabel = '������';

        //��ǩԼ״̬��ֵ����ת��
        $signStatus = $this->service->signStatus_d($rows ['signStatus']);
        $this->assign('signStatus', $signStatus);

        $equs = $this->service->getEquipments_d($_GET ['id']);

        $equDao = new model_purchase_contract_equipment();
        //��ȡ�ɹ�������Ϣ
        $planEquRows = $equDao->getPlanEqu_d($id);
        if ($planEquRows) {
            if($planEquRows[0]["purchType"] == "assets" || $planEquRows[0]["purchType"] == "oa_asset_purchase_apply"){
                $applyDeptLabel = '�ֿ�';
                $applyManLabel = '�ֹ�Ա';
            }
        }
        $this->assign('planList', $equDao->showPlanEquList($planEquRows));

        //��ȡ�ɹ�ѯ����Ϣ
        $inquiryEquRow = $equDao->getInquiryEqu_d($id);
        $this->assign('inquiryList', $equDao->showInquiryList($inquiryEquRow));

        //��ȡ�ɹ���������Ψһ��Ϣ,��ʾ������ʷ�۸���Ϣ
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
        $this->assign('readType', $readType);//�����ǲ鿴�����������عرհ�ť
        $this->assign('list', $this->service->addContractEquList_s($newEqus));
        //��ȡ��Ӧ����Ϣ
        $suppDao = new model_purchase_contract_applysupp();
        $suppRows = $suppDao->getSuppByParentId($_GET['id']);
        //��ȡѯ�۲�Ʒ�嵥
        $uniqueEquRows = $equDao->getUniqueByParentIdNew($_GET['id']);
        //��ʾ��������

        $suppequDao = new model_purchase_contract_applysuppequ();
        if (is_array($suppRows)) {
            foreach ($suppRows as $key => $val) {
                $suppRows[$key]['child'] = $suppequDao->getProByParentId($val['id']);
            }
        }
        //��ȡ���������ϵ���������Э��۸�
        $applybasicDao = new model_purchase_apply_applybasic();
        $materialequDao = new model_purchase_material_materialequ();
        $materialDao = new model_purchase_material_material();
        $suppProDao = new model_purchase_contract_applysuppequ();
        for ($i = 0; $i < count($uniqueEquRows); $i++) {
            $amount = $applybasicDao->getAmountAll($uniqueEquRows[$i]['productId']);

            $uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)'] ? $amount[0]['SUM(amountAll)'] : 0;
            $materialequRow = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $amount[0]['SUM(amountAll)'] + $uniqueEquRows[$i]['amountAll']);//���ϵ�ǰ��������
            $materialRow = $materialDao->get_d($materialequRow['parentId']);

            $materialequRow1 = $materialequDao->getPrice($uniqueEquRows[$i]['productId'], $uniqueEquRows[$i]['amount']);//û�е�ǰ����
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
     * �ɹ���������
     */
    function c_toListInfo()
    {
        $logic = isset($_GET["logic"]) ? $_GET["logic"] : "and";
        $field = isset($_GET["field"]) ? $_GET["field"] : "createTime";
        $relation = isset($_GET["relation"]) ? $_GET["relation"] : "greater";
        $last = strtotime("-1 month", time());
        $last_lastday = date("Y-m-t", $last);//�ϸ������һ��
        $values = isset($_GET["values"]) ? $_GET["values"] : $last_lastday;
        $this->assign("logic", $logic);
        $this->assign("field", $field);
        $this->assign("relation", $relation);
        $this->assign("values", $values);
        $this->display('listinfo');
    }

    /**
     *�ɹ��������� �߼�����
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
     *�ж��Ƿ����ύ��������
     *
     */
    function c_isApplySeal()
    {
        $id = $_POST['id'];
        $row = $this->service->get_d($id);
        if ($row['isStamp'] != 1 && $row['isNeedStamp'] != 1) {//δ�������
            echo 1;
        } else if ($row['isStamp'] == 0 && $row['isNeedStamp'] == 1) {//��������£���δ����
            echo 0;
        } else if ($row['isStamp'] == 1 && $row['isNeedStamp'] == 1) {//��������£��Ѹ���
            echo 2;
        }
    }


    /************************�������������ɹ��������**********************/

    /************************ �ɹ����������������֤ ********************/
    /**
     * ��֤����
     */
    function c_canPayapply()
    {
        $id = $_POST['id'];
        $rs = $this->service->canPayapply_d($id);
        echo $rs;
        exit();
    }

    /**
     * �˿�������֤
     */
    function c_canPayapplyBack()
    {
        $id = $_POST['id'];
        $rs = $this->service->canPayapplyBack_d($id);
        echo $rs;
        exit();
    }

    /************************ �ɹ����������������֤ ********************/

    /**
     * �ɹ�����ͳ�Ʊ���
     */
    function c_toStatistics()
    {
        $logic = isset($_GET["logic"]) ? $_GET["logic"] : "and";
        $field = isset($_GET["field"]) ? $_GET["field"] : "createTime";
        $relation = isset($_GET["relation"]) ? $_GET["relation"] : "greater";
        $lastMonth = strtotime("-1 month", time());
        $lastMonth_lastDay = date("Y-m-t", $lastMonth);//�ϸ������һ��
        $values = isset($_GET["values"]) ? $_GET["values"] : $lastMonth_lastDay;
        $this->assign("logic", $logic);
        $this->assign("field", $field);
        $this->assign("relation", $relation);
        $this->assign("values", $values);
        $this->view('statistics');
    }

    /**
     *�ɹ��������� �߼�����
     */
    function c_statisticsSearch()
    {
        $beginDate = isset($_GET['beginDate']) ? $_GET['beginDate'] : "";
        $logic = isset($_GET['logic']) ? $_GET['logic'] : "";
        $field = isset($_GET['field']) ? $_GET['field'] : "";
        $relation = isset($_GET['relation']) ? $_GET['relation'] : "";
        $values = isset($_GET['values']) ? $_GET['values'] : "";
        $this->assign("beginDate", $beginDate);
        if (!empty($logic)) {//�ж��Ƿ����ѯ����
            $logicArr = explode(',', $logic);
            $fieldArr = explode(',', $field);
            $relationArr = explode(',', $relation);
            $valuesArr = explode(',', $values);
            $list = $this->service->selectList($logicArr, $fieldArr, $relationArr, $valuesArr);//��ѯ����ģ��
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
     * �ɹ�����ȡ������
     */
    function c_cancelApproval()
    {
        $id = isset ($_GET ['id']) ? $_GET ['id'] : null;
        $orderRows = $this->service->get_d($id);
        if ($orderRows['isApplyPay'] == 1) {//�ɹ���������(����������)
            echo "<script>parent.location='controller/purchase/contract/ewf_index_payedit.php?actTo=delWork&billId='+$id+'&examCode=oa_purch_apply_basic&formName=�ɹ���������(����������)'</script>";
        } else {//�ɹ���ͬ����
            echo "<script>parent.location='controller/purchase/contract/ewf_index.php?actTo=delWork&billId='+$id+'&examCode=oa_purch_apply_basic'</script>";
        }
    }

    /**
     * ��鶩����������ܽ���Ƿ��ڿɱ䷶Χ��
     */
    function c_chkChangeForm(){
        $chkResult = '';
        $id = isset($_POST ['oldId'])? $_POST ['oldId'] : '';
        $allMoney = isset($_POST ['allMoney'])? $_POST ['allMoney'] : 0;

        //��ȡ������������������ɣ����ز��ܼ������룬������븶�������ж�
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payedMoney = $payablesapplyDao->getApplyMoneyByPur_d($id, 'YFRK-01');

        $payedMoney = bcmul($payedMoney,1);
        $allMoney = bcmul($allMoney,1);
        $chkResult =  ($payedMoney > $allMoney)? 'No' : 'Yes';
        $chkResultMsg =  ($payedMoney > $allMoney)? '��������С�������븶���' : '';

        $arr = array();
        $arr ['oldId'] = $id;
        $arr ['allMoney'] = $allMoney;
        $arr ['payedMoney'] = $payedMoney;
        $arr ['chkResult'] = $chkResult;
        $arr ['msg'] = $chkResultMsg;
        echo util_jsonUtil::encode($arr);
    }
}