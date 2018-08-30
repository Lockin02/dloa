<?php
/**
 * @author Administrator
 * @Date 2012年6月22日 星期五 11:09:55
 * @version 1.0
 * @description:人事管理-基础信息-健康信息控制层
 */
class controller_hr_personnel_health extends controller_base_action {

	function __construct() {
		$this->objName = "health";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * 跳转到人事管理-基础信息-健康信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增人事管理-基础信息-健康信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑人事管理-基础信息-健康信息页面
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
	 * 跳转到查看人事管理-基础信息-健康信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

    function c_toPersonnelList(){
    	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo);
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }
    /******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/*
	 * 导出excel
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '健康信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * 导出excel
	 */
	  function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userName']))
	 		$this->service->searchArr['userNameSearch'] = $object['userName'];
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['hospital']))
	 		$this->service->searchArr['hospital'] = $object['hospital'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['endDate']))
	 		$this->service->searchArr['endDate'] = $object['endDate'];
		if(!empty($object['checkResult']))
			$this->service->searchArr['checkResult'] = $object['checkResult'];
		if(!empty($object['remark']))
			$this->service->searchArr['remark'] = $object['remark'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['hospital']=$planEquRows[$key]['hospital'];
			$exportData[$key]['checkDate']=$planEquRows[$key]['checkDate'];
			$exportData[$key]['checkResult']=$planEquRows[$key]['checkResult'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			$exportData[$key]['hospitalOpinion']=$planEquRows[$key]['hospitalOpinion'];
		}
		return $this->service->excelOut ( $exportData );
	 }
 }
?>