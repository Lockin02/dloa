<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:32:24
 * @version 1.0
 * @description:外包供应商库控制层
 */
class controller_outsourcing_supplier_basicinfo extends controller_base_action {

	function __construct() {
		$this->objName = "basicinfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到外包供应商库列表
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * 跳转到外包供应商库列表(黑名单)
	 */
    function c_toBlackList() {
      $this->view('black-list');
    }

   /**
	 * 跳转到新增外包供应商库页面
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('suppTypeCode' => 'WBGYSLX' ));//供应商类型
		$this->showDatadicts ( array ('taxPoint' => 'WBZZSD' ) ); //税率
     	$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑外包供应商库页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('suppTypeCode' => 'WBGYSLX' ),$obj['suppTypeCode']);//供应商类型
		$this->showDatadicts ( array ('taxPoint' => 'WBZZSD' ),$obj['taxPoint'] ); //税率
      $this->view ( 'edit');
   }

     /**
	 * 跳转到外包供应商等级变更页面
	 */
	function c_toChangeSuppGrad() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='金';break;
			case '2':$suppGrade='银';break;
			case '3':$suppGrade='铜';break;
			case '4':$suppGrade='黑名单';break;
			case '0':$suppGrade='未认证';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGradeCn", $suppGrade );
      $this->view ( 'change-grade');
   }

   /**
	 * 跳转到查看外包供应商库页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='金';break;
			case '2':$suppGrade='银';break;
			case '3':$suppGrade='铜';break;
			case '4':$suppGrade='黑名单';break;
			case '0':$suppGrade='未认证';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGrade", $suppGrade );

      $this->view ( 'view' );
   }

      /**
	 * 跳转到查看外包供应商库页面
	 */
	function c_toChangeView() {
      $this->permCheck (); //安全校验
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	  $this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='金';break;
			case '2':$suppGrade='银';break;
			case '3':$suppGrade='铜';break;
			case '4':$suppGrade='黑名单';break;
			case '0':$suppGrade='未认证';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGrade", $suppGrade );
		switch($obj['gradeChange']){
			case '1':$gradeChange='金';break;
			case '2':$gradeChange='银';break;
			case '3':$gradeChange='铜';break;
			case '4':$gradeChange='黑名单';break;
			case '0':$suppGrade='未认证';break;
			default: $gradeChange='';break;
		}
		$this->assign ( "gradeChange", $gradeChange );

      $this->view ( 'change-view' );
   }
  /**
	 * 跳转到查看外包供应商库页面TAB
	 */
	function c_toTabView() {
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'view-tab' );
   }

     /**
	 * 跳转到查看外包供应商库页面TAB
	 */
	function c_toTabChangeView() {
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'change-tab' );
   }
  /**
	 * 跳转到编辑外包供应商库页面TAB
	 */
	function c_toTabEdit() {
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'edit-tab' );
   }

      /**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

      /**
	 * 导入更新excel
	 */
	function c_toExcelUpdate(){
		$this->display('excel-update');
	}

   	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
		if ($id) {
			msg ( $msg );
		}else{
			msg('保存失败');

		}
	}

	 /**
	 *信息修改
	 *
	 */
	 function c_edit(){
		$flag=$this->service->edit_d( $_POST [$this->objName]);
		if($flag){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');

		}
	 }

	 	 /**
	 *信息修改
	 *
	 */
	 function c_changeGrade(){
		$flag=$this->service->changeGrade_d( $_POST [$this->objName]);
		if($flag){
			succ_show('controller/outsourcing/supplier/ewf_change_index.php?actTo=ewfSelect&billId=' . $_POST [$this->objName]['id']);
			$sql="update oa_outsourcesupp_supplib SET ExaStatus='完成' where id=". $_POST [$this->objName]['id'];
	  		$this->service->query($sql);
		}else{
			msg('提交变更失败');

		}
	 }

	     /**
     * 变更审批后发送邮件
     */
     function c_dealChange(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines']=="ok"){  //审批通过
				$obj = $this->service->dealChange_d ( $folowInfo['objId'] );
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

		function c_ajaxChange() {
			$flag=$this->service->ajaxChange_d ( $_POST ['id'] );
			if($flag){
				echo 1;
			}else{
				echo 0;
			}
	}

		 	/**
	 * 导入excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '外包供应商导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

			 	/**
	 * 导入excel
	 */
	function c_excelUpdate(){
		set_time_limit(0);
		$resultArr = $this->service->updateExecelData_d ();

		$title = '外包供应商导入更新结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导出汇总数据excel
	 */
	 function c_excelOutAll() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_Outall');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		//var_dump($rows);exit();
		$colArr  = array(
//			"suppCode"=>"供应商编号",
//			"suppName"=>"供应商名称",
//			"suppGrade"=>"供应商等级",
//			"mainBusiness"=>"主要经营范围",
//			"registeredFunds"=>"注册资金",
//			"legalRepre"=>"法人代表",
//			"address"=>"供应商地址",
//			"bankName"=>"开户银行",
//			"accountNum"=>"开户账号",
//			"name"=>"联系人",
//			"jobName"=>"职位",
//			"mobile"=>"电话"
		);
		$modelName = '供应商信息';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr, $rows, $modelName);
	 }

	//假删除供应商
	function c_deleteSupp() {
		$flag=$this->service->deleteSupp_d ( $_POST ['id'],$_POST ['isDel'] );
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}

		/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}
	
	/**
	 * 获取省份编码，联系人,电话,银行账户和银行
	 */
	function c_getInfo(){
		$result = $this->service->getInfo_d($_POST);
// 		print_r($result);die();
		echo util_jsonUtil::encode($result);
	} 
 }