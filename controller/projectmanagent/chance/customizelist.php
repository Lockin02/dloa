<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 14:32:55
 * @version 1.0
 * @description:�����Զ����嵥���Ʋ� ��Ʒ�嵥

 */
class controller_projectmanagent_chance_customizelist extends controller_base_action {

	function __construct() {
		$this->objName = "customizelist";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * ��ת�������Զ����嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



	/**
	 * ��ʼ������
	 */
	function c_init() {
		//$returnObj = $this->objName;
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

//	//��ת ��ʱ���ϴ���ҳ��
//    function c_handle(){
//        $obj = $this->service->get_d ( $_GET ['id'] );
//		foreach ( $obj as $key => $val ) {
//			$this->assign ( $key, $val );
//		}
//    	$this->display("handle");
//    }
 }
?>