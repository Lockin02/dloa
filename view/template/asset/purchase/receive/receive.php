<?php

/**
 *
 * 资产验收model
 * @author fengxw
 *
 */
class model_asset_purchase_receive_receive extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_receive";
		$this->sql_map = "asset/purchase/receive/receiveSql.php";
		parent::__construct ();
	}

	/**
	 * 新建保存资产验收及明细单
	 */
	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['receiveItem'])){
				msg ( '请填写好资产验收明细单的信息！' );
				throw new Exception('资产验收信息不完整，保存失败！');
			}
// 			$codeDao = new model_common_codeRule ();
// 	       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_receive";
// 	       	$applyDateArr = $this->_db->get_one($sql);
// 	       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
// 	       	$thisDate =  day_date;
// 	       	if( $applyDate!= $thisDate ){
// 				$object ['name']=$codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],true);
// 	       	}else{
// 				$object ['name']=$codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],false);
// 	       	}
// 			$id=parent::add_d($object,true);

			$sendContent="<table><tr><td>验收人:</td><td>" . $object ['salvage'] . "</td><td>采购单编号:</td><td>" . $object ['purchaseContractCode'] . "</td></tr>"
											."<tr><td>验收日期:</td><td>" . $object ['limitYears'] . "</td><td>验收金额:</td><td>" . $object ['amount'] . "</td></tr>"
											."<tr><td>验收结果:</td><td colspan='3'>" . $object ['result'] . "</td></tr></table>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>资产名称</b></td><td><b>规格</b></td><td><b>数量</b></td><td><b>单价</b></td><td><b>金额</b></td><td><b>配置</b></td><td><b>备注</b></td></tr>";
			//保存明细单
// 			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			$applyItemDao=new model_asset_purchase_apply_applyItem();
			$arrivalDao=new model_purchase_arrival_arrival();
			$applyDao = new model_asset_purchase_apply_apply();
// 			$requirementDao = new model_asset_require_requirement();
			$applyArr = array();
			//初始化推送给aws的数组
			$obj = array();//用来存放推送的数据
			$detail = array();//用来存放明细
			//是否为交付部采购,不是则为行政部采购
			$isDelivery = empty($object['purchaseContractId']) ? false : true;
			
			if($isDelivery){//由交付部执行采购
				//获取采购需求id
				$equDao=new model_purchase_contract_equipment();
				$planEquRows=$equDao->getPlanEqu_d($object['purchaseContractId']);
				foreach ($planEquRows as $val){
					$applyArr[$val['id']] = $val['applyId'];
				}
				//来源单号及id
				$obj['comeFromNo'] = $object['purchaseContractCode'];
				$obj['comeFromId'] = $object['purchaseContractId'];
			}else{//由行政部执行采购
				//更新采购状态为【完成】，值设置为3
				$applyDao->updatePurchState($object['applyId'], 3);
				//来源单号及id
				$obj['comeFromNo'] = $object['code'];
				$obj['comeFromId'] = $object['applyId'];
			}
			$seNum=1;
			foreach($object['receiveItem'] as &$val){
				if($val['isDelTag']!="1"){
// 					$val['receiveId']=$id;
// 					$val['isCard']="0";
					if($isDelivery){//由交付部执行采购
						$val['applyId'] = $applyArr[$val['contractId']];
					}else{//由行政部执行采购
						$val['applyId'] = $object['applyId'];
					}
// 					$receiveItemDao->add_d($val);
					//更新采购申请明细的验收数量
					if(!empty($val['applyEquId'])){//添加判断 by chengl 因为采购订单的验收无需做此操作
						$applyItemDao->updateAmountCheck($val['applyEquId'],$val['checkAmount']);
						array_push($detail, array(
							'comeFromItemId' => $val['applyEquId'],
							'productId' => '',
							'productCode' => '',
							'productName' => $val['assetName'],
							'pattern' => $val['spec'],
							'unit' => '',
							'num' => $val['checkAmount'],
							'price' => $val['price'],
							'amount' => $val['amount']
						));
					}
	
					if(!empty($val['arrivalEquId'])){//add by huangzf 更新源单收料通知单信息
						$arrivalDao->updateInStock($object['arrivalId'], $val['arrivalEquId'], $val['assetId'], $val['checkAmount']);
						array_push($detail, array(
							'comeFromItemId' => $val['contractId'],
							'productId' => $val['productId'],
							'productCode' => $val['sequence'],
							'productName' => $val['assetName'],
							'pattern' => $val['spec'],
							'unit' => $val['units'],
							'num' => $val['checkAmount'],
							'price' => $val['price'],
							'amount' => $val['amount']
						));
					}
					$sendContent .= <<<EOT
													<tr align="center" >
													<td>$seNum</td>
													<td>$val[assetName]</td>
													<td>$val[spec]</td>
													<td>$val[checkAmount]</td>
													<td>$val[price]</td>
													<td>$val[amount]</td>
													<td>$val[deploy]</td>
													<td>$val[remark]</td>
												</tr>
EOT;
					$seNum++;
				}
			}
			//主表信息
			$obj['acceptance']['userId'] = $object['salvageId'];
			$obj['acceptance']['userName'] = $object['salvage'];
			$obj['acceptance']['result'] = $object['result'];
			$obj['acceptance']['amount'] = $object['amount'];
			//明细信息
			$obj['acceptance']['detail'] = $detail;
			// 接入aws
			// 1.推送验收单数据给aws
			// 验收类型为【采购收货通知】
			$obj['comeFrom'] = 'YSLY-01';
			$result = util_curlUtil::getDataFromAWS('asset', 'createAcceptanceByPurchase', $obj);
			// 2.改变需求申请单状态,置为【待验收】
			if($result){
				if($isDelivery){//由交付部执行采购
					foreach($object['receiveItem'] as $val){
						if($val['isDelTag']!="1"){
							$val['applyId'] = $applyArr[$val['contractId']];
							$requirementId = $applyDao->getRequirementId($val['applyId']);//获取采购需求对应的资产需求申请id
							$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
									'requireId' => $requirementId, 'applyStatus' => '1041')
							);
// 							$requirementDao->updateRecognize($requirementId);
						}
					}
				}else{//由行政部执行采购
					$requirementId = $applyDao->getRequirementId($object['applyId']);//获取采购需求对应的资产需求申请id
					$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
							'requireId' => $requirementId, 'applyStatus' => '1041')
					);
// 					$requirementDao->updateRecognize($requirementId);
				}
					
				//发送邮件给采购员
				if($result && !empty($object['purchaseContractId']) && $seNum>1){
					$orderDao=new model_purchase_contract_purchasecontract();
					$orderObj=$orderDao->get_d($object['purchaseContractId']);
					$emailDao = new model_common_mail();
					$emailDao->mailClear ( "资产验收通知", $orderObj['sendUserId'], $sendContent );
				}
			}
			
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['receiveItem'] )) {
				$editresult = parent::edit_d ( $object, true );
				$receiveItemDao=new model_asset_purchase_receive_receiveItem();
				$itemsArr = $this->setItemMainId ( "receiveId", $object ['id'], $object ['receiveItem'] );
				$itemsObj = $receiveItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 设置关联清单的从表的主表id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/**
	 * 单据撤销 - create by kuangzw
	 */
	function ajaxRevocation_d($id){
		try{
			$this->start_d();

			//查询现有业务信息
			$obj = $this->find(array('id' => $id));

			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			//如果是从收料单下来的,则需要反向更新收料单数量
			if($obj['arrivalId']){
				//保存明细单
//				$receiveItemDao=new model_asset_purchase_receive_receiveItem();
				$receiveItemArr = $receiveItemDao->findAll(array('receiveId' => $id));
//				print_r($receiveItemArr);

				$arrivalDao=new model_purchase_arrival_arrival();
				foreach($receiveItemArr as $key => $val){
					if(!empty($val['arrivalEquId'])){//add by huangzf 更新源单收料通知单信息
						$arrivalDao->updateInStockCancel($obj['arrivalId'], $val['arrivalEquId'], $val['assetId'], $val['checkAmount']);
					}
				}
			}

			//删除单据
			$this->revocateSendMail_d($id);
			$receiveItemDao->deleteByFk($id);
			$this->deletes($id);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 单据撤销邮件通知 - create by zengzx
	 */
	function revocateSendMail_d($id){
		try{
			$this->start_d();

			//查询现有业务信息
			$object = $this->get_d($id);
			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			$receiveItemDao->searchArr['receiveId']=$id;
			$itemObj = $receiveItemDao->list_d();
			$object['receiveItem']=$itemObj;

			$sendContent="<table><tr><td>验收人:</td><td>" . $object ['salvage'] . "</td><td>采购单编号:</td><td>" . $object ['purchaseContractCode'] . "</td></tr>"
											."<tr><td>验收日期:</td><td>" . $object ['limitYears'] . "</td><td>验收金额:</td><td>" . $object ['amount'] . "</td></tr>"
											."<tr><td>验收结果:</td><td colspan='3'>" . $object ['result'] . "</td></tr></table>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>资产名称</b></td><td><b>规格</b></td><td><b>数量</b></td><td><b>单价</b></td><td><b>金额</b></td><td><b>备注</b></td></tr>";

			$seNum=1;
			foreach($object['receiveItem'] as $val){
				//更新采购申请明细的验收数量
				$sendContent .= <<<EOT
												<tr align="center" >
												<td>$seNum</td>
												<td>$val[assetName]</td>
												<td>$val[spec]</td>
												<td>$val[checkAmount]</td>
												<td>$val[price]</td>
												<td>$val[amount]</td>
												<td>$val[remark]</td>
											</tr>
EOT;
				$seNum++;
			}
//			echo $sendContent;
			$emailDao = new model_common_mail();
			include (WEB_TOR . "model/common/mailConfig.php");
			$this->mailArr = $mailUser[$this->tbl_name];
			$emailDao->mailClear ( "资产验收撤回通知", $this->mailArr['sendUserId'], $sendContent );
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * 新建保存资产验收及明细单
	 */
	function addByRequirein_d($object){
		try{
			$this->start_d();
			
			//去掉页面删除的数据
			foreach($object['receiveItem'] as $key => $val){
				if($val['isDelTag'] == '1'){
					unset($object['receiveItem'][$key]);
				}
			}
			//统一实例化
// 			$codeDao = new model_common_codeRule ();
// 			$receiveItemDao = new model_asset_purchase_receive_receiveItem();
			$requireinDao = new model_asset_require_requirein();
			$requireinitemDao = new model_asset_require_requireinitem();
// 			$sql = "SELECT MAX(createTime) as createTime from oa_asset_receive";
// 			$applyDateArr = $this->_db->get_one($sql);
// 			$applyDate = substr($applyDateArr['createTime'], 0, 10);
// 			$thisDate = day_date;
// 			if( $applyDate != $thisDate ){
// 				$object['name'] = $codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],true);
// 			}else{
// 				$object['name'] = $codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],false);
// 			}
// 			$id = parent::add_d($object,true);
			//初始化推送给aws的数组
			$obj = array();//用来存放推送的数据
			$detail = array();//用来存放明细
			$amount = 0;//用来计算主表总额
			$productDao = new model_stock_productinfo_productinfo();
			foreach($object['receiveItem'] as $val){
// 				$val['receiveId'] = $id;
// 				$val['isCard'] = '0';
// 				$receiveItemDao->add_d($val);//保存验收明细单
				$requireinitemDao->updateReceiveNum($val['requireinItemId'],$val['checkAmount']);//更新物料转资产明细验收数量
				$detailAmount = $val['checkAmount'] * $val['productPrice'];//用来计算明细总额
				//获取物料单位
				$rs = $productDao->find(array('id' => $val['assetId']),null,'unitName');
				array_push($detail, array(
					'comeFromItemId' => $val['requireinItemId'],
					'productId' => $val['assetId'],
					'productCode' => $val['assetCode'],
					'productName' => $val['assetName'],
					'pattern' => $val['spec'],
					'unit' => $rs['unitName'],
					'num' => $val['checkAmount'],
					'price' => $val['productPrice'],
					'amount' => $detailAmount
				));
				$amount += $detailAmount;
			}

			$requireinId = $object['requireinId'];
			$requireinDao->updateReceiveStatus($requireinId);//更新物料转资产申请验收状态
// 			$requireinDao->updateRequireInStatus($requireinId);//更新需求申请物料转资产状态			
			// 验收类型为【物料转资产】
			$obj['comeFrom'] = 'YSLY-02';
			//来源单号及id
			$obj['comeFromNo'] = $object['requireinCode'];
			$obj['comeFromId'] = $object['requireinId'];
			//主表信息
			$obj['acceptance']['userId'] = $object['salvageId'];
			$obj['acceptance']['userName'] = $object['salvage'];
			$obj['acceptance']['result'] = $object['result'];
			$obj['acceptance']['amount'] = $amount;
			//明细信息
			$obj['acceptance']['detail'] = $detail;
			// 接入aws
			// 1.推送验收单数据给aws
			$result = util_curlUtil::getDataFromAWS('asset', 'createAcceptanceByPurchase', $object);
			// 2.改变需求申请单状态,置为【待验收】
			if($result){
				$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
					'requireId' => $requireinId, 'applyStatus' => '1041')
				);
			}
			
			$this->commit_d();
			return $result;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
}
