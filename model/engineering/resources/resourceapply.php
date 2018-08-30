<?php

/**
 * @author Show
 * @Date 2012年11月7日 星期三 19:23:17
 * @version 1.0
 * @description:项目设备申请表 Model层
 */
class model_engineering_resources_resourceapply extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_resource_apply";
		$this->sql_map = "engineering/resources/resourceapplySql.php";
		parent:: __construct();
	}

	/**
	 * 数据字典处理
	 */
	public $datadictFieldArr = array(
		'applyType', 'getType'
	);

	/**
	 * 单据状态
	 */
	function rtStatus_d($thisVal) {
		switch ($thisVal) {
			case '0' :
				return '未下达';
				break;
			case '1' :
				return '部分下达';
				break;
			case '2' :
				return '已下达';
				break;
			default :
				return $thisVal;
		}
	}

	/**
	 * 确认状态
	 */
	function rtConfirmStatus_d($thisVal) {
		switch ($thisVal) {
			case '0' :
				return '保存';
				break;
			case '1' :
				return '部门检查';
				break;
			case '2' :
				return '检查完成';
				break;
			case '6' :
				return '打回';
				break;
			case '3' :
				return '等待发货';
				break;
			case '4' :
				return '发货中';
				break;
			case '7' :
				return '撤回待确认';
				break;
			case '5' :
				return '完成';
				break;
			default :
				return $thisVal;
		}
	}

	/*********************  增删改查 ********************/
	/**
	 * 项目页面中新增申请单
	 */
	function addInProject_d($object) {
		try {
			//数据字典
			$object = $this->processDatadict($object);
			//生成申请单号
			$codeRuleDao = new model_common_codeRule();
			$formNo = $codeRuleDao->resourceapplyCode($this->tbl_name);
			$object['formNo'] = $formNo;

			//审批信息状态自动载入
			$object['ExaStatus'] = AUDITED;
			$object['ExaDT'] = day_date;
			$object['status'] = 0;

			$newId = parent::add_d($object, true);

			return array('applyId' => $newId, 'applyNo' => $formNo);
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * add
	 */
	function add_d($object) {
		//截取数据
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		try {
			$this->start_d();
			//数据字典
			$object = $this->processDatadict($object);
			//生成申请单号
			$codeRuleDao = new model_common_codeRule();
			$object['formNo'] = $codeRuleDao->resourceapplyCode($this->tbl_name);
			$object['status'] = 0;
			$object['ExaStatus'] = WAITAUDIT;

			$newId = parent::add_d($object, true);

			//从表信息
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $newId), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			if($object['audit'] == '1'){
				//记录操作日志
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($newId, '提交申请');
				//邮件通知
				$this->sendEmail_d($object, $newId);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * edit
	 */
	function edit_d($object) {
		//截取数据
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		$id = $object['id'];
		try {
			$this->start_d();

			//数据字典
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//从表信息
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $id), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			if($object['audit'] == '1'){//提交时执行
				//记录操作日志
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '提交申请');
				//邮件通知
				$this->sendEmail_d($object, $id);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 确认申请
	 */
	function editConfirm_d($object) {
		//截取数据
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		$id = $object['id'];
		$obj = $this->get_d($id); // 获取单据信息
		if ($obj['confirmStatus'] == 2) return true; // 后台校验已处理

		try {
			$this->start_d();

			if ($object['audit'] == '1') {//提交时执行
				$object['confirmId'] = $_SESSION['USER_ID'];
				$object['confirmName'] = $_SESSION['USERNAME'];
				$object['confirmTime'] = date('Y-m-d H:i:s');
				//更新已勾选申请明细的确认状态为1
				foreach ($resourceapplydet as $key => $val){
					if(!isset($val['isDelTag'])){
						if(!isset($val['isChecked'])){
							unset($resourceapplydet[$key]);
						}else{
							$resourceapplydet[$key]['status'] = 1;
						}
					}
				}
			}

			//从表信息
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $id), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			//主表信息
			$unConfirmNum = $detailDao->findCount(array('mainId' => $id,'status' => 0));//获取未确认的明细记录数
			if($unConfirmNum == 0){
				$object['confirmStatus'] = 2;//检查完成
			}else{
				$object['confirmStatus'] = 1;//部门检查
			}
			$object = $this->processDatadict($object);//数据字典
			parent::edit_d($object, true);

			if ($object['audit'] == '1') {//提交时执行
				//记录操作日志
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '设备确认');
				if ($object['confirmStatus'] == '2'){
					$this->mailDeal_d('resourceApplyConfirm', $object['applyUserId'],
						array(
							'id'           => $id,
							'confirmUser'  => $_SESSION['USERNAME'],
							'formNo'       => $obj['formNo'],
							'changeReason' => $object['changeReason']
						)
					);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 确认物料
	 */
	function confirmDetail_d($object) {
		//截取数据
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		try {
			$this->start_d();

			//从表信息
			$detailDao = new model_engineering_resources_resourceapplydet();
			$detailDao->saveDelBatch($resourceapplydet);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 打回
	 */
	function applyBack_d($id) {
		try {
			$this->start_d();
	
			//明细处理，将已确认的设备状态改为待确认
			$detailDao = new model_engineering_resources_resourceapplydet();
			$detailDao->update(array('mainId' => $id,'status'=>1), array('status' => 0));
			//单据状态改为打回
			$this->update(array('id' => $id), array('confirmStatus' => 6));
			//记录操作日志
			$logDao = new model_engineering_baseinfo_resourceapplylog();
			$logDao->addLog_d($id, '打回');
	
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}
	
	/**
	 * 更新确认状态
	 */
	function confirmStatus_d($id, $confirmStatus) {
		$object = array(
			'id' => $id,
			'confirmStatus' => $confirmStatus
		);
		if ($confirmStatus == '2') {
			$object['confirmId'] = $_SESSION['USER_ID'];
			$object['confirmName'] = $_SESSION['USERNAME'];
			$object['confirmTime'] = date('Y-m-d H:i:s');
		}
		return parent::edit_d($object, true);
	}

	/**
	 * 自动更新下达状态
	 */
	function updateSatusAuto_d($id, $applyDetailDao = null) {
		if (empty($applyDetailDao)) {
			$applyDetailDao = new model_engineering_resources_resourceapplydet();
		}
		//获取设备信息
		$datas = $applyDetailDao->findAll(array('mainId' => $id), null);
		$dealNum = 0;//已执行数量
		$applyNum = 0;//申请数量
		foreach ($datas as $v) {
			$dealNum += $v['exeNumber'];
			$applyNum += $v['number'];
		}
		if ($dealNum == 0) {
			$status = 0;//未处理
		} elseif ($dealNum != $applyNum) {
			$status = 1;//处理中
		} else {
			$status = 2;//已处理
		}
		return parent::edit_d(array('id' => $id, 'status' => $status), true);
	}

	/**
	 * 更新单据状态
	 */
	function updateConfirmStatus_d($id) {
		//获取申请数量
		$sql = "SELECT SUM(number-backNumber) AS number FROM oa_esm_resource_applydetail WHERE mainId = ".$id;
		$rs = $this->findSql($sql);
		$applyNum = $rs[0]['number'];//申请数量
		//获取撤回数量
		$sql = "SELECT SUM(backNumber) as backNumber FROM oa_esm_resource_applydetail WHERE status = '2' AND mainId = ".$id;
		$rs = $this->findSql($sql);
		$backNum = $rs[0]['backNumber'];//撤回数量
		//获取已出库明细数量
		$sql = "SELECT SUM(exeNumber) AS exeNumber FROM oa_esm_resource_taskdetail WHERE
					taskId IN ( SELECT id FROM oa_esm_resource_task WHERE applyId = ".$id.")";
		$rs = $this->findSql($sql);
		$dealNum = $rs[0]['exeNumber'];//已出库数量
		if($backNum != 0){
			$confirmStatus = 7;//撤回待确认
		} elseif ($dealNum == 0) {
			$confirmStatus = 3;//等待发货
		} elseif ($dealNum != $applyNum) {
			$confirmStatus = 4;//发货中
		} else {
			$confirmStatus = 5;//完成
		}
		return parent::edit_d(array('id' => $id, 'confirmStatus' => $confirmStatus), true);
	}
	/********************** 新报告确认部分 - 审批完成后业务处理 **********************/
	/**
	 * 审批完成后业务处理
	 */
	function dealAfterAudit_d($spid) {
		//获取工作流信息
		$otherdatas = new model_common_otherdatas ();
		$flowInfo = $otherdatas->getStepInfo($spid);
		$id = $flowInfo['objId'];

		//记录操作日志
		$logDao = new model_engineering_baseinfo_resourceapplylog();
		$logDao->addLog_d($id, '审批');

		return true;
	}

	//发送邮件
	function  sendEmail_d($object, $newId) {
		//邮件信息获取
		if (isset($object['email'])) {
			$emailArr = $object['email'];
			unset($object['email']);
		}
		$obj = $this->find(array('id' => $newId));

		//发送邮件 ,当操作为提交时才发送
		if (isset($emailArr)) {
			if (!empty($emailArr['TO_ID']) && $emailArr['issend'] == 'y') {
				$this->mailDeal_d('resourceapply', $emailArr['TO_ID'], array('id' => $newId, 'applyUser' => $obj['applyUser'],
					'formNo' => $obj['formNo']
				));
			}
		}
	}

	/**
	 * 发送邮件给默认接收人
	 */
	function  sendDefaultEmail_d($newId) {
		$obj = $this->find(array('id' => $newId));
		$mailDao = new model_system_mailconfig_mailconfig();
		$mailArr = $mailDao->find(array('objCode' => 'resourceapply'));
		if ($mailArr['defaultUserId']) {
			$this->mailDeal_d('resourceapply', $mailArr['defaultUserId'], array('id' => $newId, 'applyUser' => $obj['applyUser'],
				'formNo' => $obj['formNo']));
		}
	}

	/**
	 * 确认发货任务分配数量
	 */
	function confirmTaskNum_d($object) {
		try {
			$this->start_d();

			$id = $object['id'];
			//明细处理
			$detail = $object['detail'];
			if (is_array($detail)) {
				//统一实例化
				$taskDao = new model_engineering_resources_task();//发货任务
				$taskdetailDao = new model_engineering_resources_taskdetail();//发货任务明细
				$taskIdArr = array();//初始化任务id数组
				foreach ($detail as $val) {
					//处理发货任务明细
					$awaitNumber = $val['awaitNumber'];
					if ($awaitNumber == 0) {//如果待确认分配数量为0，则删除该任务明细
						$taskdetailDao->deletes($val['id']);
					} else {//否则更新任务明细分配数量及待确认分配数量
						$taskdetailDao->update(array('id' => $val['id']), array('number' => $val['awaitNumber'], 'awaitNumber' => 'NULL'));
					}
					//处理发货任务
					$taskDao->update(array('id' => $val['taskId']), array('status' => 0));//更新发货任务单据状态为未处理
					//更新申请明细已下达数量
					$num = $val['number'] - $awaitNumber;//实际上减少的下达数量
					$sql = "UPDATE oa_esm_resource_applydetail SET exeNumber = exeNumber - {$num} WHERE id = '{$val['applyDetailId']}';";
					$this->_db->query($sql);
					//构建任务id数组
					array_push($taskIdArr, $val['taskId']);
				}
				//更新申请单下达状态
				$this->updateSatusAuto_d($id);
				//转换成任务id字符串
				$taskIdStr = implode(",", $taskIdArr);
				//记录操作日志
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '发货修改确认');
				//邮件通知
				if ($object['mailInfo']['issend'] == 'y') {
					$this->mailDeal_d('esmResourcesTaskConfirmNum', $object['mailInfo']['TO_ID'], array('id' => $taskIdStr));
				}
			} else {
				throw new Exception ("单据信息不完整!");
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 个人名下在借的设备数量
	 */
	function getBorrowDeviceNum($userId) {
		$sql = "
			SELECT
				IFNULL(SUM(i.amount - i.return_num),0) as borrowDeviceNum
			FROM
				device_borrow_order_info i
			LEFT JOIN device_borrow_order o ON o.id = i.orderid
			WHERE
				i.amount > i.return_num
			AND o.userid = '".$userId."'";
		return $this->findSql($sql);
	}
	
	/**
	 * 确认撤回设备
	 */
	function confirmBack_d($object) {
		try {
			$this->start_d();
	
			$id = $object['id'];
			//明细处理
			$detail = $object['detail'];
			unset($object['detail']);
			parent::edit_d(array('id' => $id, 'backReason' => $object['backReason']), true);//增加撤回原因
			if (is_array($detail)) {
				$resourceapplydetDao = new model_engineering_resources_resourceapplydet();//申请明细
				foreach ($detail as $v) {
					if(isset($v['isDelTag'])){
						$exeNumber = $v['exeNumber'] - $v['backNumber'];
						$resourceapplydetDao->update(array('id'=>$v['id']), array('exeNumber'=>$exeNumber,'backNumber'=> 0,'status'=>1));
					}else{
						$resourceapplydetDao->update(array('id'=>$v['id']), array('status'=>3));
					}
				}
				//更新申请单下达状态
				$this->updateSatusAuto_d($id);
				//更新单据状态
				$this->updateConfirmStatus_d($id);
				//记录操作日志
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id,'撤回确认');
			} else {
				throw new Exception ("单据信息不完整!");
			}
	
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}