<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:11:22
 * @version 1.0
 * @description:��Ӧ����Ŀ��Ϣ���Ʋ�
 */
class controller_outsourcing_supplier_proejct extends controller_base_action {

	function __construct() {
		$this->objName = "proejct";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ӧ����Ŀ��Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

    	/**
	 * ��ת����Ӧ����Ŀ��Ϣ�б�
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * ��ת����Ӧ����Ŀ��Ϣ�б�
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * ��ת��������Ӧ����Ŀ��Ϣҳ��
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //��ȡ��Ӧ����Ϣ
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
     	$this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ӧ����Ŀ��Ϣҳ��
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
	 * ��ת���鿴��Ӧ����Ŀ��Ϣҳ��
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
	function c_pageJsonProject() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ("select_project");
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