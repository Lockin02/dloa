<?php
/**
 * @author Administrator
 * @Date 2012-08-07 16:06:30
 * @version 1.0
 * @description:��ְ--��̸��¼����Ʋ�
 */
class controller_hr_leave_interview extends controller_base_action {

	function __construct() {
		$this->objName = "interview";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ--��̸��¼���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
    * ������˸������̸��¼
    */
   function c_proList(){
   	   $this->assign('userId',$_SESSION['USER_ID']);
   	   $this->view('prolist');
   }

   /**
	 * ��ת��������ְ--��̸��¼��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ְ--��̸��¼��ҳ��
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
	 * ��ת����д��̸��¼��ҳ��
	 */
	function c_toWrite() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��̸��Ϊ��ǰ��¼�û�
		$obj['interviewer']=$_SESSION['USERNAME'];
		$obj['interviewerId']=$_SESSION['USER_ID'];
		//��ȡ��̸��¼�ӱ�����
		$detail=$this->service->getDetailByID($obj['interviewerId'],$obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������̸����
		$this->assign("interviewContent",$detail['interviewContent']);
		$this->assign("leaveReson",$detail['leaveReson']);
		$this->assign("jobAdvice",$detail['jobAdvice']);
		$this->assign("companyAdvice",$detail['companyAdvice']);
		$this->assign("interviewAdvice",$detail['interviewAdvice']);
		//�����̸����Ϊ�գ�������ǰ���ڣ��ɸ���
		if(!isset($detail['interviewDate'])){
			$this->assign("interviewDate",date("Y-m-d"));
		}else{
			$this->assign("interviewDate",$detail['interviewDate']);
		}
    	$this->view ( 'write');
   }
	 /**
	 * ��ת����д��̸��¼��ҳ��
	 */
	function c_toPersonView() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��̸��Ϊ��ǰ��¼�û�
		$obj['interviewer']=$_SESSION['USERNAME'];
		$obj['interviewerId']=$_SESSION['USER_ID'];
		//��ȡ��̸��¼�ӱ�����
		$detail=$this->service->getDetailByID($obj['interviewerId'],$obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������̸����
		$this->assign("interviewContent",$detail['interviewContent']);
		$this->assign("leaveReson",$detail['leaveReson']);
		$this->assign("jobAdvice",$detail['jobAdvice']);
		$this->assign("companyAdvice",$detail['companyAdvice']);
		$this->assign("interviewAdvice",$detail['interviewAdvice']);
		//�����̸����Ϊ�գ�������ǰ���ڣ��ɸ���
		if(!isset($detail['interviewDate'])){
			$this->assign("interviewDate",date("Y-m-d"));
		}else{
			$this->assign("interviewDate",$detail['interviewDate']);
		}
    	$this->view ( 'person-view');
   }
   /**
	 * ��ת���鿴��ְ--��̸��¼��ҳ��
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
	 * �����������
	 */
	function c_add($isAddInfo = true) {

		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
	/**
	 * ��д��̸��¼
	 */
	function c_write($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->write_d( $object, $isEditInfo )) {
			msg ( '�ύ�ɹ���' );
		}
	}
   /**
    * ��̸��¼
    */
   function c_InterviewNotice(){
       $leaveId = $_GET['leaveId'];
       //�ж��Ƿ������̸��¼
       $sql = "select id  from oa_hr_leave_interview where leaveId=".$leaveId."";
       $flagArr = $this->service->findSql($sql);
       $leaveDao = new model_hr_leave_leave();
       if(empty($flagArr[0]['id'])){
	       	$obj = $leaveDao->get_d ( $leaveId );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		   $this->assign("leaveId",$leaveId);
       	   $this->view ('add');
       }else{
	       	$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
       	   $this->view ('view');
       }
   }
 }
?>