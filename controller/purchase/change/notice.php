<?php
/**
 * @description: �ɹ����֪ͨ
 * @date 2010-12-24 ����10:00:20
 * @author chengl
 * @version V1.0
 */
class controller_purchase_change_notice extends controller_base_action {

	function __construct() {
		$this->objName = 'notice';
		$this->objPath = 'purchase_change';
		parent::__construct ();
	}

	/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ��ת�����֪ͨ�б�
	 */
	function c_toChangeList() {
		$this->display ( "list" );
	}

	/**
	 * ���ձ��֪ͨ
	 */
	function c_receive() {
		$this->service->receive_d ( $_GET ['id'] );
		echo 1;
	}

	/*
	 * @desription ��ת��������֪ͨ�б�
	 * @author qian
	 * @date 2011-1-12 ����11:07:33
	 */
	function c_toChangeTaskList () {
		$this->display("list-task");
	}

	/**
	 * ��ת���ҵĲɹ���ͬ���֪ͨ�б�
	 * @return return_type
	 */
	function c_myChangeList () {
		$this->display("list-mycontract");
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$returnObj = $this->objName;
		$$returnObj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $$returnObj as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		//		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			//TODO:��ȡȫ������������
			$this->service->getChildArr_d( $_GET['id'] );
			$this->display (  'view' );
		} else {
			$this->display ( 'edit' );
		}
	}


	/*****************************************Ajax��JSON����*****************************************/
	/**
	 * �ҵı��--���˵�½�û�
	 * �˷���һ��������ʾ�����ˡ��ļƻ������񡢺�ͬ����б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_myChangeJSON () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['updateId'] = $_SESSION['USER_ID'];
//		$rows = $service->page_d ();
		$rows = $service->pageBySqlId("list");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @descritpion ���˳��ɹ��ƻ�
	 * @author qian
	 * @date 2011-2-25-10:20
	 */
	function c_pageJsonPlan(){
		$service = $this->service;
		$service->getParam($_POST);	//����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$service->searchArr['modelCode'] = "plan";
		$rows = $service->pageBySqlId("list");
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode( $arr );
	}


	/**
	 * @description ���˳�������ƻ��ı�����˷���������ı���б���ˡ�
	 * @author qian
	 * @date 2011-2-25 10:04
	 */
	function c_pageJsonTask(){
		$service = $this->service;
		$service->getParam($_POST);	//����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$service->searchArr['modelCode'] = "task";
		$rows = $service->pageBySqlId("list");
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode( $arr );
	}


	/*****************************************Ajax��JSON����*****************************************/

}
?>
