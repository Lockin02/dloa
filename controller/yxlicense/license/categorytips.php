<?php
/**
 * @author show
 * @Date 2013��11��25�� 10:56:46
 * @version 1.0
 * @description:(��license)���ñ�ע��Ϣ���Ʋ�
 */
class controller_yxlicense_license_categorytips extends controller_base_action {

	function __construct() {
		$this->objName = "categorytips";
		$this->objPath = "yxlicense_license";
		parent :: __construct();
	}

	/**
	 * ��ת��(��license)���ñ�ע��Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����ע������Ϣҳ��
	 */
	function c_toTips() {
		$this->assign('formId',$_GET['id']);
		$this->view ( 'tips' );
	}
	
	//����
	function c_tips() {
		$obj = $_POST[$this->objName];
		$id = $this->service->tips_d ($obj);
		if ($id) {
			msg ( "����ɹ�" );
		} else {
			msg ( "����ʧ��" );
		}
	}
}