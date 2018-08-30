<?php
/**
 * @author Administrator
 * @Date 2013��10��9�� 9:41:20
 * @version 1.0
 * @description:���������� Model��
 */
 class model_report_report_produce  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_report_produce";
		$this->sql_map = "report/report/produceSql.php";
		parent::__construct ();
	}



	/**
	 * ��дadd_d����

	 */
	function add_d($object) {

		try {
			$this->start_d();
		    //����������Ϣ
			$newId = parent :: add_d($object,true);
			$stockArr=array();
			$outStockArr=array();
			$infoArr = array();
			if(!empty($object['stock'])){
				$stockArr = $object['stock'];
			}
			if(!empty($object['outstock'])){
				$outStockArr = $object['outstock'];
			}
			$infoArr = array_merge($stockArr,$outStockArr);
			//����ӱ���Ϣ
			if (!empty ($infoArr)) {
				foreach($infoArr as $k => $v){
					if($v['proType'] == '�п��'){
						$infoArr[$k]['total'] = $v['proTime'] + $v['testTime'] + $v['packageTime'];
					}else{
						$infoArr[$k]['total'] = $v['needNum'] + $v['proTime'] + $v['testTime'] + $v['packageTime'];
					}
				}
				$infoDao = new model_report_report_produceinfo();
				$infoDao->createBatch($infoArr, array (
					'produceId' => $newId
				));
			}
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//�޸�������Ϣ
			parent :: edit_d($object, true);

			$Id = $object['id'];
			//����ӱ���Ϣ
			$infoDao = new model_report_report_produceinfo();
			$infoDao->delete(array (
				'produceId' => $Id
			));
			$stockArr=array();
			$outStockArr=array();
			$infoArr = array();
			if(!empty($object['stock'])){
				$stockArr = $object['stock'];
			}
			if(!empty($object['outstock'])){
				$outStockArr = $object['outstock'];
			}
			$infoArr = array_merge($stockArr,$outStockArr);
			foreach ($infoArr as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($infoArr[$k]);
				}
				if($v['proType'] == '�п��'){
						$infoArr[$k]['total'] = $v['proTime'] + $v['testTime'] + $v['packageTime'];
					}else{
						$infoArr[$k]['total'] = $v['needNum'] + $v['proTime'] + $v['testTime'] + $v['packageTime'];
					}
			}
			$infoDao->createBatch($infoArr, array (
				'produceId' => $Id
			));
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
 }
?>