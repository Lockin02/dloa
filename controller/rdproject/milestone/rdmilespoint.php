<?php
/**
 * @description: 项目里程碑点action
 * @date 2010-9-18 上午11:31:25
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_milestone_rdmilespoint extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:51:57
	 */
	function __construct() {
		$this->objName = "rdmilespoint";
		$this->objPath = "rdproject_milestone";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/

	/*
	 * 修改里程碑点的方法
	 */
	function c_toEdit(){
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$service = $this->service;
		$rows = $service->getEditMileInfo_d($id);
		$rows2['0'] = $rows;
		$this->arrToShow($rows2);

//		$exmilestone = $this->getDataNameByCode($_GET['projectType']);
		$this->show->assign('exmilestone',$this->service->rmMilespointSelect_d( $rows['projectId'],$rows['frontCode'],$id ) );
		$this->show->display( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/*
	 * 修改里程碑点，同时保存修改信息
	 */
	function c_editpoint(){
		$rdmile = $_POST[$this->objName];
//		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		//判断变更后的前置里程碑点是否与本身的里程碑点一样，是则不能保存并返回提示信息
		$id = $this->service->editpoint_d($rdmile,true);

		if($id){
			msg('操作成功');
		}
	}

	/**
	 * @desription 变更
	 * @param tags
	 * @date 2010-10-13 下午03:52:32
	 */
	function c_rmChange () {
		$arr = isset( $_POST['rdmilespoint'] )?$_POST['rdmilespoint']:exit;
		if( $this->service->rmChange_d($arr) ){
			msg("操作成功");
		}else{
			msg("操作成功");
		}

	}



	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/

}

?>
