<?php
/**
 * @author huangzf
 * @Date 2012年5月11日 星期五 13:40:44
 * @version 1.0
 * @description:生产申请单 Model层
 */
class model_produce_apply_produceapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_produceapply";
		$this->sql_map = "produce/apply/produceapplySql.php";
		//生产申请单策略
		$this->applyStrategyArr = array(
			"CONTRACT" => array( //合同生产需求
				"relDocTypeName" => "合同",
				"model" => "model_produce_apply_strategy_contractapply"
			),
			"BORROW" => array( //借式用生产需求
				"relDocTypeName" => "借式用",
				"model" => "model_produce_apply_strategy_borrowapply"
			),
			"PRESENT" => array( //赠送生产需求
				"relDocTypeName" => "赠送",
				"model" => "model_produce_apply_strategy_presentapply"
			)
		);

		parent::__construct ();
	}

	/**
	 * 根据源单类型返回“合同”或者“源单”
	 */
	function getShowRelDoc_d($relDocTypeCode) {
		$contractTypeArr = array('HTLX-XSHT', 'HTLX-FWHT', 'HTLX-ZLHT', 'HTLX-YFHT');
		if (in_array($relDocTypeCode, $contractTypeArr)) {
			return '合同';
		} else {
			return '源单';
		}
	}

	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			if (is_array($object["items"])) {
				$this->start_d ();

				//获取归属公司名称
				$object['formBelong'] = $_SESSION['USER_COM'];
				$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
				$object['businessBelong'] = $_SESSION['USER_COM'];
				$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

				//生产申请单基本信息
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode("oa_produce_produceapply" ,"SCSQ");
				if ($object ['ExaStatus'] == "完成") {
					$object ['ExaDT'] = date ( "Y-m-d" );
				}
				$id = parent::add_d($object ,true);

				if ($id) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					foreach($object["items"] as $key => $val) {
						if ($val['isDelTag'] != 1) {
							//处理发货任务的ID
							$val['relDocItemId'] = $val["id"];
							unset($val["id"]);

							//处理产品ID
							$val['goodsId'] = $val['conProductId'];
							unset($val['conProductId']);

							//处理规格型号
							$val['pattern'] = $val['productModel'];
							unset($val['productModel']);

							//处理需求数量
							$val['needNum'] = $val['number'];
							unset($val['number']);

							//处理已下达数量
							$val['exeNum'] = $val['issuedProNum'];
							unset($val['issuedProNum']);

							//处理license
							$val['licenseConfigId'] = $val['license'];
							unset($val['license']);

							$val['relDocId'] = $object['relDocId']; //源单ID
							$val['relDocCode'] = $object['relDocCode']; //源单编号
							$val['weekly'] = $this->getWeekly_d($object['applyDate']); //获取当前周次
							$val['mainId'] = $id;
							$produceapplyitemDao->add_d($val ,true);

							//更新发货清单已下达数量
							$equDao = new model_contract_contract_equ();
							$equDao->updateById(
								array(
									"id" => $val['relDocItemId'] ,
									'issuedProNum' => ($val['exeNum'] + $val['produceNum'])
								)
							);
						}
					}

					$this->applyMail_d($id);
				}

				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 其他部门新增
	 */
	function addDepartment_d($obj) {
		try {
			$this->start_d ();

			//获取归属公司名称
			$obj['formBelong'] = $_SESSION['USER_COM'];
			$obj['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$obj['businessBelong'] = $_SESSION['USER_COM'];
			$obj['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$codeDao = new model_common_codeRule ();
			$obj['docCode'] = $codeDao->stockCode("oa_produce_produceapply" ,"SCSQ"); //单据编号

			$datadictDao = new model_system_datadict_datadict();
			$obj['relDocType'] = $datadictDao->getDataNameByCode($obj['relDocTypeCode']); //源单类型

			$id = parent::add_d($obj ,true);

			if ($id) {
				if (is_array($obj["items"])) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					foreach($obj["items"] as $key => $val) {
						$val['relDocId'] = $obj['relDocId']; //源单ID
						$val['relDocCode'] = $obj['relDocCode']; //源单编号
						$val['weekly'] = $this->getWeekly_d($obj['applyDate']); //获取当前周次
						$val['mainId'] = $id;
						$produceapplyitemDao->add_d($val ,true);
					}
				}
			}

			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array($object["items"])) {
				$this->start_d ();
				$editResult = parent::edit_d($object ,true);

				if ($editResult) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					$equDao = new model_contract_contract_equ();
					foreach($object["items"] as $key => $val) {
						$produceapplyitemObj = $produceapplyitemDao->get_d($val["id"]);
						$equObj = $equDao->get_d($val['relDocItemId']);
						if ($val['isDelTag'] != 1) {
							$val['state'] = 0;
							$produceapplyitemDao->edit_d($val ,true);
							//更新发货清单已下达数量
							$equDao->updateById(
								array(
									"id" => $val['relDocItemId'] ,
									'issuedProNum' => ($equObj['issuedProNum'] + $val['produceNum'])
								)
							);
						} else {
							$produceapplyitemDao->deleteByPk($val["id"]);
						}
					}

					$this->applyMail_d($object["id"]);
				}

				$this->commit_d ();
				return $object["id"];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 其他部门编辑保存
	 */
	function editDepartment_d($obj) {
		try {
			$this->start_d ();

			$datadictDao = new model_system_datadict_datadict();
			$obj['relDocType'] = $datadictDao->getDataNameByCode($obj['relDocTypeCode']); //源单类型

			$editResult = parent::edit_d($obj ,true);

			if ($editResult) {
				$produceapplyitemDao = new model_produce_apply_produceapplyitem();
				foreach($obj["items"] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['weekly'] = $this->getWeekly_d($obj['applyDate']); //获取当前周次
						if ($val["id"] > 0) {
							$val['state'] = 0;
							$produceapplyitemDao->edit_d($val ,true);
						} else {
							$val['relDocId'] = $obj['relDocId']; //源单ID
							$val['relDocCode'] = $obj['relDocCode']; //源单编号
							$val['mainId'] = $obj["id"];
							$produceapplyitemDao->add_d($val ,true);
						}
					} else {
						$produceapplyitemDao->deleteByPk($val["id"]);
					}
				}
			}

			$this->commit_d ();
			return $obj["id"];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 变更操作
	 */
	function change_d($object){
		try{
			$this->start_d();

			$changeLogDao = new model_common_changeLog('produceapply'); //实例化变更类

			//从表处理
			if (is_array($object['items'])) {
				foreach ($object['items'] as $key => $val) {
					if ($val['id']) {
						$object['items'][$key]['oldId'] = $val['id'];
					} else {
						$object['items'][$key]['relDocId'] = $object['relDocId'];
						$object['items'][$key]['relDocCode'] = $object['relDocCode'];
						$object['items'][$key]['weekly'] = $this->getWeekly_d($object['applyDate']); //获取当前周次
					}
				}
			}

			$tempObjId = $changeLogDao->addLog($object); //建立变更信息

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 开启保存申请单
	 */
	function openApply($id) {
		$this->updateDocStatus ( $id );
		return true;
	}

	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$produceapplyitemDao = new model_produce_apply_produceapplyitem ();
		$produceapplyitemDao->sort="id";
		$produceapplyitemDao->searchArr ['mainId'] = $id;
		$object ["items"] = $produceapplyitemDao->listBySqlId ();
		return $object;
	}

	/**
	 * 获取关联源单信息 策略控制
	 * @param  $id
	 */
	function ctGetRelDocInfo($id, iproduceapply $iproduceapply) {
		return $iproduceapply->getRelDocInfo ( $id );
	}

	/**
	 * 下达生产申请,生产申请基本信息 模板赋值  策略控制
	 * @param  $obj
	 */
	function ctAssignBaseAtApply($obj, iproduceapply $iproduceapply, show $show) {
		$show->assign ( "applyDate", date ( "Y-m-d" ) );
		$show->assign ( "applyUserCode", $_SESSION ['USER_ID'] );
		$show->assign ( "applyUserName", $_SESSION ['USERNAME'] );
		return $iproduceapply->assignBaseAtApply ( $obj, $show );
	}

	/**
	 * @description 下达生产申请,清单显示模板策略控制
	 * @param $rows
	 */
	function ctShowItemAtApply($rows, iproduceapply $iproduceapply) {
		return $iproduceapply->showItemAtApply ( $rows );
	}

	/**
	 * 新增生产申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($relArr, $relItemArr, iproduceapply $iproduceapply) {
		return $iproduceapply->dealRelInfoAtAdd ( $relArr, $relItemArr );
	}

	/**
	 * 修改生产申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($relArr, $relItemArr,$lastItemArr, iproduceapply $iproduceapply) {
		return $iproduceapply->dealRelInfoAtEdit ( $relArr, $relItemArr,$lastItemArr );
	}

	/**
	 * 更新申请单下达状态
	 * @param  $id
	 */
	function updateDocStatus($id) {
		$sql = "select sum(produceNum) as produceNum,sum(exeNum) as exeNum  from oa_produce_produceapply_item  where mainId=$id ;";
		$result = $this->findSql ( $sql );
		if ($result [0] ['produceNum'] > $result [0] ['exeNum']) {
			if ($result [0] ['exeNum'] > 0) {
				$this->updateById ( array ("id" => $id, "docStatus" => "1" ) );
			} else {
				$this->updateById ( array ("id" => $id, "docStatus" => "0" ) );
			}
		} else {
			$this->updateById ( array ("id" => $id, "docStatus" => "2" ) );
		}
	}

	/**
	 * 打回
	 */
	function back_d( $obj ) {
		try {
			$this->start_d ();

			$oldObj = $this->get_d($obj["id"]);
			$back['reason'] = $obj['backReason']; //当前的打回原因
			$back['name'] = $_SESSION['USERNAME']; //当前的打回人
			$obj['backReason'] = $_SESSION['USERNAME'].'&nbsp;&nbsp;'.date('Y-m-d H:i:s').'<br>'.$obj['backReason'];
			if ($oldObj['backReason']) {
				$obj['backReason'] = $oldObj['backReason'].'<breakpoint>'.$obj['backReason']; //拼接到上一次的原因
			}

			$itemDao = new model_produce_apply_produceapplyitem();
			if (is_array($obj['items'])) {
				$equDao = new model_contract_contract_equ();
				foreach ($obj['items'] as $key => $val) {
					if ($val['state'] == '0') {
						$val['state'] = 2;
						$itemDao->updateById($val);
						$itemObj = $itemDao->get_d($val['id']);
						$equObj  = $equDao->get_d($itemObj['relDocItemId']);
						//更新发货清单已下达数量
						$equDao->updateById(
							array(
								"id" => $itemObj['relDocItemId'] ,
								'issuedProNum' => ($equObj['issuedProNum'] - $itemObj['produceNum'])
							)
						);
					}
				}
			}

			$items = $itemDao->findAll(array('mainId' => $obj['id'] ,'state' => 0));
			if (!empty($items)) {
				$obj['docStatus'] = 9; // 如果没有全部打回，则更新单据状态为部分打回
			}

			$this->updateById($obj);
			$this->backApplyMail_d($obj["id"] ,$back);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 关闭
	 */
	function close_d( $obj ) {
		try {
			$this->start_d ();

			$this->updateById($obj);

			if (is_array($obj["items"])) {
				$itemDao = new model_produce_apply_produceapplyitem();
				$closeNum = 0; //关闭的条数初始化为0
				$equIdArr = array();
				foreach ($obj["items"] as $key => $val) {
					if (isset($val['state'])) {
						if ($val['state'] == 0) { //本次关闭的
							array_push($equIdArr ,$val["id"]);
							$val['state'] = 1;
							$itemDao->updateById($val);
						}
						$closeNum++;
					}
				}

				if ($closeNum == count($obj["items"])) { //全部关闭则把单据状态改为关闭
					$this->updateById(array("id" => $obj["id"] ,'docStatus' => 3));
				}

				if (!empty($equIdArr)) {
					$this->closeApplyMail_d($obj["id"] ,implode(',' ,$equIdArr));
				}
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 审批通过
	 */
	function dealAfterAudit_d($id) {
		try {
			$this->start_d ();

			$this->applyMail_d($id); //邮件通知

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 变更审批完成后
	 */
	function dealAfterAuditChange_d($objId ,$userId){
		$obj = $this->get_d($objId);
		if($obj['ExaStatus'] == '完成') {
			try{
				$this->start_d();

				$changeLogDao = new model_common_changeLog('produceapply');
				$changeLogDao->confirmChange_d( $obj );

				$this->updateById(array('id' => $obj['originalId'] ,'docStatus' => 1));
				$this->dealDocStatus_d($obj['originalId']); //处理单据状态

				$this->commit_d();
				return true;
			} catch(Exception $e) {
				$this->rollBack();
				return false;
			}
		} else {
			try{
				$this->start_d();

				$this->updateById(array('id' => $obj['originalId'] ,'docStatus' => 1 ,'ExaStatus' => '完成'));

				$this->commit_d();
				return true;
			}catch(Exception $e){
				$this->rollBack();
				return false;
			}
		}
	}

	/**
	 * 下达生产需求邮件通知
	 */
	function applyMail_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('produceapplyApply' ,null ,array("id" => $id));
	}

	/**
	 * 打回生产申请邮件通知
	 */
	function backApplyMail_d($id ,$back) {
		$obj = $this->get_d( $id );
		$exaInfo = array(
			"id" => $id
			,'backReason' => $back['reason']
			,'backName' => $back['name']
		);
		$this->mailDeal_d('produceapplyBack' ,$obj['createId'] ,$exaInfo);
	}

	/**
	 * 关闭生产申请物料邮件通知
	 */
	function closeApplyMail_d($id ,$equIds) {
		$obj = $this->get_d( $id );
		$exaInfo = array(
			"id"      => $id
			,'equIds' => $equIds
		);
		$this->mailDeal_d('produceapplyClose' ,$obj['createId'] ,$exaInfo);
	}

	/**
	 * 根据日期获取所在月份的周次（从1开始）
	 */
	function getWeekly_d ($date) {
		$firstDay = date('Y-m-01' ,strtotime($date)); //获取当前月份的第一天日期
		$dayNum   = date('j' ,strtotime($date)); //日期是几日
		$week     = date('w' ,strtotime($firstDay)); //获取当前月份第一天是星期几（0星期日-6星期六）
		$weekly = ceil(($dayNum + $week) / 7);
		return $weekly;
	}

	/**
	 * 根据id处理单据状态(如果完成则改变状态，否则不做处理)
	 */
	function dealDocStatus_d($id) {
		$itemDao = new model_produce_apply_produceapplyitem();
		$itemObjs = $itemDao->findAll(array('mainId' => $id ,'state' => 0));
		if (is_array($itemObjs)) {
			$rs = true;
			foreach ($itemObjs as $key => $val) {
				if ($val['produceNum'] != $val['exeNum']) {
					$rs = false;
					break;
				}
			}
			if ($rs) {
				$this->updateById(array("id" => $id ,'docStatus' => 2));
			}
		}
	}

	/**
	 * 获取指定日期所在星期的开始日期与结束日期的时间
	 * return array('startDate' ,'endDate')
	 */
	function getWeekDate_d($date = '') {
		$date = empty($date) ? date('Y-m-d') : $date;
		$timestamp = strtotime($date);
		$N = date('N' ,$timestamp); //1-7表示星期一到星期天
		$dateArr = array();
		$dateArr['startDate'] = date('Y-m-d' ,$timestamp - ($N - 1) * 86400);
		$dateArr['endDate'] = date('Y-m-d' ,$timestamp + (7 - $N) * 86400);
		return $dateArr;
	}


	/**
	 * 修改部分打回
	 */
	function editBack_d($obj) {
		try {
			$this->start_d ();
			$editResult = parent::edit_d($obj ,true);

			if ($editResult && is_array($obj['items'])) {
				$itemDao = new model_produce_apply_produceapplyitem();
				$equDao = new model_contract_contract_equ();
				foreach($obj["items"] as $key => $val) {
					$equObj = $equDao->get_d($val['relDocItemId']);
					if ($val['isDelTag'] != 1) {
						$val['state'] = 0;
						$itemDao->edit_d($val ,true);
						//更新发货清单已下达数量
						$equDao->updateById(
							array(
								"id" => $val['relDocItemId'] ,
								'issuedProNum' => ($equObj['issuedProNum'] + $val['produceNum'])
							)
						);
					} else {
						$itemDao->deleteByPk($val["id"]);
					}
				}

				$this->applyMail_d($obj["id"]);
			}

			$this->commit_d ();
			return $obj["id"];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/**
	 * 下达任务时验证
	 * @param $id
	 * @param $itemIds 从表id,默认为空
	 * @return array
	 */
	function taskCheck_d($id,$itemIds = null) {
		// 需求信息
		$obj = $this->get_d($id);
		// 验证需求下达状态
		if ($obj['docStatus'] != '0' && $obj['docStatus'] != '1' && $obj['docStatus'] != '9') {
			return array('pass' => 0, 'msg' => "只有下达状态为未下达/部分下达/部分打回的单据才能下达生产任务!");
		} else {
			$itemDao = new model_produce_apply_produceapplyitem();
			if(!is_null($itemIds)){
				$rs = $itemDao->findAll("id in(".$itemIds.")",null,'productCode,produceNum,exeNum,proType');
			}else{
				$rs = $itemDao->findAll(array('mainId' => $id),null,'productCode,produceNum,exeNum,proType');
			} 
			if (!empty($rs)) {
				$proType = '';
				$proTypeId = 0;
				foreach ($rs as $v){
// 					if($proType == ''){
// 						$proType = $v['proType'];
// 						$proTypeId = $v['proTypeId'];
// 					}else{
// 						if($proType != $v['proType']){
// 							return array('pass' => 0, 'msg' => "不同物料类型的物料不能合并生成一张单据，请重新选择!");
// 							break;
// 						}
// 					}
					if($v['exeNum'] >= $v['produceNum']){
						return array('pass' => 0, 'msg' => "物料编号为【".$v['productCode']."】的物料已经下达完毕,请勿重复下达!");
						break;
					}
				}
			}else{
				return array('pass' => 0, 'msg' => "没有可以下达的物料!");
			}
		}
		return array('pass' => 1, 'msg' => '', 'proType' => $proType, 'proTypeId' => $proTypeId);
	}
}