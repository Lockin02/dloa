<?php

/**
 * Class model_contract_counting_counting
 */
class model_contract_counting_counting extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_counting";
        $this->sql_map = "contract/counting/countingSql.php";
        parent::__construct();
    }

    /**
     * ��������
     * @param $contractCode
     * @param $year
     * @param $month
     * @return int
     */
    function update_d($contractCode, $year, $month)
    {
        // �������ڲ�����ͺ���ĺ�ͬ PMS 131
        $otherDataDao = new model_common_otherdatas();
        $areaNameFilter = $otherDataDao->getConfig('countingFilterAreaName');
        $areaNameFilterArr = explode(",",$areaNameFilter);
        $areaNameFiltSql = '';
        if(!empty($areaNameFilterArr)){
            $areaNameFilterStr = implode($areaNameFilterArr,"','");
            $areaNameFiltSql = "AND c.areaName NOT IN('{$areaNameFilterStr}')";
        }

        // ��ʱ
        set_time_limit(0);

        // ��ȡԭ�����ݵı��
        $marksArr = array();
        $oldData = $this->getOldData_d($contractCode, $year, $month);
        if(count($oldData) > 0){
            foreach ($oldData as $item){
                $marksArr[$item['contractId']]['isDel'] = $item['isDel'];
                $marksArr[$item['contractId']]['isTrue'] = $item['isTrue'];
            }
        }

        // ���ԭ��������
        $this->clean_d($contractCode, $year, $month);

        // ��ͬ������ȡ
        $sql = "SELECT
                c.id AS contractId, c.contractCode, c.contractName, c.moduleName AS module,
                c.contractMoney, p.exeDeptName AS area, c.createTime,
                p.productConId,p.goodsClass AS product, p.proType AS productType, p.money AS productMoney,
                p.newProLineName AS newProLine,p.newProLineCode AS newProLineCode, pr.projectId, pr.projectCode, pr.projectName, pr.projectType
            FROM
                oa_contract_contract c
                LEFT JOIN (
                    SELECT
                        c.contractId, c.proTypeId, c.proType, c.newProLineName, c.newProLineCode,
                        c.exeDeptName, SUM(money) AS money,b.goodsClass,c.id as productConId
                    FROM oa_contract_product c LEFT JOIN oa_goods_base_info b ON c.conProductId = b.id
                    WHERE c.isTemp = 0 AND c.isDel = 0
                    GROUP BY c.contractId, c.proTypeId, c.newProLineCode, c.exeDeptId, b.goodsClass
                ) p ON c.id = p.contractId
		        LEFT JOIN (
                    SELECT
                        contractId, id AS projectId, projectCode, projectName, newProLine, '����' AS projectType,'17,18' AS proTypeIds
                    FROM oa_esm_project WHERE contractType = 'GCXMYD-01'
                    UNION ALL
                    SELECT
                        contractId, id AS projectID, projectCode, projectName, proLineCode AS newProLine, '��Ʒ' AS projectType,'11' AS proTypeIds
                    FROM oa_contract_project WHERE esmProjectId IS NULL
                ) pr ON c.id = pr.contractId AND p.contractId = pr.contractId AND pr.newProLine = p.newProLineCode AND FIND_IN_SET(p.proTypeId,pr.proTypeIds)>0
            WHERE c.isTemp = 0 AND c.ExaStatus IN('���','���������') {$areaNameFiltSql} ";

        if ($contractCode) {
            $sql .= " AND c.contractCode = '$contractCode'";
        }
        if ($year) {
            $sql .= " AND YEAR(c.createTime) = $year";
        }
        if ($month) {
            $sql .= " AND MONTH(c.createTime) = $month";
        }
        $sql .= " ORDER BY c.createTime";

        // �����ӱ�
        $newSql = "
            select t.*,case ch.ExaDT WHEN ch.ExaDT is null THEN '' WHEN ch.ExaDT = '0000-00-00' THEN '' ELSE ch.ExaDT END AS lastChange from(
                {$sql}
            )t LEFT JOIN (
             select objId,MAX(ExaDT) as ExaDT from oa_contract_changlog where objType = 'contract' GROUP BY objId ORDER BY objId DESC
             ) ch ON t.contractId = ch.objId
        ";

        $data = $this->_db->getArray($newSql);

        if ($data) {
            // ��Ŀӳ��
            $keyMap = array();
            // ��Ʒ��Ŀӳ��
            $conKeyMap = array();
            foreach ($data as $k => $v) {
                // �����ܶ�
                $newProLineMoney = $this->getContractProLineMoney($v['contractId'],$v['newProLineCode'],$v['projectType']);
                $data[$k]['newProLineMoney'] = sprintf("%.2f",$newProLineMoney);

                // �����Ʒ��ͬռ��
                // $data[$k]['productRate'] = bcdiv($v['productMoney'], $v['contractMoney'], 10);
                $productMoney = sprintf("%.2f",$v['productMoney']);
                $newProLineMoney = sprintf("%.2f",$newProLineMoney);
                $data[$k]['productRate'] = bcdiv($productMoney, $newProLineMoney, 10);

                // ��Ŀid���棬�����id,�����ȥ��ȡ��Ŀ����
                if ($v['projectId'] && $v['projectType'] == '����') {
                    if (!isset($keyMap[$v['projectId']])) {
                        $keyMap[$v['projectId']] = array();
                    }
                    $keyMap[$v['projectId']][] = $k;
                }

                // ��Ŀid���棬�����id,�����ȥ��ȡ��Ŀ����
                if ($v['projectId'] && $v['projectType'] == '��Ʒ') {
                    if (!isset($conKeyMap[$v['projectId']])) {
                        $conKeyMap[$v['projectId']] = array();
                    }
                    $conKeyMap[$v['projectId']][] = $k;
                }

                // �����Ӧ����ʷ���
                if(isset($marksArr[$v['contractId']])){
                    $data[$k]['isDel'] = $marksArr[$v['contractId']]['isDel'];
                    $data[$k]['isTrue'] = $marksArr[$v['contractId']]['isTrue'];
                }
            }

            // ��Ŀ�������
            if ($keyMap) {
                $projectDao = new model_engineering_project_esmproject();
                $projectMap = $projectDao->getMapByIds_d(implode(',', array_keys($keyMap)));

                // ����Ŀ���ݷ���ԭʼ����
                foreach ($keyMap as $k => $v) {
                    foreach ($v as $vi) {
                        $data[$vi]['projectMoney'] = sprintf("%.2f", $projectMap[$k]['projectMoneyWithTax']);
                        $data[$vi]['projectIncome'] = $projectMap[$k]['curIncome'];
                        $data[$vi]['projectIncome'] = sprintf("%.2f", $data[$vi]['projectIncome']);
                        $data[$vi]['projectFee'] = $projectMap[$k]['feeAll'];
                        $data[$vi]['projectRate'] = round(bcdiv($data[$vi]['projectMoney'], $data[$vi]['contractMoney'], 10), 9);
                        $data[$vi]['productIncome'] = round(bcmul($data[$vi]['productRate'], $data[$vi]['projectIncome'], 9), 2);
                        $data[$vi]['productFee'] = round(bcmul($data[$vi]['productRate'], $data[$vi]['projectFee'], 9), 2);
                        $data[$vi]['productProfit'] = bcsub($data[$vi]['productIncome'], $data[$vi]['productFee'], 2);
                        $data[$vi]['productRate'] = round($data[$vi]['productRate'],9);
                    }
                }
            }

            if ($conKeyMap) {
                $conProjectDao = new model_contract_conproject_conproject();
                $conProjectMap = $conProjectDao->getMapByIds_d(implode(',', array_keys($conKeyMap)));

                // ����Ŀ���ݷ���ԭʼ����
                foreach ($conKeyMap as $k => $v) {
                    foreach ($v as $vi) {
                        $data[$vi]['projectMoney'] = sprintf("%.2f", $conProjectMap[$k]['proMoney']);
                        $data[$vi]['projectIncome'] = $conProjectMap[$k]['curIncome'];
                        $data[$vi]['projectIncome'] = sprintf("%.2f", $data[$vi]['projectIncome']);
                        $data[$vi]['projectFee'] = $conProjectMap[$k]['feeAll'];
                        $data[$vi]['projectRate'] = round(bcdiv($data[$vi]['projectMoney'], $data[$vi]['contractMoney'], 10), 9);
                        $data[$vi]['productIncome'] = round(bcmul($data[$vi]['productRate'], $data[$vi]['projectIncome'], 9), 2);
                        $data[$vi]['productFee'] = round(bcmul($data[$vi]['productRate'], $data[$vi]['projectFee'], 9), 2);
                        $data[$vi]['productProfit'] = bcsub($data[$vi]['productIncome'], $data[$vi]['productFee'], 2);
                        $data[$vi]['productRate'] = round($data[$vi]['productRate'],9);
                    }
                }
            }

            // �Ի�ȡ�������ݽ��м��
            $data = $this->checkData($data);

            // echo "<pre>";print_r($data);exit();

            foreach ($data as $k => $v) {
                parent::add_d($v);
            }
        }

        return 1;
    }

    /**
     * ͨ����ͬID �Լ����߻�ȡ��Ӧ�Ĳ����ܶ�
     * @param $contractId
     * @param $proLineCode
     * @param $projectType
     * @return int
     */
    function getContractProLineMoney($contractId,$proLineCode,$projectType){
        $countSql = "";
        switch($projectType){
            case '����':
                $countSql = "select sum(money) as productMoney from oa_contract_product where contractId = {$contractId} and isDel = 0 and newProLineCode = '{$proLineCode}' and proTypeId in (17,18);";
                break;
            case '��Ʒ':
                $countSql = "select sum(money) as productMoney from oa_contract_product where contractId = {$contractId} and isDel = 0 and newProLineCode = '{$proLineCode}' and proTypeId in (11);";
                break;
        }
        if($countSql != ""){
            $result = $this->_db->getArray($countSql);
            if($result){
                return round($result[0]['productMoney'],3);
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    /**
     * �Ի�ȡ�������ݽ��м�� ( ���˼��,������ )
     *
     * @param $dateArr
     * @return mixed
     */
    function checkData($dateArr){
        $backArr = $dateArr;
        if($dateArr){
            $catchArr = array();

            // ͳ�Ʋ�Ʒ�Լ���Ŀ�ܶ�
            foreach ($dateArr as $k => $v){
                if(!isset($catchArr[$v['contractCode']])){
                    $catchArr[$v['contractCode']]['productIds'] = array($v['productConId']);// ��ƷID����
                    $catchArr[$v['contractCode']]['productNames'] = array($v['product']);// ��Ʒ������
                    $catchArr[$v['contractCode']]['projectCodes'] = array($v['projectCode']);// ��Ŀ������
                    $catchArr[$v['contractCode']]['productMoney'] =  $v['productMoney'];// ��Ʒ���ͳ��
                    $catchArr[$v['contractCode']]['projectMoney'] =  $v['projectMoney'];// ��Ŀ���ͳ��
                }else{
                    if(!in_array($v['productConId'],$catchArr[$v['contractCode']]['productIds'])){// ͳ�Ʋ�Ʒ���
                        array_push($catchArr[$v['contractCode']]['productNames'],$v['product']);
                        array_push($catchArr[$v['contractCode']]['productIds'],$v['productConId']);
                        $catchArr[$v['contractCode']]['productMoney'] += $v['productMoney'];
                    }

                    if(!in_array($v['projectCode'],$catchArr[$v['contractCode']]['projectCodes'])){// ͳ����Ŀ���
                        array_push($catchArr[$v['contractCode']]['projectCodes'],$v['projectCode']);
                        $catchArr[$v['contractCode']]['projectMoney'] += $v['projectMoney'];
                    }
                }
            }

            // ������˼�������������ֶ�
            foreach($dateArr as $k => $v){
                unset($backArr[$k]['productConId']);
                if(isset($catchArr[$v['contractCode']])){
                    $countArr = $catchArr[$v['contractCode']];
                    $countingDif = $countArr['productMoney'] - $v['contractMoney'];
                    $buildDif = $countArr['projectMoney'] - $v['contractMoney'];
                    $backArr[$k]['countingCheck']= (abs($countingDif) < 0.01)? '��ȷ' : '����';// ���˼�飺������Ʒ����ܺ��Ƿ�����ܺ�ͬ������ʱ��ʾ����
                    $backArr[$k]['buildCheck']= (abs($buildDif) < 0.01)? '��ȷ' : '����';// �����飺����Ŀ����ܺ��Ƿ�����ܺ�ͬ������ʱ��ʾ����
                    if($backArr[$k]['buildCheck'] == "����"){
                        $buildDif = bcmul($v['contractMoney'],round($v['projectRate'],6),3);
                        $buildDif = bcsub($countArr['projectMoney'],$buildDif);
                        $backArr[$k]['buildCheck']= (abs($buildDif) < 0.01)? '��ȷ' : '����';
                    }
                }
            }
        }

        return $backArr;
    }

    /**
     * ���ԭ��������
     * @param $contractCode
     * @param $year
     * @param $month
     * @throws Exception
     */
    function clean_d($contractCode, $year, $month)
    {
        $sql = "DELETE FROM " . $this->tbl_name . " WHERE 1";
        if ($contractCode) {
            $sql .= " AND contractCode = '$contractCode'";
        }
        if ($year) {
            $sql .= " AND YEAR(createTime) = $year";
        }
        if ($month) {
            $sql .= " AND MONTH(createTime) = $month";
        }
        $this->_db->query($sql);
    }

    /**
     * ��ȡԭ��������
     * @param $contractCode
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getOldData_d($contractCode, $year, $month){
        $sql = "SELECT * FROM " . $this->tbl_name . " WHERE 1";
        if ($contractCode) {
            $sql .= " AND contractCode = '$contractCode'";
        }
        if ($year) {
            $sql .= " AND YEAR(createTime) = $year";
        }
        if ($month) {
            $sql .= " AND MONTH(createTime) = $month";
        }
        return $this->_db->getArray($sql);
    }

    /**
     * ���ü�¼�ֶ�������
     *
     * @param $field
     * @param $contractCode
     * @param $year
     * @param $month
     * @return mixed
     */
    function resetRecord_d($field, $contractCode, $year, $month){
        $sql = "UPDATE " . $this->tbl_name . " SET {$field}=0 WHERE 1";
        if ($contractCode) {
            $sql .= " AND contractCode = '$contractCode'";
        }
        if ($year) {
            $sql .= " AND YEAR(createTime) = $year";
        }
        if ($month) {
            $sql .= " AND MONTH(createTime) = $month";
        }
        $this->_db->query($sql);
        return 1;
    }
}