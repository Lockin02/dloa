<?php
/**
 * @description: ��Ŀ��ɫ����
 * @date 2010-9-28 ����10:35:02
 */
class controller_rdproject_manage_rditemrole extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-9-28 ����10:37:07
	 */
	function __construct () {
		$this->objName = "rditemrole";
		$this->objPath = "rdproject_manage";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/
	/*
	 * ����Ŀ��ɫ������ʾ�б���
	 */
	function c_itemrolelist(){
		$service = $this->service;
		//��������
//		$service->getParam($_GET);//����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ҳ
		$auditArr = array(
			"createId" => $_SESSION['USER_ID']
		);
//		$service->__SET("searchArr",$auditArr);

		//��������
		if(!isset($_GET['itemType'])){
			$_GET['itemType'] = null;
		}
		$rows = $service->page_d( $_GET['itemType'] );

		$this->pageShowAssign();

		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->show->assign('list',$service->showitemrolelist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * @desription ͨ��<select>��������ֵ��ҳ�����ݽ��й���
	 * @param tags
	 * @date 2010-9-29 ����10:01:23
	 */
	function c_rolefilterlist () {
		$service = $this->service;
		//ר�����ڹ��˵�SQL���
		$sql = "select_filterrole";
//		$rows = $service->filterpage_d($_GET['itemType'],$sql);
		$rows = $service->filterpage_d($sql);

//		echo "<pre>";
//		print_r($rows);
		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->show->assign('list',$service->showitemrolelist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * @desription ��ת����ɫ����ҳ��
	 * @param tags
	 * @date 2010-9-28 ����02:28:05
	 */
	function c_toaddrole () {
		$service = $this->service;
		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->showDatadicts(array('ExRole'=>'XMJS'));
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/**
	 * @desription ��ɫ���Ӻ���
	 * @param tags
	 * @date 2010-9-28 ����06:39:37
	 */
	function c_addrole () {
		$roleid = $this->service->addrole_d($_POST [$this->objName],true);
//		echo "<pre>";
//		print_r($roleid);
		if($roleid){
			msg('��ӽ�ɫ�ɹ�');
		}
	}

	/**
	 * @desription ��ת����ɫȨ�޹���ҳ��
	 * @param tags
	 * @date 2010-9-28 ����02:30:46
	 */
	function c_tolimitrole () {
		$service = $this->service;
		$this->showDatadicts(array('itemRole'=>'XMJS'));
		$this->show->display($this->objPath . '_' . $this->objName . '-limit');
	}

	/**
	 * @desription ����Ȩ����ʾҳ��
	 * @param tags
	 * @date 2010-9-28 ����04:25:22
	 */
	function c_basiclimit () {
		$this->show->display($this->objPath . '_' . 'rditemrolebasic');
	}

	/**
	 * @desription �ĵ�Ȩ����ʾҳ��
	 * @param tags
	 * @date 2010-9-28 ����04:27:16
	 */
	function c_filelimit () {
		$this->show->display($this->objPath . '_' . 'rditemrolefile');
	}




	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/



}


?>