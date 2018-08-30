<?php
/**
 * @author Administrator
 * @Date 2011年6月2日 10:30:23
 * @version 1.0
 * @description:生产任务清单控制层 
 */
class controller_produce_protask_protaskequ extends controller_base_action {

	function __construct() {
		$this->objName = "protaskequ";
		$this->objPath = "produce_protask";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到生产任务清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>