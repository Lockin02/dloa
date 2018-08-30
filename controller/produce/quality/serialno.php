<?php
/**
 *
 * �ʼ��������к�̨��
 * @author chenrf
 *
 */
class controller_produce_quality_serialno extends controller_base_action {

	function __construct() {
		$this->objName = "serialno";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ���кŴ���
	 */
	function c_toDeal(){
		$this->assignFunc($_GET);

		//��ȡ�������к�
		$rs = $this->service->getSequence_d($_GET['relDocId'],$_GET['relDocType']);
		$this->assignFunc($rs);

		$this->view('deal');
	}

	/**
	 * ����¼��
	 */
	function c_deal(){
		if($this->service->deal_d($_POST['serialno'])){
			msg("�����ɹ�");
		}
	}

	/**
	 * ���кŲ�ѯ
	 */
	function c_toDealView(){
		$this->assignFunc($_GET);

		//��ȡ�������к�
		$rs = $this->service->getSequence_d($_GET['relDocId'],$_GET['relDocType']);
		$this->assignFunc($rs);

		$this->view('dealview');
	}

	/**
	 *
	 * ���ʼ챨������
	 */
	function c_add(){
		if($this->service->add_d($_POST['serialno'])){
			msg("�����ɹ�");
		}
	}

	/**
	 * ��ȡĳһ�������к�����
	 * Enter description here ...
	 */
	function c_ajaxCount(){
		$relDocId=$_GET['relDocId'];
		$relDocType=$_GET['relDocType'];
		if($relDocId)
			echo $this->service->getCount($relDocId,$relDocType);
		else
			echo 'error';
	}
	/**
	 * ��ת��excel����ҳ��
	 */
	function c_toImportSerialno(){
		$this->assignFunc($_GET);
		$this->view('import');
	}

	function c_importExcel(){
		$title = '���кŵ������б�';
		$thead = array( '������Ϣ','������' );
		$resultArr=$this->service->importExcel($_POST['serialno']);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}