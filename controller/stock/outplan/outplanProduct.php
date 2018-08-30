<?php
/**
 * @author zengzx
 * @Date 2011年5月4日 15:33:31
 * @version 1.0
 * @description:发货计划控制层
 */
class controller_stock_outplan_outplanProduct extends controller_base_action {

	function __construct() {
		$this->objName = "outplanProduct";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * 跳转到发货计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * 发货计划邮寄接收人
     */
     function c_toMailTo(){
     	$this->view('mailTo');
     }


	/*
	 * 跳转到发货计划-tab
	 */
    function c_listTab() {
      $this->display('list-tab');
    }

	/*
	 * 默认的列表跳转方法
	 */
	function c_list() {
		$this->assign('docStatus',$_GET['docStatus']);
		$this->display ( 'list' );
	}

	function c_pageByContEqu(){
     	$this->view('pagebycontequ');
	}
}
?>