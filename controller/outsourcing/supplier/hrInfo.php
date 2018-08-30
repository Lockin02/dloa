<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:49:55
 * @version 1.0
 * @description:供应商人力资源信息控制层
 */
class controller_outsourcing_supplier_hrInfo extends controller_base_action {

	function __construct() {
		$this->objName = "hrInfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到供应商人力资源信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增供应商人力资源信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑供应商人力资源信息页面
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
	 * 跳转到查看供应商人力资源信息页面
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
			$service->searchArr = array('suppId' => $_POST['suppId']);
			$objArr = $service->listBySqlId('select_sum');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['skillArea'] = '汇总';
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