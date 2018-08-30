<?php

/**
 * @author Administrator
 * @Date 2012-10-19 10:32:11
 * @version 1.0
 * @description:��ǰ֧��������Ʋ�
 */
class controller_projectmanagent_support_support extends controller_base_action {

	function __construct() {
		$this->objName = "support";
		$this->objPath = "projectmanagent_support";
		parent :: __construct();
	}

	/*
	 * ��ת����ǰ֧�������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
		 * ��ת��������ǰ֧������ҳ��
		 */
	function c_toAdd() {

		$chanceId = isset ($_GET['id']) ? $_GET['id'] : null;
		if ($chanceId) {
			$chanceDao = new model_projectmanagent_chance_chance();
			$rows = $chanceDao->get_d($chanceId);
			//�������� Դ������
			$chanceType = "chance";
		}
        $this->assign('prinvipalName', isset ($rows['prinvipalName']) ? $rows['prinvipalName'] : null);
        $this->assign('prinvipalId', isset ($rows['prinvipalId']) ? $rows['prinvipalId'] : null);
        $this->assign('projectCode', isset ($rows['chanceCode']) ? $rows['chanceCode'] : null);
        $this->assign('projectName', isset ($rows['chanceName']) ? $rows['chanceName'] : null);
		$this->assign('customerName', isset ($rows['customerName']) ? $rows['customerName'] : null);
		$this->assign('customerId', isset ($rows['customerId']) ? $rows['customerId'] : null);
		$this->assign('customerType', isset ($rows['customerType']) ? $rows['customerType'] : null);
		$this->assign('SingleType', isset ($chanceType) ? $chanceType : null);
		$this->assign('SingleId', isset ($chanceId) ? $chanceId : null);

		$this->showDatadicts(array ('customerTypeView' => 'KHLX'), $rows['customerType']);

		$this->view('add');
	}

	/**
		 * ��ת���༭��ǰ֧������ҳ��
		 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$obj['customerInfo'] = str_replace("<br />"," ",$obj['customerInfo']);
		$obj['customerRemark'] = str_replace("<br />"," ",$obj['customerRemark']);
		$obj['goals'] = str_replace("<br />"," ",$obj['goals']);
		$obj['exContent'] = str_replace("<br />"," ",$obj['exContent']);
		$obj['exPlan'] = str_replace("<br />"," ",$obj['exPlan']);
		$obj['prepared'] = str_replace("<br />"," ",$obj['prepared']);

		$obj['file'] = $this->service->getFilesByObjId($_GET['id'], true);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->showDatadicts(array ('customerTypeView' => 'KHLX'), $obj['customerType']);
		$this->view('edit');
	}

	/**
		 * ��ת���鿴��ǰ֧������ҳ��
		 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$obj['file'] = $this->service->getFilesByObjId($_GET['id'], false);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
		$this->view('view');
	}
   /**
    * �������޸�ҳ
    */
   function c_appEdit(){
        $this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
		$this->view('appedit');
   }

	/*
	 * ��������÷���
	 */
	function c_confirmSupport() {
		$applyinfo = isset($_GET['rows']) ? $_GET['rows'] : null;
        if (!empty ($_GET['spid'])) {
			$this->service->workflowCallBack($_GET['spid'],$applyinfo);
		}
	    echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			succ_show('controller/projectmanagent/support/ewf_support_index.php?actTo=ewfSelect&billId='.$id);
//			msg ( $msg );
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}


    /**
     * ��ǰ֧�����----�̻�tab
     */
    function c_supportListBychance(){
    	 $this->assign("chanceId", $_GET['chanceId']);
    	 $this->view("list-chance");
    }

    /**
     *�ҵ�����֧��
     */
    function c_mySupportTab(){
        $this->view("mytab");
    }
    /*
     * �ҵ�����֧��----�Ҹ����
     */
    function c_myResponsible(){
    	$this->assign("userId",$_SESSION['USER_ID']);
        $this->view("myres");
    }
    /*
     * �ҵ�����֧��----�������
     */
    function c_myApply(){
    	$this->assign("userId",$_SESSION['USER_ID']);
        $this->view("myapply");
    }
}
?>