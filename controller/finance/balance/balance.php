<?php
/**
 * @author Show
 * @Date 2011��5��19�� ������ 19:34:41
 * @version 1.0
 * @description:�ڳ�����(Ӧ��Ӧ��)���Ʋ�
 * ������� formType 0. Ӧ�� 1. Ӧ��
 */
class controller_finance_balance_balance extends controller_base_action {

	function __construct() {
		$this->objName = "balance";
		$this->objPath = "finance_balance";
		parent::__construct ();
	}

	/*
	 * ��ת���ڳ�����(Ӧ��Ӧ��)
	 */
    function c_page() {
    	$formType = isset($_GET['formType']) ? $_GET['formType'] : 0;
    	$this->assign( 'formType',$formType );
    	$this->assign( 'formTypeCN',$this->service->rtBalanceType_d($formType) );
    	$this->assign( 'formTypeVal',$this->service->rtBalanceVal_d($formType) );
    	$this->display('list');
    }

    /**
     * ����ڳ����
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
     * �༭��ʼ��
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
     * ���ʹ���
     */
    function c_checkout(){
    	//�����ǳ�ʼ������������ʧ��
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
     * �����ʹ���
     */
    function c_uncheckout(){
    	//�����ǳ�ʼ������������ʧ��
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
     * �����㹦��
     */
    function c_balanceCount(){
    	//�����ǳ�ʼ������������ʧ��
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