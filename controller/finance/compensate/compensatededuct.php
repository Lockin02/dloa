<?php
/**
 * @author show
 * @Date 2014��12��31�� 
 * @version 1.0
 * @description:�⳥���ۿ��¼���Ʋ�
 */
class controller_finance_compensate_compensatededuct extends controller_base_action {

	function __construct() {
		$this->objName = "compensatededuct";
		$this->objPath = "finance_compensate";
		parent :: __construct();
	}

	/**
	 * ��ת�������⳥�ۿ��¼�б�
	 */
	function c_page() {
		$this->view('list');
	}
}