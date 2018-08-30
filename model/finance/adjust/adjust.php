<?php

/**
 * @author Show
 * @Date 2011��1��13�� ������ 17:22:36
 * @version 1.0
 * @description:��� Model��
 */
class model_finance_adjust_adjust extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_finance_adjustment";
		$this->sql_map = "finance/adjust/adjustSql.php";
		parent::__construct();
	}

	/**
	 * ��д��ӷ���
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
		$codeRuleDao = new model_common_codeRule();
		$object['adjustCode'] = $codeRuleDao->financeCode($this->tbl_name, 'DLBC');
		$object['status'] = 'CGFPZT-YGJ';
		return parent::add_d($object, true);
	}

	/**
	 * ��д�ݹ��ػ�
	 * @param $object
	 * @param $releaseArr
	 * @param $relatedId
	 * @param $formDate
	 * @throws Exception
	 */
	function addInRelease_d($object, $releaseArr, $relatedId, $formDate) {
		$adjustDetailDao = new model_finance_adjust_adjustdetail();
		$codeRuleDao = new model_common_codeRule();
		$inRows = array();
		$inRows['adjustCode'] = $codeRuleDao->financeCode($this->tbl_name, 'DLBC');
		$inRows['status'] = 'CGFPZT-WSH';
		$inRows['supplierName'] = $object['supplierName'];
		$inRows['supplierId'] = $object['supplierId'];
		$inRows['relatedId'] = $relatedId;
		$inRows['formDate'] = $formDate;
		try {
			$newId = parent::add_d($inRows, true);

			$formDiffer = $adjustDetailDao->batchAdd_d($releaseArr, $newId);

			$this->edit_d(array('id' => $newId, 'amount' => $formDiffer));

		} catch (exception $e) {
			throw $e;
		}
	}

	//�⹺��������Ӳ��
	//������Ƕ�ά����
	//һ�ж�Ӧһ�����ӱ���Ϣ
	function addForCal_d($object, $relatedId, $formDate) {
		if (empty($object)) {
			return false;
		}
		//		print_r($object);
		$adjustDetailDao = new model_finance_adjust_adjustdetail();
		$codeRuleDao = new model_common_codeRule();
		$inRows = array();
		$inRows['adjustCode'] = $codeRuleDao->financeCode($this->tbl_name, 'DLBC');
		$inRows['status'] = 'CGFPZT-WSH';
		$inRows['supplierName'] = $object[0]['supplierName'];
		$inRows['supplierId'] = $object[0]['supplierId'];
		$inRows['relatedId'] = $relatedId;
		$inRows['formDate'] = $formDate;
		//		print_r($inRows);
		try {
			$newId = parent::add_d($inRows, true);

			$formDiffer = $adjustDetailDao->batchAdd_d($object, $newId);

			$this->edit_d(array('id' => $newId, 'amount' => $formDiffer));
			return true;
		} catch (exception $e) {
			throw $e;
		}
	}

	/**
	 * ��дget_d
	 * @param $id
	 * @return bool|mixed
	 */
	function get_d($id) {
		$adjustDetailDao = new model_finance_adjust_adjustdetail();
		$rows = parent::get_d($id);
		$rows['adjustDetail'] = $adjustDetailDao->showDrtail($adjustDetailDao->getRows_d($id));
		return $rows;
	}

	/**
	 * �����״̬�ĳ��Ѻ���
	 * @return mixed
	 */
	function updateAdjustStatus_d() {
		return $this->query("update " . $this->tbl_name . " set status = 'CGFPZT-YHS' where status = 'CGFPZT-WHS'");
	}

	/**
	 * ��ȡ�����Ѿ����в���Ĺ���id
	 * @param $thisYear
	 * @param $thisMonth
	 * @return null
	 */
	function getAdjustRelatedIds_d($thisYear, $thisMonth) {
		$sql = "select GROUP_CONCAT(cast(relatedId as char(100))) as relatedId
			from oa_finance_adjustment where year(formDate) = $thisYear and month(formDate) = $thisMonth";
		$rs = $this->_db->get_one($sql);
		if (is_array($rs)) {
			return $rs['relatedId'];
		} else {
			return null;
		}
	}
}