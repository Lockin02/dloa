<?php
/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:23
 * @version 1.0
 * @description:��Ŀ����Ԥ��������Ʋ�
 */
class controller_engineering_change_esmchangebud extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangebud";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀ����Ԥ�������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort = 'c.budgetType ,c.parentName';
		$service->asc = false;
		$rows = $service->list_d ();
		//����Ȩ��
		$rows = $this->gridDateFilter($rows);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * �б�����Ȩ�޹���
	 */
	function gridDateFilter($rows){
		//����Ԥ�㵥��Ȩ�޵� 2013-07-08
		$otherDataDao = new model_common_otherdatas();
		$esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		if(!$esmLimitArr['����Ԥ�㵥��']){
			foreach($rows as $key => $val){
				if($val['budgetType'] == 'budgetPerson' && empty($val['customPrice'])){
					$rows[$key]['price'] = '******';
				}
			}
		}
		return $rows;
	}

	/********************* ��ɾ�Ĳ� *************************/
	/**
	 * ��ת��������Ŀ����Ԥ������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀ����Ԥ������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
		$this->view('edit');
	}

	/**
	 * ��ת���鿴��Ŀ����Ԥ������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
		$this->view('view');
	}
}