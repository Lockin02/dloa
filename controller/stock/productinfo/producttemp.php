<?php

/*
 * Created on 2010-7-17
 *	��Ʒ������ϢController
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_stock_producttemp_producttemp extends controller_base_action {

	function __construct() {
		$this->objName = "producttemp";
		$this->objPath = "stock_producttemp";
		parent::__construct ();
	}

    //��ʱ�����б�
    function c_producttemp(){
    	$this->display("-templist");
    }
}
?>
