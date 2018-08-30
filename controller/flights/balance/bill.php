<?php
/**
 * @author Show
 * @Date 2013��7��1�� ������ 13:50:47
 * @version 1.0
 */
class controller_flights_balance_bill extends controller_base_action {
	function __construct() {
		$this->objName = "bill";
		$this->objPath = "flights_balance";
		parent::__construct ();
	}

	/**
	 * ����
	 */
	function c_toAdd(){
		$this->assignFunc($_GET);
		$this->showDatadicts(array('billType' => 'FPLX'));
		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->getBillInfo_d ( $_GET ['mainId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('billType' => 'FPLX'),$obj['billType']);
        //�������{file}
        $this->assign('file',$this->service->getFilesByObjId ( $obj['id'], true,'oa_flights_balance_bill' )) ;

		$this->view ( 'edit' );
	}
}
?>