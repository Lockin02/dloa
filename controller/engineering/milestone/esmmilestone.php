<?php
/**
 * @author Show
 * @Date 2011��12��10�� ������ 13:45:07
 * @version 1.0
 * @description:��Ŀ��̱�(oa_esm_project_milestone)���Ʋ�
 */
class controller_engineering_milestone_esmmilestone extends controller_base_action {

	function __construct() {
		$this->objName = "esmmilestone";
		$this->objPath = "engineering_milestone";
		parent::__construct ();
	}

	/*
	 * ��ת����Ŀ��̱�(oa_esm_project_milestone)
	 */
    function c_page() {
       $this->display('list');
    }

	/**
	 * ��ת���༭Tab��̱�
	 */
    function c_tabEsmmilestone() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }

    /**
     * ��ת���鿴Tab��̱�
     */
     function c_toViewMilestone(){
		$this->assign('projectId',$_GET['projectId']);
       	$this->view('viewlist');
     }

    /**
	 * ��ת��Tab����ҳ��
	 */
	function c_toAdd() {
        $projectId = $_GET['projectId'];
        //��ȡ��Ŀ����
		$rs = $this->service->getObjInfo_d($projectId);
        $this->assignFunc($rs);

        //��ȡ����һ����̱���
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
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
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