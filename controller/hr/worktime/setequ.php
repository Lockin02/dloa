<?php
/**
 * @author yxin1
 * @Date 2014��4��24�� 9:53:16
 * @version 1.0
 * @description:�����ڼ�����ϸ���Ʋ� 
 */
class controller_hr_worktime_setequ extends controller_base_action {

	function __construct() {
		$this->objName = "setequ";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������ڼ�����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������ڼ�����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����ڼ�����ϸҳ��
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
	 * ��ת���鿴�����ڼ�����ϸҳ��
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