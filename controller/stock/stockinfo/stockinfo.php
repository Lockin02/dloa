<?php

/**
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_stock_stockinfo_stockinfo extends controller_base_action
{

	function __construct() {
		$this->objName = "stockinfo";
		$this->objPath = "stock_stockinfo";
		parent::__construct();
	}

	function c_page() {
		$this->display('list');
	}

	/**
	 * 新增仓库基本信息
	 */
	function c_toAdd() {
		$this->showDatadicts(array('stockType' => 'CKLX'));
		$this->showDatadicts(array('stockUseCode' => 'CKYT'));

		// 获取归属公司名称
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->view('add');

	}

	/**
	 * 修改仓库信息页面
	 */
	function c_init() {
		$stockinfo = $this->service->get_d($_GET ['id']);
		foreach ($stockinfo as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array('stockType' => 'CKLX'), $stockinfo ['stockType']);
		$this->showDatadicts(array('stockUseCode' => 'CKYT'), $stockinfo ['stockUseCode']);
		$this->display('edit');

	}

	/**
	 * 查看仓库信息页面
	 */
	function c_view() {
		$stockinfo = $this->service->get_d($_GET ['id']);
		foreach ($stockinfo as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->show->assign("stockType", $this->getDataNameByCode($stockinfo ['stockType']));
		$this->show->assign("stockUseCode", $this->getDataNameByCode($stockinfo ['stockUseCode']));
		$this->display('view');

	}

	/**
	 * 校验仓库代码是否重复
	 */
	function c_checkStockCode() {
		$stockCode = isset ($_GET ['stockCode']) ? $_GET ['stockCode'] : false;
		$id = isset ($_GET ['id']) ? $_GET ['id'] : null;
		$searchArr = array("nstockCode" => $stockCode);
		$isRepeat = $this->service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 校验仓库名称是否重复
	 */
	function c_checkStockName() {
		$stockName = isset ($_GET ['stockName']) ? $_GET ['stockName'] : null;
		$id = isset ($_GET ['id']) ? $_GET ['id'] : null;
		$searchArr = array("nstockName" => $stockName);
		$isRepeat = $this->service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 删除仓库，并返回执行结果
	 */
	function c_ajaxdeleteStock() {
		$service = $this->service;
		$id = isset ($_POST ['id']) ? $_POST ['id'] : null;
		if ($service->checkDeleteStock($id)) {
			if ($service->deletes_d($id))
				echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * @author huangzf
	 * 获取把仓库信息组成树Json数据
	 */
	function c_getTreeStock() {
		$service = $this->service;
		$service->sort = 'stockCode';
		$service->asc = false;
		$rows = $service->listBySqlId('select_treejson');
		$jsonResult = array();
		$jsonTemp = array();
		if (is_array($rows)) {
			foreach ($rows as $val) {
				$val ['parentId'] = -1;
				array_push($jsonTemp, $val);
			}
		}
		array_push($jsonResult, array("id" => -1, "name" => "所有仓库", 'isParent' => 1, "nodes" => $jsonTemp));
		echo util_jsonUtil::encode($jsonResult);
	}

	/**
	 * 根据parentId获取物料树形数据
	 */
	function c_getTreeDataByParentId() {
		$service = $this->service;
		$parentId = isset ($_POST ['id']) ? $_POST ['id'] : -1;
		$service->searchArr ['parentId'] = $parentId;
		$service->asc = false;
		$rows = $service->listBySqlId('select_treeinfo');
		echo util_jsonUtil::encode($rows);
	}

	/**
	 *
	 * 上传仓库信息EXCEL
	 */
	function c_toUploadExcel() {
		$this->display("import");
	}

	/**
	 *
	 *导入EXCEL中上传仓库信息
	 */
	function c_importStock() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			$resultArr = $service->importStockInfo($excelData);
			if (is_array($resultArr))
				echo util_excelUtil::showResult($resultArr, "仓库基本信息导入结果", array("仓库代码", "结果"));
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_stockJson() {
		$service = $this->service;
		$content = $service->this_limit;
		$_REQUEST['content'] = $content['仓库权限'];
		$service->getParam($_REQUEST);
		$service->searchArr["ids"] = $content['仓库权限'];
		// 		$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 验证库存数量
	 */
	function c_toCheckStockBalance() {
		$this->view('checkstockbalance');
	}

	/**
	 * 验证库存数量
	 */
	function c_checkStockBalance() {
		$resultArr = $this->service->checkStockBalance_d();
		if ($resultArr) {
			//如果有错误信息，那么就呈现最终信息
			$title = '项目导入结果列表';
			$thead = array('数据信息', '导入结果');
			echo util_excelUtil::showResult($resultArr, $title, $thead);
		} else {
			echo '库存匹配完成，没有发现存在差异的数据';
		}
	}
}