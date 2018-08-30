<?php
/**
 * @author huangzf
 * @Date 2011年5月9日 22:10:00
 * @version 1.0
 * @description:入库单基本信息 Model层 1.入库单类型：A.有采购入库  B.产品入库   C.其它入库 D.退料入库
 * 2.单据状态：  未审核、已审核
 */
class model_stock_instock_stockin extends model_base
{

	public $stockinStrategyArr = array();
	public $stockinPreArr = array();

	function __construct() {
		$this->tbl_name = "oa_stock_instock";
		$this->sql_map = "stock/instock/stockinSql.php";
		parent::__construct();
		$this->stockinStrategyArr = array(//入库策略
			"RKPURCHASE" => "model_stock_instock_strategy_purchasestockin", //外购入库
			"RKPRODUCT" => "model_stock_instock_strategy_productstockin", //产品入库
			"RKOTHER" => "model_stock_instock_strategy_otherstockin", //其他入库
			"RXSTH" => "model_stock_instock_strategy_otherstockin", //其他入库
			"RKPRODUCEBACK" => "model_stock_instock_strategy_producebackstockin", //生产退料入库
            "RKDLBF" => "model_stock_instock_strategy_idleStockScrapStockin", //待报废呆料入库
		); //其他入库

		$this->stockinPreArr = array(//编号前缀
			"RKPURCHASE" => "WIN",
			"RKPRODUCT" => "CIN",
			"RKOTHER" => "QIN",
			"RKPRODUCEBACK" => "SIN",
			"RXSTH" => "HIN",
            "RKDLBF" => "DIN",
		);

		$this->docStatus = array(//单据状态
			"WSH" => "未审核", "YSH" => "已审核"
		);
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/*===================================页面模板======================================*/
	/**
	 * 入库申请单列表显示模板
	 * @param $rows
	 * @param istockin $istrategy
	 */
	function showList($rows, istockin $istrategy) {
		$istrategy->showList();
	}

	/**
	 * @description 联单据新增入库申请时，清单显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemAdd($rows, istockin $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 *
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemAddRed($rows, istockin $istrategy) {
		return $istrategy->showItemAddRed($rows);
	}

	/**
	 * @description 修改入库申请时，清单显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemEdit($rows, istockin $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description 修改入库申请时，清单显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemEditPrice($rows, istockin $istrategy) {
		return $istrategy->showItemEditPrice($rows);
	}

	/**
	 * @description 查看入库申请时，清单显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemView($rows, istockin $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * @description 打印入库申请时，清单显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemPrint($rows, istockin $istrategy) {
		return $istrategy->showItemPrint($rows);
	}
	
	/**
	 * @description 打印入库申请时，清单显示模板--红色单
	 * @param $rows
	 * @param $istrategy
	 */
	function showRedItemPrint($rows, istockin $istrategy) {
		return $istrategy->showRedItemPrint($rows);
	}

	/**
	 * @description 获取源单信息
	 * @param $paramArr
	 * @param $istrategy
	 */
	function getRelInfo($paramArr, istockin $istrategy) {
		return $istrategy->getRelInfo($paramArr);
	}

	/**
	 * @desription 列表显示
	 * @param tags
	 * @date 2011-2-24 下午02:18:24
	 * @qiaolong
	 */
	function showViewProdList($taskProdRows) {
		$producetaskDao = new model_produce_task_producetask();
		$list = $producetaskDao->showAppTaskProdList($taskProdRows);
		return $list;
	}

	/**
	 * @description 入库申请单从表信息显示模板
	 * @param $rows
	 * @param $istrategy
	 */
	function showProDetailList($rows, istockin $istrategy) {
		return $istrategy->showProDetailList($rows);
	}

	/**
	 *
	 * 根据蓝色入库单生成红色入库单，清单显示模板
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows, istockin $istrategy) {
		return $istrategy->showRelItem($rows);
	}

	/**
	 * 根据入库单下推出库单时，清单显示模板
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showItemAtOutStock($rows, istockin $istrategy) {
		return $istrategy->showItemAtOutStock($rows);
	}

     /***
      *
      */
    function  getMoneyLimit($limitKey){
        $otherdatasDao=new model_common_otherdatas();
        $limit =$otherdatasDao->getUserPriv('stock_instock_stockin',$_SESSION ['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        return $limit[$limitKey];
    }
    /*===================================业务处理======================================*/
    /**
	 * 新增入库单
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//保存入库单基本信息
				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr[$object['docType']]);
				$object['catchStatus'] = "CGFPZT-WGJ";
				$id = parent::add_d($object, true);

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, $object,
                    $object['docStatus'] == "YSH" ? '审核入库' : '新增入库');

                if (isset($object['orgId']) && $object['orgId']) {
                    $logSettingDao->addObjLog($this->tbl_name, $object['orgId'],
                        array('docCode' => $object['docCode']), '下推红字单据');
                }

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				// 缓存一份物料ID
				$productIds = array();

				//保存入库单物料清单
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //设置钩稽信息
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];

					if (!in_array($itemObj['productId'], $productIds)) {
						$productIds[] = $itemObj['productId'];
					}

                    //处理物料一级分类
                    if(!isset($itemObj['proType'])||$itemObj['proType']==""){
                        $typeRow=$productinfoDao->getParentType($itemObj['productId']);
                        if(!empty($typeRow)){
                            $itemObj['proType']=$typeRow['proType'];
                        }
                    }
					//蓝色入库单新增序列号同时保存到清单中，便于搜索
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $id, $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				//调用策略处理各自业务
				$stockinStrategy = $this->stockinStrategyArr[$object['docType']];
				if($stockinStrategy) {
					$paramArr = array(//单据基本信息
						'docId' => $id, 'docCode' => $object['docCode'], 'docType' => $object['docType'],
						'docStatus' => $object['docStatus'], 'relDocId' => $object['relDocId'],
						'relDocCode' => $object['relDocCode'], 'relDocType' => $object['relDocType'],
						'auditDate' => $object['auditDate'], 'isRed' => $object['isRed']
					);

					if ($object['purOrderId'] > 0) {
                        $paramArr['auditDate'] = $this->getEntryDateForPurOrderId_d($object['purOrderId'],
							implode(',', $productIds), false, false);
					}

					$dealRelInfoResult = $this->ctDealRelInfoAtAdd(new $stockinStrategy(), $paramArr, $itemsObj);
					if(!$dealRelInfoResult) {
						throw new Exception("相关业务处理有误，请确认！");
					}
				} else {
					throw new Exception("该类型入库暂未开放，请联系开发人员！");
				}
				if($object['relDocId'] && $object['relDocType'] == 'RSLTZD') {
					//add chenrf 更新入库时间
					$sql = ' update oa_produce_quality_ereportequitem c ,(
                        select c.id , b.mainId from oa_purchase_arrival_info c left join oa_purchase_arrival_equ p
                        on c.id=p.arrivalId left join  oa_produce_qualityapply_item m on p.id=m.relDocItemId
                        left join oa_produce_quality_taskitem n ON m.id=n.applyItemId
                        LEFT JOIN oa_produce_quality_ereportequitem b ON n.id=b.relItemId
                        WHERE b.id is not null AND m.status=3 )  p
                        SET c.completionTime=NOW() WHERE c.mainId=p.mainId';
					$sql .= ' and p.id="' . $object['relDocId'] . '"';
					$this->query($sql);
				}

				/*start:发送邮件 ,当操作为提交时才发送*/
				if($object['docStatus'] == "YSH") {
					if($object['relDocType'] != "RZCRK") {
						if(isset($object['email'])){
							$emailArr = $object['email'];
							if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
								$datadictDao = new model_system_datadict_datadict();
							
								$sendContent = "各位好:<br/>下面的物料信息已入库,请知悉.<br/>";
								$sendContent .= "<table><tr><td>单据编号:</td><td>" . $object['docCode'] . "</td><td>交货单位:</td><td>" . $object['purchaserName'] . "</td></tr>"
										. "<tr><td>源单类型:</td><td>" . $datadictDao->getDataNameByCode($object['relDocType']) . "</td><td>源单编号:</td><td>" . $object['relDocCode'] . "</td></tr>"
												. "<tr><td>客户名称:</td><td>" . $object['clientName'] . "</td><td></td><td></td></tr>"
														. "<tr><td>备注:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
								$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>数量</b></td><td><b>批次号</b></td><td><b>序列号</b></td></tr>";
								$seNum = 1;
								foreach($itemsObj as $kValue) {
									$sendContent .= <<<EOT
                                <tr align="center" >
                                    <td>$seNum</td>
                                    <td>$kValue[productCode]</td>
                                    <td>$kValue[productName]</td>
                                    <td>$kValue[pattern]</td>
                                    <td>$kValue[actNum]</td>
                                    <td>$kValue[batchNum]</td>
                                    <td>$kValue[serialnoName]</td>
                                </tr>
EOT;
									$seNum++;
								}
								$sendContent .= "</table>";
								$emailDao = new model_common_mail();
								$emailDao->mailClear("产品入库通知", $emailArr['TO_ID'], $sendContent);
							} else {
								if($object['isRed'] == "1" && $object['docType'] == "RKPURCHASE") {//外购入库退料发送通知
									$sendContent = "各位好:<br/>下面的物料信息已退库,请知悉.<br/>";
									$sendContent .= "<table><tr><td>供应商名称:</td><td>" . $object['supplierName'] . "</td><td>付款日期:</td><td>" . $object['payDate'] . "</td></tr>"
											. "<tr><td>单据编号:</td><td>" . $object['docCode'] . "</td><td>单据日期:</td><td>" . $object['auditDate'] . "</td></tr>"
													. "<tr><td>客户名称:</td><td>" . $object['purOrderCode'] . "</td><td>采购员名称:</td><td>" . $object['purchaserName'] . "</td></tr>"
															. "<tr><td>备注:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
									$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>数量</b></td><td><b>批次号</b></td><td><b>序列号</b></td></tr>";
									$seNum = 1;
									foreach($itemsObj as $kValue) {
										$sendContent .= <<<EOT
                                    <tr align="center" >
                                        <td>$seNum</td>
                                        <td>$kValue[productCode]</td>
                                        <td>$kValue[productName]</td>
                                        <td>$kValue[pattern]</td>
                                        <td>$kValue[actNum]</td>
                                        <td>$kValue[batchNum]</td>
                                        <td>$kValue[serialnoName]</td>
                                    </tr>
EOT;
										$seNum++;
									}
									$sendContent .= "</table>";
									$emailDao = new model_common_mail();
									include(WEB_TOR . "model/common/mailConfig.php");
									$emailArr = isset($mailUser["purchinstock"]) ? $mailUser["purchinstock"] : '';
									$emailDao->mailClear("外购退库通知", $object['purchaserCode'] . "," . $emailArr['TO_ID'], $sendContent);
								}
							}
						}
					} else {//资产入库-提交时邮件通知
						$requireoutDao = new model_asset_require_requireout();
						$rs = $requireoutDao->find(array('id' => $object['relDocId']), null, 'applyId');
						$this->mailDeal_d('assetInStock', $rs['applyId'], array('id' => $id));
					}
				}
				/*end:发送邮件 ,当操作为提交时才发送*/

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 保存9月份前入库单
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function addPre_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//保存入库单基本信息
				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr[$object['docType']]);
				$object['catchStatus'] = "CGFPZT-WGJ";
				$id = parent::add_d($object, true);

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				//保存入库单物料清单
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //设置钩稽信息
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];
					//蓝色入库单新增序列号同时保存到清单中，便于搜索
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $id, $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				$arrivalDao = new model_purchase_arrival_arrival();
				foreach($itemsObj as $key => $value) {
					$arrivalDao->updateInStock($object['relDocId'], $value['relDocId'], $value['productId'], $value['actNum']);
				}

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 修改入库单
	 * @param $object
	 * @return mixed
	 * @throws Exception
	 */
	function edit_d($object) {
        // 获取旧的入库单信息
        $org = parent::get_d($object['id']);

		try {
			$this->start_d();
			if(is_array($object['items'])) {
				$editresult = parent::edit_d($object, true); //修改入库单信息

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->compareModelObj($this->tbl_name, $org, $object, $object['docStatus'] == "YSH" ? '审核入库' : '修改入库');

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				// 缓存一份物料ID
				$productIds = array();

				//保存入库单物料清单
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //设置钩稽信息
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];

					if (!in_array($itemObj['productId'], $productIds)) {
						$productIds[] = $itemObj['productId'];
					}

					//蓝色入库单新增序列号同时保存到清单中，便于搜索
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);

				}
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				//调用策略处理各自业务
				$stockinStrategy = $this->stockinStrategyArr[$object['docType']];
				if($stockinStrategy) {
					$paramArr = array(//单据基本信息参数
						'docId' => $object['id'], 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'],
                        'auditDate' => $object['auditDate'], 'isRed' => $object['isRed']
					);

					if ($object['purOrderId'] > 0) {
						$paramArr['auditDate'] = $this->getEntryDateForPurOrderId_d($object['purOrderId'],
							implode(',', $productIds), false, false);
					}

					$dealRelInfoResult = $this->ctDealRelInfoAtEdit(new $stockinStrategy(), $paramArr, $itemsObj);
					if(!$dealRelInfoResult) {
						throw new Exception("相关业务处理有误，请确认！");
					}
				} else {
					throw new Exception("该类型入库暂未开放，请联系开发人员!");
				}

				/*start:发送邮件 ,当操作为提交时才发送*/
				if($object['docStatus'] == "YSH") {
					if($object['relDocType'] != "RZCRK") {
						if(isset($object['email'])){
							$emailArr = $object['email'];
							if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
								$datadictDao = new model_system_datadict_datadict();
								$sendContent = "各位好:<br/>下面的物料信息已入库,请知悉.<br/>";
								$sendContent .= "<table><tr><td>单据编号:</td><td>" . $object['docCode'] . "</td><td>交货单位:</td><td>" . $object['purchaserName'] . "</td></tr>"
										. "<tr><td>源单类型:</td><td>" . $datadictDao->getDataNameByCode($object['relDocType']) . "</td><td>源单编号:</td><td>" . $object['relDocCode'] . "</td></tr>"
												. "<tr><td>客户名称:</td><td>" . $object['clientName'] . "</td><td></td><td></td></tr>"
														. "<tr><td>备注:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
								$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>数量</b></td><td><b>批次号</b></td><td><b>序列号</b></td></tr>";
								$seNum = 1;
								foreach($itemsObj as $kValue) {
									$sendContent .= <<<EOT
									<tr align="center">
										<td>$seNum</td>
										<td>$kValue[productCode]</td>
										<td>$kValue[productName]</td>
										<td>$kValue[pattern]</td>
										<td>$kValue[actNum]</td>
										<td>$kValue[batchNum]</td>
										<td>$kValue[serialnoName]</td>
									</tr>
EOT;
									$seNum++;
								}
								$sendContent .= "</table>";
								$emailDao = new model_common_mail();
								$emailDao->mailClear("产品入库通知", $emailArr['TO_ID'], $sendContent);
							}
						}
					} else {//资产入库-提交时邮件通知
						$requireoutDao = new model_asset_require_requireout();
						$rs = $requireoutDao->find(array('id' => $object['relDocId']), null, 'applyId');
						$this->mailDeal_d('assetInStock', $rs['applyId'], array('id' => $object['id']));
					}
				}
				/*end:发送邮件 ,当操作为提交时才发送*/

				$this->commit_d();
				return $editresult;
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

    /**
     * 批量删除对象
     */
    function deletes_d($id)
    {
        // 获取旧的入库单信息
        $org = parent::get_d($id);

        try {
            $this->deletes($id);

            //插入操作记录
            $logSettingDao = new model_syslog_setting_logsetting();
            $logSettingDao->deleteObjLog($this->tbl_name, $org);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

	/**
	 * 根据主键id获取入库单基本信息及物料清单信息
	 * @param $id
	 * @param istockin $istrategy
	 * @return bool|mixed
	 */
	function get_d($id, istockin $istrategy) {
		$stockinObj = parent::get_d($id);
		$stockinObj['items'] = $istrategy->getItem($id);
		return $stockinObj;
	}

	/**
	 * 根据单据编号查看入库单信息
	 * @param $docCode
	 * @param istockin $istrategy
	 * @return bool|mixed
	 */
	function findByDocCode($docCode, istockin $istrategy) {
		$this->searchArr = array("nDocCode" => $docCode);
		$inStockArr = $this->list_d();
		$stockinObj = parent::get_d($inStockArr[0]['id']);
		$stockinObj['items'] = $istrategy->getItem($inStockArr[0]['id']);
		return $stockinObj;
	}

	/**
	 * 查看入库申请源单据业务信息
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @return mixed
	 */
	function ctViewRelInfo(istockin $istrategy, $paramArr = false) {
		return $istrategy->viewRelInfo($paramArr);
	}

	/**
	 * 新增入库单时源单据业务处理
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @param bool $relItemArr
	 * @return mixed
	 */
	function ctDealRelInfoAtAdd(istockin $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * 修改入库申请时源单据业务处理
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @param bool $relItemArr
	 * @return mixed
	 */
	function ctDealRelInfoAtEdit(istockin $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

    /**
     * 查询序列号的状态 PMS2241 2017-01-06
     * @param $SerialnoNames
     * @return array|bool
     */
	function chkSerialnoState($SerialnoNames = '',$SerialnoIds = ''){
        $sql = '';
        if($SerialnoNames != ''){
            $sql = "select seqStatus from oa_stock_product_serialno where FIND_IN_SET(sequence,'{$SerialnoNames}') > 0;";
        }else if($SerialnoIds != ''){
            $sql = "select seqStatus from oa_stock_product_serialno where id in ({$SerialnoIds});";
        }

        $seqStatusArr = ($sql != '')? $this->_db->getArray($sql) : array();
        return $seqStatusArr;
    }

	/**
	 * 反审核入库单
	 * @param $id
	 * @param istockin $istrategy
	 * @return bool
	 * @throws Exception
	 */
	function ctCancelAudit($id, istockin $istrategy) {
		try {
			$this->start_d();
			$stockinObj = $this->get_d($id, $istrategy);

			// 缓存一份物料ID
			$productIds = array();

            // 查询序列号状态 PMS2241 2017-01-06
            $noInStockSerialno = false;// 是否存在非库存中序列号标示,默认false,表示都是库存中
            foreach($stockinObj['items'] as $val) {
				if (!in_array($val['productId'], $productIds)) {
					$productIds[] = $val['productId'];
				}
                if($val['serialnoName'] != '' || $val['serialnoId'] != ''){
                    $data = $this->chkSerialnoState('',$val['serialnoId']);
                    foreach($data as $key => $val){
                        //只要有一个序列号的状态不为库存中的,该单据不得反审核
                        if($val['seqStatus'] != 0){
                            $noInStockSerialno = true;
                        }
                    }
                }
            }
            if($noInStockSerialno){
                throw new Exception("单据中存在序列号状态不为库存中的,禁止反审核!");
            }else if("YSH" == $stockinObj['docStatus']) {
				//反审核，更新即时库存
				if($stockinObj['items']) {
					if($stockinObj['isRed'] == 1) {
						$state = false;
					} else {
						$state = true;
					}
					if($stockinObj['relDocType'] == 'RSLTZD' && $stockinObj['isRed'] == 1) {
						$conditions['arrivalId'] = $stockinObj['relDocId'];
						foreach($stockinObj['items'] as $val) {
							$conditions['productId'] = $val['productId'];
							$this->updateArrivalNum_d($conditions, $val, $state);
						}
					}
					if($stockinObj['relDocType'] == 'RTLTZD') {
						$arrivalId = $this->get_table_fields('oa_purchase_delivered', "id='" . $stockinObj['relDocId'] . "'", 'sourceId');
						$conditions['arrivalId'] = $arrivalId;
						foreach($stockinObj['items'] as $val) {
							$conditions['productId'] = $val['productId'];
							$this->updateArrivalNum_d($conditions, $val, $state);
						}
					}
				}
				$obj = array("id" => $id, "docStatus" => "WSH");
				$this->updateById($obj);

				if ($stockinObj['purOrderId'] > 0) {
					$stockinObj['auditDate'] = $this->getEntryDateForPurOrderId_d($stockinObj['purOrderId'],
						implode(',', $productIds), false, false);
				}

				if(!$istrategy->cancelAudit($stockinObj)) {
					throw new Exception("单据反审核失败");
				}

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, array(), '取消审核');
			} else {
				throw new Exception("单据状态已反审核!");
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 修改主表单
	 * @param $object
	 * @return mixed
	 */
	function parentEdit($object) {
		return parent::edit_d($object);
	}

	/**
	 * 数组过滤 - 同表单的表单数据不予显示
	 * @param $object
	 * @return mixed
	 */
	function filterRows_d($object) {
		if($object) {
			$markId = null;
			foreach($object as $key => $val) {
				if($markId == $val['mainId']) {
					unset($object[$key]['docCode']);
					unset($object[$key]['docStatus']);
					unset($object[$key]['supplierName']);
					unset($object[$key]['auditDate']);
					unset($object[$key]['catchStatus']);
					$object[$key]['isRed'] = 2;
				} else {
					$markId = $val['mainId'];
				}
			}
		}
		return $object;
	}

	/**
	 * 获取当前财务周期
	 */
	function rtThisPeriod_d() {
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}

	/**
	 * 修改入库单 - 核算中修改价格
	 * @param $object
	 * @return null
	 */
	function editPrice_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				$editresult = parent::edit_d($object, true); //修改入库单信息
				//保存入库单物料清单
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $key => $itemObj) { //设置钩稽信息
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $itemsObjArr);
				$stockinItemDao->saveDelBatch($itemsArr);

				$this->commit_d();
				return $editresult;
			} else {
				throw new Exception("单据信息不完整,，请确认!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/******************************st:storageapply中的方法*********************************************************/

	/**
	 * 过滤数组为单一元素
	 * @param $array
	 * @return array
	 */
	function a_array_unique($array) {
		$out = array();
		foreach($array as $key => $value) {
			if(!in_array($value, $out)) {
				$out[$key] = $value;
			}
		}
		return $out;
	}

	/**
	 * 设置关联清单的从表的申请单id信息
	 * @param $mainIdName
	 * @param $mainIdValue
	 * @param $iteminfoArr
	 * @return array
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array();
		foreach($iteminfoArr as $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/**
	 * 根据id 获取外购入库单
	 * @param  $ids
	 * @param  $docType
	 */
	function findAllItemByIds($ids, $docType = 'RKPURCHASE') {
		$searchArr = array("ids" => $ids, "docType" => $docType, "docStatus" => "YSH");
		$this->searchArr = $searchArr;
		$this->sort = "c.id";
		return $this->listBySqlId("select_item");
	}

	/**
	 * 外购入库单关联订单逻辑处理
	 * @param $stockinOrder
	 * @return bool|null
	 */
	function unionOrder($stockinOrder) {
		try {
			$this->start_d();
			$stockinItemDao = new model_stock_instock_stockinitem();
			$stockinObj = $this->get_d($stockinOrder['id'], new model_stock_instock_strategy_purchasestockin());

			//更新主表信息
			$tempStockInObj = array("id" => $stockinOrder['id'], "relDocType" => "RCGDD", "relDocId" => $stockinOrder['purOrderId'], "relDocCode" => $stockinOrder['purOrderCode'], "purOrderCode" => $stockinOrder['purOrderCode'], "purOrderId" => $stockinOrder['purOrderId']);
			$this->updateById($tempStockInObj);

			//更新从表信息 ,根据productId匹配
			$stockinItemArr = $stockinObj['items'];
			foreach($stockinItemArr as $key => $value) { //入库清单
				$tempStockinItemObj = array("id" => $value['id']);
				foreach($stockinOrder['orderItems'] as $oKey => $oValue) { //订单清单
					if($value['productId'] == $oValue['productId']) {
						$tempStockinItemObj['relDocId'] = $oValue['id'];
						$tempStockinItemObj['price'] = $oValue['price'];
						$tempStockinItemObj['subPrice'] = $value['actNum'] * $oValue['price'];
						$tempStockinItemObj['unHookAmount'] = $value['actNum'] * $oValue['price'];
						$stockinItemDao->updateById($tempStockinItemObj);
						break;
					}
				}
			}

			//更新关联订单信息
			$purchasecontractDao = new model_purchase_contract_purchasecontract();
			foreach($stockinOrder['orderItems'] as $oKey => $oValue) { //订单清单
				$purchasecontractDao->updateInStock($stockinOrder['purOrderId'], $oValue['id'], $oValue['productId'], $oValue['unionNum']);
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}

	}

	/**
	 * 外购入库单收料通知单逻辑处理
	 * @param $stockinOrder
	 * @return bool|null
	 */
	function unionArrival($stockinOrder) {
		try {
			$this->start_d();
			$stockinItemDao = new model_stock_instock_stockinitem();
			$stockinObj = $this->get_d($stockinOrder['id'], new model_stock_instock_strategy_purchasestockin());

			//更新主表信息
			$tempStockInObj = array("id" => $stockinOrder['id'], "relDocType" => "RSLTZD", "relDocId" => $stockinOrder['arrivalId'], "relDocCode" => $stockinOrder['arrivalCode'], "purOrderCode" => $stockinOrder['purOrderCode'], "purOrderId" => $stockinOrder['purOrderId'], "purchaserCode" => $stockinOrder['purchaserCode'], "purchaserName" => $stockinOrder['purchaserName']);
			$this->updateById($tempStockInObj);

			//更新从表信息 ,根据productId匹配
			$stockinItemArr = $stockinObj['items'];
			foreach($stockinItemArr as $key => $value) { //入库清单
				$tempStockinItemObj = array("id" => $value['id']);
				foreach($stockinOrder['arrivalItems'] as $oKey => $oValue) { //收料清单
					if($value['productId'] == $oValue['productId']) {
						$tempStockinItemObj['relDocId'] = $oValue['id'];
						$tempStockinItemObj['price'] = $oValue['price'];
						$tempStockinItemObj['subPrice'] = $value['actNum'] * $oValue['price'];
						$tempStockinItemObj['unHookAmount'] = $value['actNum'] * $oValue['price'];
						$stockinItemDao->updateById($tempStockinItemObj);
						break;
					}
				}
			}

			//更新关联收料信息
			$purchaseArrivalDao = new model_purchase_arrival_arrival();
			foreach($stockinOrder['arrivalItems'] as $oKey => $oValue) { //收料清单
				$purchaseArrivalDao->updateInStock($stockinOrder['arrivalId'], $oValue['id'], $oValue['productId'], $oValue['unionNum']);
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 导入外购入库单
	 * @param $excelData
	 * @return array|null
	 */
	function importPurchaseStockin($excelData) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$purchasecontractDao = new model_purchase_contract_purchasecontract(); //采购订单Dao
			$purEquDao = new model_purchase_contract_equipment();
			$dataDictDao = new model_system_datadict_datadict(); //数字字典
			$inStockItemDao = new model_stock_instock_stockinitem(); //入库清单Dao
			$productinfoDao = new model_stock_productinfo_productinfo(); //产品Dao
			$supplierDao = new model_supplierManage_formal_flibrary(); //供应商Dao
			$userDao = new model_deptuser_user_user(); //用户Dao
			$stockDao = new model_stock_stockinfo_systeminfo(); //仓库设置Dao

			$cgfsArr = $dataDictDao->getDatadictsByParentCodes("cgfs");
			$stockSysObj = $stockDao->get_d("1");

			$resultArr = array();
			foreach($excelData as $key => $value) {
				$stockinObject = array();
				if(!empty($value[0])) {
					if(date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900))) < '2011-09-01') { //小于20110901
						$productinfoArr = $productinfoDao->getProByCode($value[6]);
						$supplierArr = $supplierDao->findBy("suppName", $value[2]);
						$purchUser = $userDao->getUserByName($value[4]);
						if(!empty($value[3])) { //有订单编号的
							$purchasecontractArr = $purchasecontractDao->findBy("hwapplyNumb", $value[3]);

							if(is_array($purchasecontractArr)) { //订单已补录
								if(is_array($productinfoArr)) { //产品信息存在
									if(is_array($purchUser)) {
										if(is_array($supplierArr)) {
											$purchMethod = "XG"; //采购方式
											$stockinObject['auditDate'] = date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)));
											$stockinObject['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr["RKPURCHASE"]);
											$stockinObject['isRed'] = "0";
											$stockinObject['relDocType'] = "RCGDD";
											//									print_r($purchasecontractArr);
											$stockinObject['relDocCode'] = $purchasecontractArr['hwapplyNumb'];
											$stockinObject['relDocId'] = $purchasecontractArr['id'];

											foreach($cgfsArr as $cKey => $dataObj) {
												if($dataObj['dataName'] == $value[1]) {
													$purchMethod = $dataObj['dataCode'];
													break;
												}
											}

											$stockinObject['purchMethod'] = $purchMethod;
											$stockinObject['accountingCode'] = 'KJKM1';
											$stockinObject['supplierName'] = $supplierArr['suppName'];
											$stockinObject['supplierId'] = $supplierArr['id'];
											$stockinObject['purOrderCode'] = $purchasecontractArr['hwapplyNumb'];
											$stockinObject['purOrderId'] = $purchasecontractArr['id'];
											$stockinObject['purchaserName'] = $purchUser['USER_NAME'];
											$stockinObject['purchaserCode'] = $purchUser['USER_ID'];
											$stockinObject['docType'] = 'RKPURCHASE';
											$stockinObject['docStatus'] = 'YSH';
											$stockinObject['auditerName'] = $_SESSION['USERNAME'];
											$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
											$stockinObject['catchStatus'] = "CGFPZT-WGJ";
											$stockinObject['remark'] = $value[4];
											$id = parent::add_d($stockinObject, true);

											$stockInItemObj = array("mainId" => $id, "productId" => $productinfoArr['id'], "productName" => $productinfoArr['productName'], "productCode" => $productinfoArr['productCode'], "pattern" => $productinfoArr['pattern'], "unitName" => $productinfoArr['unitName'], "batchNum" => $value['10'], "actNum" => $value['11'], "price" => $value['13'], "subPrice" => $value['14'], "inStockId" => $stockSysObj['salesStockId'], "inStockCode" => $stockSysObj['salesStockCode'], "inStockName" => $stockSysObj['salesStockName'], "unHookNumber" => $value['11'], "unHookAmount" => $value['14']);
											//处理订单的已入库数量
											$purEquObj = $purEquDao->find(array("productId" => $productinfoArr['id'], "basicId" => $purchasecontractArr['id']));
											if(is_array($purEquObj)) {
												$purchasecontractDao->updateInStock($purchasecontractArr['id'], $purEquObj['id'], $productinfoArr['id'], $value['11']);
												$stockInItemObj['relDocId'] = $purEquObj['id'];
											}
											$inStockItemDao->add_d($stockInItemObj);

											array_push($resultArr, array("docCode" => "日期:" . $stockinObject['auditDate'] . ",供应商:" . $value[2] . "物料编号:" . $value[6], "result" => "导入成功!"));
										} else {
											array_push($resultArr, array("docCode" => $value[2], "result" => "导入失败,供应商为" . $value[2] . "的信息系统中不存在!"));
										}

									} else {
										array_push($resultArr, array("docCode" => $value[6], "result" => "导入失败,采购员为" . $value[4] . "的信息系统中不存在!"));
									}

								} else {
									array_push($resultArr, array("docCode" => $value[6], "result" => "导入失败,物料编码为" . $value[6] . "不存在!"));
								}
							} else {
								array_push($resultArr, array("docCode" => $value[2], "result" => "导入失败,订单编号为" . $value[3] . "不存在!"));
							}
						} else { //没有订单编号的
							if(is_array($productinfoArr)) { //产品信息存在
								if(is_array($purchUser)) {
									if(is_array($supplierArr)) {
										$purchMethod = "XG"; //采购方式
										$stockinObject['auditDate'] = date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)));
										$stockinObject['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr["RKPURCHASE"]);
										$stockinObject['isRed'] = "0";

										foreach($cgfsArr as $cKey => $dataObj) {
											if($dataObj['dataName'] == $value[1]) {
												$purchMethod = $dataObj['dataCode'];
												break;
											}
										}

										$stockinObject['purchMethod'] = $purchMethod;
										$stockinObject['accountingCode'] = 'KJKM1';
										$stockinObject['supplierName'] = $supplierArr['suppName'];
										$stockinObject['supplierId'] = $supplierArr['id'];
										$stockinObject['purchaserName'] = $purchUser['USER_NAME'];
										$stockinObject['purchaserCode'] = $purchUser['USER_ID'];
										$stockinObject['docType'] = 'RKPURCHASE';
										$stockinObject['docStatus'] = 'YSH';
										$stockinObject['auditerName'] = $_SESSION['USERNAME'];
										$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
										$stockinObject['catchStatus'] = "CGFPZT-WGJ";
										$stockinObject['remark'] = $value[4];
										$id = parent::add_d($stockinObject, true);

										$stockInItemObj = array("mainId" => $id, "productId" => $productinfoArr['id'], "productName" => $productinfoArr['productName'], "productCode" => $productinfoArr['productCode'], "pattern" => $productinfoArr['pattern'], "unitName" => $productinfoArr['unitName'], "batchNum" => $value['10'], "actNum" => $value['11'], "price" => $value['13'], "subPrice" => $value['14'], "inStockId" => $stockSysObj['salesStockId'], "inStockCode" => $stockSysObj['salesStockCode'], "inStockName" => $stockSysObj['salesStockName'], "unHookNumber" => $value['11'], "unHookAmount" => $value['14']);
										$inStockItemDao->add_d($stockInItemObj);

										array_push($resultArr, array("docCode" => "日期:" . $stockinObject['auditDate'] . ",供应商:" . $value[2] . "物料编号:" . $value[6], "result" => "导入成功!"));
									} else {
										array_push($resultArr, array("docCode" => $value[2], "result" => "导入失败,供应商为" . $value[2] . "的信息系统中不存在!"));
									}
								} else {
									array_push($resultArr, array("docCode" => $value[6], "result" => "导入失败,采购员为" . $value[4] . "的信息系统中不存在!"));
								}
							} else {
								array_push($resultArr, array("docCode" => $value[6], "result" => "导入失败,物料编码为" . $value[6] . "不存在!"));
							}
						}
					} else {
						array_push($resultArr, array("docCode" => $value[2], "result" => "导入失败,单据日期不能大于20110901=>" . date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)))));
					}
				}
			}
			$this->commit_d();
			return $resultArr;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 导入未勾稽入库单
	 * @param $excelData
	 * @return array|null
	 */
	function importWgjDoc($excelData) {
		try {
			$this->start_d();
			$resultArr = array();
			$supplierDao = new model_supplierManage_formal_flibrary(); //供应商Dao
			$userDao = new model_deptuser_user_user(); //用户Dao
			$productDao = new model_stock_productinfo_productinfo();
			$instockItemDao = new model_stock_instock_stockinitem();
			$stockDao = new model_stock_stockinfo_stockinfo();
			$lastDocCode = "";
			$lastMainId = "";
			foreach($excelData as $key => $obj) {
				if(!empty($obj['0']) && !empty($obj[4])) {
					$productDao->searchArr['ext2'] = $obj[4];
					$productArr = $productDao->listBySqlId();
					if(is_array($productArr)) {
						if($obj['1'] != $lastDocCode) {

							$supplierArr = $supplierDao->findBy("suppName", $obj[2]);
							$purchUser = $userDao->getUserByName($obj[3]);
							$isRed = $obj[7] > 0 ? "0" : "1";
							$proNum = $obj[7] > 0 ? $obj[7] : -$obj[7];
							$proAmount = $obj[8] > 0 ? $obj[8] : -$obj[8];
							$stockObj = array('auditDate' => $obj[0],
								'isRed' => $isRed,
								'docCode' => $obj['1'] . "K3",
								'supplierId' => $supplierArr['id'],
								'supplierName' => $supplierArr['suppName'],
								'purchaserName' => $purchUser['USER_NAME'],
								'purchaserCode' => $purchUser['USER_ID'],
								'docType' => 'RKPURCHASE',
								'docStatus' => 'YSH',
								'auditerName' => $_SESSION['USERNAME'],
								'auditerCode' => $_SESSION['USER_ID'],
								'catchStatus' => "CGFPZT-WGJ",
								'remark' => $obj[4]
							);
							$lastMainId = parent::add_d($stockObj, true);
							$lastDocCode = $obj['1'];

						}

						$stockObj = $stockDao->findBy("stockName", $obj[6]);
						$instockItem = array("mainId" => $lastMainId, "productId" => $productArr['0']['id'], "productCode" => $productArr['0']['productCode'], "productName" => $productArr['0']['productName'], "pattern" => $productArr['0']['pattern'], "unitName" => $productArr['0']['unitName'], "storageNum" => $proNum, "actNum" => $proNum, "price" => round($proAmount / $proNum, 6), "subPrice" => $proAmount, "unHoodNum" => $proNum, "unHoodAmount" => $proAmount, "inStockName" => $stockObj['stockName'], "inStockId" => $stockObj['id'], "inStockCode" => $stockObj['stockCode']);
						$instockItemDao->add_d($instockItem);
						array_push($resultArr, array("docCode" => "k3编码:" . $obj[4] . ",单据编号:" . $obj[1], "result" => "导入成功!"));

					} else {
						array_push($resultArr, array("docCode" => "k3编码:" . $obj[4] . ",单据编号:" . $obj[1], "result" => "k3编码不存在，导入成功!"));
					}
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/*****************************上查下查**************************/

	/**
	 * 判断所传源单信息是否有下推的外购入库单
	 */
	function hasSource($objId, $objType) {
		$this->searchArr = array('relDocId' => $objId, 'relDocType' => $objType);
		if(is_array($this->listBySqlId('select_default'))) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 采购发票钩稽默认获取入库单
	 */
	function getPurchaseOutstock_d($supplierId) {
		$this->searchArr = array('supplierId' => $supplierId, 'docType' => 'RKPURCHASE', 'docStatus' => 'YSH', 'catchStatusNo' => 'CGFPZT-YGJ');
		$this->sort = "c.auditDate";
		$rs = $this->listBySqlId('select_item');
		return $this->initHook_d($rs);
	}

	/**
	 * 外裤入库单格式渲染
	 */
	function initHook_d($object) {
		$str = null;
		if(is_array($object)) {
			$i = 0;
			$dataArr = null;
			$markId = null;
			foreach($object as $key => $val) {
				$i++;
				$tr_class = $i % 2 == 0 ? 'tr_even' : 'tr_odd';

				if(empty($markId) || $markId != $val['mainId']) {

					//判断状态字段是否在数组中，然后做出相应的处理
					if(!isset($dataArr[$val['catchStatus']])) {
						$dataArr = $this->datadictArrSearch_d($dataArr, $val['catchStatus']);
					}
					$hookType = $dataArr[$val['catchStatus']];

					if($val['isRed'] == 1) {
						$codeStr = "<span class='red'>$val[docCode]</span>";
					} else {
						$codeStr = "<span>$val[docCode]</span>";
					}

					$str .= <<<EOT
						<tr class="storageList_$val[mainId] $tr_class">
							<td>
								<input type="text" name="storage[$i][number]" id="stonumber$i" value="$val[actNum]" class="txtshort" onblur="checkInput('stonumber$i','oldpronumber$i');">
								<input type="hidden" name="storage[$i][hookMainId]" id="stoHookManId$val[mainId]" value="$val[mainId]">
								<input type="hidden" name="storage[$i][hookId]" value="$val[id]">
								<input type="hidden" id="oldpronumber$i" value="$val[actNum]">
								<input type="hidden" name="storage[$i][hookObjCode]" value="$val[docCode]">
								<input type="hidden" name="storage[$i][formDate]" id="stoFormDate$i" value="$val[auditDate]">
								<input type="hidden" id="storageId_$i" value="$val[inStockId]">
								<input type="hidden" id="isRed$i" value="$val[isRed]">
		                    </td>
		                    <td>
		                        $val[auditDate]
		                    </td>
		                    <td>
		                    	$codeStr<img src="images/closeDiv.gif" onclick="delStorage($val[mainId])" title="删除行">
		                    </td>
		                    <td>
		                        $val[inStockName]
		                    </td>
		                    <td>
		                        $hookType
		                    </td>
		                    <td>
		                        $val[productCode]
		                        <input type="hidden" name="storage[$i][unHookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][hookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][productName]" value="$val[productName]">
		                        <input type="hidden" name="storage[$i][productNo]" value="$val[productNo]">
		                        <input type="hidden" name="storage[$i][productId]" id="storagePN$i" value="$val[productId]">
		                        <input type="hidden" name="storage[$i][cost]" value="$val[cost]">
		                        <input type="hidden" name="storage[$i][stockId]" value="$val[inStockId]">
		                        <input type="hidden" name="storage[$i][stockName]" value="$val[inStockName]">
		                    </td>
		                    <td>
		                        $val[productName]
		                    </td>
		                    <td>
		                        $val[actNum]
		                    </td>
		                    <td>
		                        $val[hookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[hookAmount]</span>
		                    </td>
		                    <td>
		                        $val[unHookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[unHookAmount]</span>
		                    </td>
		                </tr>
EOT;
				} else {
					$str .= <<<EOT
						<tr class="storageList_$val[mainId] $tr_class">
							<td>
								<input type="text" name="storage[$i][number]" id="stonumber$i" value="$val[actNum]" class="txtshort" onblur="checkInput('stonumber$i','oldpronumber$i');">
								<input type="hidden" name="storage[$i][hookMainId]" id="stoHookManId$val[mainId]" value="$val[mainId]">
								<input type="hidden" name="storage[$i][hookId]" value="$val[id]">
								<input type="hidden" id="oldpronumber$i" value="$val[actNum]">
								<input type="hidden" name="storage[$i][hookObjCode]" value="$val[docCode]">
								<input type="hidden" name="storage[$i][formDate]" id="stoFormDate$i" value="$val[auditDate]">
								<input type="hidden" id="storageId_$i" value="$val[inStockId]">
								<input type="hidden" id="isRed$i" value="$val[isRed]">
		                    </td>
		                    <td colspan="4">
		                    </td>
		                    <td>
		                        $val[productCode]
		                        <input type="hidden" name="storage[$i][unHookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][hookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][productName]" value="$val[productName]">
		                        <input type="hidden" name="storage[$i][productNo]" value="$val[productNo]">
		                        <input type="hidden" name="storage[$i][productId]" id="storagePN$i" value="$val[productId]">
		                        <input type="hidden" name="storage[$i][cost]" value="$val[cost]">
		                        <input type="hidden" name="storage[$i][stockId]" value="$val[inStockId]">
		                        <input type="hidden" name="storage[$i][stockName]" value="$val[inStockName]">
		                    </td>
		                    <td>
		                        $val[productName]
		                    </td>
		                    <td>
		                        $val[actNum]
		                    </td>
		                    <td>
		                        $val[hookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[hookAmount]</span>
		                    </td>
		                    <td>
		                        $val[unHookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[unHookAmount]</span>
		                    </td>
		                </tr>
EOT;
				}
				$markId = $val['mainId'];
			}
		}
		return $str;
	}

	/**
	 * 数据字典查询方法
	 * @param $dataArr
	 * @param $dataCode
	 * @return mixed
	 */
	function datadictArrSearch_d($dataArr, $dataCode) {
		$datadictDao = new model_system_datadict_datadict();
		$rtName = $datadictDao->getDataNameByCode($dataCode);
		$dataArr[$dataCode] = $rtName;
		return $dataArr;
	}

	/**
	 * 获取外购入库单关联订单的最晚入库时间
	 * @param $purOrderId
	 * @return mixed
	 */
	function getRecentlyDate($purOrderId) {
		$sql = "select max(auditDate) as auditDate,purOrderId from oa_stock_instock
					where isRed='0' and purOrderId='$purOrderId' and docType='RKPURCHASE'
				  	group by purOrderId";
		return $this->findSql($sql);
	}

	/**
	 *
	 * 获取订单物料的最近入库时间
	 * @param  $purOrderId
	 * @param  $productId
	 * @return mixed
	 */
	function getOrderProLastDate($purOrderId, $productId) {
		$sql = "select max(auditDate) as docDate from oa_stock_instock i INNER JOIN oa_stock_instock_item ii on(ii.mainId=i.id)
 					where isRed='0' and purOrderId='$purOrderId' and docType='RKPURCHASE' and docStatus='YSH' and ii.productId='$productId';";
		if($record = $this->findSql($sql)) {
			return $record[0]['docDate'];
		} else {
			return "";
		}
	}

	/**
	 * 检查入库数和收料单数量是否存在填写错误
	 * @param $object
	 * @return int
	 */
	function checkAudit_d($object) {
		$equItems = $object['items'];
		if($object['relDocType'] == 'RZCRK') {//源单类型为资产入库
			$requireoutitemDao = new model_asset_require_requireoutitem();
			foreach($equItems as $key => $val) {
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32) {
					$rs = $requireoutitemDao->find(array('id' => $val['relDocId']), null, 'number,executedNum');
					if($rs['executedNum'] + $val['actNum'] > $rs['number']) {
						return 4;//入库数量大于申请数量
					}
				}
			}
		}else if($object['relDocType'] == 'RSCJHD') {//源单类型为生产计划单
				$noticeequDao = new model_stock_withdraw_noticeequ();
				foreach($equItems as $key => $val) {
					$rs = $noticeequDao->find(array('mainId' => $val['relDocId'],'productId' => $val['productId']), null, 'number,executedNum');
					if($rs['executedNum'] + $val['actNum'] > $rs['number']) {
						return 4;//入库数量大于申请数量
					}
				}
		}else {
			$equipmentDao = new model_purchase_arrival_equipment();
			foreach($equItems as $key => $val) {
				$equipmentArr = $equipmentDao->find(array('id' => $val['relDocId']), null, 'qualityPassNum,contractId');
				if($val['actNum'] > $equipmentArr['qualityPassNum']) {
					return 2;//入库数大于质检合格数
				}
                if($equipmentArr['contractId']) {
                    $sql = "select sum(c.actNum) as actNumCount from oa_stock_instock_item c left join oa_purchase_arrival_equ d on c.relDocId = d.id where"
                        . " c.relDocId in(select id from oa_purchase_arrival_equ where contractId = {$equipmentArr['contractId']}) group by d.contractId";
                    $arr = $this->_db->getArray($sql);
                    $applyequipmentDao = new model_purchase_apply_applyequipment();
                    $applyequipmentArr = $applyequipmentDao->find(array('id' => $equipmentArr['contractId']), null, 'amountAll');
                    if(($arr[0]['actNumCount'] + $val['actNum']) > $applyequipmentArr['amountAll']) {
                        return 3;//收货数量大于订单数量
                    }
                }
			}
		}
		return 1;//正常
	}

	/**
	 * 审核，更新即时库存
	 * @param $conditions
	 * @param $obj
	 * @param bool $state
	 * @return mixed
	 */
	function updateArrivalNum_d($conditions, $obj, $state = true) {
		$equDao = new model_purchase_arrival_equipment();
		$equipmentRow = $equDao->findAll($conditions);
		if($equipmentRow && $state == true) {
			$equipmentRow[0]['arrivalNum'] = $equipmentRow[0]['arrivalNum'] - $obj['actNum'];
			$result = $equDao->edit_d($equipmentRow[0]);
		} else if($equipmentRow && $state == false) {
			$equipmentRow[0]['arrivalNum'] = $equipmentRow[0]['arrivalNum'] + $obj['actNum'];
			$result = $equDao->edit_d($equipmentRow[0]);
		}
		return $result;
	}

	/**
	 * 获取最新入库价
	 * @param $productIdArr
	 * @return int
	 */
	function getLastInPrice_d($productIdArr) {

		// 返回结果
		$results = array();

		if(empty($productIdArr)) {
			return $results;
		}

		// 查询物料范围
		$productIds = implode(",", $productIdArr);

		// 查询物料的最新入库单价
		$sql = "SELECT c.auditDate, i.productId, i.productCode, i.productName, i.price FROM
				oa_stock_instock c INNER JOIN oa_stock_instock_item i ON c.id = i.mainId
			WHERE
				c.isRed = 0 AND c.docStatus = 'YSH' AND i.productId IN($productIds)
				AND i.price <> 0
			ORDER BY i.productCode, c.auditDate DESC";
		$rows = $this->_db->getArray($sql);

		if($rows) {
			foreach($rows as $v) {
				if(!isset($results[$v['productId']])) {
					$results[$v['productId']] = $v['price'];
				}
			}
		}

		return $results;
	}

	/**
	 * 根据更新类型更新红字出库单的单价
	 * @param $params
	 * @return int
	 */
	function updateProductPrice_d($params) {

		// 更新数量
		$num = 0;

		// 截取更新类型
		$updateType = $params['updateType'];
		unset($params['updateType']);

        if($updateType == 1){
            if(isset($params['thisYear'])){
                unset($params['thisYear']);
            }

            if(isset($params['thisMonth'])){
                unset($params['thisMonth']);
            }
        }

		// 列表数据获取
		$this->getParam($params);
		$items = $this->listBySqlId('select_callist');

		// 非空的时候
		if(!empty($items)) {

			// 等待处理的数据
			$waitItems = array();

			// 缓存需要查询的物料id
			$productIdArr = array();

			// 循环处理数据
			foreach($items as $k => $v) {
				// 价格为0时才去处理
				if($v['price'] == 0) {

					// 物料id缓存
					if (!in_array($v['productId'], $productIdArr)) {
						$productIdArr[] = $v['productId'];
					}

					// 待处理数据缓存
					$waitItems[] = $v;
				}
			}

			// 如果有需要处理的数据，那么则进行单价获取
			if(!empty($productIdArr)) {

				// 根据更新类新获取相应的单据金额
				switch($updateType) {
					case 0: // 最初余额加权平均价
						$stockbalanceDao = new model_finance_stockbalance_stockbalance();
						$priceArr = $stockbalanceDao->getBeginPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 1: // 最新出库价
						$stockoutDao = new model_stock_outstock_stockout();
						$priceArr = $stockoutDao->getLastOutPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 2: // 最新入库价
						$priceArr = $this->getLastInPrice_d($productIdArr);
						break;
					default:
				}

				// 价格处理
				if(!empty($priceArr)) {

					// 出库子表初始化
					$stockinitemDao = new model_stock_instock_stockinitem();

					// 这里要更新
					foreach($waitItems as $v) {
						$price = $priceArr[$v['productId']];
						if($price && $price != 0) {
							$stockinitemDao->update(
								array('id' => $v['id']),
								array(
									'price' => $price,
									'subPrice' => round(bcmul($price, $v['actNum'], 6), 2)
								)
							);

							$num++;
						}
					}
				}
			}
		}

		return $num;
	}

    /**
     * 统计某原单关联的所有出入库单抵消后的物料数量
     * @param $relDocId
     * @param string $docStatus // 单据的相关状态,如 "'YSH','SPZ'"
     * @return array
     */
	function getRelativeItemsCount($relDocId,$docStatus = ''){
        $ItemsArr = array();
        $extSql = ($docStatus != '')? " and o.docStatus in ({$docStatus})" : "";
        if($relDocId != '') {
            $getIdsSql = "select GROUP_CONCAT(id) as ids from oa_stock_instock where relDocId = {$relDocId}";
            $ids = $this->_db->getArray($getIdsSql);
            $ids = ($ids) ? $ids[0]['ids'] : '';
            $chkSql = "SELECT
					c.productId,
					c.productCode,
					o.isRed,
					c.actNum,
					o.docStatus,
					'instock' AS objType
				FROM
					oa_stock_instock_item c
				LEFT JOIN oa_stock_instock o ON c.mainId = o.id
				WHERE
					o.id in ({$ids}) {$extSql}
				UNION ALL
				SELECT
					c.productId,
					c.productCode,
					o.isRed,
					c.actOutNum AS actNum,
					o.docStatus,
					'outstock' AS objType
				FROM
					oa_stock_outstock_item c
				LEFT JOIN oa_stock_outstock o ON c.mainId = o.id
				WHERE
					o.relDocId in ({$ids}) {$extSql};";
            $resultArr = $this->_db->getArray($chkSql);
            if ($resultArr) {
                foreach ($resultArr as $k => $v) {
                    if (!isset($ItemsArr[$v['productId']])) {
                        $ItemsArr[$v['productId']]['Code'] = $v['productCode'];
                        $ItemsArr[$v['productId']]['Num'] = 0;
                    }

                    if ($v['objType'] == 'instock' && $v['isRed'] == 0) {//入库蓝单
                        $ItemsArr[$v['productId']]['Num'] += $v['actNum'];
                    } else if ($v['objType'] == 'instock' && $v['isRed'] == 1) {//入库红单
                        $ItemsArr[$v['productId']]['Num'] -= $v['actNum'];
                    } else if ($v['objType'] == 'outstock' && $v['isRed'] == 0) {//出库蓝单
                        $ItemsArr[$v['productId']]['Num'] -= $v['actNum'];
                    } else if ($v['objType'] == 'outstock' && $v['isRed'] == 1) {//出库红单
                        $ItemsArr[$v['productId']]['Num'] += $v['actNum'];
                    }
                }
            }
        }
        return $ItemsArr;
    }

    /**
     * 关闭待报废呆料入库单
     *
     * @param $id
     */
    function closeIdleScrapInStock($id){
        // 更新入库单为已关闭状态
        $this->update(array('id'=>$id),array('docStatus'=>'YGB'));
        $stockoutStrategy = $this->stockinStrategyArr['RKDLBF'];
        $objArr = $this->get_d($id,new $stockoutStrategy());
        $serialnoDao = new model_stock_serialno_serialno();

        if($objArr){
            // 在待报废呆料仓中减去对应的物料数量
            $items = $objArr['items'];
            foreach ($items as $item){
                $stockId = $item['inStockId'];
                $productCode = $item['productCode'];
                $outNum = $item['actNum'];
                $updateSql = "UPDATE oa_stock_inventory_info set actNum = actNum-{$outNum},exeNum = exeNum-{$outNum} where stockId = {$stockId} and productCode = '{$productCode}';";
                $this->_db->query( $updateSql );

                // 物料序列号状态更新为库存中
                if(!empty($item['serialnoId'])) {
                    $sequenceId = $item['serialnoId'];

                    $sequencObj['seqStatus'] = "0";
                    $serialnoDao->update("id in($sequenceId)", $sequencObj);
                }
            }
        }
    }

	/**
	 * 根据采购订单ID获取入库单最大日期，如果没有匹配到，返回暂未有入库时间
	 * @param $purOrderIds
	 * @param string| $productIds
	 * @param bool|true $emptyReturnMsg
	 * @param bool|true $checkAsset
	 * @return string
	 */
	function getEntryDateForPurOrderId_d($purOrderIds, $productIds = "", $emptyReturnMsg = true, $checkAsset = true) {
		if (!$purOrderIds) {
			return $emptyReturnMsg ? "参数传入错误" : "";
		}

		if ($productIds) {
			$data = $this->_db->get_one("select max(c.auditDate) as entryDate
				from oa_stock_instock c LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
				where c.docStatus = 'YSH' AND purOrderId IN($purOrderIds) AND i.productId IN($productIds)");
		} else {
			$data = $this->_db->get_one("select max(auditDate) as entryDate from oa_stock_instock
				where docStatus = 'YSH' AND purOrderId IN($purOrderIds)");
		}

		if ($data['entryDate']) {
			return $data['entryDate'];
		} else if ($checkAsset) {
			// 查询新OA
			$result = util_curlUtil::getDataFromAWS('asset', 'GetLastAcceptanceSubmitDate', array(
					'comeFromId' => $purOrderIds)
			);
			try {
				$result = json_decode($result['data'], true);

				return !empty($result['data']['lastSubmitDate']) ?
					$result['data']['lastSubmitDate'] : ($emptyReturnMsg ? "暂未有入库时间" : "");
			} catch (Exception $e) {

			}
		}
		return $emptyReturnMsg ? "暂未有入库时间" : "";
	}
}