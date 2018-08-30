<?php
/**
 * @author Michael
 * @Date 2014��7��21�� 11:45:06
 * @version 1.0
 * @description:���¹���-������ѡ��¼���Ʋ� 
 */
class controller_hr_personnel_excel extends controller_base_action {

	function __construct() {
		$this->objName = "excel";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����¹���-������ѡ��¼�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������¹���-������ѡ��¼ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���¹���-������ѡ��¼ҳ��
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
	 * ��ת���鿴���¹���-������ѡ��¼ҳ��
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