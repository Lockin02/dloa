<?php
/**
 * @author Administrator
 * @Date 2013��10��9�� 9:41:20
 * @version 1.0
 * @description:������������Ʋ�
 */
class controller_report_report_produce extends controller_base_action {

	function __construct() {
		$this->objName = "produce";
		$this->objPath = "report_report";
		parent::__construct ();
	 }

	/**
	 * ��ת�������������б�
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * �鿴�б�
     */
    function c_viewlist(){

    	return model_contract_common_contExcelUtil :: produceViewList();
//    	$this->view('viewlist');
    }

   /**
	 * ��ת����������������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭����������ҳ��
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
	 * ��ת���鿴����������ҳ��
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
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		set_time_limit(0);

		$uploaddir = WEB_TOR . 'upfile/';
		$uploadfile = $uploaddir. $_FILES['inputExcel']['name'];
		print "<pre>";
		if (move_uploaded_file($_FILES['inputExcel']['tmp_name'], $uploaddir . $_FILES['inputExcel']['name'])) {
		   msgGo("�ϴ��ɹ�", '?model=report_report_produce');
		} else {
		    print "ʧ�ܰܰܰܰ�";
		    print_r($_FILES);
		}
		print "</pre>";


	}
 }
?>