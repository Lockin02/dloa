<?php
/**
 * @author zengzx
 * @Date 2011��5��4�� 15:33:31
 * @version 1.0
 * @description:�����ƻ����Ʋ�
 */
class controller_stock_outplan_outplanProduct extends controller_base_action {

	function __construct() {
		$this->objName = "outplanProduct";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * �����ƻ��ʼĽ�����
     */
     function c_toMailTo(){
     	$this->view('mailTo');
     }


	/*
	 * ��ת�������ƻ�-tab
	 */
    function c_listTab() {
      $this->display('list-tab');
    }

	/*
	 * Ĭ�ϵ��б���ת����
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