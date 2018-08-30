<?php
/**
 * @author Michael
 * @Date 2014年1月7日 星期二 10:27:48
 * @version 1.0
 * @description:车辆供应商-车辆资源库控制层
 */
class controller_outsourcing_outsourcessupp_vehicle extends controller_base_action {

	function __construct() {
		$this->objName = "vehicle";
		$this->objPath = "outsourcing_outsourcessupp";
		parent::__construct ();
	}

	/**
	 * 跳转到车辆供应商-车辆资源库列表
	 */
    function c_page() {
		$this->view('list');
	}

   /**
	 * 跳转到新增车辆供应商-车辆资源库页面
	 */
	function c_toAdd() {
		$this->view ( 'add' )
;	}

   /**
	 * 从供应商跳转到新增车辆供应商-车辆资源库页面
	 */
	function c_toAddSupp() {
		$vehiclesuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$obj = $vehiclesuppDao->get_d( $_GET ['suppId'] );
		$this->assign ('suppId' ,$obj['id']);
		$this->assign ('suppCode' ,$obj['suppCode']);
		$this->assign ('suppName' ,$obj['suppName']);
		$this->view ( 'add-supp' );
	}

   /**
	 * 跳转到编辑车辆供应商-车辆资源库页面
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
	 * 从供应商跳转到编辑车辆供应商-车辆资源库页面
	 */
	function c_toEditSupp() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit-supp');
	}

   /**
	 * 跳转到编辑车辆供应商-车辆资源库列表
	 */
	function c_toEditList() {
		$this->permCheck (); //安全校验
		$suppId = isset($_GET ['suppId'])?$_GET ['suppId']:'';
		$this->assign('suppId' ,$suppId);
		$this->view ( 'edit-list' );
	}

   /**
	 * 跳转到查看车辆供应商-车辆资源库页面
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
	 * 跳转到查看车辆供应商-车辆资源库列表
	 */
	function c_toViewList() {
		$this->permCheck (); //安全校验
		$suppId = isset($_GET ['suppId'])?$_GET ['suppId']:'';
		$this->assign('suppId' ,$suppId);
		$this->view ( 'view-list' );
	}

	/**
	 * 导出车辆资源库信息
	 */
	function c_excelOut() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_excelOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '车辆资源库信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	}

    /**
	 * 跳转到自定义导出excel页面
	 */
	function c_toExcelOutCustom() {
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

		if(!empty($formData['place'])) //地点
			$this->service->searchArr['place'] = $formData['place'];

		if(!empty($formData['carNumber'])) //车牌号
			$this->service->searchArr['carNumber'] = $formData['carNumber'];

	 	if(!empty($formData['carModel'])) //车型
			$this->service->searchArr['carModel'] = $formData['carModel'];

		if(!empty($formData['brand'])) //品牌
			$this->service->searchArr['brand'] = $formData['brand'];

		if(!empty($formData['displacement'])) //排量
			$this->service->searchArr['displacement'] = $formData['displacement'];

		if(!empty($formData['buyDateSta'])) //购车日期上
			$this->service->searchArr['buyDateSta'] = $formData['buyDateSta'];
		if(!empty($formData['buyDateEnd'])) //购车日期下
			$this->service->searchArr['buyDateEnd'] = $formData['buyDateEnd'];

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
		$modelName = '车辆资源库信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
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

		$title = '车辆资源库导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}
?>