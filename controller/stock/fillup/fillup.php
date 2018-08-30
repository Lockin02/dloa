<?php
/**
 * @author huangzf
 * @Date 2011年1月17日 11:51:07
 * @version 1.0
 * @description:补库计划控制层
 */
class controller_stock_fillup_fillup extends controller_base_action {

	function __construct() {
		$this->objName = "fillup";
		$this->objPath = "stock_fillup";
		parent::__construct ();
	}

	/*
	 * 跳转到补库计划
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * @desription 添加方法
	 * @param tags
	 * @date 2011-1-17 下午02:07:21
	 * @qiaolong
	 */
	function c_toAdd() {
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 *
	 * 根据库存警告信息下推补库计划
	 */
	function c_toPush() {
		$this->view ( "push" );
	}
	/**
	 * @desription 跳转到查看页面
	 * @param tags
	 * @date 2011-1-17 下午01:28:20
	 * @qiaolong
	 */
	function c_init() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$fillup = $this->service->get_d ( $_GET ['id'] );

		foreach ( $fillup as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
// 						echo"<pre>";
// 						print_r($str);
// 						die();
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}

		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;

		$this->show->assign ( "actType", $actType ); //操作页面(一般的查看页面、内嵌在审批表单中)
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

		/**
	 * 跳转审批编辑页面
	 *
	 * @param tags
	 */
	function c_toAuditEdit () {
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isLastStep($_GET['id'],$this->service->tbl_name);
		if(0){
			$id = $_GET ['id'];
			$fillup = $this->service->get_d ( $_GET ['id'] );
			$this->assign('invnumber',count( $fillup ["details"] ));
			foreach ( $fillup as $key => $val ) {
				if ($key == 'details') {
					$str = $this->service->showEditAudit ( $val );
					$this->show->assign ( 'list', $str );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->display('audit-edit');
		}else{
			$this->c_init();
		}
	}

	/**
	 *详细页面
	 */
	function c_detail() {
		$this->show->assign ( "id", $_GET ['id'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-detail' );
	}

	/**
	 * @desription 跳转到修改页面
	 * @linzx
	 */
	function c_toEdit() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$fillup = $this->service->get_d ( $_GET ['id'] );
		foreach ( $fillup as $key => $val ) {

			if ($key == 'details') {
				$str = $this->service->showEditDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->show->assign ( "itemscount", count ( $fillup ['details'] ) );
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
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
				succ_show ( 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_stock_fillup&formName=补库审批' );
			} else {
				echo "<script>alert('新增成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
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
		$fillUpObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $fillUpObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId=' . $fillUpObj ['id'] . '&examCode=oa_stock_fillup&formName=补库审批' );
			} else {
				echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}
	/**
	 * 改变单据的状态
	 */
	function c_changeAuditStatus() {
		$service = $this->service;
		if ($service->changeAuditStatus ( $_GET ['id'] )) {
			echo 0; //成功
		} else {
			echo 1;
		}
	}

	/**判断是否能下达采购计划
	 *author can
	 *2011-3-30
	 */
	function c_isAddPlan() {
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$flag = $this->service->isAddPlan_d ( $id );
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
	}
	/**************************************审批相关****************************************************************/

	/**
	 * 我的审批 - tab
	 */
	function c_auditTab() {
		$this->display ( 'audittab' );
	}

	/**
	 * 我的审批 － 未审批页面
	 */
	function c_toAuditNo() {
		$this->display ( 'auditno' );
	}

	/**
	 * 我的审批 － 已审批的页面
	 */
	function c_toAuditYes() {
		$this->display ( 'audityes' );
	}

	/**
	 * @desription 我的审核任务 盘点信息审核 列表获取数据方法
	 * @param tags
	 * @date 2011-8-17
	 * @chenzb
	 */
	function c_myApprovalPJ() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true; //设置降序排序
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		//$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ( 'sql_examine' );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * 审批补库后处理业务方法
	 */
	function c_dealAfterAudit() {
		$rows=isset($_GET['rows'])?$_GET['rows']:null;

	    //审批流回调方法
        $this->service->workflowCallBack($_GET['spid']);

		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
}

?>