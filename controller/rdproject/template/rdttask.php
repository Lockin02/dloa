<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_template_rdttask extends controller_base_action{
	function __construct() {
		$this->objName = "rdttask";
		$this->objPath = "rdproject_template";
		parent::__construct ();
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->assign( 'templateName',$_GET['templateName'] );
		$this->assign( 'templateId',$_GET['templateId'] );
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ));
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ));
		$this->display ( 'add' );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		if(isset($_GET ['perm']) && $_GET ['perm'] == 'view'){
			$rows['taskType'] = $this->getDataNameByCode($rows['taskType']);
			$rows['priority'] = $this->getDataNameByCode($rows['priority']);
		}
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		if (isset($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) ,$rows['taskType']);
			$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ),$rows['priority'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * 确认删除
	 */
	function c_toDelete(){
		showmsg('确定删除？',"location='?model=rdproject_template_rdttask&action=deleteAction&id=".$_GET['id']."'",'button');
	}
//
//	/**
//	 * 删除操作
//	 */
//	function c_deleteAction(){
//		if($this->service->deletes($_GET['id'])){
//			msg('删除成功');
//		}else{
//			msg('删除失败');
//		}
//	}


}
?>
