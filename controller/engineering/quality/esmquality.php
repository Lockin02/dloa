<?php
/**
 * @author Show
 * @Date 2011年12月8日 星期四 18:57:10
 * @version 1.0
 * @description:项目质量(oa_esm_project_quality)控制层
 */
class controller_engineering_quality_esmquality extends controller_base_action {

	function __construct() {
		$this->objName = "esmquality";
		$this->objPath = "engineering_quality";
		parent::__construct ();
	}

	/*
	 * 跳转到项目质量(oa_esm_project_quality)
	 */
    function c_page() {
       	$this->view('list');
    }

	/*
	 * 跳转到Tab项目质量
	 */
    function c_tabEsmquality() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }

	/*
	 * 跳转到查看Tab项目质量
	 */
    function c_toViewList() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('viewlist');
    }

    /**
	 * 跳转到Tab新增页面
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
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$TypeOne = $this->getDataNameByCode ( $obj ['isDeal'] );
			$this->assign ( 'isDeal', $TypeOne );
			$this->view ( 'view' );
		} else {
			//对通用数据字典（是/否）作判断
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