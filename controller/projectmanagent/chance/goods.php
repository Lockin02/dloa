<?php
/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:�̻���Ʒ�嵥���Ʋ�
 */
class controller_projectmanagent_chance_goods extends controller_base_action {

	function __construct() {
		$this->objName = "goods";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * ��ת���̻��򵥲�Ʒ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������̻��򵥲�Ʒ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�̻��򵥲�Ʒ��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�̻��򵥲�Ʒ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

   /**
    * ��ʱ������Ϣ�б����ӱ���ӱ�����
    */
   	function c_timingPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->asc = true;
		$service->searchArr['timingDate'] = $_GET['timingDate'];
		$rows = $service->pageBySqlId("select_timing");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
 }
?>