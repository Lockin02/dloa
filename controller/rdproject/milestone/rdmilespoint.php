<?php
/**
 * @description: ��Ŀ��̱���action
 * @date 2010-9-18 ����11:31:25
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_milestone_rdmilespoint extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:51:57
	 */
	function __construct() {
		$this->objName = "rdmilespoint";
		$this->objPath = "rdproject_milestone";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/

	/*
	 * �޸���̱���ķ���
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
	 * �޸���̱��㣬ͬʱ�����޸���Ϣ
	 */
	function c_editpoint(){
		$rdmile = $_POST[$this->objName];
//		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		//�жϱ�����ǰ����̱����Ƿ��뱾�����̱���һ���������ܱ��沢������ʾ��Ϣ
		$id = $this->service->editpoint_d($rdmile,true);

		if($id){
			msg('�����ɹ�');
		}
	}

	/**
	 * @desription ���
	 * @param tags
	 * @date 2010-10-13 ����03:52:32
	 */
	function c_rmChange () {
		$arr = isset( $_POST['rdmilespoint'] )?$_POST['rdmilespoint']:exit;
		if( $this->service->rmChange_d($arr) ){
			msg("�����ɹ�");
		}else{
			msg("�����ɹ�");
		}

	}



	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/

}

?>
