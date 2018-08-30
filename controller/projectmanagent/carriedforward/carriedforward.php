<?php
/**
 * @author Show
 * @Date 2011��7��30�� 15:23:12
 * @version 1.0
 * @description:��ͬ��ת����Ʋ�
 */
class controller_projectmanagent_carriedforward_carriedforward extends controller_base_action {

	function __construct() {
		$this->objName = "carriedforward";
		$this->objPath = "projectmanagent_carriedforward";
		parent::__construct ();
	}

	/*
	 * ��ת����ͬ��ת��
	 */
    function c_page() {
       $this->display('list');
    }

    /**
     * ѡ���ת����
     */
    function c_toCarried(){
        $this->assign( 'thisYear' , date('Y') );
        $this->assign( 'thisMonth' , date('m')*1 );
        $this->display('tocarried');
    }

    /**
     * ѡ���ת����
     */
    function c_carriedDetail(){
        $this->assign( 'thisYear',$_GET['thisYear'] );
        $this->assign( 'thisMonth',$_GET['thisMonth'] );
        $this->display('carrieddetail');
    }

    /**
     * ��дadd
     */
    function c_toAdd(){
        $this->assign( 'thisDate' ,day_date );
        $this->showDatadicts ( array ('saleType' => 'KPRK' ));
    	$this->view('add');
    }

    /**
     * �Ƿ��ѽ�ת
     */
    function c_isCarried(){
    	if($this->service->isCarried_d($_POST['outStockId'],$_POST['id'])){
            echo 1;
    	}else{
    		echo 0;
    	}
    }

    /**
     * ����init
     */
    function c_init() {
        $obj = $this->service->get_d ( $_GET ['id'] );
        $this->assignFunc($obj);
        if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
            $this->assign( 'saleType', $this->getDataNameByCode($obj['saleType']));
            $this->display ( 'view' );
        } else {
            $this->showDatadicts ( array ('saleType' => 'KPRK' ),$obj['saleType']);
            $this->view ( 'edit' );
        }
    }
}
?>