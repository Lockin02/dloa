<?php
/**
 * @author Administrator
 * @Date 2011年9月2日 11:34:47
 * @version 1.0
 * @description:发货计划邮寄接受人控制层
 */
class controller_stock_outplan_outmail extends controller_base_action {

	function __construct() {
		$this->objName = "outmail";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * 跳转到发货计划邮寄接受人
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }


	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$mailmanArr = $this->service->selectFun();
		$this->assign('mailmanIds',$mailmanArr[0]);
		$this->assign('mailmanNames',$mailmanArr[1]);
		$this->display ( 'add' );
	}

    /**
     * 邮件接收人
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
     		msg('邮件接收人设置成功');
     	}
     }

 }
?>