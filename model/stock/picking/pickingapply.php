<?php


/**
 * 发票登记model层类
 */
class model_stock_picking_pickingapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_pickingapply";
		$this->sql_map = "stock/picking/pickingapplySql.php";
		parent :: __construct();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/
	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 添加费用发票
	 */
	function add_d($pickingapply) {
		$pickingapply['ExaStatus'] = '待审批';
		$pickingapply['status'] = '待提交';
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
	 * 编辑费用发票
	 */
	function edit_d($picking) {
		try {
			$this->start_d();
			$detailDao = new model_stock_picking_pickingapplyDetail();
			//删除费用发条关联的费用条目
			$detailDao->deleteDetailByPickingId($picking['id']);
			//添加费用发票
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
	 * 添加费用发票
	 */
	function reedit_d($pickingapply,$preId) {
		$pickingapply['ExaStatus'] = '待审批';
		$pickingapply['status'] = '待提交';
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
	 * 设置已重新编辑的申请单状态
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
	 * 获取发票条目
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
	 * 根据合同编号获取到款信息
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