<?php

/**
 * @author Show
 * @Date 2014��06��27��
 * @version 1.0
 * @description:��Ŀ����ά��(oa_esm_costmaintain)ģ�Ͳ�
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
     * ��ȡ��Ŀ����ά����־
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
        //����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if (!isset($sysLimit['��Ŀ����ά������']) || $sysLimit['��Ŀ����ά������'] == 0) {
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
     * ���html
     */
    function searchHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>���</th><th>��Ŀ���</th><th>��Ŀ����</th><th>��������</th>' .
                '<th>�·�</th><th>Ԥ��</th><th>����</th><th>��Ŀ״̬</th><th>������</th><th>����ʱ��</th></tr></thead><tbody>';
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
            return 'û�в�ѯ������';
        }
    }

    /**
     * ��Ŀ���õ���
     */
    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); //�������
        $tempArr = array();

        //ʵ����������Ŀ
        $esmprojectDao = new model_engineering_project_esmproject();
        //ʵ������������
        $costTypeDao = new model_finance_expense_costtype();
        //ʵ������ĿԤ��
        $esmbudgetDao = new model_engineering_budget_esmbudget();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");

            //ɾ������Ŀո��Լ��հ�����
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
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    $inArr = array('ExaStatus' => 1); // ��ʼ��������
                    //���ù����·�
                    if (!empty($val[2]) && $val[2] != '0000-00-00' && $val[2] != '') {
                        if (!is_numeric($val[2])) {
                            $inArr['month'] = $val[2];
                        } else {
                            $inArr['month'] = util_excelUtil::exceltimtetophp($val[2]);
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д���ù����·�';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //��Ŀ���
                    if (!empty($val[0]) && $val[0] != '') {
                        //������Ŀ����жϸ���Ŀ�Ƿ���ڣ��������ȡ��Ӧ����Ŀid�������ڲ��������Ŀ����
                        $esmprojectObj = $esmprojectDao->findAll(array('projectCode' => $val[0]), null,
                            'id,projectName,planBeginDate,actBeginDate,planEndDate,actEndDate,status');
                        if (is_array($esmprojectObj)) {
                            //���鵽��ֻһ����Ŀ��¼���򱨴�
                            if (count($esmprojectObj) > 1) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��ѯ���ظ�����Ŀ��Ϣ';
                                array_push($resultArr, $tempArr);
                                continue;
                            } elseif (!empty($val[1]) && $val[1] != '' && $val[1] != $esmprojectObj['0']['projectName']) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��Ŀ�������Ŀ���Ʋ�ƥ��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $inArr['projectCode'] = $val[0];
                            $inArr['projectId'] = $esmprojectObj['0']['id'];
                            $inArr['projectName'] = $esmprojectObj['0']['projectName'];
                            $inArr['planBeginDate'] = $esmprojectObj['0']['planBeginDate'];
                            $inArr['planEndDate'] = $esmprojectObj['0']['planEndDate'];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�в�ѯ����ص���Ŀ��Ϣ';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д��Ŀ���';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //���ô���
                    if (!empty($val[3]) && $val[3] != '') {
                        $inArr['parentCostType'] = $val[3];
                        //���ݷ����������ƻ�ȡ��Ӧ��id,���Ԥ����豸Ԥ�����
                        if ($val[3] != '���Ԥ��' && $val[3] != '�豸Ԥ��') {
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[3]);
                            if (!empty($costTypeInfo)) {
                                $inArr['parentCostTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�÷��ô��಻����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д���ô���';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //����С��
                    if (!empty($val[4]) && $val[4] != '') {
                        $inArr['costType'] = $val[4];
                        //���ݷ����������ƻ�ȡ��Ӧ��id,���Ԥ����豸Ԥ��ķ��ó���
                        if ($val[3] != '���Ԥ��' && $val[3] != '�豸Ԥ��') {
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[4]);
                            if (!empty($costTypeInfo)) {
                                if ($costTypeInfo['ParentCostTypeID'] != $inArr['parentCostTypeId']) {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!���ô��������С�಻ƥ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                $inArr['costTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�÷���С�಻����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д����С��';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //Ԥ��
                    if ($val[3] == '�豸Ԥ��') {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ�ܣ��ݲ��������豸Ԥ�㣬��֪ͨ��Ŀ�������б��';
                        array_push($resultArr, $tempArr);
                        continue;
                    } else {
                        $inArr['budget'] = $val[5];
                    }
                    //����,�����Ϊ����˾���
                    $inArr['fee'] = $val[6];
                    //��ע
                    if (!empty($val[7]) && $val[7] != '') {
                        $inArr['remark'] = $val[7];
                    }
                    //���뿪ʼִ��
                    try {
                        $this->start_d();
                        //��֤�������������Ŀ����ά�����Ƿ���ڣ������򲻽����κβ���
                        $conditions = array(
                            'projectId' => $inArr['projectId'],
                            'month' => $inArr['month'],
                            'parentCostType' => $inArr['parentCostType'],
                            'costType' => $inArr['costType']
                        );
                        $obj = $this->find($conditions, null, 'id,budget,fee,isDel');
                        //����������Ѵ��ڣ��򲻽����κβ���
                        if ($inArr['fee'] == $obj['fee'] && $inArr['budget'] == $obj['budget'] && $obj['isDel'] == 0) {
                            $tempArr['result'] = '���κθ���';
                        } else {
                            if (!empty($obj)) {
                                $inArr['id'] = $obj['id'];
                                if ($this->edit_d($inArr, true)) {
                                    // ������ĿԤ��
                                    if ($esmbudgetDao->importByCostMainTain($inArr)) {
                                        $tempArr['result'] = '���³ɹ�';
                                    }
                                }
                            } else {
                                if ($this->add_d($inArr, true)) {
                                    // ������ĿԤ��
                                    if ($esmbudgetDao->importByCostMainTain($inArr)) {
                                        $tempArr['result'] = '�����ɹ�';
                                    }
                                }
                            }
                        }
                        $this->commit_d();
                    } catch (Exception $e) {
                        $this->rollBack();
                        $tempArr['result'] = '����ʧ��';
                    }
                    $tempArr['docCode'] = '��' . $actNum . '������';
                    array_push($resultArr, $tempArr);
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ��ȡ����ά����Ϣ
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
			        LEFT(month, 7) AS month, budget, fee, parentCostType, costType, remark,'����ά��' AS category
				FROM oa_esm_costmaintain WHERE
					projectId = " . $projectId . " AND parentCostType = '" . $parentCostType . "' AND costType = '" . $costType . "'
				UNION ALL
				SELECT
                    yearMonth AS month, 0 AS budget, i.prepaid AS fee, '���Ԥ��' AS parentCostType,
				    c.businessType AS costType,remark,'���Ԥ��' AS category
				FROM
					oa_outsourcing_nprepaid c
					INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
				WHERE c.projectId = " . $projectId . " AND c.businessType = '" . $costType . "'
			) c ORDER BY category, month";

        $data = $this->_db->getArray($sql);
        $summaryRow = array('budget' => 0, 'fee' => 0, 'costType' => '�ϼ�');

        foreach ($data as $v) {
            $summaryRow['budget'] = bcadd($summaryRow['budget'], $v['budget'], 2);
            $summaryRow['fee'] = bcadd($summaryRow['fee'], $v['fee'], 2);
        }
        $data[] = $summaryRow;

        return $data;
    }
}