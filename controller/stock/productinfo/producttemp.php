<?php

/*
 * Created on 2010-7-17
 *	产品基本信息Controller
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_stock_producttemp_producttemp extends controller_base_action {

	function __construct() {
		$this->objName = "producttemp";
		$this->objPath = "stock_producttemp";
		parent::__construct ();
	}

    //临时物料列表
    function c_producttemp(){
    	$this->display("-templist");
    }
}
?>
