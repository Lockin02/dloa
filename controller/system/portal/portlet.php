<?php

/**
 * portlet���Ʋ�
 */
class controller_system_portal_portlet extends controller_base_action {

	function __construct() {
		$this->objName = "portlet";
		$this->objPath = "system_portal";
		parent :: __construct();
	}

	function c_list() {
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:'';
		$parentName = isset($_GET['parentName'])?$_GET['parentName']:'';
		$this->assign('parentId',$parentId);
		$this->assign('parentName',$parentName);
		$this->view ( 'list' );
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$typeId = isset($_GET['typeId'])?$_GET['typeId']:'';
		$typeName = isset($_GET['typeName'])?$_GET['typeName']:'';
		$this->assign('typeId',$typeId);
		$this->assign('typeName',$typeName);
		$this->view ( 'add' );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			if($key=="isPerm"){
				$val=($val==1?"checked":"");
			}
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��д�༭
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if(empty($object['isPerm'])){
			$object['isPerm']=0;
		}
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��д����
	 */
	function c_add($isAddInfo = false) {
		$object = $_POST [$this->objName];
		if(empty($object['isPerm'])){
			$object['isPerm']=0;
		}
		$id = $this->service->add_d ( $object, $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * ��½�û��Ż���ҳ
	 */
	function c_portal(){
		$sql="select max(portletOrder) as num from oa_portal_portlet_user where userId='".$_SESSION['USER_ID']."'";
		$maxOrder=$this->service->queryCount($sql);
		$this->view ( 'portal' );
	}

	/**
	 * ѡ��portlet
	 */
	function c_selectPortlet(){
		$this->view ( 'select' );
	}


	/**
	 * ��ȡ��ǰ��¼��ӵ��Ȩ�޵�portlets��ҳ
	 */
	function c_getPermPortlets(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->getUserRolePerm ($_SESSION["USER_ID"],$_SESSION["USER_JOBSID"]);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>