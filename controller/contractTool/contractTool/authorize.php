<?php

class controller_contractTool_contractTool_authorize extends controller_base_action {

	function __construct() {
		$this->objName = "authorize";
		$this->objPath = "contractTool_contractTool";
//		$this->lang="contract";//语言包模块
		parent :: __construct();
	}

	function  c_toAdd(){
		$userLimitStr = $this->service->getLimitHtml_d();
		$this->assign("userLimitStr",$userLimitStr);
		$this->view("add");
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsons() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();
		foreach($rows as $k=>$v){
			$rowArrs = array();
			$aff = explode(",",$v['limitInfo']);
			if(in_array("build",$aff)){
				array_push($rowArrs,"合同信息解读");
			}
			if(in_array("delivery",$aff)){
				array_push($rowArrs,"合同交付");
			}
			if(in_array("waiting",$aff)){
				array_push($rowArrs,"合同验收");
			}
			if(in_array("invoice",$aff)){
				array_push($rowArrs,"合同开票收款");
			}
			if(in_array("archive",$aff)){
				array_push($rowArrs,"合同文本归档");
			}
			if(in_array("close",$aff)){
				array_push($rowArrs,"合同关闭");
			}
			$rowsss = implode(",",$rowArrs);
			$rows[$k]['limitInfos'] = $rowsss;
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

		/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$limitArr = explode(",",$obj['limitInfo']);
			$userLimitStr = $this->service->getLimitHtml_d($limitArr);
			$this->assign("userLimitStr",$userLimitStr);
			$this->view ('edit',true);
		}
	}


	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id == "0") {
			msg( "该用户已存在!" );
		}else{
			msg ( $msg );
		}
	}

	function c_checkUser(){
		$userName = $_POST['userName'];
		$userCode = $_POST['userCode'];
		$result = $this->service->checkUser_d($userCode,$userName);
		if($result == 1){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>