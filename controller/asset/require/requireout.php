<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:资产转物料申请控制层
 */
class controller_asset_require_requireout extends controller_base_action {

	function __construct() {
		$this->objName = "requireout";
		$this->objPath = "asset_require";
		parent :: __construct();
	}
	
	/**
	 * 跳转到资产转物料申请列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到资产入库列表
	 */
	function c_awaitList() {
		$this->view('awaitList');
	}
	
	/**
	 * 跳转到相关物料明细
	 */
	function c_detailList(){
		$this->view('detailList');
	}
	
	/**
	 * 资产及物料明细表
	 */
	function c_jsonDetail(){
		$service = $this->service;
		
		$service->getParam ( $_REQUEST );
		//只显示出库数量大于0的资产及物料明细
		$sqlStr = "sql: and i.executedNum > 0";
		$service->searchArr['condition'] = $sqlStr;
		$rows = $service->page_d ('select_detail');
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
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->assign('applyDate', day_date);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyDeptId', $_SESSION['DEPT_ID']);
		$this->assign('applyDeptName', $_SESSION['DEPT_NAME']);
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->view('add');
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType != 'audit'){
			$object['ExaStatus']="待提交";
		}
		$id = $this->service->add_d($object,true);
		if ($id) {
			if ($actType == 'audit') {
				succ_show('controller/asset/require/ewf_index_requireout.php?actTo=ewfSelect&billId=' . $id);
			} else {
				msgRf('保存成功');
			}
		}else{
			if ($actType == 'audit') {
				msgRf('提交失败');
			} else {
				msgRf('保存失败');
			}
		}
	}
	
	/**
	 * 跳转到编辑页面
	 */
	function c_toEdit() {
// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 编辑对象操作
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType != 'audit' && $actType != 'confirm') {
			$object['ExaStatus']="待提交";
		}
		$flag = $this->service->edit_d($object, true);
		if ($flag) {				
			if ($actType == 'audit') {
				succ_show('controller/asset/require/ewf_index_requireout.php?actTo=ewfSelect&billId=' . $object['id']);
			} elseif ($actType == 'confirm') {
				msgRf('确认成功');
			} else {
				msgRf('编辑成功');
			}
		} else {
			if ($actType == 'audit') {
				msgRf('提交失败');
			} elseif ($actType == 'confirm') {
				msgRf('确认失败');
			} else {
				msgRf('编辑失败');
			}
		}
	}

	/**
	 * 跳转到查看页面
	 */
	function c_toView() {
		// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	
	/**
	 * 跳转到确认物料页面
	 */
	function c_toConfirm() {
		// 		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('confirm');
	}
	
	/**
	 * 下推入库时添加从表信息
	 */
	function c_getItemList () {
		$requireId = isset($_POST['requireId'])?$_POST['requireId']:null;
		$requireitemDao = new model_asset_require_requireoutitem();
		$rows = $requireitemDao->getDetail_d($requireId);
		echo util_jsonUtil::iconvGB2UTF ($requireitemDao->showProAtEdit($rows));
	}
	
	/**
	 * 审批过后执行方法
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// 审批里的objId为 申请单id
		$objId = $folowInfo['objId'];
		//审批通过，发送邮件通知相关人员进行资产入库库
		if ($folowInfo['examines'] == "ok") {
			$this->service->mailDeal_d('assetRequireout',null,array('id' => $objId));
		}else{//审批不通过，将卡片状态设置为闲置
			$object = $this->service->get_d($objId);
			foreach($object['items'] as $key => $val){
				$cardDao = new model_asset_assetcard_assetcard();
				$cardDao->update(array('id'=>$val['assetId']),array('useStatusCode'=>'SYZT-XZ','useStatusName'=>'闲置'));
			}
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
}