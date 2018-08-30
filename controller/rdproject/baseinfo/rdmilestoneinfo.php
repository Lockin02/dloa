<?php
	/**
	 * @description: ��Ŀ��̱�-�����Ϣ
	 * @date 2010-9-26 ����03:05:22
	 */
class controller_rdproject_baseinfo_rdmilestoneinfo extends controller_base_action{
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-9-26 ����03:06:50
	 */
	function __construct () {
		$this->objName = "rdmilestoneinfo";
		$this->objPath = "rdproject_baseinfo";
		parent::__construct();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription Ĭ����תҳ��
	 * @param tags
	 * @date 2010-9-26 ����09:35:49
	 */
	function c_projectlist () {
		$this->display('main-list');
	}

	/*
	 * ��ʾ�󵼺���
	 */
	function c_menulist(){
		$this->display('menu-list');
	}


	/*
	 * �����̱������Ϣ
	 */
	function c_toaddmilestone(){

		//��ȡ���ҳ���С�ǰ����̱�������Ҫ��ҳ������
		$service = $this -> service;
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
//		$gettablemilestone = $_GET['projectType'];
//		$getexmilestone = $service->pageExMile_d($parentId);
		//��ʾǰ����̱��������б�
//		$exMilestoneName = $service->showExMilestone($getexmilestone);

		//ͨ�������ȡǰ����̱�������
//		$getexMileName = $service->getexMileName($gettablemilestone);
//		$this->assign('ex',$getexMileName);

		$this->showDatadicts(array('projectType' => 'YFXMGL'));
//		$this->assign('exMilestoneName',$exMilestoneName);
		//����Ψһ����
		$this->assign('numb',$this->businessCode());
		$this->assign('parentId',$parentId);

		$this->display('add');

	}

	/*
	 * ������̱�
	 */
	function c_addmilestone(){
		$milObj = $_POST[$this->objName];
		$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$id = $this->service->addmilestone_d($milObj,true);
		if($id){
			msg('�����̱��ɹ�');
		}
	}


	/*
	 * ��ʾĬ����ҳ�档
	 * Ĭ��Ϊ����Ŀ��̱���Tab��ǩ�µ���ʾ�б�ҳ��
	 */
	function c_milestonelist(){
		$service = $this->service;
		//��ҳ
		$auditArr = array(
			"createId" => $_SESSION['USER_ID'],
		);

		if(!isset($_GET['projectType'])){
			$_GET['projectType'] = null;
		}
		$rows = $service->page_d( $_GET['projectType'] );
		$this->pageShowAssign();
		//���������ֵ���������ʾ��Ŀ���͵�������
		$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->assign('list',$service->showprojectlist($rows));
		$this->display('list');
	}


	/**
	 * @desription ���б����ݽ��й���
	 * @param tags
	 * @date 2010-9-29 ����06:33:29
	 */
	function c_milestonefilterlist () {
		//���´����Ƕ�Ӧ������-��̱��㡱��2010��10��26��ע�͡�
		$service = $this->service;
		$projectType = $_GET['projectType'];
		$this->assign('projectTypeVal',$projectType);
		$service->searchArr=array("projectType"=>$projectType);
		$rows = $service->page_d();
		$this->showdatadicts(array('projectType' => 'YFXMGL'));
		$this->pageShowAssign();

		$this->assign('list',$service->showprojectlist($rows));
		$this->display('list');
	}

	/**
	 * @desription �����ṩһ���ӿڣ�����һ��������̱�����Ϣ����
	 * @param tags
	 * @return return_type
	 * @date 2010-10-3 ����04:55:21
	 */
	function c_returnMilestoneInfo(){
		$service = $this->service;
		//����Ĳ�����"��Ŀ����"<option>������ֵ,IDΪ"projectType"
		$projectType = $_GET['projectType'];

		//��������Ҫ���������ݣ�����Ĳ�����ͨ��ѡ�С���Ŀ���͡�������Ӧ������ֵ
		$service->returnMilestoneInfo_d($projectType);

	}

	/*
	 * ��ת���޸���̱�ҳ��
	 */
	function c_toEdit(){
		$service = $this->service;
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$rows = $service->getEditMilestoneInfo_d($id);
		$frontRows=$service->findAll(array('numb'=>$rows['frontNumb']));
		$rows2['0'] = $rows;
		$this->arrToShow($rows2);

		$exMilestoneName = $service->milestoneSelect_d( $id );
		$this->assign('frontName',$frontRows[0][milestoneName]);
		$this->assign('exMilestoneName',$exMilestoneName );
		$this->assign('milestoneDescription',$rows['milestoneDescription']);
		$this->display('edit');
	}

	/*
	 * �޸���̱�
	 */
	function c_editMilestone(){
		$rdmile = $_POST[$this->objName];

		$id = $this->service->editMilestone_d($rdmile,true);
		if($id){
			msg('�༭��̱��ɹ�');
		}
	}

	/**ɾ����̱�
	*author can
	*2011-4-8
	*/
	function c_deleteMilestone(){
		$id=isset($_GET['id'])?$_GET['id']:null;
		$flag=$this->service->deletes($id);
//		if($flag){
//			msgGo('ɾ���ɹ���',"?model=rdproject_baseinfo_rdmilestoneinfo&action=milestonelist");
//		}
	}

	/**ɾ����̱�
	*author can
	*2011-4-8
	*/
	function c_deleteMilestone1(){
		$id=isset($_GET['id'])?$_GET['id']:null;
		$flag=$this->service->deletes($id);
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	*************************************************************************************************/
	/**������Ŀ��ȡǰ����̱�
	*author can
	*2011-4-8
	*/
	function c_getFrontMilestone(){
		$projectType=isset($_POST['projectType'])?$_POST['projectType']:null;

		//ͨ�������ȡǰ����̱�������
		$getexMileName = $this->service->getMilestoneByProjectType_d($projectType);
		$exMilestoneName = $this->service->showExMilestoneList($getexMileName);
		echo $exMilestoneName;


	}
}
?>
