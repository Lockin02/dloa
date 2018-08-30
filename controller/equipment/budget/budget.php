<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:20
 * @version 1.0
 * @description:�豸Ԥ�����Ʋ�
 */
class controller_equipment_budget_budget extends controller_base_action {

	function __construct() {
		$this->objName = "budget";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * ��ת���豸Ԥ����б�
	 */
    function c_page() {
      $this->view('list');
    }
    /**
     * �ҵ�Ԥ���
     */
    function c_mybudget() {
      $this->assign("userId",$_SESSION['USER_ID']);
      $this->view('mybudgetList');
    }

   /**
    * ��дԤ���
    */
   function c_addBudget(){
//      $this->view('addBudget');
      $this->c_toAdd();
   }

   /**
	 * ��ת�������豸Ԥ���ҳ��
	 */
	function c_toAdd() {
		$equId = $_GET ['equId'];
	  if(!empty($equId)){
	  	 $baseDao = new model_equipment_budget_budgetbaseinfo();
         $baseinfo = $baseDao->get_d($equId);
	  }else{
	  	 $baseinfo = array("budgetTypeName"=>"","equName"=>"","remark"=>"");
	  }

        foreach ( $baseinfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployList($equId);
//		$this->assign("list",$list);
     //˵��
     //��ȡ�ļ�����
    	$url =  WEB_TOR . 'view/template/equipment/budget/budgetExplain.txt';
    	if($url){
			$fileSize = filesize($url);
			if($file = fopen($url ,'r')){
				$str = stripslashes(fread($file,$fileSize));
			}else{
				$str = "�Ҳ����ļ�";
			}
			fclose($file);
    	}else{
			$str = "�����ڵ�����";
    	}
    	$str = nl2br($str);
    	$this->assign('explain',$str);

     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�豸Ԥ���ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployEditList($_GET ['id']);
//		$this->assign("list",$list);
	    $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�豸Ԥ���ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$list = $this->service->deployViewList($_GET ['id']);
//		$this->assign("list",$list);
		$this->view ( 'view' );
   }


	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msgGo ( $msg ,'?model=equipment_budget_budget&action=addBudget');
		}
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
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

    /**
     * Ԥ���˵��ά��
     */
    function c_explain(){
    	//��ȡ�ļ�����
    	$url =  WEB_TOR . 'view/template/equipment/budget/budgetExplain.txt';
    	if($url){
			$fileSize = filesize($url);
			if($file = fopen($url ,'r')){
				$str = stripslashes(fread($file,$fileSize));
			}else{
				$str = "�Ҳ����ļ�";
			}
			fclose($file);
    	}else{
			$str = "�����ڵ�����";
    	}
    	$this->assign('content',$str);
       $this->view("explain");
    }

    function c_editExplain(){
      $object = $_POST ['explain'];
      $this->service->editExplain_d($object);
      msgGo("�޸ĳɹ�", '?model=equipment_budget_budget&action=explain');
    }

    /**
     * �ӱ�����
     */
    function c_baseinfoList(){
    	 $ids = isset ($_POST['ids']) ? addslashes($_POST['ids']) : "";
    	 $type = isset($_POST['type']) ? addslashes($_POST['type']) : "";
         $listHTML = $this->service->baseinfoList_d($ids,$type);
//         echo $listHTML;
         echo util_jsonUtil :: iconvGB2UTF($listHTML);
    }


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );

		foreach($rows as $key => $val){
           $useEndDate = $service->getUseEndDate($val['id']);
           $rows[$key]['useEndDate']  = $useEndDate;
		}

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