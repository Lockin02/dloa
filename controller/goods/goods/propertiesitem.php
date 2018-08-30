<?php
/**
 * @author Administrator
 * @Date 2012��3��1�� 20:13:15
 * @version 1.0
 * @description:���������ݿ��Ʋ�
 */
class controller_goods_goods_propertiesitem extends controller_base_action {

	function __construct() {
		$this->objName = "propertiesitem";
		$this->objPath = "goods_goods";
		parent::__construct ();
	}

	/*
	 * ��ת�������������б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת����������������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭����������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴����������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * ��������id��ȡ�ӱ��嵥
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
	 * ��ȡ��������json
	 */
	function c_getJson(){
		$row = $this->service->get_d($_GET['id']);
		echo util_jsonUtil::encode ( $row );
	}
}
?>