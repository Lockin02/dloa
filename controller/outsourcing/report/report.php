<?php
/*
 * Created on 2014-1-2
 */
 class controller_outsourcing_report_report extends controller_base_action {

	function __construct() {
		$this->objName = "report";
		$this->objPath = "outsourcing_report";
		parent::__construct ();
	}

	/**
	 *外包数据库报表
	 */
	 function c_toOutsourcingReport(){
	 	extract($_GET);
	 	$projectCode = isset ( $_GET ['projectCode'] ) ? $_GET ['projectCode']:'';
	 	$projectName = isset ( $_GET ['projectName'] ) ? $_GET ['projectName']:'';
	 	$orderCode = isset ( $_GET ['orderCode'] ) ? $_GET ['orderCode']:'';
	 	$signCompanyName = isset ( $_GET ['signCompanyName'] ) ? $_GET ['signCompanyName']:'';
	 	$this->showDatadicts ( array ('nature' => 'GCXMXZ' ),$nature); //项目类型
	 	$this->showDatadicts ( array ('itemStatus' => 'GCXMZT' ),$itemStatus); //项目状态
	 	$this->assign('projectCode',$projectCode);  //项目编号
	 	$this->assign('projectName',$projectName); //项目名称
	 	$this->assign('orderCode',$orderCode);  //外包合同编号
	 	$this->assign('signCompanyName',$signCompanyName);//外包供应商
	 	$this->assign('natureName',$natureName);
	 	$this->assign('itemStatusName',$itemStatusName);
	 	$this->view('outsourcing');
	 }

}
?>
