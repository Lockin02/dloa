<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:53:06
 * @version 1.0
 * @description:�豸����-���滻�豸����-���滻���Ͽ��Ʋ� 
 */
class controller_engineering_resources_replacedinfo extends controller_base_action {

	function __construct() {
		$this->objName = "replacedinfo";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���豸����-���滻�豸����-���滻�����б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������豸����-���滻�豸����-���滻����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�豸����-���滻�豸����-���滻����ҳ��
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
	 * ��ת���鿴�豸����-���滻�豸����-���滻����ҳ��
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