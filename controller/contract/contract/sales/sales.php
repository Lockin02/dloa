<?php

/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 */
class controller_contract_sales_sales extends controller_base_action {

	function __construct() {
		$this->objName = "sales";
		$this->objPath = "contract_sales";
		parent :: __construct();
	}

	/********************��*************************/
	//TODO;
	/**
	 * ��ͬ���ҳ��
	 */
	function c_index() {
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->display('add');
	}

	/**
	 * ��Ӷ���
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id && $_GET['act'] == 'save') {
			showmsg('����ɹ���', '?model=contract_sales_sales&action=myApplyTab');
		}
		elseif ($id) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_contract_sales');
		} else {
			showmsg('����ʧ�ܣ�', '?model=contract_sales_sales&action=myApplyTab');
		}
	}

	/*******************ɾ***************************/
	/*
	 * ��дɾ������
	 */
	function c_deletes() {
		try {
			$delete=$this->service->deletes_d ( $_GET ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * ɾ������-���ݿ�����׶�
	 */
	function c_del() {
		if ($this->service->deletes_d($_GET['id'])) {
			msg('ɾ���ɹ���');
		} else {
			msg('ɾ��ʧ�ܣ�');
		}
	}

	/**
	 * ɾ������-��ɾ��-���ݿ�����׶�
	 */
	function c_notdel() {
		if ($this->service->notDel($_GET['id'])) {
			msg('ɾ���ɹ���');
		} else {
			msg('ɾ��ʧ�ܣ�');
		}
	}


	/*******************��*****************************/
	//TODO;
	/**
	 * �޸Ķ���-�ݶ�ֻ����û���ύ�������ĺ�ͬ
	 */
	function c_edit() {
		$object = $this->service->edit_d($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			msgRf('����ɹ���');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&examCode=oa_contract_sales&formName=���ۺ�ͬ����');
		} else {
			msgRf('����ʧ�ܣ�');
		}
	}

	/**
	 * ��д��ͬ��Ϣ�޸� infoedit
	 */
	 function c_infoedit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->editBySelf ( $object, $isEditInfo )) {
			msg ( '����ɹ���' );
		}
	}

	/**
	 * ���±༭����-ʹ���ڱ���غ����±༭�Ķ���
	 */
	function c_editForBackAction() {
		$object = $this->service->editAgainAfterBack($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			msgRf('����ɹ���');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_sales&formName=���ۺ�ͬ����');
		} else {
			msgRf('����ʧ�ܣ�', 'index1.php?model=contract_sales_sales&action=myApplyContractAction');
		}
	}

	/**
	 * �����ͬ
	 */
	function c_changeContract() {
		$object = $this->service->contractChange($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			showmsg('����ɹ���', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=��ͬ���');
		} else {
			showmsg('����ʧ�ܣ�', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction');
		}
	}

	/**
	 * ��ʼ������-���ڱ༭��ͬ
	 */
	function c_init() {

		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForEdit($rowsT);
		//����
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber']);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('edit');
	}

	/**
	 * ��ʼ������-��غ�༭��ͬ
	 */
	function c_editAfterBackAction() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForEdit($rowsT);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('backedit');
	}

	/**
	 * ��ʼ������-���ڱ����ͬ
	 */
	function c_readInfoForChange() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForChange($rowsT);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$this->assign('applyTime', day_date);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->assign('formNumber', $this->businessCode());

		$this->display('change');
	}

     /**
      * �����ͬ�������޸�
      */
      function c_readInfoForChangeEdit() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForChange($rowsT);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$this->assign('applyTime', day_date );
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->assign('formNumber', $this->businessCode());

		$this->display('change-edit');
	}

	/**
	 * ҳ����ʾ-ָ����ͬ������
	 */
	function c_changePrincipal() {
		$rowsT = $this->service->getContractInfo_d($_GET['id'], 'none');
		$this->assign('id', $_GET['id']);
		$this->assign('contractNumber', $rowsT['contNumber']);
		$this->assign('contractName', $rowsT['contName']);
		$this->assign('oldprincipalName', $rowsT['principalName']);
		$this->assign('oldprincipalId', $rowsT['principalId']);
		$this->show->display('contract_common_changeprincipal');
	}

	/**
	 * ҳ����ʾ-ָ����ִͬ����
	 */
	function c_changeExecute() {
		$rowsT = $this->service->getContractInfo_d($_GET['id'], 'none');
		$this->assign('id', $_GET['id']);
		$this->assign('contractNumber', $rowsT['contNumber']);
		$this->assign('contractName', $rowsT['contName']);
		$this->assign('oldexecutorName', $rowsT['executorName']);
		$this->assign('oldexecutorId', $rowsT['executorId']);
		$this->show->display('contract_common_changeexecutor');
	}

	/**
	 * ���ݲ���-ָ����ͬ������/ִ����
	 */
	function c_changePeopleAction() {
		$object = $this->service->editBySelf($_POST[$this->objName]);
		if ($object) {
			msg('ָ���ɹ���');
		} else {
			msg('ָ��ʧ�ܣ�');
		}
	}

	/**
	 * �����ͬ����ִ��ҳ��
	 */
	function c_contractBeginAction() {
		$returnObj = $this->service->returnSignStatus($_GET['id']);
		$this->assign('contNumber', $_GET['contNumber']);
		$this->assign('contractId', $_GET['id']);
		$this->assign('signStatus', $this->service->showSignStatus($returnObj['signStatus']));
		$this->show->display('contract_common_bcinfo-begin');
	}

	function c_beginAction() {
		$object = $this->service->contractBegin($_POST[$this->objName]);
		if ($object) {
			msg('�����ɹ���');
		} else {
			msg('����ʧ�ܣ�');
		}
	}

	/**
	 * ����رպ�ͬҳ��
	 */
	function c_intoCloseAction() {
		$rows = $this->service->returnMainMsg($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('id', $_GET['id']);
		$this->assign('closeTime', day_date);
		$this->assign('closerName', $_SESSION['USERNAME']);
		$this->assign('closerId', $_SESSION['USER_ID']);
		$this->showDatadicts(array ('closeType' => 'GBYY'));
		$this->show->display('contract_common_close');
	}

	/**
	 * �رպ�ͬ
	 */
	function c_closeAction() {
		$object = $this->service->contractClose($_POST[$this->objName]);
		if ($object) {
			msg('�رճɹ���');
		} else {
			msg('�ر�ʧ�ܣ�');
		}
	}

	/*******************��*****************************/
	//TODO;
	/**
	 * ��ͬ����
	 */
	function c_infoTab(){
		$this->assign('id',$_GET['id']);
		$this->assign('contNumber' , $_GET['contNumber']);
		$this->display( 'salesinfo-tab');
	}

	/**
	 * ������ͬʱ�鿴��ͬ��Ϣ
	 */
	function c_readBaseInfoInEx() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		//����
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);

		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('readinex');
	}

	/*
	 * �鿴��ͬ��Ϣ
	 */
	function c_salesinfo() {
		$this->assign('id',$_GET['id']);
		$this->display('salesinfo-tab');
	}
	/**
	 * ��ʼ������-�鿴��ͬ��Ϣ-������ͬ��ؼ�¼
	 */
	function c_readDetailedInfo() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);
		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('view-baseinfo');
	}

	/**
	 * ��ʼ������-�鿴��ͬ��Ϣ-������ͬ��ؼ�¼-���޸�ҳ��
	 */
	 function c_readDetailedInfoNoedit(){
	 	$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);
		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('view');
	 }

	/*
	 * �鿴��ͬ��Ϣ�޸�ҳ��
	 */
	 function c_BaseInfoEdit(){
 		$rowsT = $this->service->getContractInfo_d($_GET['id'],'none');
		$rows = $this->service->contractInfoForEdit($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);
		$this->assignFunc($rows);
		//�ı�ǩԼ״̬
		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->assign('contractId', $_GET['id']);
		$this->display('baseinfo-edit');
	 }

	/***********************�б�*********************/
	//TODO;
	/**
	 * �����������ۺ�ͬ
	 */
	function c_showExaSalesAction() {
		$this->display('zsp');
	}

	/*
	 * ��д��������ͬ��pageJson
	 */
	 function c_zspPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;
		$rows = $service->pageBySqlId('contract_list');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * δִ�еĺ�ͬ
	 */
	function c_showNotExecutingSaleAction() {
		$this->display('wzx');
	}

	/*
	 * ��дδִ�е�PageJson
	 */
	 function c_wzxPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;
        $rows = $service->pageBySqlId('contract_list');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }


	/**
	 * ִ���еĺ�ͬtab
	 */
	function c_zzxTab() {
		$this->display('zzx-tab');
	}

	/**
	 * ִ���еĺ�ͬ
	 */
	function c_showExecutingSalesAction() {
		$this->display('zzx');
	}

	 /*
      * ��д��ִ�е�PageJson����
      */
     function c_zzxPageJson() {
     	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;

        $rows = $service->pageBySqlId('contract_list');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
     }

	/**
	 * ��ͬ�豸ִ�����
	 */
	function c_showExecutingSalesDetail() {
		$service = $this->service;
		$this->assign('sealocation', '"?model=contract_sales_sales&action=showExecutingSalesDetail&"+searchfield+"="+searchvalue');
		/****רҵ�ָ���****/

		$service->getParam($_GET);
		$service->searchArr['contStatusEqual'] = 1;
		$service->sort = 'updateTime';
		$rows = $service->page_d();
		$this->pageShowAssign();
		$this->assign('list', $service->showExecuting_s($rows));
		$this->display('list');
	}

	/**
	 * ����еĺ�ͬ
	 */
	function c_showChangingSalesAction() {
		$this->display('zbg');
	}

	/*
	 * ��д�ڱ����PageJson����
	 */
	 function c_zbgPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->searchArr['changeStatus'] = 1;
		$service->searchArr['changingstatus'] = '';

        $rows = $service->pageBySqlId('contract_changing');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * �ѹرյĺ�ͬ
	 */
	function c_showClosedSalesAction() {
		$this->display('ygb');
	}
	/*
	 * ��д�ѹرյ�PageJson����
	 */
	 function c_pageJsonClose() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;

        $rows = $service->pageBySqlId('contract_close');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * �ҵĺ�ͬ����tab
	 */
	function c_myApplyTab() {
       $this->display('myapply-tab');
	}
	/**
	 * �ҵĺ�ͬ����
	 */
	function c_myApplyContractAction() {
		$this->display('myapply');
	}

	/**
	 * �Ҹ���ĺ�ͬ
	 */
	function c_myPrincipalContractAction() {
		$this->display('myprincipal');
	}

	/**
	 * ��ִ�еĺ�ͬ
	 */
	function c_myExecuteContractAction() {
		$this->display('myexecute');
	}
	/**
	 * �ҵ�����-���ۺ�ͬTAB
	 */
	function c_salespactTab () {
		$this->display( 'salespact-tab');
	}

	/**
	 * ��ͬ�汾���
	 */
	function c_versionShow() {
		$this->assign('contNumber', $_GET['contNumber']);
		$this->display('history');
	}

	/*
	 * ��ͬ�汾PageJson
	 */
	 function c_historyListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $rows = $service->pageBySqlId('version_list');

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * ��д�Ҹ���ĺ�ͬ PageJson
	 */
	 function c_myprincipalPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->searchArr['principalId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');

        $rows = $service->page_d ();
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }
	 /*
	  * ��д�ҵĺ�ͬ���� PageJson
	  */
	 function c_myApplyPageJson() {
	  	$service = $this->service;
	  	$service->getParam ( $_POST ); // ����ǰ̨��ȡ�Ĳ�����Ϣ

	  	$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');

        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/*
	 * ��д��ִ�еĺ�ͬ PageJson
	 */
      function c_myExecutePageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); // ����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;

		$service->searchArr['executorId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
      }

      /*
       * ��д��ͬ��ʷPageJson
       */
	 function c_historyPageJson() {
       	$service = $this->service;
	  	$service->getParam ( $_POST ); // ����ǰ̨��ȡ�Ĳ�����Ϣ
	  	$service->asc = false;

	  	$service->searchArr['contNumber'] = $_POST['contNumber'];
		$rows = $service->pageBySqlId('contract_list');

        foreach( $rows as $key => $val ){
        	$rows[$key]['contStatusC'] = $service->returnContStatus( $val['contStatus']);
        }
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
     }
	 /**
	  * grid ����ҳ��
	  */
	 function c_toAuditTab(){
	 	$this->display('auditTab');
	 }

	 /**
	  * grid ����ҳ��
	  */
	 function c_approvalNo(){
	 	$this->display('unaudit');
	 }

	 /**
	  * δ�����ĺ�ͬ
	  */
	 function c_unauditPageJson(){
		$service = $this->service;
	  	$service->getParam ( $_POST ); // ����ǰ̨��ȡ�Ĳ�����Ϣ

	  	$service->searchArr['findInName'] = $_SESSION['USER_ID'];
	  	$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId('contract_examining');
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
	 }

	 /**
	  * �������ĺ�ͬ
	  */
	 function c_approvalYes(){
		$this->display('audited');
	 }

	 /**
	  * �������ĺ�ͬ
	  */
	 function c_auditedPageJson(){
		$service = $this->service;
	  	$service->getParam ( $_POST ); // ����ǰ̨��ȡ�Ĳ�����Ϣ

	  	$service->searchArr['findInName'] = $_SESSION['USER_ID'];
	  	$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId('contract_examined');
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
	 }

	 /*********************��ͬ����*******************/
	 //TODO;

	/**
	  *  ��ת����ͬ����ҳ��
	  */
	 function c_executeContract(){
	 	$service = $this->service;
	 	$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInEC($rows);
		$this->assignFunc($rows);
	 	$this->display('executecontract');
	 }

	 /**
	  *  ��ת�������ҳ��
	  */
	 function c_toLockStockByContract(){
		$service = $this->service;
		$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInEC($rows);
		$this->assignFunc($rows);

		//�õ�Ĭ�ϲֿ����Ϣ(���òֿ�ӿ�)
		$stockInfoDao=new model_stock_stockinfo_stockinfo();
		$stockInfo=$stockInfoDao->getDefaultStockInfo();
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['id']);
		$this->display('lockstock');
	 }

	 /**
	  *  ��ͬ�����豸
	  */
	 function c_saleToLock(){
		$service = $this->service;
		$rows = $service->find(array('id'=>$_GET['id']),null,'id,contNumber,contName,formalNo');
		$rows = $service->showDetailInLock($rows);
		$this->assignFunc($rows);
		$this->display('lock');
	 }

	 /*********************ǩԼ����*********************/
	 //TODO;
	 /**
	  * ��ͬǩԼ�����б�
	  */
	 function c_signList(){
		$this->display('signlist');
	 }

	 /**
	  * ǩ�պ�ͬ����
	  */
	 function c_sign(){
		if ($this->service->sign_d($_POST['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	 }

	 /**
	  * �޸ĺ�ͬǩԼ״̬
	  */
	 function c_toSign(){
		$rows = $this->service->getContractInfo_d($_GET['id'],'none');
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);
		$rows['signStatus'] = $this->service->showSignStatus($rows['signStatus']);
		$this->assignFunc($rows);
		//�ı�ǩԼ״̬
		$this->assign('contractId', $_GET['id']);
		$this->display('sign');
	 }

	 /**
	  * ����ǩԼ״̬
	  */
	 function c_editBySelf() {
		$object = $this->service->editBySelf($_POST[$this->objName]);
		if ($object) {
			msg('�޸ĳɹ���');
		} else {
			msg('�޸�ʧ�ܣ�');
		}
	}

		/**
		 * @ ajax�ж���
		 *
		 */
	    function c_ajaxContNumber() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['contNumber'] ) ? $_GET ['contNumber'] : false;

			$searchArr = array ("ajaxContNumber" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
}
?>