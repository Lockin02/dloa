<?php
/**
 * @author Show
 * @Date 2012��2��10�� ������ 14:05:49
 * @version 1.0
 * @description:��Ŀ��̱��������Ʋ�
 */
class controller_engineering_changemilestone_changemilestone extends controller_base_action {

	function __construct() {
		$this->objName = "changemilestone";
		$this->objPath = "engineering_changemilestone";
		parent::__construct ();
	}

	/**
	 * ����б�ѡ��ҳ
	 */
	function c_toChange(){
		//��ȡ�������Ŀid
		$projectId = $_GET['projectId'];

		$this->assignFunc($_GET);
		//�ж���Ŀ�Ƿ���ڱ�������ұ�������Ƿ�����̱�
		$isChangeMileStone = $this->service->isChangeMilestone_d($projectId);

		//���Ҫ�����������б�
		if($isChangeMileStone == 1){
			$this->view('list');
		}else{
			$this->view('needadd');
		}
	}

	/*
	 * ��ת����Ŀ��̱�������б�
	 */
    function c_page() {
    	$this->assign('changeId',$_GET['changeId']);
		$this->view('list');
    }

    /**
     * ��̱�����鿴�б�
     */
    function c_viewPage(){
    	$this->assign('changeId',$_GET['changeId']);
		$this->view('listview');
    }

    /**
	 * ��ת��������Ŀ��̱������ҳ��
	 */
	function c_toAdd() {
		$rs = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($rs);
		$this->assign('projectId',$_GET['projectId']);
		$this->assign('changeId',$_GET['changeId']);
		$this->assign('versionNo',$_GET['versionNo']);
		$this->showDatadicts ( array ('status' => 'LCBZT' ) );

		$this->view ( 'add' );
	}

    /**
	 * ��ת���༭��Ŀ��̱������ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('status' => 'LCBZT' ) ,$obj['status']);
		$this->view ( 'edit');
	}

    /**
	 * ��ת���鿴��Ŀ��̱������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );

		//ȥ��ʵ�ʿ�ʼ���ڿյ�����
		if($obj['actBeginDate'] == '0000-00-00'){
			$obj['actBeginDate'] = "";
		}

		//ȥ��ʵ�ʽ������ڿյ�����
		if($obj['actEndDate'] == '0000-00-00'){
			$obj['actEndDate'] = "";
		}
		$this->assignFunc($obj);

		$this->assign('status',$this->getDataNameByCode($obj['status']));
		$this->view ( 'view' );
	}

	/**
	 * �첽������̱� - ����Ŀ��ֱ�Ӹ���
	 */
	function c_addMilestone(){
		$projectId = $_POST['projectId'];
		$changeId = $_POST['changeId'];
		$versionNo = $_POST['versionNo'];
		//��Ŀ��Ϣ��Ⱦ��
		$rs = $this->service->addMilestone_d($projectId,$changeId,$versionNo);
		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
	}

	/**
	 * �첽������̱� - ����Ŀ��ֱ�Ӹ���
	 */
	function c_addMileAndChange(){
		$projectId = $_POST['projectId'];
		//��Ŀ��Ϣ��Ⱦ��
		$rs = $this->service->addMileAndChange_d($projectId);
		if($rs){
			echo util_jsonUtil::encode ( $rs );
		}else{
			echo 0;
		}
	}
}
?>