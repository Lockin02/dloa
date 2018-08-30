<?php
/**
 * @author Show
 * @Date 2013年7月1日 星期五 13:50:47
 * @version 1.0
 */
class controller_flights_balance_bill extends controller_base_action {
	function __construct() {
		$this->objName = "bill";
		$this->objPath = "flights_balance";
		parent::__construct ();
	}

	/**
	 * 新增
	 */
	function c_toAdd(){
		$this->assignFunc($_GET);
		$this->showDatadicts(array('billType' => 'FPLX'));
		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 初始化对象
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->getBillInfo_d ( $_GET ['mainId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('billType' => 'FPLX'),$obj['billType']);
        //附件添加{file}
        $this->assign('file',$this->service->getFilesByObjId ( $obj['id'], true,'oa_flights_balance_bill' )) ;

		$this->view ( 'edit' );
	}
}
?>