<?php
/**
 * @author show
 * @Date 2014年12月31日 
 * @version 1.0
 * @description:赔偿单扣款记录控制层
 */
class controller_finance_compensate_compensatededuct extends controller_base_action {

	function __construct() {
		$this->objName = "compensatededuct";
		$this->objPath = "finance_compensate";
		parent :: __construct();
	}

	/**
	 * 跳转到个人赔偿扣款记录列表
	 */
	function c_page() {
		$this->view('list');
	}
}