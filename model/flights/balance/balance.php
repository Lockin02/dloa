<?php

/**
 * @author Show
 * @Date 2013��7��1�� ������ 14:18:47
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

    //���ñ�������
    private $detailTypeArr = array(
        '1' => '���ŷ���',
        '2' => '��ͬ��Ŀ����',
        '3' => '�з�����',
        '4' => '��ǰ����',
        '5' => '�ۺ����'
    );

    //���ط�������
    function rtDetailType($thisVal)
    {
        if (isset($this->detailTypeArr[$thisVal])) {
            return $this->detailTypeArr[$thisVal];
        } else {
            return $thisVal;
        }
    }

    //����yn
    function rtStatus_d($thisVal)
    {
        if ($thisVal == '1') {
            return '��֧��';
        } else {
            return 'δ֧��';
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function addBatch_d($object)
    {
        //�޳������޹���Ϣ
        $items = $object['items'];
        unset($object['items']);
        try {
            $this->start_d();
            //���㵥���Զ�����
            $Bcode = new model_common_codeRule();
            $object['balanceCode'] = $Bcode->commonCode("��Ʊ����", $this->tbl_name, 'DPJS');
            $newId = parent::add_d($object, true);

            //ʵ�����ӱ�
            $batchDao = new model_flights_balance_batchitem();
            //�������Զ���ȡ���ı�������ӵ��ӱ�����ȥ
            $items = util_arrayUtil::setArrayFn(array('mainId' => $newId), $items);
            $batchDao->saveDelBatch($items);

            //ʵ������Ʊ��Ϣ���ӱ�
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
        //�޳������޹���Ϣ
        $items = $object['items'];
        unset($object['items']);

        try {
            $this->start_d();
            parent::edit_d($object, true);

            //ʵ�����ӱ�
            $batchDao = new model_flights_balance_batchitem();
            $items = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $items);
            $batchDao->saveDelBatch($items);

            //ʵ������Ʊ��Ϣ���ӱ�
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
     * ����ID�޸Ļ�MSG���״̬
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

            //ɾ������
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
     * ���ڲ�ѯmessage�����Ϣ
     * @param $msgId
     * @return mixed
     */
    function msgInfo_d($msgId)
    {
        $msgInfo = new model_flights_message_message();
        return $msgInfo->filterMes_d($msgId);
    }

    /**
     * ���½��㵥״̬
     * @param $id
     * @param int $balanceStatus
     * @throws Exception
     * @return mixed
     */
    function updatebalanceStatus_d($id, $balanceStatus = 2)
    {
        try {
            // ��������֣���ô�Ǿ�OA����������OA
            if (is_numeric($id) && strlen($id) < 32) {
                return $this->update(array('id' => $id), $this->addUpdateInfo(array('balanceStatus' => $balanceStatus)));
            } else {
                switch ($balanceStatus) {
                    case 0:
                        $payment = 'δ֧��';
                        break;
                    case 1:
                        $payment = '��֧��';
                        break;
                    case 2:
                        $payment = '֧��������';
                        break;
                    default:
                        $payment = 'δ֪״̬';
                }
                // ����aws:���¶�Ʊ������Ϣ����״̬
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

    /************** ��Ʊ��һЩ������� *********************/

    /**
     * ������鿴��Ʊ��Ϣ
     * @param $id
     * @return bool|mixed
     */
    function getBillInfo_d($id)
    {
        $billDao = new model_flights_balance_bill();
        return $billDao->getBillInfo_d($id);
    }

    /**
     * ���·�Ʊ����
     * @param  $id
     * @param  $billCode
     * @return mixed
     */
    function updateBill_d($id, $billCode)
    {
        return $this->update(array('id' => $id), array('billCode' => $billCode));
    }

    /**************** ��ʾ������Ϣ ************************/
    /**
     * ��ȡ���û���
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
     * ��ʾ���û���
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
						<th>��������</th>
						<th style="width:90px;">�� ��</th>
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
     * ��ȡ���ŷ���
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
     * ��ʾ���ŷ���
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
					<td colspan="20">-- ����������� --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:200px;">
				<thead>
					<tr class="main_tr_header">
						<th>���ù�������</th>
						<th style="width:90px;">�� ��</th>
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
     * ��ͬ��Ŀ����
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
     * ��ʾ��ͬ��Ŀ����
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
					<td colspan="20">-- ����������� --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:800px;">
				<thead>
					<tr class="main_tr_header">
						<th>��Ŀ���</th>
						<th style="width:300px;">��Ŀ����</th>
						<th style="width:150px;">���ù�������</th>
						<th style="width:90px;">�� ��</th>
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
     * ��ǰ����
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
     * ��ʾ��ǰ����
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
					<td colspan="20">-- ����������� --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:780px;">
				<thead>
					<tr class="main_tr_header">
						<th style="width:120px;">���ù�������</th>
						<th style="width:150px;">��Ŀ���</th>
						<th style="width:150px;">�̻����</th>
						<th style="width:120px;">���۸�����</th>
						<th>ʡ��</th>
						<th style="width:90px;">�� ��</th>
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
     * �ۺ����
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
     * ��ʾ�ۺ����
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
					<td colspan="20">-- ����������� --</td>
				</tr>
EOT;
        }

        $countMoney = number_format($countMoney, 2);
        $str = <<<EOT
			<table class="form_in_table" style="width:500px;">
				<thead>
					<tr class="main_tr_header">
						<th style="width:150px;">��ͬ���</th>
						<th style="width:120px;">���ù�������</th>
						<th>ʡ��</th>
						<th style="width:90px;">�� ��</th>
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
     * ��ȡ��Ӧ�ķ�����ϸ
     * @param $id
     * @return mixed
     */
    function getCostShare_d($id)
    {
        // ��������֣���ô�Ǿ�OA����������OA
        if (is_numeric($id) && strlen($id) < 32) {
            // �������ͻ�ȡ
            $costTypeDao = new model_finance_expense_costtype();
            $costTypeInfo = $costTypeDao->getCostTypeByBudgetType_d('YSLX-01');

            // �����ڻ�ȡ
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
            // ��aws��ȡ��Ʊ��������
            $result = util_curlUtil::getDataFromAWS('flights', 'GetCostShare', array(
                "bindId" => $id
            ));
            $rows = util_jsonUtil::decode($result['data'], true);
            return $rows['data']['costShare'];
        }
    }
}