<?php
/**
 * @author Show
 * @Date 2011年5月19日 星期四 19:34:41
 * @version 1.0
 * @description:期初余额表(应付应收)控制层
 * 余额类型 formType 0. 应收 1. 应付
 */
class controller_finance_balance_balance extends controller_base_action {

	function __construct() {
		$this->objName = "balance";
		$this->objPath = "finance_balance";
		parent::__construct ();
	}

	/*
	 * 跳转到期初余额表(应付应收)
	 */
    function c_page() {
    	$formType = isset($_GET['formType']) ? $_GET['formType'] : 0;
    	$this->assign( 'formType',$formType );
    	$this->assign( 'formTypeCN',$this->service->rtBalanceType_d($formType) );
    	$this->assign( 'formTypeVal',$this->service->rtBalanceVal_d($formType) );
    	$this->display('list');
    }

    /**
     * 添加期初余额
     */
    function c_toAdd(){
    	$formType = isset($_GET['formType']) ? $_GET['formType'] : 0;

    	$this->assign( 'thisDate' ,day_date );
    	$this->assign( 'thisMonth' , date('n') );
    	$this->assign( 'thisYear' ,date('Y') );

    	$this->assign( 'formType',$formType );
    	$this->assign( 'formTypeCN',$this->service->rtBalanceType_d($formType) );

    	$thisCode = $this->service->rtBalanceCode_d($formType);
    	$this->display( $thisCode. '-add' );
    }

    /**
     * 编辑初始化
     */
    function c_init() {
    	$perm = isset( $_GET ['perm']) ?  $_GET ['perm'] : 'edit';
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);

		$thisCode = $this->service->rtBalanceCode_d($obj['formType']);
    	$this->assign( 'formTypeCN',$this->service->rtBalanceType_d($obj['formType']) );

		if ($perm == 'view') {
			$this->display ( $thisCode.'-view' );
		} else {
			$this->display ( $thisCode.'-edit' );
		}
	}

	/**
     * 结帐功能
     */
    function c_checkout(){
    	//若果是初始条件，反结算失败
    	if($this->service->isLastPeriod_d($_POST['formType'])) { echo 2 ; exit();}
		$rs = $this->service->checkout_d($_POST['formType']);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
    }

    /**
     * 反结帐功能
     */
    function c_uncheckout(){
    	//若果是初始条件，反结算失败
    	if($this->service->isFirstPeriod_d($_POST['formType'])) { echo 2 ; exit();}
		$rs = $this->service->uncheckout_d($_POST['formType']);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
    }

    /**
     * 余额核算功能
     */
    function c_balanceCount(){
    	//若果是初始条件，反结算失败
		$rs = $this->service->balanceCount_d($_POST['formType']);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
    }
}
?>