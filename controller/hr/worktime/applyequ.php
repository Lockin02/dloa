<?php
/**
 * @author yxin1
 * @Date 2014��4��24�� 13:37:38
 * @version 1.0
 * @description:�ڼ��ռӰ�������ϸ����Ʋ� 
 */
class controller_hr_worktime_applyequ extends controller_base_action {

	function __construct() {
		$this->objName = "applyequ";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���ڼ��ռӰ�������ϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������ڼ��ռӰ�������ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�ڼ��ռӰ�������ϸ��ҳ��
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
	 * ��ת���鿴�ڼ��ռӰ�������ϸ��ҳ��
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