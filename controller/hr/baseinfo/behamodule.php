<?php
/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:12:40
 * @version 1.0
 * @description:��Ϊģ�����ÿ��Ʋ� 
 */
class controller_hr_baseinfo_behamodule extends controller_base_action {

	function __construct() {
		$this->objName = "behamodule";
		$this->objPath = "hr_baseinfo";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ϊģ�������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ϊģ������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ϊģ������ҳ��
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
	 * ��ת���鿴��Ϊģ������ҳ��
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