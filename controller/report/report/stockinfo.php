<?php
/**
 * @author Administrator
 * @Date 2013��10��8�� 10:16:55
 * @version 1.0
 * @description:��Ʒ��汨�������Ϣ���Ʋ�
 */
class controller_report_report_stockinfo extends controller_base_action {

	function __construct() {
		$this->objName = "stockinfo";
		$this->objPath = "report_report";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ʒ��汨�������Ϣ�б�
	 */
    function c_page() {

      $dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);
      $this->view('list');
    }

   /**
	 * ��ת��������Ʒ��汨�������Ϣҳ��
	 */
	function c_toAdd() {
	  //��ȡ������ ֧�������֧�����
	  $newWorkStr = $this->service->getNetWorkStr();
	  $this->assign("newWorkStr",$newWorkStr);
	  $softWareStr = $this->service->getsoftWareStr();
	  $this->assign("softWareStr",$softWareStr);

	  $dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);

      $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ʒ��汨�������Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

	  //��ȡ������ ֧�������֧�����
	  $newWorkStr = $this->service->getNetWorkStr($obj);
	  $this->assign("newWorkStr",$newWorkStr);
	  $softWareStr = $this->service->getsoftWareStr($obj);
	  $this->assign("softWareStr",$softWareStr);
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��Ʒ��汨�������Ϣҳ��
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
      * ��Ʒ��汨��
      */
    function c_reportView(){
    	$object = array(
			'budgetTypeName' => '',
			'budgetTypeId' => '',
			'brand' => '',
			'equName' => '',
			'equId' => '',
			'netWork' => '',
			'software' => '',
			'isStop' => ''
		);
		$object = isset($_GET['isSearch']) ? $_GET : $object;
		$this->assignFunc($object);

		$dataType = isset($_GET['dataType'])?$_GET['dataType']: 0 ;
	  $this->assign("dataType",$dataType);
    	$this->view("reportView");
    }
    //�߼�����
    function c_listinfoSearch(){
//     	print_r($_GET);
//     	die();
    	$newWorkStr = $this->service->getNetWorkStr();
    	$this->assign("newWorkStr",$newWorkStr);
    	$softWareStr = $this->service->getsoftWareStr();
    	$this->assign("softWareStr",$softWareStr);
		$this->assignFunc( $_GET );
		$this->assign("dataType",$_GET['dataType']);
		$this->view('listinfosearch');
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * json
	 */

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_stockJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$service->__SET('groupBy', "c.id");
		$service->sort = "budgetTypeName";
		$rows = $service->pageBySqlId('select_gridinfo');
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

	/**
	 * ����������ϸ tab
	 */
	function c_numViewTab(){
		$cids = $_GET['cids'];
		$bids = $_GET['bids'];
		$pids = $_GET['pids'];
		$this->assign("cids",$cids);
		$this->assign("bids",$bids);
		$this->assign("pids",$pids);
         $this->view("view-tab");
	}

	//��ͬ�����б�
	function c_conViewList(){
		$cids = $_GET['cids'];
		$this->assign("cids",$cids);
		$this->view("conViewList");
	}
	//���������б�
	function c_borrowViewList(){
		$bids = $_GET['bids'];
		$this->assign("bids",$bids);
		$this->view("borrowViewList");
	}
	//���������б�
	function c_preViewList(){
		$pids = $_GET['pids'];
		$this->assign("pids",$pids);
		$this->view("preViewList");
	}

 }
?>