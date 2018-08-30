<?php
/**
 * @author Administrator
 * @Date 2012��8��25�� ������ 10:54:13
 * @version 1.0
 * @description:���ڿ��Ʋ�
 */
class controller_hr_hols_hols extends controller_base_action {

	function __construct() {
		$this->objName = "hols";
		$this->objPath = "hr_hols";
		parent::__construct ();
	 }

	/**
	 * ��ת�������б�
	 */
    function c_page() {
      $this->assign('UserId',$_GET['userNo']);
      $this->view('list');
    }

   /**
	 * ��ת����������ҳ��
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
   }

   /**
	 * ��ת���༭����ҳ��
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
	 * ��ת���鿴����ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$personnelInfo = $this->service->getPersonnelByUserId($obj['UserId']);
		$this->assign ('userNo', $personnelInfo['userNo']);
		$this->assign ('userName', $personnelInfo['userName']);
		$this->assign ('companyName', $personnelInfo['companyName']);
		$this->assign ('deptNameS', $personnelInfo['deptNameS']);
		$this->assign ('deptNameT', $personnelInfo['deptNameT']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>