<?php
/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:07
 * @version 1.0
 * @description:��Ŀ��Χ�������Ʋ�
 */
class controller_engineering_change_esmchangeact extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeact";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀ��Χ������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀ��Χ�����ҳ��
	 */
	function c_toAdd() {
        //��ȡ����
        $parentId = $_GET['parentId'];
        $esmactivityObj = $this->service->getActivity_d($parentId);
        $this->assignFunc($esmactivityObj);

        //��ȡ��ǰ�ȼ���ʣ�๤��ռ��
//		$thisWorkRate = $this->service->getWorkRateByParentId_d($_GET['id'],$parentId);
//		$lastWorkRate = bcsub(100,$thisWorkRate,2);
//		$this->assign('workRate',$lastWorkRate);

		if($parentId != PARENT_ID){
	        //��������λ��ʼ��workloadUnit
	        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ), $esmactivityObj['workloadUnit']);
		}else{
	        //��Ŀ��ȡ
	        $esmprojectObj = $this->service->getProject_d($_GET['projectId']);
	        $this->assignFunc($esmprojectObj);

	        //��������λ��ʼ��workloadUnit
	        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ));
		}

		$this->view('add');
	}

    /**
     * �����������
     */
    function c_add() {
        $id = $this->service->add_d ( $_POST [$this->objName]);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        if ($id) {
            msgRf ( $msg );
        }
    }

	/**
	 * ��ת���༭��Ŀ��Χ�����ҳ��
	 */
	function c_toEdit() {
        //��ȡ����
        $esmactivityObj = $this->service->getActivity_d($_GET['activityId']);
        $this->assignFunc($esmactivityObj);

        //��������λ��ʼ��workloadUnit
        $this->showDatadicts ( array ('workloadUnit' => 'GCGZLDW' ), $esmactivityObj['workloadUnit']);

		$this->view('edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msgRf ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��ת���鿴��Ŀ��Χ�����ҳ��
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
	 * ���㹤��ռ���ܺ�
	 *
	 */
	function c_workRateCount(){
		echo $this->service->workRateCount($_GET['changeId']);
	}

	//��ȡ�����¼������е�ռ���ܺ���
	function c_workRateCountNew(){
		$result = $this->service->workRateCountNew($_GET['changeId'], $_GET['parentId'], null);
		echo util_jsonUtil::encode($result);
	}

	/**
	 * ���±�������Ԥ�ƿ�ʼ��Ԥ�ƽ���
	 */
	function c_checkIsLate(){
        echo $this->service->checkIsLate_d($_POST['changeId'],$_POST['projectId']);
	}
}