<?php
/**
 * @author Administrator
 * @Date 2012-08-07 16:06:30
 * @version 1.0
 * @description:离职--面谈记录表控制层
 */
class controller_hr_leave_interview extends controller_base_action {

	function __construct() {
		$this->objName = "interview";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职--面谈记录表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
    * 管理个人负责的面谈记录
    */
   function c_proList(){
   	   $this->assign('userId',$_SESSION['USER_ID']);
   	   $this->view('prolist');
   }

   /**
	 * 跳转到新增离职--面谈记录表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑离职--面谈记录表页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
    	$this->view ( 'edit');
   }
	 /**
	 * 跳转到填写面谈记录表页面
	 */
	function c_toWrite() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//面谈者为当前登录用户
		$obj['interviewer']=$_SESSION['USERNAME'];
		$obj['interviewerId']=$_SESSION['USER_ID'];
		//获取面谈记录从表数据
		$detail=$this->service->getDetailByID($obj['interviewerId'],$obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//带出面谈内容
		$this->assign("interviewContent",$detail['interviewContent']);
		$this->assign("leaveReson",$detail['leaveReson']);
		$this->assign("jobAdvice",$detail['jobAdvice']);
		$this->assign("companyAdvice",$detail['companyAdvice']);
		$this->assign("interviewAdvice",$detail['interviewAdvice']);
		//如果面谈日期为空，带出当前日期，可更改
		if(!isset($detail['interviewDate'])){
			$this->assign("interviewDate",date("Y-m-d"));
		}else{
			$this->assign("interviewDate",$detail['interviewDate']);
		}
    	$this->view ( 'write');
   }
	 /**
	 * 跳转到填写面谈记录表页面
	 */
	function c_toPersonView() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//面谈者为当前登录用户
		$obj['interviewer']=$_SESSION['USERNAME'];
		$obj['interviewerId']=$_SESSION['USER_ID'];
		//获取面谈记录从表数据
		$detail=$this->service->getDetailByID($obj['interviewerId'],$obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//带出面谈内容
		$this->assign("interviewContent",$detail['interviewContent']);
		$this->assign("leaveReson",$detail['leaveReson']);
		$this->assign("jobAdvice",$detail['jobAdvice']);
		$this->assign("companyAdvice",$detail['companyAdvice']);
		$this->assign("interviewAdvice",$detail['interviewAdvice']);
		//如果面谈日期为空，带出当前日期，可更改
		if(!isset($detail['interviewDate'])){
			$this->assign("interviewDate",date("Y-m-d"));
		}else{
			$this->assign("interviewDate",$detail['interviewDate']);
		}
    	$this->view ( 'person-view');
   }
   /**
	 * 跳转到查看离职--面谈记录表页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {

		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
	/**
	 * 填写面谈记录
	 */
	function c_write($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->write_d( $object, $isEditInfo )) {
			msg ( '提交成功！' );
		}
	}
   /**
    * 面谈记录
    */
   function c_InterviewNotice(){
       $leaveId = $_GET['leaveId'];
       //判断是否存在面谈记录
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