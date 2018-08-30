<?php
/**
 * @author Administrator
 * @Date 2012-09-25 09:54:23
 * @version 1.0
 * @description:销售备货物料清单控制层
 */
class controller_projectmanagent_stockup_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "projectmanagent_stockup";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售备货物料清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增销售备货物料清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑销售备货物料清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看销售备货物料清单页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


	/**
	 * 获取物料信息
	 */
	function c_addlistJson() {
		$service = $this->service;
//		$service->getParam ( $_REQUEST );
		$dao = new model_stock_productinfo_productinfo();
		$rows = $dao->get_d($_POST['equId']);
	  $arr[0]['productId'] = $rows['id'];
	  $arr[0]['productCode'] = $rows['productCode'];
	  $arr[0]['productName'] = $rows['productName'];
	  $arr[0]['productModel'] = $rows['pattern'];
	  $arr[0]['unitName'] = $rows['unitName'];

		//数据加入安全码
		$arr = $this->sconfig->md5Rows ( $arr );
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>