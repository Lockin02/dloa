<?php

/**
 * @author tse
 * @Date 2014��4��1�� 11:53:04
 * @version 1.0
 * @description:��ͬ���յ� Model��
 */
class model_contract_checkaccept_checkaccept extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_contract_check";
        $this->sql_map = "contract/checkaccept/checkacceptSql.php";
        parent::__construct();
    }


    /**
     * ���ݺ�ͬID ��ȡ�ӱ�����
     */
    function getDetail_d($contractId)
    {
        $this->searchArr ['contractId'] = $contractId;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * ��д��������
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $contractDao = new model_contract_contract_contract();
            $checkacceptArr = $object['checkaccept'];

            foreach ($checkacceptArr as $key => $val) {

                //����Ԥ������ʱ��
                if (empty($val['checkDateR'])) {
                    $checkDTArray = $this->handleCheckDT($val['clause'], $val['days'] ? $val['days'] : 0, $object['contractId'], true);
                    $date = $checkDTArray['payDT'];
                    $realDate = $checkDTArray['realDT'];
                } else {
                    if (!empty($val['checkDateR']) && !empty($val['days']) && $val['checkDateR'] != '0000-00-00') {
                        $datetime = strtotime($val['checkDateR']);
                        $datetime += $val['days'] * 24 * 3600;
                        $date = date('Y-m-d', $datetime);
                    } else {
                        $date = "";
                    }
                    $realDate = "";
                }

                $checkacceptArr[$key]['contractCode'] = $object['contractCode'];
                $checkacceptArr[$key]['contractId'] = $object['contractId'];
                $checkacceptArr[$key]['confirmStatus'] = "δȷ��";
                $checkacceptArr[$key]['checkStatus'] = "δ����";
                $checkacceptArr[$key]['isSend'] = "δ����";
                $checkacceptArr[$key]['realEndDate'] = $realDate;
                $checkacceptArr[$key]['checkDate'] = $date;
            }
            $this->createBatch($checkacceptArr);
            //���º�ͬ����״̬
            $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => 'δ����','checkName' => $_SESSION['USER_NAME'],'checkId' => $_SESSION['USER_ID'],'checkDate' => date("Y-m-d H:i:s")));
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /*
	 * ת��ʱ���
	 */
    function transitionTime($timestamp)
    {
        $time = "";
        if (!empty($timestamp)) {
            if (mktime(0, 0, 0, 1, $timestamp - 1, 1900) > '2000-01-01') {
                $wirteDate = mktime(0, 0, 0, 1, $timestamp - 1, 1900);
                $time = date("Y-m-d", $wirteDate);
            } else {
                $time = $timestamp;
            }

        }
        return $time;
    }


    //������������
    function importAdd_d($object, $cid)
    {
        try {
            $this->start_d();
            $contractDao = new model_contract_contract_contract();

            $object['checkDateR'] = $this->transitionTime($object['checkDateR']);

            //����Ԥ������ʱ��
            if (empty($object['checkDateR'])) {
                $checkDTArray = $this->handleCheckDT($object['clause'], $object['days'] ? $object['days'] : 0, $cid, true);
                $date = $checkDTArray['payDT'];
                $realDate = $checkDTArray['realDT'];
            } else {
                if (!empty($object['checkDateR']) && !empty($object['days']) && $object['checkDateR'] != '0000-00-00') {

                    $datetime = strtotime($object['checkDateR']);
                    $datetime += $object['days'] * 24 * 3600;
                    $date = date('Y-m-d', $datetime);
                } else {
                    $date = "";
                }
                $realDate = "";
            }
            $object['contractId'] = $cid;
            $object['confirmStatus'] = !empty($object['isCon']) ? $object['isCon'] : "δȷ��";
            $object['checkStatus'] = "δ����";
            $object['isSend'] = "δ����";
            $object['realEndDate'] = $realDate;
            $object['checkDate'] = $date;

            $newId = parent:: add_d($object);
            //���º�ͬ����״̬
            $contractDao->update(array('id' => $cid), array('checkStatus' => 'δ����'));
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��д�༭����
     */
    function edit_d($object)
    {
        $updateArr = $object['checkaccept'];
        $deleteArr = array();
        try {
            $this->start_d();
            foreach ($updateArr as $key => $val) {
                if ($val['isDelTag'] == 1) {//ɾ������
                    array_push($deleteArr, $val['id']);
                } else if ($val['id'] != '') {//��������
                    $arr = $this->find(array('id' => $val['id']));
                    if ($arr['clause'] != $val['clause']) {  //�ж��Ƿ����޸�����

                        $checkDTArray = $this->handleCheckDT($val['clause'], $val['days'] ? $val['days'] : 0, $object['contractId'], true);
                        $date = $checkDTArray['payDT'];
                        $realDate = $checkDTArray['realDT'];

                        $this->update(
                            array('id' => $val['id']),
                            array(
                                'days' => $val['days'], 'clause' => $val['clause'], 'checkDate' => $date,
                                'realEndDate' => $realDate, 'confirmStatus' => 'δȷ��',
                                'isChange' => 1
                            )
                        );
                    } else {
                        parent::edit_d($val);
                    }
                } else {//��������
                    $checkDTArray = $this->handleCheckDT($val['clause'], $val['days'] ? $val['days'] : 0, $object['contractId'], true);
                    $date = $checkDTArray['payDT'];
                    $realDate = $checkDTArray['realDT'];

                    parent::add_d(array(
                        'contractId' => $object['contractId'], 'contractCode' => $object['contractCode'],
                        'confirmStatus' => 'δȷ��', 'days' => $val['days'],
                        'checkStatus' => 'δ����', 'isSend' => 'δ����', 'clause' => $val['clause'],
                        'checkDate' => $date, 'realEndDate' => $realDate,
                        'clauseInfo' => $val['clauseInfo']
                    ));
                }
            }
            //ɾ������
            if (!empty($deleteArr)) {
                $ids = implode(',', $deleteArr);
                $this->deletes($ids);
            }
            //�޸ĺ�ͬ����״̬
            $contractDao = new model_contract_contract_contract();
            $count1 = $this->findCount(array('contractId' => $object['contractId'], 'checkStatus' => 'δ����'));
            $count2 = $this->findCount(array('contractId' => $object['contractId'], 'checkStatus' => '������'));
            if ($count1 == 0) {
                if ($count2 != 0) {
                    $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => '������'));
                } else {
                    $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => 'δ¼��'));
                }
            }
            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    /**
     * ȷ��Ԥ����������
     */
    function confirm_d($object)
    {
        try {
            $this->update(array('id' => $object['id']), array('checkDate' => $object['checkDate'], 'confirmStatus' => '��ȷ��', 'isSend' => '�ѷ���'));
            //�����ʼ�
            $checkArr = $this->get_d($object['id']);
            $contractDao = new model_contract_contract_contract();
            $contractArr = $contractDao->find(array('id' => $checkArr['contractId']), null, 'prinvipalId');
            $this->mailDeal_d('confirmCheckDate', $contractArr['prinvipalId'], array('contractCode' => $checkArr['contractCode'], 'clause' => $checkArr['clause'], 'name' => $_SESSION['USERNAME']));
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * ���Ԥ����������
     */
    function change_d($object)
    {
        try {
            $this->update(array('id' => $object['id']), array('checkDate' => $object['checkDate'], 'confirmStatus' => '��ȷ��', 'isSend' => '�ѷ���'));
            $checkArr['checkacceptId'] = $object['id'];
            $checkArr['checkacceptType'] = 'checkDate';
            $checkArr['checkacceptNewV'] = $object['checkDate'];
            $checkArr['checkacceptOldV'] = $object['checkDateOld'];
            $newCheckArr = $this->addCreateInfo($checkArr);
            $this->newCreate($newCheckArr);
            //�����ʼ�
            $checkArr = $this->get_d($object['id']);
            $contractDao = new model_contract_contract_contract();
            $contractArr = $contractDao->find(array('id' => $checkArr['contractId']), null, 'prinvipalId');
            $this->mailDeal_d('confirmCheckDate', $contractArr['prinvipalId'], array('contractCode' => $checkArr['contractCode'], 'clause' => $checkArr['clause'], 'name' => $_SESSION['USERNAME']));
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    function newCreate($row)
    {
        if (!is_array($row))
            return FALSE;
        foreach ($row as $key => $value) {
            $cols [] = $key;
            $vals [] = "'" . $this->__val_escape($value) . "'";
        }
        $col = join(',', $cols);
        $val = join(',', $vals);

        $sql = "INSERT INTO oa_contract_check_changehistory ({$col}) VALUES ({$val})";
        if (FALSE != $this->_db->query($sql)) { // ��ȡ��ǰ������ID
            if ($newinserid = $this->_db->insert_id()) {
                return $newinserid;
            } else {
                //				return array_pop ( $this->find ( $row, "{$this->pk} DESC", $this->pk ) );
            }
        }
        return FALSE;
    }

    /**
     * ����
     * @param unknown $object
     * @return number
     */
    function check_d($object)
    {
        try {
            $obj = $this->get_d($object['id']);
            if ($object['isError'] == 1 && empty($object['reason'])) {//����Ƿ����쳣���ղ���ԭ���Ƿ�Ϊ��
                return 2;
            } else {
                $date1 = date("Y-m-d");
                $date2 = $obj['checkDate'];
                $d1 = strtotime($date1);
                $d2 = strtotime($date2);
                $days = round(($d1 - $d2) / 3600 / 24);
                if ($days > 30) {//����Ƿ��ڣ����ﶨ30���Ϊ���ڴ���
                    if (empty($object['reason'])) {
                        return 3;
                    }
                }
            }
            //�������յ�������״̬
            $this->update(array('id' => $object['id']), array('checkStatus' => '������',
                'reason' => $object['reason'], 'realCheckDate' => $object['realCheckDate']));
            //���º�ͬ������״̬
            $contractDao = new model_contract_contract_contract();
            $count = $this->findCount(array('contractId' => $obj['contractId'], 'checkStatus' => 'δ����'));
            if ($count == 0) {
                $contractDao->update(array('id' => $obj['contractId']), array('checkStatus' => '������'));
            } else {
                $contractDao->update(array('id' => $obj['contractId']), array('checkStatus' => '��������'));
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * ���³�������
     */
    function updateCheckRemind_d($object)
    {
        $remind = $this->find(array(
            'id' => $object['id']
        ), null, 'remind');
        $this->update(array(
            'id' => $object['id']
        ), array('remind' => $remind['remind'] + 1));
    }

    /**
     * @param $id
     * @return string
     */
    function getChanceHistory_d($id)
    {
        $boostStr = $str = $boostList = "";
        $checkacceptsql = "select * from oa_contract_check_changehistory where checkacceptId='" . $id . "' and checkacceptType='checkDate'";
        $checkacceptInfo = $this->_db->getArray($checkacceptsql);
        foreach ($checkacceptInfo as $v) {
            $boostStr .= "-->" . "<span style='color:blue' title = '�ƽ��ˣ� " . $v['createName'] . "
�ƽ�ʱ�� �� " . $v['createTime'] . "'>" . $v['checkacceptType'] . "<span>";
            $boostList .= "<tr><td style='text-align: left'>��" . $v['createName'] . "���ڡ�" . $v['createTime'] . "����Ԥ���������ڴ� �� " . $v['checkacceptOldV'] . " �������� �� " . $v['checkacceptNewV'] . " ��</td><tr>";
        }
        if ($checkacceptInfo) {
            $str .= <<<EOT
               $boostList
EOT;
        } else {
            $str .= <<<EOT
				<tr align="center">
					<td>
						<b>�ޱ����Ϣ</b>
					</td>

			</tr>
EOT;
        }
        return $str;
    }

    /**
     * ��ȡԤ��ʱ��
     */
    function handleCheckDT($clause, $days, $contractId, $getRealDTAndPayDT = false)
    {
        if (empty($days))
            $days = 0;
        $handleSql = "select dateCode from oa_contract_check_setting where clause = '" . $clause . "'";
        $configArr = $this->_db->getArray($handleSql);
        if (!empty($configArr)) {
            if ($configArr[0]['dateCode'] == 'beginDate') {
                $sql = "select
                        date_add(if(actBeginDate is null,planBeginDate,actBeginDate), interval " . $days . " day) as payDT,
                        if(actBeginDate is null,planBeginDate,actBeginDate) AS realDT
                    from oa_esm_project where contractId= '" . $contractId . "'
					group by contractId";
            } else if ($configArr[0]['dateCode'] == 'endDate') {
                $sql = "select
                        date_add(if(actEndDate is null,planEndDate,actEndDate), interval " . $days . " day) as payDT,
                        if(actEndDate is null,actEndDate,actEndDate) AS realDT
                    from oa_esm_project where contractId= '" . $contractId . "'
					group by contractId";
            } else if ($configArr[0]['dateCode'] == 'c_endDate') {
                $sql = "select date_add(endDate, interval " . $days . " day) as payDT,
                        endDate AS realDT
                    from oa_contract_contract where id= '" . $contractId . "'";
            } else if ($configArr[0]['dateCode'] == 'firstOutstockDate') { // ��һ�γ�������
                $sql = "SELECT date_add(min(auditDate), interval ".$days." day) as payDT,
                        min(auditDate) AS realDT
                    FROM oa_stock_outstock WHERE contractId = '".$contractId."' AND docStatus = 'YSH'";
            } else {
                $sql = "select date_add(" . $configArr[0]['dateCode'] . ", interval " . $days . " day) as payDT,
                        " . $configArr[0]['dateCode'] . " AS realDT
                    from oa_contract_contract where id = '" . $contractId . "'";
            }
            $arr = $this->_db->getArray($sql);

            if ($getRealDTAndPayDT) {
                if (!empty($arr)) {
                    return $arr[0];
                } else {
                    return array(
                        'payDT' => '',
                        'realDT' => ''
                    );
                }
            } else {
                if (!empty($arr)) {
                    return $arr[0]['payDT'];
                } else {
                    return "";
                }
            }
        } else {
            if ($getRealDTAndPayDT) {
                return array(
                    'payDT' => '',
                    'realDT' => ''
                );
            } else {
                return "";
            }
        }
    }

    /**
     * Ȩ������
     * Ȩ�޷��ؽ������:
     * �������Ȩ�ޣ�����true
     * �����Ȩ��,����false
     */
    function initLimit($customSql = null)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //Ȩ����������
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode',
            'deptLimit' => 'c.prinvipalDeptId',
            'customerTypeLimit' => 'c.customerType',
            'contractTypeLimit' => 'c.contractType',
        );
        //Ȩ������
        $limitArr = array();
        $limitArr['appNameStr'] = '1';
        //Ȩ��ϵͳ
        if (isset ($sysLimit['��ƷȨ��']) && !empty ($sysLimit['��ƷȨ��']))
            $limitArr['goodsLimit'] = $sysLimit['��ƷȨ��'];
        if (isset ($sysLimit['��������']) && !empty ($sysLimit['��������']))
            $limitArr['areaLimit'] = $sysLimit['��������'];
        if (isset ($sysLimit['����Ȩ��']) && !empty ($sysLimit['����Ȩ��']))
            $limitArr['deptLimit'] = $sysLimit['����Ȩ��'];
        if (isset ($sysLimit['�ͻ�����']) && !empty ($sysLimit['�ͻ�����']))
            $limitArr['customerTypeLimit'] = $sysLimit['�ͻ�����'];
        if (isset ($sysLimit['��ͬ����']) && !empty ($sysLimit['��ͬ����']))
            $limitArr['contractTypeLimit'] = $sysLimit['��ͬ����'];
        if (strstr($limitArr['goodsLimit'], ';;') || strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;') || strstr($limitArr['contractTypeLimit'], ';;')) {
            return true;
        } else {
            //�������˻�ȡ�������
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //����Ȩ�޺ϲ�
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');
            }
            //���۸����˶�ȡ��Ӧʡ�ݺͿͻ�����
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $limitArr['saleAreaInfo'] = $saleAreaInfo;
            }
            //			print_r($limitArr);
            if (empty ($limitArr)) {
                return false;
            } else {
                //�������۸�����
                if (!empty($limitArr['saleAreaInfo'])) {
                    $saleAreaStr = "";
                    foreach ($saleAreaInfo as $sval) {
                        $saleTemp = "";
                        //�ͻ�����
                        $saleTemp .= " c.customerType  in ('" . str_replace(',', "','", $sval['customerType']) . "') ";
                        //ʡ��
                        if ($sval['provinceId'] != '0') {//ȫ�����˵�
                            $saleTemp .= "and c.contractProvinceId ='" . $sval['provinceId'] . "'  ";
                        }
                        $saleAreaStr .= " or ( " . $saleTemp . " ) ";
                    }
                    unset($limitArr['saleAreaInfo']);//����
                }
                //���û��Ȩ��
                $i = 0;
                $sqlStr = "sql:and ( ";
                $k = 0;
                if (!empty($limitArr['goodsLimit'])) {
                    $goodsLimitArr = explode(",", $limitArr['goodsLimit']);
                    $goodsLimitStr = "and (";
                    foreach ($goodsLimitArr as $k => $v) {
                        if ($k == 0) {
                            $goodsLimitStr .= "FIND_IN_SET($v,goodsTypeStr)";
                        } else {
                            $goodsLimitStr .= "or FIND_IN_SET($v,goodsTypeStr)";
                        }
                        $k++;
                    }
                    $goodsLimitStr .= ")";
                    unset($limitArr['goodsLimit']);
                }
                //�ж�������
                $USER = $_SESSION['USER_ID'];
                $appNameStr = "(FIND_IN_SET('" . $USER . "',appNameStr) or c.prinvipalId = '" . $_SESSION['USER_ID'] . "')";
                unset($limitArr['appNameStr']);
                foreach ($limitArr as $key => $val) {
                    $arr = explode(',', $val);
                    if (is_array($arr)) {
                        $val = "";
                        foreach ($arr as $v) {
                            $val .= "'" . $v . "',";
                        }
                        $val = substr($val, 0, -1);
                    }
                    if ($i == 0) {
                        $sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
                    } else {
                        $sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
                    }
                    $i++;
                }
                //�������۸�������
                if (empty($limitArr)) {
                    $sqlStr .= $appNameStr . $saleAreaStr;
                    //$sqlStr = "";
                    //$sqlStr .= "sql: and ".$appNameStr;
                } else {
                    $sqlStr .= "or " . $appNameStr . $saleAreaStr;
                    //$sqlStr .= ")";
                }
                $sqlStr .= ")";
                if (!empty($goodsLimitStr)) {
                    $sqlStr .= $goodsLimitStr;
                }
                if ($customSql) {
                    $sqlStr .= $customSql;
                }
                $this->searchArr['mySearchCondition'] = $sqlStr;
                return true;
            }
        }
    }

    function getListEndDate($cid, $type)
    {
        $checksettingDao = new model_contract_checksetting_checksetting();
        $setArr = $checksettingDao->find(array('clause' => $type));
        if ($setArr['dateCode'] == 'beginDate' || $setArr['dateCode'] == 'endDate') {
            $sql = "select if(actEndDate is null,planEndDate,actEndDate) as endDate from oa_esm_project where contractId= '" . $cid . "'
				group by contractId";
            $arr = $this->_db->getArray($sql);
        } else {
            $sql = "select  outstockDate as endDate " .
                "from oa_contract_contract where id = '" . $cid . "'";
            $arr = $this->_db->getArray($sql);
        };
        if (!empty($arr[0]['endDate']) && $arr[0]['endDate'] != '0000-00-00') {
            return $arr[0]['endDate'];
        } else {
            return "-";
        }

    }

}

?>