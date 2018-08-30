<?php
/**
 * @author Show
 * @Date 2012��3��31�� ������ 11:13:43
 * @version 1.0
 * @description:���������ͬ����������Ϣ���Ʋ�
 */
class controller_contract_otherpayapply_otherpayapply extends controller_base_action {

	function __construct() {
		$this->objName = "otherpayapply";
		$this->objPath = "contract_otherpayapply";
		parent::__construct ();
	}

	/**
	 * ��ת�����������ͬ����������Ϣ�б�
	 */
    function c_page() {
		$this->view('list');
    }

	/**
	 * ��ת���������������ͬ����������Ϣҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

  	/**
	 * ��ת���༭���������ͬ����������Ϣҳ��
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
	 * ��ת���鿴���������ͬ����������Ϣҳ��
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
	 * �������ͺ�id���������Ӧ��������id
	 */
	function c_getFeeDeptId(){
		$contractId = $_POST['contractId'];
		$contractType = $_POST['contractType'];
		$obj = $this->service->find(array('contractId' => $contractId ,'contractType' => $contractType),null,'feeDeptId');
//		print_r($obj);
		if(is_array($obj)){
			echo $obj['feeDeptId'];
		}else{
			echo 0;
		}
	}
}
?>