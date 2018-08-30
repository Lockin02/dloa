<?php

/**
 * @author Show
 * @Date 2011年5月21日 星期六 14:47:06
 * @version 1.0
 * esm 工程类项目费用报销
 * trp 试用类项目费用报销
 */
class model_finance_expense_expense extends model_base
{

	function __construct() {
		$this->tbl_name = "cost_summary_list";
		$this->sql_map = "finance/expense/expenseSql.php";
		parent::__construct();
	}

	// 公司权限处理 TODO
	protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	// 配置报销类型
	private $detailTypeArr = array(
		'1' => '部门费用',
		'2' => '合同项目费用',
		'3' => '研发费用',
		'4' => '售前费用',
		'5' => '售后费用'
	);

	// 返回费用类型
	function rtDetailType($thisVal) {
		if (isset($this->detailTypeArr[$thisVal])) {
			return $this->detailTypeArr[$thisVal];
		} else {
			return $thisVal;
		}
	}

	// 数据字典配置
	public $datadictFieldArr = array('module');

	// 添加一个后天验证
	function checkform_d($object) {
        // -- 费用总金额与分摊总金额验证 -- //
        $expensedetail = $expensecostshare = array();
        $expenseCost = $costshareCost = 0;
        $newExpensecostshareObj = array();
        foreach ($object['expensedetail'] as $k => $v){
            if(!isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                foreach ($v['expenseinv'] as $ev){
                    if($ev['isDelTag'] != '1'){
                        $expensedetail[] = $ev;
                        $expenseCost = bcadd($expenseCost, $ev['Amount'], 2);
//                    echo $ev['Amount']."<br>";
                    }
                }
            }
        }
        foreach ($object['expensecostshare'] as $k => $v){
            if(!isset($v['ID']) && !isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                $newExpensecostshareObj[$k] = $v;
            }else if(isset($v['ID'])){
                $newExpensecostshareObj[$k] = $v;
            }
            if(!isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                $expenseinv = array();
                foreach ($v['expenseinv'] as $ev){
                    if(!isset($ev['ID']) && !isset($ev['isDelTag']) || $ev['isDelTag'] != '1'){
                        $expenseinv[] = $ev;
                    }else if(isset($ev['ID'])){
                        $expenseinv[] = $ev;
                    }
                    if($ev['isDelTag'] != '1'){
                        $expensecostshare[] = $ev;
                        $costshareCost = bcadd($costshareCost, $ev['CostMoney'], 2);
//                        echo $ev['CostMoney']."<br>";
                    }
                }
                $newExpensecostshareObj[$k]['expenseinv'] = $expenseinv;
            }
        }

        if($expenseCost != $costshareCost){
//             echo "expenseCost: ".$expenseCost." costshareCost: ".$costshareCost;exit();
            return '保存失败，费用信息总金额不等于分摊信息总金额！';
        }else{
            $object['expensecostshare'] = $newExpensecostshareObj;
        }
        // -- 费用总金额与分摊总金额验证 -- //

		//费用类型
		if (trim($object['DetailType']) == "") {
			return '保存失败，单据没有选择费用类型！';
		}

		//费用期间表单验证
		if ($object['CostDateBegin'] == "" || $object['CostDateEnd'] == "" || $object['days'] == "") {
			return '保存失败，费用期间信息不完整';
		}

		//事由
		if (trim($object['Purpose']) == "") {
			return '保存失败，没有填写报销事由';
		}

		//报销人员
		if (trim($object['CostManName']) == "") {
			return '保存失败，没有选择报销人员';
		}

		if (trim($object['CostManCom']) == '' && trim($object['CostBelongCom']) == "") {
			return '保存失败，没有填写费用归属公司';
		} else if (trim($object['CostBelongCom']) == "") {
            $object['CostBelongCom'] = $object['CostManCom'];
            $object['CostBelongComId'] = $object['CostManComId'];
        }

		if (trim($object['CostBelongDeptName']) == "") {
			return '保存失败，没有填写费用归属部门';
		}

		//进入对应验证
		switch ($object['DetailType']) {
			case '1' :
				//设置需要清空的内容
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if ($object['projectName'] == '') {
					$object['projectId'] = '';
					$object['ProjectNo'] = '';
					$object['projectName'] = '';
					$object['projectType'] = '';
					$object['proManagerId'] = '';
					$object['proManagerName'] = '';
				}
				$object['city'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
				}
				break;
			case '2' :
				//工程项目
				if (!$object['projectId']) {
					return '保存失败，没有正确选择项目，请重新填写';
				}
				if (trim($object['projectName']) == "") {
					return '保存失败，没有选择该笔费用所在工程项目';
				}
				if (trim($object['CostBelongDeptName']) == "") {
					return '保存失败，没有填写费用归属部门';
				}
				//设置需要清空的内容
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
					$object['city'] = '';
				}
				break;
			case '3' :
				// 项目id
				if (!$object['projectId']) {
					return '保存失败，没有正确选择项目，请重新填写';
				}
				//研发项目
				if (trim($object['projectName']) == "") {
					return '保存失败，没有选择该笔费用所在研发项目';
				}
				if (trim($object['CostBelongDeptName']) == "") {
					return '保存失败，没有填写费用归属部门';
				}
				//设置需要清空的内容
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
					$object['city'] = '';
				}
				break;
			case '4' :
				//省份
				if (trim($object['province']) == "") {
					return '保存失败，没有选择客户所在省份';
				}
				//城市
				if (is_array($object['city'])) {
					$object['city'] = implode(',', $object['city']);
				}
				if (trim($object['city']) == "") {
					return '保存失败，没有选择客户所在城市';
				}
				//请选择客户类型
				if (trim($object['CustomerType']) == "") {
					return '保存失败，没有选择客户类型';
				}
				//请选择客户类型
				if (trim($object['CostBelonger']) == "") {
					return '保存失败，没有录入销售负责人，销售负责人可由商机、客户名称自动带出，或者通过客户省份、城市、类型由系统自动匹配';
				}
				//归属部门
				if ($object['CostBelongDeptId'] == "" || $object['CostBelongDeptName'] == "") {
					return '保存失败，没有选择费用归属部门';
				}
				//设置需要清空的内容
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				break;
			case '5' :
				if ($object['contractCode'] == "") {
					return '保存失败，没有选择该笔费用归属合同';
				}
				//请选择客户类型
				if (trim($object['CostBelonger']) == "") {
					return '保存失败，没有录入销售负责人，销售负责人为费用归属人';
				}
				//归属部门
				if ($object['CostBelongDeptId'] == "" || $object['CostBelongDeptName'] == "") {
					return '保存失败，没有选择费用归属部门';
				}
				//设置需要清空的内容
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['projectId'] = '';
				$object['ProjectNo'] = '';
				$object['projectName'] = '';
				$object['projectType'] = '';
				$object['proManagerId'] = '';
				$object['proManagerName'] = '';
				break;
		}
		return $object;
	}

	/********************* 内部增删查改 *******************/
	/**
	 * 重写add_d
	 * @param $object
	 * @return array|bool|string
	 */
	function add_d($object,$deptId = '') {
		$object = $this->checkform_d($object);
		if (!is_array($object)) {//数据错误时返回错误信息
			return $object;
		}

		//本次处理状态
		$thisAuditType = $object['thisAuditType'];
		unset($object['thisAuditType']);

		//获取报销明细信息
		$expensedetail = $object['expensedetail'];
		unset($object['expensedetail']);

		//获取分摊明细信息
		$expensecostshare = $object['expensecostshare'];
		unset($object['expensecostshare']);

		//状态输入
		$object['Status'] = $thisAuditType == 'check' ? '部门检查' : '编辑';
		$object['ExaStatus'] = '编辑';

		//判断是否项目报销
		$object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
		$object['CostClientType'] = $object['DetailType'] == 4 || $object['DetailType'] == 5 ? $object['CustomerType'] : $object['Purpose'];
		$object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];

		//不明参数设置
		$object['xm_sid'] = 1;
		$object['isNew'] = 1;
		$object['InputDate'] = $object['UpdateDT'] = date('Y-m-d H:i:s');
		$object['CheckAmount'] = $object['Amount'];
		$object['Area'] = $_SESSION['AREA'];
		//如果是提交检查,更新提交检查时间
		if ($thisAuditType == 'check') {
			$object['subCheckDT'] = $object['InputDate'];
		}

		//判断是否延迟报销
		$object['isLate'] = abs((strtotime(date('Y-m-d', strtotime($object['InputDate']))) -
                strtotime($object['CostDateEnd'])) / 86400) > ISLATE ? 1 : 0;

		//费用明细类实例化
		$expensedetailDao = new model_finance_expense_expensedetail();
		//分摊明细类实例化
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		//费用cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		//费用cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();

        // 如果所属版块为空则补充相应的版块内容 created by huanghaojin 16-10-25 2153 (原来的模块是在前端所属部门字段内容变更后根据部门ID动态更新的)
        $deptDao = new model_deptuser_dept_dept();
        $deptId = $object['CostBelongDeptId'];
        $deptRow = $deptDao->find(array('DEPT_ID' => $deptId));
        $object['module'] = $deptRow['module'];


		//开始进入数据处理
		try {
			$this->start_d();

			//自动生成系统编号和表单状态
			$codeRuleDao = new model_common_codeRule();
            $deptId = ($deptId == '')? $_SESSION['DEPT_ID'] : $deptId;
			$object['BillNo'] = $codeRuleDao->expenseCode('expense', $deptId);

			//报销申请表处理
			$object = $this->processDatadict($object);
			$newId = parent::add_d($object);

			//插入报销明细数据
			$headObj = $object;
			$headObj['ProjectNO'] = $object['ProjectNo'];
			$headId = $expenselistDao->add_d($headObj);

			//插入申请关联表
			$assObj = $object;
			$assObj['HeadID'] = $headId;
			$assObj['RNo'] = 1;
			$assId = $expenseassDao->add_d($assObj);

			//报销明细费用处理
			//关联数据复制
			$addArr = array(
				'BillNo' => $object['BillNo'],
				'RNo' => 1,
				'HeadID' => $headId,
				'AssID' => $assId
			);
			$expensedetail = util_arrayUtil::setArrayFn($addArr, $expensedetail);
			$expensecostshare = util_arrayUtil::setArrayFn($addArr, $expensecostshare);
			//插入费用明细
			$expensedetailDao->saveDelBatch($expensedetail);
			//插入分摊明细
			$expensecostshareDao->saveDelBatch($expensecostshare);

			//发票单
			$exbillDao = new model_finance_expense_exbill();
			$exbillDao->addSummary_d($object['BillNo'], $codeRuleDao);

			//如果是下推单据，则进行项目信息更新
			if ($object['isPush'] == 1) {
				if ($object['esmCostdetailId']) {
					$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
					$esmcostdetailDao->updateCost_d($object['esmCostdetailId'], '3');

					$esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
					$esmcostdetailInvDao->updateCostInvoice_d($object['esmCostdetailId'], '3');

					//计算人员的项目费用
					if ($object['projectId']) {
						//获取当前项目的费用
						$projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['CostMan']);

						//更新人员费用信息
						$esmmemberDao = new model_engineering_member_esmmember();
						$esmmemberDao->update(
							array('projectId' => $object['projectId'], 'memberId' => $object['CostMan']),
							$projectCountArr
						);
					}
				}
			}

			//更新附件关联关系
			$this->updateObjWithFile($newId, $object['BillNo']);

			//获取特别申请包含的特别申请号
			$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);
			if ($specialApplyArr) {//如果存在特别申请号,则
				//获取申请单使用情况
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//更新特别申请单
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			$object['id'] = $newId;
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}

		//邮件通知部分
		$this->expenseMail_d($object, $thisAuditType);

		return $object;
	}

	/**
	 * 费用邮件调整
	 * @param $object
	 * @param string $thisAuditType
	 */
	function expenseMail_d($object, $thisAuditType = 'check') {
		$content = null;
		$title = null;
		$tomail = null;

		//单据提交检查
		if ($thisAuditType == 'check') {
			//内容
			$content = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "已将报销单提交部门检查,请登陆OA进行确认！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单号：" . $object['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//标题
			$title = "OA-报销单提交检查通知:" . $object['BillNo'];
			//收件人
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
		} else if ($thisAuditType == 'needConfirm') {
			//内容
			$content = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "调整了报销单的金额,请登陆OA进行确认！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单号：" . $object['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//标题
			$title = "OA-报销单等待确认通知:" . $object['BillNo'];
			//收件人
			$tomail = $object['InputMan'];
		}

		$mailDao = new model_common_mail();
		$mailDao->mailClear($title, $tomail, $content);
	}

	/**
	 * 重写edit
	 * 如果是编辑状态，返回true,如果是提交审批，返回$summaryId
	 * @param $object
	 * @return array|bool|string
	 */
	function edit_d($object) {
		$object = $this->checkform_d($object);
		if (!is_array($object)) {
			return $object;
		}

		//由于有部门检查功能 - 金额优先处理
		$object['CheckAmount'] = $object['Amount'];

		//本次处理状态
		$thisAuditType = $object['thisAuditType'];
		unset($object['thisAuditType']);
		if ($thisAuditType == 'check') {
			$object['Status'] = '部门检查';
		} else if ($thisAuditType == 'needConfirm') {
			$object['Status'] = '等待确认';
			unset($object['Amount']);
		}

		//获取报销明细信息
		$expensedetail = $object['expensedetail'];
		unset($object['expensedetail']);

		//获取分摊明细信息
		$expensecostshare = $object['expensecostshare'];
		unset($object['expensecostshare']);

		//判断是否项目报销
		$object['CostClientType'] = $object['DetailType'] == 4 || $object['DetailType'] == 5 ? $object['CustomerType'] : $object['Purpose'];
		$object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];

		//不明参数设置
		$object['UpdateDT'] = date('Y-m-d H:i:s');
		$object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
		//如果是提交检查,更新提交检查时间
		if ($thisAuditType == 'check') {
			$object['subCheckDT'] = $object['InputDate'];
		}

		//判断是否延迟报销
		$object['isLate'] = abs((strtotime(date('Y-m-d', strtotime($object['InputDate']))) - strtotime($object['CostDateEnd'])) / 86400) > ISLATE ? 1 : 0;

		//费用明细类实例化
		$expensedetailDao = new model_finance_expense_expensedetail();
		//分摊明细类实例化
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		//费用cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		//费用cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();

        // 如果所属版块为空则补充相应的版块内容 created by huanghaojin 16-10-25 2153 (原来的模块是在前端所属部门字段内容变更后根据部门ID动态更新的)
        $deptDao = new model_deptuser_dept_dept();
        $deptId = $object['CostBelongDeptId'];
        $deptRow = $deptDao->find(array('DEPT_ID' => $deptId));
        $object['module'] = $deptRow['module'];

		//开始进入数据处理
		try {
			$this->start_d();

			//获取特别申请包含的特别申请号
			$oldSpecialApplyArr = empty($object['specialApplyNos']) ? array() : explode(',', $object['specialApplyNos']);
			unset($object['specialApplyNos']);

			//报销申请表处理
			$object = $this->processDatadict($object);
			parent::edit_d($object);

			//插入报销明细数据
			$headObj = $object;
			$headObj['ProjectNO'] = $object['ProjectNo'];
			$expenselistDao->edit_d($headObj);

			//插入申请关联表
			$assObj = $object;
			$assObj['id'] = $object['AssID'];
			$expenseassDao->edit_d($assObj);

			//报销明细费用处理
			$addArr = array(
				'RNo' => 1,
				'HeadID' => $object['HeadID'],
				'AssID' => $object['AssID'],
				'BillNo' => $object['BillNo']
			);
			$expensedetail = util_arrayUtil::setArrayFn($addArr, $expensedetail);
			$expensecostshare = util_arrayUtil::setArrayFn($addArr, $expensecostshare);
			//插入费用明细
			$expensedetailDao->saveDelBatch($expensedetail);
			//插入分摊明细
			$expensecostshareDao->saveDelBatch($expensecostshare);

			//获取特别申请包含的特别申请号
			$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);
			$specialApplyArr = array_unique(array_merge($specialApplyArr, $oldSpecialApplyArr));
			if ($specialApplyArr) {
				//获取申请单使用情况
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//更新特别申请单
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}

		//邮件通知部分
		$this->expenseMail_d($object, $thisAuditType);

		return $object;
	}

	/**
	 * 获取业务信息
	 * @param $id
	 * @return bool|mixed
	 */
	function getInfo_d($id) {
		//获取本单位信息
		$obj = parent::get_d($id);

		//费用明细类实例化
		$expensedetailDao = new model_finance_expense_expensedetail();
		$obj['expensedetail'] = $expensedetailDao->findAll(array('BillNo' => $obj['BillNo']), 'MainTypeId');

		//费用cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		$expenselistArr = $expenselistDao->find(array('BillNo' => $obj['BillNo']), null, 'HeadID');
		$obj['HeadId'] = $expenselistArr['HeadID'];
		$obj['specialApplyNos'] = implode(',', $expensedetailDao->getSpecialApplyNos_d($obj['BillNo']));

		//费用cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();
		$expenseassArr = $expenseassDao->find(array('BillNo' => $obj['BillNo']), null, 'ID');
		$obj['AssID'] = $expenseassArr['ID'];

		//分摊明细类实例化
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		$obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);

		return $obj;
	}

	/**
	 * 更具单据编号获取费用信息
	 * @param $BillNo
	 * @return bool|mixed
	 */
	function getByBillNo_d($BillNo) {
		$condition = array('BillNo' => $BillNo);
		return $this->find($condition);
	}

	/**
	 * 批量删除对象
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function deletes_d($id) {
		$object = $this->get_d($id);
		//如果是不合法删除，则返回失败
		if (empty($object) || $object['ExaStatus'] == AUDITING || $object['ExaStatus'] == AUDITED) {
			throw new Exception('Illegally deleted documents!');
		}

		//实力话费用明细
		$expensedetailDao = new model_finance_expense_expensedetail();
		$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);

		try {
			$this->start_d();

			//删除表数据
			$this->deletes($id);

			//删除费用明细
			$expensedetailDao->delete(array('BillNo' => $object['BillNo']));
			if (!empty($specialApplyArr)) {
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//更新特别申请单
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			//删除发票明细
			$expenseinvDao = new model_finance_expense_expenseinv();
			$expenseinvDao->delete(array('BillNo' => $object['BillNo']));

			//删除汇总单
			$exbillDao = new model_finance_expense_exbill();
			$exbillDao->delete(array('ConBillNo' => $object['BillNo']));

			//关系表
			$expenselistDao = new model_finance_expense_expenselist();
			$expenselistDao->delete(array('BillNo' => $object['BillNo']));

			//关系表
			$expenseassDao = new model_finance_expense_expenseass();
			$expenseassDao->delete(array('BillNo' => $object['BillNo']));

			//清除业务数据
			//如果是下推单据，则进行项目信息更新
			if ($object['isPush'] == 1) {
				if ($object['esmCostdetailId']) {
					$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
					$esmcostdetailDao->updateCost_d($object['esmCostdetailId'], '1');

					$esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
					$esmcostdetailInvDao->updateCostInvoice_d($object['esmCostdetailId'], '1');

					//计算人员的项目费用
					if ($object['projectId']) {
						//获取当前项目的费用
						$projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['CostMan']);

						//更新人员费用信息
						$esmmemberDao = new model_engineering_member_esmmember();
						$esmmemberDao->update(
							array('projectId' => $object['projectId'], 'memberId' => $object['CostMan']),
							$projectCountArr
						);
					}
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
	 * 删除汇总表时清空表单信息
	 * @param $BillNo
	 * @return bool
	 * @throws Exception
	 */
	function clearBillNoInfo_d($BillNo) {
		try {
			// 更新
			$this->update(array('BillNo' => $BillNo), array('BillNo' => '', 'Status' => '编辑'));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 部门检查 - 提交单据
	 * @param $id
	 * @return bool
	 */
	function ajaxHand_d($id) {
		$object = array(
			'id' => $id,
			'Status' => '部门检查',
			'subCheckDT' => date('Y-m-d H:i:s')
		);

		parent::edit_d($object);

		$obj = $this->find(array('id' => $id));
		//邮件通知部分
		$this->expenseMail_d($obj);

		return true;
	}

	/**
	 * 部门检查 - 打回单据
	 * @param $id
	 * @return bool
	 */
	function ajaxBack_d($id) {
		$object = array(
			'id' => $id,
			'Status' => '编辑',
			'ExaStatus' => '编辑'
		);
		return parent::edit_d($object);
	}

	/**
	 * 部门检查列表 - 部门收单
	 * @param $id
	 * @return bool
	 */
	function ajaxDeptRec_d($id) {
		$object = array(
			'id' => $id,
			'isNotReced' => '0',
			'RecInvoiceDT' => date('Y-m-d H:i:s')
		);
		return parent::edit_d($object);
	}

	/**
	 * 部门检查列表 - 提交财务
	 * @param $id
	 * @return bool
	 */
	function ajaxHandFinance_d($id) {
		$object = array(
			'id' => $id,
			'isHandUp' => '1',
			'HandUpDT' => date('Y-m-d H:i:s')
		);
		return parent::edit_d($object);
	}

	/**
	 * 获取报销说明附件
	 */
	function getFile_d() {
		$managentDao = new model_file_uploadfile_management();
		$fileRs = $managentDao->find(array('serviceId' => 1, 'serviceType' => 'expenseselect'), null, 'id');
		if ($fileRs) {
			return "?model=file_uploadfile_management&action=toDownFileById&fileId=" . $fileRs['id'];
		} else {
			return '#';
		}
	}

	/**
	 * 收单调用方法
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function receiveForm($id) {
		try {
			//更新
			return $this->update(array('id' => $id), array('IsFinRec' => '1', 'FinRecDT' => date('Y-m-d H:i:s')));
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 退单调用方法
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function backForm($id) {
		try {
			//更新
			return $this->update(array('id' => $id), array('IsFinRec' => '0'));
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 提交单据确认
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function handConfirm_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');
		$rs = true;
		try {
			//更新
			$this->update(array('id' => $id), array('Status' => '等待确认'));
		} catch (Exception $e) {
			throw $e;
		}

		//如果更新成功，发送邮件
		if ($rs) {
			//内容
			$content = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "调整了报销单的金额,请登陆OA进行确认！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单号：" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//标题
			$title = "OA-报销单等待确认通知:" . $obj['BillNo'];
			//收件人
			$tomail = $obj['InputMan'];

			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * 确认单据
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function confirmCheck_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');

		$rs = true;
		try {
			//更新
			$this->update(array('id' => $id), array('Status' => '部门检查', 'Amount' => $obj['CheckAmount']));
		} catch (Exception $e) {
			throw $e;
		}

		//如果更新成功，发送邮件
		if ($rs) {
			//内容
			$content = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单已被" . $_SESSION["USERNAME"] . "确认,请登陆OA进行确认！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单号：" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//标题
			$title = "OA-报销单确认通知:" . $obj['BillNo'];

			//收件人
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * 否认单据
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function unconfirmCheck_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');

		$rs = true;
		try {
			//更新
			$this->update(array('id' => $id), array('Status' => '部门检查'));
		} catch (Exception $e) {
			throw $e;
		}

		//如果更新成功，发送邮件
		if ($rs) {
			//内容
			$content = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单已被" . $_SESSION["USERNAME"] . "<font color='red'>否认</font>,请登陆OA进行确认！<br />&nbsp;&nbsp;&nbsp;&nbsp;报销单号：" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//标题
			$title = "OA-报销单确认通知:" . $obj['BillNo'];

			//收件人
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * 真忙啊，我还要获取省份
	 * @param $province
	 * @return string
	 */
	function getProvince_d($province) {
		$provinceDao = new model_system_procity_province();
		$provinceRows = $provinceDao->findAll();

		if ($province) {
			$str = '<option value=""></option>';
		} else {
			$str = '<option value="" select="selected"></option>';
		}
		foreach ($provinceRows as $key => $val) {
			if ($province == $val['provinceName']) {
				$str .= '<option value="' . $val['provinceName'] . '" selected="selected">' . $val['provinceName'] . '</option>';
			} else {
				$str .= '<option value="' . $val['provinceName'] . '">' . $val['provinceName'] . '</option>';
			}
		}
		return $str;
	}

	/**
	 * 真忙啊，我还要获取城市
	 * @param $city
	 * @return string
	 */
	function getCity_d($city) {
		if ($city) {
			$cityDao = new model_system_procity_city();
			$cityRows = $cityDao->findAll();

			$str = '<option value=""></option>';
			foreach ($cityRows as $key => $val) {
				if ($city == $val['cityName']) {
					$str .= '<option value="' . $val['cityName'] . '" selected="selected">' . $val['cityName'] . '</option>';
				} else {
					$str .= '<option value="' . $val['cityName'] . '">' . $val['cityName'] . '</option>';
				}
			}
			return $str;
		} else {
			return '<option value="" select="selected"></option><option value="请先选择省份">请先选择省份</option>';
		}
	}

	/**
	 * 要获取部门信息了。。。
	 * @param null $userId
	 * @param null $branchDao
	 * @return int
	 */
	function needExpenseCheck_d($userId = null, $branchDao = null) {
		//人员判断
		$userId = $userId ? $userId : $_SESSION['USER_ID'];

		$userDao = new model_deptuser_user_user();
		$userInfo = $userDao->getUserById($userId);

		//判断
		if (empty($branchDao)) {
			$branchDao = new model_deptuser_branch_branch();
		}
		$branchInfo = $branchDao->find(array('NamePT' => $_SESSION['Company']), null, 'needExpenseCheck');

		if ($userInfo['needExpenseCheck'] == 1 && $branchInfo['needExpenseCheck']) {
			return $userInfo['needExpenseCheck'];
		} else {
			return 0;
		}
	}

	/**
	 * 重新计算报销单内容
	 * @param $id
	 * @param null $expensedetailDao
	 * @param null $expenseinvDao
	 * @return bool
	 * @throws Exception
	 */
	function recountExpense_d($id, $expensedetailDao = null, $expenseinvDao = null) {
		try {
			$this->start_d();

			//查询自身内容
			$obj = $this->find(array('ID' => $id), null, 'BillNo');

			//判断是否需求实例化费用明细
			if (!$expensedetailDao) {
				$expensedetailDao = new model_finance_expense_expensedetail();
			}
			$expensedetailArr = $expensedetailDao->findAll(array('BillNo' => $obj['BillNo']), null, 'id,esmCostdetailId,CostMoney,CostTypeID,days');

			//发票部分处理
			if (!$expenseinvDao) {
				$expenseinvDao = new model_finance_expense_expenseinv();
			}

			//查询当前存在的补贴类型
			$costTypeDao = new model_finance_expense_costtype();
			$subsidyArr = $costTypeDao->getIsSubsidy_d();

			//任务费用明细id
			$esmIdsArr = array();
			//单据金额
			$formMoney = $invoiceMoney = $invoiceNumber = $feeRegular = $feeSubsidy = 0;

			//循环重构esmCostdetailId
			foreach ($expensedetailArr as $key => $val) {
				if ($val['esmCostdetailId']) {
					//费用id
					$esmIdsArr = array_merge($esmIdsArr, explode(',', $val['esmCostdetailId']));
				}
				//金额
				$formMoney = bcadd($formMoney, bcmul($val['CostMoney'], $val['days'], 2), 2);
				//常规费用以及补贴费用
				if (in_array($val['CostTypeID'], $subsidyArr)) {
					$feeSubsidy = bcadd($feeSubsidy, bcmul($val['CostMoney'], $val['days'], 2), 2);
				} else {
					$feeRegular = bcadd($feeRegular, bcmul($val['CostMoney'], $val['days'], 2), 2);
				}

				//删除已经不存在的发票类型
				if ($val['esmCostdetailId']) {
					$expenseinvDao->clearInvoice_d($obj['BillNo'], $val['esmCostdetailId'], $val['id']);
				}
			}

			$expenseinvArr = $expenseinvDao->findAll(array('BillNo' => $obj['BillNo']), null, 'Amount,days,invoiceNumber,isSubsidy');
			foreach ($expenseinvArr as $key => $val) {
				if ($val['isSubsidy'] == "0") {
					//发票金额
					$invoiceMoney = bcadd($invoiceMoney, $val['Amount'], 2);
					//发票数量
					$invoiceNumber = bcadd($invoiceNumber, $val['invoiceNumber'], 2);
				}
			}

			//更新单据内容
			$this->update(array('ID' => $id), array(
				'esmCostdetailId' => implode(',', $esmIdsArr),
				'Amount' => $formMoney,
				'CheckAmount' => $formMoney,
				'feeSubsidy' => $feeSubsidy,
				'feeRegular' => $feeRegular,
				'invoiceMoney' => $invoiceMoney,
				'invoiceNumber' => $invoiceNumber
			));

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/******************* 工程项目报销 *******************/
	/**
	 * 工程数据获取
	 * @param $projectId
	 * @return bool|mixed
	 */
	function getEsmInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($projectId);
		$esmprojectObj['projectId'] = $esmprojectObj['id'];
		unset($esmprojectObj['id']);

		//加入试用项目的内容获取
		if ($esmprojectObj['contractType'] == 'GCXMYD-04') {
			//试用项目
			$trialprojectDao = new model_projectmanagent_trialproject_trialproject();
			$trialprojectObj = $trialprojectDao->find(array('id' => $esmprojectObj['contractId']));

			//如果试用项目含有商机编号
			if ($trialprojectObj['chanceCode']) {
				$chanceDao = new model_projectmanagent_chance_chance();
				$chanceObj = $chanceDao->find(array('id' => $trialprojectObj['chanceId']));

				$esmprojectObj['salePerson'] = $chanceObj['prinvipalName'];
				$esmprojectObj['salePersonId'] = $chanceObj['prinvipalId'];
				$esmprojectObj['salePersonDept'] = $chanceObj['prinvipalDept'];
				$esmprojectObj['salePersonDeptId'] = $chanceObj['prinvipalDeptId'];
				$esmprojectObj['chanceId'] = $chanceObj['id'];
				$esmprojectObj['chanceCode'] = $chanceObj['chanceCode'];
				$esmprojectObj['chanceName'] = $chanceObj['chanceName'];
			} else {
				$esmprojectObj['salePerson'] = $trialprojectObj['applyName'];
				$esmprojectObj['salePersonId'] = $trialprojectObj['applyNameId'];

				$userDao = new model_deptuser_user_user();
				$userObj = $userDao->getUserById($esmprojectObj['salePersonId']);
				$esmprojectObj['salePersonDept'] = $userObj['DEPT_NAME'];
				$esmprojectObj['salePersonDeptId'] = $userObj['DEPT_ID'];

				$esmprojectObj['chanceId'] = '';
				$esmprojectObj['chanceCode'] = '';
				$esmprojectObj['chanceName'] = '';
			}

			//			echo "<pre>";
			//			print_r($chanceObj);
		}

		return $esmprojectObj;
	}

	/**
	 * 获取对应报销单号的项目费用信息id
	 * @param $BillNo
	 * @return string
	 */
	function getEsmCostDetail_d($BillNo) {
		$rs = $this->findAll(array('BillNo' => $BillNo), null, 'esmCostdetailId');
		if ($rs) {
			//缓存id数组
			$esmcostdetailArr = array();
			foreach ($rs as $key => $val) {
				array_push($esmcostdetailArr, $val['esmCostdetailId']);
			}
			return implode($esmcostdetailArr, ',');
		} else {
			return '';
		}
	}

	/**
	 * 工程模板渲染TODO
	 * @param $expenseCostTypeArr
	 * @param $relDocType
	 * @return array
	 */
	function initEsmAdd_d($expenseCostTypeArr, $relDocType) {
		//返回数组
		$rtArr = array();

		if ($expenseCostTypeArr) {
			//获取对应发票金额
			$esmcostInvoiceDao = new model_engineering_cost_esminvoicedetail();

			//获取发票类型
			$sql = "select id,name from bill_type where TypeFlag=1 and closeflag=0";
			$billTypeArr = $this->_db->getArray($sql);

			//查询模板小类型
			$sql = "select CostTypeID as id, CostTypeName as name, showDays, isReplace, isEqu, invoiceType,
                invoiceTypeName, isSubsidy, budgetType, ParentCostType, ParentCostTypeID from cost_type where isNew = 1";
			$costTypeArr = $this->_db->getArray($sql);

			//模板实例化字符串
			$str = null;
			//单据总金额 总数量
			$countMoney = $invoiceMoney = $invoiceNumber = $feeRegular = $feeSubsidy = 0;

			//项目费用记录id
			$costIdsArr = array();

			foreach ($expenseCostTypeArr as $k => $v) {
				//查询本日志内的该项费用金额
				$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';

				// 费用类型转义 -- 适用于其他源单的类型转报销
				//                $v = $this->fitCostType_d($costTypeArr, $v);

				foreach ($costTypeArr as $key => $val) {
					if ($v['costTypeId'] == $val['budgetType']) {
						$v['costTypeId'] = $val['id'];
						$v['costType'] = $val['name'];
						$v['parentCostType'] = $val['ParentCostType'];
						$v['parentCostTypeId'] = $val['ParentCostTypeID'];
						$v['times'] = 1;
						$v['invoiceType'] = $val['invoiceType'];
						$v['isReplace'] = $val['isReplace'];
						break;
						//                        return $v;
					}
				}
				//                return $v;

				//设置费用类型Id
				$countI = $v['costTypeId'];

				if ($v['costIds'])
					array_push($costIdsArr, $v['costIds']);

				//获取匹配费用类型
				$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['costTypeId']);

				//判断是否补贴费用，并计算对应费用信息
				if ($thisCostType['isSubsidy']) {
					$feeSubsidy = bcadd($feeSubsidy, bcmul($v['costMoney'], $v['times'], 2), 2);
					$countMoney = bcadd($countMoney, bcmul($v['costMoney'], $v['times'], 2), 2);
				} else {
					$feeRegular = bcadd($feeRegular, $v['costMoney'], 2);
					$countMoney = bcadd($countMoney, $v['costMoney'], 2);
				}

				$str .= <<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[parentCostTypeId]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][esmCostdetailId]" value="$v[costIds]"/>

	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                    </td>
		                <td valign="top" class="form_text_right">
EOT;
				//如果需要显示天数，则显示
				if ($thisCostType['showDays']) {
					$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI"
                                   value="$v[costMoney]" class="readOnlyTxtShort formatMoney" style="width:60px"
                                   readonly="readonly"/>
							X
							天数
							<input type="text" name="expense[expensedetail][$countI][days]" class="readOnlyTxtMin"
                                   id="days$countI" value="$v[times]" readonly="readonly"/>
						</span>
EOT;
				} else {
					$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="1"/>
EOT;
				}
				$str .= <<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;

				if ($relDocType) {
					$billTypeStr = $this->initBillType_d($billTypeArr, null, $v['invoiceType'], $v['isReplace']);//模板实例化字符串
					$thisI = $countI . "_" . 0;
					$invoiceNumber = bcadd($invoiceNumber, 1);
					$invoiceMoney = bcadd($invoiceMoney, $v['costMoney'], 2);
					$str .= <<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI"
                                       name="expense[expensedetail][$countI][expenseinv][0][Amount]"
                                       costTypeId="$v[CostTypeID]" rowCount="$thisI"
                                       onblur="invMoneySet('$thisI');countInvoiceMoney();"
                                       class="txtshort formatMoney" value="$v[costMoney]"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort" value="1"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="0"/>
                            </td>
                            <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
                            </td>
                        </tr>
EOT;
				} else {
					//发票部分循环处理
					$esmInvoiceArr = $esmcostInvoiceDao->getInvoice_d($v['costIds']);
					foreach ($esmInvoiceArr as $thisK => $thisV) {
						$billTypeStr = $this->initBillView_d($billTypeArr, $thisV['invoiceTypeId']);
						$invoiceNumber = bcadd($invoiceNumber, $thisV['invoiceNumber']);
						$invoiceMoney = bcadd($invoiceMoney, $thisV['invoiceMoney'], 2);

						//发票部分
						$str .= <<<EOT
	                    <tr>
	                        <td width="40%">
	                            <input type="text" value="$billTypeStr" style="width:90px" class="readOnlyTxtShort" readonly="readonly"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" value="$thisV[invoiceTypeId]"/>
	                        </td>
	                        <td width="30%">
	                            <input type="text" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                        </td>
	                        <td width="30%">
	                            <input type="text" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
	                        </td>
						</tr>
EOT;
					}
				}

				//设置备注栏高度
				$remarkHeight = isset($esmInvoiceArr) ? (count($esmInvoiceArr) - 1) * 33 + 20 . "px" : "20px";

				$str .= <<<EOT
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="引入特别申请" onclick="showSpecialApply($countI)" readonly="readonly">
	                    </td>
		                <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" style="height:$remarkHeight" id="remark$countI" class="txt">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
			}
			$rtArr['expensedetail'] = $str;
			$rtArr['countMoney'] = $countMoney;
			$rtArr['feeRegular'] = $feeRegular;
			$rtArr['feeSubsidy'] = $feeSubsidy;
			$rtArr['invoiceMoney'] = $invoiceMoney;
			$rtArr['invoiceNumber'] = $invoiceNumber;
			$rtArr['esmCostdetailId'] = implode($costIdsArr, ',');
		}

		return $rtArr;
	}

	/**
	 * 工程模板渲染TODO
	 * @param $expenseArr
	 * @return mixed
	 */
	function initEsmEdit_d($expenseArr) {

		//获取发票类型
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		//查询模板小类型
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type";
		$costTypeArr = $this->_db->getArray($sql);

		//实例化费用发票明细
		$expenseinvDao = new model_finance_expense_expenseinv();

		//模板实例化字符串
		$str = null;
		//单据总金额
		$countMoney = 0;
		foreach ($expenseArr['expensedetail'] as $k => $v) {
			$specialApplyNo = $v['specialApplyNo'];
			//设置费用类型Id
			$countI = $v['CostTypeID'];
			//查询本日志内的该项费用金额
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$countMoney = bcadd($countMoney, bcmul($v['CostMoney'], $v['days'], 2), 2);

			//获取匹配费用类型
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $thisCostType[name]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$thisCostType[name]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][ID]" value="$v[ID]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//如果需要显示天数，则显示
			if ($thisCostType['showDays']) {
				$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" class="readOnlyTxtShort formatMoney" style="width:60px" readonly="readonly"/>
							X
							天数
							<input type="text" name="expense[expensedetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
						</span>
EOT;
			} else {
				$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}
			$str .= <<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;

			//获取发票信息
			$expenseinvArr = $expenseinvDao->findAll(array('BillDetailID' => $v['ID'], 'BillNo' => $v['BillNo']));
			foreach ($expenseinvArr as $thisK => $thisV) {
				// 是否不需要发票
				if ($thisCostType['isSubsidy'] == 1 && $thisK == 0) {
					$billArr = $this->getBillArr_d($billTypeArr, $thisV['BillTypeID']);
					$thisI = $countI . "_" . $thisK;
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" class="txtshort formatMoney" style="color:gray" onblur="invMoneySet('$thisI');countAll();" title="补贴类发票金额不计入到单据发票金额中,只用于打单显示"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
	                        </td>
	                    </tr>
EOT;
				} else {
					$billTypeStr = $this->initBillType_d($billTypeArr, $thisV['BillTypeID'], $thisCostType['invoiceType'], $thisCostType['isReplace']);
					$thisI = $countI . "_" . $thisK;
					//图片显示判定
					$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
					//方法判定
					$funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
					$invTitle = $thisK == 0 ? "添加行" : "删除本行发票";
					//发票部分
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" onblur="invMoneySet('$thisI');countInvoiceMoney();countAll();" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][isSubsidy]" value="0"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
			}

			//设置备注栏高度
			$remarkHeight = (count($expenseinvArr) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
                    <td valign="top">
	                	<input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" value="$specialApplyNo" title="引入特别申请" onclick="showSpecialApply($countI)" readonly="readonly">
	                </td>
	                <td valign="top">
                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" style="height:$remarkHeight" class="txt">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/********************* 模板部分处理 *******************/
	/**
	 * 获取最新模版
	 */
	function getModelType_d() {
		$customtemplateDao = new model_finance_expense_customtemplate();
		return $customtemplateDao->getModelType_d();
	}

	/**
	 * 模板处理 - 主要是费用项目转换
	 * @param $object
	 * @return mixed
	 */
	function rowDeal_d($object) {
		//实例化
		$otherDateDao = new model_common_otherdatas();
		//费用项转换
		foreach ($object as $key => $val) {
			$object[$key]['fields'] = $otherDateDao->initCostType($val['fields']);
		}
		return $object;
	}

	/**
	 * 费用信息模板处理 - 新增渲染模板
	 * @param $modelType
	 * @return string
	 */
	function initTempAdd_d($modelType) {
		$str = "";

		//获取模板信息
		$sql = "select id,templateName,contentId from cost_customtemplate where id = $modelType";
		$rs = $this->_db->getArray($sql);
		$modelArr = $rs[0];
		//add chenrf 增加关闭状态条件
		//查询模板小类型
		$sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(" . $modelArr['contentId'] . ") and c.isNew = '1' and isClose = 0 order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
		$costTypeArr = $this->_db->getArray($sql);

		//获取发票类型
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		foreach ($costTypeArr as $k => $v) {
			$countI = $v['CostTypeID'];
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[ParentCostTypeID]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$v[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//如果需要显示天数，则显示
			if ($v['showDays']) {
				$str .= <<<EOT
                    <span>
                        <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
                        X
                        天数
                        <input type="text" name="expense[expensedetail][$countI][days]" class="txtmin" id="days$countI" value="1" onblur="daysCheck(this);detailSet($countI);countAll();setCostshareMoney($countI);" onchange="setCostshareMoney($countI);"/>
                    </span>
EOT;
			} else {
				$str .= <<<EOT
                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
                    <input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="1"/>
EOT;
			}

			// 是否不需要发票
			if ($v['isSubsidy'] == 1) {
				$billArr = $this->getBillArr_d($billTypeArr, $v['invoiceType']);
				$str .= <<<EOT
                    </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                    <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][0][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="txtshort formatMoney" onblur="invMoneySet('$thisI');countAll();" style="color:gray" title="补贴类发票金额不计入到单据发票金额中,只用于打单显示"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
	                                    <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
                            <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="引入特别申请" onclick="showSpecialApply($countI)" readonly="readonly"/>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" class="txt"></textarea>
	                    </td>
	                </tr>
EOT;
			} else {
				$billTypeStr = $this->initBillType_d($billTypeArr, null, $v['invoiceType'], $v['isReplace']);//模板实例化字符串
				$str .= <<<EOT
                    </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][0][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="invMoneySet('$thisI');countInvoiceMoney();" class="txtshort formatMoney"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
	                                    <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="0"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
                            <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="引入特别申请" onclick="showSpecialApply($countI)" readonly="readonly"/>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" class="txt"></textarea>
	                    </td>
	                </tr>
EOT;
			}
		}

		return $str;
	}

	/**
	 * 分摊信息模板处理 - 新增渲染模板
	 * @param $modelType
	 * @return string
	 */
	function initCostshareTempAdd_d($modelType) {
		$str = "";

		//获取模板信息
		$sql = "select id,templateName,contentId from cost_customtemplate where id = $modelType";
		$rs = $this->_db->getArray($sql);
		$modelArr = $rs[0];
		//add chenrf 增加关闭状态条件
		//查询模板小类型
		$sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(" . $modelArr['contentId'] . ") and c.isNew = '1' and isClose = 0 order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
		$costTypeArr = $this->_db->getArray($sql);

		//获取所属板块
		$rs = $this->getDatadicts ("HTBK");
		$moduleArr = $rs['HTBK'];
		$moduleStr = $this->initModule_d($moduleArr);//模板实例化所属板块字符串

		foreach ($costTypeArr as $k => $v) {
			$countI = $v['CostTypeID'];
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			$str .= <<<EOT
                <tr class="$trClass" id="trCostshare$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除分摊" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="expense[expensecostshare][$countI][MainType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainTypeId]" value="$v[ParentCostTypeID]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="expense[expensecostshare][$countI][CostType]" id="costTypeCostshare$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][CostTypeID]" id="costTypeIdCostshare$countI" value="$v[CostTypeID]"/>
                    </td>
                    <td valign="top" colspan="3" class="innerTd">
                        <table class="form_in_table" id="tableCostshare_$countI">
                            <tr id="trCostshare_$thisI">
                                <td width="49.5%">
                              		<input type="text" name="expense[expensecostshare][$countI][expenseinv][0][CostMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" id="costMoneyCostshare_$thisI" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/>
                                </td>
                                <td width="32.5%">
                                    <select id="selectCostshare_$thisI" name="expense[expensecostshare][$countI][expenseinv][0][module]" style="width:90px;display:none;"><option value="">请选择板块</option>$moduleStr</select>
                                    <input id="inputCostshare_$thisI" type="text" value=""  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;" readonly/>
                                </td>
                                <td width="18%">
                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="addModule($countI)"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="expense[expensecostshare][$countI][Remark]" id="remarkCostshare$countI" class="txtlong"></textarea>
                    </td>
                </tr>
EOT;
		}

		return $str;
	}

	/**
	 * 模版处理 - 编辑渲染模版
	 * @param $expenseArr
	 * @return mixed
	 */
	function initTempEdit_d($expenseArr) {

		//获取发票类型
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		//查询模板小类型
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where isNew = '1'";
		$costTypeArr = $this->_db->getArray($sql);

		//实例化费用发票明细
		$expenseinvDao = new model_finance_expense_expenseinv();

		//模板实例化字符串
		$str = null;
		//单据总金额
		$countMoney = 0;
		foreach ($expenseArr['expensedetail'] as $k => $v) {
			$specialApplyNo = $v['specialApplyNo'];
			//设置费用类型Id
			$countI = $v['CostTypeID'];
			//查询本日志内的该项费用金额
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$countMoney = bcadd($countMoney, bcmul($v['CostMoney'], $v['days'], 2), 2);
			$thisI = $countI . "_0";

			//获取匹配费用类型
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $thisCostType[name]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$thisCostType[name]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][ID]" value="$v[ID]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//如果需要显示天数，则显示
			if ($thisCostType['showDays']) {
				$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
							X
							天数
							<input type="text" name="expense[expensedetail][$countI][days]" class="txtmin" id="days$countI" value="$v[days]" onblur="daysCheck(this);detailSet($countI);countAll();setCostshareMoney($countI);" onchange="setCostshareMoney($countI);"/>
						</span>
EOT;
			} else {
				$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}
			$str .= <<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
			//获取发票信息
			$expenseinvArr = $expenseinvDao->findAll(array('BillDetailID' => $v['ID'], 'BillNo' => $v['BillNo']));
			foreach ($expenseinvArr as $thisK => $thisV) {
				// 是否不需要发票
				if ($thisCostType['isSubsidy'] == 1 && $thisK == 0) {
					$billArr = $this->getBillArr_d($billTypeArr, $thisV['BillTypeID']);
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" class="txtshort formatMoney" style="color:gray" onblur="invMoneySet('$thisI');countAll();" title="补贴类发票金额不计入到单据发票金额中,只用于打单显示"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
	                        </td>
	                    </tr>
EOT;
				} else {
					$billTypeStr = $this->initBillType_d($billTypeArr, $thisV['BillTypeID'], $thisCostType['invoiceType'], $thisCostType['isReplace']);
					$thisI = $countI . "_" . $thisK;
					//图片显示判定
					$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
					//方法判定
					$funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
					$invTitle = $thisK == 0 ? "添加行" : "删除本行发票";
					//发票部分
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" onblur="invMoneySet('$thisI');countInvoiceMoney();countAll();" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][isSubsidy]" value="0"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
			}

			//设置备注栏高度
			$remarkHeight = (count($expenseinvArr) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
                    <td valign="top">
                        <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" value="$specialApplyNo" title="引入特别申请" onclick="showSpecialApply($countI)" readonly="readonly"/>
                    </td>
	                <td valign="top">
                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" style="height:$remarkHeight" class="txt">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/**
	 * 分摊模版处理 - 编辑渲染模版
	 * @param $expenseArr
	 * @return mixed
	 */
	function initCostshareTempEdit_d($expenseArr) {
		//查询模板小类型
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where isNew = '1'";
		$costTypeArr = $this->_db->getArray($sql);

		//模板实例化字符串
		$str = null;
		//获取所属板块
		$rs = $this->getDatadicts ("HTBK");
		$moduleArr = $rs['HTBK'];
		foreach ($expenseArr['expensecostshare'] as $k => $v) {
			$detail = $v['detail'];
			//设置费用类型Id
			$countI = $v['CostTypeID'];
			//查询本日志内的该项费用金额
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			//获取匹配费用类型
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="trCostshare$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除分摊" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensecostshare][$countI][ID]" value="$v[id]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                   	<td valign="top" class="form_text_right">
                        $v[CostType]
                        <input type="hidden" name="expense[expensecostshare][$countI][CostType]" id="costTypeCostshare$countI" value="$v[CostType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][CostTypeID]" id="costTypeIdCostshare$countI" value="$v[CostTypeID]"/>
                    </td>
			        <td colspan="3" class="innerTd">
                        <table class="form_in_table" id="tableCostshare_$countI">
EOT;
			foreach ($detail as $thisK => $thisV) {
				$thisI = $countI . "_" . $thisK;
				//图片显示判定
				$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
				//方法判定
				$funClick = $thisK == 0 ? "addModule($countI)" : "deleteModule($countI,this)";
				$invTitle = $thisK == 0 ? "添加行" : "删除本行信息";
				$moduleStr = $this->initModule_d($moduleArr,$thisV[module]);//模板实例化所属板块字符串

                // 检查该单据的费用归属部门是否开放可编辑板块的权限
                $deptId = $expenseArr['CostBelongDeptId'];
                $datadictDao = new model_system_datadict_datadict();
                $configuratorDao = new model_system_configurator_configurator();
                $selectHide = $inputHide = '';
                $moduleName = $datadictDao->getDataNameByCode($thisV[module]);
                $data = $configuratorDao->checkDeptInConfig($deptId);
                $result = ($data)? 1 : 0;
                if($result == 1){
                    $inputHide = 'display:none;';
                }else{
                    $selectHide = 'display:none;';
                }

				$str .= <<<EOT
	            	<tr id="trCostshare_$thisI">
		            	<td width="49.5%">
		            	 	<input type="hidden" name="expense[expensecostshare][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
		                	<input type="text" name="expense[expensecostshare][$countI][expenseinv][$thisK][CostMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" id="costMoneyCostshare_$thisI" value="$thisV[CostMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/>
	                    </td>
	                    <td width="32.5%">
                        	<select id="selectCostshare_$thisI" name="expense[expensecostshare][$countI][expenseinv][$thisK][module]" style="width:90px;$selectHide"><option value="">请选择板块</option>$moduleStr</select>
                        	<input id="inputCostshare_$thisI" type="text" value="$moduleName"  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;$inputHide" readonly/>
	                    </td>
                        <td width="18%">
                        	<img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
                        </td>
	                </tr>
EOT;
			}

			//设置备注栏高度
			$remarkHeight = (count($detail) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
	                <td valign="top">
                    	<textarea name="expense[expensecostshare][$countI][Remark]" id="remarkCostshare$countI" style="height:$remarkHeight" class="txtlong">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensecostshare'] = $str;

		return $rtArr;
	}

	/**
	 * 模板处理 - 查看渲染模板
	 * @param $expensedetail
	 * @return array
	 */
	function initTempView_d($expensedetail) {
		//返回数组
		$rtArr = array();

		//获取发票类型
		$sql = "select id,name from bill_type";
		$billTypeArr = $this->_db->getArray($sql);

		//查询模板小类型
		$sql = "select CostTypeID as id,CostTypeName as name from cost_type";
		$costTypeArr = $this->_db->getArray($sql);

		//实例化费用发票明细
		$expenseinvDao = new model_finance_expense_expenseinv();

		//模板实例化字符串
		$str = null;
		//单据总金额
		$countMoney = 0;

		//标志位
		$markArr = array();
		//合同列计算
		$countArr = array();

		//相同费用计算
		foreach ($expensedetail as $key => $val) {
			if ($val['CostMoney'] == 0) {
				continue;
			}
			//获取发票信息
			$expensedetail[$key]['expenseinv'] = $expenseinvDao->findAll(array('BillDetailID' => $val['ID'], 'BillNo' => $val['BillNo']));

			//发票信息长度
			$expensedetail[$key]['invLength'] = count($expensedetail[$key]['expenseinv']);

			if (isset($countArr[$val['MainTypeId']])) {
				$countArr[$val['MainTypeId']] += $expensedetail[$key]['invLength'];
			} else {
				$countArr[$val['MainTypeId']] = $expensedetail[$key]['invLength'];
			}
		}

		foreach ($expensedetail as $k => $v) {
			//特别申请信息
			$specialApplyNo = $v['specialApplyNo'];
			if ($v['CostMoney'] == 0) {
				continue;
			}
			$mailSize = $countArr[$v['MainTypeId']];

			//查询本日志内的该项费用金额
			$detailMoney = bcmul($v['CostMoney'], $v['days'], 2);
			$countMoney = bcadd($countMoney, $detailMoney, 2);

			//带天数金额显示
			if ($v['days'] > 1) {
				$costMoneyHtm = "<span class='formatMoney green' title='单价:" . $v['CostMoney'] . " X 天数:" . $v['days'] . "'>$detailMoney</span>";
			} else {
				$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
			}

			//费用类型转换
			$thisCostType = $this->initBillView_d($costTypeArr, $v['CostTypeID']);

			$invSize = $v['invLength'];

			foreach ($v['expenseinv'] as $thisK => $thisV) {
				$blue = $thisV['Amount'] == 0 ? 'blue' : '';
				if ($thisK == 0) {
					$billType = $this->initBillView_d($billTypeArr, $thisV['BillTypeID']);
					if (!in_array($v['MainTypeId'], $markArr)) {
						$trClass = count($markArr) % 2 == 0 ? 'tr_odd' : 'tr_even';

						$str .= <<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[MainType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $thisCostType
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
				                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
				          		<td valign="top" class="form_text_right" rowspan="$invSize">
									$specialApplyNo
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[Remark]
			                    </td>
				            </tr>
EOT;
						array_push($markArr, $v['MainTypeId']);
					} else {
						$str .= <<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $thisCostType
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
				                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
				             	<td valign="top" class="form_text_right" rowspan="$invSize">
									$specialApplyNo
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[Remark]
			                    </td>
				            </tr>
EOT;
					}
				} else {
					$billType = $this->initBillView_d($billTypeArr, $thisV['BillTypeID']);
					$str .= <<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
			                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
			            </tr>
EOT;
				}
			}
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/**
	 * 将数组初始化成option选项
	 * @param $object
	 * @param null $thisVal
	 * @param null $defaultVal
	 * @param int $isReplace
	 * @return null|string
	 */
	function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1) {
		//    	echo $thisVal . "---".$defaultVal.'---'.$isReplace.'<br/>';
		$str = null;
		$title = $isReplace ? '此费用允许替票' : '此费用不允许替票';
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
				$str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
			} elseif ($defaultVal == $val['id']) {
				if ($thisVal) {
					$str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
				} else {
					$str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
				}
			} else {
				if ($isReplace) {
					$str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
				}
			}
		}
		return $str;
	}

	/**
	 * 将所属板块数组初始化成option选项
	 * @param $object
	 * @param null $defaultVal
	 * @return null|string
	 */
	function initModule_d($object, $defaultVal = null) {
		$str = null;
		foreach ($object as $key => $val) {
			if ($val['dataCode'] == $defaultVal) {
				$str .= '<option value="' . $val['dataCode'] . '" selected="selected" title="' . $val['dataName'] . '">' . $val['dataName'] . '</option>';
			} else {
				$str .= '<option value="' . $val['dataCode'] . '" title="' . $val['dataName'] . '">' . $val['dataName'] . '</option>';
			}
		}
		return $str;
	}

	/**
	 * 查看发票值
	 * @param $object
	 * @param null $thisVal
	 * @return null
	 */
	function initBillView_d($object, $thisVal = null) {
		$str = null;
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
				return $val['name'];
			}
		}
		return null;
	}

	/**
	 * 返回对应的发票类型
	 * @param $object
	 * @param null $defaultVal
	 * @return array
	 */
	function getBillArr_d($object, $defaultVal = null) {
		if ($defaultVal) {
			$rtArr = array();
			foreach ($object as $key => $val) {
				if ($val['id'] == $defaultVal) {
					$rtArr = $val;
					break;
				}
			}
			return $rtArr;
		} else {
			return array(
				'name' => '',
				'id' => ''
			);
		}
	}

	/**
	 * 返回费用类型名称
	 * @param $object
	 * @param null $thisVal
	 * @return array|null
	 */
	function initExpenseEdit_d($object, $thisVal = null) {
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
				return array(
					'name' => $val['name'],
					'showDays' => $val['showDays'],
					'isReplace' => $val['isReplace'],
					'isEqu' => $val['isEqu'],
					'invoiceType' => $val['invoiceType'],
					'invoiceTypeName' => $val['invoiceTypeName'],
					'isSubsidy' => $val['isSubsidy']
				);
			}
		}
		return null;
	}

	/**
	 * 匹配费用类型
	 * @param $object
	 * @param $v
	 * @return array
	 */
	function fitCostType_d($object, $v) {
		foreach ($object as $key => $val) {
			if ($v['costTypeId'] == $val['budgetType']) {
				$v['costTypeId'] = $val['id'];
				$v['name'] = $val['name'];
				return $v;
			}
		}
		return $v;
	}

	/************************* 费用类型部分 ************************/

	/**
	 * 获取费用表
	 */
	function getCostType_d() {
		//模板实例化字符串
		$str = null;
		$imgLine = 'images/menu/tree_line.gif'; //直线
		$imgMinus = 'images/menu/tree_minus.gif'; //缩减符号
		$imgBlank = 'images/menu/tree_blank.gif'; //分支符号
		//update chenrf 20130425添加关闭功能
		//查询模板小类型
		$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where CostTypeLeve=1 and isNew = '1' and isClose = 0 order by orderNum";

		$costTypeArr = $this->_db->getArray($sql);
		if ($costTypeArr) {

			foreach ($costTypeArr as $key => $val) {
				$str .= "<div class='box'><table  class='form_in_table'>";
				//行变色
				$trClass = 'tr_odd';

				$str .= <<<EOT
	            	<tr class="$trClass">
	                    <td class="form_text_right" valign="top">
		                    <img src="$imgMinus" id="$val[CostTypeID]" onclick="CostTypeShowAndHide($val[CostTypeID])"/>
	                        <font style="font-weight:bold;">$val[CostTypeName]</font>
	                    </td>
		            </tr>
EOT;
				//update chenrf 20130425添加关闭功能
				//二级数据处理
				$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where ParentCostTypeID ='" . $val['CostTypeID'] . "' and CostTypeLeve=2 and isNew = '1' and isClose = 0 order by orderNum";

				$costLv2Arr = $this->_db->getArray($sql);
				if ($costLv2Arr) {
					//记录1级类
					$lv1Cls = "ct_" . $val['CostTypeID'];
					foreach ($costLv2Arr as $lv2Key => $lv2Val) {
						//update chenrf 20130425添加关闭功能
						//三级数据处理
						$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where ParentCostTypeID ='" . $lv2Val['CostTypeID'] . "' and CostTypeLeve=3 and isNew = '1' and isClose = 0 order by orderNum";

						$costLv3Arr = $this->_db->getArray($sql);
						//						print_r($costLv3Arr);
						//如果有三级数据
						if ($costLv3Arr) {
							$treeImg = $imgMinus;
							$chkHtml = "";
							$secLine = '<img src="' . $imgLine . '"/>';
						} else {
							$secLine = '';
							$treeImg = $imgBlank;
							$chkHtml = <<<EOT
								<input type="checkbox"
									id="chk$lv2Val[CostTypeID]"
									value="$lv2Val[CostTypeID]"
									name="$lv2Val[CostTypeName]"
									parentId="$val[CostTypeID]"
									parentName="$val[CostTypeName]"
									showDays="$lv2Val[showDays]"
									isReplace="$lv2Val[isReplace]"
									isEqu="$lv2Val[isEqu]"
									invoiceType="$lv2Val[invoiceType]"
									invoiceTypeName="$lv2Val[invoiceTypeName]"
									isSubsidy="$lv2Val[isSubsidy]"
									onclick="setCustomCostType($lv2Val[CostTypeID],this)"
								/>
EOT;
						}
						$str .= <<<EOT
			            	<tr class="$trClass $lv1Cls" isView="1">
			                    <td class="form_text_right" valign="top">
			                  		$secLine
			                    	<img src="$treeImg" id="$lv2Val[CostTypeID]" onclick="CostType2View($lv2Val[CostTypeID],this)"/>
									$chkHtml
			                        <span id="view$lv2Val[CostTypeID]">$lv2Val[CostTypeName]</span>
			                    </td>
				            </tr>
EOT;

						//三级数据
						if ($costLv3Arr) {
							//记录1级类
							$lv2Cls = "ct_" . $lv2Val['CostTypeID'];
							foreach ($costLv3Arr as $lv3Key => $lv3Val) {
								$str .= <<<EOT
					            	<tr class="$trClass $lv1Cls $lv2Cls" isView="1">
			                   			<td class="form_text_right" valign="top">
					                    	<img src="$imgLine"/>
					                    	<img src="$imgBlank"/>
				                    		<input type="checkbox"
				                    			id="chk$lv3Val[CostTypeID]"
				                    			value="$lv3Val[CostTypeID]"
												name="$lv3Val[CostTypeName]"
												parentId="$lv2Val[CostTypeID]"
												parentName="$lv2Val[CostTypeName]"
												showDays="$lv3Val[showDays]"
												isReplace="$lv3Val[isReplace]"
												isEqu="$lv3Val[isEqu]"
												invoiceType="$lv3Val[invoiceType]"
												invoiceTypeName="$lv3Val[invoiceTypeName]"
												isSubsidy="$lv3Val[isSubsidy]"
				                    			onclick="setCustomCostType($lv3Val[CostTypeID],this)"
			                    			/>
					                        <span id="view$lv3Val[CostTypeID]">$lv3Val[CostTypeName]</span>
					                    </td>
						            </tr>
EOT;
							}
						}
					}
				}
				$str .= "</table></div>";
			}
		}
		return $str;
	}

	/********************* 获取售前售后部门信息 *********************/
	/**
	 * 获取售前售后部门信息
	 * @param $detailType
	 * @return array
	 */
	function getSaleDept_d($detailType) {
		//返回数组
		$rtArr = array();

		include(WEB_TOR . "includes/config.php");
		//售前和售后不一样
		if ($detailType == "4") {
			//售前部门
			$sourceArr = isset($expenseSaleDept) ? $expenseSaleDept : null;
		} elseif ($detailType == "5") {
			//售后部门
			$sourceArr = isset($expenseContractDept) ? $expenseContractDept : null;
		} else {
			$sourceArr = isset($expenseTrialProjectFeeDept) ? $expenseTrialProjectFeeDept : array('triProjectDeptId' => '', 'triProjectDeptName' => '营销线');
		}
		//构建返回数组
		foreach ($sourceArr['normalDept'] as $key => $val) {
			array_push($rtArr, array('value' => $key, 'text' => $val));
		}
		//构建权限数组部分
		if ($sourceArr['limitDept']) {
			foreach ($sourceArr['limitDept'] as $key => $val) {
				if ($_SESSION['DEPT_ID'] == $key) {
					array_push($rtArr, array('value' => $key, 'text' => $val));
				}
			}
		}
		return $rtArr;
	}

	/**
	 * 判断部门是否需要省份
	 * @param $deptId
	 * @return int
	 */
	function deptIsNeedProvince_d($deptId) {
		include(WEB_TOR . "includes/config.php");
		//部门费用需要省份的部门
		$expenseNeedProvinceDept = isset($expenseNeedProvinceDept) ? $expenseNeedProvinceDept : null;
		//返回key数组
		$keyArr = array_keys($expenseNeedProvinceDept);
		if (in_array($deptId, $keyArr)) {
			return 1;
		} else {
			return 0;
		}
	}

	/********************* 外部调用获取数据接口 **********************/
	/**
	 * 根据项目获取费用明细
	 * @param $projectNo
	 * @return array
	 */
	function getFeeSum_d($projectNo) {
		$sql = "select
				sum(c.CostMoney) as CostMoney
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '打回'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostMoney']) {
			return $rs[0]['CostMoney'];
		} else {
			return 0;
		}
	}

	/**
	 * 获取某个项目的某项决算
	 * @param $projectNo
	 * @param $costTypeIds
	 * @return int
	 */
	function getSomeFeeSum_d($projectNo, $costTypeIds) {
		if (!$costTypeIds) {
			return 0;
		}
		$sql = "select
				sum(c.CostMoney) as CostMoney
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '打回'
				and l.ProjectNo = '$projectNo' AND d.CostTypeID IN($costTypeIds)
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo' AND d.CostTypeID IN($costTypeIds)
			) c";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostMoney']) {
			return $rs[0]['CostMoney'];
		} else {
			return 0;
		}
	}

	/**
	 * 根据项目获取费用明细
	 * @param $projectNo
	 * @return array
	 */
	function getFeeDetail_d($projectNo) {
		$sql = "select
				sum(c.CostMoney) as CostMoney,
				t.CostTypeName,t.ParentCostType,t.ParentCostTypeID
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '打回'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c
			left join
			cost_type t on c.CostTypeID = t.CostTypeID
			group by t.CostTypeName";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostTypeName']) {
			return $rs;
		} else {
			return array();
		}
	}

    /**
     * @param $projectNo
     * @param $costTypeName
     * @return array|bool
     */
    function getFeeDetailGroupMonth_d($projectNo, $costTypeName) {
        $sql = "select
				DATE_FORMAT(InputDate,'%Y%m') AS yearMonth,
				sum(c.CostMoney) as actFee
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney,a.CostDateEnd AS InputDate
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '打回'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney,l.CostDateBegin AS InputDate
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c
			left join
			cost_type t on c.CostTypeID = t.CostTypeID
			WHERE t.CostTypeName = '$costTypeName'
			group by DATE_FORMAT(InputDate,'%Y%m')";
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['yearMonth']) {
            return $rs;
        } else {
            return array();
        }
    }

	/**
	 * 根据项目获取费用明细 - 传入的是项目编号数组
	 * @param $projectCodeArr
	 * @return array
	 */
	function getFeeDetailByCodeArr_d($projectCodeArr) {
		$projectCodeCondition = util_jsonUtil::strBuild(implode(',', $projectCodeArr));
		$sql = "SELECT
				c.ProjectNo, SUM(c.CostMoney) AS CostMoney, c.CostTypeID
			FROM(
				SELECT
					l.ProjectNo, d.CostTypeID, ROUND((d.CostMoney*d.days),2) AS CostMoney
				FROM
					cost_summary_list l
					INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
					INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '打回'
				AND l.ProjectNo IN($projectCodeCondition)
				UNION ALL
				SELECT
					l.ProjectNo, d.CostTypeID, ROUND((d.CostMoney*d.days),2) AS CostMoney
				FROM
					cost_summary_list l
					INNER JOIN cost_detail d ON d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				AND l.ProjectNo IN($projectCodeCondition)
			) c
			GROUP BY c.ProjectNo, c.CostTypeID";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostTypeID']) {
			return $rs;
		} else {
			return array();
		}
	}

	/**
	 * 获取所有的项目决算 - 报销
     * @param $year null
     * @param $month null
	 * @param $projectCodeStr null
	 * @return array
	 */
	function getAllProjectFee_d($year = null, $month = null, $projectCodeStr = null) {
        // 如果有传入年月，则生成过滤日期的SQL段
        $periodSql = "";
        if ($year && $month) {
            $lastDate = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
            $oldFormSql = " AND TO_DAYS(a.CostDateEnd) <= TO_DAYS('" . $lastDate . "')";
            $periodSql = " AND TO_DAYS(l.CostDateBegin) <= TO_DAYS('" . $lastDate . "')";
        }

		$projectSql = "";
		if ($projectCodeStr) {
			$projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectCodeStr) . ")";
		}

		$rs = $this->_db->getArray("SELECT
				c.projectNo,SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 $projectSql
					$oldFormSql  AND l.ProjectNo <> '' AND l. STATUS <> '打回'
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,l.Amount AS costMoney
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1 $projectSql
					$periodSql AND l.ProjectNo <> ''
			) c
		GROUP BY c.projectNo");
		return $rs ? $rs : array();
	}

    /**
     * 获取某些类型的报销金额
     * @param $costTypeIds
     * @param $year null
     * @param $month null
	 * @param $projectCodeStr null
     * @return array
     */
    function getAllProjectSomeFee_d($costTypeIds, $year = null, $month = null, $projectCodeStr = null) {
		if (!$costTypeIds) {
			return array();
		}
        // 如果有传入年月，则生成过滤日期的SQL段
        $periodSql = "";
        if ($year && $month) {
            $lastDate = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
            $oldFormSql = " AND TO_DAYS(a.CostDateEnd) <= TO_DAYS('" . $lastDate . "')";
            $periodSql = " AND TO_DAYS(l.CostDateBegin) <= TO_DAYS('" . $lastDate . "')";
        }

		$projectSql = "";
		if ($projectCodeStr) {
			$projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectCodeStr) . ")";
		}

		$rs = $this->_db->getArray("SELECT
				c.projectNo,SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 $projectSql AND d.CostTypeID IN($costTypeIds)
					$oldFormSql AND l.ProjectNo <> '' AND l. STATUS <> '打回'
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail d ON d.BillNo = l.BillNo
				WHERE
					l.isNew = 1 AND l.isEffected = 1 $projectSql AND d.CostTypeID IN($costTypeIds)
					$periodSql AND l.ProjectNo <> ''
			) c
		GROUP BY c.projectNo");
		return $rs ? $rs : array();
	}

    /**
     * 获取年月项目的决算
     * @param null $year
     * @param null $month
     * @param null $projectNos
     * @return int
     */
    function getPeriodFee_d($year = null, $month = null, $projectNos = null) {
        // 如果有传入年月，则生成过滤日期的SQL段
        $periodSql = "";
        if ($year && $month) {
            $oldFormSql = " AND YEAR(a.CostDateEnd) = " . $year . " AND MONTH(a.CostDateEnd) = " . $month;
            $periodSql = " AND YEAR(l.CostDateBegin) = " . $year . " AND MONTH(l.CostDateBegin) = " . $month;
        }

        $projectSql = '';
        if ($projectNos) {
            $projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectNos) . ")";
        }

        $rs = $this->_db->getArray("SELECT
				SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 AND l. STATUS <> '打回'
					$oldFormSql $projectSql
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,l.Amount AS costMoney
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1
					$periodSql $projectSql
			) c");
        return $rs[0]['costMoney'] ? $rs[0]['costMoney'] : 0;
    }

	/**
	 * 获取 时间 范围内变化的报销
	 * @param $beginTime
	 * @param $endTime
	 * @return array|bool
	 */
	function getChangeProjectCodeList_d($beginTime, $endTime) {
		if (!$beginTime || !$endTime) {
			return array();
		}
		$timeSql = " AND UNIX_TIMESTAMP(l.InputDate) BETWEEN $beginTime AND $endTime";

		$data = $this->_db->getArray("SELECT
				c.projectNo
			FROM (
				SELECT
					l.ProjectNo AS projectNo
				FROM
					cost_summary_list l
				WHERE
					l.isproject = 1 AND l.isNew = 0 AND l.ProjectNo <> '' AND l. STATUS <> '打回'
					$timeSql
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1 AND l.ProjectNo <> ''
					$timeSql
			) c
		GROUP BY c.projectNo");

		$rs = array();
		foreach ($data as $v) {
			$rs[] = $v['projectNo'];
		}
		return $rs;
	}

    /**
     * PMS613 获取费用归属部门为系统商销售只能选的费用承担人
     * @return string
     */
    function getFeemansForXtsSales(){
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $otherDataDao = new model_common_otherdatas();
        $feemansIdForXtsSales = $otherDataDao->getConfig('limitFeeMansFor_xtsSales');
        if($feemansIdForXtsSales != ''){
            $feemansIdForXtsSalesArr = explode(",",rtrim($feemansIdForXtsSales,","));
            $feemansForXtsSalesStr = "";
            if(!empty($feemansIdForXtsSalesArr)){
                $userDao = new model_deptuser_user_user();
                foreach ($feemansIdForXtsSalesArr as $uid){
                    if(!empty($uid)){
                        $userInfo = $userDao->getUserName_d($uid);
                        $feemansForXtsSalesStr .= ($feemansForXtsSalesStr == "")? "{$uid}:{$userInfo['USER_NAME']}" : ",{$uid}:{$userInfo['USER_NAME']}";
                    }
                }
            }
            return $feemansForXtsSalesStr;
        }
    }

    /**
     * 检查报销单的费用小类是否有对应的阿里商旅的记录
     *
     * @param string $userId
     * @param string $startDate
     * @param string $endDate
     * @param string $costTypeIds
     * @return array
     */
    function chkAliTripRecord($userId = "", $startDate = "", $endDate = "", $costTypeIds = ""){
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('ALSLFYMM','config_itemSub2','',array("config_itemSub2IN" => $costTypeIds));// 391,394,397
        $dateRange = array("CostDateBegin" => $startDate,"CostDateEnd" => $endDate);
        $resultArr = $aliCostTypeArr = $backArr = array();
        if(!empty($matchConfigItem)){// 只有费用类型存在于配置端里的才做检验
            foreach ($matchConfigItem as $val){
                $aliCostTypeArr[] = $val['config_item1'];
                $resultArr[$val['config_item1']] = array(
                    "aliCostType" => $val['config_item1'],
                    "expenseCostType" => $val['config_item2'],
                    "expenseCostTypeId" => $val['config_itemSub2']
                );
            }

            // 根据传入的用户ID以及费用区间查询对应的阿里商旅费用记录,并排除掉那些不存在对应关系的费用项后
            $aliDao = new model_finance_expense_alibusinesstrip();

            $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);

            if(!empty($dataRows)){// 检查酒店记录
                foreach ($dataRows as $row){
                    if(isset($resultArr[$row['category']])){
                        $backArr[] = $resultArr[$row['category']];
                    }
                }
            }
        }

        // 必须在调用Ali的SDK之前先实例化一下本地的util,否则会出错
//        $jsonUtilObj = new util_jsonUtil();
//
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// 酒店记录
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// 机票记录
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// 火车票记录
//
//        $resultArr = util_jsonUtil::iconvGB2UTFArr($resultArr);
//        $backArr = array();
//        if(!empty($dataRows)){// 检查酒店记录
//            foreach ($dataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }
//
//        if(!empty($flightDataRows)){// 检查机票记录
//            foreach ($flightDataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }
//        if(!empty($trainDataRows)){// 检查火车票记录
//            foreach ($trainDataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }

        // 返回最终数组
        return $backArr;
    }

    /**
     * 获取所有报销单中存在的项目省份信息
     * @return mixed
     */
    function getAllProProvince_d(){
        return $this->_db->getArray("select proProvince,proProvinceId from cost_summary_list where proProvince <> '' GROUP BY proProvince;");
    }
}