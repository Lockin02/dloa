<?php
/**
 * @author phz
 * @Date 2014年1月20日 星期一 10:37:38
 * @version 1.0
 * @description:工单控制层 
 */
class controller_outsourcing_workorder_order extends controller_base_action {

	function __construct() {
		$this->objName = "order";
		$this->objPath = "outsourcing_workorder";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到工单列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增工单页面
	 */
	function c_toAdd() {
	 $this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//合同外包方式
	 $this->showDatadicts ( array ('nature' => 'GCXMXZ' ) );//项目性质
     $this->view ( 'add' );
	 
   }
   
   /**
	 * 跳转到编辑工单页面
	 */
	function c_toEdit() {
//   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      		$this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看工单页面
	 */
	function c_toView() {
//      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
  	 /**
	 * 导出汇总数据excel
	 */
	function c_excelOutAll() {
		set_time_limit(0);
		if($_GET['act']==1){	//	判断是否从供应商工单导出
			$suppCode = $_SESSION['USER_ID'];
			$this->service->searchArr['suppCode'] = $suppCode;
		}
		$rows = $this->service->list_d('select_orderOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		//var_dump($rows);exit();
		$colArr  = array(
		);
		$modelName = '外包工单';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);
	}
	 /*
	  * 跳转到外包工单高级查询页面
	  */
	function c_toSearchExport(){
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ));//合同外包方式
		$this->showDatadicts ( array ('nature' => 'GCXMXZ' ));//外包：项目性质
	  	$this->view ( 'searchExport' );
	}
	  /*
	   * 外包工单高级查询导出处理
	   */
	 function c_searchExport(){
	   	set_time_limit(0);
	   	$service = $this->service;
	 	$formData = $_POST[$this->objName];
	 	if(trim($formData['approvalCode'])){ //外包立项编号
			$service->searchArr['approvalCode'] = $formData['approvalCode'];
	 	}
	 	if(trim($formData['suppName'])){ //外包供应商名称
			$service->searchArr['suppName'] = $formData['suppName'];
	 	}
	 	if(trim($formData['suppCode'])){ //外包供应商编号
			$service->searchArr['suppCode'] = $formData['suppCode'];
	 	}
	 	if(trim($formData['projectName'])){ //项目名称
			$service->searchArr['projectName'] = $formData['projectName'];
	 	}
	 	if(trim($formData['projectCode'])){ //项目编号
			$service->searchArr['projectCode'] = $formData['projectCode'];
	 	}
	 	if(trim($formData['provinceId'])){ //项目身份
			$service->searchArr['provinceId'] = $formData['provinceId'];
	 	}
	 	if(trim($formData['suppType'])){ //外包类型
			$service->searchArr['suppType'] = $formData['suppType'];
	 	}
	 	if(trim($formData['natureCode'])){ //项目性质
			$service->searchArr['natureCode'] = $formData['natureCode'];
	 	}
	 	if(trim($formData['projectManager'])){ //项目经理
			$service->searchArr['projectManager'] = $formData['projectManager'];
	 	}
	 	if(trim($formData['ExaStatus'])){ //审核状态
			$service->searchArr['ExaStatus'] = $formData['ExaStatus'];
	 	}
	 	if(trim($formData['ExaDT'])){ //审核时间
			$service->searchArr['ExaDT'] = $formData['ExaDT'];
	 	}
	 	$rows = $service->list_d('select_orderOut');
	 	for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array(
		);
		$modelName = '外包工单';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);
	 }
	 /*
	  * 供应商工单
	  */
	 function c_toSupplierOrder(){
	 	$userAccount = $_SESSION['USER_ID'];
		$suppCode = $this->service->get_table_fields('oa_outsourcesupp_personnel', "userAccount='".$userAccount."'", 'suppCode');
		if($suppCode){
			$this->assign('suppCode',$suppCode);
		}
		else{
			$this->assign('suppCode',"-");	//避免查询过程因为空值而不带上该条件
		}
		$this->view('supplierList');
		
	 }
 }
?>