<?php
/**
 * @author Administrator
 * @Date 2012年7月16日 15:00:09
 * @version 1.0
 * @description:行政区域表控制层 
 */
class controller_asset_basic_agency extends controller_base_action {

	function __construct() {
		$this->objName = "agency";
		$this->objPath = "asset_basic";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到行政区域表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
    /**
	 * 跳转到新增行政区域表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
    /**
	 * 跳转到编辑行政区域表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
    /**
	 * 跳转到查看行政区域表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         
		$this->view ( 'view' );
   }
   
   /**
    * grid列表下拉过滤
    */
   function c_getSelection(){
   	$rows = $this->service->list_d();
   	$datas = array();
   	foreach( $rows as $key=>$val ){
   		$datas[$key]['text']=$val['agencyName'];
   		$datas[$key]['value']=$val['agencyCode'];
   	}
   	echo util_jsonUtil::encode ( $datas );
   }
 }