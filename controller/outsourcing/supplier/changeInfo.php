<?php
/**
 * @author Administrator
 * @Date 2013��10��28�� ����һ 19:56:25
 * @version 1.0
 * @description:�ȼ������¼���Ʋ�
 */
class controller_outsourcing_supplier_changeInfo extends controller_base_action {

	function __construct() {
		$this->objName = "changeInfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת���ȼ������¼�б�
	 */
    function c_page() {
	 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
	 $this->assign('suppId',$suppId);
      $this->view('list');
    }

   /**
	 * ��ת�������ȼ������¼ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�ȼ������¼ҳ��
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
	 * ��ת���鿴�ȼ������¼ҳ��
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