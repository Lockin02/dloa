<?php
/**
 * @author Administrator
 * @Date 2013��4��23�� ���ڶ� 16:38:39
 * @version 1.0
 * @description:���ȿ�����Ϣ���Ʋ�
 */
class controller_hr_assess_assessrecords extends controller_base_action {

	function __construct() {
		$this->objName = "assessrecords";
		$this->objPath = "hr_assess";
		parent::__construct ();
	 }

	/**
	 * ��ת�����ȿ�����Ϣ�б�
	 */
    function c_page() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign('userNo',$userNo);
      	$this->assign('userAccount',$userAccount);
      $this->view('list');
    }

   /**
	 * ��ת���������ȿ�����Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���ȿ�����Ϣҳ��
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
	 * ��ת���鿴���ȿ�����Ϣҳ��
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