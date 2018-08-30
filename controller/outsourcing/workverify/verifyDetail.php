<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:07:02
 * @version 1.0
 * @description:工作量确认单明细控制层
 */
class controller_outsourcing_workverify_verifyDetail extends controller_base_action {

	function __construct() {
		$this->objName = "verifyDetail";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * 跳转到工作量确认单明细列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增工作量确认单明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑工作量确认单明细页面
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
	 * 跳转到查看工作量确认单明细页面
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
	 * 跳转到工作量确认(项目经理)
	 */
	function c_toManagerAuditList() {
		$projectIds=$this->service->getProjectIds_d();
		if($projectIds==''){
			$projectIds='无';
		}
		$this->assign('projectIds',$projectIds);
      $this->view ( 'verify-manager' );
   }

   	//项目经理审核
	function c_managerAudit(){
        $id = $_POST['id'];
        $managerAuditState = $_POST['managerAuditState'];
        $managerAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['managerAuditRemark']);
        $rs = $this->service->managerAudit_d($id,$managerAuditState,$managerAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	  	//项目经理审核(重审)
	function c_managerAuditBack(){
        $object['id'] = $_POST['id'];
         $object['beginDatePM'] = $_POST['beginDatePM'];
         $object['endDatePM'] = $_POST['endDatePM'];
         $object['feeDayPM'] = $_POST['feeDayPM'];
         $object['managerAuditRemark'] = util_jsonUtil::iconvUTF2GB($_POST['managerAuditRemark']);
        $rs = $this->service->managerAuditBack_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}


        /**
	 * 跳转到工作量确认(服务经理)
	 */
	function c_toServerAuditList() {
		//获取所负责的省份
		$provinceDao=new model_engineering_officeinfo_range();
		$provinceStr=$provinceDao->getProvinces_d($_SESSION['USER_ID']);
		if($provinceStr==''){
			$provinceStr='无';
		}
		$this->assign('provinceStr',$provinceStr);
     	 $this->view ( 'verify-server' );
   }

      	//服务经理审核
	function c_serverAudit(){
        $id = $_POST['id'];
        $serverAuditState = $_POST['serverAuditState'];
        $serverAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['serverAuditRemark']);
        $rs = $this->service->serverAudit_d($id,$serverAuditState,$serverAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	      	//服务经理打回
	function c_serverAuditBack(){
        $id = $_POST['id'];
        $managerAuditState = $_POST['managerAuditState'];
        $serverAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['serverAuditRemark']);
        $rs = $this->service->serverAuditBack_d($id,$managerAuditState,$serverAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	        /**
	 * 跳转到工作量确认(服务总监)
	 */
	function c_toAreaAuditList() {
		//获取所负责的省份
		$officeinfoDao=new model_engineering_officeinfo_officeinfo();
		$officeIds=$officeinfoDao->getOfficeIds_d($_SESSION['USER_ID']);
		if($officeIds==''){
			$officeIds='无';
		}
		$this->assign('officeIds',$officeIds);
      $this->view ( 'verify-area' );
   }

         	//服务总监审核
	function c_areaAudit(){
        $id = $_POST['id'];
        $areaAuditState = $_POST['areaAuditState'];
        $areaAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['areaAuditRemark']);
        $rs = $this->service->areaAudit_d($id,$areaAuditState,$areaAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

 	//服务总监打回
	function c_areaAuditBack(){
        $id = $_POST['id'];
        $serverAuditState = $_POST['serverAuditState'];
        $areaAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['areaAuditRemark']);
        $rs = $this->service->areaAuditBack_d($id,$serverAuditState,$areaAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	/**
	 * 项目统计审核信息（项目经理）
	 */
	function c_auditJsonForManager(){
		$service = $this->service;
		$service->getParam ( $_POST );

		$service->groupBy = 'c.projectId,c.parentId';
		$rows = $service->list_d ('select_default');
		echo util_jsonUtil::encode ( $rows );
	}

		//项目经理审核 -- 按项目批量
	function c_auditAllForManager(){
       	$object = $_POST;
        $rs = $this->service->auditAllForManager_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	//服务经理审核 -- 按项目批量
	function c_auditAllForSever(){
       	$object = $_POST;
        $rs = $this->service->auditAllForSever_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

		//服务总监审核 -- 按项目批量
	function c_auditAllForArea(){
       	$object = $_POST;
        $rs = $this->service->auditAllForArea_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	/**
	 * 获取外包供应商工作量与外包结算对接数据返回json
	 */
	function c_suppVerifyListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ( 'select_suppVerify' );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>