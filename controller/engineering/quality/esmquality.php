<?php
/**
 * @author Show
 * @Date 2011��12��8�� ������ 18:57:10
 * @version 1.0
 * @description:��Ŀ����(oa_esm_project_quality)���Ʋ�
 */
class controller_engineering_quality_esmquality extends controller_base_action {

	function __construct() {
		$this->objName = "esmquality";
		$this->objPath = "engineering_quality";
		parent::__construct ();
	}

	/*
	 * ��ת����Ŀ����(oa_esm_project_quality)
	 */
    function c_page() {
       	$this->view('list');
    }

	/*
	 * ��ת��Tab��Ŀ����
	 */
    function c_tabEsmquality() {
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
		$rs = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($rs);
		$this->assign('submiterName',$_SESSION['USERNAME']);
		$this->assign('submiterId',$_SESSION['USER_ID']);
		$this->assign('projectId',$_GET['projectId']);
		$this->showDatadicts ( array ('isDeal' => 'YANDN' ));
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
			$TypeOne = $this->getDataNameByCode ( $obj ['isDeal'] );
			$this->assign ( 'isDeal', $TypeOne );
			$this->view ( 'view' );
		} else {
			//��ͨ�������ֵ䣨��/�����ж�
			$isDeal = "";
			if( $obj ['isDeal']==1){
				$isDeal = "1Y";
			}else{
				$isDeal = "2N";
			}
			$this->showDatadicts ( array ('isDeal' => 'YANDN' ), $isDeal );
			$this->view ( 'edit' );
		}
	}
}
?>