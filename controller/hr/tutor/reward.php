<?php

/**
 * @author Administrator
 * @Date 2012-08-29 17:09:19
 * @version 1.0
 * @description:��ʦ����������Ʋ�
 */
class controller_hr_tutor_reward extends controller_base_action {

	function __construct() {
		$this->objName = "reward";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/**
	 * ��ת����ʦ���������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ʦ��������ҳ��
	 */
	function c_toAdd() {
		$this->assign('dept', $_SESSION['DEPT_NAME']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->view('hradd' ,true);
	}

	/**
	 * ��ת���༭��ʦ��������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit' ,true);
	}

	/**
	 * ��ת���鿴��ʦ��������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * ��ת�� ��ʦ���������ţ� �鿴��ʦ����ҳ��
	 */
	function c_toViewByDept() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->view('viewbydept');
	}

	function c_toRead() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('read');
	}

	/**
	* ��ʦ�����������б�
	*/
	function c_toDeptList() {
		$this->view('deptlist');
	}

	/**
	 * ��ʦ������HR�б�
	 */
	function c_toHrList() {
		$this->view('hrlist');
	}

	/**
	 * ��ת��ȷ�Ͻ�������ҳ��
	 */
	 function c_toGrant(){
	 	$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('grant');
	}

	/**
	 * �����Ż�ȡ��ǰ ������80�����ϵ� δ�ܵ������ĵ�ʦ
	 */
	function c_rewardInfoJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//��ȡ����

		$rows = $service->getRewardInfo_d();
		//���ݼ��밲ȫ��

		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡ��ǰ ������80�����ϵ� δ�ܵ������ĵ�ʦ(�������Ź���)
	 */
	function c_rewardInfo() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//��ȡ����

		$rows = $service->getRewardList_d();
		//���ݼ��밲ȫ��

		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->add_d($_POST[$this->objName], $isAddInfo);
		$msg = $_POST["msg"] ? $_POST["msg"] : '��ӳɹ���';
		if ($id) {
			//			msg ( $msg );
			succ_show('controller/hr/tutor/ewf_reward.php?actTo=ewfSelect&billId=' . $id);
		}

		//$this->listDataDict();
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST[$this->objName];
		$id = $this->service->edit_d($object, $isEditInfo);
		if ($id) {
			//			msg ( '�༭�ɹ���' );
			succ_show('controller/hr/tutor/ewf_reward.php?actTo=ewfSelect&billId=' . $id);
		}
	}

	/**
	 * ��ʦ��������ͨ�������ʼ�
	 */
	function c_confirmExa() {
		if (!empty ($_GET['spid'])) {
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);

			if ($folowInfo['examines'] == "ok") {
				$obj = $this->service->get_d($folowInfo['objId']);
				//���ݽ���id��ȡ�ӱ����ݷ�д����״̬
				$this->service->updateRewardState($folowInfo['objId'], "1");
				//�����ʼ�����ʦ
				//��ȡĬ�Ϸ�����
				include (WEB_TOR . "model/common/mailConfig.php");
				$addMsg = "���� ��</br>       ��ʦ����������ͨ������.����������ţ���" . $obj['code'] . "�����뼰ʱ���Ž���";
				$emailDao = new model_common_mail();
				$emailDao->mailClear('��ʦ��������', $mailUser['tutorReward']['sendUserId'], $addMsg);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ȷ�Ͻ�������
	 */
	function c_conGrant() {

		$object = $_POST[$this->objName];

		if($this->service->conGrant_d($object)){
				msg ( '�ύ�ɹ���' );
		}
	}

	/**
	 * ȷ�Ϸ������������Ϣ
	 */
	function c_publish() {
		try {
			$this->service->publish_d($_POST['id']);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}
}
?>