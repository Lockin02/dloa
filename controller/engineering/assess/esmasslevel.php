<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 11:23:19
 * @version 1.0
 * @description:���˵ȼ����ñ���Ʋ� 
 */
class controller_engineering_assess_esmasslevel extends controller_base_action {

	function __construct() {
		$this->objName = "esmasslevel";
		$this->objPath = "engineering_assess";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����˵ȼ����ñ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������˵ȼ����ñ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���˵ȼ����ñ�ҳ��
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
	 * ��ת���鿴���˵ȼ����ñ�ҳ��
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