<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:50
 * @version 1.0
 * @description:Ԥ��ִ�м�¼���Ʋ� 
 */
class controller_system_warninglogs_warninglogs extends controller_base_action {

	function __construct() {
		$this->objName = "warninglogs";
		$this->objPath = "system_warninglogs";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��Ԥ��ִ�м�¼�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������Ԥ��ִ�м�¼ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭Ԥ��ִ�м�¼ҳ��
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
	 * ��ת���鿴Ԥ��ִ�м�¼ҳ��
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