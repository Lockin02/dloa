<?php

/**
 * �����豸���Ʋ���
 * @author chenzb
 */
class controller_asset_assetcard_equip extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "equip";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	}
	/**
	 * ��ת�������豸��Ϣ�б�
	 */
	function c_page() {
		$this->assign('assetId',$_GET['assetId']);
		$this->assign('assetCode',$_GET['assetCode']);
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$this->assign('sysLimit', $sysLimit['�����豸ɾ��Ȩ��']);
		$this->view ( 'list' );
	}
 /*
   *  ��ת�������豸��Ϣ�б�
	 */
	function c_toPage() {
		$this->assign('assetId',$_GET['assetId']);
		$this->view ( 'grid-list' );
	}


	/**
	 * ��ת�������豸����ҳ��
	 */

	function c_toAdd() {
		$this->assign('assetId',$_GET['assetId']);
		$this->assign('assetCode',$_GET['assetCode']);
		$this->view ( 'add' );
	}
	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}


}
?>