<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:13:15
 * @version 1.0
 * @description:配置项内容控制层
 */
class controller_goods_goods_propertiesitem extends controller_base_action {

	function __construct() {
		$this->objName = "propertiesitem";
		$this->objPath = "goods_goods";
		parent::__construct ();
	}

	/*
	 * 跳转到配置项内容列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增配置项内容页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑配置项内容页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看配置项内容页面
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
	 * 根据主表id获取从表清单
	 */
	function c_pageItemJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$resultArr = array ();
		if (is_array ( $rows )) {
			//			$assitemDao = new model_goods_goods_assitem ();
			//			foreach ( $rows as $key => $value ) {
			//				$assitemDao->searchArr = array ("mainId" => $value ['id'] );
			//				$assItemArr = $assitemDao->listBySqlId ();
			//				if (is_array ( $assItemArr )) {
			//					$assItemStr = "";
			//					foreach ( $assItemArr as $key => $val ) {
			//						$assItemStr .= $val ['itemId'] . ",";
			//					}
			//					$value ['assitem'] = $assItemStr;
			//
			//				} else {
			//					$value ['assitem'] = "";
			//				}
			//				array_push ( $resultArr, $value );
			//			}
			$asslistDao = new model_goods_goods_asslist ();
			foreach ( $rows as $key => $value ) {
				$asslistDao->searchArr = array ("mainId" => $value ['id'] );
				$asslistArr = $asslistDao->listBySqlId ();
				if (is_array ( $asslistArr )) {
					$assItemStr = "";
					$assItemIdStr="";
					$assItemTipStr="";
					foreach ( $asslistArr as $key => $val ) {
						$assItemIdStr .= $val ['itemIds'] . ",";
						$assItemTipStr .= $val ['itemNames'] . ",";
					}
					$value ['assitemIdStr'] = $assItemIdStr;
					$value ['assitemTipStr'] = $assItemTipStr;

				} else {
					$value ['assitemIdStr'] = "";
					$value ['assitemTipStr'] = "";
				}
				array_push ( $resultArr, $value );
			}
		}

		echo util_jsonUtil::encode ( $resultArr );
	}

	/**
	 * 获取单个配置json
	 */
	function c_getJson(){
		$row = $this->service->get_d($_GET['id']);
		echo util_jsonUtil::encode ( $row );
	}
}
?>