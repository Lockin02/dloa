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
	 * �����ֿ������Ϣ
	 */
	function c_toAdd() {
		$this->showDatadicts(array('stockType' => 'CKLX'));
		$this->showDatadicts(array('stockUseCode' => 'CKYT'));

		// ��ȡ������˾����
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->view('add');

	}

	/**
	 * �޸Ĳֿ���Ϣҳ��
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
	 * �鿴�ֿ���Ϣҳ��
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
	 * У��ֿ�����Ƿ��ظ�
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
	 * У��ֿ������Ƿ��ظ�
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
	 * ɾ���ֿ⣬������ִ�н��
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
	 * ��ȡ�Ѳֿ���Ϣ�����Json����
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
		array_push($jsonResult, array("id" => -1, "name" => "���вֿ�", 'isParent' => 1, "nodes" => $jsonTemp));
		echo util_jsonUtil::encode($jsonResult);
	}

	/**
	 * ����parentId��ȡ������������
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
	 * �ϴ��ֿ���ϢEXCEL
	 */
	function c_toUploadExcel() {
		$this->display("import");
	}

	/**
	 *
	 *����EXCEL���ϴ��ֿ���Ϣ
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
				echo util_excelUtil::showResult($resultArr, "�ֿ������Ϣ������", array("�ֿ����", "���"));
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_stockJson() {
		$service = $this->service;
		$content = $service->this_limit;
		$_REQUEST['content'] = $content['�ֿ�Ȩ��'];
		$service->getParam($_REQUEST);
		$service->searchArr["ids"] = $content['�ֿ�Ȩ��'];
		// 		$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��֤�������
	 */
	function c_toCheckStockBalance() {
		$this->view('checkstockbalance');
	}

	/**
	 * ��֤�������
	 */
	function c_checkStockBalance() {
		$resultArr = $this->service->checkStockBalance_d();
		if ($resultArr) {
			//����д�����Ϣ����ô�ͳ���������Ϣ
			$title = '��Ŀ�������б�';
			$thead = array('������Ϣ', '������');
			echo util_excelUtil::showResult($resultArr, $title, $thead);
		} else {
			echo '���ƥ����ɣ�û�з��ִ��ڲ��������';
		}
	}
}