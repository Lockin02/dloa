<?php

/**
 * @author Show
 * @Date 2012��6��25�� ����һ 19:10:38
 * @version 1.0
 * @description:����������÷�̯��ϸ����Ʋ�
 */
class controller_finance_payablescost_payablescost extends controller_base_action {

	function __construct() {
		$this->objName = "payablescost";
		$this->objPath = "finance_payablescost";
		parent :: __construct();
	}

	/*
	 * ��ת������������÷�̯��ϸ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();

		if(!empty($rows)){

			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );

			//�����������
//			$rows = $service->initExaInfo_d($rows);

			//�ܼ�������
			$objArr = $service->listBySqlId('count_all');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['shareTypeName'] = '�ϼ�';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �鿴�����̯��Ϣ
	 */
	function c_listViewCost(){
		$otherId = $_POST['otherId'];//������ͬid

		$service = $this->service;
		$rows = $service->getListViewCost_d($otherId);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת����������������÷�̯��ϸ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭����������÷�̯��ϸ��ҳ��
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
	 * ��ת���鿴����������÷�̯��ϸ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus($obj['status']));
		$this->view('view');
	}

	/**
	 * ������÷�̯¼��
	 */
	function c_toShare(){
		$payapplyId = $_GET['payapplyId'];

		$rs = $this->service->find(array('payapplyId' => $payapplyId),null,'id');

		$this->assignFunc($_GET);

		if(is_array($rs)){
			//����ԭ�з��÷�̯����
			$this->service->searchArr = array('payapplyId' => $payapplyId );
			$this->service->asc = false;
			$rows = $this->service->list_d();

			$dataStr = $this->service->initShareEdit_v($rows);
			$this->assign('detail',$dataStr);
			$this->assign('detailNo',count($rows));
//			echo "<pre>";
//			print_r($rows);

			$this->view('share-edit');
		}else{
			$this->view('share-add');
		}
	}

	/**
	 * ������÷�̯
	 */
	function c_share(){
		$object = $_POST[$this->objName];
		$rs = $this->service->share_d($object);
		if($rs){
			msg('��̯�ɹ�');
		}else{
			msg('��̯ʧ��');
		}
	}

	/*************************** ���뵼������ *************************/
	/**
	 * ���÷�̯����
	 */
	function c_toExcelIn(){
		$this->display('toexcel');
	}

	/**
	 * ���÷�̯����
	 */
	function c_excelIn(){
		$resultArr = $this->service->excelIn_d ($_POST['checkType']);
		$title = '���÷�̯�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ���÷�̯-��������
	 */
	function c_expense(){
		$this->assign('costTypeId',$_GET['costTypeId']);
		$this->view('expense');
	}
	/**
	 *
	 *��ѯ��̯������ϸ��������ʱ������Ϣ��
	 */
   function c_listCost(){
		$rows=$this->service->listCost($_REQUEST['payapplyId'],$_SESSION['USER_ID']);
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>