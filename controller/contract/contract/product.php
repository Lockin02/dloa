<?php

/**
 * @author Liub
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:合同 产品清单控制层
 */

class controller_contract_contract_product extends controller_base_action
{

	function __construct() {
		$this->objName = "product";
		$this->objPath = "contract_contract";
		parent::__construct();
	}


	/*
	 * 跳转到合同 产品清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam( $_POST ); //设置前台获取的参数信息


		$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 产品配置iframe
	 */
	function c_toProductIframe() {
		$this->assign('isMoney', isset($_GET['isMoney']) ? $_GET['isMoney'] : 0);
		$this->assign('isSale', isset($_GET['isSale']) ? $_GET['isSale'] : 0);
		$this->assign('isCon', isset($_GET['isCon']) ? $_GET['isCon'] : 0);
		$this->assign('rowNum', isset($_GET['rowNum']) ? $_GET['rowNum'] : 0);
		$this->assign('componentId', isset($_GET['componentId']) ? $_GET['componentId'] : '');
		$this->assign('isFrame', isset($_GET['isFrame']) ? $_GET['isFrame'] : 0);
        $this->assign('notEquSlt', isset($_GET['notEquSlt']) ? $_GET['notEquSlt'] : 0);
        $this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : 0);// 变更合同时所用
		$this->view('productiframe');
	}

	/**
	 * 选择产品信息
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

		// 这里重新查询一下产品名称
		$goodsDao = new model_goods_goods_goodsbaseinfo();
		$goodsInfo = $goodsDao->find(array('id' => $_GET['goodsId']), null, 'goodsName');
		$this->assign('goodsName', $goodsInfo['goodsName']);


		$this->view('selectproductinfo');
	}

	/**
	 * 数据解析
	 */
	function c_toResolve() {
		$this->service->resolve_d($_GET['id']);
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		// 查询产品信息
		$rows = $service->dealProduct_d($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonLimit() {
		$service = $this->service;

		//关键人员信息获取
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);


		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		if ($rows) {
			//变更数据处理
			$rows = $service->dealArr_d($rows);

			//权限处理
			if ($createId != $_SESSION ['USER_ID'] && $prinvipalId != $_SESSION ['USER_ID'] && $areaPrincipalId != $_SESSION ['USER_ID']) {
				$rows = $this->service->filterWithoutField('产品金额', $rows, 'keyList', array('price', 'money'), 'contract_contract_contract');
			}
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}


	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonCost() {
		$service = $this->service;

		//关键人员信息获取
		$createId = $_POST['createId'];
		$prinvipalId = $_POST['prinvipalId'];
		$areaPrincipalId = $_POST['areaPrincipalId'];
		unset($_POST['createId']);
		unset($_POST['prinvipalId']);
		unset($_POST['areaPrincipalId']);
		$isApp = $_GET['isApp'];

		$service->getParam($_REQUEST);
		$rows = $service->list_d();

		//成本确认权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		if ($isApp == '0') {
			$costLimit = $sysLimit['成本确认'];
		} else {
			$costLimit = $sysLimit['成本确认审核'];
		}

		$costLimitArr = explode(",", $costLimit);

		if ($rows) {
			//查找产品类型
			foreach ($rows as $key => $val) {
				$sql = "select id,parentId from oa_goods_type where id in(select goodsTypeId from  oa_goods_base_info where id = '" . $val['conProductId'] . "')";
				$goodsIdArr = $this->service->_db->getArray($sql);
				if ($goodsIdArr[0]['parentId'] != "-1") {
					//判断如果不为根节点进行第二次查找
					$sqlB = "select a.id as pid from oa_goods_type a INNER JOIN(select * from oa_goods_type " .
						"where id = '" . $goodsIdArr[0]['id'] . "') b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
					$goodsIdArrB = $this->service->_db->getArray($sqlB);
					$goodsTypeId = $goodsIdArrB[0]['pid'];
				} else {
					$goodsTypeId = $goodsIdArr[0]['id'];
				}
				$rows[$key]['goodsTypeId'] = $goodsTypeId;
				//产品线
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
			//重排KEY值
			$rows = array_values($rows);

			//变更数据处理
			$rows = $service->dealArr_d($rows);
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}


	/**
	 * 获取带有变更标识的合同产品信息
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
	 * 可编辑表格 根据合同id获取产品下拉
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