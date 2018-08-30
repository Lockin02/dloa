<?php
/**
 * @author Administrator
 * @Date 2013年12月15日 星期日 22:23:01
 * @version 1.0
 * @description:外包结算控制层 合同状态
                        0.未提交
                        1.审批中
                        2.执行中
                        3.已关闭
                        4.变更中
 */
class controller_outsourcing_account_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "outsourcing_account";
		parent::__construct ();
	 }

	/**
	 * 跳转到外包结算列表
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * 跳转到外包立项列表(个人)
	 */
    function c_toMyList() {
    	$this->assign ('userId', $_SESSION['USER_ID'] );
        $this->view('my-list');
    }

	/**
	 * 跳转到外包结算确认列表
	 */
    function c_toAffirmList() {
        $this->view('affirm-list');
    }

   /**
	 * 跳转到新增外包结算页面
	 */
	function c_toAdd() {
		$applyId=isset($_GET ['appId'])?$_GET ['appId']:'';
		$applyDao=new model_outsourcing_approval_basic();//获取外包立项信息
		$applyRow=$applyDao->get_d($applyId);
		foreach ( $applyRow as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$projectDao=new model_engineering_project_esmproject();//获取项目信息
		$projectRow=$projectDao->get_d($applyRow['projectId']);
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$applyRow['payType'] );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$applyRow['taxPointCode'] ); //税率
        $this->assign ( 'datenow', date("Y-m-d") );
        $this->assign ( 'usernow', $_SESSION['USERNAME'] );
        $this->assign ( 'userId', $_SESSION['USER_ID'] );
        $this->view ( 'add' );
   }

   /**
	 * 跳转到从外包供应商工作量新增外包结算页面
	 */
	function c_toSuppVerifyAdd() {
		$suppVerifyId = isset($_GET ['suppVerifyId'])?$_GET ['suppVerifyId']:'';
		$suppVerifyDao = new model_outsourcing_workverify_suppVerify();//获取外包供应商工作量信息
		$suppVerifyRow = $suppVerifyDao->get_d($suppVerifyId);
		foreach ( $suppVerifyRow as $key => $val ) {
			$this->assign ($key ,$val);
		}

		$verifyDetailDao = new model_outsourcing_workverify_verifyDetail();//获取工作量详细信息
		$verifyDetailRow = $verifyDetailDao->findAll(array('suppVerifyId'=>$suppVerifyId) ,null ,'projectId');
		$proIds = '';
		foreach ( $verifyDetailRow as $key => $val ) {
			$proIds .= $val['projectId'].',';
		}
		$this->assign ('proIds' ,substr($proIds ,0 ,-1));

		$this->showDatadicts (array('payType' => 'HTFKFS'));//付款方式
		$this->showDatadicts (array('taxPointCode' => 'WBZZSD')); //税率

		$this->view ( 'supp-add' );
	}

   /**
	 * 跳转到编辑外包结算页面
	 */
	function c_toEdit() {
//   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //税率
		$this->view ( 'edit');
	}

   /**
	 * 跳转到编辑外包结算页面
	 */
	function c_toAffirm() {
//		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//付款方式
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //税率
		$this->view ( 'affirm');
	}

   /**
	 * 跳转到查看外包结算页面
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
    * 添加申请事件，跳转到列表页
    */
	function c_add(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $applyId);
			}else{
				msg ( '保存成功！' );
			}
		}else{
			msg ( '保存失败！' );
		}
	}

	/**
	* 从工作量添加申请事件
	*/
	function c_suppVerifyAdd(){
		$_POST['basic']['state'] = isset ($_GET['isSubmit']) ? 1 : 0;

		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			msg ( '保存成功！' );
		}else{
			msg ( '保存失败！' );
		}
	}

	/**
	 * ajax提交
	 */
	function c_ajaxSubmit() {
		try {
			$this->service->update(array('id'=>$_POST['id']) ,array('state'=>'1'));
			$this->service->sendMailAffirm_d($_POST['id']);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

     /**
    * 编辑，跳转到列表页
    */
	function c_edit(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '保存成功！' );
			}
		}else{
			msg ( '保存失败！' );
		}
	}

    /**
    * 外包结算确认编辑，跳转到列表页
    */
	function c_affirmEdit(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$_POST['basic']['state'] = 2;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '保存成功！' );
			}
		}else{
			msg ( '保存失败！' );
		}
	}

	/**
	* 从工作量编辑
	*/
	function c_suppVerifyEdit(){
		$_POST['basic']['state'] = isset ($_GET['isSubmit']) ? 1 : 0;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			msg ( '保存成功！' );
		}else{
			msg ( '保存失败！' );
		}
	}

	/**
	 * ajax方式删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletes() {
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

   /**
    * 付款申请--获取可以申请的金额
    */
	function c_getCanApply(){
		$id = $_POST['id'];
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();
		$applyMoney = $payablesapplyDao -> getApplyMoneyByPur_d($id,'YFRK-05');
		$orderMoney = $this->service->find(array('id'=>$id),null,'orderMoney');
   		$canApply = bcsub($orderMoney['orderMoney'],$applyMoney,2);
   		echo $canApply;
	}
	/*
	 * 跳转到外包结算汇总筛选导出页面
	 */
	function c_toExportOut(){
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ));
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ));
		$this->showDatadicts ( array ('projectType' => 'GCXMXZ' ));
		$this->view("exportOut");
	}
	/*
	 * 外包结算汇总导出
	 */
	 function c_exportOut(){
 		set_time_limit(0);
	   	$service = $this->service;
	 	$formData = $_POST[$this->objName];
	 	$service->searchArr['ExaStatusArr'] = '部门审批,完成,打回';
	 	if(trim($formData['formCode'])){ //单据编号
			$service->searchArr['formCode'] = trim($formData['formCode']);
	 	}
	 	if(trim($formData['approvalCode'])){ //外包立项编号
			$service->searchArr['approvalCode'] = trim($formData['approvalCode']);
	 	}
	 	if(trim($formData['projectCode'])){ //项目编号
			$service->searchArr['projectCode'] = trim($formData['projectCode']);
	 	}
	 	if(trim($formData['projectName'])){ //项目名称
			$service->searchArr['projectName'] = trim($formData['projectName']);
	 	}
	 	if(trim($formData['outsourcing'])){ //外包方式
			$service->searchArr['outsourcing'] = trim($formData['outsourcing']);
	 	}
	 	if(trim($formData['outContractCode'])){ //外包编号
			$service->searchArr['outContractCode'] = trim($formData['outContractCode']);
	 	}
	 	if(trim($formData['suppName'])){ //外包供应商
			$service->searchArr['suppName'] = trim($formData['suppName']);
	 	}
	 	if(trim($formData['projectType'])){ //项目类型
			$service->searchArr['projectType'] = trim($formData['projectType']);
	 	}
	 	if(trim($formData['saleManangerName'])){ //销售负责人
			$service->searchArr['saleManangerName'] = trim($formData['saleManangerName']);
	 	}
	 	if(trim($formData['projectManangerName'])){ //项目经理
			$service->searchArr['projectManangerName'] = trim($formData['projectManangerName']);
	 	}
	 	if(trim($formData['payType'])){ //付款方式
			$service->searchArr['payType'] = trim($formData['payType']);
	 	}
	 	if(trim($formData['ExaStatus'])){ //审批状态
			$service->searchArr['ExaStatus'] = trim($formData['ExaStatus']);
	 	}
	 	$rows = $service->list_d('select_excel');
	 	if(!$rows){
	 		msg("没有相关记录！");
	 		return 0;
	 	}
	 	for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array(
		);
		$modelName = '外包结算导出模版';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);

	 }
 }
?>