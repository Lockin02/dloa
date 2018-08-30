<?php


/**
 * 卡片清理控制层类
 *  @author linzx
 */
class controller_asset_assetcard_clean extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "clean";
		$this->objPath = "asset_assetcard";
		parent :: __construct();
	}
	/**
	 * 跳转到用信息列表
	 */
	function c_page() {
		$this->view('list');
	}
	/**
	 * 跳转到出售单资产列表
	 */
	function c_toCleanSell() {

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$sellID = isset ($_GET['sellID']) ? $_GET['sellID'] : null;
		$this->assign('sellID', $sellID);
		$this->view('sellitem-list');

	}

	/**
	 * 跳转到报废单资产列表
	 */
	function c_toCleanScrap() {

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$allocateID = isset ($_GET['allocateID']) ? $_GET['allocateID'] : null;
		$this->assign('allocateID', $allocateID);
		$this->view('scrapitem-list');

	}
	/**
	 * 跳转到新增页面 清理资产出售
	 **/
	function c_toAdd() {

		$billType = isset ($_GET['billType']) ? $_GET['billType'] : null;
		$this->assign('billType', $billType);

		$billNo = isset ($_GET['billNo']) ? $_GET['billNo'] : null;
		$this->assign('billNo', $billNo);

		$billID = isset ($_GET['billID']) ? $_GET['billID'] : null;
		$this->assign('billID', $billID);

		$assetCode = isset ($_GET['assetCode']) ? $_GET['assetCode'] : null;
		$this->assign('assetCode', $assetCode);

		$assetId = isset ($_GET['assetId']) ? $_GET['assetId'] : null;
		$this->assign('assetId', $assetId);

		$assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName', $assetName);

		$assetTypeName = isset ($_GET['assetTypeName']) ? $_GET['assetTypeName'] : null;
		$this->assign('assetTypeName', $assetTypeName);

		$this->assign('cleanDate', date("Y-m-d"));

		//变动方式下拉框
		$methodDao = new model_asset_basic_change();
		$names = $methodDao->listBySqlId("select_changeIsDel");
		//$names=$methodDao->list_d();
		$list = $this->service->showSelect_d($names);
		$this->assign("changeWayOption", $list);

		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName], true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/assetcard/ewf_index_clean.php?actTo=ewfSelect&billId=' . $id);
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
	 * 初始化对象 查看清理信息
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//提交审批后查看单据时隐藏关闭按钮
		if (isset ($_GET['viewBtn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}
		$this->view('view');
	}

	/**
	 * 固定资产清理审批过后执行方法
	 * @linzx
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		// 审批里的objId为 清理单Id  [examines] => ok  [examines] => no
		$objId = $folowInfo['objId'];
		//审批通过就改变卡片的清理状态，没通过就不改变
		if ($folowInfo['examines'] == "ok") {
			$cradId = $this->service->getAssetIdById_d($objId);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

}
?>