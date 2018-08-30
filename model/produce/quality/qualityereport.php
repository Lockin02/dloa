<?php

/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 16:46:27
 * @version 1.0
 * @description:检验报告 Model层
 */
class model_produce_quality_qualityereport extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_produce_quality_ereport";
		$this->sql_map = "produce/quality/qualityereportSql.php";
		parent::__construct();
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/**
	 * 邮件获取
	 * @param $thisKey
	 * @return array
	 */
	function getMail_d($thisKey) {
		include(WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
			'sendName' => '');
		return $mailArr;
	}

	//状态返回
	function rtStatus($thisVal) {
		switch ($thisVal) {
			case "WTJ" :
				return "未提交";
			case "WSH" :
				return "待审核";
			case "YSH" :
				return "合格";
			case "RBJS" :
				return "让步接收";
			case "BHG" :
				return "不合格";
			case "BH" :
				return "驳回";
			case "BC" :
				return "保存";
			default :
				return "非法状态";
		}
	}

	//数据字典
	public $datadictFieldArr = array(
		'qualityType', 'relDocType'
	);

	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
        $ereportequitemProduceNum = 0;// 不合格物料数量
        $ereportequitemQualitedNum = 0;// 合格物料数量
        $hasEmptyQualitedNum = false;// 有合格数为0的物料

		//质检内容取数
		if(isset($object ['items'])){
			$items = $object ['items'];
			unset($object ['items']);
		}

		//质检物料取数
		if(isset($object ['ereportequitem'])){
			$ereportequitem = $object ['ereportequitem'];
			unset($object ['ereportequitem']);
            foreach ($ereportequitem as $k => $v){// 清除多余字段
                unset($ereportequitem[$k]['serialnoChkedNum']);
            }
		}

		//不合格质检明细
		if(isset($object['failureitem'])){
			$failureitem = $object['failureitem'];
			unset($object ['failureitem']);
		}
		try {
            //实例化任务对象
            $qualityTaskDao = new model_produce_quality_qualitytask();
            $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyItemDao = new model_produce_quality_qualityapplyitem();

			//源单类型为生产检验的，质检内容允许为空
			if ($object['relDocType'] != 'ZJSQYDSC' && !is_array($items)) {
				throw new Exception ("单据信息不完整，请确认！");
			}

			$this->start_d();

			//单号生成
			$codeDao = new model_common_codeRule ();
			$object ['docCode'] = $codeDao->stockCode("oa_produce_quality_ereport", "ZJBG");

			//根据提交值设置状态 -- 对于提交状态的单据特殊处理
			if ($object['auditStatus'] == "WSH") {
				if ($object['produceNum'] == 0 && $object['relDocType'] != 'ZJSQDLBF') {
					//检验合格的直接通过审核，不需要再处理
					$object['auditStatus'] = 'YSH';
					$object['ExaStatus'] = '完成';
				} else if ($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 呆料报废质检需要通过审核提交审批流处理,如果此原单还存在未通过物料,则此报告状态为完成
                    $object['auditStatus'] = 'YSH';
                    $object['ExaStatus'] = '待提交';
                } else {
					$object['ExaStatus'] = '待提交';
				}
			}
			if ($object['auditStatus'] == "BC") {
				//如果是保存状态，则将质检任务的物料状态进行变更
				$object['ExaStatus'] = '待提交';
			}
            if ($object['qualityType'] == 'ZJFSCJ' ) {
                unset($object['standardId']);
            }
			//新增
			$object = $this->processDatadict($object);
			$id = parent::add_d($object, true);

			//质检内容
			if($items){
				$qualityereportitemDao = new model_produce_quality_qualityereportitem();
				$items = util_arrayUtil::setItemMainId("mainId", $id, $items);
				$qualityereportitemDao->saveDelBatch($items);
			}

			//质检设备内容
			if($ereportequitem){
				$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
				$ereportequitem = util_arrayUtil::setItemMainId("mainId", $id, $ereportequitem);
				$qualityereportequitemDao->saveDelBatch($ereportequitem);
			}

			//不合格设备
			if ($failureitem) {
				$failureitemDao = new model_produce_quality_failureitem();
				$failureitem = util_arrayUtil::setItemMainId("mainId", $id, $failureitem);
				$failureitemDao->saveDelBatch($failureitem);
			}

			//根据提交值设置状态 -- 对于提交状态的单据特殊处理
			if($ereportequitem){
				foreach ($ereportequitem as $val) {
					if ($val['isDelTag'] != "1") {
						//计算出已质检数
						$checkedNumArr = $qualityTaskItemDao->find(array("id" => $val['relItemId']), null, "checkedNum,assignNum,standardNum");
						$checkedNum = $checkedNumArr['checkedNum'];
						$checkedNum += $val['thisCheckNum'];
                        $standardNum = $checkedNumArr['standardNum'];
                        $standardNum += $val['qualitedNum'];
                        $qualitedNum = $standardNum;

						if ($object['auditStatus'] == "BC") {
							//保存状态
							$checkStatus = "";
						} elseif (($checkedNumArr['assignNum'] - $checkedNum) == 0) {
							//检验状态
							$checkStatus = "YJY";
						} else {
							$checkStatus = "BFJY";
						}

						$cklTypeUpdateArr = array();
                        $realCheckNum = $checkedNum;
                        if($object['qualityType'] == "ZJFSCJ"){
                            $realCheckNum = $val['samplingNum'];// 抽检的实际检查数量等于抽检数量
                            // $qualitedNum = bcsub($val['thisCheckNum'],$val['produceNum']);
                            $cklTypeUpdateArr['checkType'] = 'ZJFSCJ';
                            $cklTypeUpdateArr['checkTypeName'] = '抽检';
                        }else if($object['qualityType'] == "ZJFSQJ"){
                            $cklTypeUpdateArr['checkType'] = 'ZJFSQJ';
                            $cklTypeUpdateArr['checkTypeName'] = '全检';
                        }

						//更新状态以及合格数量和已质检数
						$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' =>$qualitedNum, "realCheckNum" => $realCheckNum,"checkedNum" => $checkedNum, "thisCheckNum" => ($checkedNumArr['assignNum'] - $checkedNum));
                        if(!empty($cklTypeUpdateArr) && isset($cklTypeUpdateArr['checkType'])){
                            $qualityTaskItem['checkType'] = $cklTypeUpdateArr['checkType'];
                            $qualityTaskItem['checkTypeName'] = $cklTypeUpdateArr['checkTypeName'];
                        }
						$qualityTaskItemDao->updateById($qualityTaskItem);

                        $ereportequitemProduceNum += $val['produceNum'];
                        $ereportequitemQualitedNum += $val['qualitedNum'];
                        if($val['qualitedNum'] <= 0){
                            $hasEmptyQualitedNum = true;
                        }
					}
				}
			}

			//重新计算任务状态
			$qualityTaskDao->renewStatus_d($object['mainId']);

			//如果质检是合格的，开始进入上级流程
            if($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 如果是呆料报废申请的
                $qualityapplyArr = $qualityapplyDao->find(array("id" => $object['applyId']));
                if($ereportequitemQualitedNum <= 0){// 不合格直接质检完成,并反写其他出库单状态未审核
                    $this->dealAfterDldfProcess_d($id,null,"zj_disPass",$object['auditStatus']);
                }else{
                    $this->dealAfterDldfProcess_d($id,null,"zj_pass",$object['auditStatus']);
                    // 全部合格的通知质检报告人员及时审核
                    $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $id));
                }
            }elseif ($object['auditStatus'] == "YSH" && $object['produceNum'] == 0) {
				$this->dealAtConfirm_d($id, $object['mailInfo']);
			}else{//如果质检不合格，通知质检报告人员及时审核
                $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $id));
            }

			//更新附件关联关系
			$this->updateObjWithFile($id,$object ['docCode']);

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改保存
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
	    $ereportequitemProduceNum = 0;// 不合格物料数量
        $ereportequitemQualitedNum = 0;// 合格物料数量
        $hasEmptyQualitedNum = false;// 有合格数为0的物料

		//质检内容取数
		if(isset($object ['items'])){
			$items = $object ['items'];
			unset($object ['items']);
		}

		//质检物料取数
		if(isset($object ['ereportequitem'])){
			$ereportequitem = $object ['ereportequitem'];
			unset($object ['ereportequitem']);
		}

		//不合格质检明细
		if(isset($object['failureitem'])){
			$failureitem = $object['failureitem'];
			unset($object ['failureitem']);
		}

		try {
            //实例化任务对象
            $qualityTaskDao = new model_produce_quality_qualitytask();
            $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyItemDao = new model_produce_quality_qualityapplyitem();

			//源单类型为生产检验的，质检内容允许为空
			if ($object['relDocType'] != 'ZJSQYDSC' && !is_array($items)) {
				throw new Exception ("单据信息不完整，请确认！");
			}

			$this->start_d();

			//根据提交值设置状态 -- 对于提交状态的单据特殊处理
			if ($object['auditStatus'] == "WSH") {
				if ($object['produceNum'] == 0 && $object['relDocType'] != 'ZJSQDLBF') {
					//检验合格的直接通过审核，不需要再处理。
					$object['auditStatus'] = 'YSH';
					$object['ExaStatus'] = '完成';
				} else if ($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 呆料报废质检需要通过审核提交审批流处理,如果此原单还存在未通过物料,则此报告状态为完成
                    $object['auditStatus'] = 'YSH';
                    $object['ExaStatus'] = '待提交';
                }else {
					$object['ExaStatus'] = '待提交';
				}
			}
			if ($object['auditStatus'] == "BC") {
				//如果是保存状态，则将质检任务的物料状态进行变更
			}

			//新增
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//质检内容
			if($items){
				$qualityereportitemDao = new model_produce_quality_qualityereportitem();
				$items = util_arrayUtil::setItemMainId("mainId", $object['id'], $items);
				$qualityereportitemDao->saveDelBatch($items);
			}

			//质检设备内容
			if($ereportequitem){
				$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
				$ereportequitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $ereportequitem);
				$qualityereportequitemDao->saveDelBatch($ereportequitem);
			}

			//不合格设备
			if ($failureitem || $object['isChangeItem'] == 1) {
				$failureitemDao = new model_produce_quality_failureitem();
				//删除旧设备
				if ($object['isChangeItem'] == 1) {
					$failureitemDao->delete(array('mainId' => $object['id']));
				}
				//处理不合格设备
				if ($failureitem) {
					$failureitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $failureitem);
					$failureitemDao->saveDelBatch($failureitem);
				}
			}

			//根据提交值设置状态 -- 对于提交状态的单据特殊处理
			//更新任务清单的检验状态
			if($ereportequitem){
				foreach ($ereportequitem as $val) {
					if ($val['isDelTag'] != "1") {
						//计算出已质检数
						$checkedNumArr = $qualityTaskItemDao->find(array("id" => $val['relItemId']), null, "thisCheckNum");
                        $qualitedNum = $val['qualitedNum'];
						if ($object['auditStatus'] == "BC") {
							//保存状态
							$checkStatus = "";
						} elseif (($checkedNumArr['thisCheckNum']) == 0) {
							//检验状态
							$checkStatus = "YJY";
						} else {
							$checkStatus = "BFJY";
						}

                        $cklTypeUpdateArr = array();
                        $realCheckNum = $checkedNum;
                        if($object['qualityType'] == "ZJFSCJ"){
                            $realCheckNum = $val['samplingNum'];// 抽检的实际检查数量等于抽检数量
                            // $qualitedNum = bcsub($val['thisCheckNum'],$val['produceNum']);
                            $cklTypeUpdateArr['checkType'] = 'ZJFSCJ';
                            $cklTypeUpdateArr['checkTypeName'] = '抽检';
                        }else if($object['qualityType'] == "ZJFSQJ"){
                            $cklTypeUpdateArr['checkType'] = 'ZJFSQJ';
                            $cklTypeUpdateArr['checkTypeName'] = '全检';
                        }

						//更新状态以及合格数量
						$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $qualitedNum,"realCheckNum" => $realCheckNum);
                        if(!empty($cklTypeUpdateArr) && isset($cklTypeUpdateArr['checkType'])){
                            $qualityTaskItem['checkType'] = $cklTypeUpdateArr['checkType'];
                            $qualityTaskItem['checkTypeName'] = $cklTypeUpdateArr['checkTypeName'];
                        }
						$qualityTaskItemDao->updateById($qualityTaskItem);

                        $ereportequitemProduceNum += $val['produceNum'];
                        $ereportequitemQualitedNum += $val['qualitedNum'];
                        if($val['qualitedNum'] <= 0){
                            $hasEmptyQualitedNum = true;
                        }
					}
				}
			}

			//重新计算任务状态
			$qualityTaskDao->renewStatus_d($object['mainId']);

			//如果质检是合格的，开始进入上级流程
            if($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 如果是呆料报废申请的
                if($ereportequitemQualitedNum <= 0){// 不合格直接设置质检不合格审核完成,并反写其他出库单状态（呆料报废,如果没有报废数量,视为质检不合格）
                    $this->dealAfterDldfProcess_d($object['id'],null,"zj_disPass",$object['auditStatus']);
                }else{
                    $this->dealAfterDldfProcess_d($object['id'],null,"zj_pass",$object['auditStatus']);
                    // 全部合格的通知质检报告人员及时审核
                    $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
                }
            }else if ($object['auditStatus'] == "YSH" && $object['produceNum'] == 0) {
                $this->dealAtConfirm_d($object['id'], $object['mailInfo']);
			}else{//如果质检不合格，通知质检报告人员及时审核
                $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
            }

			$this->commit_d();
			return $object['id'];
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 删除
	 * @param $id
	 * @return bool
	 */
	function deletes_d($id) {
		try {
			$this->start_d();

			//先还原任务记录
			$obj = $this->get_d($id);

			//更新任务清单的检验状态
			$qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

			$serialnoDao = new model_produce_quality_serialno();
			foreach ($obj['ereportequitem'] as $val) {
				$arr = $qualityTaskItemDao->find(array('id' => $val['relItemId']));
				$checkedNum = $arr['checkedNum'] - $val['thisCheckNum'];
				$thisCheckNum = $arr['thisCheckNum'] + $val['thisCheckNum'];
				$standardNum = $arr['standardNum'] - $val['qualitedNum'];
				//更新状态
				$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => "", "checkedNum" => $checkedNum, "thisCheckNum" => $thisCheckNum, 'standardNum' => $standardNum);
				$qualityTaskItemDao->updateById($qualityTaskItem);

				//删除不合格序列号
				$del = 'relDocId = "' . $val['relItemId'] . '" and relDocType ="oa_produce_quality_serialno"';
				$serialnoDao->delete($del);
			}

			//删除报告
			parent::deletes_d($id);

			//源单类型为生产检验的，额外删除附件
			if($obj['relDocType'] == 'ZJSQYDSC'){
				$managementDao = new model_file_uploadfile_management();
				$managementDao->delete(array('serviceId' => $id,'serviceType' => 'oa_produce_quality_ereport'));
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 通过id获取详细信息
	 * @param $id
	 * @return bool|mixed
	 */
	function get_d($id) {
		$object = parent::get_d($id);
		$qualityereportitemDao = new model_produce_quality_qualityereportitem();
		$qualityereportitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $qualityereportitemDao->listBySqlId();

		$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
		$qualityereportequitemDao->searchArr ['mainId'] = $id;
		$object ['ereportequitem'] = $qualityereportequitemDao->listBySqlId();
		//add chenrf
		$standardModel = new model_produce_quality_standard();
		$uploadFile = new model_file_uploadfile_management ();
		$files = $uploadFile->getFilesByObjId($object['standardId'], $standardModel->tbl_name);
		if (is_array($files) && !empty($files))
			$object['fileImage'] = '<a href="?model=file_uploadfile_management&action=toDownFileById&fileId=' . $files[0]['id'] . '" taget="_blank" title="点击下载"><img src="images/icon/icon103.gif" /></a>';
		else
			$object['fileImage'] = '';
		return $object;
	}

	/**
	 * 审核完成后处理业务
	 * @param $id
	 * @param null $mailArr
	 * @return bool
	 * @throws Exception
	 */
	function dealAtConfirm_d($id, $mailArr = null) {
		try {
			//获取报告数据
			$obj = $this->get_d($id);

			//更新质检任务
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//更新质检申请
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//更新对应申请源单业务
			$applyDao = new model_produce_quality_qualityapply();
			//缓存申请单数组
			$applyArr = array();

			//循环处理
			foreach ($obj['ereportequitem'] as $val) {
				//查询任务的源单id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//更新质检申请明细处理
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $val['qualitedNum'], $val['thisCheckNum']);

				//源单缓存
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//申请单源单处理
				$relClass = $applyDao->getStrategy_d($taskObj['applyId']);
				$relClassM = new $relClass ();//策略实例
				$applyObj = $applyDao->get_d($taskObj['applyId']);
				$applyDao->dealRelInfoAtConfirm($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum']);
				$applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
			}

			//如果存在申请数组，则统一更新申请单状态
			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//调用邮件发送
				if (!empty($mailArr) && $mailArr['issend'] == "y") {
					$mailStr = "你好，质检员【" . $obj['examineUserName'] . "】已经录入了质检报告【" . $obj['docCode'] . "】，审核结果为 【" . $this->rtStatus($obj['auditStatus'])
						. "】,质检方式为 【" . $obj['qualityTypeName'] . "】,检验设备明细如下：" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>物料编码</td><td>物料名称</td><td>规格型号</td><td>供应商</td><td>报检数量</td><td>单位</td><td>报检时间</td><td>申请源单号</td><td>申请人</td><td>紧急程度</td><td>合格数</td><td>不合格数</td><td>备注</td></tr>";
					foreach ($obj['ereportequitem'] as $key => $val) {
						$mailStr .= <<<EOT
							<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA通知：质检报告', $mailArr['TO_ID'], $mailStr);
				}
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 审核完成后处理业务
	 * @param $object
	 * @return bool
	 */
	function confirm_d($object) {
		//质检物料取数
		$ereportequitem = $object ['ereportequitem'];
		unset($object ['ereportequitem']);

		try {
			$this->start_d();

			$object['auditorName'] = $_SESSION['USERNAME'];
			$object['auditorId'] = $_SESSION['USER_ID'];
			$object['auditDate'] = date('Y-m-d H:i:s');

			//非收料/生产质检直接通过审核 PMS2386:呆料报废质检除外
			if ($object['relDocType'] != 'ZJSQYDSL' && $object['relDocType'] != 'ZJSQYDSC' && $object['relDocType'] != 'ZJSQDLBF') {
				$object['ExaStatus'] = AUDITED;
				$object['ExaDT'] = day_date;
			}

			//更新质检报告
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//质检设备内容
			$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
			$ereportequitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $ereportequitem);
			$qualityereportequitemDao->saveDelBatch($ereportequitem);

			//非收料/生产质检直接通过审核 PMS2386:呆料报废质检除外
			if ($object['relDocType'] != 'ZJSQYDSL' && $object['relDocType'] != 'ZJSQYDSC' && $object['relDocType'] != 'ZJSQDLBF') {
				$this->dealAtBack_d($object['id']);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 审批之后调用方法
	 * @param $spid
	 * @return bool|int
	 * @throws Exception
	 */
	function workflowCallBack($spid) {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo($spid);
		$objId = $folowInfo['objId'];
		$object = $this->get_d($objId);
        if($object['relDocType'] == 'ZJSQDLBF'){// 呆料报废审批完成后处理数据
            $dispassNum = 0;
            $auditResult = $this->_db->getArray("select * from flow_step_partent where Wf_task_ID = {$folowInfo['task']};");
            foreach ($auditResult as $v){
                if($v['Result'] == 'no'){
                    $dispassNum += 1;
                }
            }
            return ($dispassNum > 0)? $this->dealAfterDldfProcess_d($objId, $object, 'sp_disPass') : $this->dealAfterDldfProcess_d($objId, $object, 'sp_pass');
//            return ($dispassNum <= 0 && $object['ExaStatus'] == AUDITED)? $this->dealAfterDldfProcess_d($objId, $object, 'sp_pass') : $this->dealAfterDldfProcess_d($objId, $object, 'sp_disPass');
        }else if ($object['ExaStatus'] == AUDITED) {
			if ($object['auditStatus'] == 'BHG') {
				return $this->dealAtBack_d($objId, $object);
			} else if ($object['auditStatus'] == 'RBJS') {
				return $this->dealAtReceive_d($objId, $object);
			}
		}
		return 1;
	}

    /**
     * 质检报告不走审批,直接触发后续流程
     * @param $objId
     * @return bool
     */
	function dealWithoutAudit($objId){
        $object = $this->get_d($objId);
        $today = date("Y-m-d");
        $this->updateById(array("id"=>$objId,"ExaStatus"=>"完成","ExaDT"=>$today));
        if ($object['auditStatus'] == 'BHG') {
            return $this->dealAtBack_d($objId, $object);
        } else if ($object['auditStatus'] == 'RBJS') {
            return $this->dealAtReceive_d($objId, $object);
        }
    }

    /**
     * 检验质检申请单关联原单的所有物料是否都质检通过了
     * @param $applyId
     * @return bool
     */
	function checkAllPass($applyId){
        // 质检申请明细
        $applyItemDao = new model_produce_quality_qualityapplyitem();
        $chekResultArr = $applyItemDao->_db->getArray("select sum(qualityNum) as qualityNum,sum(standardNum) as standardNum from oa_produce_qualityapply_item where mainId in ({$applyId}) ORDER BY id desc;");
        if($chekResultArr){
            if($chekResultArr[0]['qualityNum'] == $chekResultArr[0]['standardNum']){// 全部关联原单物料质检完成
                return true;
            }else{// 部分关联原单物料质检完成
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 检验质检申请单关联原单是否质检通过了 (有报废数就算通过)
     * @param $applyId
     * @return bool
     */
    function checkDLBFPass($applyId){
        // 质检申请明细
        $applyItemDao = new model_produce_quality_qualityapplyitem();
        $chekResultArr = $applyItemDao->_db->getArray("select sum(i.produceNum) as produceNum,sum(i.qualitedNum) as qualitedNum from oa_produce_quality_ereportequitem i left join oa_produce_quality_ereport e on i.mainId = e.id where e.applyId = {$applyId};");
        if($chekResultArr){
            if($chekResultArr[0]['qualitedNum'] > 0){// 有报废物料质检完成
                return true;
            }else{// 部分关联原单物料质检完成
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 呆料报废审批通过后续处理
     * @param $id
     * @param null $object
     * @param string $auditStatus
     * @return bool
     */
	function dealAfterDldfProcess_d($id, $object = null, $processType = 'zj_pass', $auditStatus = ''){
        if (empty($object)) {
            $object = $this->get_d($id);
        }
        try {
            $this->start_d();

            // 质检任务明细
            $taskItemDao = new model_produce_quality_qualitytaskitem();
            // 质检申请明细
            $applyItemDao = new model_produce_quality_qualityapplyitem();
            // 质检任务源单
            $qualityTaskDao = new model_produce_quality_qualitytask();
            // 质检申请源单
            $applyDao = new model_produce_quality_qualityapply();
            // 其他出库源单
            $stockoutDao = new model_stock_outstock_stockout();
            // 缓存申请单数组
            $applyArr = array();

            $objOldArr = $this->find(array("id"=>$id));
            $qualityapplyArr = $applyDao->find(array("id" => $objOldArr['applyId']));
            if($auditStatus != 'BC') {// 点保存的不做以下处理
                // 质检通过需要做是否全部通过的检验处理
                if ($processType == "zj_pass" || $processType == "sp_pass") {// 质检通过 或 审批通过
                    if ($processType == "zj_pass") {// 检查是否全部质检通过,否则其他出库单状态还是质检中
                        //循环处理
                        foreach ($object['ereportequitem'] as $val) {
                            //查询任务的源单id
                            $taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
                            //更新质检申请明细处理 （qualitedNum: 报废数量,produceNum: 不报废数量）
                            $qualitedNum = ($val['qualitedNum'] > 0)? $val['qualitedNum'] + $val['produceNum'] : $val['qualitedNum'];// 呆料报废,只要有报废数量者为质检通过
                            $applyItemDao->updateDeal_d($taskObj['applyItemId'], $qualitedNum, $val['thisCheckNum']);

                            //源单缓存
                            if (!in_array($taskObj['applyId'], $applyArr)) {
                                array_push($applyArr, $taskObj['applyId']);
                            }
                        }

                        //如果存在申请数组，则统一更新申请单状态
                        if ($applyArr) {
                            foreach ($applyArr as $v) {
                                $applyDao->renewStatus_d($v);
                            }
                        }

                        $chekResult = $this->checkDLBFPass($objOldArr['applyId']);
                        $docStatus = $chekResult ? "SPZ" : "ZJZ";
                        // 更新其他出库单
                        $stockoutUpdateArr = array("id" => $qualityapplyArr['relDocId'], "docStatus" => $docStatus);
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);

                    }else if($processType == "sp_pass"){// 审批通过后,使用策略处理相关业务
                        $qualityapplyDao = new model_produce_quality_qualityapply();
                        $relClass = $qualityapplyDao->getStrategy_d($objOldArr['applyId']);
                        $relClassM = new $relClass(); //策略实例
                        $applyObj = $qualityapplyDao->get_d($objOldArr['applyId']);
                        $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);

                        // 审批通过后发送不报废物料信息邮件
                        if($object['ExaStatus'] == AUDITED){
                            $this->sendMailForUnblockEqus($id);
                        }
                    }

                } else {// 不通过
                    switch ($processType) {
                        case 'zj_disPass'://质检不通过,统一处理原单关联的全部质检申请,质检报告,质检任务,以及原出库单
                            //更新质检申请原单
                            $workDetailMsg = $qualityapplyArr['workDetail'] . " (呆料报废质检完成,含有不合格物料,质检不通过。)";
                            $conditionArr = array("id" => $objOldArr['applyId']);
                            $updateArr = array("id" => $objOldArr['applyId'], "status" => 3, "workDetail" => $workDetailMsg);
                            $updateArr = $applyDao->addUpdateInfo($updateArr);
                            $updateArr['closeUserName'] = $_SESSION['USERNAME'];
                            $updateArr['closeUserId'] = $_SESSION['USER_ID'];
                            $updateArr['closeTime'] = date("Y-m-d H:i:s");
                            $applyDao->update($conditionArr, $updateArr);
                            // 更新质检申请明细
                            $conditionArr = array("mainId" => $objOldArr['applyId']);
                            $updateArr = array("mainId" => $objOldArr['applyId'], "status" => 3, "passReason" => "呆料报废质检不通过,统一打回。");
                            $applyItemDao->update($conditionArr, $updateArr);

                            // 质检任务更新 (PMS2386 呆料报废质检如有一个不合格,则全部任务都为已完成)
                            $condition = "applyId={$objOldArr['applyId']}";// 更新质检任务明细
                            $taskItemDao->update($condition, array('checkStatus' => 'YJY'));
                            $condition = "applyId={$objOldArr['applyId']} AND (acceptStatus <> 'YWC' OR complatedTime IS NULL)";// 更新质检任务
                            $qualityTaskDao->update($condition, array('acceptStatus' => 'YWC', 'complatedTime' => date("Y-m-d H:i:s")));

                            // 质检报告更新 (PMS2386 呆料报废质检如有一个不合格,则全部报告都不合格,且审批状态为待提交)
                            $conditionArr = array("applyId" => $objOldArr['applyId']);
                            $qualityResultArr = array("auditStatus" => "BHG", "ExaStatus" => "待提交", "ExaDT" => '');
                            $this->update($conditionArr, $qualityResultArr);

                            // 质检不通过时发送不报废物料信息邮件
                            $this->sendMailForUnblockEqus($id);

                            break;
                        case 'sp_disPass'://审批不通过
                            //更新质检申请原单
                            $workDetailMsg = $qualityapplyArr['workDetail'] . " (呆料报废质检审批不通过。)";
                            $conditionArr = array("id" => $objOldArr['applyId']);
                            $updateArr = array("id" => $objOldArr['applyId'], "status" => 3, "workDetail" => $workDetailMsg);
                            $updateArr = $applyDao->addUpdateInfo($updateArr);
                            $updateArr['closeUserName'] = $_SESSION['USERNAME'];
                            $updateArr['closeUserId'] = $_SESSION['USER_ID'];
                            $updateArr['closeTime'] = date("Y-m-d H:i:s");
                            $applyDao->update($conditionArr, $updateArr);
                            // 更新质检申请明细
                            $conditionArr = array("mainId" => $objOldArr['applyId']);
                            $updateArr = array("mainId" => $objOldArr['applyId'], "status" => 3, "passReason" => "呆料报废质检审批不通过,统一打回。");
                            $applyItemDao->update($conditionArr, $updateArr);

                            // 质检任务更新 (PMS2386 呆料报废质检审批打回,则全部任务都为已完成)
                            $condition = "applyId={$objOldArr['applyId']}";// 更新质检任务明细
                            $taskItemDao->update($condition, array('checkStatus' => 'YJY'));
                            $condition = "applyId={$objOldArr['applyId']} AND (acceptStatus <> 'YWC' OR complatedTime IS NULL)";// 更新质检任务
                            $qualityTaskDao->update($condition, array('acceptStatus' => 'YWC', 'complatedTime' => date("Y-m-d H:i:s")));

                            // 质检报告更新 (PMS2386 呆料报废质检审批打回,则全部报告都不合格,且审批状态为审批打回)
                            $conditionArr = array("applyId" => $objOldArr['applyId']);
                            $qualityResultArr = array("auditStatus" => "BHG", "ExaStatus" => "审批打回", "ExaDT" => date("Y-m-d"));
                            $this->update($conditionArr, $qualityResultArr);
                            break;
                    }

                    // 相关呆料报废原出库单打回处理
                    $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();
                    $blockeququalityapplyDao->dealRelItemBack($qualityapplyArr['relDocId'],'','',$processType);
                    $blockeququalityapplyDao->dealRelInfoBack($qualityapplyArr['relDocId']);
                }
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 发送一份[呆料其他出库不报废物料信息]邮件，列出物料清单： 其他出库单号 物料编码，物料名称，不报废数量，不报废序列号，发送给刘红辉，录入质检报告操作人
     * @param $id
     */
    function sendMailForUnblockEqus($id){
        $chkSQL = "select ot.docCode,oi.id,oi.productCode,oi.productName,ei.qualitedNum,ei.produceNum,ei.serialnoId as removeSerialnoId,ei.serialnoName as removeSerialnoName,oi.actOutNum,oi.qualityNum,oi.serialnoId,oi.serialnoName from oa_produce_quality_ereportequitem ei left join oa_stock_outstock_item oi on oi.id = ei.mainDocItemId left join oa_stock_outstock ot on ot.id = oi.mainId where ei.mainId = '{$id}';";
        $result = $this->_db->getArray($chkSQL);
        $mainCode = "";$index = 1;
        if($result){
            $tableContent = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center >".
                "<tr bgcolor=#EAEAEA align=center ><th>序号</th><th>物料编码</th><th>物料名称</th><th>不报废数量</th><th>不报废序列号</th></tr>";
            foreach ($result as $k => $v){
                $productCode = $v["productCode"];
                $productName = $v["productName"];
                $produceNum = $v["produceNum"];
                $removeSerialnoNameStr = $v["removeSerialnoName"];
                $mainCode = ($mainCode == "")? $v["docCode"] : $mainCode;
                $produceNum = bcadd($produceNum,0,0);
                $tableContent .= "<tr><td>{$index}</td><td>{$productCode}</td><td>{$productName}</td><td>{$produceNum}</td><td>{$removeSerialnoNameStr}</td></tr>";
                $index += 1;
            }
            $tableContent .= "</table>";
            $ebody = "您好！以下是呆料其他出库单 ".$mainCode." 的不报废物料列表:<br/> ".$tableContent;
            $addresses="";
            $uids = "'honghui.liu','".$_SESSION['USER_ID']."'";
            $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in(".$uids.")";
            $adrsArr = $this->_db->getArray($sql);
            $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";

            if($addresses != "" && $mainCode != ""){
                $title = "呆料其他出库不报废物料信息";
                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','$title','$ebody','$addresses','',NOW(),'','','1')";
                $this->_db->query($sql);
            }
        }
    }

	/**
	 * 退回处理
	 * @param $id
	 * @param null $object
	 * @return bool
	 */
	function dealAtBack_d($id, $object = null) {
		if (empty($object)) {
			$object = $this->get_d($id);
		}
		try {
			$this->start_d();

			//更新质检任务
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//更新质检申请
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//更新对应申请源单业务
			$applyDao = new model_produce_quality_qualityapply();
			//缓存申请单数组
			$applyArr = array();

			//缓存采购员ID
			$purchaserIdArr = array();

			//循环处理
			foreach ($object['ereportequitem'] as $val) {
				//查询任务的源单id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//更新质检申请明细处理
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $val['qualitedNum'], $val['thisCheckNum']);

				//源单缓存
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//申请单源单处理 - 如果做退回处理，参数3为质检数量
                if($object['relDocType'] != 'ZJSQDLBF') {// 呆料报废以审批通过后才算完成
                    $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                    $relClassM = new $relClass ();//策略实例
                    $applyObj = $applyDao->get_d($taskObj['applyId']);
                    $applyDao->dealRelInfoAtBack($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum'], $val['passNum'], $val['receiveNum'], $val['backNum']);
                    $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                }

				//缓存入采购员数组
				if (!in_array($val['purchaserId'], $purchaserIdArr)) {
					array_push($purchaserIdArr, $val['purchaserId']);
				}
			}

			//如果存在申请数组，则统一更新申请单状态
			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//调用邮件发送
				if (!empty($purchaserIdArr)) {
					$mailStr = "你好，质检报告【" . $object['docCode'] . "】已通过审批，审核结果为 【" . $this->rtStatus($object['auditStatus'])
						. "】,质检方式为 【" . $object['qualityTypeName'] . "】,检验人为 【" . $object['examineUserName'] . "】检验设备明细如下：" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>物料编码</td><td>物料名称</td><td>规格型号</td><td>供应商</td><td>报检数量</td><td>单位</td><td>报检时间</td><td>申请源单号</td><td>申请人</td><td>紧急程度</td><td>合格数</td><td>不合格数</td><td>让步接收数</td><td>退回数量</td><td>备注</td></tr>";
					foreach ($object['ereportequitem'] as $val) {
						$mailStr .= <<<EOT
							<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[receiveNum]</td><td>$val[backNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					//获取配置邮件发送人
					$mailArr = $this->getMail_d('purchquality');
					$purchaserIdArr = array_merge($purchaserIdArr, explode(',', $mailArr['sendUserId']));

					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA通知：质检报告', implode(',', $purchaserIdArr), $mailStr);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 让步接收
	 * @param $id
	 * @param null $object
	 * @return bool
	 */
	function dealAtReceive_d($id, $object = null) {
		if (empty($object)) {
			$object = $this->get_d($id);
		}
		try {
			$this->start_d();

			//更新质检任务
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//更新质检申请
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//更新对应申请源单业务
			$applyDao = new model_produce_quality_qualityapply();
			//缓存申请单数组
			$applyArr = array();

			//缓存采购员ID
			$purchaserIdArr = array();

			//循环处理
			foreach ($object['ereportequitem'] as $val) {
				//可用数量 = 接收数量 + 让步接收数量
				$canUseNum = bcadd($val['receiveNum'], $val['passNum']);

				//查询任务的源单id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//更新质检申请明细处理
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $canUseNum, $val['thisCheckNum']);

				//源单缓存
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//申请单源单处理 - 让步接收处理，则合格数量为让步接收数量
                if($object['relDocType'] != 'ZJSQDLBF'){// 呆料报废以审批通过后才算完成
                    $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                    $relClassM = new $relClass ();//策略实例
                    $applyObj = $applyDao->get_d($taskObj['applyId']);
                    $applyDao->dealRelInfoAtReceive($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum'],
                        $val['passNum'], $val['receiveNum'], $val['backNum']
                    );
                    $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                }

				//缓存入采购员数组
				if (!in_array($val['purchaserId'], $purchaserIdArr)) {
					array_push($purchaserIdArr, $val['purchaserId']);
				}
			}

			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//调用邮件发送
				if (!empty($purchaserIdArr)) {
					$mailStr = "你好，质检报告【" . $object['docCode'] . "】已通过审批，审核结果为 【" . $this->rtStatus($object['auditStatus'])
						. "】,质检方式为 【" . $object['qualityTypeName'] . "】,检验人为 【" . $object['examineUserName'] . "】检验设备明细如下：" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>物料编码</td><td>物料名称</td><td>规格型号</td><td>供应商</td><td>报检数量</td><td>单位</td><td>报检时间</td><td>申请源单号</td><td>申请人</td><td>紧急程度</td><td>合格数</td><td>不合格数</td><td>正常接收数</td><td>让步接收数</td><td>退回数量</td><td>备注</td></tr>";
					foreach ($object['ereportequitem'] as $val) {
						$mailStr .= <<<EOT
                            <tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[passNum]</td><td>$val[receiveNum]</td><td>$val[backNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					//获取配置邮件发送人
					$mailArr = $this->getMail_d('purchquality');
					$purchaserIdArr = array_merge($purchaserIdArr, explode(',', $mailArr['sendUserId']));

					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA通知：质检报告', implode(',', $purchaserIdArr), $mailStr);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 撤销校验
	 * @param $object
	 * @return bool
	 */
	function checkCanBack_d($object) {
		//更新对应申请源单业务
		$applyDao = new model_produce_quality_qualityapply();
        $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();

        if($object['relDocType'] == 'ZJSQDLBF'){
            return $blockeququalityapplyDao->checkCanBack_d($object);
        }else{
            //需要检查的业务对象 - 检验的是明细项
            $checkArr = array();
            //循环查询申请源单id
            foreach ($object['ereportequitem'] as $val) {
                if (isset($checkArr[$val['objType']])) {
                    array_push($checkArr[$val['objType']], $val['objItemId']);
                } else {
                    $checkArr[$val['objType']][0] = $val['objItemId'];
                }
            }

            return $applyDao->checkCanBack_d($checkArr);
        }
	}

	/**
	 * 撤销质检报告
	 * @param $id
	 * @return bool|int
	 */
	function backReport_d($id) {
		//获取报告数据
		$obj = $this->get_d($id);
		if ($obj['auditStatus'] != 'YSH' && $obj['auditStatus'] != 'WSH') {
			return false;
		}

		$checkCanBack = $this->checkCanBack_d($obj);
		if ($checkCanBack == false) {
			return -1;
		}

		try {
			$this->start_d();

			if ($obj['auditStatus'] == 'YSH') {
				parent::edit_d(array('id' => $id, 'auditStatus' => 'BC', 'ExaStatus' => WAITAUDIT, 'ExaDT' => ''));

				//更新质检任务
				$taskItemDao = new model_produce_quality_qualitytaskitem();
				//更新质检申请
				$applyItemDao = new model_produce_quality_qualityapplyitem();
				//更新对应申请源单业务
				$applyDao = new model_produce_quality_qualityapply();
				//缓存申请单数组
				$applyArr = array();

				//缓存采购员ID
				$purchaserIdArr = array();

				//循环处理
				foreach ($obj['ereportequitem'] as $val) {
					//查询任务的源单id
					$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId,checkedNum');
					//更新质检申请明细处理
					$applyItemDao->updateUndeal_d($taskObj['applyItemId'], $val['thisCheckNum'], $val['thisCheckNum']);

					//源单缓存
					if (!in_array($taskObj['applyId'], $applyArr)) {
						array_push($applyArr, $taskObj['applyId']);
					}

					//申请单源单处理
                    if($obj['relDocType'] != 'ZJSQDLBF') {// 呆料报废以审批通过后才算完成
                        $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                        $relClassM = new $relClass ();//策略实例
                        $applyObj = $applyDao->get_d($taskObj['applyId']);
                        $applyDao->dealRelInfoAtUnconfirm($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum']);
                        $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                    }

					if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
						$checkStatus = "BFJY";
					} else {
						$checkStatus = "";
					}

					//更新状态以及合格数量
					$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => 0);
					$taskItemDao->updateById($qualityTaskItem);

					//缓存入采购员数组
					if (!in_array($val['purchaserId'], $purchaserIdArr)) {
						array_push($purchaserIdArr, $val['purchaserId']);
					}
				}

				//重新计算任务状态
				$qualityTaskDao = new model_produce_quality_qualitytask();
				$qualityTaskDao->renewStatus_d($obj['mainId']);

                if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:呆料报废撤销报告同时更新其他出库状态为质检中
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityapplyArr = $qualityapplyDao->find(array("id" => $obj['applyId']));
                    if($qualityapplyArr){
                        $stockoutUpdateArr = array("id"=>$qualityapplyArr['relDocId'],"docStatus"=>"ZJZ");
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);
                    }

                    // 检查是否需要处理审批记录
                    $auditTask = $this->_db->getArray("select task from wf_task where code='oa_produce_quality_ereport' and pid = {$id};");
                    if($auditTask){
                        // 清除对应的审批申请记录
                        $taskId = $auditTask[0]['task'];
                        $sql = "delete from wf_task where task = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step_partent where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                    }
                }

				//如果存在申请数组，则统一更新申请单状态
				if ($applyArr) {
					foreach ($applyArr as $v) {
						$applyDao->renewStatus_d($v);
					}

					//调用邮件发送
					if (!empty($purchaserIdArr)) {
						$this->mailDeal_d('qualityapplyBack', implode(',', $purchaserIdArr), array('id' => $id, 'examineUserName' => $obj['examineUserName'], 'docCode' => $obj['docCode']));
					}
				}
			} else {
				//筛除数组
				$ereportequitem = $obj['ereportequitem'];
				unset($obj['ereportequitem']);

				unset($obj['ereportitem']);

				//驳回处理
				$obj['auditStatus'] = 'BC';
				parent::edit_d($obj);

				//实例化任务对象
				$qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

				//根据提交值设置状态 -- 对于提交状态的单据特殊处理
				//更新任务清单的检验状态
				foreach ($ereportequitem as $val) {
					$taskObj = $qualityTaskItemDao->find(array('id' => $val['relItemId']), null, 'checkedNum');
					if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
						$checkStatus = "BFJY";
					} else {
						$checkStatus = "";
					}
					//更新状态以及合格数量
					$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $val['qualitedNum']);
					$qualityTaskItemDao->updateById($qualityTaskItem);
				}

				//重新计算任务状态
				$qualityTaskDao = new model_produce_quality_qualitytask();
				$qualityTaskDao->renewStatus_d($obj['mainId']);

                if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:呆料报废撤销报告时同事更新其他出库状态为质检中
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityapplyArr = $qualityapplyDao->find(array("id" => $obj['applyId']));
                    if($qualityapplyArr){
                        $stockoutUpdateArr = array("id"=>$qualityapplyArr['relDocId'],"docStatus"=>"ZJZ");
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);
                    }

                    // 检查是否需要处理审批记录
                    $auditTask = $this->_db->getArray("select task from wf_task where code='oa_produce_quality_ereport' and pid = {$id};");
                    if($auditTask){
                        // 清除对应的审批申请记录
                        $taskId = $auditTask[0]['task'];
                        $sql = "delete from wf_task where task = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step_partent where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                    }
                }
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 驳回报告
	 * @param $id
	 * @param $reason
	 * @return bool
	 */
	function rejectReport_d($id, $reason) {
		try {
			$this->start_d();

			//获取报告数据
			$obj = $this->get_d($id);

            if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:呆料报废驳回报告相当于审批打回处理
                $this->dealAfterDldfProcess_d($id, $obj, 'sp_disPass');
            }else{
                //筛除数组
                $ereportequitem = $obj['ereportequitem'];
                unset($obj['ereportequitem']);

                unset($obj['ereportitem']);

                //驳回处理
                $obj['auditStatus'] = 'BH';
                parent::edit_d($obj);

                //实例化任务对象
                $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

                //根据提交值设置状态 -- 对于提交状态的单据特殊处理
                //更新任务清单的检验状态
                foreach ($ereportequitem as $val) {
                    $taskObj = $qualityTaskItemDao->find(array('id' => $val['relItemId']), null, 'checkedNum');
                    if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
                        $checkStatus = "BFJY";
                    } else {
                        $checkStatus = "";
                    }
                    //更新状态以及合格数量
                    $qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $val['qualitedNum']);
                    $qualityTaskItemDao->updateById($qualityTaskItem);
                }

                //重新计算任务状态
                $qualityTaskDao = new model_produce_quality_qualitytask();
                $qualityTaskDao->renewStatus_d($obj['mainId']);

                //调用邮件发送
                if ($obj['examineUserName']) {
                    $mailStr = "你好，【" . $_SESSION['USERNAME'] . "】已经驳回了质检报告【" . $obj['docCode'] . "】，驳回原因为 ：<br/>"
                        . $reason . "<br/>"
                        . "检验设备明细如下：<br/>" .
                        "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>物料编码</td><td>物料名称</td><td>规格型号</td><td>供应商</td><td>报检数量</td><td>单位</td><td>报检时间</td><td>申请源单号</td><td>申请人</td><td>紧急程度</td><td>合格数</td><td>不合格数</td><td>备注</td></tr>";
                    foreach ($ereportequitem as $val) {
                        $mailStr .= <<<EOT
						<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[remark]</td></tr>
EOT;
                    }
                    $emailDao = new model_common_mail();
                    $emailDao->mailClear('OA通知：质检报告驳回', $obj['examineUserId'], $mailStr);
                }
            }

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}