<?php
/**
 * @author show
 * @Date 2013��12��27�� 11:18:01
 * @version 1.0
 * @description:��������Ʋ�
 */
class controller_engineering_officeinfo_manager extends controller_base_action {

	function __construct() {
		$this->objName = "manager";
		$this->objPath = "engineering_officeinfo";
		parent :: __construct();
	}

	/**
	 * ��ת���������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('productLine' => 'GCSCX' ));
		$this->assign('formBelong',$_SESSION['USER_COM']);
		$this->assign('formBelongName',$_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong',$_SESSION['USER_COM']);
		$this->assign('businessBelongName',$_SESSION['USER_COM_NAME']);
		$this->view('add');
	}

	/**
	 * ��ת���༭������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('oldProductLine',$obj['productLine']);
		$this->showDatadicts ( array ('productLine' => 'GCSCX' ),$obj['productLine']);
		$this->view('edit');
	}

	/**
	 * ��ת���鿴������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$actType = isset($_GET['actType']) ? $_GET['actType'] : '';
		$this->assign('actType', $actType );
		$this->view('view');
	}

	//����ʡ�ݺ͹�˾��ȡ������
	function c_getManager(){
		$rtArr = array(
			'managerId' => '',
			'managerName' => ''
		);
		if(!empty($_POST['provinceIdArr'])){
			$service = $this->service;
			$service->getParam ( $_POST );
			$service->asc = false;
			$service->sort = 'c.province';
			$rows = $service->list_d();
			if($rows){
				$managerIdArr = array();
				$managerNameArr = array();
				foreach($rows as $v){
					if(!empty($v['managerId'])){
						array_push($managerIdArr,$v['managerId']);
						array_push($managerNameArr,$v['managerName']);
					}
				}
				$rtArr['managerId'] = implode(',',$managerIdArr);
				$rtArr['managerName'] = implode(',',$managerNameArr);
			}
		}
		echo util_jsonUtil::encode ( $rtArr );
	}

	/**
	 * �������Ƿ��ظ�
	 */
	function c_checkRepeat() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$rows = $service->list_d();
		if($rows){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
}