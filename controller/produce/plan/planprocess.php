<?php
/**
 * @author yxin1
 * @Date 2014��8��29�� 14:39:36
 * @version 1.0
 * @description:�����ƻ�������Ʋ� 
 */
class controller_produce_plan_planprocess extends controller_base_action {

	function __construct() {
		$this->objName = "planprocess";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������ƻ������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������ƻ�����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����ƻ�����ҳ��
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
	 * ��ת���鿴�����ƻ�����ҳ��
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