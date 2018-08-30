<?php
/**
 * 采购申请单产品清单model
 */
class model_purchase_apply_applyequipment extends model_base{

	//状态位
	private $status;

	function __construct() {
		$this->tbl_name = "oa_purch_apply_equ";
		$this->sql_map = "purchase/apply/applyequipmentSql.php";
		parent :: __construct();
		$this->status = array(
			0 => array(
				"stateEName" => "execution",
				"stateCName" => "执行",
				"stateVal" => "1"
			),
			1 => array(
				"stateEName" => "locking",
				"stateCName" => "锁定",
				"stateVal" => "2"
			),
			2 => array(
				"stateEName" => "end",
				"stateCName" => "完成",
				"stateVal" => "3"
			),
			3 => array(
				"stateEName" => "close",
				"stateCName" => "关闭",
				"stateVal" => "4"
			)
		);
	}

/*****************************************显示分割线********************************************/

	/**
	 * 通过value查找状态
	 * $stateVal Key值
	 * return 中文名称
	 */
	function statusToVal($stateVal){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 通过状态查找value
	 * $stateSta 英文名
	 * return key值
	 */
	function statusToSta($stateSta){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

/*****************************************显示分割线********************************************/

	 /**
	  * 采购申请单设备-采购申请单不分页列表
	  */
//	function listEqu_d($stockApplyId){
//		$searchArr = array (
//			"stockApplyId" => $stockApplyId
//		);
//		$this->__SET('groupBy', "p.productNumb,p.storageId");
//		$this->__SET('sort', "p.id");
//		$this->__SET('searchArr', $searchArr);
//		$rows = $this->listBySqlId("equipment_list");
//		$i = 0;
//		foreach($rows as $key => $val){
//			$this->resetParam();
//			$searchArr = array (
//				"productNumb" => $val['productNumb'],
//				"storageId" => $val['storageId'],
//				"stockApplyId" => $stockApplyId
//			);
//			$this->__SET('groupBy', "p.id");
//			$this->__SET('sort', "P.id");
//			$this->__SET('searchArr', $searchArr);
//			$chiRows = $this->listBySqlId("equipment_list");
//			$rows[$i]['childArr']=$chiRows;
//			++$i;
//		}
//		return $rows;
//	}

	/**
	 * @exclude 获取未下达到货单的产品数量
	 * @author ouyang
	 * @param $purAppId 申请单Id
	 * @param $productId 设备产品Id
	 * @return
	 * @version 2010-8-10 下午06:51:32
	 */
	function getNotArrPurPros($purAppId,$productId=false){
		/*edit by huangzf 20100103*/
		$searchArr = array (
					"basicId" => $purAppId,
//					"deviceIsUse" => "1",
//					"status" => $this->statusToSta("execution")
		);
		if($productId){
			$searchArr['productId']=$productId;
		}
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list1");
//		echo "<pre>";
//		print_r($rows);
		if($rows){
			$rtArray = false;
			$i = 0;
			foreach($rows as $key => $val){
				if( !isset( $val['amountAll']) || $val['amountAll']==0 ){
					$allAmount = 0;
				}else{
					$allAmount = $val['amountAll'];
				}

				if( !isset( $val['amountIssued']) || $val['amountIssued']==0 ){
					$issuedAmount = 0;
				}else{
					$issuedAmount = $val['amountIssued'];
				}

				if( $allAmount>$issuedAmount ){
					$rtArray[$i] = $val;
					++$i;
				}
			}
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************显示分割线********************************************/

	/**
	 * 对外接口
	 * 如果$lastIssueNum有值的话，把原数据的数量-$lastIssueNum+&issueNum,没有的话把&issueNum跟原数据的数量相加
	 */
	function updateAmountIssued($id,$issuedNum,$lastIssueNum=false){
		$sql="";
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set amountIssued=amountIssued + $issuedNum - $lastIssueNum where id='$id' ";
			}else{
				$sql = " update ".$this->tbl_name." set amountIssued=amountIssued + $issuedNum where id='$id' ";
			}

//			$searchArr = array (
//						"id" => $id
//			);
//			$this->__SET('searchArr', $searchArr);
//			$rows = $this->listBySqlId("equ_list1");
//			if($rows){
//				$planDao = new model_purchase_plan_basic();
//				$arrPurchaseType = $planDao ->purchTypeToArr( $rows["0"]["typeTabName"] );
//				$objModelName = $arrPurchaseType["objectEquName"];
//				$funByWayAmount = $arrPurchaseType["funByWayAmount"];
//				$objDao = new $objModelName();
//				$issuedAmount = $issuedNum - $lastIssueNum;
//				$objDao->$funByWayAmount($rows["0"]["typeEquTabId"],-$issuedAmount);
//			}
			return parent::query($sql);
		}
	}

	/**
	 * 修改业务对象在途数量
	 */
	function funByWayAmountAll($basicId){
		$searchArr = array (
					"basicId" => $basicId,
//					"deviceIsUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list");
		if($rows){
			$planDao = new model_purchase_plan_basic();
			foreach( $rows as $key => $val ){
				$id = $val["id"];
				$amountAll = $val["amountAll"];
				$arrPurchaseType = $planDao ->purchTypeToArr( $val["typeTabName"] );
				$objModelName = $arrPurchaseType["objectEquName"];
				$funByWayAmount = $arrPurchaseType["funByWayAmount"];
				$objDao = new $objModelName();
				$objDao->$funByWayAmount($rows["0"]["typeEquTabId"],$amountAll);
			}
		}
	}

	/**
	 * 是否可变更
	 */
	function canChange($taskNumb){
//		$searchArr = array (
//			"taskNumb" => $taskNumb,
//			"deviceIsUse" => "1"
//		);
//		$this->__SET('searchArr', $searchArr);
//		$rows = $this->listBySqlId("equipment_list");
//		if($rows){
//			$applyEquDao = new model_purchase_apply_applyequipment();
//			$returnVal = false;
//			foreach( $rows as $key => $val ){
//				$returnVal = $applyEquDao->canChange($val['deviceNumb']);
//			}
//			return false;
//		}else{
//			return true;
//		}
	}

	/**
	 * 通过采购计划编号加锁
	 */
	function lockingByPlan_d($planNumb){
//		try {
//			$this->start_d ();
//			$basicDao = new model_purchase_apply_applybasic();
//			$sql = "select b.state as state,e.id as id from oa_purch_apply_basic b,oa_purch_apply_equ e where b.applyNumb=e.basicNumb and b.isUse='1' and e.plantNumb='$planNumb' and e.deviceIsUse='1' and e.status='".$this->statusToSta('execution')."' ";
//			$arr = $basicDao->query($sql);
//			foreach($arr as $key => $val){
//				if($val['state']!=$this->stateToSta['execute']&&$val['state']!=$this->stateToSta['end']&&$val['state']!=$this->stateToSta['close']){
//					$updateArr = array(
//						"status" => $this->statusToSta('locking')
//					);
//					$whereArr = array(
//						"id" => $val['id']
//					);
//					$this->update($whereArr, $updateArr);
//				}
//			}
//			$this->commit_d ();
//		} catch ( Exception $e ) {
//			$this->rollBack ();
////			echo "<pre>";
////			print_r($e);
//			throw new Exception($e);
//		}
	}

/*****************************************显示分割线********************************************/

	/**
	 * @exclude 关闭采购申请单所有设备 维护采购任务设备下达数量
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午05:37:46
	 */
	function close_d($basicNumb){
		$searchArr = array (
					"basicNumb" => $basicNumb,
					"deviceIsUse" => "1",
					//"statusArr" => $this->statusToSta('execution')
					"status" => $this->statusToSta('execution')
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list");
		if($rows){
			$taskEquDao = new model_purchase_task_equipment();
			foreach($rows as $key =>$val){
				if( !isset($val['amountAll'])){
					$val['amountAll'] =0;
				}
				$taskEquDao->updateAmountIssued( $val['taskEquNumb'],0,$val['amountAll'] );

			}
		}
	}

	/**
	 * @exclude 删除采购申请单所有设备 维护采购任务设备下达数量
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午05:37:46
	 */
	function del_d($id){
		$searchArr = array (
					"basicId" => $id,
//					"deviceIsUse" => "1",
					//"statusArr" => $this->statusToSta('execution')
					"status" => $this->statusToSta('execution')
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list");
		if($rows){
			$taskEquDao = new model_purchase_task_equipment();
			foreach($rows as $key =>$val){
				if( !isset($val['amountAll'])){
					$val['amountAll'] =0;
				}
				$taskEquDao->updateAmountIssued( $val['taskEquNumb'],0,$val['amountAll'] );

			}
		}
	}

	/**
	 * @exclude 通过采购任务编号关闭所有采购申请单
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午10:26:08
	 */
	function closeAllApply_d ($taskNumb) {
		$searchArr = array (
					"taskNumb" => $taskNumb,
//					"deviceIsUse" => "1",
					//"statusArr" => $this->statusToSta('execution')
					"status" => $this->statusToSta('execution')
		);
		$this->__SET('searchArr', $searchArr);
		$arr = $this->listBySqlId("equ_list");
		if($arr){
			$basicDao = new model_purchase_apply_applybasic();
			foreach ( $arr as $key => $val ){
				$basicDao->close_d($val['basicNumb'],false);
			}
		}
	}

/*****************************************显示分割线********************************************/

	/**
	 * @exclude 通过采购申请单Id判断所属设备是否已下达完成
	 * @author ouyang
	 * @param 采购申请单Id
	 * @return bool
	 * @version 2010-8-10 下午04:37:54
	 */
	function endByBasicId_d ($basicNumb) {
		$searchArr = array (
					"basicNumb" => $basicNumb,
					"deviceIsUse" => "1",
					//"statusArr" => $this->statusToSta('execution').",".$this->statusToSta('locking')
					"status" => $this->statusToSta('execution')
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		$returnVal = true;
		if($rows){
			foreach($rows as $key =>$val){
				if( !isset($val['amountAll'])){
					$val['amountAll'] =0;
				}
				if( !isset($val['amountIssued'])){
					$val['amountIssued'] =0;
				}
				if($val['amountAll']>$val['amountIssued']){
					$returnVal = false;
				}
			}
		}
		return $returnVal;
	}

	/**
	 * @exclude 通过任务编号判断是否全部采购申请单完成或关闭
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午11:43:17
	 */
	function findEndObj_d ( $taskNumb ) {
		$searchArr = array (
					"taskNumb" => $taskNumb,
//					"deviceIsUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		$returnVal = true;
		$basicDao = new model_purchase_apply_applybasic();
//		echo "<pre>";
//		print_r($rows);
		foreach($rows as $key => $val){
			$state = $basicDao->getStateByNumb($val['basicNumb']);
//			echo $state;
			if( $state != $basicDao->stateToSta('close') && $state != $basicDao->stateToSta('end') ){
				$returnVal = false;
			}
			echo $returnVal;
		}
		return $returnVal;
	}

	/**
	 * @exclude 得到产品清单未到货数量
	 * @author ouyang
	 * @param 产品清单ID
	 * @return
	 * @version 2010-8-10 下午07:03:50
	 */
	function getAppProNotIssNum_d($purchAppProId){
		$searchArr = array (
					"id" => $purchAppProId
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list");
		$amountAll = $rows['0']['amountAll'];
		$amountIssued = $rows['0']['amountIssued'];
		if(isset($amountIssued)&&$amountIssued!=""){
			return $amountAll-$amountIssued;
		}else{
			return $amountAll;
		}
	}

}
?>