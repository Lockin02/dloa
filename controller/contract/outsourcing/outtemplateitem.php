<?php
/**
 * @author Show
 * @Date 2013��10��8�� 0:20:42
 * @version 1.0
 * @description:���ģ�����ģ����ϸ���Ʋ� 
 */
class controller_contract_outsourcing_outtemplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "outtemplateitem";
		$this->objPath = "contract_outsourcing";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����ģ�����ģ����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������ģ�����ģ����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���ģ�����ģ����ϸҳ��
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
	 * ��ת���鿴���ģ�����ģ����ϸҳ��
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