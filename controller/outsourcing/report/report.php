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
	 *������ݿⱨ��
	 */
	 function c_toOutsourcingReport(){
	 	extract($_GET);
	 	$projectCode = isset ( $_GET ['projectCode'] ) ? $_GET ['projectCode']:'';
	 	$projectName = isset ( $_GET ['projectName'] ) ? $_GET ['projectName']:'';
	 	$orderCode = isset ( $_GET ['orderCode'] ) ? $_GET ['orderCode']:'';
	 	$signCompanyName = isset ( $_GET ['signCompanyName'] ) ? $_GET ['signCompanyName']:'';
	 	$this->showDatadicts ( array ('nature' => 'GCXMXZ' ),$nature); //��Ŀ����
	 	$this->showDatadicts ( array ('itemStatus' => 'GCXMZT' ),$itemStatus); //��Ŀ״̬
	 	$this->assign('projectCode',$projectCode);  //��Ŀ���
	 	$this->assign('projectName',$projectName); //��Ŀ����
	 	$this->assign('orderCode',$orderCode);  //�����ͬ���
	 	$this->assign('signCompanyName',$signCompanyName);//�����Ӧ��
	 	$this->assign('natureName',$natureName);
	 	$this->assign('itemStatusName',$itemStatusName);
	 	$this->view('outsourcing');
	 }

}
?>
