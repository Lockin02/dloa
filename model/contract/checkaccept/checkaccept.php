<?php

/**
 * @author tse
 * @Date 2014年4月1日 11:53:04
 * @version 1.0
 * @description:合同验收单 Model层
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
     * 根据合同ID 获取从表数据
     */
    function getDetail_d($contractId)
    {
        $this->searchArr ['contractId'] = $contractId;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * 重写新增方法
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $contractDao = new model_contract_contract_contract();
            $checkacceptArr = $object['checkaccept'];

            foreach ($checkacceptArr as $key => $val) {

                //计算预计验收时间
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
                $checkacceptArr[$key]['confirmStatus'] = "未确认";
                $checkacceptArr[$key]['checkStatus'] = "未验收";
                $checkacceptArr[$key]['isSend'] = "未发送";
                $checkacceptArr[$key]['realEndDate'] = $realDate;
                $checkacceptArr[$key]['checkDate'] = $date;
            }
            $this->createBatch($checkacceptArr);
            //更新合同检验状态
            $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => '未验收','checkName' => $_SESSION['USER_NAME'],'checkId' => $_SESSION['USER_ID'],'checkDate' => date("Y-m-d H:i:s")));
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /*
	 * 转换时间戳
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


    //导入新增方法
    function importAdd_d($object, $cid)
    {
        try {
            $this->start_d();
            $contractDao = new model_contract_contract_contract();

            $object['checkDateR'] = $this->transitionTime($object['checkDateR']);

            //计算预计验收时间
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
            $object['confirmStatus'] = !empty($object['isCon']) ? $object['isCon'] : "未确认";
            $object['checkStatus'] = "未验收";
            $object['isSend'] = "未发送";
            $object['realEndDate'] = $realDate;
            $object['checkDate'] = $date;

            $newId = parent:: add_d($object);
            //更新合同检验状态
            $contractDao->update(array('id' => $cid), array('checkStatus' => '未验收'));
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
    function edit_d($object)
    {
        $updateArr = $object['checkaccept'];
        $deleteArr = array();
        try {
            $this->start_d();
            foreach ($updateArr as $key => $val) {
                if ($val['isDelTag'] == 1) {//删除条款
                    array_push($deleteArr, $val['id']);
                } else if ($val['id'] != '') {//更新条款
                    $arr = $this->find(array('id' => $val['id']));
                    if ($arr['clause'] != $val['clause']) {  //判断是否有修改条款

                        $checkDTArray = $this->handleCheckDT($val['clause'], $val['days'] ? $val['days'] : 0, $object['contractId'], true);
                        $date = $checkDTArray['payDT'];
                        $realDate = $checkDTArray['realDT'];

                        $this->update(
                            array('id' => $val['id']),
                            array(
                                'days' => $val['days'], 'clause' => $val['clause'], 'checkDate' => $date,
                                'realEndDate' => $realDate, 'confirmStatus' => '未确认',
                                'isChange' => 1
                            )
                        );
                    } else {
                        parent::edit_d($val);
                    }
                } else {//新增条款
                    $checkDTArray = $this->handleCheckDT($val['clause'], $val['days'] ? $val['days'] : 0, $object['contractId'], true);
                    $date = $checkDTArray['payDT'];
                    $realDate = $checkDTArray['realDT'];

                    parent::add_d(array(
                        'contractId' => $object['contractId'], 'contractCode' => $object['contractCode'],
                        'confirmStatus' => '未确认', 'days' => $val['days'],
                        'checkStatus' => '未验收', 'isSend' => '未发送', 'clause' => $val['clause'],
                        'checkDate' => $date, 'realEndDate' => $realDate,
                        'clauseInfo' => $val['clauseInfo']
                    ));
                }
            }
            //删除条款
            if (!empty($deleteArr)) {
                $ids = implode(',', $deleteArr);
                $this->deletes($ids);
            }
            //修改合同验收状态
            $contractDao = new model_contract_contract_contract();
            $count1 = $this->findCount(array('contractId' => $object['contractId'], 'checkStatus' => '未验收'));
            $count2 = $this->findCount(array('contractId' => $object['contractId'], 'checkStatus' => '已验收'));
            if ($count1 == 0) {
                if ($count2 != 0) {
                    $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => '已验收'));
                } else {
                    $contractDao->update(array('id' => $object['contractId']), array('checkStatus' => '未录入'));
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
     * 确认预计验收日期
     */
    function confirm_d($object)
    {
        try {
            $this->update(array('id' => $object['id']), array('checkDate' => $object['checkDate'], 'confirmStatus' => '已确认', 'isSend' => '已发送'));
            //发送邮件
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
     * 变更预计验收日期
     */
    function change_d($object)
    {
        try {
            $this->update(array('id' => $object['id']), array('checkDate' => $object['checkDate'], 'confirmStatus' => '已确认', 'isSend' => '已发送'));
            $checkArr['checkacceptId'] = $object['id'];
            $checkArr['checkacceptType'] = 'checkDate';
            $checkArr['checkacceptNewV'] = $object['checkDate'];
            $checkArr['checkacceptOldV'] = $object['checkDateOld'];
            $newCheckArr = $this->addCreateInfo($checkArr);
            $this->newCreate($newCheckArr);
            //发送邮件
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
        if (FALSE != $this->_db->query($sql)) { // 获取当前新增的ID
            if ($newinserid = $this->_db->insert_id()) {
                return $newinserid;
            } else {
                //				return array_pop ( $this->find ( $row, "{$this->pk} DESC", $this->pk ) );
            }
        }
        return FALSE;
    }

    /**
     * 验收
     * @param unknown $object
     * @return number
     */
    function check_d($object)
    {
        try {
            $obj = $this->get_d($object['id']);
            if ($object['isError'] == 1 && empty($object['reason'])) {//检测是否是异常验收并且原因是否为空
                return 2;
            } else {
                $date1 = date("Y-m-d");
                $date2 = $obj['checkDate'];
                $d1 = strtotime($date1);
                $d2 = strtotime($date2);
                $days = round(($d1 - $d2) / 3600 / 24);
                if ($days > 30) {//检测是否超期（这里定30天后为超期处理）
                    if (empty($object['reason'])) {
                        return 3;
                    }
                }
            }
            //更新验收单的验收状态
            $this->update(array('id' => $object['id']), array('checkStatus' => '已验收',
                'reason' => $object['reason'], 'realCheckDate' => $object['realCheckDate']));
            //更新合同的验收状态
            $contractDao = new model_contract_contract_contract();
            $count = $this->findCount(array('contractId' => $obj['contractId'], 'checkStatus' => '未验收'));
            if ($count == 0) {
                $contractDao->update(array('id' => $obj['contractId']), array('checkStatus' => '已验收'));
            } else {
                $contractDao->update(array('id' => $obj['contractId']), array('checkStatus' => '部分验收'));
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * 更新超期提醒
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
            $boostStr .= "-->" . "<span style='color:blue' title = '推进人： " . $v['createName'] . "
推进时间 ： " . $v['createTime'] . "'>" . $v['checkacceptType'] . "<span>";
            $boostList .= "<tr><td style='text-align: left'>【" . $v['createName'] . "】于【" . $v['createTime'] . "】将预计验收日期从 【 " . $v['checkacceptOldV'] . " 】更新至 【 " . $v['checkacceptNewV'] . " 】</td><tr>";
        }
        if ($checkacceptInfo) {
            $str .= <<<EOT
               $boostList
EOT;
        } else {
            $str .= <<<EOT
				<tr align="center">
					<td>
						<b>无变更信息</b>
					</td>

			</tr>
EOT;
        }
        return $str;
    }

    /**
     * 获取预计时间
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
            } else if ($configArr[0]['dateCode'] == 'firstOutstockDate') { // 第一次出库日期
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
     * 权限设置
     * 权限返回结果如下:
     * 如果包含权限，返回true
     * 如果无权限,返回false
     */
    function initLimit($customSql = null)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //权限配置数组
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode',
            'deptLimit' => 'c.prinvipalDeptId',
            'customerTypeLimit' => 'c.customerType',
            'contractTypeLimit' => 'c.contractType',
        );
        //权限数组
        $limitArr = array();
        $limitArr['appNameStr'] = '1';
        //权限系统
        if (isset ($sysLimit['产品权限']) && !empty ($sysLimit['产品权限']))
            $limitArr['goodsLimit'] = $sysLimit['产品权限'];
        if (isset ($sysLimit['销售区域']) && !empty ($sysLimit['销售区域']))
            $limitArr['areaLimit'] = $sysLimit['销售区域'];
        if (isset ($sysLimit['部门权限']) && !empty ($sysLimit['部门权限']))
            $limitArr['deptLimit'] = $sysLimit['部门权限'];
        if (isset ($sysLimit['客户类型']) && !empty ($sysLimit['客户类型']))
            $limitArr['customerTypeLimit'] = $sysLimit['客户类型'];
        if (isset ($sysLimit['合同类型']) && !empty ($sysLimit['合同类型']))
            $limitArr['contractTypeLimit'] = $sysLimit['合同类型'];
        if (strstr($limitArr['goodsLimit'], ';;') || strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;') || strstr($limitArr['contractTypeLimit'], ';;')) {
            return true;
        } else {
            //区域负责人获取相关区域
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //区域权限合并
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');
            }
            //销售负责人读取对应省份和客户类型
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $limitArr['saleAreaInfo'] = $saleAreaInfo;
            }
            //			print_r($limitArr);
            if (empty ($limitArr)) {
                return false;
            } else {
                //增加销售负责人
                if (!empty($limitArr['saleAreaInfo'])) {
                    $saleAreaStr = "";
                    foreach ($saleAreaInfo as $sval) {
                        $saleTemp = "";
                        //客户类型
                        $saleTemp .= " c.customerType  in ('" . str_replace(',', "','", $sval['customerType']) . "') ";
                        //省份
                        if ($sval['provinceId'] != '0') {//全国过滤掉
                            $saleTemp .= "and c.contractProvinceId ='" . $sval['provinceId'] . "'  ";
                        }
                        $saleAreaStr .= " or ( " . $saleTemp . " ) ";
                    }
                    unset($limitArr['saleAreaInfo']);//消除
                }
                //配置混合权限
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
                //判断审批人
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
                //加上销售负责区域
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