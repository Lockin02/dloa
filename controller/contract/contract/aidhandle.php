<?php
/**
 * @author Administrator
 * @Date 2012-06-29 10:15:12
 * @version 1.0
 * @description:�������������¼���Ʋ�
 */
class controller_contract_contract_aidhandle extends controller_base_action {

	function __construct() {
		$this->objName = "aidhandle";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * ��ת���������������¼�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������������������¼ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�������������¼ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�������������¼ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

   /**
    * ���������ܰ�ťѡ���ͬ�б�
    */
   function c_chooseContract(){
   	  $this->assign("handleType",$_GET['handleType']);
   	  $this->view("chooseContract");
   }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->pageBySqlId('select_gridinfo');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>