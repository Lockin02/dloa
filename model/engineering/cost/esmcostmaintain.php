<?php

/**
 * @author Show
 * @Date 2014年06月27日
 * @version 1.0
 * @description:项目费用维护(oa_esm_costmaintain)模型层
 */
class model_engineering_cost_esmcostmaintain extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_costmaintain";
        $this->sql_map = "engineering/cost/esmcostmaintainSql.php";
        parent:: __construct();
    }

    /**
     * 获取项目费用维护日志
     */
    function getSearchList_d($object)
    {
        $condition = '';
        if (!empty($object['projectCode'])) {
            $condition .= " and p.projectCode = '" . $object['projectCode'] . "'";
        }
        if (!empty($object['costType'])) {
            $condition .= " and c.costType = '" . util_jsonUtil::iconvUTF2GB($object['costType']) . "'";
        }
        if (!empty($object['startMonth'])) {
            $condition .= " and DATE_FORMAT(c.month,'%Y-%m') >= '" . $object['startMonth'] . "'";
        }
        if (!empty($object['endMonth'])) {
            $condition .= " and DATE_FORMAT(c.month,'%Y-%m') <= '" . $object['endMonth'] . "'";
        }
        if (!empty($object['startYear'])) {
            $condition .= " and DATE_FORMAT(c.month,'%Y') >= '" . $object['startYear'] . "'";
        }
        if (!empty($object['endYear'])) {
            $condition .= " and DATE_FORMAT(c.month,'%Y') <= '" . $object['endYear'] . "'";
        }
        if ($object['ExaStatus'] != "") {
            $condition .= " and c.ExaStatus = " . $object['ExaStatus'];
        }
        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if (!isset($sysLimit['项目费用维护管理']) || $sysLimit['项目费用维护管理'] == 0) {
            $condition .= " and c.createId = '" . $_SESSION['USER_ID'] . "'";
        }

        $sql = "
			SELECT
				c.id,c.projectId,p.projectCode,p.projectName,p.STATUS,p.statusName,p.planBeginDate,p.actBeginDate,p.planEndDate,p.actEndDate,
				DATE_FORMAT(c.month, '%Y-%m') AS month,FORMAT(c.budget,2) as budgetMoney,FORMAT(c.fee,2) as feeMoney,FORMAT(c.feeWait,2) as feeWaitMoney,c.fee,c.feeWait,c.parentCostTypeId,c.parentCostType,
				c.costTypeId,c.costType,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.remark,c.ExaStatus,c.ExaDT
			FROM
				" . $this->tbl_name . " c
			LEFT JOIN oa_esm_project p ON p.id = c.projectId
			WHERE
				1 = 1 AND isDel = 0" . $condition . "
			ORDER BY 
				c.projectId,c.parentCostType,c.costType,c.month";
        return $this->_db->getArray($sql);
    }

    /**
     * 输出html
     */
    function searchHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>序号</th><th>项目编号</th><th>项目名称</th><th>费用类型</th>' .
                '<th>月份</th><th>预算</th><th>决算</th><th>项目状态</th><th>操作人</th><th>操作时间</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'>";
                $html .= "<td>$i</td>";
                $html .= $v['projectId'] == 'noId' ? "<td align='left'>$v[projectCode]</td>" :
                    "<td align='left'><a href='javascript:void(0);' onclick='searchDetail(\"$v[projectId]\")'>$v[projectCode]</a></td>";
                $html .= "<td align='left'>$v[projectName]</td><td align='left'>$v[costType]</td><td>$v[month]</td>" .
                    "<td align='right'>$v[budgetMoney]</td><td align='right'>$v[feeMoney]</td>" .
                    "<td>$v[statusName]</td><td>$v[updateName]</td><td>$v[updateTime]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return '没有查询到数据';
        }
    }

    /**
     * 项目费用导入
     */
    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); //结果数组
        $tempArr = array();

        //实例化工程项目
        $esmprojectDao = new model_engineering_project_esmproject();
        //实例化费用类型
        $costTypeDao = new model_finance_expense_costtype();
        //实例化项目预算
        $esmbudgetDao = new model_engineering_budget_esmbudget();

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");

            //删除多余的空格以及空白数据
            foreach ($excelData as $key => $val) {
                $delete = true;
                foreach ($val as $index => $value) {
                    $excelData[$key][$index] = trim($value);
                    if ($value != '') {
                        $delete = false;
                    }
                }
                if ($delete) {
                    unset($excelData[$key]);
                }
            }

            if (count($excelData) > 0) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    $inArr = array('ExaStatus' => 1); // 初始插入数据
                    //费用归属月份
                    if (!empty($val[2]) && $val[2] != '0000-00-00' && $val[2] != '') {
                        if (!is_numeric($val[2])) {
                            $inArr['month'] = $val[2];
                        } else {
                            $inArr['month'] = util_excelUtil::exceltimtetophp($val[2]);
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写费用归属月份';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //项目编号
                    if (!empty($val[0]) && $val[0] != '') {
                        //根据项目编号判断该项目是否存在，存在则获取对应的项目id，不存在不导入该项目费用
                        $esmprojectObj = $esmprojectDao->findAll(array('projectCode' => $val[0]), null,
                            'id,projectName,planBeginDate,actBeginDate,planEndDate,actEndDate,status');
                        if (is_array($esmprojectObj)) {
                            //若查到不只一条项目记录，则报错
                            if (count($esmprojectObj) > 1) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!查询到重复的项目信息';
                                array_push($resultArr, $tempArr);
                                continue;
                            } elseif (!empty($val[1]) && $val[1] != '' && $val[1] != $esmprojectObj['0']['projectName']) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!项目编号与项目名称不匹配';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $inArr['projectCode'] = $val[0];
                            $inArr['projectId'] = $esmprojectObj['0']['id'];
                            $inArr['projectName'] = $esmprojectObj['0']['projectName'];
                            $inArr['planBeginDate'] = $esmprojectObj['0']['planBeginDate'];
                            $inArr['planEndDate'] = $esmprojectObj['0']['planEndDate'];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有查询到相关的项目信息';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写项目编号';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //费用大类
                    if (!empty($val[3]) && $val[3] != '') {
                        $inArr['parentCostType'] = $val[3];
                        //根据费用类型名称获取相应的id,外包预算和设备预算除外
                        if ($val[3] != '外包预算' && $val[3] != '设备预算') {
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[3]);
                            if (!empty($costTypeInfo)) {
                                $inArr['parentCostTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!该费用大类不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写费用大类';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //费用小类
                    if (!empty($val[4]) && $val[4] != '') {
                        $inArr['costType'] = $val[4];
                        //根据费用类型名称获取相应的id,外包预算和设备预算的费用除外
                        if ($val[3] != '外包预算' && $val[3] != '设备预算') {
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[4]);
                            if (!empty($costTypeInfo)) {
                                if ($costTypeInfo['ParentCostTypeID'] != $inArr['parentCostTypeId']) {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!费用大类与费用小类不匹配';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                $inArr['costTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!该费用小类不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写费用小类';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //预算
                    if ($val[3] == '设备预算') {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败！暂不允许导入设备预算，请通知项目经理自行变更';
                        array_push($resultArr, $tempArr);
                        continue;
                    } else {
                        $inArr['budget'] = $val[5];
                    }
                    //决算,导入的为待审核决算
                    $inArr['fee'] = $val[6];
                    //备注
                    if (!empty($val[7]) && $val[7] != '') {
                        $inArr['remark'] = $val[7];
                    }
                    //导入开始执行
                    try {
                        $this->start_d();
                        //验证导入的数据在项目费用维护表是否存在，存在则不进行任何操作
                        $conditions = array(
                            'projectId' => $inArr['projectId'],
                            'month' => $inArr['month'],
                            'parentCostType' => $inArr['parentCostType'],
                            'costType' => $inArr['costType']
                        );
                        $obj = $this->find($conditions, null, 'id,budget,fee,isDel');
                        //导入的数据已存在，则不进行任何操作
                        if ($inArr['fee'] == $obj['fee'] && $inArr['budget'] == $obj['budget'] && $obj['isDel'] == 0) {
                            $tempArr['result'] = '无任何更新';
                        } else {
                            if (!empty($obj)) {
                                $inArr['id'] = $obj['id'];
                                if ($this->edit_d($inArr, true)) {
                                    // 更新项目预算
                                    if ($esmbudgetDao->importByCostMainTain($inArr)) {
                                        $tempArr['result'] = '更新成功';
                                    }
                                }
                            } else {
                                if ($this->add_d($inArr, true)) {
                                    // 新增项目预算
                                    if ($esmbudgetDao->importByCostMainTain($inArr)) {
                                        $tempArr['result'] = '新增成功';
                                    }
                                }
                            }
                        }
                        $this->commit_d();
                    } catch (Exception $e) {
                        $this->rollBack();
                        $tempArr['result'] = '导入失败';
                    }
                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                    array_push($resultArr, $tempArr);
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 获取费用维护信息
     * @param $projectIdArr
     * @return array
     */
    function getFeeByIdArr_d($projectIdArr)
    {
        $projectCodeCondition = implode(',', $projectIdArr);
        $sql = "SELECT projectId, costTypeId, SUM(fee) AS fee
            FROM oa_esm_costmaintain
            WHERE ExaStatus = 1 AND projectId IN(" . $projectCodeCondition . ") GROUP BY projectId, costTypeId";
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['costTypeId']) {
            return $rs;
        } else {
            return array();
        }
    }

    /**
     * @param $projectId
     * @param $budgetName
     * @return array|bool
     */
    function getDetailGroupMonth_d($projectId, $budgetName)
    {
        $sql = "SELECT DATE_FORMAT(month, '%Y%m') AS yearMonth, SUM(fee) AS actFee,createName,createTime,remark
            FROM oa_esm_costmaintain
            WHERE ExaStatus = 1 AND projectId = $projectId
              AND costType = '$budgetName' GROUP BY DATE_FORMAT(month, '%Y%m')";
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['yearMonth']) {
            return $rs;
        } else {
            return array();
        }
    }

    function searchDetailJson_d($projectId, $parentCostType, $costType)
    {
        $parentCostType = util_jsonUtil::iconvUTF2GB($parentCostType);
        $costType = util_jsonUtil::iconvUTF2GB($costType);
        $sql = "SELECT *
			FROM
			(
				SELECT
			        LEFT(month, 7) AS month, budget, fee, parentCostType, costType, remark,'费用维护' AS category
				FROM oa_esm_costmaintain WHERE
					projectId = " . $projectId . " AND parentCostType = '" . $parentCostType . "' AND costType = '" . $costType . "'
				UNION ALL
				SELECT
                    yearMonth AS month, 0 AS budget, i.prepaid AS fee, '外包预算' AS parentCostType,
				    c.businessType AS costType,remark,'外包预提' AS category
				FROM
					oa_outsourcing_nprepaid c
					INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
				WHERE c.projectId = " . $projectId . " AND c.businessType = '" . $costType . "'
			) c ORDER BY category, month";

        $data = $this->_db->getArray($sql);
        $summaryRow = array('budget' => 0, 'fee' => 0, 'costType' => '合计');

        foreach ($data as $v) {
            $summaryRow['budget'] = bcadd($summaryRow['budget'], $v['budget'], 2);
            $summaryRow['fee'] = bcadd($summaryRow['fee'], $v['fee'], 2);
        }
        $data[] = $summaryRow;

        return $data;
    }
}