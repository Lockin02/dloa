<?php

/**
 * @author eric
 * @Date 2013-4-17 9:53:24
 * @version 1.0
 * @description: ��������Ϣ
 */
class controller_product_terminal_functioninfo extends controller_base_action {
        function __construct() {
		$this->objName = "functioninfo";
		$this->objPath = "product_terminal";
		parent::__construct ();
	}
        //��ת���б�ҳ
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
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
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
