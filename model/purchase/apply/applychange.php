<?php

class model_purchase_apply_applychange extends model_base{

	//״̬λ
	private $state;

	function __construct() {
		$this->tbl_name = "oa_purch_apply_change";
		$this->sql_map = "purchase/apply/applychangeSql.php";
		parent :: __construct();
		$this->state = array(
			0 => array(
				"stateEName" => "save",
				"stateCName" => "����",
				"stateVal" => "0"
			),
			1 => array(
				"stateEName" => "approval",
				"stateCName" => "������",
				"stateVal" => "1"
			),
			2 => array(
				"stateEName" => "fightback",
				"stateCName" => "���",
				"stateVal" => "2"
			),
			3 => array(
				"stateEName" => "end",
				//"stateCName" => "���",
				"stateCName" => "������ִ��",
				"stateVal" => "3"
			),
			4 => array(
				"stateEName" => "close",
				"stateCName" => "�ر�",
				"stateVal" => "4"
			)
		);
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �鿴���������
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����07:07:05
	 */
	function read_d ($id) {
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		return $rows;
	}


/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude δ�����б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:26:38
	 */
	function pageApprovalWait_d(){
		$searchArr = $this->__GET('searchArr');
		$searchArr["wfCode"] = $this->tbl_name;
		$searchArr["wfFlag"] = '0';
		$searchArr["wfTake"] = "1";
		$searchArr["wfUser"] = $_SESSION['USER_ID'];
		$searchArr["wfStatus"] = "ok";
		$searchArr["wfExamines"] = "1";
		$searchArr["wfEnter_user"] = "1";
		$searchArr["wfName"] = "�ɹ����뵥���";
		$this->__SET('searchArr', $searchArr);
		$this->__SET('groupBy', "w.task");
		$rows = $this->pageBySqlId("list_Approval");
		$i = 0;
		if($rows){
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * @exclude �������б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:26:26
	 */
	function pageApprovalAlready_d(){
		$searchArr = $this->__GET('searchArr');
		$searchArr["wfCode"] = $this->tbl_name;
		$searchArr["wfFlag"] = '1';
		$searchArr["wfUser"] = $_SESSION['USER_ID'];
		$searchArr["wfName"] = "�ɹ����뵥���";
		$searchArr["wfTake"] = "1";
		$searchArr["wfEnter_user"] = "1";
		$this->__SET('searchArr', $searchArr);
		$this->__SET('groupBy', "w.task");
		$rows = $this->pageBySqlId("list_Approval");
		$i = 0;
		if($rows){
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude ����ִ��
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:26:03
	 */
	function execute_d ( $id ) {
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		try {
			$this->start_d ();
			$applyDao = new model_purchase_apply_applybasic();
			$applyDao->closeApplyById_d( $rows['0']['idOld'] );
			$applyDao->executeApplyById_d( $rows['0']['idNew'] );
			$object = array(
				"id" => $id,
				"state" => $this->stateToSta('close')
			);
			$this->updateById($object);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @exclude �ر�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:52:35
	 */
	function close_d ($id) {
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		try {
			$this->start_d ();
			$applyDao = new model_purchase_apply_applybasic();
			$applyDao->closeApplyById_d( $rows['0']['idNew'] );
			$applyDao->executeApplyById_d( $rows['0']['idOld'] );
			$object = array(
				"id" => $id,
				"state" => $this->stateToSta('close')
			);
			$this->updateById($object);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

}
?>
