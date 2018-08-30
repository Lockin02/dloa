<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 9:59:32
 * @version 1.0
 * @description:项目风险(oa_esm_project_risk)控制层
 */
class controller_engineering_risk_esmrisk extends controller_base_action {

	function __construct() {
		$this->objName = "esmrisk";
		$this->objPath = "engineering_risk";
		parent::__construct ();
	}

	/*
	 * 跳转到项目风险(oa_esm_project_risk)
	 */
    function c_page() {
       $this->view('list');
    }

    /*
	 * 跳转到Tab项目风险
	 */
    function c_tabEsmrisk() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }

    /*
	 * 跳转到查看Tab项目风险
	 */
    function c_toViewList() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('viewlist');
    }

    /**
	 * 跳转到Tab新增页面
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
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
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