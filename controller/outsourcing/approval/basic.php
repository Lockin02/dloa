<?php
/**
 * @author Administrator
 * @Date 2013年11月19日 星期二 23:55:18
 * @version 1.0
 * @description:外包立项控制层 合同状态
                        0.未提交
                        1.审批中
                        2.执行中
                        3.已关闭
                        4.变更中
 */
class controller_outsourcing_approval_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "outsourcing_approval";
		parent::__construct ();
	 }

	/**
	 * 跳转到外包立项列表
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * 跳转到外包立项列表(个人)
	 */
    function c_toMyList() {
    	$this->service->setCompany(0); # 个人列表,不需要进行公司过滤
    	$this->assign ('userId', $_SESSION['USER_ID'] );
        $this->view('my-list');
    }

   /**
	 * 跳转到新增外包立项页面
	 */
	function c_toAdd() {
		$this->view ('add' ,true);
	}

    /**
	 * 跳转到新增外包立项页面
	 */
	function c_toAddByApply() {
		$applyId=isset($_GET ['applyId'])?$_GET ['applyId']:'';
		$applyDao=new model_outsourcing_outsourcing_apply();//获取外包申请信息
		$applyRow=$applyDao->get_d($applyId);
		foreach ( $applyRow as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$projectDao=new model_engineering_project_esmproject();//获取项目信息
		$projectRow=$projectDao->get_d($applyRow['projectId']);

		if(is_array($projectRow)){
			$this->assign ('nature', $projectRow['nature'] );
			$this->assign ('natureName', $projectRow['natureName'] );
			$this->assign ('managerId', $projectRow['managerId'] );
			$this->assign ('managerName', $projectRow['managerName'] );
			$this->assign ('salesmanId', $projectRow['salesmanId'] );
			$this->assign ('salesman', $projectRow['salesman'] );
			$this->assign ('contractMoney', $projectRow['contractMoney'] );
		}else{
			$this->assign ('nature', '');
			$this->assign ('natureName', '' );
			$this->assign ('managerId', '' );
			$this->assign ('managerName', '' );
			$this->assign ('salesmanId', '' );
			$this->assign ('salesman', '' );
			$this->assign ('contractMoney', '' );
		}
		switch($applyRow['outType']){
			case '1':$this->assign ('outsourcing', 'HTWBFS-01');$this->assign ('outsourcingName', '整包');break;
			case '2':$this->assign ('outsourcing', 'HTWBFS-03');$this->assign ('outsourcingName', '分包');break;
			case '3':$this->assign ('outsourcing', 'HTWBFS-02');$this->assign ('outsourcingName', '人员租赁');break;
			default:$this->assign ('outsourcing', '');$this->assign ('outsourcingName', '');break;
		}
		$esmbudgetDao=new model_engineering_budget_esmbudget();//获取项目预算信息
		$esmbudgetRows=$esmbudgetDao->getAllBudgetDetail_d($applyRow['projectId']);

		$this->showDatadicts ( array ('payType' => 'HTFKFS' ) );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ) ); //税率
        $this->assign ( 'datenow', date("Y-m-d") );
        $this->assign ( 'usernow', $_SESSION['USERNAME'] );
        $this->assign ( 'userId', $_SESSION['USER_ID'] );
        $this->view ('add' ,true);
   }

   /**
	 * 跳转到编辑外包立项页面
	 */
	function c_toEdit() {
//   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //税率
        $this->assign ( 'datenow', date("Y-m-d") );
		$this->view ('edit' ,true);
	}

   /**
	 * 跳转到编辑外包立项页面
	 */
	function c_toChange() {
//   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //税率
        $this->assign ( 'datenow', date("Y-m-d") );
      	$this->view ('change' ,true);
   }

   	/**
	 * 变更查看tab
	 */
	function c_changeTab(){
//		$this->permCheck (); //安全校验
		$newId = $_GET ['id'];
		$this->assign('id',$newId);

		$rs = $this->service->find(array('id' => $newId ),null,'originalId');
		$this->assign('originalId',$rs['originalId']);

		$this->display('changetab');
	}


   /**
	 * 跳转到查看外包立项页面
	 */
	function c_toView() {
//      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//审批显示处理
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType );
		$this->view ( 'view' );
	}
      /**
	 * 跳转到查看外包立项页面
	 */
	function c_toViewTab() {
//      $this->permCheck (); //安全校验
		$this->assign('id',$_GET ['id'] );
		$this->view ( 'view-tab' );
	}

      /**
	 * 跳转到查看外包立项页面
	 */
	function c_toChangeView() {
//      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//审批显示处理
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType );
		$this->view ( 'change-view' );
	}

   /**
    * 添加申请事件，跳转到列表页
    */
	function c_add(){
		$this->checkSubmit(); //验证是否重复提交
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' . $applyId);
			}else{
				msg ( '保存成功！' );
			}
		}else{
			msg ( '保存失败！' );
		}
   }

  /**
    * 编辑，跳转到列表页
    */
	function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '保存成功！' );
			}
		}else{
			msg ( '保存失败！' );
		}
   }

   	/**
	 * 变更操作
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$this->checkSubmit(); //验证是否重复提交
		try {
			$id = $this->service->change_d ( $_POST [$this->objName] );

			succ_show("controller/outsourcing/approval/ewf_change.php?actTo=ewfSelect&billId=" . $id);

		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}

	/**
	 * 变更审批完成后处理
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAuditChange_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/*
	 * 外包立项审批通过处理
	 */
	 function c_dealAfterAuditPass() {
	 	if (! empty ( $_GET ['spid'] )) {
	 		//审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	 }
	 /**
	 * 获取立项数据生成下拉列表
	 */
	function c_pullPage() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->page_d ('select_pull');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>