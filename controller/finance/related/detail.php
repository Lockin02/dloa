<?php
/**
 * @author Show
 * @Date 2010年12月29日 星期三 20:07:33
 * @version 1.0
 * @description:发表勾稽记录表控制层
 */
class controller_finance_related_detail extends controller_base_action {

	function __construct() {
		$this->objName = "detail";
		$this->objPath = "finance_related";
		parent::__construct ();
	 }

	/*
	 * 跳转到发表勾稽记录表
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



	/**
	 * 钩稽详情
	 */
	function c_hookInfo(){
		$service = $this->service;
		$this->show->assign('relatedId',$service->getBaseInfo_d($_GET['id'],$_GET['hookObj']));
		$this->show->display( $this->objPath . '_' . $this->objName . '-list-info' );
	}
 }
?>