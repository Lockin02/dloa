<?php
/**
 * @author yxin1
 * @Date 2014��8��26�� 14:07:59
 * @version 1.0
 * @description:���������������Ͽ��Ʋ� 
 */
class controller_produce_task_configproduct extends controller_base_action {

	function __construct() {
		$this->objName = "configproduct";
		$this->objPath = "produce_task";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������������������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
    /**
	 * ��ת����������������������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
    /**
	 * ��ת���༭����������������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
      $this->view ( 'edit');
   }
   
    /**
	 * ��ת���鿴����������������ҳ��
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
    * ��ȡ�������ݷ���json
    */
   function c_listJsonStatistics() {
	   	$service = $this->service;
	   	if(empty($_REQUEST['taskIds'])){
	   		unset($_REQUEST['taskIds']);
	   	}
	   	$service->getParam ( $_REQUEST );
	   	$rows = $service->list_d ('select_statistics');
	   	//���ݼ��밲ȫ��
	   	$rows = $this->sconfig->md5Rows ( $rows );
	   	echo util_jsonUtil::encode ( $rows );
   }
 }