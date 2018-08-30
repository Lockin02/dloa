<?php


/**
 * ��Ʊ�Ǽ�model����
 */
class model_stock_picking_pickingapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_pickingapply";
		$this->sql_map = "stock/picking/pickingapplySql.php";
		parent :: __construct();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/
	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��ӷ��÷�Ʊ
	 */
	function add_d($pickingapply) {
		$pickingapply['ExaStatus'] = '������';
		$pickingapply['status'] = '���ύ';
		try {
			$this->start_d();
			$id = parent :: add_d($pickingapply, true);
			if (is_array($pickingapply['pickingapplyDetail'])) {
				$detailDao = new model_stock_picking_pickingapplyDetail();
				foreach ($pickingapply['pickingapplyDetail'] as $pickingapplyDetail) {
					$pickingapplyDetail['pickingId'] = $id;
					if(!empty($pickingapplyDetail['productName'])){
						$detailDao->add_d($pickingapplyDetail);
					}
				}
			}
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/*
	 * �༭���÷�Ʊ
	 */
	function edit_d($picking) {
		try {
			$this->start_d();
			$detailDao = new model_stock_picking_pickingapplyDetail();
			//ɾ�����÷��������ķ�����Ŀ
			$detailDao->deleteDetailByPickingId($picking['id']);
			//��ӷ��÷�Ʊ
			if (is_array($picking['pickingapplyDetail'])) {
				foreach ($picking['pickingapplyDetail'] as $pickingapplyDetail) {
					if ($pickingapplyDetail['stockName']) {
						$pickingapplyDetail['pickingId'] = $picking['id'];
						$detailDao->add_d($pickingapplyDetail);
					}
				}
			}
			$picking = parent :: edit_d($picking, true);
			$this->commit_d();
			return $picking;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}



	/**
	 * ��ӷ��÷�Ʊ
	 */
	function reedit_d($pickingapply,$preId) {
		$pickingapply['ExaStatus'] = '������';
		$pickingapply['status'] = '���ύ';
		try {
			$this->start_d();
			$id = parent :: add_d($pickingapply, true);
			if (is_array($pickingapply['pickingapplyDetail'])) {
				$detailDao = new model_stock_picking_pickingapplyDetail();
				foreach ($pickingapply['pickingapplyDetail'] as $pickingapplyDetail) {
					$pickingapplyDetail['pickingId'] = $id;
					if(!empty($pickingapplyDetail['productName'])){
						$detailDao->add_d($pickingapplyDetail);
					}
				}
			}
			$this->setReEditedStatus( $preId );
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}



	/**
	 * ���������±༭�����뵥״̬
	 */
	function setReEditedStatus( $id ){
		$sql = "update oa_stock_pickingapply c set c.status = 'reedit' where c.id = '$id'";
		if( $this->query ( $sql ) ){
			return true;
		}else{
			return false;
		}

	}



	/*
	 * ��ȡ��Ʊ��Ŀ
	 */
	function get_d($id) {
		$detailDao = new model_stock_picking_pickingapplyDetail();
		$details = $detailDao->getDetailByPickingId($id);
		$picking = parent :: get_d($id);
//		echo "<pre>";
//		print_r($picking);
		$picking['pickingapplyDetail'] = $details;
//		echo "<pre>";
//		print_r($picking);
		return $picking;
	}

	/**
	 * ���ݺ�ͬ��Ż�ȡ������Ϣ
	 */
	function getIncomeByContract($contractNumber) {
		$this->searchArr = array (
			"contractNumber" => $contractNumber
		);
		return parent :: list_d();
	}

	function easyEdit($object,$isTrue = true){
		return parent::edit_d($object,$isTrue);
	}
}
?>