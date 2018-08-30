<?php
/**
 * @author Administrator
 * @Date 2012��7��19�� ������ 16:20:22
 * @version 1.0
 * @description:����������Ʋ�
 */
class controller_hr_recruitment_interviewComment extends controller_base_action {

	function __construct() {
		$this->objName = "interviewComment";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת�����������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������������ҳ��
	 */
	function c_toAdd() {
	 $interview = new model_hr_recruitment_invitation();
	 $obj = $interview->get_d ( $_GET ['setid'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//��ȡ���Թ�����
		$interviewerType=$this->service->get_table_fields('oa_hr_invitation_interviewer', "parentId='".$_GET ['setid']."' and interviewerId='".$_SESSION['USER_ID']."'", 'interviewerType');
		$this->assign("interviewerType",$interviewerType);
	 $this->assign("sexy",$obj['sex']);
	 $this->assign("username",$_SESSION['USERNAME']);
	 $this->assign("userid",$_SESSION['USER_ID']);
	 $this->view ( 'add' );
	}

	/*
	 * ��ת��������������ҳ��
	 */
	function c_toAddComment(){
		$interview = new model_hr_recruitment_invitation();
		$obj = $interview->get_d ( $_GET ['setid'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("username",$_SESSION['USERNAME']);
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('addcomment');
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		//$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$re=$this->service->addOrUpdate( $_POST [$this->objName]);
		if($re=='update')
		$myMsg='���³ɹ���';
		else
		$myMsg='��ӳɹ���';
			
		$msg = $_POST ["msg"] ? $_POST ["msg"] :$myMsg;
		if ($re) {
			msg ( $msg );
		}else{
			msg ( '����ʧ��!' );
		}

		//$this->listDataDict();
	}
	/**
	 * ��ת���༭��������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/*
	 * ��ת���༭��������ҳ��
	 */
	function c_toEditComment(){
		$obj=$this->service->find(array("invitationId"=>$_GET['setid']));
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("username",$_SESSION['USERNAME']);
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('editcomment');
	}


	/**
	 * ��ת���鿴��������ҳ��
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
	 * ��ȡ���˲�����������ݷ���json
	 */
	function c_interviewManagerJson() {
		$service = $this->service;
		$sql = "select c.id ,c.invitationId ,c.invitationCode ,c.interviewType ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.applicantName ,c.userAccount ," .
			   "c.userName ,c.sexy ,c.positionsName ,c.positionsId ,c.deptName ,c.deptId ,c.projectGroup ,c.useWriteEva ,c.interviewEva ,c.interviewer ,c.interviewerId ," .
			   "c.interviewDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.interviewId,c.interviewerType  from oa_hr_interview_comment c where 1=1 " .
			   "and c.interviewerType='".$_REQUEST['interviewerType']."' and (c.interviewId='".$_REQUEST['interviewId']."' or c.invitationId='".$_REQUEST['invitationId']."')";
		$rows=$service->_db->getArray ( $sql );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>