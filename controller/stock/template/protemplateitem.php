<?php
/**
 * @author Show
 * @Date 2013��8��2�� ������ 14:41:42
 * @version 1.0
 * @description:����ģ��������ϸ����Ʋ� 
 */
class controller_stock_template_protemplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "protemplateitem";
		$this->objPath = "stock_template";
		parent::__construct ();
	 }
    
	/**
	 * ��ת������ģ��������ϸ���б�
	 */
    function c_page() {
      	$this->view('list');
    }
    
   /**
	 * ��ת����������ģ��������ϸ��ҳ��
	 */
	function c_toAdd() {
     	$this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭����ģ��������ϸ��ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
      	$this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴����ģ��������ϸ��ҳ��
	 */
	function c_toView() {
      	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      	$this->view ( 'view' );
   }
 }
?>