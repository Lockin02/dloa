<?php
/**
 * 资产出售控制层类
 * @linzx
 */
class controller_asset_disposal_sell extends controller_base_action {

	function __construct() {
		$this->objName = "sell";
		$this->objPath = "asset_disposal";
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
		$this->assign ( 'sellerId', $_SESSION ['USER_ID'] );
		$this->assign ( 'seller', $_SESSION ['USERNAME'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'sellDate', date("Y-m-d") );

		$this->view ( 'add' );
	}


	/**
	 * 由报废单跳转到报废具体资产页面
	 * @author linzx
	 */
	function c_toSellScrap(){
		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo',$billNo);
		$allocateID = isset ($_GET['allocateID']) ? $_GET['allocateID'] : null;
		$this->assign('allocateID',$allocateID);

		$this->view ( 'scrapitem-list' );
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
				succ_show ( 'controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='.$id);
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
	 * @linzx
	 */
      function c_edit() {
      	 $sellObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($sellObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='.$sellObj['id'].'&examCode=oa_asset_sell&formName=资产出售申请审批' );
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


	/**
	 * 固定资产出售审批过后执行方法
	 * @linzx
	 */
	function c_dealAfterAudit(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		// 审批里的objId为 出售单Id
		$objId = $folowInfo ['objId'];
//		$cradId=$this->service->getCardIdById_d($objId);
		if ($folowInfo['examines'] == "ok") {
			$cradId=$this->service->getCardIdById_d($objId);
		}
      	echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

	 /**
	  * ajax删除借用主表及清单从表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $sellObj = $this->service->get_d ( $_GET ['id'] );
			$sellitemDao = new model_asset_disposal_sellitem();
	  		$condition = array(
	  			'sellId'=>$sellObj['id']
	  		);
	  		$sellitemDao->delete($condition);
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
	   * 由报废具体资产跳转到填写报废出售单列表页面
	   */
	  function c_toScrapAssetList(){
	  	$branchDao = new model_deptuser_branch_branch();
	  	$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
	  	$this->assign('applyCompanyCode', $_SESSION['Company']);
	  	$this->assign('applyCompanyName', $branchArr['NameCN']);
	  	$this->assign ( 'deptId', $_SESSION ['DEPT_ID'] );
	  	$this->assign ( 'sellerId', $_SESSION ['USER_ID'] );
	  	$this->assign ( 'seller', $_SESSION ['USERNAME'] );
	  	$deptDao = new model_deptuser_dept_dept();
	  	$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
	  	$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
	  	$this->assign ( 'sellDate', date("Y-m-d") );
	  	$assetIdArr= $_GET['assetIdArr'];
	  	$this->assign('assetIdArr',$assetIdArr);
	  	
	  	$this->view ( 'scrap-addList' );
	  }
}