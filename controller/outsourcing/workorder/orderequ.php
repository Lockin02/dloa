<?php
/**
 * @author phz
 * @Date 2014��1��18�� ������ 17:09:44
 * @version 1.0
 * @description:�����ӱ���Ʋ� 
 */
class controller_outsourcing_workorder_orderequ extends controller_base_action {

	function __construct() {
		$this->objName = "orderequ";
		$this->objPath = "outsourcing_workorder";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������ӱ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������ӱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����ӱ�ҳ��
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
	 * ��ת���鿴�����ӱ�ҳ��
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