<?php

/**
 * 资产维保控制层类
 *  @author linzx
 */
class controller_asset_daily_keep extends controller_base_action {

	function __construct() {
		$this->objName = "keep";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}


	/**
	 * 跳转列表页面
	 * 不写就调用action的c_list();
	 */
	function c_list() {
		$this->view ( 'list' );
	}



		/**
	 * 初始化对象
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
            //提交审批后查看单据时隐藏关闭按钮
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'keeperId', $_SESSION ['USER_ID'] );
		$this->assign ( 'keeper', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'keepDate', date("Y-m-d") );
		$this->view ( 'add' );
	}


 /**
	  * ajax删除借用主表及清单从表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $keepObj = $this->service->get_d ( $_GET ['id'] );
			$keepitemDao = new model_asset_daily_keepitem();
	  		$condition = array(
	  			'keepId'=>$keepObj['id']
	  		);
	  		$keepitemDao->delete($condition);
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
	 * @linzx
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='.$id);
			} else {
				echo "<script>alert('新增成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('审批新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				 echo "<script>alert('新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
}
/**
	 * 修改对象操作
	 * @chenzb
	 */
      function c_edit() {
      	 $keepObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($keepObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='.$keepObj['id'].'&examCode=oa_asset_keep&formName=资产维保申请审批' );
			} else {
				 echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				 echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

}
?>