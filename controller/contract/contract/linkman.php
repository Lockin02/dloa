<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:15:29
 * @version 1.0
 * @description:合同联系人信息表控制层
 */
class controller_contract_contract_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "contract_contract";
		parent::__construct ();
	}

	/*
	 * 跳转到合同联系人信息表列表
	 */
    function c_page() {
    	$this->view('list');
    }

   /**
	 * 跳转到新增合同联系人信息表页面
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
	}

   /**
	 * 跳转到编辑合同联系人信息表页面
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
	 * 跳转到查看合同联系人信息表页面
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
	 * 获取所有数据返回json
	 */
	function c_listJsonLimit() {
		$service = $this->service;

		//关键人员信息获取
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);


		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();

		$otherDataDao = new model_common_otherdatas();
		$limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID']);

		if ($createId != $_SESSION ['USER_ID'] && $prinvipalId != $_SESSION ['USER_ID'] && $areaPrincipalId != $_SESSION ['USER_ID']) {
			$rows = $this->service->filterWithoutField ( '联系人信息', $rows, 'keyList', array ('Email', 'telephone','QQ' ),'contract_contract_contract' );
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>