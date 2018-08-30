<?php

/**
 * @author huangzf
 * @Date 2012年3月1日 20:16:27
 * @version 1.0
 * @description:产品基本信息控制层
 */

class controller_goods_goods_goodsbaseinfo extends controller_base_action
{

	function __construct() {
		$this->objName = "goodsbaseinfo";
		$this->objPath = "goods_goods";
		parent::__construct();
	}

	/**
	 * 跳转到产品基本信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到选择产品列表
	 */
	function c_toChoosePage() {
		$this->assign('isMoney', isset($_GET['isMoney']) ? $_GET['isMoney'] : 0);
		$this->assign('isSale', isset($_GET['isSale']) ? $_GET['isSale'] : 0);
		$this->assign('isCon', isset($_GET['isCon']) ? $_GET['isCon'] : 0);
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
		$this->assign('isFrame', isset($_GET['isFrame']) ? $_GET['isFrame'] : 0);
        $this->assign('notEquSlt', isset($_GET['notEquSlt']) ? $_GET['notEquSlt'] : 0);
        $this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : 0);// 变更合同时所用
		$this->view('choose-list');
	}

	/**
	 * 选择列表
	 */
	function c_pageSelect() {
		$this->view('list-select');
	}

	/**
	 * 跳转到新增产品页面
	 */
	function c_toAddWhole() {
		$this->showDatadicts(array('exeDeptName' => 'GCSCX'));
		$parentName = isset ($_GET['parentName']) ? $_GET['parentName'] : "销售类产品";
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : "11";
		$this->showDatadicts(array('useStatus' => 'WLSTATUS'));
		$this->assign("parentId", $parentId);
		$this->assign("parentName", $parentName);
		$this->view('addWhole');
	}

	/**
	 * 跳转到新增产品基本信息页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('exeDeptCode' => 'HTCPX'));
		$parentName = isset ($_GET['parentName']) ? $_GET['parentName'] : "";
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : "";
		$this->showDatadicts(array('useStatus' => 'WLSTATUS'));
        $this->showDatadicts(array('goodsTypeCode' => 'PDTYPE'));
		$this->assign("parentId", $parentId);
		$this->assign("parentName", $parentName);
		$this->view('add');
	}

	/**
	 * 跳转到编辑产品基本信息页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$obj['file'] = $this->service->getFilesByObjId($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('useStatus' => 'WLSTATUS'), $obj['useStatus']);
		$this->showDatadicts(array('exeDeptCode' => 'HTCPX'), $obj['exeDeptCode']);
        $this->showDatadicts(array('goodsTypeCode' => 'PDTYPE'),$obj['goodsTypeCode']);

		$this->view('edit');
	}

	/**
	 * 跳转到查看产品基本信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$obj['file'] = $this->service->getFilesByObjId($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->show->assign("useStatus", $this->getDataNameByCode($obj['useStatus']));
		$this->show->assign('exeDeptCode', $this->getDataNameByCode($obj['exeDeptCode']));
		$this->view('view');
	}

	/**
	 *海外查看国内产品基本信息页面
	 */
	function c_HWtoView($id) {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($id);
		$obj['file'] = $this->service->getFilesByObjId($id);
		return util_jsonUtil:: iconvGB2UTFArr($obj);
	}

	/**
	 * 跳转到产品检测页面
	 */
	function c_toViewRelation() {
		$msg = $this->service->checkRelateInfo($_GET['id']);
		$this->assign('msg', $msg);
		$this->view("relation");
	}

	/**
	 * 更新产品信息
	 *
	 */
	function c_toUpdate() {
		$rows = $this->service->get_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('update');
	}

	/**
	 * 获取物料关联业务对象
	 */
	function c_getGoodsRelationArr() {
		include_once("model/goods/goods/goodsRelationTableArr.php");
		echo util_jsonUtil::encode(isset($goodsRelationTableArr) ? $goodsRelationTableArr : '');
	}

	/**
	 * 更新物料相关业务信息
	 */
	function c_updateGoodsInfo() {
		$goodsbaseinfo = $_POST['goodsbaseinfo'];
		$relationArr = $_POST['checked']; //选中更新的业务对象
		if ($_POST['updateType'] == 1) {
			//根据id更新
			$this->service->updateRelationsById($goodsbaseinfo, $relationArr);
		}
		msg('更新成功！');
	}

	/**
	 * 获取国内产品所有数据转成Json返回到海外
	 */
	function c_getOAPropageJson($objArr) {
		$object = $objArr['object'];
		$object['model'] = "goods_goods_goodsbaseinfo";
		$object['action'] = "pageJson";
		$object['goodsTypeId'] = $object['productTypeId'];
		$service = $this->service;
		$service->getParam($object);
		$rows = $service->page_d();
		return util_jsonUtil::iconvGB2UTFArr($rows);
	}

	/**
	 * ajax根据产品id 获取产品线
	 */
	function c_getExeDeptCodeById() {
		$arr = $this->service->find(array("id" => $_POST['pid']), null, "exeDeptCode");
		echo $arr['exeDeptCode'];
	}

	/**
	 * 查看产品历史
	 */
	function c_toShowHistory() {
		$this->view('showHistory');
	}

	/**
	 * 获取产品的类型
	 */
	function c_getProType() {
		echo util_jsonUtil::encode($this->service->getProType_d($_POST['id']));
	}
}