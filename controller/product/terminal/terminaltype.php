<?php

/**
 * @author eric
 * @Date 2013-4-15 17:12:03
 * @version 1.0
 * @description: 终端分类
 */
class controller_product_terminal_terminaltype extends controller_base_action {
        function __construct() {
		$this->objName = "terminaltype";
		$this->objPath = "product_terminal";
		parent::__construct ();
	}
        //跳转到列表页
        function c_page() {
            $this->view('list');
        }
        function c_toAdd() {
            $productIdVal=  isset($_GET['productId']) ? $_GET['productId'] : '';
            $productNameVal=  isset($_GET['productName']) ? $_GET['productName'] : '';
            $this->assign('productId', $productIdVal );
            $this->assign('productName', $productNameVal);
            $this->view('add');
        }
        function c_toEdit(){
            $this->view('edit');
        }
        /**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}
        
}
?>
