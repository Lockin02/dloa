<?php
/**
 * @author Administrator
 * @Date 2011年7月22日 9:41:21
 * @version 1.0
 */
class controller_system_currency_currency extends controller_base_action {

	function __construct() {
		$this->objName = "currency";
		$this->objPath = "system_currency";
		parent::__construct ();
	 }

	/*
	 * 跳转到oa_system_region
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



	/**
	 * @ ajax判断项 验证币别是否重复
	 *
	 */
	function c_ajaxCurrency() {
		$service = $this->service;
		$currency = isset ( $_GET ['ajaxCurrency'] ) ? $_GET ['ajaxCurrency'] : false;
		$searchArr = array ("ajaxCurrency" => $currency );
		$isRepeat = $service->isRepeat ( $searchArr, "" );

		if ($isRepeat == false) {
			echo 0;
		} else {
			echo 1;
		}
	}
 }
?>