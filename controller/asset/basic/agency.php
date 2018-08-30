<?php
/**
 * @author Administrator
 * @Date 2012��7��16�� 15:00:09
 * @version 1.0
 * @description:�����������Ʋ� 
 */
class controller_asset_basic_agency extends controller_base_action {

	function __construct() {
		$this->objName = "agency";
		$this->objPath = "asset_basic";
		parent::__construct ();
	 }
    
	/**
	 * ��ת������������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
    /**
	 * ��ת���������������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
    /**
	 * ��ת���༭���������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
    /**
	 * ��ת���鿴���������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         
		$this->view ( 'view' );
   }
   
   /**
    * grid�б���������
    */
   function c_getSelection(){
   	$rows = $this->service->list_d();
   	$datas = array();
   	foreach( $rows as $key=>$val ){
   		$datas[$key]['text']=$val['agencyName'];
   		$datas[$key]['value']=$val['agencyCode'];
   	}
   	echo util_jsonUtil::encode ( $datas );
   }
 }