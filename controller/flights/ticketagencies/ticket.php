<?php
//控制层
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
		//遍历订票业务的小数组
		$object = $this->service->ergodic ( $object );
		$id = $this->service->add_d ( $object, true );
		if ($id) {
			msg ( "添加成功" );
		} else {
			msg ( "添加失败" );
		}
	}
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//		print_r($obj);
		foreach ( $obj as $key => $val ) {
			if ($key == "bookingBusiness") {
				$arr = explode ( " , ", $val );
				foreach ( $arr as $k => $v ) {
					if ($v == "机票") {
						$this->assign ( 'isStartY0', 'checked' );
					}
					if ($v == "火车票") {
						$this->assign ( 'isStartY1', 'checked' );
					}
					if ($v == "汽车票") {
						$this->assign ( 'isStartY2', 'checked' );
					}
					if ($v == "酒店") {
						$this->assign ( 'isStartY3', 'checked' );
					}
				}
			}
			else if ($key == "agencyType"){
				if ($val=="航空"){
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
			msg ( "编辑成功" );
		} else {
			msg ( "编辑失败" );
		}
	}
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc ( $obj );
		$this->view ( 'view' );
	}
}

?>