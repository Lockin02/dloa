<?php

/**
 * @author huangzf
 * @Date 2012��3��1�� 20:16:27
 * @version 1.0
 * @description:��Ʒ������Ϣ���Ʋ�
 */

class controller_goods_goods_goodsbaseinfo extends controller_base_action
{

	function __construct() {
		$this->objName = "goodsbaseinfo";
		$this->objPath = "goods_goods";
		parent::__construct();
	}

	/**
	 * ��ת����Ʒ������Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��ѡ���Ʒ�б�
	 */
	function c_toChoosePage() {
		$this->assign('isMoney', isset($_GET['isMoney']) ? $_GET['isMoney'] : 0);
		$this->assign('isSale', isset($_GET['isSale']) ? $_GET['isSale'] : 0);
		$this->assign('isCon', isset($_GET['isCon']) ? $_GET['isCon'] : 0);
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
		$this->assign('isFrame', isset($_GET['isFrame']) ? $_GET['isFrame'] : 0);
        $this->assign('notEquSlt', isset($_GET['notEquSlt']) ? $_GET['notEquSlt'] : 0);
        $this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : 0);// �����ͬʱ����
		$this->view('choose-list');
	}

	/**
	 * ѡ���б�
	 */
	function c_pageSelect() {
		$this->view('list-select');
	}

	/**
	 * ��ת��������Ʒҳ��
	 */
	function c_toAddWhole() {
		$this->showDatadicts(array('exeDeptName' => 'GCSCX'));
		$parentName = isset ($_GET['parentName']) ? $_GET['parentName'] : "�������Ʒ";
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : "11";
		$this->showDatadicts(array('useStatus' => 'WLSTATUS'));
		$this->assign("parentId", $parentId);
		$this->assign("parentName", $parentName);
		$this->view('addWhole');
	}

	/**
	 * ��ת��������Ʒ������Ϣҳ��
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
	 * ��ת���༭��Ʒ������Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
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
	 * ��ת���鿴��Ʒ������Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
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
	 *����鿴���ڲ�Ʒ������Ϣҳ��
	 */
	function c_HWtoView($id) {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($id);
		$obj['file'] = $this->service->getFilesByObjId($id);
		return util_jsonUtil:: iconvGB2UTFArr($obj);
	}

	/**
	 * ��ת����Ʒ���ҳ��
	 */
	function c_toViewRelation() {
		$msg = $this->service->checkRelateInfo($_GET['id']);
		$this->assign('msg', $msg);
		$this->view("relation");
	}

	/**
	 * ���²�Ʒ��Ϣ
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
	 * ��ȡ���Ϲ���ҵ�����
	 */
	function c_getGoodsRelationArr() {
		include_once("model/goods/goods/goodsRelationTableArr.php");
		echo util_jsonUtil::encode(isset($goodsRelationTableArr) ? $goodsRelationTableArr : '');
	}

	/**
	 * �����������ҵ����Ϣ
	 */
	function c_updateGoodsInfo() {
		$goodsbaseinfo = $_POST['goodsbaseinfo'];
		$relationArr = $_POST['checked']; //ѡ�и��µ�ҵ�����
		if ($_POST['updateType'] == 1) {
			//����id����
			$this->service->updateRelationsById($goodsbaseinfo, $relationArr);
		}
		msg('���³ɹ���');
	}

	/**
	 * ��ȡ���ڲ�Ʒ��������ת��Json���ص�����
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
	 * ajax���ݲ�Ʒid ��ȡ��Ʒ��
	 */
	function c_getExeDeptCodeById() {
		$arr = $this->service->find(array("id" => $_POST['pid']), null, "exeDeptCode");
		echo $arr['exeDeptCode'];
	}

	/**
	 * �鿴��Ʒ��ʷ
	 */
	function c_toShowHistory() {
		$this->view('showHistory');
	}

	/**
	 * ��ȡ��Ʒ������
	 */
	function c_getProType() {
		echo util_jsonUtil::encode($this->service->getProType_d($_POST['id']));
	}
}