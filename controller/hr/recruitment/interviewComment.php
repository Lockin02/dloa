<?php
/**
 * @author Administrator
 * @Date 2012年7月19日 星期四 16:20:22
 * @version 1.0
 * @description:面试评语控制层
 */
class controller_hr_recruitment_interviewComment extends controller_base_action {

	function __construct() {
		$this->objName = "interviewComment";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到面试评语列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增面试评语页面
	 */
	function c_toAdd() {
	 $interview = new model_hr_recruitment_invitation();
	 $obj = $interview->get_d ( $_GET ['setid'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//获取面试官类型
		$interviewerType=$this->service->get_table_fields('oa_hr_invitation_interviewer', "parentId='".$_GET ['setid']."' and interviewerId='".$_SESSION['USER_ID']."'", 'interviewerType');
		$this->assign("interviewerType",$interviewerType);
	 $this->assign("sexy",$obj['sex']);
	 $this->assign("username",$_SESSION['USERNAME']);
	 $this->assign("userid",$_SESSION['USER_ID']);
	 $this->view ( 'add' );
	}

	/*
	 * 跳转到新增面试评价页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		//$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$re=$this->service->addOrUpdate( $_POST [$this->objName]);
		if($re=='update')
		$myMsg='更新成功！';
		else
		$myMsg='添加成功！';
			
		$msg = $_POST ["msg"] ? $_POST ["msg"] :$myMsg;
		if ($re) {
			msg ( $msg );
		}else{
			msg ( '操作失败!' );
		}

		//$this->listDataDict();
	}
	/**
	 * 跳转到编辑面试评语页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/*
	 * 跳转到编辑面试评价页面
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
	 * 跳转到查看面试评语页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	 
	/**
	 * 获取用人部门意见的数据返回json
	 */
	function c_interviewManagerJson() {
		$service = $this->service;
		$sql = "select c.id ,c.invitationId ,c.invitationCode ,c.interviewType ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.applicantName ,c.userAccount ," .
			   "c.userName ,c.sexy ,c.positionsName ,c.positionsId ,c.deptName ,c.deptId ,c.projectGroup ,c.useWriteEva ,c.interviewEva ,c.interviewer ,c.interviewerId ," .
			   "c.interviewDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.interviewId,c.interviewerType  from oa_hr_interview_comment c where 1=1 " .
			   "and c.interviewerType='".$_REQUEST['interviewerType']."' and (c.interviewId='".$_REQUEST['interviewId']."' or c.invitationId='".$_REQUEST['invitationId']."')";
		$rows=$service->_db->getArray ( $sql );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>