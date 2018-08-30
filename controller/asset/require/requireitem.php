<?php
/**
 * @author Administrator
 * @Date 2012��5��11�� 11:41:42
 * @version 1.0
 * @description:�ʲ�����������ϸ���Ʋ�
 */
class controller_asset_require_requireitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }

    /**
	 * ��ת���ʲ�����������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������ʲ�����������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�ʲ�����������ϸҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�ʲ�����������ϸҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   
	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * �´�ɹ�����ʱ��ȡ�������ݷ���json
	 */
	function c_requireJsonApply() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->requireJsonApply_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * �´�����ת�ʲ�����ʱ��ȡ�������ݷ���json
	 */
	function c_requireinJsonApply() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->requireinJsonApply_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_pageByRequireJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );}
 }
?>