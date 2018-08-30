<?php
/**
 * @author Administrator
 * @Date 2012-08-09 09:35:57
 * @version 1.0
 * @description:离职清单模板控制层
 */
class controller_hr_leave_formwork extends controller_base_action {

	function __construct() {
		$this->objName = "formwork";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职清单模板列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增离职清单模板页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑离职清单模板页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看离职清单模板页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
   	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$service->sort='sort';
		$rows = $service->list_d ("select_default");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_addItemList() {
		$count = isset ($_POST['count']) ? $_POST['count'] : '';
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$service->sort='sort';
		$rows = $service->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_d($rows,$count);
		echo $fromworkList;
	}
 }
?>