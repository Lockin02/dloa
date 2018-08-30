<?php

/**
 * @author Show
 * @Date 2011��5��8�� ������ 13:55:05
 * @version 1.0
 * @description:��������(��)���Ʋ�
 */
class controller_finance_payablesapply_payablesapply extends controller_base_action
{

    private $unDeptExtFilter = "";// PMS377 ��ģ����Ҫ�������صĲ���ѡ��
    function __construct() {
        $this->objName = "payablesapply";
        $this->objPath = "finance_payablesapply";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");

        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        $this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
    }

    /**
     * ��ת����������(��)
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת���������루������֧����
     */
    function c_pageEntrust() {
        $this->view('listEntrust');
    }

    /**
     * ���񸶿������б� - ��ʱ���pagejson(�����б�)
     */
    function c_pageJsonList() {
        $service = $this->service;

        if ($_REQUEST['status'] == 'FKSQD-03') {
            $_REQUEST['formDateBegin'] = isset($_REQUEST['formDateBegin']) ? $_REQUEST['formDateBegin'] : day_date;
            $_REQUEST['formDateEnd'] = isset($_REQUEST['formDateEnd']) ? $_REQUEST['formDateEnd'] : day_date;
        }
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        if (!empty($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);
            $newRow = array();

            //����������׼�� - �Ѹ���ʱ����Ч
            if ($_REQUEST['status'] == 'FKSQD-03') {
                $mark = "";
                $dCountArr = array('id' => 'noId', 'payMoney' => 0, 'payedMoney' => 0, 'shareMoney' => 0,
					'payMoneyCur' => 0);
                foreach ($rows as $val) {
                    if (!empty($mark) && $mark != $val['lastPrintTime']) {
                        $dCountArr['formNo'] = $mark;
                        $newRow[] = $dCountArr;
                        $dCountArr = array('id' => 'noId', 'payMoney' => 0, 'payedMoney' => 0, 'shareMoney' => 0,
							'payMoneyCur' => 0);
                    }
                    $newRow[] = $val;

					// ���ִ���
					if ($val['currencyCode'] == 'CNY') {
						$dCountArr['payMoneyCur'] = bcadd($dCountArr['payMoneyCur'], $val['payMoneyCur'], 2);
					} else {
						$dCountArr['payMoney'] = bcadd($dCountArr['payMoney'], $val['payMoney'], 2);
					}
                    $dCountArr['payedMoney'] = bcadd($dCountArr['payedMoney'], $val['payedMoney'], 2);
                    $dCountArr['shareMoney'] = bcadd($dCountArr['shareMoney'], $val['shareMoney'], 2);
                    if ($mark != $val['lastPrintTime']) {
                        $mark = $val['lastPrintTime'];
                    }
                }
                $dCountArr['formNo'] = $mark;
                $newRow[] = $dCountArr;
            }

            //�ܼ�������
            $objArr = $service->listBySqlId('count_allnew');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '�ϼ�';
                $rsArr['id'] = 'noId';
            }

            if (empty($newRow)) {
                $rows[] = $rsArr;
            } else {
                $newRow[] = $rsArr;
                $rows = $newRow;
            }
        }
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �˿������б�
     */
    function c_pageForRefund() {
        $this->view('listforrefund');
    }

    /**
     * ��������Ԥ��ҳ��
     */
    function c_pageForRead() {
        $this->view('list-forread');
    }

    /**
     * ��������Ԥ��
     */
    function c_pageJsonForRead() {
        $service = $this->service;
        $rows = array();

        $service->getParam($_REQUEST);

        $deptLimit = isset($this->service->this_limit['����Ȩ��']) ? $this->service->this_limit['����Ȩ��'] : null;
        if (strstr($deptLimit, ';;')) {
            $rows = $service->page_d();
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //�ܼ�������
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '�ϼ�';
                $rsArr['id'] = 'noId';
                $rows[] = $rsArr;
            }
        } else if (!empty($deptLimit)) {
            $service->searchArr['deptIds'] = $deptLimit;
            $rows = $service->page_d();
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //�ܼ�������
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '�ϼ�';
                $rsArr['id'] = 'noId';
                $rows[] = $rsArr;
            }
        }
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Բɹ������ĸ�������ҳ��
     */
    function c_toAddInPurcont() {
        $rs = $this->service->getContractinfoById_d($_GET['id']);
        if ($rs['allMoney'] <= $rs['payablesapplyMoney']) {
            msg('�Ѵ���������');
        }
        $detail = $this->service->initPayApplyDetail_d(array(0 => $rs));
        $rs['detail'] = $detail[0];
        $rs['coutNumb'] = $detail[1];
        $this->assignFunc($rs);

        $this->assign('paymentCondition', $this->getDataNameByCode($rs['paymentCondition']));
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->assign('formDate', day_date);

        $this->display('addinpurcont');
    }

    /**
     * ��Բɹ������ĸ�������ҳ��
     */
    function c_addInPurcont() {
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            if ($_GET['act']) {
                succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $_POST[$this->objName]['feeDeptId'] . $this->service->tbl_name . '&flowMoney=' . $_POST[$this->objName]['payMoney']);
            } else {
                msgRf('����ɹ�');
            }
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * �������� ��д
     */
    function c_toAddforObjType() {
        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($_GET['payFor']) ? $_GET['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($_GET['objType']);
        $initObj = new $newClass();
        //��ȡ��Ӧҵ����Ϣ
        $rs = $this->service->getObjInfo_d($_GET, $initObj);

        // �ɹ��������ִ���
        if(in_array($_GET['objType'], array("YFRK-01", "YFRK-02"))){
        	$currency = "";
        	$currencyCode = "";
        	$rate = "";
        	foreach ($rs['detail'] as $k => $v){
        		$v['currency'] = empty($v['currency']) ? "�����" : $v['currency'];
        		$v['currencyCode'] = empty($v['currencyCode']) ? "CNY" : $v['currencyCode'];
        		$v['rate'] = empty($v['rate']) || $v['rate'] * 1 == "0" ? "1" : $v['rate'];
        		if($k == 0){
        			$currency = $v['currency'];
        			$currencyCode = $v['currencyCode'];
        			$rate = $v['rate'];
        		}else if($currency != $v['currency'] || $rate != $v['rate']){
        			msg("��ͬ������ֻ���ʵĲɹ��������ܺϲ�����");
        		}
        	}
        }
        //����ǵ����ƣ�����뵥������Ⱦҳ��
        if (isset($_GET['addType'])) {
            $initRs = $this->service->initAddOne_d($rs, $initObj, $payFor);
            if (!$initRs[2]) {
                msgRf('���������Ѵ����ޣ����ܼ�������');
                exit;
            }
            $this->assign('canApplyAll', $initRs[2]);
        } else {
            $initRs = $this->service->initAdd_d($rs, $initObj, $payFor);
            $this->assign('canApplyAll', $initRs[2]);
        }

        if (!$initRs[1]) {
            msgRf('���������Ѵ����ޣ����ܼ�������');
            exit;
        }

        $rs['detail'] = $initRs[0];
        $rs['coutNumb'] = $initRs[1];

        //��Ⱦ����
        $this->assignFunc($rs);

        //��ѯ��Դ����������� - �ʺ� - ����ص�-�Ƿ񿪾ݷ�Ʊ��Ϣ
        if($_GET['objType'] == 'YFRK-06'){
            $countInfo = $this->service->find(array('sourceType' => $_GET['objType'], 'supplierId' => $rs['signCompanyId']),
                'id desc', 'bank,account,place,isInvoice');
        }else{
            $sourceCode = $_GET['objType'] == 'YFRK-01' ? $rs['hwapplyNumb'] : $rs['objCode'];
            $countInfo = $this->service->find(array('sourceType' => $_GET['objType'], 'sourceCode' => $sourceCode),
                'id desc', 'bank,account,place,isInvoice');
        }

        if (empty($countInfo)) {
            $countInfo = array('bank' => '', 'account' => '', 'place' => '');
        }

        //��Ⱦ������Ϣ
        $this->assignFunc($countInfo);
        $this->assignFunc($_GET);

        //ҳ��������Ⱦ
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, true);//����ҵ������

        $this->assign('formDate', day_date);//����
        $this->assign('userId', $_SESSION['USER_ID']);//���ݵ�ǰ�û�id

        $type = $this->service->getBusinessCode($_GET['objType']);
        if ($type == 'outaccount') {
            //��ȡ������˾����
            $this->assign('formBelong', $_SESSION['USER_COM']);
            $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
            $this->assign('businessBelong', $_SESSION['USER_COM']);
            $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
            if($type == "purcontract"){// �ɹ��������ִ���
            	$this->assign('currency', $currency);
            	$this->assign('currencyCode', $currencyCode);
            	$this->assign('rate', $rate);
            }
        }else if($type == "other"){
            //����ҵ������
            $this->assign('payForBusiness',$rs['payForBusiness']);
            $this->assign('payForBusinessName',$rs['payForBusinessName']);
        }

        $this->assign('periodStr', $this->periodDeal());

        $this->view($type . '-add' . $keyWork);
    }

    /**
     * �������� - ��������
     */
    function c_toAddDept() {
        $thisObj = $_GET;

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($_GET['payFor']) ? $_GET['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));

        //��Ⱦ������Ϣ
        $this->assignFunc($thisObj);
        $this->assign('sourceTypeCN', $this->getDataNameByCode($thisObj['sourceType']));

        //ҳ��������Ⱦ
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->assign('formDate', day_date);

        //����Ĭ����Ϣ
        $this->assign('salesmanId', $_SESSION['USER_ID']);
        $this->assign('salesman', $_SESSION['USERNAME']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->display($this->service->getBusinessCode($thisObj['sourceType']) . '-adddept');
    }

    /**
     * ��ѡ����̤��
     */
    function c_toAddPedal() {
        $thisObj = $_GET;
        isset($thisObj['owner']) ? $this->assign('sendUserId', $_SESSION['USER_ID']) : $this->assign('sendUserId', "");
        $this->display($this->service->getBusinessCode($thisObj['sourceType']) . '-addpedal');
    }

    /**
     * ��дtoAdd
     */
    function c_toAdd() {
        //���Ե�������ҳ��//���Ե�������ҳ��

        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->assign('formDate', day_date);

        $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
        $this->assign('owner', $owner);

        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('createName', $_SESSION['USERNAME']);

        $this->display('add');
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = false) {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($object);
        if ($id) {
            if (isset($_GET['act'])) {
                if ($object['sourceType'] == 'YFRK-01') {  //�ɹ�����
                    if ($object['payFor'] != 'FKLX-03') {
                        succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } //add chenrf 20130506
                elseif ($object['sourceType'] == 'YFRK-02') {  //������ͬ
                    if ($object['payFor'] != 'FKLX-03') {
                        //��������
                        succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } else {
                    succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                        '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                        '&billDept=' . $object['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('����ɹ�');
            }
        } else {
            msgRf('����ʧ��');
        }
    }

    /**
     * �޸Ķ���
     */
    function c_edit($isEditInfo = false) {
        //		$this->permCheck (); //��ȫУ��
        $object = $_POST[$this->objName];
        $id = $this->service->edit_d($object);
        if ($id) {
            if (isset($_GET['act'])) {
                if ($object['sourceType'] == 'YFRK-01') {//�ɹ�����
                    if ($object['payFor'] != 'FKLX-03') {
                        succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } //add chenrf 20130506
                elseif ($object['sourceType'] == 'YFRK-02') {  //������ͬ
                    if ($object['payFor'] != 'FKLX-03') {
                        //��������
                        succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } else {
                    succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'] .
                        '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                        '&billDept=' . $object['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('�༭�ɹ�');
            }
        } else {
            msgRf('�༭ʧ��');
        }
    }

    /**
     * ��дc_init
     */
    function c_init() {
        //URLȨ�޿���
        $this->permCheck();
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        if ($perm == 'view') {
            if (!empty($obj['sourceType'])) {
                $this->c_toView($_GET['id']);
                exit;
            }

            $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
            $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
            $this->assign('detail', $detailObj);
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false));
            $this->display('view');
        } else {
            $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
            $this->assign('owner', $owner);
            $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
            $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true));
            $this->display('edit');
        }
    }

    /**
     * ��������鿴ҳ��
     */
    function c_toView() {
        //URLȨ�޿���
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);
        if($initRs['payForBusiness'] == '' && $initRs['exaCode'] == 'oa_sale_other'){
            $otherObj = $this->service->get_one("select payForBusiness from oa_sale_other where id = '{$initRs['exaId']};'");
            $initRs['payForBusiness'] = (isset($otherObj['payForBusiness']))? $otherObj['payForBusiness'] : '';
        }
        $this->assignFunc($initRs);
        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('payForBusiness', $this->getDataNameByCode($initRs['payForBusiness']));//����ҵ������
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //������չ������
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        // ����ǲɹ����������������������˵��������
        if ($object['sourceType'] == 'YFRK-01') {
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            // ����ⵥ��ȡ
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $this->assign('entryDate', $entryDate);
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        //���ݷ�Ʊ
        $this->assign('isInvoice', $this->service->rtYesNo_d($object['isInvoice']));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-view' . $keyWork);
    }

    /**
     * ��������鿴ҳ�� - ����鿴����ҳ�棩
     */
    function c_toViewSimple() {
        //URLȨ�޿���
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        if ($object['isPay'] == 1)
            $this->assign('isPay', '��');
        else
            $this->assign('isPay', '��');

        //������չ������
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-viewsimple' . $keyWork);
    }

    /**
     * ��������鿴ҳ��
     */
    function c_toEdit() {
        //URLȨ�޿���
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');
        // ����ǲɹ����������������������˵��������
        if ($object['sourceType'] == 'YFRK-01') {
            // ��ȡ�������
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $object['entryDate'] = $entryDate;
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initEdit_d($object, $initObj, $payFor);

        $object['detail'] = $initRs[0];
        $object['coutNumb'] = $initRs[1];

        $this->assignFunc($object);

        $this->showDatadicts(array('payType' => 'CWFKFS'), $object['payType']);
        $this->showDatadicts(array('payForBusiness' => 'FKYWLX'), $object['payForBusiness'], true);

        $this->assign('sourceTypeCN', $this->getDataNameByCode($object['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, true));

        $this->assign('periodStr', $this->periodDeal($object['period']));

        $payForBusinessName = '';
        if($this->service->getBusinessCode($object['sourceType']) == "other"){
            $payForBusinessName = $this->getDataNameByCode($object['payForBusiness']);
        }
        $this->assign('payForBusiness',$payForBusinessName);

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-edit' . $keyWork);
    }

    /**
     * �������������鿴ҳ��
     */
    function c_toViewAudit($id = null) {
        $id = empty($id) ? $_GET['id'] : $id;

        //URLȨ�޿���
        $this->permCheck();

        $object = $this->service->get_d($id, 'clear');

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initAudit_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        // ����ǲɹ����������������������˵��������
        if ($object['sourceType'] == 'YFRK-01') {
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            // ����ⵥ��ȡ
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $this->assign('entryDate', $entryDate);
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //���ݷ�Ʊ
        $this->assign('isInvoice', $this->service->rtYesNo_d($object['isInvoice']));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-viewaudit' . $keyWork);
    }

    /**
     * ��ӡҳ��
     */
    function c_toPrint() {

        //URLȨ�޿���
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        if (empty($object['sourceType'])) {
            $this->c_print();
            exit;
        }

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initPrint_d($object, $initObj);
        //		print_r($initRs);

        $this->assignFunc($initRs);

        //��Ŀ���
        $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

        //������չ������
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        $this->assign('todayStr', date("Y-m-d"));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-print' . $keyWork);
    }

    /**
     * ������ӡ
     */
    function c_toBatchPrint() {
        //id��
        $ids = null;
        //��id����
        $newIdArr = array();

        if (isset($_GET['id'])) {
            $ids = $_GET['id'];
            $idArr = explode(',', $ids);
        } else {
            $idArr = $this->service->getPayablesapplyCanPay_d();
        }

        $this->display('batchprinthead');

        //�ϼƽ��
        $allMoney = 0;

        foreach ($idArr as $key => $val) {
            $id = is_array($val) ? $val['id'] : $val;

            if(in_array($id,$newIdArr)){
                continue;
            }

            if (empty($ids)) {
                array_push($newIdArr, $id);
            }

            $object = $this->service->get_d($id, 'clear');

            //������
            $allMoney = bcAdd($allMoney, $object['payMoney'], 2);

            //����״̬�ж�
            if ($object['status'] != 'FKSQD-01') {
                msgRf('����[' . $id . '] ״̬Ϊ��[' . $this->getDataNameByCode($object['status']) . '] ���ܽ��и�������ӡ����ѡ�񵥾ݺ󣬵��������ӡ����');
                die();
            }

            //�������Ͷ���
            $payForTypes = array_keys($this->service->payForArr);
            $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
            $this->assign('payFor', $payFor);
            $this->assign('payForCN', $this->getDataNameByCode($payFor));
            $keyWork = $this->service->payForArr[$payFor];

            //���ò���
            $newClass = $this->service->getClass($object['sourceType']);
            $initObj = new $newClass();

            $initRs = $this->service->initPrint_d($object, $initObj);

            //������˾����,չʾ��˾ȫ��
            $branchDao = new model_deptuser_branch_branch();
            $branchObj = $branchDao->find(array("NamePT" => $initRs['businessBelong']), null, 'fullname');
            $initRs['fullName'] = $branchObj['fullname'];

            $this->assignFunc($initRs);

            //��Ŀ���
            $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

            $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

            //������չ������
            if (empty($object['exaCode'])) {
                $this->assign('exaId', $id);
                $this->assign('exaCode', $this->service->tbl_name);
            }

            $this->display($this->service->getBusinessCode($object['sourceType']) . '-print-expand' . $keyWork);
        }
        if (empty($ids)) {
            $ids = implode($newIdArr, ',');
        }
        $this->assign('ids', $ids);
        $this->assign('allNum', count($idArr));
        $this->assign('allMoney', $allMoney);
        $this->assign('todayStr', date("Y-m-d"));
        $this->display('batchprint');
    }

    /**
     * ������ӡ - ֻ���ڴ�ӡ
     */
    function c_toBatchPrintAlong() {
        //id��
        $ids = null;
        //��id����
        $newIdArr = array();

        if (isset($_GET['id'])) {
            $ids = $_GET['id'];
            $idArr = explode(',', $ids);
        } else {
            $idArr = $this->service->getPayablesapplyCanPay_d();
        }

        $this->display('batchprinthead');

        //�ϼƽ��
        $allMoney = 0;

        foreach ($idArr as $key => $val) {
            $id = is_array($val) ? $val['id'] : $val;

            if (empty($ids)) {
                array_push($newIdArr, $id);
            }

            $object = $this->service->get_d($id, 'clear');
            //������
            $allMoney = bcAdd($allMoney, $object['payMoney'], 2);

            //�������Ͷ���
            $payForTypes = array_keys($this->service->payForArr);
            $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
            $this->assign('payFor', $payFor);
            $this->assign('payForCN', $this->getDataNameByCode($payFor));
            $keyWork = $this->service->payForArr[$payFor];

            //���ò���
            $newClass = $this->service->getClass($object['sourceType']);
            $initObj = new $newClass();
            $initRs = $this->service->initPrint_d($object, $initObj);

            //������˾����,չʾ��˾ȫ��
            $branchDao = new model_deptuser_branch_branch();
            $branchObj = $branchDao->find(array("NamePT" => $initRs['businessBelong']), null, 'fullname');
            $initRs['fullName'] = $branchObj['fullname'];

            $this->assignFunc($initRs);
            //��Ŀ���
			$arr=explode('-',$object['detail'][0]['orgFormType']);
			$orgFormType=$arr[0];
			$this->assign("orgFormType", $orgFormType);
            $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

            $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

            //������չ������
            if (empty($object['exaCode'])) {
                $this->assign('exaId', $id);
                $this->assign('exaCode', $this->service->tbl_name);
            }
            $this->display($this->service->getBusinessCode($object['sourceType']) . '-print-expand' . $keyWork);
        }
        if (empty($ids)) {
            $ids = implode($newIdArr, ',');
        }
        $this->assign('ids', $ids);
        $this->assign('allNum', count($idArr));
        $this->assign('allMoney', $allMoney);
        $this->assign('todayStr', date("Y-m-d"));
        $this->display('batchprintalong');
    }

    /**
     * ��ر༭ - ������
     */
    function c_toReEdit() {
        //URLȨ�޿���
        $this->permCheck();
        $obj = $this->service->get_d($_GET['id'], 'detail', 'edit');

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);
        $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
        $this->assign('owner', $owner);
        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor']);
        $this->assign('detail', $detailObj[0]);
        $this->assign('coutNumb', $detailObj[1]);
        $this->display('reedit');
    }

    /**
     * ����ʱ�鿴��������ҳ��
     */
    function c_initAuditing() {
        $id = $_GET['id'];

        $this->permCheck($id);
        $orgObj = $this->service->find(array('id' => $id), null, 'sourceType');
        if (!empty($orgObj['sourceType'])) {
            $this->c_toViewAudit($id);
            exit;
        }

        $obj = $this->service->getAuditing_d($id, 'detail', 'view');

        $detailObj = $obj['detail'];
        unset($obj['detail']);


        //��Ⱦ��������
        $this->assignFunc($obj);

        $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
        $this->assign('detail', $detailObj);
        $this->assign('file', $this->service->getFilesByObjId($id, false));
        $this->display('viewauditing');
    }

    /**
     * �ҵĸ�������
     */
    function c_toMyApply() {
        $this->display('myapply-list');
    }

    /**
     * �ҵĸ�������json
     */
    function c_myApplyJson() {
        $service = $this->service;
        $service->setCompany(0);//����Ҫ���˹�˾
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $this->service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_default');
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת���ر�ҳ��
     */
    function c_toClose() {
        $id = $_GET['id'];
        $this->permCheck($id);

        $obj = $this->service->get_d($id);
        //��Ⱦ��������
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);

        $payMailType = $this->service->getMailType_d($obj['sourceType']);
        //��ȡĬ�Ϸ�����
        $rs = $this->service->getSendMen_d($payMailType);
        $this->assignFunc($rs);

        // ���ùر���
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $this->display('close');
    }

    /**
     * �رշ���
     */
    function c_close() {
        if ($this->service->close_d($_POST[$this->objName])) {
            msg('�رճɹ�');
        } else {
            msg('�ر�ʧ��');
        }
    }

    /**
     * ��ת�������ر�ҳ��
     */
    function c_toBatchClose() {
        //���ø�������id,�ر����ڼ��ر���
        $this->assign('ids', $_GET['id']);
        $this->assign('thisDate', day_date);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $this->display('batchclose');
    }

    /**
     * �����رշ���
     */
    function c_batchClose() {
        if ($this->service->batchClose_d($_POST[$this->objName])) {
            msg('�رճɹ�');
        } else {
            msg('�ر�ʧ��');
        }
    }
    /********************************�����͸��������б�*****************************/

    /**
     * Դ�����͸��������б�
     */
    function c_mySourceTypeList() {
        $sourceType = isset($_GET['sourceType']) ? $_GET['sourceType'] : 'YFRK-01';
        $this->assign('sourceType', $sourceType);
        $this->display($this->service->getBusinessCode($sourceType) . '-mysourcetypelist');
    }

    /********************��������************************/

    /**
     * ����ҳ��tab
     */
    function c_auditTab() {
        $this->display('audittab');
    }

    /**
     * �������ĸ�������
     */
    function c_auditundo() {
        $this->display('auditundo');
    }

    /**
     *  ��������������json
     */
    function c_auditundoJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $rows = $service->pageBySqlId('select_auditing');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �������ĸ�������
     */
    function c_auditdone() {
        $this->display('auditdone');
    }

    /**
     * �������ĸ�������json
     */
    function c_auditdoneJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $rows = $service->pageBySqlId('select_audited');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ����������ʷ
     */
    function c_toHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], 'purchase_contract_purchasecontract');
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('skey', $_GET['skey']);
        $this->assignFunc($obj);
        $this->display('history');
    }

    /**
     * ����������ʷjson
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);//�鿴����������ʷ������Ҫ���˹�˾
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->groupBy = 'c.id,d.objId,d.objType';
        $rows = $service->pageBySqlId('select_history');

        if (!empty($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            $rsArr = array('payMoney' => 0, 'payedMoney' => 0);
            $rsArr['formNo'] = 'ѡ��ϼ�';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //�ܼ�������
            $service->groupBy = 'd.objId,d.objType';
            $objArr = $service->listBySqlId('select_historycount');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '�ϼ�';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ����������ʷ
     */
    function c_toHistoryForObj() {
        $objId = isset($_GET['obj']['objId']) ? $_GET['obj']['objId'] : '';
        $objIds = isset($_GET['obj']['objIds']) ? $_GET['obj']['objIds'] : '';
        if (empty($objId) && empty($objIds)) {
            exit('û�д�����ز���');
        } else {
            $this->assign('userId', $_SESSION['USER_ID']);
            $this->assign('objId', $objId);
            $this->assign('objIds', $objIds);
            $this->assign('objType', $_GET['obj']['objType']);
            $this->display('historyforobj');
        }
    }

    /**
     * ���������ӡ
     */
    function c_print() {
        //URLȨ�޿���
        $this->permCheck();
        $obj = $this->service->get_d($_GET['id'], 'detail');

        $detailObj = $this->service->detailDeald_d($obj['detail']);
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
        $this->assign('detail', $detailObj);
        $this->display('print');
    }

    /**
     * �����Ѵ�ӡ����
     */
    function c_changePrintCount() {
        $printTimes = empty($_POST['printTimes']) ? $_POST['printTimes'] : 1;
        echo $this->service->changePrintCount_d($_POST['id'], $printTimes);
    }

    /**
     * �����Ѵ�ӡ���� - ��id
     */
    function c_changePrintCountIds() {
        $printTimes = !empty($_POST['printTimes']) ? $_POST['printTimes'] : 1;
        echo $this->service->changePrintCountIds_d($_POST['ids'], $printTimes);
    }

    /**
     * �б�߼���ѯ
     */
    function c_toSearch() {
        $this->showDatadicts(array('sourceType' => 'YFRK'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->view('search');
    }

    /**
     * ������ɺ�ҵ����
     */
    function c_dealAfterAudit() {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ���һ�������ϴ������Ĺ���
     */
    function c_toUploadFile() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
        $this->assignFunc($obj);

        $this->view('uploadfile');
    }

    /**
     * �ύ����֧������  - Ŀǰ���ڸ����б��е��ύ����֧��
     */
    function c_handUpPay() {
        echo $this->service->handUpPay_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ��ת���ύ����ȷ��ҳ��
     */
    function c_toConfirm() {
        //Ĭ�Ϸ�����
        $this->assign('id', $_GET['id']);
        $obj = $this->service->find(array('id' => $_GET['id']), null, 'businessBelong,isEntrust');
        $this->assignFunc($obj);
        $mailUser = $this->service->getMailUser_d('handUpPayMail', true, $obj['businessBelong']);
        $this->assign('defaultUserName', $mailUser['defaultUserName']);
        $this->assign('defaultUserId', $mailUser['defaultUserId']);
        $this->assign('supplierName', $_GET['supplierName']);
        $this->assign('payMoney', $_GET['payMoney']);
        $this->view('confirm');
    }

    /**
     * �ύ����֧������(��)
     */
    function c_handUpPay2() {
        if ($this->service->handUpPay2_d($_POST[$this->objName])) {
            msg("�ύ�ɹ�");
        } else {
            msg("�ύʧ��");
        }
    }

    /******************** S ���뵼��ϵ�� ********************/
    /**
     *  �����б�
     */
    function c_excelOut() {
        set_time_limit(0);
        $service = $this->service;

        if ($_GET['status'] == 'FKSQD-03') {
            $_GET['formDateBegin'] = isset($_GET['formDateBegin']) ? $_GET['formDateBegin'] : day_date;
            $_GET['formDateEnd'] = isset($_GET['formDateEnd']) ? $_GET['formDateEnd'] : day_date;
            $_GET['isEntrust'] = 0;
        }

        //�������ಿ�ֲ�ѯ����
        $service->getParam($_GET);

        //		print_r($service->searchArr);

        $service->sort = 'c.actPayDate DESC,c.lastPrintTime';
        $rows = $service->list_d('select_excel2');

        if (!empty($rows)) {
            //�ܼ�������
            $objArr = $service->listBySqlId('count_allnew');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['id'] = '�ϼ�';
                $rows[] = $rsArr;
            }
        }
        return model_finance_common_financeExcelUtil::exportPayablesapply($rows);
    }

    /**
     *  �����б�
     */
    function c_excelDetail() {
        set_time_limit(0);
        $service = $this->service;

        $outType = isset($_GET['outType']) ? $_GET['outType'] : '07';

        //�������ಿ�ֲ�ѯ����
        $service->getParam($_GET);
        $service->sort = 'c.formDate';
        $service->asc = false;
        $rows = $service->list_d('select_excel');

        if (!empty($rows)) {
            //�ܼ�������
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['id'] = '�ϼ�';
                $rows[] = $rsArr;
            }
        }
        if ($outType == '07') {
            return model_finance_common_financeExcelUtil::exportPayApplyDetail07($rows);
        } else {
            return model_finance_common_financeExcelUtil::exportPayApplyDetail($rows);
        }
    }

    /******************** E ���뵼��ϵ�� ********************/

    //add chenrf 20130504
    /**
     * ����ʱ�鿴�˿�����ҳ��
     */
    function c_initBack() {
        //URLȨ�޿���
        $this->permCheck();

        $id = $_GET['id'];
        $object = $this->service->get_d($id, 'clear');

        //�������Ͷ���
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //���ò���
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //������չ������
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }
        //ί�и���
        $this->assign('isEntrust', $this->service->rtYesNo_d($object['isEntrust']));
        $this->display($this->service->getBusinessCode($object['sourceType']) . '-pay' . $keyWork);
    }

    /**
     * ����������������
     */
    function c_updateAuditDate() {
        echo $this->service->update(array('id' => $_POST['id']), array('auditDate' => $_POST['auditDate'])) ? 1 : 0;
    }

    /**
     * ���������������
     */
    function c_toChangeDate() {
        $changeDao = new model_finance_payablesapply_payablesapplychange();
        $change = $changeDao->find(array('purOrderId' => $_GET['id'], 'ExaStatus' => '��������'));
        if (!empty($change)) {
            msg("�����ύ�ı�������������ڻ�δ����");
        } else {
            $arr = $this->service->find(array('id' => $_GET['id']));
            $this->assignFunc($arr);
            $this->view('changedate');
        }
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * �����·ݴ���
     * @param string $default
     * @return string
     */
    function periodDeal($default = '') {
        $period = $default ? explode('.', $default) : array();
        $defaultYear = empty($period) ? '' : $period[0];
        $defaultMonth = empty($period) ? '' : $period[1];

        $periodStr = "<select id='yearSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 2016; $i <= 2020; $i++) {
            if ($i == $defaultYear) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select> . ";

        $periodStr .= "<select id='monthSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $defaultMonth) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select>";
        $periodStr .= "<input type='hidden' id='period' name='invother[period]' value='" . $default . "'>";
        $periodStr .=<<<E
            <script type='text/javascript'>
                $(function() {
                    var changePeriod = function() {
                        var year = $("#yearSelector").val();
                        var month = $("#monthSelector").val();
                        if (year != "" && month != "") {
                            $("#period").val(year + '.' + month);
                        } else {
                            $("#period").val('');
                        }
                    }

                    $("#yearSelector").bind('change', changePeriod);
                    $("#monthSelector").bind('change', changePeriod);
                });
            </script>
E;
        return $periodStr;
    }
}