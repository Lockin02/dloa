<?php

/**
 * @author huangzf
 * @Date 2013年3月7日 星期四 10:47:28
 * @version 1.0
 * @description:质检申请单 Model层
 */
class model_produce_quality_qualityapply extends model_base
{
	public $applyStrategyArr = array();

	function __construct() {
		$this->tbl_name = "oa_produce_quality_apply";
		$this->sql_map = "produce/quality/qualityapplySql.php";
		//生产申请单策略
		$this->applyStrategyArr = array(
			"ZJSQYDSL" => "model_produce_quality_strategy_purchqualityapply",//采购收料通知
			"ZJSQYDHH" => "model_produce_quality_strategy_exchangequalityapply",//换货收料通知
			"ZJSQYDGH" => "model_produce_quality_strategy_returnqualityapply",//归还收料通知
			"ZJSQYDSC" => "model_produce_quality_strategy_producequalityapply",//生产收料通知
			"ZJSQYDTH" => "model_produce_quality_strategy_salereturnqualityapply",//t退货
            "ZJSQDLBF" => "model_produce_quality_strategy_blockeququalityapply"//呆料报废通知单(PMS2386)
		);
		parent::__construct();
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/****************************** 策略部分 *****************************/
	/**
	 * 根据id获取对应策略
	 */
	function getStrategy_d($id) {
		$obj = $this->find(array('id' => $id), 'relDocType');
		return $this->applyStrategyArr[$obj['relDocType']];
	}

	//获取业务数据
	public function ctGetRelDocInfo($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->getRelDocInfo($relDocId);
	}

	//获取主表对应从表业务数据
	public function ctGetRelDetailInfo($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->getRelDetailInfo($relDocId);
	}

	/**
	 * 新增质检申请时源单据业务处理
	 * @param $istockin 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtAdd(iqualityapply $iqualityapply, $paramArr = false, $relItemArr = false) {
		return $iqualityapply->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * 更新质检明细 - 质检放行使用
	 */
	function ctDealRelItemPass($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelItemPass($relDocId, $relDocItemId, $qualityNum);
	}

    /**
     * 损坏方形
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     * @param iqualityapply $iqualityapply
     */
    function ctDealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
        return $iqualityapply->dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum);
    }

	/**
	 * 更新源单状态 - 质检完成时使用,主要通过判断自身信息更新状态信息
	 */
	function ctDealRelInfoCompleted($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelInfoCompleted($relDocId);
	}

	/**
	 * 质检完毕时处理关联业务
	 * 质检合格时,质检数量等于单次检查数量,所以直接用单词检查数量进行更新
	 */
	function dealRelInfoAtConfirm($id, $itemId, $thisCheckNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode']
		);
		return $istrategy->dealRelInfoAtConfirm($paramArr);
	}

	/**
	 * 质检退回时处理收料单
	 */
	function dealRelInfoAtBack($id, $itemId, $thisCheckNum, $passNum, $receiveNum, $backNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode'],
			'passNum' => $passNum, 'receiveNum' => $receiveNum, 'backNum' => $backNum
		);
		return $istrategy->dealRelInfoAtBack($paramArr);
	}

	/**
	 * 质检退回时处理收料单
	 */
	function dealRelInfoAtReceive($id, $itemId, $thisCheckNum, $passNum, $receiveNum, $backNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode'],
			'passNum' => $passNum, 'receiveNum' => $receiveNum, 'backNum' => $backNum
		);
		return $istrategy->dealRelInfoAtReceive($paramArr);
	}

	/**
	 * 质检撤销
	 * 质检合格时,质检数量等于单次检查数量,所以直接用单词检查数量进行更新
	 */
	function dealRelInfoAtUnconfirm($id, $itemId, $thisCheckNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode']
		);
		return $istrategy->dealRelInfoAtUnconfirm($paramArr);
	}

	/**
	 * 处理质检明细 - 质检申请打回时使用
	 */
	function ctDealRelItemBack($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelItemBack($relDocId, $relDocItemId, $qualityNum);
	}

	/**
	 * 更新源单状态 - 质检申请打回时使用,主要通过判断自身信息更新状态信息
	 */
	function ctDealRelInfoBack($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelInfoBack($relDocId);
	}
	/****************************** 项目配置 *****************************/

	/**
	 * 状态数组
	 */
	public $statusArr = array(
		'0' => '待执行',
		'1' => '部分执行',
		'2' => '执行中',
		'3' => '已关闭'
	);

	//将外部类型转为内部类型
	public function rtStatus($value) {
		if (isset($this->statusArr[$value])) {
			return $this->statusArr[$value];
		} else {
			return $value;
		}
	}

	//邮件获取
	function getMail_d($thisKey) {
		include(WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
			'sendName' => '');
		return $mailArr;
	}

	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			if (is_array($object ['items'])) {
				$this->start_d();
				//质检申请单基本信息
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode("oa_produce_quality_apply", "ZJSQ");

				$id = parent::add_d($object, true);
				$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
				$itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $object ['items']);
				$itemsObjs = $qualityapplyitemDao->saveDelBatch($itemsArr);

				//有源单信息的，进行处理相关业务
				if (!empty ($object ['relDocType']) && !empty ($object ['relDocId'])) {
					$relDocDaoName = $this->applyStrategyArr [$object ['relDocType']];
					$relArr = array("id" => $id, "relDocId" => $object ['relDocId'], "relDocCode" => $object ['relDocCode']);

					$this->ctDealRelInfoAtAdd(new $relDocDaoName (), $relArr, $itemsObjs);
				}

				$this->commit_d();
				return true;
			} else {
				throw new Exception ("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array($object ['items'])) {
				$this->start_d();
				$editResult = parent::edit_d($object, true);
				$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
				$itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $object ['items']);
				$qualityapplyitemDao->saveDelBatch($itemsArr);
				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception ("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d($id);
		$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
		$qualityapplyitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $qualityapplyitemDao->listBySqlId();
		return $object;
	}

	/**
	 * 根据源单检查是否存在质检申请
	 */
	function checkExsitQuality($relDocId, $relDocType) {
		$this->searchArr = array("relDocType" => $relDocType,
			"relDocId" => $relDocId);
		$row = $this->listBySqlId();
		if (is_array($row)) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * 重新计算申请单的状态 -- 根据物料信息计算
	 * @param $id
	 * @return mixed
	 */
	function renewStatus_d($id) {
		//查询质检物料
		$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
		$qualityapplyitemArr = $qualityapplyitemDao->findAll(array('mainId' => $id), null, 'id,status');

		$doingArr = array(); //在建的记录
		$waitingArr = array(); //等待处理的记录
		$doneArr = array(); //处理完毕的记录
		//循环判断状态
		foreach ($qualityapplyitemArr as $key => $val) {
			switch ($val['status']) {
				case "0" :
					array_push($doneArr, $val['id']);
					break;
				case "1" :
					array_push($doingArr, $val['id']);
					break;
				case "2" :
					array_push($doingArr, $val['id']);
					break;
				case "3" :
					array_push($doneArr, $val['id']);
					break;
				case "4" :
					array_push($waitingArr, $val['id']);
					break;
			}
		}

		$status = null;

		//单据未处理
		if (count($waitingArr) == count($qualityapplyitemArr)) {
			$status = "0";
		} elseif (count($doneArr) == count($qualityapplyitemArr)) {//单据已处理
			$status = "3";
		} elseif (count($waitingArr) > 0) {
			$status = "1";
		} else {
			$status = "2";
		}

		//更新数组
		$conditionArr = array("id" => $id);
		$updateArr = array("id" => $id, 'status' => $status);
		$updateArr = $this->addUpdateInfo($updateArr);

		//如果做完了
		if ($status == "3") {
			$updateArr['closeUserName'] = $_SESSION['USERNAME'];
			$updateArr['closeUserId'] = $_SESSION['USER_ID'];
			$updateArr['closeTime'] = date("Y-m-d H:i:s");
		} else {
			$updateArr['closeUserName'] = '';
			$updateArr['closeUserId'] = '';
			$updateArr['closeTime'] = '';
		}

		return $this->update($conditionArr, $updateArr);
	}

	/**
	 * 查询质检申请信息
	 */
	function findQuality_d($relDocItemId) {
		//实例化明细
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->find(array("relDocItemId" => $relDocItemId));
		if ($applyItemObj['status'] != "3") {
			return array('thisType' => 'apply', 'mainId' => $applyItemObj['mainId']);
		} else {
			//质检任务
			$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
			$qualitytaskitemObj = $qualitytaskitemDao->find(array("applyItemId" => $applyItemObj['id']));

			//质检报告
			$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
			$qualityereportequitemObj = $qualityereportequitemDao->find(array("relItemId" => $qualitytaskitemObj['id']));

			return array('thisType' => 'report', 'mainId' => $qualityereportequitemObj['mainId']);
		}
	}

	/**
	 * 判断是否可以撤销
	 */
	function checkCanBack_d($object) {
		$rs = true;
		//循环判断 - 针对复合行报告使用
		foreach ($object as $key => $val) {
			$strategeName = $this->applyStrategyArr[$key];
			if (!$strategeName) continue; //如果没有源单，则默认为真
			$istrategy = new $strategeName ();

			if (!$istrategy->checkCanBack_d($val)) {
				$rs = false;
				break;
			}
		}
		return $rs;
	}

    /**
     * 获取损坏方形的明细
     */
    function getDamagePassItem($relDocId) {
        $this->searchArr = array('relDocId' => $relDocId, 'iStatus' => 5);
        return $this->listBySqlId('select_detail');
    }
}