<?php
/**
 * @author Administrator
 * @Date 2012��10��7�� ������ 15:16:42
 * @version 1.0
 * @description:��ʦ���˷������Ʋ�
 */
class controller_hr_tutor_tutorScheme extends controller_base_action {

	function __construct() {
		$this->objName = "tutorScheme";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * ��ת����ʦ���˷����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ʦ���˷���ҳ��
	 */
	function c_toAdd() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->find(null,'id desc',null);
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}else{
			$this->assign('schemeName','');
			$this->assign('id','');
			$this->assign('tutProportion','');
			$this->assign('deptProportion','');
			$this->assign('hrProportion','');
			$this->assign('supProportion','');
			$this->assign('remark','');
		}
        $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ʦ���˷���ҳ��
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
	 * ��ת���鿴��ʦ���˷���ҳ��
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
	 * ��д��������
	 */
	function c_add() {
		try{
			$id = $this->service->add_d ( $_POST [$this->objName]);
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
			if ($id) {
				msg ( $msg );
			}
		}catch (Exception $e){
			msg($e->getMessage());
		}
	}
 }
?>