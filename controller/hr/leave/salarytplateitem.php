<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 15:29:33
 * @version 1.0
 * @description:�����嵥ģ���嵥���Ʋ� 
 */
class controller_hr_leave_salarytplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "salarytplateitem";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������嵥ģ���嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������嵥ģ���嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����嵥ģ���嵥ҳ��
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
	 * ��ת���鿴�����嵥ģ���嵥ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
	/**
	* ��ȡ�����嵥editgrid����
	*/
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>