<?php
/**
 * @author Show
 * @Date 2011��12��10�� ������ 9:59:32
 * @version 1.0
 * @description:��Ŀ����(oa_esm_project_risk)���Ʋ�
 */
class controller_engineering_risk_esmrisk extends controller_base_action {

	function __construct() {
		$this->objName = "esmrisk";
		$this->objPath = "engineering_risk";
		parent::__construct ();
	}

	/*
	 * ��ת����Ŀ����(oa_esm_project_risk)
	 */
    function c_page() {
       $this->view('list');
    }

    /*
	 * ��ת��Tab��Ŀ����
	 */
    function c_tabEsmrisk() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }

    /*
	 * ��ת���鿴Tab��Ŀ����
	 */
    function c_toViewList() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('viewlist');
    }

    /**
	 * ��ת��Tab����ҳ��
	 */
	function c_toAdd() {
		$rs = $this->service->getObjInfo_d($_GET['id']);
		$this->assignFunc($rs);
		$this->assign('submiterName',$_SESSION['USERNAME']);
		$this->assign('submiterCode',$_SESSION['USER_ID']);
		$this->assign('projectId',$_GET['id']);
		$this->assign('submitDate',day_date);
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
}
?>