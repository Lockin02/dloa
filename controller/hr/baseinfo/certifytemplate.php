<?php

/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:19:03
 * @version 1.0
 * @description:��ְ�ʸ�ȼ���֤���۱�ģ����Ʋ�
 */
class controller_hr_baseinfo_certifytemplate extends controller_base_action {

	function __construct() {
		$this->objName = "certifytemplate";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/******************* �б���Ϣ **********************/

	/**
	 * ��ת����ְ�ʸ�ȼ���֤���۱�ģ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/******************** ��ɾ�Ĳ� **********************/

	/**
	 * ��ת��������ְ�ʸ�ȼ���֤���۱�ģ��ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'));//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'));//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'));//���뼶��
		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msgRf ( $msg );
		}
	}

	/**
	 * ��ת���༭��ְ�ʸ�ȼ���֤���۱�ģ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection']);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel']);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade']);//���뼶��

		$this->view('edit');
	}

	/**
	 * ��ת���鿴��ְ�ʸ�ȼ���֤���۱�ģ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_d($obj['status']));

		$this->view('view');
	}

	/******************** ҵ���߼� ***********************/
	/**
	 * �ж��Ƿ��Ѵ�������һ�������õ�ģ��
	 */
	function c_isAnotherTemplate(){
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$careerDirection = isset($_POST['careerDirection']) ? $_POST['careerDirection'] : null;
		$baseLevel = isset($_POST['baseLevel']) ? $_POST['baseLevel'] : null;
		$baseGrade = isset($_POST['baseGrade']) ? $_POST['baseGrade'] : null;

		$rs = $this->service->isAnotherTemplate_d($careerDirection,$baseLevel,$baseGrade,$id);

		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * �ر�ģ��
	 */
	function c_close(){
		$id = $_POST['id'];
		if($this->service->close_d($id)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
}
?>