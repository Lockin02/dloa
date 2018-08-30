<?php

/**
 * @author Show
 * @Date 2011��12��5�� ����һ 10:19:51
 * @version 1.0
 * @description:������ͬ���Ʋ�
 */
class controller_contract_other_other extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 ���ù������Ž�ֹѡ��Ĳ���ID����
    private $DenyFegsdeptId = ""; // PMS772 ���ù������Ž�ֹѡ��Ĳ���ID,ͨ�����ö�����
    private $unDeptExtFilter = "";// PMS377 ��ģ����Ҫ�������صĲ���ѡ��
    private $bindId = "";
	function __construct() {
		$this->objName = "other";
		$this->objPath = "contract_other";
		parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $subsidyArr = $otherDataDao->getConfig('unSltDeptFilter');
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unSltDeptFilter = $subsidyArr;
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");
        $DenyFegsdept = $otherDataDao->getDenyFegsdept();
        if(isset($DenyFegsdept['0']) && !empty($DenyFegsdept['0'])){
            $this->DenyFegsdeptId = $DenyFegsdept['0']['belongDeptIds'];
        }

        // �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIdsArr = $otherDataDao->getConfig('unSelectableIds');
        $this->unSelectableIds = $unSelectableIdsArr;
        $this->bindId = "7627a082-a267-4e6c-b404-97e469d80ec4";
	}

	/**
	 * ��ת��������ͬ
	 */
	function c_page() {
		isset($_GET['autoload']) ? $this->assign('autoload', $_GET['autoload']) : $this->assign('autoload', '');# �Զ�����
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonFinanceInfo() {
        // ��ʼ�������������� created by huanghaojin (���ڵ���ʱ��������������)
        if(isset($_SESSION['searchArr'])){
            unset($_SESSION['searchArr']);
        }else{$_SESSION['searchArr'] = array();}

		$service = $this->service;

		//��ʼ����������
		if (isset($_POST['payandinv'])) {
			$thisSet = $service->initSetting_c($_POST['payandinv']);
			$_POST[$thisSet] = 1;
			unset($_POST['payandinv']);
		}

		//ϵͳȨ��
		$deptLimit = $this->service->this_limit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if (strstr($deptLimit, ';;')) {
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_financeInfo');
		} else {//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			if (!empty($deptLimit)) {
				$_POST['deptsIn'] = $deptLimit;
				$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
				$rows = $service->page_d('select_financeInfo');
			}
		}

		if (is_array($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);

			//�ϼƼ���
			$rows = $this->service->pageCount_d($rows);

			$objArr = $service->listBySqlId('count_list');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['createDate'] = '�ϼ�';
				$rsArr['id'] = 'noId';
				$rows[] = $rsArr;
			}
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;//������չSQL

        // ��¼������������ created by huanghaojin (���ڵ���ʱ��������������)
        $search_Array = $service->searchArr;
        unset($search_Array['isSearchTag_']);
        $_SESSION['searchArr'] = $search_Array;

		echo util_jsonUtil::encode($arr);
	}


    function c_pageForAuditView() {
        $ids = isset($_GET['ids'])? $_GET['ids'] : '';

        // ���session������ڶ�Ӧ��IDs
        if(isset($_GET['idsKey']) && isset($_SESSION[$_GET['idsKey']])){
            $ids = $_SESSION[$_GET['idsKey']];
        }

        $this->assign("ids",$ids);
        $this->view('listForAudit');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonInfo() {
        if(isset($_POST['ids']) && $_POST['ids'] != ''){
            // ��ʼ�������������� created by huanghaojin (���ڵ���ʱ��������������)
            if(isset($_SESSION['searchArr'])){
                unset($_SESSION['searchArr']);
            }else{$_SESSION['searchArr'] = array();}

            $service = $this->service;

            //��ʼ����������
            if (isset($_POST['payandinv'])) {
                $thisSet = $service->initSetting_c($_POST['payandinv']);
                $_POST[$thisSet] = 1;
                unset($_POST['payandinv']);
            }

            $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
            $rows = $service->pageBySqlId('select_financeInfo');
            $arr ['pageSql'] = $service->listSql;//������չSQL

            if (is_array($rows)) {
                //���ݼ��밲ȫ��
                $rows = $this->sconfig->md5Rows($rows);

                //�ϼƼ���
                $rows = $this->service->pageCount_d($rows);

                $objArr = $service->listBySqlId('count_list');
                if (is_array($objArr)) {
                    $rsArr = $objArr[0];
                    $rsArr['createDate'] = '�ϼ�';
                    $rsArr['id'] = 'noId';
                    $rows[] = $rsArr;
                }
            }

            $arr ['collection'] = $rows;
            //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
            $arr ['page'] = $service->page;


            // ��¼������������ created by huanghaojin (���ڵ���ʱ��������������)
            $search_Array = $service->searchArr;
            unset($search_Array['isSearchTag_']);
            $_SESSION['searchArr'] = $search_Array;
        }else{
            $arr ['collection'] = array();
            //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
            $arr ['totalSize'] = 0;
        }


        echo util_jsonUtil::encode($arr);
    }

	/**
	 * ��д����
	 */
	function c_toAdd() {
		$this->showDatadicts(array('fundType' => 'KXXZ'));
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), null, true);

		// ����������Ϣ��Ⱦ
		$this->showDatadicts(array('payFor' => 'FKLX'), null, false, array('expand1' => 1)); // ��������
		$this->showDatadicts(array('payType' => 'CWFKFS')); // ���㷽ʽ
		$this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, array("��ѡ��"=>"")); // ����ҵ������
		$this->showDatadicts(array('invoiceType' => 'FPLX'), null, true); // ��Ʊ����

//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//		$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->assign('isSysCode', ORDERCODE_INPUT); // �Ƿ��ֹ������ͬ��
		$this->assign('isShare', PAYISSHARE); // �Ƿ����÷��÷�̯

		$this->assign('userId', $_SESSION['USER_ID']);

		// ��ȡ������˾����
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		//��ȡ��������۲���id
		$this->assign('saleDeptId', expenseSaleDeptId);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
		isset($_GET['open']) ? $this->view('add', true) : $this->view('addDept', true);
	}

	/**
	 * ���� - �����ⲿ����
	 */
	function c_toAddPay() {
        $balanceDao = new model_flights_balance_balance();
        // ��Ʊ��ʱ�򣬽���Ʊ����ת����ñ���
        $costShare = $balanceDao->getCostShare_d($_GET['projectId']);

        if (!$costShare) {
            header("charset:GBK");
            exit('δ�ܻ�ȡ��Ʊ��̯���ݣ�����ϵ����Ա');
        }

		$this->showDatadicts(array('fundType' => 'KXXZ'), 'KXXZB');
		$this->assign('fundTypeHidden', 'KXXZB');
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $_GET['projectType'], true);
		$this->assign('projectTypeHidden', $_GET['projectType']);

		//����������Ϣ��Ⱦ
		$this->showDatadicts(array('payFor' => 'FKLX'), null, false, array('expand1' => 1));//��������
		$this->showDatadicts(array('payType' => 'CWFKFS'));//���㷽ʽ
//		$this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, true); // ����ҵ������
		$this->showDatadicts(array('invoiceType' => 'FPLX'), null, true); // ��Ʊ����

		$this->assign("payForBusinessName","��"); // ����ҵ������(��Ʊ��������Ĭ����ʾ�ǡ��ޡ�)
        $this->assign("payForBusiness","FKYWLX-0");

		$this->assign('projectId', $_GET['projectId']);
		$this->assign('projectCode', $_GET['projectCode']);
		$this->assign('projectName', $_GET['projectName']);
		$this->assign('orderMoney', $_GET['orderMoney']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->assign('isSysCode', ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯

		// ��ȡ������˾����
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//        $this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
		$this->view('addpay', true);
	}

	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//������ͬ���������
					succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgRf('����ɹ�');
			}
		} else {
			msgRf('����ʧ��');
		}
	}

	/**
	 * ���������������
	 */
	function c_addDept() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//������ͬ���������
					succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgGo('����ɹ���', '?model=contract_other_other&action=toAdd');
			}
		} else {
			msgGo('����ʧ�ܣ�', '?model=contract_other_other&action=toAdd');
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->editInfo_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//������ͬ���������
					succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgRf('����ɹ�');
			}
		} else {
			msgRf('����ʧ��');
		}
	}

	/**
	 * �鿴ҳ�� - ��������������Ϣ
	 */
	function c_viewAlong() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->getInfo_d($_GET['id']);
		$obj['orgFundType'] = $obj['fundType'];
        $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "��" :  "��";
        $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
		$this->assignFunc($obj);

		//�ύ������鿴����ʱ���عرհ�ť
		$this->assign('showBtn', isset($_GET['showBtn']) ? $_GET['showBtn'] : 1);

		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->assign('file1', $this->service->getFilesByObjId($obj['id'], false, 'oa_sale_otherpayapply'));

		$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
		$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

		$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

		$this->assign('payFor', $this->getDataNameByCode($obj['payFor']));
		$this->assign('payType', $this->getDataNameByCode($obj['payType']));
		$this->assign('payForBusiness', $this->getDataNameByCode($obj['payForBusiness']));//����ҵ������

		$this->assign('isInvoice', $this->service->rtYesOrNo_d($obj['isInvoice']));

		//ί�и���
		$this->assign('isEntrust', $this->service->rtYesOrNo_d($obj['isEntrust']));

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);

        $showDataSum = "<fieldset style='display:none;'>";
        if($thisObjCode == 'pay'){
            $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));

            // ��ȡͳ������
            $sumStr = "";

            // �������ʱ�޸ĵ��Ӻ�ؿ�����,����ʾ��ʱ���ݣ�ֻ������ҳ����ʾ��
            if(isset($_GET['viewOn']) && $_GET['viewOn'] == 'audit'){
                $applyManWhkMoneyIds = $applyManYqWhkMoneyIds = $signCompanyWhkMoneyIds = $signCompanyYqWhkMoneyIds = '';
                $applyManWhkMoney = $applyManYqWhkMoney = $signCompanyWhkMoney = $signCompanyYqWhkMoney = 0;

                $datadictDao = new model_system_datadict_datadict();
                $payForBusinessArr = $datadictDao->getDatadictsByParentCodes ( 'FKYWLX' ,array("dataCode" => $obj['payForBusiness']));
                if($payForBusinessArr && is_array($payForBusinessArr)){
                    $payForBusinessArr = $payForBusinessArr['FKYWLX'][0];
                }
                // ���ø���ҵ�����͵���չ�ֶ�4������ͳ�����Ƿ���Ҫ��ʾ PMS678
                $showDataSum = ($payForBusinessArr['expand4'] == 1)? "<fieldset id='dataSumTr' style='width:1180px;margin:auto;margin-top:10px;text-align:center'>" : "<fieldset style='display:none;'>";

                $applyManMoney = $this->service->sumBzjMoney("byMan",$obj['principalId']);
                $applyManWhkMoney = ($applyManMoney['needPay'] == 0)? "0.00" : $applyManMoney['needPay'];
                $applyManWhkMoneyIds = $applyManMoney['needPayIds'];
                $applyManYqWhkMoney = ($applyManMoney['needPayBeyond'] == 0)? "0.00" : $applyManMoney['needPayBeyond'];
                $applyManYqWhkMoneyIds = $applyManMoney['needPayBeyondIds'];

                $signCompanyMoney = $this->service->sumBzjMoney("byCompany",$obj['signCompanyName']);
                $signCompanyWhkMoney = ($signCompanyMoney['needPay'] == 0)? "0.00" : $signCompanyMoney['needPay'];
                $signCompanyWhkMoneyIds = $signCompanyMoney['needPayIds'];
                $signCompanyYqWhkMoney = ($signCompanyMoney['needPayBeyond'] == 0)? "0.00" : $signCompanyMoney['needPayBeyond'];
                $signCompanyYqWhkMoneyIds = $signCompanyMoney['needPayBeyondIds'];


                $this->assign('signCompany',$obj['signCompanyName']);
                $this->assign('applyMan',$obj['principalName']);

                $this->assign('applyManWhkMoney',$applyManWhkMoney);//δ�ؿ�/��Ʊ��֤��
                $this->assign('applyManWhkMoneyIds',$applyManWhkMoneyIds);
                $this->assign('applyManYqWhkMoney',$applyManYqWhkMoney);//����δ�ؿ�/��Ʊ��֤��
                $this->assign('applyManYqWhkMoneyIds',$applyManYqWhkMoneyIds);
                $this->assign('signCompanyWhkMoney',$signCompanyWhkMoney);//δ�ؿ�/��Ʊ��֤��
                $this->assign('signCompanyWhkMoneyIds',$signCompanyWhkMoneyIds);
                $this->assign('signCompanyYqWhkMoney',$signCompanyYqWhkMoney);//����δ�ؿ�/��Ʊ��֤��
                $this->assign('signCompanyYqWhkMoneyIds',$signCompanyYqWhkMoneyIds);
                if($obj['delayPayDaysTemp'] != '' && $obj['delayPayDaysTemp'] >= 0 && $obj['delayPayDaysTemp'] != $obj['delayPayDays']){
                    $this->assign('delayPayDays',$obj['delayPayDaysTemp']);

                }
            }
        }
        $this->assign('showDataSum',$showDataSum);

        $this->assign('viewAct',isset($_GET['act'])? $_GET['act'] : '');
        $this->assign('buffersDayStyle',(isset($_GET['act']) && $_GET['act'] == "auditView")? "style='color:red'" : '');
        $bufferDaysIsShow = '';
        if(!isset($_GET['act'])){
            $bufferDaysIsShow = '1';
            $this->assign('viewAct','auditView');
        }
        $this->assign('bufferDaysIsShow',$bufferDaysIsShow);
        // �ж��Ƿ���Ҫ��ʾ����֤������������ͬ��
        $displayTd1 = 'style="display:none;"';
        $td0ColSpan = "colspan='5'";
        $hasRelativeContract = "";
        if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
            $displayTd1 = '';
            $td0ColSpan = '';
            if($obj['hasRelativeContract'] == 2){
                $hasRelativeContract = "��";
            }
        }

        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);
        $this->assign('hasRelativeContract',$hasRelativeContract);

        $otherDataDao = new model_common_otherdatas();
        $editorsId = $otherDataDao->getConfig('bufferDaysEditors');
        $editorsId = explode(",",$editorsId);
        $bufferDaysEditLimit = in_array($_SESSION['USER_ID'],$editorsId)? "1" : "";
        $this->assign('bufferDaysEditLimit',$bufferDaysEditLimit);

        // Դ������ѡ�� PMS 650
        $codeType = $codeValue = "";
        if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeType = "���ۺ�ͬ";
                $codeValue = $obj['contractCode'];
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeType = "�̻�";
                $codeValue = $obj['chanceCode'];
            }
        }
        $this->assign('codeType', $codeType);
        $this->assign('codeValue', $codeValue);

        $this->view($thisObjCode . '-viewAlong');
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck(); //��ȫУ��

		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$obj = $this->service->getInfo_d($_GET['id']);
			$obj['orgFundType'] = $obj['fundType'];
            $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "��" :  "��";
            $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
			$this->assignFunc($obj);
			//�ύ������鿴����ʱ���عرհ�ť
			if (isset($_GET['viewBtn'])) {
				$this->assign('showBtn', 1);
			} else {
				$this->assign('showBtn', 0);
			}
			//�������{file}
			$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));

			$this->assign('fundType', $this->getDataNameByCode($obj['fundType']));

			$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
			$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

			//ǩ��״̬
			$this->assign('signedStatusCN', $this->service->rtIsSign_d($obj['signedStatus']));

			$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

            $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));

            $this->assign('viewAct',isset($_GET['act'])? $_GET['act'] : '');
            // �ж��Ƿ���Ҫ��ʾ����֤������������ͬ��
            $displayTd1 = 'style="display:none;"';
            $td0ColSpan = "colspan='5'";
            $hasRelativeContract = "";
            if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
                $displayTd1 = '';
                $td0ColSpan = '';
                if($obj['hasRelativeContract'] == 2){
                    $hasRelativeContract = "��";
                }
            }

            $this->assign('td0ColSpan',$td0ColSpan);
            $this->assign('displayTd1',$displayTd1);
            $this->assign('hasRelativeContract',$hasRelativeContract);

            // Դ������ѡ�� PMS 650
            $codeType = $codeValue = "";
            if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
                if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                    $codeType = "���ۺ�ͬ";
                    $codeValue = $obj['contractCode'];
                }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                    $codeType = "�̻�";
                    $codeValue = $obj['chanceCode'];
                }
            }
            $this->assign('codeType', $codeType);
            $this->assign('codeValue', $codeValue);

			$this->view('view');
		} else {
            //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
            //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
            $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

            // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
            $unSltDeptFilter = $this->unSltDeptFilter;
            $this->assign('unSltDeptFilter', $unSltDeptFilter);

            // PMS 180 �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
            $unSelectableIds = $this->unSelectableIds;
            $this->assign('unSelectableIds', $unSelectableIds);

			$obj = $this->service->getInfo_d($_GET['id']);

			$this->assignFunc($obj);

			//����
			$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
			$this->assign('file1', $this->service->getFilesByObjId($obj['id'], true, 'oa_sale_otherpayapply'));

			$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
			$this->showDatadicts(array('fundType' => 'KXXZ'), $obj['fundType']);
			$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // ��Ʊ����

			$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

//            echo"<pre>";print_r($obj);exit();

            $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">��</option><option value="2" selected>��</option>' : '<option value="1" selected>��</option><option value="2">��</option>';
            $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);


			//����������Ϣ��Ⱦ
			$this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor'], false, array('expand1' => 1));//��������
			$this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);//���㷽ʽ
            $this->assign('payForBusinessName',  $obj['payForBusinessName']);//����ҵ������
            $this->assign('payForBusiness', $obj['payForBusiness']);//����ҵ������
            if($obj['projectType'] == 'QTHTXMLX-03'){// ��Ʊ��Ĭ��ѡ����
                $this->assign('payForBusinessOpts',"<option value='FKYWLX-0' selected>��</option>");
            }else{
                $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));
            }

			$this->assign('userId', $_SESSION['USER_ID']);
			$this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯
			$this->assign('payee', $this->getDataNameByCode($obj['payee']));
			$this->assign('comments', $this->getDataNameByCode($obj['comments']));

			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$objs = $payablesapplyDao->getPayinfo_d($_GET['id']);
			//isʡ��
			$this->assign('deptIsNeedProvince', $payablesapplyDao->deptIsNeedProvince_d($obj['feeDeptId']));
			//isʡ��
			$this->assign('provinceName', $objs[0]['provinceName']);
			//��ȡ��������۲���id
			$this->assign('saleDeptId', expenseSaleDeptId);

            // ���������Ϣ����
            $stampConfigDao = new model_system_stamp_stampconfig();
            $legalPersonUsername = $legalPersonName = $businessBelongId = '';
            if($obj['isNeedStamp'] == 1 && $obj['stampIds'] != ''){
                $stampIdArr = explode(",",$obj['stampIds']);
                $stampConfigInfo = $stampConfigDao->get_d($stampIdArr[0]);
                if($stampConfigInfo){
                    $legalPersonUsername = $stampConfigInfo['legalPersonUsername'];
                    $legalPersonName = $stampConfigInfo['legalPersonName'];
                    $businessBelongId = $stampConfigInfo['businessBelongId'];
                }
            }
            $this->assign('legalPersonUsername', $legalPersonUsername);
            $this->assign('legalPersonName', $legalPersonName);
            $this->assign('businessBelongId', $businessBelongId);

            $expenseDao = new model_finance_expense_expense();
            // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
            $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
            $this->assign('feemansForXtsSales', $feemansForXtsSales);

            // Դ������ѡ�� PMS 650
            $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
            if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
                if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                    $codeTypeOpts = "<option value=\"���ۺ�ͬ\" selected>���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
                }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                    $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\" selected>�̻�</option>";
                }
            }
            $this->assign('codeTypeOpts', $codeTypeOpts);

			$this->view('edit', true);
		}
	}

    /**
     * ���ƺ�ͬ
     */
    function  c_toCopyAdd(){
        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

        $obj = $this->service->getInfo_d($_GET['id']);

        $this->assignFunc($obj);

        //����
//        $this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
//        $this->assign('file1', $this->service->getFilesByObjId($obj['id'], true, 'oa_sale_otherpayapply'));
        $this->assign('file1', '');

        $this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
        $this->showDatadicts(array('fundType' => 'KXXZ'), $obj['fundType']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // ��Ʊ����

        $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">��</option><option value="2" selected>��</option>' : '<option value="1" selected>��</option><option value="2">��</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);


        //����������Ϣ��Ⱦ
        $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor'], false, array('expand1' => 1));//��������
        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);//���㷽ʽ
        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));
        $this->assign('payForBusinessName', util);//����ҵ������
        $this->assign('payForBusiness', $obj['payForBusiness']);//����ҵ������

        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯
        $this->assign('payee', $this->getDataNameByCode($obj['payee']));
        $this->assign('comments', $this->getDataNameByCode($obj['comments']));

        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $objs = $payablesapplyDao->getPayinfo_d($_GET['id']);
        //isʡ��
        $this->assign('deptIsNeedProvince', $payablesapplyDao->deptIsNeedProvince_d($obj['feeDeptId']));
        //isʡ��
        $this->assign('provinceName', $objs[0]['provinceName']);
        //��ȡ��������۲���id
        $this->assign('saleDeptId', expenseSaleDeptId);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $this->assign('userId', $_SESSION['USER_ID']);

        // ��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        //��ʾ������Ϣ
//        $uploadFile = new model_file_uploadfile_management ();
//        $files = $uploadFile->getFilesByObjId ( $_GET ['id'], $this->service->tbl_name );
        $fileStr='';
//        if(is_array($files)){
//            foreach($files as $fKey=>$fVal){
//                $i=$fKey+1;
//                //���븽����
//                $fileArr['serviceType']=$this->service->tbl_name;
//                $fileArr['originalName']=$fVal['originalName'];
//                $fileArr['newName']=$this->service->tbl_name."-".$fVal['newName'];
//                $UPLOADPATH2=UPLOADPATH;
//                $newPath=str_replace('\\','/',$UPLOADPATH2);
//                $destDir=$newPath.$this->service->tbl_name."/";
//                $fileArr['uploadPath']=$destDir;
//                $fileArr['tFileSize']=$fVal['tFileSize'];
//                $test = $uploadFile->add_d ( $fileArr, true );
//                $fileStr.='<div class="upload" id="fileDiv'.$test.'"><a title="�������" href="?model=file_uploadfile_management&amp;action=toDownFileById&amp;fileId='.$test.'">'.$fVal['originalName'].'</a>&nbsp;<img src="images/closeDiv.gif" onclick="delfileById('.$test.')" title="���ɾ������"><div></div></div><input type="hidden" name="fileuploadIds['.$i.']" value="'.$test.'">';
//            }
//        }

        // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        $this->show->assign("file",$fileStr);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        // Դ������ѡ�� PMS 650
        $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
        if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeTypeOpts = "<option value=\"���ۺ�ͬ\" selected>���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\" selected>�̻�</option>";
            }
        }
        $this->assign('codeTypeOpts', $codeTypeOpts);

        $this->view('copy-add', true);

    }

	/**
	 * ��ͬtabҳ
	 */
	function c_viewTab() {
		$this->assign('id', $_GET['id']);
		$obj = $this->service->get_d($_GET['id']);
		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->display($thisObjCode . '-viewtab');
	}

    /**
     * ����򲻿�Ʊ��¼ҳ��
     */
	function c_toShowCostChangeRecord(){
        $this->assign('type', $_GET['type']);
        $this->assign('objId', $_GET['objId']);
        $this->view('listCostChangeRecord');
    }

    /**
     * ����򲻿�Ʊ��¼ Json ����
     */
    function c_listCostChangeRecordJson(){
        $type = $_POST['type'];
        $objId = $_POST['objId'];
        $service = $this->service;
        $service->searchArr['costChangeType'] = $type;
        $service->searchArr['costChangeobjId'] = $objId;
        $service->setCompany(0);

        $rows = $service->page_d('slt_costChangeRecord');

        if($type == 'uninvoiceMoney'){
            // ���ͳ����
            $rowsCount = $service->listBySqlId('slt_costChangeRecordCount');
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['objCode'] = '�ϼ�';
            $rsArr['costAmount'] = is_array($rowsCount)? $rowsCount[0]['costAmount'] : '0';
            $rows[] = $rsArr;
        }

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
    }

	/**
	 * ���·�����
	 */
	function c_toUpdateReturnMoney() {
		//��ȡ��ͬ������Ϣ
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('updatereturnmoney');
	}

	/**
	 * ���·�����
	 */
	function c_updateReturnMoney() {
		if ($this->service->edit_d($_POST[$this->objName])) {
			msg('����ɹ�');
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת�������ҳ��
	 */
	function c_toStamp() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('applyDate', day_date);
		$this->assign('file', '�����κθ���');

		//��ǰ����������
		$this->assign('thisUserId', $_SESSION['USER_ID']);
		$this->assign('thisUserName', $_SESSION['USERNAME']);

		$this->view('stamp');
	}

	/**
	 * ����������Ϣ����
	 */
	function c_stamp() {
		if ($this->service->stamp_d($_POST[$this->objName])) {
			msg("����ɹ���");
		} else {
			msg("����ʧ�ܣ�");
		}
	}

	/**
	 * ������ɺ�����µķ���
	 */
	function c_dealAfterAudit() {
		$this->service->dealAfterAudit_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ������ͬ���������
	 */
	function c_dealAfterAuditPayapply() {
		$this->service->dealAfterAuditPayapply_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��ת����������ͬ
	 */
	function c_myOther() {
		$this->view('mylist');
	}

    /**
     * ��ȡ��ҳ����ת��Json����д��
     */
    function c_pageJson() {
        $service = $this->service;

        if(isset($_REQUEST['isSelf']) && $_REQUEST['isSelf'] == 1){
            $this->c_myOtherListPageJson();
        }else{
            $service->getParam ( $_REQUEST );
            //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

            //$service->asc = false;
            $rows = $service->page_d ();
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows ( $rows );
            $arr = array ();
            $arr ['collection'] = $rows;
            //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
            $arr ['page'] = $service->page;
            $arr ['advSql'] = $service->advSql;
            $arr ['listSql'] = $service->listSql;
            echo util_jsonUtil::encode ( $arr );
        }
    }

	/**
	 * �ҵ�������ͬ
	 */
	function c_myOtherListPageJson() {
		$service = $this->service;

        $userId = '';
        if(isset($_POST['principalId'])){
            $userId = $_POST['principalId'];
            unset($_POST['principalId']);
        }

		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->searchArr['principalIdAndCreateId'] = ($userId == '')? $_SESSION['USER_ID'] : $userId;
		$service->setCompany(0); # �����б�,����Ҫ���й�˾����
		$rows = $service->page_d('select_financeInfo');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * �رպ�ͬ
	 */
	function c_changeStatus() {
		echo $this->service->edit_d(array('id' => $_POST['id'], 'status' => '3')) ? 1 : 0;
	}

	/**
	 * �����ϴ�
	 */
	function c_toUploadFile() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits() {
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���ϵ�� *********************/
	/**
	 * �������ҳ��
	 */
	function c_toChange() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		//����
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
		$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // ��Ʊ����

		//������������ѡ��
		$datadictDao = new model_system_datadict_datadict ();
		$rs = $datadictDao->find(array('dataCode' => $obj['fundType']), null, 'expand1');
		$this->assign('isNeed', $rs['expand1']);
		//��ȡ��������۲���id
		$this->assign('saleDeptId', expenseSaleDeptId);

        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));
        $this->assign('payForBusinessName', util);//����ҵ������
        $this->assign('payForBusiness', $obj['payForBusiness']);//����ҵ������

        // ��ѯ�Ƿ���ڸ���������߷�̯��¼
        $payablesApplyDao = new model_finance_payablesapply_payablesapply();
        $costShareDao = new model_finance_cost_costshare();
        if ($payablesApplyDao->getApplyAndDetail_d(array('objType' => 'YFRK-02', 'objId' => $obj['id']))
            || $costShareDao->getShareList_d($obj['id'], 2)) {
            $this->assign('canChangeCurrency', '0');
        } else {
            $this->assign('canChangeCurrency', '1');
        }

        $this->assign('userId', $obj['createId']);
        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">��</option><option value="2" selected>��</option>' : '<option value="1" selected>��</option><option value="2">��</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        // Դ������ѡ�� PMS 650
        $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
        if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeTypeOpts = "<option value=\"���ۺ�ͬ\" selected>���ۺ�ͬ</option><option value=\"�̻�\">�̻�</option>";
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeTypeOpts = "<option value=\"���ۺ�ͬ\">���ۺ�ͬ</option><option value=\"�̻�\" selected>�̻�</option>";
            }
        }
        $this->assign('codeTypeOpts', $codeTypeOpts);

		$this->view('change');
	}

	/**
	 * �������
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$object = $_POST[$this->objName];
		try {
			$id = $this->service->change_d($object);
			if ($object['fundType'] == 'KXXZB') {
                $originalObj = $this->service->get_d($object['oldId']);
                $this->service->updateChangeDetailField($id,$originalObj);

				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
			} else {
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
			}
		} catch (Exception $e) {
			msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
		}
	}

	/**
	 * ������ɺ�����µķ���
	 */
	function c_dealAfterAuditChange() {
		$this->service->dealAfterAuditChange_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ����鿴tab
	 */
	function c_changeTab() {
		$this->permCheck(); //��ȫУ��
		$this->assign('id', $_GET['id']);

		$rs = $this->service->find(array('id' => $_GET['id']), null, 'originalId,fundType');
		$this->assign('originalId', $rs['originalId']);

		// ���Ե���ҳ��
		$thisObjCode = $this->service->getBusinessCode($rs['fundType']);
		$this->display($thisObjCode . '-changetab');
	}

	/**
	 * ����鿴��ͬ  - �鿴ԭ��ͬ
	 */
	function c_changeView() {
//		$this->permCheck(); //��ȫУ��

		$obj = $this->service->get_d($_GET['id']);
        $obj['orgFundType'] = $obj['fundType'];
        $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "��" :  "��";
        $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
		$this->assignFunc($obj);

		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

		$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
		$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
		$this->assign('isNeedRestamp', $this->service->rtYesOrNo_d($obj['isNeedRestamp']));

        $this->assign('payForBusiness', $this->getDataNameByCode($obj['payForBusiness']));//����ҵ������
        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("��ѡ��"=>""));

		$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

        // �ж��Ƿ���Ҫ��ʾ����֤������������ͬ��
        $displayTd1 = 'style="display:none;"';
        $td0ColSpan = "colspan='5'";
        $hasRelativeContract = "";
        if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
            $displayTd1 = '';
            $td0ColSpan = '';
            if($obj['hasRelativeContract'] == 2){
                $hasRelativeContract = "��";
            }
        }

        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);
        $this->assign('hasRelativeContract',$hasRelativeContract);

        if($obj['delayPayDaysTemp'] != '' && $obj['delayPayDaysTemp'] >= 0 && $obj['delayPayDaysTemp'] != $obj['delayPayDays']){
            $this->assign('delayPayDays',$obj['delayPayDaysTemp']);
        }
        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);

        $otherDataDao = new model_common_otherdatas();
        $editorsId = $otherDataDao->getConfig('bufferDaysEditors');
        $editorsId = explode(",",$editorsId);
        $bufferDaysEditLimit = in_array($_SESSION['USER_ID'],$editorsId)? "1" : "";
        $this->assign('bufferDaysEditLimit',$bufferDaysEditLimit);

        // Դ������ѡ�� PMS 650
        $codeType = $codeValue = "";
        if($obj['payForBusiness'] == "FKYWLX-06"){// �б�����
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeType = "���ۺ�ͬ";
                $codeValue = $obj['contractCode'];
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeType = "�̻�";
                $codeValue = $obj['chanceCode'];
            }
        }
        $this->assign('codeType', $codeType);
        $this->assign('codeValue', $codeValue);

		$this->view('changeview');
	}
	/******************* E ���ϵ�� *********************/

	/******************* S ǩ��ϵ�� *********************/
	/**
	 * ��ͬǩ�� - �б�tabҳ
	 */
	function c_signTab() {
		$this->display('signTab');
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signingList() {
		$this->view('signinglist');
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signedList() {
		$this->view('signedlist');
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_toSign() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->showDatadicts(array('outsourceType' => 'HTWB'), $obj['outsourceType']);
		$this->showDatadicts(array('payType' => 'HTFKFS'), $obj['payType']);//��ͬ���ʽ
		$this->showDatadicts(array('outsourcing' => 'HTWBFS'), $obj['outsourcing']);//��ͬ�����ʽ

		$this->view('sign');
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_sign() {
		if ($this->service->sign_d($_POST[$this->objName])) {
			msgRf('ǩ�ճɹ�');
		} else {
			msgRf('ǩ��ʧ��');
		}
	}

	/******************* E ǩ��ϵ�� *********************/

	/******************* S ���뵼������ *******************/
	/**
	 * ��������
	 */
	function c_exportExcel() {

		$service = $this->service;

        // ����¼��session�ڵ����������ֶμ��봫��������� created by Huanghaojin(���ڵ���ʱ��������������)
        if(is_array($_SESSION['searchArr'])){
            foreach ($_SESSION['searchArr'] as $k => $v) {
                if(!isset($_REQUEST[$k])){
                    $_REQUEST[$k] = $v;
                }
            }
        }

        $service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = 'c.createTime';
		$rows = $service->listBySqlId('select_financeInfo');
		return model_contract_common_contractExcelUtil::otherContractOut_e($rows);
	}
	/******************* E ���뵼������ *******************/

	/******************* S �޸ķ�̯��ϸ *****************/

	/**
	 * �޸ķ��÷�̯
	 */
	function c_toChangeCostShare() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('changeType', isset($_GET['changeType']) ? $_GET['changeType'] : '');
		$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // ��Ʊ����

        $showPayType = ($obj['fundType'] == "KXXZB")? "" : "hide-box";
        $longColspan = ($obj['fundType'] == "KXXZB")? "" : "3";
        $this->assign('showPayType', $showPayType);
        $this->assign('longColspan', $longColspan);

        // PMS 180 �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIds = ($obj['payForBusiness'] == "FKYWLX-0")? $this->unSelectableIds : "";
        $this->assign('unSelectableIds', $unSelectableIds);

        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

		//��ȡ��������۲���id
		$this->assign('saleDeptId', expenseSaleDeptId);

        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">��</option><option value="2" selected>��</option>' : '<option value="1" selected>��</option><option value="2">��</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

		$this->view('changeCostShare');
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_changeCostShare() {
		if ($this->service->changeCostShare_d($_POST[$this->objName])) {
			msgRf('�޸ĳɹ�');
		} else {
			msgRf('�޸�ʧ��');
		}
	}

	/******************* E �޸ķ�̯��ϸ *****************/

	/**
	 * ��֤����
	 */
	function c_canPayapply() {
		echo $this->service->canPayapply_d($_POST['id']);
	}

	/**
	 * �˿�������֤
	 */
	function c_canPayapplyBack() {
		echo $this->service->canPayapplyBack_d($_POST['id']);
	}

	/**
	 * �رպ�ͬ
	 */
	function c_toClose() {
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		// ��ȡ�����ùرպ�ͬȨ��
		$this->assign('closeLimit', !$this->service->this_limit['�رպ�ͬȨ��'] ? 1 : 0);
		$this->view('close');
	}

	/**
	 * �رշ���
	 */
	function c_close() {
		$object = $_POST[$this->objName];
		$closeLimit = $object['closeLimit'];
		unset($object['closeLimit']);
        //�߱��رպ�ͬȨ�޵�������������
        if ($closeLimit) {
            $object['status'] = "3";
            $object['ExaStatus'] = "���";
            $object['ExaDT'] = date("Y-m-d H:i:s");
        }
        if ($this->service->edit_d($object)) {
            if ($closeLimit) {
                msg('�ύ�ɹ�');
            } else {
                succ_show('controller/contract/other/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
            }
        } else {
            msg('�ύʧ��');
        }
    }

    /**
     * ���ݺ�ͬID��ȡ��һ�������Ĵ���״̬��no:δ����, ok:�Ѵ���
     */
    function c_ajaxChkIsFirstAudit(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $sql = "select p.Result from flow_step_partent p left join wf_task w on p.Wf_task_ID = w.task where w.code = 'oa_sale_other' AND w.finish IS NULL AND w.Pid = '{$objId}' AND p.SmallId = 1;";
        $result = $this->service->_db->getArray($sql);
        if($result){
            echo ($result[0]['Result'] == "")? "ok" : "no";
        }else{
            echo "no";
        }
    }

    /**
     * ajax ������ʱ�Ӻ�ؿ������ֶ�
     */
    function c_ajaxUpdateDelayPayDaysTemp(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $newDelayPayDays = isset($_POST['delayPayDays'])? $_POST['delayPayDays'] : '';
        $isChange = isset($_POST['isChange'])? $_POST['isChange'] : '';
        $updateArr = ($isChange == 1)? array("id"=>$objId,"delayPayDaysTemp"=>$newDelayPayDays,"delayPayDays"=>$newDelayPayDays) : array("id"=>$objId,"delayPayDaysTemp"=>$newDelayPayDays);
        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * ajax ���»��������ֶ�
     */
    function c_ajaxUpdateBufferDays(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $newBufferDays = isset($_POST['bufferDays'])? $_POST['bufferDays'] : '';
        $updateArr = array("id"=>$objId,"bufferDays"=>$newBufferDays);
        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * �ؿ��ʼ�
     */
    function c_toSendMail()
    {
        $this->view('sendmail');
    }

    /**
     * ���ͻؿ��ʼ�
     */
    function c_sendMail()
    {
        $this->service->sendMail_d($_POST[$this->objName]);
        msg('���ͳɹ�');
    }

    /**
     * �ؿ��ʼ���ȡ
     */
    function c_getSendMailInfo()
    {
        echo util_jsonUtil::encode($this->service->getSendMailInfo_d(util_jsonUtil::iconvUTF2GB($_POST['signCompanyName'])));
    }
}