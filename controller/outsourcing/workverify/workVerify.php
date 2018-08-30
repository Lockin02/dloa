<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:05:37
 * @version 1.0
 * @description:工作量确认单控制层
 */
class controller_outsourcing_workverify_workVerify extends controller_base_action {

	function __construct() {
		$this->objName = "workVerify";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * 跳转到工作量确认单列表
	 */
    function c_page() {
		$this->assign('createId', $_SESSION['USER_ID']);
      $this->view('list');
    }

	/**
	 * 跳转到工作量确认单列表(汇总)
	 */
    function c_toAllList() {
      $this->view('all-list');
    }

	/**
	 * 跳转到工作量确认单列表(交付)
	 */
    function c_toDeliverList() {
      $this->view('deliver-list');
    }
   /**
	 * 跳转到新增工作量确认单页面
	 */
	function c_toAdd() {
		$this->assign('formDate',date("Y-m-d"));
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
	     $this->view ( 'add' );
   }
    /**
	 * 跳转到新增工作量确认单页面
	 */
	function c_toWorkverifyAdd() {
		$object = $_POST [$this->objName];
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLog_d($object['beginDate'],$object['endDate']);
     	$this->view ( 'add-verify' );
   }

   /**
	 * 跳转到编辑工作量确认单页面
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
	 * 跳转到工作量确认单页面(确认金额)
	 */
	function c_toDeliverEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'deliver-edit');
   }

      /**
	 * 跳转到工作量确认单修改页面(提交审批后)
	 */
	function c_toAuditEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'audit-edit');
   }

   /**
	 * 跳转到查看工作量确认单页面
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
	 * 跳转到查看工作量确认单页面(交付)
	 */
	function c_toDeliverView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'deliver-view' );
   }

      /**
	 * 跳转到工作量确认tab
	 */
	function c_toTabWorkVerify() {
		$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
		//获取项目经理未审核条数
		$managerAuditNum=$verifyDetailDao->countManagerAuditNo();
		$this->assign('managerAuditNum',$managerAuditNum);
		//获取服务经理未审核条数
		$serverAuditNum=$verifyDetailDao->countServerAuditNo();
		$this->assign('serverAuditNum',$serverAuditNum);
		//获取服务总监未审核条数
		$areaAuditNum=$verifyDetailDao->countAreaAuditNo();
		$this->assign('areaAuditNum',$areaAuditNum);
      $this->view ( 'verify-tab' );
   }

   	 /**
	 *新增
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
				msg ( '提交成功！' );
			}else{
				msg ( '保存成功！' );
			}
		}else{
			if($addType!=''){
				msg ( '提交失败！' );
			}else{
				msg ( '保存失败！' );
			}
		}
	 }

 	/**
	 *编辑
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
				msg ( '提交成功！' );
			}else{
				msg ( '保存成功！' );
			}
		}else{
			if($addType!=''){
				msg ( '提交失败！' );
			}else{
				msg ( '保存失败！' );
			}
		}
	 }

	  	/**
	 *编辑
	 *
	 */
	 function c_deliverEdit(){
		$id=$this->service->deliverEdit_d($_POST[$this->objName]);
		if($id){
				msg ( '确认成功！' );
		}else{
				msg ( '确认失败！' );
		}
	 }

   	/**
	 *提交审批后编辑
	 *
	 */
	 function c_auditEdit(){
		$id=$this->service->auditEdit_d($_POST[$this->objName]);
		if($id){
				msg ( '保存成功！' );
		}else{
				msg ( '保存失败！' );
		}
	 }

   	/**
	 * 获取周期内的工作量json
	 */
	function c_worklogListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLog_d($_POST['beginDate'],$_POST['endDate']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

		/**
	 * 改变状态(ajax)
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
	 * 跳转到选择列表页面
	 */
	function c_toSelect() {
	 $this->assign("approvalId",$_GET['approvalId']);
     $this->view ( 'select' );
   }

	 	/**
	 * 导出工作量确认单
	 *
	 */
	 function c_exportWorkVerify(){
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:"";
		//获取主表数据
		$rows=$this->service->get_d($id);
		$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
		$detailRows=$verifyDetailDao->getListByParentId($id);  //根据订单ID获取确认详细信息
//		echo "<pre>";
//		print_r($detailRows);

		$dao = new model_outsourcing_workverify_workVerifyUtil ();
		return $dao->exportWorkVerify ( $rows,$detailRows ); //导出Excel
	 }
 }
?>