<?php

/**
 *
 * 评估方案控制层类
 * @author fengxw
 *
 */

class controller_supplierManage_scheme_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "supplierManage_scheme";
		parent :: __construct();
	}

	/**
	 * 跳转到评估方案
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//读取数据字典
		$this->showDatadicts(array (
			'OptschemeType' => 'FALX'
		));
		$this->assign('formManId', $_SESSION['USER_ID']);
		$this->assign('formManName', $_SESSION['USERNAME']);
		$this->assign('formManDept', $_SESSION['DEPT_NAME']);
		$this->assign('formManDeptId', $_SESSION['DEPT_ID']);
		$this->assign('formDate', date("Y-m-d"));
		$this->view('add',true);
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		//		echo "<pre>";
		//		print_R($_GET);
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			if (isset ($_GET['viewBtn'])) {
				$this->assign('showBtn', 1);
			} else {
				$this->assign('showBtn', 0);
			}
			//读取数据字典
			$this->showDatadicts(array (
				'OptschemeType' => 'FALX'
			));
			$this->view('view');
		} else {
			$this->assign('formManDept', $_SESSION['DEPT_NAME']);
			$this->assign('formManDeptId', $_SESSION['DEPT_ID']);
			//读取数据字典
			$this->showDatadicts(array ('schemeTypeCode' => 'FALX'),$obj['schemeTypeCode']);
			$this->view('edit',true);
		}
	}

 	/**
	  * 先删从表信息，再删主表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $Obj = $this->service->get_d ( $_GET ['id'] );
			$itemDao = new model_supplierManage_scheme_schemeItem();
	  		$condition = array(
	  			'parentId'=>$Obj['id']
	  		);
	  		$itemDao->delete($condition);
			$this->service->deletes_d ( $_GET ['id'] );
			$message = '<div style="color:red" align="center">删除成功!</div>';
		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}
		msg('删除成功！');
	  }

	 /**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
 				msgGo("保存成功",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
		else{
			if ("audit" == $actType) {
 				msgGo("审批提交失败",'index1.php?model=supplierManage_scheme_scheme');
			} else {
 				msgGo("保存失败",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
	}

	/**
	 * 修改对象操作
	 */
      function c_edit() {
      	$this->checkSubmit(); //验证是否重复提交
      	$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
			} else {
 				msgGo("修改成功",'index1.php?model=supplierManage_scheme_scheme');
			}
		}
		else{
			if ("audit" == $actType) {
 				msgGo("审批提交失败",'index1.php?model=supplierManage_scheme_scheme');
			} else {
 				msgGo("修改失败",'index1.php?model=supplierManage_scheme_scheme');
			}

		}
	}


    /**
     * 根据部门Id获取负责人信息
     */
    function c_getDeptLeader(){
        $deptId=isset($_POST['deptId'])?$_POST['deptId']:'';
        $returnRow=$this->service->getDeptLeader($deptId);
        if(!empty($returnRow)){
            echo util_jsonUtil::encode ( $returnRow );
        }else{
            echo 0;
        }
    }
}
?>