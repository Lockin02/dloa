<?php
/**
 * @author Show
 * @Date 2010��12��29�� ������ 20:07:33
 * @version 1.0
 * @description:��������¼����Ʋ�
 */
class controller_finance_related_detail extends controller_base_action {

	function __construct() {
		$this->objName = "detail";
		$this->objPath = "finance_related";
		parent::__construct ();
	 }

	/*
	 * ��ת����������¼��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



	/**
	 * ��������
	 */
	function c_hookInfo(){
		$service = $this->service;
		$this->show->assign('relatedId',$service->getBaseInfo_d($_GET['id'],$_GET['hookObj']));
		$this->show->display( $this->objPath . '_' . $this->objName . '-list-info' );
	}
 }
?>