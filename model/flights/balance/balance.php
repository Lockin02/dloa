<?php

/**
 * @author Show
 * @Date 2013年7月1日 星期五 14:18:47
 * @version 1.0
 */
class model_flights_balance_balance extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_flights_balance";
        $this->sql_map = "flights/balance/balanceSql.php";
        parent::__construct();
    }

    //配置报销类型
    private $detailTypeArr = array(
        '1' => '部门费用',
        '2' => '合同项目费用',
        '3' => '研发费用',
        '4' => '售前费用',
        '5' => '售后费用'
    );

    //返回费用类型
    function rtDetailType($thisVal)
    {
        if (isset($this->detailTypeArr[$thisVal])) {
            return $this->detailTypeArr[$thisVal];
        } else {
            return $thisVal;
        }
    }

    //返回yn
    function rtStatus_d($thisVal)
    {
        if ($thisVal == '1') {
            return '已支付';
        } else {
            return '未支付';
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function addBatch_d($object)
    {
        //剔除主表无关信息
        $items = $object['items'];
        unset($object['items']);
        try {
            $this->start_d();
            //结算单号自动生成
            $Bcode = new model_common_codeRule();
            $object['balanceCode'] = $Bcode->commonCode("订票结算", $this->tbl_name, 'DPJS');
            $newId = parent::add_d($object, true);

            //实例化从表
            $batchDao = new model_flights_balance_batchitem();
            //把主表自动获取到的表数据添加到从表里面去
            $items = util_arrayUtil::setArrayFn(array('mainId' => $newId), $items);
            $batchDao->saveDelBatch($items);

            //实例化订票信息主从表
            $messageDao = new model_flights_message_message();
            foreach ($items as $key => $val) {
                $messageDao->updateAuditState_d($val['msgId'], 2);
            }

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        //剔除主表无关信息
        $items = $object['items'];
        unset($object['items']);

        try {
            $this->start_d();
            parent::edit_d($object, true);

            //实例化从表
            $batchDao = new model_flights_balance_batchitem();
            $items = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $items);
            $batchDao->saveDelBatch($items);

            //实例化订票信息主从表
            $messageDao = new model_flights_message_message();
            foreach ($items as $key => $val) {
                if ($val['isDelTag'] == "1") {
                    $messageDao->updateAuditState_d($val['msgId'], 1);
                } else {
                    $messageDao->updateAuditState_d($val['msgId'], 2);
                }
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 根据ID修改回MSG表的状态
     * @param $id
     * @return bool
     */
    function delete_d($id)
    {
        try {
            $this->start_d();
            $itemDao = new model_flights_balance_batchitem();
            $itemArr = $itemDao->itemInfo_d($id);

            $messageDao = new model_flights_message_message();

            foreach ($itemArr as $key => $val) {
                $messageDao->updateAuditState_d($val['msgId'], 1);
            }

            //删除结算
            $this->delete(array('id' => $id));

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 用于查询message表的信息
     * @param $msgId
     * @return mixed
     */
    function msgInfo_d($msgId)
    {
        $msgInfo = new model_flights_message_message();
        return $msgInfo->filterMes_d($msgId);
    }

    /**
     * 更新结算单状态
     * @param $id
     * @param int $balanceStatus
     * @throws Exception
     * @return mixed
     */
    function updatebalanceStatus_d($id, $balanceStatus = 2)
    {
        try {
            // 如果是数字，那么是旧OA，否则是新OA
            if (is_numeric($id) && strlen($id) < 32) {
                return $this->update(array('id' => $id), $this->addUpdateInfo(array('balanceStatus' => $balanceStatus)));
            } else {
                switch ($balanceStatus) {
                    case 0:
                        $payment = '未支付';
                        break;
                    case 1:
                        $payment = '已支付';
                        break;
                    case 2:
                        $payment = '支付流程中';
                        break;
                    default:
                        $payment = '未知状态';
                }
                // 接入aws:更新订票结算信息结算状态
                $result = util_curlUtil::getDataFromAWS('flights', 'UpdateFlightsPayment', array(
                    "bindId" => $id, "payment" => $payment
                ));

                $errorInfo = util_jsonUtil::decode($result['data']);
                if ($errorInfo['data']['error']) {
                    throw new Exception($errorInfo['data']['error']);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /************** 发票的一些相关内容 *********************/

    /**
     * 在主表查看发票信息
     * @param $id
     * @return bool|mixed
     */
    function getBillInfo_d($id)
    {
        $billDao = new model_flights_balance_bill();
        return $billDao->getBillInfo_d($id);
    }

    /**
     * 更新发票方法
     * @param  $id
     * @param  $billCode
     * @return mixed
     */
    function updateBill_d($id, $billCode)
    {
        return $this->update(array('id' => $id), array('billCode' => $billCode));
    }

    /**************** 显示费用信息 ************************/
    /**
     * 获取费用汇总
     * @param $id
     * @return mixed
     */
    function getCostForShow_d($id)
    {
        $sql = "select
				m.detailType,sum(m.costPay) as costPay
			from
				oa_flights_balance c
					inner join
				oa_flights_balance_item i
					on c.id = i.mainId
				left join
					oa_flights_message m on i.msgId = m.id
			where c.id = $id
			group by detailType order by detailType";
        return $this->_db->getArray($sql);
    }

    /**
     * 显示费用汇总
     * @param $datas
     * @return string
     */
    function showCost_d($datas)
    {
        $countMoney = 0;
        if ($datas) {
            $trStr = "";
            foreach ($datas as $key => $val) {
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                $detailType = $this->rtDetailType($val['detailType']);
                $costPay = number_format($val['costPay'], 2);
                $trStr .= <<<EOT
					<tr class="$trClass">
						<td>$detailType</td>
						<td style="text-align:right;">$costPay</td>
					</tr>
EOT;
                $countMoney = bcAdd($countMoney, $val['costPay'], 2);
            }
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:200px;">
				<thead>
					<tr class="main_tr_header">
						<th>费用类型</th>
						<th style="width:90px;">金 额</th>
					</thead>
				</thead>
				$trStr
				<tr class="tr_count">
					<td></td>
					<td style="text-align:right;">$countMoney</td>
				</tr>
			</table>
EOT;
        return $str;
    }

    /**
     * 获取部门费用
     * @param $id
     * @return mixed
     */
    function getCostForDept_d($id)
    {
        $sql = "select
				m.costBelongDeptName,sum(m.costPay) as costPay
			from
				oa_flights_balance c
					inner join
				oa_flights_balance_item i
					on c.id = i.mainId
				left join
					oa_flights_message m on i.msgId = m.id
			where c.id = $id and detailType = 1
			group by m.costBelongDeptName order by m.costBelongDeptName";
        return $this->_db->getArray($sql);
    }

    /**
     * 显示部门费用
     * @param $datas
     * @return string
     */
    function showCostDept_d($datas)
    {
        $countMoney = 0;
        if ($datas) {
            $trStr = "";
            foreach ($datas as $key => $val) {
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costPay = number_format($val['costPay'], 2);
                $trStr .= <<<EOT
					<tr class="$trClass">
						<td>{$val['costBelongDeptName']}</td>
						<td style="text-align:right;">$costPay</td>
					</tr>
EOT;
                $countMoney = bcAdd($countMoney, $val['costPay'], 2);
            }
        } else {
            $trStr = <<<EOT
				<tr class="tr_odd">
					<td colspan="20">-- 暂无相关数据 --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:200px;">
				<thead>
					<tr class="main_tr_header">
						<th>费用归属部门</th>
						<th style="width:90px;">金 额</th>
					</thead>
				</thead>
				$trStr
				<tr class="tr_count">
					<td></td>
					<td style="text-align:right;">$countMoney</td>
				</tr>
			</table>
EOT;
        return $str;
    }

    /**
     * 合同项目费用
     * @param $id
     * @param $detailType
     * @return mixed
     */
    function getCostForProject_d($id, $detailType)
    {
        $sql = "select
				m.projectCode,m.projectName,m.costBelongDeptName,sum(m.costPay) as costPay
			from
				oa_flights_balance c
					inner join
				oa_flights_balance_item i
					on c.id = i.mainId
				left join
					oa_flights_message m on i.msgId = m.id
			where c.id = $id and detailType = $detailType
			group by m.projectCode,m.costBelongDeptName order by m.projectCode";
        return $this->_db->getArray($sql);
    }

    /**
     * 显示合同项目费用
     * @param $datas
     * @return string
     */
    function showCostProject_d($datas)
    {
        $countMoney = 0;
        if ($datas) {
            $trStr = "";
            foreach ($datas as $key => $val) {
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costPay = number_format($val['costPay'], 2);
                $trStr .= <<<EOT
					<tr class="$trClass">
						<td>{$val['projectCode']}</td>
						<td>{$val['projectName']}</td>
						<td>{$val['costBelongDeptName']}</td>
						<td style="text-align:right;">$costPay</td>
					</tr>
EOT;
                $countMoney = bcAdd($countMoney, $val['costPay'], 2);
            }
        } else {
            $trStr = <<<EOT
				<tr class="tr_odd">
					<td colspan="20">-- 暂无相关数据 --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:800px;">
				<thead>
					<tr class="main_tr_header">
						<th>项目编号</th>
						<th style="width:300px;">项目名称</th>
						<th style="width:150px;">费用归属部门</th>
						<th style="width:90px;">金 额</th>
					</thead>
				</thead>
				$trStr
				<tr class="tr_count">
					<td colspan="3"></td>
					<td style="text-align:right;">$countMoney</td>
				</tr>
			</table>
EOT;
        return $str;
    }

    /**
     * 售前费用
     * @param $id
     * @return mixed
     */
    function getCostForSale_d($id)
    {
        $sql = "select
				m.chanceCode,m.projectCode,m.projectName,m.costBelongDeptName,m.costBelonger,
				sum(m.costPay) as costPay,m.province
			from
				oa_flights_balance c
					inner join
				oa_flights_balance_item i
					on c.id = i.mainId
				left join
					oa_flights_message m on i.msgId = m.id
			where c.id = $id and detailType = 4
			group by m.chanceCode,m.projectCode,m.costBelongDeptName,m.province,m.costBelonger order by m.projectCode";
        return $this->_db->getArray($sql);
    }

    /**
     * 显示售前费用
     * @param $datas
     * @return string
     */
    function showCostSale_d($datas)
    {
        $countMoney = 0;
        if ($datas) {
            $trStr = "";
            foreach ($datas as $key => $val) {
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costPay = number_format($val['costPay'], 2);
                $trStr .= <<<EOT
					<tr class="$trClass">
						<td>{$val['costBelongDeptName']}</td>
						<td>{$val['chanceCode']}</td>
						<td>{$val['projectCode']}</td>
						<td>{$val['costBelonger']}</td>
						<td>{$val['province']}</td>
						<td style="text-align:right;">$costPay</td>
					</tr>
EOT;
                $countMoney = bcAdd($countMoney, $val['costPay'], 2);
            }
        } else {
            $trStr = <<<EOT
				<tr class="tr_odd">
					<td colspan="20">-- 暂无相关数据 --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:780px;">
				<thead>
					<tr class="main_tr_header">
						<th style="width:120px;">费用归属部门</th>
						<th style="width:150px;">项目编号</th>
						<th style="width:150px;">商机编号</th>
						<th style="width:120px;">销售负责人</th>
						<th>省份</th>
						<th style="width:90px;">金 额</th>
					</thead>
				</thead>
				$trStr
				<tr class="tr_count">
					<td colspan="5"></td>
					<td style="text-align:right;">$countMoney</td>
				</tr>
			</table>
EOT;
        return $str;
    }

    /**
     * 售后费用
     * @param $id
     * @return mixed
     */
    function getCostForContract_d($id)
    {
        $sql = "select
				m.contractCode,m.costBelongDeptName,
				sum(m.costPay) as costPay,m.province
			from
				oa_flights_balance c
					inner join
				oa_flights_balance_item i
					on c.id = i.mainId
				left join
					oa_flights_message m on i.msgId = m.id
			where c.id = $id and detailType = 5
			group by m.contractCode,m.costBelongDeptName,m.province order by m.projectCode";
        return $this->_db->getArray($sql);
    }

    /**
     * 显示售后费用
     * @param $datas
     * @return string
     */
    function showCostContract_d($datas)
    {
        $countMoney = 0;
        if ($datas) {
            $trStr = "";
            foreach ($datas as $key => $val) {
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costPay = number_format($val['costPay'], 2);
                $trStr .= <<<EOT
					<tr class="$trClass">
						<td>{$val['contractCode']}</td>
						<td>{$val['costBelongDeptName']}</td>
						<td>{$val['province']}</td>
						<td style="text-align:right;">$costPay</td>
					</tr>
EOT;
                $countMoney = bcAdd($countMoney, $val['costPay'], 2);
            }
        } else {
            $trStr = <<<EOT
				<tr class="tr_odd">
					<td colspan="20">-- 暂无相关数据 --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:500px;">
				<thead>
					<tr class="main_tr_header">
						<th style="width:150px;">合同编号</th>
						<th style="width:120px;">费用归属部门</th>
						<th>省份</th>
						<th style="width:90px;">金 额</th>
					</thead>
				</thead>
				$trStr
				<tr class="tr_count">
					<td colspan="3"></td>
					<td style="text-align:right;">$countMoney</td>
				</tr>
			</table>
EOT;
        return $str;
    }

    /**
     * 获取对应的费用明细
     * @param $id
     * @return mixed
     */
    function getCostShare_d($id)
    {
        // 如果是数字，那么是旧OA，否则是新OA
        if (is_numeric($id) && strlen($id) < 32) {
            // 费用类型获取
            $costTypeDao = new model_finance_expense_costtype();
            $costTypeInfo = $costTypeDao->getCostTypeByBudgetType_d('YSLX-01');

            // 财务期获取
            $obj = $this->find(array('id' => $id), null, 'balanceDateB');
            $inPeriod = date('Y.m', strtotime($obj['balanceDateB']));
            $sql = "SELECT
				m.detailType,m.chanceId,m.chanceCode,m.projectId,m.projectCode,m.projectName,m.projectType,
				m.costBelongDeptName AS belongDeptName,m.costBelongDeptId AS belongDeptId,
				m.costBelonger AS belongName,m.costBelongerId AS belongId,
				SUM(m.costPay) AS costMoney,m.province,m.contractId,m.contractCode,
				m.customerId,m.customerName,m.customerType,
				'" . $costTypeInfo['costTypeName'] . "' AS costTypeName, '" . $costTypeInfo['costTypeId'] .
                "' AS costTypeId, '" . $costTypeInfo['parentTypeId'] . "' AS parentTypeId, '" .
                $costTypeInfo['parentTypeName'] . "' AS parentTypeName, costBelongComId AS belongCompany,
                costBelongCom AS belongCompanyName, '$inPeriod' AS inPeriod, '$inPeriod' AS belongPeriod
			FROM
				oa_flights_balance_item i
				LEFT JOIN
					oa_flights_message m ON i.msgId = m.id
			WHERE i.mainId = $id
			GROUP BY m.contractCode,m.chanceCode,m.projectCode,m.costBelongDeptName,m.province,m.costBelonger
			ORDER BY m.detailType";
            return $this->_db->getArray($sql);
        } else {
            // 从aws获取机票费用数据
            $result = util_curlUtil::getDataFromAWS('flights', 'GetCostShare', array(
                "bindId" => $id
            ));
            $rows = util_jsonUtil::decode($result['data'], true);
            return $rows['data']['costShare'];
        }
    }
}