<?php


/**
 * 固定资产日常操作公用model层类
 * @author zengzx
 * @since 1.0 - 2011-11-29
 */
class model_asset_daily_dailyCommon extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_relBusiness";
		//		$this->sql_map = "asset/daily/allocationSql.php";
		parent :: __construct();
			$this->relatedClassNameArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
		"oa_asset_allocation" => "asset_daily_allocation", //资产调拨
		"oa_asset_borrow" => "asset_daily_borrow", //资产借用
		"oa_asset_charge" => "asset_daily_charge", //资产领用
		"oa_asset_return" => "asset_daily_return", //资产归还
		"oa_asset_rent" => "asset_daily_rent", //资产租赁
		"oa_asset_keep" => "asset_daily_keep", //资产维保
		"oa_asset_lose" => "asset_daily_lose", //资产遗失

		);
			$this->relatedStrategyArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
		"oa_asset_allocation" => "model_asset_daily_allocation", //资产调拨
		"oa_asset_borrow" => "model_asset_daily_borrow", //资产借用
		"oa_asset_charge" => "model_asset_daily_charge", //资产领用
		"oa_asset_return" => "model_asset_daily_return", //资产归还
		"oa_asset_rent" => "model_asset_daily_rent", //资产租赁
		"oa_asset_keep" => "model_asset_daily_keep", //资产维保
		"oa_asset_lose" => "model_asset_daily_lose", //资产遗失

		);
			$this->relatedEquStrategyArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
		"oa_asset_allocation" => "model_asset_daily_allocationitem", //资产调拨清单
		"oa_asset_borrow" => "model_asset_daily_borrowitem", //资产借用清单
		"oa_asset_charge" => "model_asset_daily_chargeitem", //资产领用清单
		"oa_asset_return" => "model_asset_daily_returnitem", //资产归还清单
		"oa_asset_rent" => "model_asset_daily_rentitem", //资产租赁清单
		"oa_asset_keep" => "model_asset_daily_keepitem", //资产维保清单
		"oa_asset_lose" => "model_asset_daily_loseitem", //资产遗失清单

		);
	}

	/**
	 *  固定资产日常操作审批时执行操作
	 * 2012年3月2日 11:08:26
	 */
	function ctUpdateRelInfoAtAudit($row, $docType) {
		if ($docType == 'oa_asset_lose') {
			$dailyStrategy = $this->relatedStrategyArr[$docType];
			$dailyDao = new $dailyStrategy ();
			//进入各自的业务类里操作
			$dailyObj = $dailyDao->updateRelInfoAtAudit($row);
		}
		return true;
	}

	/**
	 *  固定资产日常操作审批通过后执行操作
	 */
	function ctDealRelInfoAtAudit($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		$relModelObj = $dailyDao->get_d($id);
		if ($relModelObj['ExaStatus'] == '完成') {
			$condition = array (
				'businessCode' => $docType,

			);

			//根据业务类型判断所对应的变动方式
			$relInfo = $this->find($condition);
			$relInfo['businessId'] = $id;
			$relInfo['businessType'] = $relInfo['businessCode'];
			$relInfo['businessCode'] = $relModelObj['billNo'];
			//进入各自的业务类里操作
			$dailyObj = $dailyDao->dealRelInfoAtAudit($id, $relInfo);
			/*邮件发送 调入确认人*/
			$emailDao = new model_common_mail();
			$mailInfo = $relModelObj['recipient'] ." 您好: <br/> 调拨申请人已申请了一个固定资产调拨信息并指定您为调入确认人<br/>单据编号： ".$relModelObj ['billNo']." " ;
	        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"oa_asset_allocation","审批","通过",$relModelObj['recipientId'],$mailInfo);

			return true;
		} else {
			return false;
		}
	}
	/**
	 * 归还资产后修改关联单据资产清单的状态位。
	 */
	function setRelEquReturnStatus($id, $docType, $assetId) {
		$dailyEquStrategy = $this->relatedEquStrategyArr[$docType];
		$dailyEquDao = new $dailyEquStrategy ();
		return $dailyEquDao->setEquStatus($id, $assetId, 1);
	}

	/**
	 * 归还资产后修改关联单据的状态位。
	 */
	function setRelReturnStatus($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		return $dailyDao->setDocStatus($id);
	}

	function setRelEquAllocateStatus($assetId) {
		$allocationRelArr = array (
			'oa_asset_borrow' => 'borrow',
			'oa_asset_charge' => 'charge'
		);
		$alterDao = new model_asset_change_assetchange();
		$alterInfo = $alterDao->getSecondChangeRecord($assetId);
		$docType = $alterInfo['businessType'];
		$docId = $alterInfo['businessId'];
		$objType = array_search($docType, $allocationRelArr);
		if ($objType && $docId) {
			$dailyStrategy = $this->relatedEquStrategyArr[$objType];
			$dailyEquDao = new $dailyStrategy ();
			$dailyEquDao->setEquStatus($docId, $assetId, 3);
			$this->setRelReturnStatus($docId, $objType);
		}
		return true;
	}


	/**
	 *  固定资产日常操作签收后执行操作
	 */
	function ctDealRelInfoAtSign($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		$relModelObj = $dailyDao->get_d($id);
		if ($relModelObj['isSign'] == '1') {
			$condition = array (
				'businessCode' => $docType,

			);
			//根据业务类型判断所对应的变动方式
			$relInfo = $this->find($condition);
			$relInfo['businessId'] = $id;
			$relInfo['businessType'] = $relInfo['businessCode'];
			$relInfo['businessCode'] = $relModelObj['billNo'];
			//进入各自的业务类里操作
			$dailyObj = $dailyDao->dealRelInfoAtAudit($id, $relInfo);
			return true;
		} else {
			return false;
		}
	}

}
?>