<?php
/**
 * @author Show
 * @Date 2013��7��11�� ������ 13:30:34
 * @version 1.0
 * @description:ͨ���ʼ����ôӱ���Ʋ� 
 */
class controller_system_mailconfig_mainconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "mainconfigitem";
		$this->objPath = "system_mailconfig";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��ͨ���ʼ����ôӱ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������ͨ���ʼ����ôӱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭ͨ���ʼ����ôӱ�ҳ��
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
	 * ��ת���鿴ͨ���ʼ����ôӱ�ҳ��
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