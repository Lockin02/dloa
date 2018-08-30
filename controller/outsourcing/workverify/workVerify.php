<?php
/**
 * @author Administrator
 * @Date 2013��9��24�� ���ڶ� 16:05:37
 * @version 1.0
 * @description:������ȷ�ϵ����Ʋ�
 */
class controller_outsourcing_workverify_workVerify extends controller_base_action {

	function __construct() {
		$this->objName = "workVerify";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * ��ת��������ȷ�ϵ��б�
	 */
    function c_page() {
		$this->assign('createId', $_SESSION['USER_ID']);
      $this->view('list');
    }

	/**
	 * ��ת��������ȷ�ϵ��б�(����)
	 */
    function c_toAllList() {
      $this->view('all-list');
    }

	/**
	 * ��ת��������ȷ�ϵ��б�(����)
	 */
    function c_toDeliverList() {
      $this->view('deliver-list');
    }
   /**
	 * ��ת������������ȷ�ϵ�ҳ��
	 */
	function c_toAdd() {
		$this->assign('formDate',date("Y-m-d"));
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
	     $this->view ( 'add' );
   }
    /**
	 * ��ת������������ȷ�ϵ�ҳ��
	 */
	function c_toWorkverifyAdd() {
		$object = $_POST [$this->objName];
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLog_d($object['beginDate'],$object['endDate']);
     	$this->view ( 'add-verify' );
   }

   /**
	 * ��ת���༭������ȷ�ϵ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

      /**
	 * ��ת��������ȷ�ϵ�ҳ��(ȷ�Ͻ��)
	 */
	function c_toDeliverEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'deliver-edit');
   }

      /**
	 * ��ת��������ȷ�ϵ��޸�ҳ��(�ύ������)
	 */
	function c_toAuditEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'audit-edit');
   }

   /**
	 * ��ת���鿴������ȷ�ϵ�ҳ��
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
	 * ��ת���鿴������ȷ�ϵ�ҳ��(����)
	 */
	function c_toDeliverView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'deliver-view' );
   }

      /**
	 * ��ת��������ȷ��tab
	 */
	function c_toTabWorkVerify() {
		$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
		//��ȡ��Ŀ����δ�������
		$managerAuditNum=$verifyDetailDao->countManagerAuditNo();
		$this->assign('managerAuditNum',$managerAuditNum);
		//��ȡ������δ�������
		$serverAuditNum=$verifyDetailDao->countServerAuditNo();
		$this->assign('serverAuditNum',$serverAuditNum);
		//��ȡ�����ܼ�δ�������
		$areaAuditNum=$verifyDetailDao->countAreaAuditNo();
		$this->assign('areaAuditNum',$areaAuditNum);
      $this->view ( 'verify-tab' );
   }

   	 /**
	 *����
	 *
	 */
	 function c_add(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '�ύ�ɹ���' );
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			if($addType!=''){
				msg ( '�ύʧ�ܣ�' );
			}else{
				msg ( '����ʧ�ܣ�' );
			}
		}
	 }

 	/**
	 *�༭
	 *
	 */
	 function c_edit(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
		$id=$this->service->edit_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '�ύ�ɹ���' );
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			if($addType!=''){
				msg ( '�ύʧ�ܣ�' );
			}else{
				msg ( '����ʧ�ܣ�' );
			}
		}
	 }

	  	/**
	 *�༭
	 *
	 */
	 function c_deliverEdit(){
		$id=$this->service->deliverEdit_d($_POST[$this->objName]);
		if($id){
				msg ( 'ȷ�ϳɹ���' );
		}else{
				msg ( 'ȷ��ʧ�ܣ�' );
		}
	 }

   	/**
	 *�ύ������༭
	 *
	 */
	 function c_auditEdit(){
		$id=$this->service->auditEdit_d($_POST[$this->objName]);
		if($id){
				msg ( '����ɹ���' );
		}else{
				msg ( '����ʧ�ܣ�' );
		}
	 }

   	/**
	 * ��ȡ�����ڵĹ�����json
	 */
	function c_worklogListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLog_d($_POST['beginDate'],$_POST['endDate']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

		/**
	 * �ı�״̬(ajax)
	 *
	 */
	 function c_changeState(){
	 	$flag=$this->service->changeState_d($_POST);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }
    /**
	 * ��ת��ѡ���б�ҳ��
	 */
	function c_toSelect() {
	 $this->assign("approvalId",$_GET['approvalId']);
     $this->view ( 'select' );
   }

	 	/**
	 * ����������ȷ�ϵ�
	 *
	 */
	 function c_exportWorkVerify(){
		$this->permCheck ();//��ȫУ��
		$id=isset($_GET['id'])?$_GET['id']:"";
		//��ȡ��������
		$rows=$this->service->get_d($id);
		$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
		$detailRows=$verifyDetailDao->getListByParentId($id);  //���ݶ���ID��ȡȷ����ϸ��Ϣ
//		echo "<pre>";
//		print_r($detailRows);

		$dao = new model_outsourcing_workverify_workVerifyUtil ();
		return $dao->exportWorkVerify ( $rows,$detailRows ); //����Excel
	 }
 }
?>