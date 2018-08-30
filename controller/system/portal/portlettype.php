<?php
/**
 * 门户portlet类型控制层
 */
class controller_system_portal_portlettype extends controller_base_action {

	function __construct() {
		$this->objName = "portlettype";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }

	/**
	 * 构造树
	 */
	 function c_getChildren(){
		$service = $this->service;
		//$service->getParam ( $_REQUEST );
		if(!empty($_POST ['id'])){
			$service->searchArr['parentId'] = $_POST['id'];
		}else{
			$service->searchArr['parentId'] = -1;
		}
		$service->asc = false;

		$rows=$service->list_d();
		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * 获取数据
	 */
	 function c_list() {
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:'';
		$parentName = isset($_GET['parentName'])?$_GET['parentName']:'';
		$this->assign('parentId',$parentId);
		$this->assign('parentName',$parentName);
		$this->view ( 'list' );
	}
	/*
	 * * 跳转到新增页面
	 */
	function c_toAdd() {
		$typeId = isset($_GET['typeId'])?$_GET['typeId']:'';
		$typeName = isset($_GET['typeName'])?$_GET['typeName']:'';
		$this->assign('parentId',$typeId);
		$this->assign('parentName',$typeName);
		$this->view ( 'add' );
	}
	//  增加信息方法
	function c_add($isAddInfo = false) {
		$_POST[$this->objName]['parentName'] = ($_POST[$this->objName]['parentName']!="")?$_POST[$this->objName]['parentName']:'根';
		$_POST[$this->objName]['parentId'] = ($_POST[$this->objName]['parentId']!="")?$_POST[$this->objName]['parentId']:'-1';
		//当为空则进行设置
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 构建portlet树
	 */
	function c_getPortletTree(){
		$service = $this->service;
		//$service->getParam ( $_REQUEST );
		$parentId=empty($_POST['id'])?-1:$_POST['id'];
		$service->searchArr['parentId']=$parentId;
		$service->asc = false;

		$types=$service->list_d();//获取类型
		function fn($row) {
			$row ['open'] ="true";
			return $row;
		}
		if(is_array($types)){
			$types=array_map ( "fn", $types ) ;
		}else{
			$types=array();
		}
		$portletDao=new model_system_portal_portlet();
		$portletDao->searchArr['typeId']=$parentId;
		$portletDao->searchArr['isPerm']=1;
		$portlets=$portletDao->list_d();
		//$list=array_push($types,$portlets);
		if(is_array($portlets)){
			foreach($portlets as $key=>$val){
				array_push($types,$val);
			}
		}
		if(empty($types)){
			$types=$portlets;
		}
		echo util_jsonUtil :: encode ($types);
	}
	/*
	**default不为空，不可以删除
	*/
	function c_ajaxdeletes(){
		//$this->permDelCheck ();
		try{
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		}catch(Exception $e){
			echo "";
			//throw $e;
		}
	}
 }
?>