<?php

/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:借试用归还管理 Model层
 */
class model_projectmanagent_borrowreturn_borrowreturn extends model_base
{
	function __construct() {
		$this->tbl_name = "oa_borrow_return";
		$this->sql_map = "projectmanagent/borrowreturn/borrowreturnSql.php";
		parent:: __construct();
	}

    //公司权限处理
    protected $_isSetCompany = 1;

	//数据字典字段处理
	public $datadictFieldArr = array(
		'applyType'
	);

	/**
	 * 处理状态
	 * @param $v
	 * @return string
	 */
	function rtDisposeState_d($v) {
		switch ($v) {
			case '0' :
				return '待处理';
				break; //初始状态
			case '1' :
				return '质检中';
				break; //质检或者部分下达
			case '2' :
				return '已处理';
				break; //全部下达
			case '3' :
				return '质检完成';
				break; //全部下达
			case '8' :
				return '打回';
				break; //打回
			case '9' :
				return '销售确认';
				break; //销售确认
			default :
				return '--';
		}
	}

	/**
	 * 赔偿状态
	 * @param $v
	 * @return string
	 */
	function rtState_d($v) {
		switch ($v) {
			case '0' :
				return '暂无';
				break;
			case '1' :
				return '待生成赔偿单';
				break;
			case '2' :
				return '已生成赔偿单';
				break;
			default :
				return '--';
		}
	}

	/**
	 * 重写add_d方法
	 */
	function add_d($object) {
		try {
			$this->start_d();

			//单号生成
			$codeRuleDao = new model_common_codeRule();
			$object['Code'] = $codeRuleDao->commonCode('借试用归还申请', $this->tbl_name, 'JYGH');

			//插入主表信息
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			//如果为后台发起的归还，则需增加销售确认，后续流程不变
// 			if ($object['salesId'] != $_SESSION['USER_ID']) {
// 				$object['disposeState'] = 9;
// 			}
			$newId = parent:: add_d($object, true);
			//插入从表信息
			if (!empty ($object['product'])) {
				$borrowEquDao = new model_projectmanagent_borrow_borrowequ();
				foreach ($object['product'] as $k => $v) {
					if ($v['productId'] == -1) {
						continue;
					}
					$equId = $v['id'];
					unset ($object['product'][$k]['id']);
					$object['product'][$k]['equId'] = $equId;

					//更新归还申请数量
					$borrowEquDao->updateApplyBackNum($equId, $v['number']);
				}
				$orderequDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
				$orderequDao->createBatch($object['product'], array(
					'returnId' => $newId,
					'borrowId' => $object['borrowId']
				), 'productName');
			}

			//如果为后台发起的归还，发送邮件通知销售进行确认，并抄送给后台操作人员
// 			if ($object['salesId'] != $_SESSION['USER_ID']) {
// 				$this->mailDeal_d('borrowreturnAddByManage', $object['salesId'], array('id' => $newId, $_SESSION['USER_ID']));
// 			}else{//否则直接发邮件通知归还业务人员进行后续操作，接收人在通用邮件配置
// 				$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $newId));
// 			}

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//修改主表信息
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			//如果为后台发起的归还，则需增加销售确认，后续流程不变
// 			if ($object['salesId'] != $object['createId']) {
// 				$object['disposeState'] = 9;
// 			}
			parent:: edit_d($object, true);

			//产品
			$productDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
			$productDao->saveDelBatch($object['product']);

			//重新计算设备数量
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//实例化借试用物料
			$borrowEquArr = $borrowEquDao->getDetail_d($object['borrowId']);
			foreach ($borrowEquArr as $v) {
				if ($v['productId'] == -1) {
					continue;
				}
				//重新统计数量
				$num = $productDao->getNumByEquId_d($v['id']);
				//更新归还申请数量
				$borrowEquDao->updateApplyBackNumEqu($v['id'], $num);
			}

			//如果为后台发起的归还，发送邮件通知销售进行确认，并抄送给后台操作人员
// 			if ($object['salesId'] != $object['createId']) {
// 				$this->mailDeal_d('borrowreturnAddByManage', $object['salesId'], array('id' => $object['id'], $object['createId']));
// 			}else{//否则直接发邮件通知归还业务人员进行后续操作，接收人在通用邮件配置
// 				$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $object['id']));
// 			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * 重写编辑方法
	 */
	function editManage_d($object) {
		try {
			$this->start_d();
			//修改主表信息
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			parent:: edit_d($object, true);

			//产品
			$productDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
			$productDao->saveDelBatch($object['product']);

			//重新计算设备数量
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//实例化借试用物料
			$borrowEquArr = $borrowEquDao->getDetail_d($object['borrowId']);
			foreach ($borrowEquArr as $v) {
				if ($v['productId'] == -1) {
					continue;
				}
				//重新统计数量
				$num = $productDao->getNumByEquId_d($v['id']);
				//更新归还申请数量
				$borrowEquDao->updateApplyBackNumEqu($v['id'], $num);
			}

			//邮件通知
			if ($object['mailInfo']['issend'] == 'y') {
				$this->mailDeal_d('borrowreturnEditByManage', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * 重写删除
	 */
	function deletes($ids) {
		if (!$ids) return true;
		try {
			$this->start_d();

			$idArr = explode(',', $ids);
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//实例化借试用物料
			$borrowreturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();//实例化借试用归还物料
			foreach ($idArr as $v) {
				//查询物料序列号
				$borrowreturnEquArr = $borrowreturnEquDao->getDetail_d($v);
				foreach ($borrowreturnEquArr as $va) {
					if ($v['productId'] == -1) {
						continue;
					}
					//更新归还申请数量
					$borrowEquDao->updateApplyBackNum($va['equId'], -$va['number']);
				}
			}

			//自行删除
			parent::deletes($ids);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 列表页提交
	 */
	function ajaxSub_d($id) {
		return $this->query("update oa_borrow_return set disposeState = '0' where id = '$id'");
	}

	/**
	 * 确认方法
	 */
	function confirmEdit_d($object) {
		try {
			$this->start_d();
			//修改主表信息
			parent:: edit_d($object, true);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据归还单id 判断并更新 处理状态
	 * @param $id
	 * @return string
	 */
	function updateReturnState_d($id) {
		$obj = $this->find(array('id' => $id), null, 'applyType');//获取申请类型
		$sql = "SELECT
                SUM(number) AS number,sum(disposeNumber) AS disposeNumber,
                SUM(qualityNum) AS qualityNum,SUM(qPassNum) AS qPassNum,SUM(qBackNum) AS qBackNum
            FROM
                oa_borrow_return_equ
            WHERE returnId = '$id' AND productId <> -1";
		$numArr = $this->_db->getArray($sql);
		if ($obj['applyType'] == 'JYGHSQLX-01') {//设备归还
			// 如果质检通过数量加质检不合格数量 等于 申请数量，说明质检已完成
			if ($numArr[0]['qPassNum'] + $numArr[0]['qBackNum'] == $numArr[0]['number']) {
				// 申请数量等于下达数量，则算处理完成，否则为质检完成
				if ($numArr[0]['number'] == $numArr[0]['disposeNumber']) {
					$disposeState = 2;
				} else {
					$disposeState = 3;
				}
			} else if ($numArr[0]['qualityNum'] == 0) { // 如果质检申请数量为0，则状态为待处理
				$disposeState = 0;
			} else if ($numArr[0]['qualityNum'] != $numArr[0]['qPassNum'] + $numArr[0]['qBackNum']) {
				$disposeState = 1;
			}
		}else{//设备遗失，无须质检
			// 申请数量等于下达数量，则算处理完成，否则为待处理
			if ($numArr[0]['number'] == $numArr[0]['disposeNumber']) {
				$disposeState = 2;
			} else {
				$disposeState = 0;
			}
		}
		$this->update(array('id' => $id), array('disposeState' => $disposeState));
		return $disposeState;
	}

    /**
     * 检查物料是否为主物料
     * @param $id
     * @throws Exception
     */
    function isMainItem_d($id) {
        try {
            $sql = "select productId from oa_borrow_return_equ where id = '$id'";
            $productId = $this->_db->getArray($sql);
            return $productId[0]['productId'];
        } catch (Exception $e) {
            throw $e;
        }
    }

	/**
	 * 更新处理状态
	 * @param $id
	 * @param $disposeState
	 * @throws Exception
	 */
	function updateDisposeState_d($id, $disposeState) {
		try {
			return $this->_db->query("update oa_borrow_return set disposeState='$disposeState' where id = $id");
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *打回单据
	 */
	function disposeback_d($object) {
		try {
			//更新状态
			$object['disposeState'] = '8';
			parent:: edit_d($object, true);

			//邮件通知
			if ($object['mailInfo']['issend'] == 'y') {
				$this->mailDeal_d('borrowreturnBackByManage', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
			}

			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 更新赔偿状态
	 */
	function updateState_d($id, $state) {
		try {
			$this->_db->query("update oa_borrow_return set state='$state' where id = $id ");
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 根据归还单id 判断并更新 处理状态
	 */
	function updateStateAuto_d($id) {
		$obj = $this->find(array('id' => $id), null, 'applyType');//获取申请类型
		$sql = "select
				sum(number) as number,sum(qPassNum + qBackNum) as qNumber,
				sum(qBackNum) as qBackNum,sum(compensateNum) as compensateNum
			from oa_borrow_return_equ where returnId = '$id'";
		$numArr = $this->_db->getArray($sql);

		//如果是设备归还
		if ($obj['applyType'] == 'JYGHSQLX-01') {
			//不合格数 等于 赔偿数 && 申请归还数 等于 申请质检数
			if ($numArr[0]['qBackNum'] == $numArr[0]['compensateNum'] && $numArr[0]['number'] == $numArr[0]['qNumber']) {
				$state = '2';
			}
		} else {//设备遗失
			//申请归还数 等于 赔偿数
			if ($numArr[0]['number'] == $numArr[0]['compensateNum']) {
				$state = '2';
			}
		}
		//拿到状态才更新
		if (isset($state)) {
			$this->update(array('id' => $id), array('state' => $state));
		}
	}

	/**
	 * 验证是否含有临时序列号
	 */
	function checkHasTempSno_d($object) {
		if (!$object['product']) return false;

		//实例化序列号类
		$serialnoDao = new model_stock_serialno_serialno();
		foreach ($object['product'] as $v) {
			if ($v['serialId']) {
				if ($serialnoDao->checkTemp_d($v['serialId'])) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * 审批之后处理
	 */
	function workflowCallBack($spid) {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo($spid);
		$objId = $folowInfo ['objId'];
		//查询物料序列号
		$borrowreturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
		$borrowreturnEquArr = $borrowreturnEquDao->getDetail_d($objId);

		//实例化序列号类
		$serialnoDao = new model_stock_serialno_serialno();
		try {
			$this->start_d();
			//将临时序列号转正
			foreach ($borrowreturnEquArr as $v) {
				if ($v['serialId']) {
					$serialnoDao->updateTempToFormal_d($v['serialId']);
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
		return true;
	}

	/**
	 * 仓库人员接收确认
	 */
	function ajaxReceive_d($id) {
		$sql = "update " . $this->tbl_name . " set receiveStatus = 1,receiveId = '" .
			$_SESSION['USER_ID'] . "',receiveName = '" . $_SESSION['USERNAME'] . "',receiveTime = '" .
			date('Y-m-d H:i:s') . "' where id = " . $id;
		return $this->_db->query($sql);
	}

	/**
	 * 质检申请打回后对应的业务操作
	 */
	function updateBusinessByBack($id) {
		$proNumSql = "SELECT
		sum(op.qualityNum) AS qualityNum
		FROM
		oa_borrow_return_equ op
		WHERE
		op.returnId = $id";
		$proNum = $this->_db->getArray($proNumSql);
		if ($proNum[0]['qualityNum'] == '0') {
			$disposeState = '0';
		} else {
			$disposeState = '1';
		}
		if (isset($disposeState)) {
			return $this->update(array('id' => $id), array('disposeState' => $disposeState));
		} else {
			return true;
		}
	}

	/**
	 * 销售确认-确认/打回单据
	 * @param $object
	 */
	function saleConfirm_d($object) {
		try {
			//更新状态
			$object['disposeState'] = '0';
			parent:: edit_d($object, true);
			//销售确认后，发送邮件通知归还业务人员进行后续操作
			$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $object['id']));

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}
}