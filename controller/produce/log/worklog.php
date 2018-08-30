<?php
/**
 * @author Administrator
 * @Date 2012��5��16�� ������ 14:12:39
 * @version 1.0
 * @description:������־���Ʋ� 
 */
class controller_produce_log_worklog extends controller_base_action {
	
	function __construct() {
		$this->objName = "worklog";
		$this->objPath = "produce_log";
		parent::__construct ();
	}
	
	/**
	 * ��ת�����й�����־�б�
	 */
	function c_pageall() {
		//var_dump($_SESSION);
		$this->assign ( "weekId", $_GET ['id'] );
		$this->view ( 'listall' );
	}
	
	/**
	 * ��ת��������־�б�
	 */
	function c_page() {
		//var_dump($_SESSION);
		$this->assign ( "weekId", $_GET ['id'] );
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת������ִ���ձ��б�
	 */
	function c_toTaskList() {
		//var_dump($_SESSION);
		$this->assign ( "taskId", $_GET ['taskId'] );
		$this->view ( 'task-list' );
	}
	
	/**
	 * ��ת������������־ҳ��
	 */
	function c_toAdd() {
		$produceTaskCode = isset ( $_GET ['produceTaskCode'] ) ? $_GET ['produceTaskCode'] : "";
		$produceTaskId = isset ( $_GET ['produceTaskId'] ) ? $_GET ['produceTaskId'] : "";
		$this->assign ( 'produceTaskCode', $produceTaskCode );
		$this->assign ( 'produceTaskId', $produceTaskId );
		$service = $this->service;
		$service->searchArr = array ("createId" => $_SESSION ['USER_ID'], "produceTaskId" => $produceTaskId, "executionDate" => date ( 'Y-m-d' ) );
		$rows = $service->listBySqlId ();
		if (is_array ( $rows )) {
			echo "<script>alert('������������־����д!')</script>";
			$this->assign ( 'executionDate', "" );
		} else {
			$this->assign ( 'executionDate', date ( 'Y-m-d' ) );
		}
		
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭������־ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * �ж��Ƿ���ڸ���־
	 */
	
	function c_isExit() {
		if ($getinfo = $this->service->isExit ( $_SESSION ['USER_ID'], $_POST ['code'] ))
			echo util_jsonUtil::encode ( $getinfo );
		else {
			return FALSE;
		}
	}
	
	/**
	 * ��ת���鿴������־ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	
	/**
	 * ���ӹ�����־����
	 */
	function c_add($isAddInfo = false) {
		//��֤ʱ��Ϸ���	
		if (strtotime ( $_POST [$this->objName] ['executionDate'] ) < strtotime ( date ( 'y-m-d' ) )) {
			msg ( "����ʧ�ܣ�ִ��ʱ�䲻�����ڽ���" );
			return;
		}
		if (strtotime ( $_POST [$this->objName] ['planEndDate'] ) <= strtotime ( date ( 'y-m-d' ) )) {
			msg ( "����ʧ�ܣ�Ԥ�ڽ���ʱ�䲻�����ں͵��ڽ���" );
			return;
		}
		
		$newweek = new model_produce_log_weeklog ();
		
		//echo($newweek);
		if ($weekid = $newweek->isExit ( $_SESSION ['USER_ID'] ))
			$_POST [$this->objName] ['weekId'] = $weekid;
		else
			$_POST [$this->objName] ['weekId'] = $newweek->createweek ();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			echo "<script>alert('����ɹ���');parent.tb_remove();parent.location.reload();</script>";
		}
	
		//$this->listDataDict();
	}
	
	/**
	 * 
	 *����ж��Ƿ����ĳ����������Ĺ�����־
	 */
	function c_checkExsitLog() {
		$this->service->searchArr = array ("createId" => $_SESSION ['USER_ID'], "executionDate" => $_POST ['executionDate'], "produceTaskId" => $_POST ['produceTaskId'] );
		$workLogArr = $this->service->listBySqlId ();
		if (is_array ( $workLogArr )) {
			echo $workLogArr [0] ['id'];
		} else {
			echo 0;
		}
	}
}
?>