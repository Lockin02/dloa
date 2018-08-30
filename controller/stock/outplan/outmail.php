<?php
/**
 * @author Administrator
 * @Date 2011��9��2�� 11:34:47
 * @version 1.0
 * @description:�����ƻ��ʼĽ����˿��Ʋ�
 */
class controller_stock_outplan_outmail extends controller_base_action {

	function __construct() {
		$this->objName = "outmail";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ƻ��ʼĽ�����
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }


	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$mailmanArr = $this->service->selectFun();
		$this->assign('mailmanIds',$mailmanArr[0]);
		$this->assign('mailmanNames',$mailmanArr[1]);
		$this->display ( 'add' );
	}

    /**
     * �ʼ�������
     */
     function c_add(){
     	$service = $this->service;
     	$mailmans = $_POST[$this->objName];
     	$mailmanIdArr = explode(',',$mailmans['mailmanIds']);
     	$mailmanNameArr = explode(',',$mailmans['mailmanNames']);
     	$mailmanArr = array();
     	foreach ( $mailmanIdArr as $key => $val){
     		$mailmanArr[$key]['mailmanId'] = $val;
     		$mailmanArr[$key]['mailmanName'] = $mailmanNameArr[$key];
     	}
     	if($service->add_d($mailmanArr)){
     		msg('�ʼ����������óɹ�');
     	}
     }

 }
?>