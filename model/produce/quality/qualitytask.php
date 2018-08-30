<?php

/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 15:09:09
 * @version 1.0
 * @description:交检任务单 Model层
 */
class model_produce_quality_qualitytask extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_produce_quality_task";
		$this->sql_map = "produce/quality/qualitytaskSql.php";
		parent::__construct();
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/**
	 * 状态数组
	 */
	public $statusArr = array(
		'WJS' => '未接收',
		'YJS' => '未完成',
		'YWC' => '已完成'
	);

	//将外部类型转为内部类型
	public function rtStatus($value) {
		if (isset($this->statusArr[$value])) {
			return $this->statusArr[$value];
		} else {
			return $value;
		}
	}

	//数据字典字段处理
	public $datadictFieldArr = array(
		'relDocType'
	);

	/*--------------------------------------------业务操作--------------------------------------------*/
	/**
	 * @description 下达质检任务,清单显示模板
	 * @param $rows
	 */
	function showItemAtIssued($obj) {
		//		echo "<pre>";
		//		print_r($obj);
		if ($obj ['items']) {
			$i = 0; //清单记录序号
			$str = ""; //返回的模板字符串
			foreach ($obj ['items'] as $key => $val) {
				if ($val['status'] == '2' || $val['status'] === '0' || $val['status'] == '3') {
					continue;
				}
				$notExeNum = $val ['qualityNum'] - $val ['assignNum'];
				if ($notExeNum > 0) {
					$seNum = $i + 1;

					$trClass = $key % 2 == 0 ? "tr_odd" : "tr_even";
					$str .= <<<EOT
						<tr align="center" class="$trClass">
							<td>
								<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
							</td>
							<td>
								$seNum
							</td>
							<td>
								{$val['productCode']}
								<input type="hidden" name="qualitytask[items][$i][productCode]" id="productCode$i" value="{$val['productCode']}" />
								<input type="hidden" name="qualitytask[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
								<input type="hidden" name="qualitytask[items][$i][applyItemId]" id="applyItemId$i" value="{$val['id']}"  />
								<input type="hidden" name="qualitytask[items][$i][supplierId]" id="supplierId$i" value="{$obj['supplierId']}"  />
								<input type="hidden" name="qualitytask[items][$i][supplierName]" id="supplierName$i" value="{$obj['supplierName']}"  />
								<input type="hidden" name="qualitytask[items][$i][supportTime]" id="supportTime$i" value="{$obj['createTime']}"  />
								<input type="hidden" name="qualitytask[items][$i][purchaserName]" id="purchaserName$i" value="{$obj['applyUserName']}"  />
								<input type="hidden" name="qualitytask[items][$i][purchaserId]" id="purchaserId$i" value="{$obj['applyUserCode']}"  />
								<input type="hidden" name="qualitytask[items][$i][applyId]" value="{$obj['id']}"  />
								<input type="hidden" name="qualitytask[items][$i][applyCode]" value="{$obj['docCode']}"  />
								<input type="hidden" name="qualitytask[items][$i][objId]" value="{$obj['relDocId']}"  />
								<input type="hidden" name="qualitytask[items][$i][objCode]" value="{$obj['relDocCode']}"  />
								<input type="hidden" name="qualitytask[items][$i][objType]" value="{$obj['relDocType']}"  />
								<input type="hidden" name="qualitytask[items][$i][objItemId]" value="{$val['relDocItemId']}"  />
							</td>
							<td>
								{$val['productName']}
								<input type="hidden" name="qualitytask[items][$i][productName]" id="productName$i" value="{$val['productName']}" />
							</td>
							<td>
								{$val['pattern']}
								<input type="hidden" name="qualitytask[items][$i][pattern]" id="pattern$i" value="{$val['pattern']}" />
							</td>
							<td>
								{$val['unitName']}
								<input type="hidden" name="qualitytask[items][$i][unitName]"  id="unitName$i" value="{$val['unitName']}" />
							</td>
							<td>
								{$val['checkTypeName']}
								<input type="hidden" name="qualitytask[items][$i][checkTypeName]" value="{$val['checkTypeName']}" />
								<input type="hidden" name="qualitytask[items][$i][checkType]" value="{$val['checkType']}" />
							</td>
							<td>
								{$val['qualityNum']}
							</td>
							<td>
								{$val['assignNum']}
							</td>
							<td>
								<input type="text" name="qualitytask[items][$i][assignNum]" id="assignNum$i" class="readOnlyTxtMin" value="$notExeNum" readonly="readonly"/>
								<input type="hidden" id="notExeNum$i"  value="$notExeNum" />
							</td>
							<td>
								<input type="text" name="qualitytask[items][$i][remark]" id="remark$i" class="txt" value="{$val['remark']}" />
							</td>
						</tr>
EOT;
					$i++;
				}
			}
			return $str;
		}
	}

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object, $isAddInfo = false, $acceptStatus = 'WJS') {
		try {
			if (is_array($object ['items'])) {
				$this->start_d();
				//质检任务基本信息
				$codeDao = new model_common_codeRule ();
				$object['docCode'] = $codeDao->stockCode("oa_produce_quality_task", "ZJRW");
				$object['acceptStatus'] = $acceptStatus;

				//数据字典处理
				$object = $this->processDatadict($object);
				//数据新增
				$id = parent::add_d($object, true);

				//从表数据新增
				$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
				$itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $object ['items']);
				$itemsObj = $qualitytaskitemDao->saveDelBatch($itemsArr);

				//缓存申请id
				$applyIdArr = array();

				$applyItemDao = new model_produce_quality_qualityapplyitem();
				//更新申请单信息
				foreach ($itemsObj as $taskItemObj) {
					if (empty($taskItemObj['isDelTag'])) {
						//更新申请数量以及状态
						$applyItemDao->updateAssignNum_d($taskItemObj['applyItemId'], $taskItemObj['assignNum']);

						//载入申请id
						if (!in_array($taskItemObj['applyId'], $applyIdArr)) {
							array_push($applyIdArr, $taskItemObj['applyId']);
						}
					}
				}

				//质检申请单状态重置
				$qualityapplyDao = new model_produce_quality_qualityapply();
				foreach ($applyIdArr as $applyId) {
					$qualityapplyDao->renewStatus_d($applyId);
				}

				$this->commit_d();
				return $id;
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
				$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
				$applyItemDao = new model_produce_quality_qualityapplyitem();

				//更新申请单信息
				foreach ($object ['items'] as $key => $taskItemObj) {
					$oldTaskItemObj = $qualitytaskitemDao->get_d($taskItemObj['id']);
					$sql = "update oa_produce_qualityapply_item set assignNum= assignNum-" . $oldTaskItemObj['assignNum'] . "+" . $taskItemObj['assignNum'] . " where id='" . $taskItemObj['applyItemId'] . "'";
					$applyItemDao->query($sql);
				}

				$itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $object ['items']);
				$qualitytaskitemDao->saveDelBatch($itemsArr);


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
		$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
		$qualitytaskitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $qualitytaskitemDao->listBySqlId();
		return $object;

	}

	/**
	 * 根据物料检验状态更新自身单据状态
	 */
	function renewStatus_d($id) {
		//查询质检物料
		$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
		$qualitytaskitemArr = $qualitytaskitemDao->findAll(array('mainId' => $id), null, 'id,checkStatus,objType,thisCheckNum');

		$doingArr = array(); //在建的记录
		$waitingArr = array(); //等待处理的记录
		$doneArr = array(); //处理完毕的记录
		$checkNumIsNull = true;
        $isDlbf = true;// 是否为呆料报废申请
		//循环判断状态
		foreach ($qualitytaskitemArr as $key => $val) {
			switch ($val['checkStatus']) {
				case "WJS" :
					array_push($waitingArr, $val['id']);
					break;
				case "BH" :
					array_push($waitingArr, $val['id']);
					break;
				case "YJY" :
					array_push($doneArr, $val['id']);
					break;
				case "YBCBG" :
					array_push($doingArr, $val['id']);
					break;
				case "" :
					array_push($waitingArr, $val['id']);
					break;
			}
			if ($val['thisCheckNum'] != 0) {
				$checkNumIsNull = false;
			}

			if($val['objType'] != 'ZJSQDLBF'){
                $isDlbf = false;
            }
		}

		$status = null;

		//单据未处理
		if (count($waitingArr) == count($qualitytaskitemArr)) {
			$status = "YJS";
		} elseif (count($doneArr) == count($qualitytaskitemArr) && $checkNumIsNull) {//单据已处理
			$status = "YWC";
		} elseif ($isDlbf){// 呆料报废如果有出现部分为检验的情况,上一级处理进行打回,此处质检任务默认已完成,关联PMS2386
            $status = "YWC";
        } else{
			$status = "YJS";
		}

		//更新数组
		$conditionArr = array("id" => $id);
		$updateArr = array("id" => $id, 'acceptStatus' => $status);
		$updateArr = $this->addUpdateInfo($updateArr);

		//如果做完了
		if ($status == "YWC") {
			$updateArr['complatedTime'] = date("Y-m-d H:i:s");
		} else {
			$updateArr['complatedTime'] = "0000-00-00 00:00:00";
		}

		return $this->update($conditionArr, $updateArr);
	}

	/**
	 * 接收质检任务
	 */
	function acceptTask_d($id) {
		$sql = "update " . $this->tbl_name . " set acceptStatus = 'YJS',acceptTime = '" . date('Y-m-d H:i:s') . "' where id in ($id)";
		return $this->_db->query($sql);
	}

    /**
     * 呆料报废ajax下达任务
     * 如果有多单同时提交,按申请单分开逐个处理
     * @param $idArr
     * @return int
     */
	function ajaxTaskForDLBF($idArr){
        $applyItemDao = new model_produce_quality_qualityapplyitem();
        $applyItemArr = $applyItemDao->chkIsAllRelativeSelected($idArr);
        foreach ($applyItemArr as $applyItem){
            $idStr = '';
            if(is_array($applyItem['itemIds']) && !empty($applyItem['itemIds'])){
                foreach ($applyItem['itemIds'] as $itemId){
                    $idStr .= $itemId.',';
                }
                $idStr = substr($idStr,0,strlen($idStr)-1);
                if($this->ajaxTask($idStr)){
                   continue;
                }else{
                    return $applyItem['docCode'];
                }
            }else{
                return $applyItem['docCode'];
            }
        }
        return 1;
    }

    /**
     * ajax下达任务
     * @param $idArr
     * @return bool|int|mixed|null
     */
	function ajaxTask($idArr) {
		$qualityapplyitemModel = new model_produce_quality_qualityapplyitem();
		$qualityapplyitemModel->getParam(array('idArr' => $idArr));
		$objectRow = $qualityapplyitemModel->list_d('select_confirmpass');
		$object = array();
		$applyId = '';
        $applyCode = '';
		foreach ($objectRow as $val) {
            $sql = "select id from oa_produce_quality_taskitem where applyCode = '{$val['applyCode']}' and applyItemId = '{$val['id']}';";
            $chkId = $this->_db->getArray($sql);
            $chkRepetition = ($chkId && $chkId[0]['id'] != '')? true : false;
		    if($chkRepetition){
		        break;
		        return false;
            }else{
                $temp['assignNum'] = $val['qualityNum'];
                $temp['canAssignNum'] = $val['canAssignNum'];
                $temp['remark'] = $val['remark'];
                $temp['productId'] = $val['productId'];
                $temp['productCode'] = $val['productCode'];
                $temp['productName'] = $val['productName'];
                $temp['pattern'] = $val['pattern'];
                $temp['unitName'] = $val['unitName'];
                $temp['checkTypeName'] = $val['checkTypeName'];
                $temp['checkType'] = $val['checkType'];
                $temp['applyItemId'] = $val['applyItemId'];
                $temp['supplierId'] = $val['supplierId'];
                $temp['supplierName'] = $val['supplierName'];
                $temp['supportTime'] = $val['supportTime'];
                $temp['purchaserName'] = $val['purchaserName'];
                $temp['purchaserId'] = $val['purchaserId'];
                $temp['objId'] = $val['objId'];
                $temp['objCode'] = $val['objCode'];
                $temp['objType'] = $val['objType'];
                $temp['objItemId'] = $val['objItemId'];
                $temp['applyId'] = $val['applyId'];
                $temp['applyCode'] = $val['applyCode'];
                $temp['thisCheckNum'] = $val['qualityNum'];
                $items[] = $temp;
                $applyId .= trim($val['applyId']) . ',';
                $applyCode .= trim($val['applyCode']) . ',';
            }
		}
		$applyCode = implode(',', array_unique(explode(',', $applyCode)));
		$applyId = implode(',', array_unique(explode(',', $applyId)));
		//检验人
		$object['chargeUserName'] = $_SESSION['USERNAME'];
		$object['chargeUserCode'] = $_SESSION['USER_ID'];
		$object['applyId'] = rtrim($applyId, ',');
		$object['applyCode'] = rtrim($applyCode, ',');
		$object['relDocType'] = $val['objType'];
		$object['acceptTime'] = date('Y-m-d H:i:s');
		$object['items'] = $items;
		return $this->add_d($object, null, 'YJS');
	}
}