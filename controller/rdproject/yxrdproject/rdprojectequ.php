<?php
/**
 * @author Administrator
 * @Date 2011年5月8日 14:16:41
 * @version 1.0
 * @description:研发产品清单控制层
 */
class controller_rdproject_yxrdproject_rdprojectequ extends controller_base_action {

	function __construct() {
		$this->objName = "rdprojectequ";
		$this->objPath = "rdproject_yxrdproject";
		parent::__construct ();
	 }

	/*
	 * 跳转到研发产品清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>