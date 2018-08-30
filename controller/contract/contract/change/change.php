<?php
/*
 * Created on 2010-7-8
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_contract_change_change extends controller_base_action{

	function __construct(){
		$this->objName = "change";
		$this->objPath = "contract_change";
		parent :: __construct();
	}

	/**
	 * ��ʼ������
	 */
	function c_showAction(){
	   $rows = $this->service->initChange($_GET['id'],'new');
		$rowsT = $rows['change'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->display( 'read-new');
	}

	/**
	 * ��ʾ�޸ı�����뵥ҳ��
	 */
	function c_editChangeForm(){
		$service = $this->service;
		$rows = $service->initChangeEdit($_GET['id'],'new');
		$rowsT = $rows['change'];
		$rowsT['formId'] = $rows['change']['id'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('edit');
	}

	/**
	 * �޸���������¼�ı�����뵥
	 */
	function c_editChangeAction(){
		$object = $this->service->editChange ( $_POST ['sales'] );
		if ($object && $_GET ['act'] == 'save') {
			showmsg ( '����ɹ���', 'index1.php?model=contract_change_change&action=myChangeAction' );
		} else if ($object) {
			succ_show('controller/contract/change/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=��ͬ���');
		} else {
			showmsg ( '�༭ʧ�ܣ�', 'index1.php?model=contract_change_change&action=myChangeAction' );
		}
	}

	/**
	 * ��ʾ�����غ����±༭ҳ��
	 */
	 function c_backEditChangeForm(){
		$service = $this->service;
		$rows = $service->initChangeEdit($_GET['id'],'new');
		$rowsT = $rows['change'];
		$rowsT['formId'] = $rows['change']['id'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->showDatadicts( array ('invoiceType' => 'FPLX' ) ,$rows['invoiceType']);

		$this->display('backedit');
	 }

	 /**
	  * �޸ı����غ�༭������뵥
	  */
	function c_backEdit(){
		$object = $this->service->backEditChange ( $_POST ['sales'] );
		if ($object && $_GET ['act'] == 'save') {
			showmsg ( '����ɹ���', 'index1.php?model=contract_change_change&action=myChangeAction' );
		} else if ($object) {
			succ_show('controller/contract/change/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=��ͬ���');
		} else {
			showmsg ( '�༭ʧ�ܣ�', 'index1.php?model=contract_change_change&action=myChangeAction' );
		}
	}

	/**
	 * ɾ������-���ݿ�����׶�
	 */
	function c_delT() {
		if ($this->service->deletesT ( $_POST ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ɾ������-��ɾ��-���ݿ�����׶�
	 */
	function c_notdel() {
		if ($this->service->notDel ( $_POST ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ������ͬ���
	 */
	function c_beginChange(){
		if($this->service->beginThisChange($_POST['id'])){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * �ҵı������
	 */
	 /****רҵ�ָ���****/
	 function c_myChangeAction() {
        $this->display('mychange');
	 }
    /**
	 * �ҵı������-��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonMyChange() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $service->searchArr['applyId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('change_list2');

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

     /**
      * �ҵ�����-��ͬ����-���ۺ�ͬ���
      */

     function c_contractChangeTab() {
     	$this->display('auditTab');
     }
	/**
	 * �������ı������
	 */
	function c_waitExemineChangeAction(){
		$this->display( 'unaudit');
	}
    /**
	 * �������ı������Json
	 */
	function c_ConpageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;

	    $rows = $service->waitExaminingChange();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * �������ı������
	 */
	function c_exeminedChangeAction(){
		$this->display( 'audited');
	}

    /**
	 * �������ı������Json
	 */
	function c_ConYsppageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;

	    $rows = $service->examinedChange();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * ����������뵥ʱ����������뵥����ͬ��Ϣ
	 */
	function c_showChangeAndNew(){
		$service = $this->service;
		$this->assign ( 'topPlan', $this->topPlan ( array (
															"clickNumb" => 1,
															 "numb" => 2,
															 "name1" => "��ǰ�������ĺ�ͬ",
															 "title1" => "��ǰ�������ĺ�ͬ",
															 "url1" => "index1.php?model=contract_change_change&action=showChangeAndNew&id=$_GET[id]",
															 "name2" => "���ǰ�ĺ�ͬ",
															 "title2" => "���ǰ�ĺ�ͬ",
															 "url2" => "index1.php?model=contract_change_change&action=showChangeAndOld&id=$_GET[id]" )
															 )
							 );
		$rows = $this->service->initChange($_GET['id'],'new');
		$rowsT = $rows['change'];
		unset($rows['change']);
		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign ( 'invoiceType', $this->getDataNameByCode($rows['invoiceType']) );
		$this->assign ( 'customerType', $this->getDataNameByCode($rows['customerType']) );
		$this->display ('readnewinex' );
	}

	/**
	 * �鿴�����뵥�Լ��ɵĺ�ͬ
	 */
	function c_showChangeAndOld(){
		$service = $this->service;
		$this->assign ( 'topPlan', $this->topPlan ( array (
															"clickNumb" => 2,
															 "numb" => 2,
															 "name1" => "��ǰ�������ĺ�ͬ",
															 "title1" => "��ǰ�������ĺ�ͬ",
															 "url1" => "index1.php?model=contract_change_change&action=showChangeAndNew&id=$_GET[id]",
															 "name2" => "���ǰ�ĺ�ͬ",
															 "title2" => "���ǰ�ĺ�ͬ",
															 "url2" => "index1.php?model=contract_change_change&action=showChangeAndOld&id=$_GET[id]" )
															 )
							 );
		$rows = $this->service->initChange($_GET['id'],'old');
		$rowsT = $rows['change'];
		unset($rows['change']);
		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign ( 'invoiceType', $this->getDataNameByCode($rows['invoiceType']) );
		$this->assign ( 'customerType', $this->getDataNameByCode($rows['customerType']) );
		$this->display ('readnewinex' );
	}
}
?>
