<?php
/**
 * @author yxin1
 * @Date 2014��12��1�� 13:43:22
 * @version 1.0
 * @description:���ָ�����ϸ���Ʋ� 
 */
class controller_contract_gridreport_gridindicatorsitem extends controller_base_action {

	function __construct() {
		$this->objName = "gridindicatorsitem";
		$this->objPath = "contract_gridreport";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����ָ�����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������ָ�����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���ָ�����ϸҳ��
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
	 * ��ת���鿴���ָ�����ϸҳ��
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