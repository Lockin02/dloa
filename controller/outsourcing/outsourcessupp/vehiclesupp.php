<?php
/**
 * @author Michael
 * @Date 2014年1月7日 星期二 10:22:36
 * @version 1.0
 * @description:车辆供应商-基本信息控制层
 */
class controller_outsourcing_outsourcessupp_vehiclesupp extends controller_base_action {

	function __construct() {
		$this->objName = "vehiclesupp";
		$this->objPath = "outsourcing_outsourcessupp";
		parent::__construct ();
	 }

	/**
	 * 跳转到车辆供应商-基本信息列表
	 */
	function c_page() {
		// $this->service->setCompany(0); # 个人列表,不需要进行公司过滤
		$this->view('list');
	}

	/**
	 * 跳转到车辆供应商-黑名单列表
	 */
	function c_toBlacklist() {
		$this->view('blacklist');
	}

   /**
	 * 跳转到新增车辆供应商-基本信息页面
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' ));  //供应商类型
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //发票属性
		$this->view ('add' ,true);
	}

   	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			msg('保存成功！');
		}else{
			msg('保存失败！');
		}
	}

   /**
	 * 跳转到编辑车辆供应商-基本信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' ) ,$obj['suppCategory']); //供应商类型
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ) ,$obj['invoiceCode']);  //发票属性
		$this->view ( 'edit');
	}

   /**
	 * 跳转到编辑车辆供应商-基本信息页面TAB
	 */
	function c_toEditTab() {
		$this->permCheck (); //安全校验
		$this->assign ( 'id' ,$_GET ['id'] );
		$this->view ( 'edit-tab');
	}

	 /**
	  * 编辑
	  */
	 function c_edit() {
		$flag = $this->service->edit_d( $_POST[$this->objName] );
		if( $flag ){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');
		}
	 }

   /**
	 * 跳转到查看车辆供应商-基本信息页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ('taxPoint' ,$obj['taxPoint'].'%');
		if ($obj['isEquipDriver'] == 1) {
			$obj['isEquipDriver'] = '能';
		} else {
			$obj['isEquipDriver'] = '不能';
		}
		$this->assign ('isEquipDriver' ,$obj['isEquipDriver']);

		if ($obj['isDriveTest'] == 1) {
			$obj['isDriveTest'] = '有';
		} else {
			$obj['isDriveTest'] = '没有';
		}
		$this->assign ('isDriveTest' ,$obj['isDriveTest']);
		$this->view ( 'view' );
	}

   /**
	 * 跳转到查看车辆供应商-基本信息页面TAB
	 */
	function c_toViewTab() {
		$this->permCheck (); //安全校验
		$this->assign ( 'id' ,$_GET ['id'] );
		$this->view ( 'view-tab' );
	}

	/**
	 * 列表高级查询
	 */
	function c_toSearch(){
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' )); //供应商类型
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //发票属性
        $this->view('search');
	}

	/**
	 * 跳转到供应商信息导入页面
	 */
	function c_toImportPage() {
		$this->view ( "import" );
	}

	/**
	 * 新增页面-导入供应商
	 */
	 function c_importVehiclesupp() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_outsourcing_outsourcessupp_importVehiclesuppUtil();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$newResult = util_jsonUtil::encode ( $excelData );
			echo "<script>window.parent.setExcelValue('".$newResult."');self.parent.tb_remove()</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');history.go(-1);</script>";
		}
	 }

	/**
	 * 供应商类型为个人
	 * 根据电话号码检查对象是否重复
	 */
	function c_checkRepeatByPhone() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}
		if(!isset($_POST['validateError'])){
			$service->getParam ( $_REQUEST );
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			echo $isRepeat;
		}else{
			//新验证组件
			$validateId=$_POST['validateId'];
			$validateValue=$_POST['validateValue'];
			$phoneNum = $_REQUEST['phoneNum'];
			$service->searchArr=array(
				$validateId."Eq"=>$validateValue ,
				"linkmanPhoneEq"=>$phoneNum
			);
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			$result=array(
				'jsonValidateReturn'=>array($_POST['validateId'],$_POST['validateError'])
			);
			if($isRepeat){
				$result['jsonValidateReturn'][2]="false";
			}else{
				$result['jsonValidateReturn'][2]="true";
			}
			echo util_jsonUtil::encode ( $result );
		}
	}

	/**
	 * 导出车辆供应商信息
	 */
	function c_excelOut() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_excelOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '车辆供应商信息';
		$startRowNum = 3;
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName ,$startRowNum);
	}

    /**
	 * 跳转到自定义导出excel页面
	 */
	function c_toExcelOutCustom() {
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' )); //供应商类型
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //发票属性
		$this->view('excelOutCustom');
	}

	/**
	 * 自定义导出车辆供应商信息
	 */
	function c_excelOutCustom() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['suppCode'])) //供应商编号
			$this->service->searchArr['suppCodeSea'] = $formData['suppCode'];

		if(!empty($formData['suppName'])) //供应商名称
			$this->service->searchArr['suppName'] = $formData['suppName'];

		if(!empty($formData['provinceName'])) //省份
			$this->service->searchArr['province'] = $formData['provinceName'];

		if(!empty($formData['cityName'])) //城市
			$this->service->searchArr['city'] = $formData['cityName'];

	 	if(!empty($formData['suppCategory'])) //供应商类型
			$this->service->searchArr['suppCategory'] = $formData['suppCategory'];

		if(!empty($formData['carAmountLower'])) //车辆范围上
			$this->service->searchArr['carAmountLower'] = $formData['carAmountLower'];
		if(!empty($formData['carAmountCeiling'])) //车辆范围下
			$this->service->searchArr['carAmountCeiling'] = $formData['carAmountCeiling'];

		if(!empty($formData['driverAmountLower'])) //司机数量上
			$this->service->searchArr['driverAmountLower'] = $formData['driverAmountLower'];
		if(!empty($formData['driverAmountCeiling'])) //司机数量下
			$this->service->searchArr['driverAmountCeiling'] = $formData['driverAmountCeiling'];

		if(!empty($formData['isEquipDriver'])) //能否配备司机
			$this->service->searchArr['isEquipDriver'] = $formData['isEquipDriver'];

		if(!empty($formData['isDriveTest'])) //有无路测经验
			$this->service->searchArr['isDriveTest'] = $formData['isDriveTest'];

		$rows = $this->service->listBySqlId('select_excelOut');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
		}

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}

		$colArr  = array();
		$modelName = '车辆供应商信息';
		$startRowNum = 3;
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName ,$startRowNum);
	}

    /**
	 * 跳转到导入excel页面
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '车辆供应商导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 跳转到供应商打入黑名单页面
	 */
	function c_toBlacklistView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "blacklistview" );
	}

	/**
	 * 跳转到供应商撤销黑名单页面
	 */
	function c_toUndoBlackView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "undoBlackView" );
	}

	/**
	 * 跳转到新增黑名单页面
	 */
	function c_toAddBlacklist() {
		$this->permCheck (); //安全校验
		$this->view ( "add-blacklist" );
	}

    /**
	 * 跳转到excel导入黑名单页面
	 */
	function c_toExcelInBlack() {
		$this->display('excelin-black');
	}

	/**
	 * 导入excel黑名单
	 */
	function c_excelInBlack() {
		set_time_limit(0);
		$resultArr = $this->service->blackExecelData_d ();

		$title = '车辆供应商黑名单导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 打入黑名单
	 */
	function c_blacklist() {
		$obj = $_POST[$this->objName];
		$obj['suppLevel'] = 0;
		$flag = $this->service->addBlacklist_d( $obj );
		if( $flag ){
			msg('保存成功');
		}else{
			msg('保存失败');
		}
	}

	/**
	 * 撤销黑名单
	 */
	function c_undoBlack() {
		$obj = $_POST[$this->objName];
		$obj['suppLevel'] = '';
		$flag = $this->service->addBlacklist_d( $obj );
		if( $flag ){
			msg('保存成功');
		}else{
			msg('保存失败');
		}
	}

	/**
	 * 获取权限
	 */
	function c_getLimits() {
		$limitName = explode(',' ,util_jsonUtil::iconvUTF2GB($_POST['limitArr']));
		$limitData = '';
		foreach ($limitName as $k => $v) {
			$limitData = $limitData.($this->service->this_limit[$v]?$this->service->this_limit[$v]:0).',';
		}
		echo util_jsonUtil::encode ( $limitData );
	}

    /**
     * 获取合同支付方式关联的费用填报信息
     */
	function c_getRelativeExpenseTmp(){
	    $payTypeId = isset($_POST['payTypeId'])? $_POST['payTypeId'] : '';
        $rentContId = isset($_POST['rentContId'])? $_POST['rentContId'] : '';
        $backArr = array(
            "result" => "no",
            "data" => array()
        );
        if($payTypeId != '' && $rentContId != ''){
            $chkSql = "select t.id as tmpId,t.expenseId,p.* from oa_contract_rentcar_expensetmp t 
                    left join oa_contract_rentcar_payinfos p on t.payInfoId = p.id 
                    where  t.payMoney > 0 and (t.ExaStatus = '未审批' or t.isConfirm = 0) and t.rentalContractId = '{$rentContId}' and t.payInfoId = '{$payTypeId}';";
            $result = $this->service->_db->getArray($chkSql);

            if($result){
                $resultArr = $result[0];
                if($resultArr['expenseId'] == ''){// 只考虑还未生成报销单的情况,如有报销单了,允许变更
                    $backArr['result'] = "ok";
                    $backArr['data'] = $resultArr;
                    $backArr['sql'] = $chkSql;
                }
            }
        }
        echo util_jsonUtil::encode($backArr);
    }
}
?>