<?php
/*
 * Created on 2010-8-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_contract_common_bcinfo extends controller_base_action{

	function __construct() {
		$this->objName = "bcinfo";
		$this->objPath = "contract_common";
		parent::__construct ();
	}

	/**
	 * 关闭合同
	 */
	function c_toClose(){
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('formalNo',$_GET['formalNo']);
		$this->show->assign('customerContNum',$_GET['customerContNum']);
		$this->show->assign('closeName',$_SESSION['USERNAME']);
		$this->show->assign('closeId',$_SESSION['USER_ID']);
		$this->show->assign('closeTime',day_date);
		$this->showDatadicts( array ( 'closeType' => 'GBYY' ) );
		$this->show->display($this->objPath . '_' .$this->objName . '-close' );
	}

	/**
	 * 关闭信息
	 */
	function c_initCloseInfo(){
		$rows = $this->service->getInfo($_GET['id'],'1');
		if($rows){
			foreach($rows as $key => $val){
				if($key == 'doType'){
					$val = $this->getDataNameByCode($val);
				}
				$this->assign($key,$val);
			}
			$this->display('read');
		}else{
			$this->display('detail-none');
		}
	}

	/**
	 *  合同关闭
	 */
	function c_closeAdd() {
		$id = $this->service->closeAdd ( $_POST [$this->objName]);
		if ($id) {
			msg ( '关闭成功！', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction' );
		} else {
			showmsg ( '关闭失败！', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction' );
		}
	}
}
?>
