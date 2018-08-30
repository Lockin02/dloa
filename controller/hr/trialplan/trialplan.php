<?php

/**
 * @author Show
 * @Date 2012��8��31�� ������ 14:53:01
 * @version 1.0
 * @description:Ա��������ѵ�ƻ����Ʋ�
 */
class controller_hr_trialplan_trialplan extends controller_base_action {

	function __construct() {
		$this->objName = "trialplan";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * ��ת��Ա��������ѵ�ƻ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Ա��������ѵ�ƻ�ҳ��
	 */
	function c_toAdd() {
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
	 * ģ��ѡ��ҳ
	 */
	function c_toSelectModel(){
		$this->assignFunc($_GET);
		$this->view('selectmodel');
	}

	/**
	 * ��ת���༭Ա��������ѵ�ƻ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴Ա��������ѵ�ƻ�ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /**
     * ��ȡ�ҵ������ƻ�
     */
    function c_getMyPlans(){
        $userId = isset($_POST['userAccount']) ? $_POST['userAccount'] : $_SESSION['USER_ID'];
        $this->service->searchArr = array(
            'memberId' => $userId
        );
        $this->sort = "c.createTime";
        $rs = $this->service->list_d();
        if($rs){
            foreach($rs as $key => $val){
                $newArr[$key]['text'] = $val['planName'];
                $newArr[$key]['value'] = $val['id'];
            }
            echo util_jsonUtil::encode ( $newArr);
        }
    }
}
?>