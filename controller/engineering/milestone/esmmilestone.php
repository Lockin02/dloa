<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 13:45:07
 * @version 1.0
 * @description:项目里程碑(oa_esm_project_milestone)控制层
 */
class controller_engineering_milestone_esmmilestone extends controller_base_action {

	function __construct() {
		$this->objName = "esmmilestone";
		$this->objPath = "engineering_milestone";
		parent::__construct ();
	}

	/*
	 * 跳转到项目里程碑(oa_esm_project_milestone)
	 */
    function c_page() {
       $this->display('list');
    }

	/**
	 * 跳转到编辑Tab里程碑
	 */
    function c_tabEsmmilestone() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }

    /**
     * 跳转到查看Tab里程碑
     */
     function c_toViewMilestone(){
		$this->assign('projectId',$_GET['projectId']);
       	$this->view('viewlist');
     }

    /**
	 * 跳转到Tab新增页面
	 */
	function c_toAdd() {
        $projectId = $_GET['projectId'];
        //获取项目内容
		$rs = $this->service->getObjInfo_d($projectId);
        $this->assignFunc($rs);

        //获取最后的一个里程碑点
        $milestoneArr = $this->service->find(array('projectId'=> $projectId),'planEndDate desc','planEndDate');
        if(is_array($milestoneArr)){
            $this->assign('planDate',$milestoneArr['planEndDate']);
        }else{
            $this->assign('planDate','');
        }

		$this->assign('projectId',$projectId);
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
			$this->assign ( 'status', $this->getDataNameByCode ( $obj ['status'] ) );
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}
}
?>