<?php
/**
 * @author Show
 * @Date 2011��1��13�� ������ 17:22:36
 * @version 1.0
 * @description:������Ʋ�
 */
class controller_finance_adjust_adjust extends controller_base_action {

	function __construct() {
		$this->objName = "adjust";
		$this->objPath = "finance_adjust";
		parent::__construct ();
	 }

	/*
	 * ��ת�����
	 */
    function c_page() {
      $this->display('list');
    }

    /**************************�����б�ҳ��***************************/
    /**
     * �����߲�ѯ�б�
     */
    function c_listInfo(){
        unset($_GET['action']);
        unset($_GET['model']);
        $thisObj = !empty($_GET) ? $_GET : array(
                                        'formDateBegin'=>'','formDateEnd'=>'','supplierName'=>'','productId'=>'',
                                        'productNo'=>'','supplierId'=>'');
        $this->assignFunc($thisObj);
        $this->display('listinfo');
    }

    /**
     * �ɹ���Ʊ�߼�����
     */
    function c_toListInfoSearch(){
        $this->showDatadicts ( array ('invType' => 'FPLX' ),$_GET['invType']);
        unset($_GET['invType']);

        $this->assignFunc($_GET);
        $this->display('listinfo-search');
    }
    /**************************�����б�ҳ��***************************/
}
?>