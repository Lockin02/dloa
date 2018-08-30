<?php
/**
 * 资产报废控制层类
 * @linzx
 */
class controller_asset_disposal_scrap extends controller_base_action {

	function __construct() {
		$this->objName = "scrap";
		$this->objPath = "asset_disposal";
		parent :: __construct();
	}

	/**
	 * 跳转列表页面
	 * 不写就调用action的c_list();
	 */
	function c_list() {
		$this->view('list');
	}

	/**
	* 初始化对象
	*/
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		//附件
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->view('view');
		} else {
			$this->view('edit');
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
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('deptName', $deptInfo['DEPT_NAME']);
		$this->assign('scrapDate', date("Y-m-d"));

		$type = $_GET['type'];

		if ($type == "lose") {
			//遗失单id
			$loseId = $_GET['loseId'];
			$this->assign('loseId', $loseId);
			//遗失单编号
			$loseBillNo = $_GET['loseBillNo'];
			$this->assign('loseBillNo', $loseBillNo);
			//遗失资产报废新增页面
			$this->view('lose-add');
		} else {
			//资产报废新增页面
			$this->view('add');
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAddByCard() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('deptName', $deptInfo['DEPT_NAME']);
		$this->assign('scrapDate', date("Y-m-d"));
		$this->assign('assetId', $_GET['assetId']);
		//资产报废新增页面
		$this->view('addbycard');
	}

	/**
	* 新增对象操作
	* @linzx
	*/
	function c_add() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		//提交财务确认
		if($actType == "finance"){
			$object['financeStatus'] = "财务确认";
		}else{
			$object['financeStatus'] = "待提交";
		}
		$id = $this->service->add_d($object, true);
		if($id){
			if($actType == "finance"){
				echo "<script>alert('提交财务确认成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('保存成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}else{
			if($actType == "finance"){
				echo "<script>alert('提交财务确认失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('保存失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	 * 修改对象操作
	 * @linzx
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		//提交财务确认
		if($actType == "finance"){
			$object['financeStatus'] = "财务确认";
		}else{
			$object['financeStatus'] = "待提交";
		}
		$id = $this->service->edit_d($object, true);
		if($id){
			if($actType == "finance"){
				echo "<script>alert('提交财务确认成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}else{
			if($actType == "finance"){
				echo "<script>alert('提交财务确认失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	* ajax删除借用主表及清单从表信息
	*/
	function c_deletes() {
		$message = "";
		try {
			$scrapObj = $this->service->get_d($_GET['id']);
			$scrapitemDao = new model_asset_disposal_scrapitem();
			$condition = array (
				'allocateID' => $scrapObj['id']
			);
			//如果从表里有loseId或loseId不为空 反写遗失明细表里的报废状态
			foreach ($scrapObj['details'] as $key => $val) {
				$assetId = $val['assetId'];
				$loseId = $val['loseId'];
				if ($loseId) {
					$changeStatus = new model_asset_daily_loseitem();
					$changeStatus->setNoScrapStatus($loseId, $assetId);
				}

			}

			$scrapitemDao->delete($condition);
			$this->service->deletes_d($_GET['id']);
			//			echo util_jsonUtil::encode ( $scrapObj);
			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch (Exception $e) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ($_GET['url'])) {
			$event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
			showmsg($message, $event, 'button');
		} else
			if (isset ($_SERVER[HTTP_REFERER])) {
				$event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
				showmsg($message, $event, 'button');
			} else {
				$this->c_page();
			}

		msg('删除成功！');

	}

	/**
	 * 固定资产报废审批过后执行方法
	 * @linzx
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// 审批里的objId为 报废单Id
		$objId = $folowInfo['objId'];
		//$cradId=$this->service->getAssetIdById_d($objId);
		if ($folowInfo['examines'] == "ok") {
			$cradId = $this->service->getAssetIdById_d($objId);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * 转到确认报废申请列表
	 */	
	function c_requireList() {
		$this->view('require-list');
	}
	/**
	 * 转到核对报废申请
	 */
	function c_toCheckRequire() {
		$obj = $this->service->get_d($_GET['id']);
		//附件
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		//邮件接收人处理
		$mailId = implode(',', array_unique(array($obj['proposerId'],$obj['payerId'])));
		$mailName = implode(',', array_unique(array($obj['proposer'],$obj['payer'])));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('mailId', $mailId);
		$this->assign('mailName', $mailName);
		$this->view('check-require');
	}
	/**
	 * 核对、打回报废申请
	 */
	function c_confirmRequire() {
		$object = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == "check"){
			$object['financeStatus'] = "已确认";
		}else{
			$object['financeStatus'] = "打回";
		}
		$id = $this->service->edit_d($object, true);
		if($id){
			if($actType == "check"){
				echo "<script>alert('核对成功!');</script>";
				//核对成功进入审批流
				succ_show('controller/asset/disposal/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
			}else{
				echo "<script>alert('打回成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}else{
			if($actType == "check"){
				echo "<script>alert('核对失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}else{
				echo "<script>alert('打回失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}
	/**
	 * 撤回
	 */
	function c_recall(){
		$object = $this->service->get_d($_POST['id']);
		$object['financeStatus'] = "待提交";
		$object['recallFlag'] = "y";  //撤回标志
		$object['item'] = $object['details'];
		unset($object['details']);
		$id = $this->service->edit_d($object, true);
		if($id){
			echo 1;
		}else{
			echo 0;
		}
	}
	/**
	 * 确认报废申请分页Json
	 */
	function c_requirePageJson() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		//拼装自定义sql
		$sqlStr = "sql: and c.financeStatus in ('财务确认','已确认') and c.ExaStatus in ('部门审批','待提交','打回')";
		$service->searchArr['statusCondition'] = $sqlStr;
		$rows = $service->page_d ();
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
}