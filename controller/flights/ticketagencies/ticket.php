<?php
//���Ʋ�
class controller_flights_ticketagencies_ticket extends controller_base_action {
	
	function __construct() {
		$this->objName = "ticket";
		$this->objPath = "flights_ticketagencies";
		parent::__construct ();
	}
	function c_list(){
		$this->view('list');
	}
	function c_toAdd() {
		$this->view ( 'add' );
	}
	function c_add() {
		$object = $_POST [$this->objName];
		//������Ʊҵ���С����
		$object = $this->service->ergodic ( $object );
		$id = $this->service->add_d ( $object, true );
		if ($id) {
			msg ( "��ӳɹ�" );
		} else {
			msg ( "���ʧ��" );
		}
	}
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//		print_r($obj);
		foreach ( $obj as $key => $val ) {
			if ($key == "bookingBusiness") {
				$arr = explode ( " , ", $val );
				foreach ( $arr as $k => $v ) {
					if ($v == "��Ʊ") {
						$this->assign ( 'isStartY0', 'checked' );
					}
					if ($v == "��Ʊ") {
						$this->assign ( 'isStartY1', 'checked' );
					}
					if ($v == "����Ʊ") {
						$this->assign ( 'isStartY2', 'checked' );
					}
					if ($v == "�Ƶ�") {
						$this->assign ( 'isStartY3', 'checked' );
					}
				}
			}
			else if ($key == "agencyType"){
				if ($val=="����"){
					$this->assign ( 'isStartY', 'checked' );
				}else{
					$this->assign ( 'isStartN', 'checked' );
				}
			}else{
				$this->assign ( $key, $val );
			}
		}
		$this->view ( 'edit' );
	}
	function c_edit() {
		$object = $_POST [$this->objName];
		$object = $this->service->ergodic ( $object );
		$id = $this->service->edit_d ( $object, true );
		if ($id) {
			msg ( "�༭�ɹ�" );
		} else {
			msg ( "�༭ʧ��" );
		}
	}
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc ( $obj );
		$this->view ( 'view' );
	}
}

?>