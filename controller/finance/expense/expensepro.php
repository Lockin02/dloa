<?php
/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:07:32
 * @version 1.0
 * @description:�������������ϸ(��Ŀ����)���Ʋ� 
 */
class controller_finance_expense_expensepro extends controller_base_action {

	function __construct() {
		$this->objName = "expensepro";
		$this->objPath = "finance_expense";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���������������ϸ(��Ŀ����)�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������������������ϸ(��Ŀ����)ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�������������ϸ(��Ŀ����)ҳ��
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
	 * ��ת���鿴�������������ϸ(��Ŀ����)ҳ��
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