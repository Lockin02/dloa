<?php
/**
 * @author Show
 * @Date 2014年1月7日 星期二 9:21:37
 * @version 1.0
 * @description:车辆供应商-车辆资源信息控制层
 */
class controller_outsourcing_outsourcessupp_vehiclesuppequ extends controller_base_action {

	function __construct() {
		$this->objName = "vehiclesuppequ";
		$this->objPath = "outsourcing_outsourcessupp";
		parent::__construct ();
	 }

	/**
	 * 跳转到车辆供应商-车辆资源信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增车辆供应商-车辆资源信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑车辆供应商-车辆资源信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看车辆供应商-车辆资源信息页面
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
	 * 获取所有数据返回json
	 */
	function c_listJsonView() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if(is_array($rows)){
			//加载合计
			$service->sort = "";
			$service->searchArr = array('parentId' => $_POST['parentId']);
			$objArr = $service->listBySqlId('select_sum');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['area'] = '汇总';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>