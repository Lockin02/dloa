<?php

/**
 * @author Liub
 * @Date 2012��3��8�� 14:13:30
 * @version 1.0
 * @description:��ͬ ��Ʒ�嵥���Ʋ�
 */

class controller_contract_contract_product extends controller_base_action
{

	function __construct() {
		$this->objName = "product";
		$this->objPath = "contract_contract";
		parent::__construct();
	}


	/*
	 * ��ת����ͬ ��Ʒ�嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��Ʒ����iframe
	 */
	function c_toProductIframe() {
		$this->assign('isMoney', isset($_GET['isMoney']) ? $_GET['isMoney'] : 0);
		$this->assign('isSale', isset($_GET['isSale']) ? $_GET['isSale'] : 0);
		$this->assign('isCon', isset($_GET['isCon']) ? $_GET['isCon'] : 0);
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
		$this->assign('isFrame', isset($_GET['isFrame']) ? $_GET['isFrame'] : 0);
        $this->assign('notEquSlt', isset($_GET['notEquSlt']) ? $_GET['notEquSlt'] : 0);
        $this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : 0);// �����ͬʱ����
		$this->view('productiframe');
	}

	/**
	 * ѡ���Ʒ��Ϣ
	 */
	function c_toSetProductInfo() {
		$cacheId = isset($_GET ['cacheId']) ? $_GET ['cacheId'] : "";
		$isMoney = isset($_GET ['isMoney']) ? $_GET ['isMoney'] : 0;
		$isSale = isset($_GET['isSale']) ? $_GET['isSale'] : 0;
		$isCon = isset($_GET['isCon']) ? $_GET['isCon'] : 0;
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
		$this->assign('isFrame', isset($_GET['isFrame']) ? $_GET['isFrame'] : 0);
        $this->assign('notEquSlt', isset($_GET['notEquSlt']) ? $_GET['notEquSlt'] : 0);
		$exeDeptName = isset($_GET['exeDeptName']) ? $_GET['exeDeptName'] : 0;
		$exeDeptCode = isset($_GET['exeDeptCode']) ? $_GET['exeDeptCode'] : 0;
        $typeId = isset($_GET['typeId']) ? $_GET['typeId'] : 0;

        $this->assign('typeId', $typeId);
		$this->assign('cacheId', $cacheId);

		if (!isset($_GET['number'])) {
			$_GET['number'] = '';
		}
		if (!isset($_GET['price'])) {
			$_GET['price'] = '';
		}
		if (!isset($_GET['money'])) {
			$_GET['money'] = '';
		}
		$this->assignFunc($_GET);
		$this->assign('isMoney', $isMoney);
		$this->assign('isSale', $isSale);
		$this->assign('isCon', $isCon);
		$this->assign("exeDeptName", $exeDeptName);
		$this->assign("exeDeptCode", $exeDeptCode);

		// �������²�ѯһ�²�Ʒ����
		$goodsDao = new model_goods_goods_goodsbaseinfo();
		$goodsInfo = $goodsDao->find(array('id' => $_GET['goodsId']), null, 'goodsName');
		$this->assign('goodsName', $goodsInfo['goodsName']);


		$this->view('selectproductinfo');
	}

	/**
	 * ���ݽ���
	 */
	function c_toResolve() {
		$this->service->resolve_d($_GET['id']);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		// ��ѯ��Ʒ��Ϣ
		$rows = $service->dealProduct_d($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonLimit() {
		$service = $this->service;

		//�ؼ���Ա��Ϣ��ȡ
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);


		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		if ($rows) {
			//������ݴ���
			$rows = $service->dealArr_d($rows);

			//Ȩ�޴���
			if ($createId != $_SESSION ['USER_ID'] && $prinvipalId != $_SESSION ['USER_ID'] && $areaPrincipalId != $_SESSION ['USER_ID']) {
				$rows = $this->service->filterWithoutField('��Ʒ���', $rows, 'keyList', array('price', 'money'), 'contract_contract_contract');
			}
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}


	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonCost() {
		$service = $this->service;

		//�ؼ���Ա��Ϣ��ȡ
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);
		$isApp = $_GET['isApp'];

		$service->getParam($_REQUEST);
		$rows = $service->list_d();

		//�ɱ�ȷ��Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		if ($isApp == '0') {
			$costLimit = $sysLimit['�ɱ�ȷ��'];
		} else {
			$costLimit = $sysLimit['�ɱ�ȷ�����'];
		}

		$costLimitArr = explode(",", $costLimit);

		if ($rows) {
			//���Ҳ�Ʒ����
			foreach ($rows as $key => $val) {
				$sql = "select id,parentId from oa_goods_type where id in(select goodsTypeId from  oa_goods_base_info where id = '" . $val['conProductId'] . "')";
				$goodsIdArr = $this->service->_db->getArray($sql);
				if ($goodsIdArr[0]['parentId'] != "-1") {
					//�ж������Ϊ���ڵ���еڶ��β���
					$sqlB = "select a.id as pid from oa_goods_type a INNER JOIN(select * from oa_goods_type " .
						"where id = '" . $goodsIdArr[0]['id'] . "') b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
					$goodsIdArrB = $this->service->_db->getArray($sqlB);
					$goodsTypeId = $goodsIdArrB[0]['pid'];
				} else {
					$goodsTypeId = $goodsIdArr[0]['id'];
				}
				$rows[$key]['goodsTypeId'] = $goodsTypeId;
				//��Ʒ��
				$sqlf = "select newProLineName,newProLineCode from oa_contract_product where id = '" . $val['id'] . "'";
				$exeDeptNameArr = $this->service->_db->getArray($sqlf);
				if (!in_array(";;", $costLimitArr)) {
					if (in_array($exeDeptNameArr[0]['newProLineCode'], $costLimitArr)) {
						$rows[$key]['newProLineName'] = $exeDeptNameArr[0]['newProLineName'];
					} else {
						unset($rows[$key]);
					}
				} else {
					$rows[$key]['newProLineName'] = $exeDeptNameArr[0]['newProLineName'];
				}
			}
			//����KEYֵ
			$rows = array_values($rows);

			//������ݴ���
			$rows = $service->dealArr_d($rows);
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}


	/**
	 * ��ȡ���б����ʶ�ĺ�ͬ��Ʒ��Ϣ
	 */
	function c_getChangeProductList() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$list = $this->service->getChangeProductList_d($_REQUEST['contractId'], $_POST['isTemp']);
		//		echo "<pre>";
		//		print_r($list);
		echo util_jsonUtil::encode($list);
	}

	/**
	 * �ɱ༭��� ���ݺ�ͬid��ȡ��Ʒ����
	 */
	function c_getPorductByidGrid() {
		$cid = $_POST['cid'];
		$isTemp = $_POST['isTemp'];
		$sql = "select p.conProductName as name,p.conProductId as value from oa_contract_product p
			left join  oa_goods_base_info i on p.conProductId = i.id
			where p.contractId='" . $cid . "' and p.isTemp=$isTemp and p.isDel=0 order by i.exedeptcode";

		$rows = $this->service->_db->getArray($sql);
		echo util_jsonUtil::encode($rows);
	}


	/**
	 * Generates an UUID
	 *
	 * @author     Anis uddin Ahmad
	 * @param      string an optional prefix
	 * @return     string the formatted uuid
	 */
	function c_uuid($prefix = '') {
		$chars = md5(uniqid(mt_rand(), true));
		$uuid = substr($chars, 0, 8) . '-';
		$uuid .= substr($chars, 8, 4) . '-';
		$uuid .= substr($chars, 12, 4) . '-';
		$uuid .= substr($chars, 16, 4) . '-';
		$uuid .= substr($chars, 20, 12);
		echo $prefix . $uuid;
	}

}