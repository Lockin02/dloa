<?php
/**
 * @author ACan
 * @Date 2016��6��14�� 14:51:41
 * @version 1.0
 * @description:��Ӧ�����������Ʋ� 
 */
class controller_supplierManage_formal_usedname extends controller_base_action {

	function __construct() {
		$this->objName = "usedname";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ӧ���������б�
	 */
    function c_page() {
        $this->show->assign ( 'suppId', $_GET ['suppId'] );
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ӧ��������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ӧ��������ҳ��
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
	 * ��ת���鿴��Ӧ��������ҳ��
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
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }
 }
?>