<?php

/**
 * @author zengzx
 * @Date 2011年5月4日 15:55:01
 * @version 1.0
 * @description:发货单 Model层
 */
class model_stock_outplan_ship extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_ship";
		$this->sql_map = "stock/outplan/shipSql.php";
		parent :: __construct();
			$this->relatedStrategyArr = array (//不同类型发货策略类,根据需要在这里进行追加
		"oa_contract_contract" => "model_stock_outplan_strategy_contractship", //销售发货
		"oa_borrow_borrow" => "model_stock_outplan_strategy_borrowship", //借用发货
		"oa_present_present" => "model_stock_outplan_strategy_presentship", //赠送发货
		"oa_contract_exchangeapply" => "model_stock_outplan_strategy_exchangeship", //赠送发货
		"oa_service_accessorder" => "model_stock_outplan_strategy_accessordership", //服务管理配件订单发货
		"oa_service_repair_apply" => "model_stock_outplan_strategy_repairapplyship", //服务管理维修申请单发货
		"independent" => "model_stock_outplan_strategy_indeptship", //独立发货

		);
	}
	/*===================================页面模板======================================*/
	/**
	 * @description 发货单列表显示模板
	 * @param $rows
	 */
	function showList($rows, shipStrategy $istrategy) {
		$istrategy->showList($rows);
	}

	/**
	 * @description 根据发货单新增发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemAddByPlan($rows, shipStrategy $istrategy) {
		return $istrategy->showItemAddByPlan($rows);
	}
	/**
	 * @description 新增发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows, shipStrategy $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 * @description 修改发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows, shipStrategy $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description 查看发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows, shipStrategy $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * @description 打印发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemPrint($rows, shipStrategy $istrategy) {
		return $istrategy->showItemPrint($rows);
	}

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr = false, shipStrategy $istrategy) {

	}

	/**
	 * 下推获取源单数据方法
	 */
	function getDocInfo($id, shipStrategy $strategy) {
		$rows = $strategy->getDocInfo($id);
		return $rows;
	}

	/**
	 * 新增发货单时源单据业务处理
	 * @param $istorageapply 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtAdd(shipStrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}
	/**
	 * 修改发货单时源单据业务处理
	 * @param $istorageapply 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtEdit(shipStrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * 删除发货单时源单据业务处理
	 * @param $istorageapply 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtDel(shipStrategy $istrategy, $paramArr = false) {
		return $istrategy->dealRelInfoAtDel($paramArr);
	}

	/**
	 * 添加对象
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$object['shipCode'] = $codeDao->sendCode($this->tbl_name);
			$id = parent :: add_d($object, true);
			$docType = isset ($object['docType']) ? $object['docType'] : null;
			if ($docType) { //存在发货单类型
				$outStrategy = $this->relatedStrategyArr[$docType];
				if ($outStrategy) {
						$paramArr = array (//单据主表参数
						'mainId' => $id,
						'docId' => $object['docId'],
						'docCode' => $object['docCode'],
						'docType' => $object['docType'],
						//'planId' => $object['planId']

					); //...可以继续追加
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //单据清单信息
						$prod = new model_stock_outplan_shipProduct();
						$mainIdArr = $paramArr;
						$prod->createBatch($relItemArr, $mainIdArr);
					} else {
						throw new Exception("单据信息不完整，请确认!");
					}
					$paramArr['planId'] = $object['planId'];
					//统一选择策略，进入各自的业务处理
					if ($paramArr['docId']) {
						$storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $paramArr, $relItemArr);
						$this->mailTo_d($object);
					} else {
						throw new Exception("无关联业务Id，请确认！");
					}
				} else {
					throw new Exception("该类型出库申请暂未开放，请联系开发人员!");
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			/*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 独立新增发货单
	 */
	function addWithoutPlan_d($object) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$object['shipCode'] = $codeDao->sendCode($this->tbl_name);
			$productsDetailDao = new model_stock_outplan_shipProduct();
			$id = parent :: add_d($object, true);
			if (!empty ($object['productsdetail'])) {
				foreach ($object['productsdetail'] as $key => $value) {
					$value['mainId'] = $id;
					$productsDetailDao->add_d($value);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 编辑对象
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			$id = parent :: edit_d($object, true);
			$docType = isset ($object['docType']) ? $object['docType'] : null;
			if ($docType) { //存在发货单类型
				$outStrategy = $this->relatedStrategyArr[$docType];
				if ($outStrategy) {
						$paramArr = array (//单据主表参数
	'mainId' => $object['id'],
						'docId' => $object['docId'],
						'docCode' => $object['docCode'],
							//							'planId' => $object['planId']

					); //...可以继续追加
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //单据清单信息
						$prod = new model_stock_outplan_shipProduct();
						$mainIdArr = array (
							'mainId' => $object['id']
						);
						$prod->delete($mainIdArr);
						$prod->createBatch($relItemArr, $paramArr);
					} else {
						throw new Exception("单据信息不完整，请确认!");
					}
					$paramArr['planId'] = $object['planId'];
					//统一选择策略，进入各自的业务处理
					$storageproId = $this->ctDealRelInfoAtEdit(new $outStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("该类型出库申请暂未开放，请联系开发人员!");
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			/*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 根据id获出库申请单所有信息
	 */
	function get_d($id) {
		$shipInfo = parent :: get_d($id);
		$itemDao = new model_stock_outplan_shipProduct();
		$searchArr = array (
			'mainId' => $id,

		);
		$shipInfo['details'] = $itemDao->findAll($searchArr);
		if (is_array($shipInfo['details'])) {
			foreach ($shipInfo['details'] as $key => $val) {
				$shipProDao = new model_stock_outplan_outplanProduct();
				$shipPro = $shipProDao->get_d($shipInfo['details'][$key]['planEquId']);
				$shipInfo['details'][$key]['contEquId'] = $shipPro['contEquId'];
			}
		}
		return $shipInfo;
	}

	/**
	 * 编辑对象
	 */
	function sign_d($object) {
		$id = parent :: edit_d($object, true);
		$docType = isset ($object['docType']) ? $object['docType'] : null;
		return $id;
	}

	/**
	 * 填写出库单时，根据出库单ID获取物料显示模板
	 */
	function getEquList_d($shipId) {
		$shipEquDao = new model_stock_outplan_shipProduct();
		$rows = $shipEquDao->getItemByshipId_d($shipId);// k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows= $productinfoDao->k3CodeFormatter_d($rows);
		$list = $shipEquDao->showAddList($rows);
		return $list;
	}

	/**
	 * 填写出库单时，根据出库单ID获取物料显示模板 -- 其他出库使用
	 */
	function getEquListOther_d($shipId) {
		$shipEquDao = new model_stock_outplan_shipProduct();
		$rows = $shipEquDao->getItemByshipId_d($shipId);
		$list = $shipEquDao->showAddOtherList($rows);
		return $list;
	}

	/**
	 * 填写出库单时，根据出库单ID将发货状态设为已发货
	 *
	 */
	function setDocStatusById_d($relDocItemArr) {
		$docStatus = array (
			'id' => $relDocItemArr['relDocId'],
			'docStatus' => '1',
			'shipStatus' => '2'
		);
		$this->updateById($docStatus);
	}

	/**
	 * 反审出库单时，根据出库单ID将发货状态设为未发货
	 *
	 */
	function unsetDocStatusById_d($relDocItemArr) {
		$docStatus = array (
			'id' => $relDocItemArr['relDocId'],
			'docStatus' => '0',
			'shipStatus' => '1'
		);
		$this->updateById($docStatus);
	}

	/**
	 * 删除发货单及关联信息：删除邮寄信息记录、回滚关联发货计划的执行状态
	 */
	function deleteObj($shipObj) {
		try {
			$this->start_d();
			$docType = $shipObj['docType'];
			//首先删除关联邮寄信息的记录
			$mailDao = new model_mail_mailinfo();
			$shipEquDao = new model_stock_outplan_shipProduct();
			if (!$mailDao->deleteByDoc($shipObj['id'], 'YJSQDLX-FHYJ')) {
				throw new Exception("单据信息不完整，请确认!");
				return null;
			}
			$condition = array (
				'mainId' => $shipObj['id']
			);
			$shipEquDao->delete($condition);
			$flag = $this->deleteByPk($shipObj['id']);
			$outStrategy = $this->relatedStrategyArr[$docType];
			$this->ctDealRelInfoAtDel(new $outStrategy (), $shipObj);
			$this->commit_d();
			return $flag;
		} catch (Exception $e) {
			$this->rollBack();
			return 0;
		}
	}

	/**
	 * 获取合同负责人方法
	 */
	function getSaleman($shiDocId, $docType, shipStrategy $strategy) {
		$salemanArr = $strategy->getSaleman($shiDocId, $docType);
		return $salemanArr;
	}

	/**
	 *
	 * 根据源单类型及源单id 查找发货单信息
	 * @param  $docType
	 * @param  $docId
	 */
	function findIdArrByDocInfo($docId, $docType) {
		$this->searchArr = array (
			"docType" => $docType,
			"docId" => $docId
		);
		$shipArr = $this->listBySqlId();
		$idArr = array ();
		foreach ($shipArr as $key => $val) {
			array_push($idArr, $val['id']);
		}
		return $idArr;
	}

	/**
	 * 根据源单号，源单类型，获取发货单id
	 * @author zengzx
	 * @param bigint $orderId 源单ID
	 * @param string $type 源单类型
	 * 2012年6月14日 09:54:29
	 */
	function getShipId_d($orderId, $type) {
		$conditions = array (
			'docId' => $orderId,
			'docType' => $type
		);
		$ids = $this->find($conditions, $sort = null, 'id');
		return $ids;
	}

	/**
	 * 发货计划下达后邮寄
	 * TODO:@param mailman string 额外邮寄人（待拓展）
	 */
	function mailTo_d($object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->mailArr = $mailUser[$this->tbl_name];
		$addMsg = $this->getAddMes_d($object);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '录入', $object['shipCode'], $this->mailArr['sendUserId'], $addMsg, '1');
	}
	/**
	 * 邮件中附加物料信息
	 */
	function getAddMes_d($object) {
		if (is_array($object['productsdetail'])) {
			$j = 0;
			$addmsg = '';
			if($object['docType'] == 'oa_borrow_borrow'){
				if($object['customerName']==''){
					$addmsg.="源单为员工借试用，借试用单号为：《<font color='red'>".$object['docCode']."</font>》</br>";
				}else{
					$addmsg.="源单为客户借试用，借试用单号为：".$object['docCode'].">,客户名称为：".$object['customerName']."</br>";
				}
			}else{
				$addmsg.="源单为合同，合同号为：".$object['docCode'].">,客户名称为：".$object['customerName']."</br>";
			}
			$addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>物料编号</td><td>物料名称</td><td>规格型号</td><td>数量</td><td>备注</td></tr>";
			foreach ($object['productsdetail'] as $key => $equ) {
				$j++;
				$productCode = $equ['productNo'];
				$productName = $equ['productName'];
				$productModel = $equ['productModel'];
				$number = $equ['number'];
				$remark = $equ['remark'];
				$addmsg .=<<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$number</td><td>$remark</td></tr>
EOT;
			}
			//					$addmsg.="</table>" .
			//							"<br><span color='red'>以上列表若有背景色为绿色的物料，说明该物料是借试用转销售的。</span></br>";
		}
		return $addmsg;
	}
	/**
	 *添加发货订单邮件通知
	 */
	 function sendMail_d($object){
		$this->mailDeal_d('shipFHDTZ',$object['receiverId'],array(id => $object['id']));
	 }
}
?>