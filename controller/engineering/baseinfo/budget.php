<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 9:38:59
 * @version 1.0
 * @description:Ԥ����Ŀ(oa_esm_baseinfo_budget)���Ʋ� status
                                                0 ����
                                                1.����
 */
class controller_engineering_baseinfo_budget extends controller_base_action {

	function __construct() {
		$this->objName = "budget";
		$this->objPath = "engineering_baseinfo";
		parent::__construct ();
	}

	/*
	 * ��ת��Ԥ��Ŀ¼
	 */
    function c_page() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$this->assign('parentId',$parentId);
       	$this->view('list');
    }

	/**
	 * ��ȡ������Ԥ������
	 */
	function c_pageJsonDL() {
		$service = $this->service;

		$_POST['isNewDL'] = 1;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->asc = false;
		$service->sort = 'c.ParentCostTypeID,c.CostTypeID';
		$rows = $service->page_d ('select_budgetdl');

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
	 * ������
	 */
	function c_getChildren(){
		$service = $this->service;

		$sqlKey = isset($_POST['rtParentType'])? 'select_treelistRtBoolean' : 'select_treelist';

		if(empty($_POST['id'])){
			$rows = array(array('id'=>PARENT_ID,'code' => 'root','name'=> 'Ԥ��Ŀ¼','isParent'=>'true'));
		}else{
			$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
			$service->searchArr['parentId'] = $parentId;
			$service->asc = false;
			$rows=$service->listBySqlId($sqlKey);
		}
		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * ����Ƿ���ڸ��ڵ㣬�����������һ��
	 */
	function c_checkParent(){
		if($this->service->checkParent_d()){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId),null,'id,budgetCode,budgetName');
        $this->assignFunc($row);
		$this->showDatadicts ( array ('budgetType' => 'FYLX' ));
        $this->view('add');
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
			$TypeOne = $this->getDataNameByCode ( $obj ['budgetType'] );
			$this->assign ( 'budgetType', $TypeOne );
			$this->view ( 'view' );
		} else {
			$this->showDatadicts ( array ('budgetType' => 'FYLX' ), $obj ['budgetType'] );
			$this->view ( 'edit' );
		}
	}
	/**
	 * @ ajax�ж���
	 *
	 */
    function c_ajaxDudgetCode() {
    	$service = $this->service;
		$projectName = isset ( $_GET ['budgetCode'] ) ? $_GET ['budgetCode'] : false;

		$searchArr = array ("budgetCode" => $projectName );

		$isRepeat =$service->isRepeat ( $searchArr, "" );

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * �ı�����״̬
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => $_POST['status']))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ɾ��ʱ�ж��Ƿ�����
	 */
	 function c_deleteCheck(){
		if($this->service->deleteCheck_d($_POST['rowData']) == 1){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	 }
}
?>