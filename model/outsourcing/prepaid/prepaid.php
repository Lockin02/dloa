<?php

/**
 * @author Acan
 * @Date 2014��10��30�� 11:03:15
 * @version 1.0
 * @description:���Ԥ�� Model��
 */
class model_outsourcing_prepaid_prepaid extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_nprepaid";
        $this->sql_map = "outsourcing/prepaid/prepaidSql.php";
        parent::__construct();
    }

    /**
     * ����Id��ȡ��Ϣ
     */
    function getInfoById_d($id)
    {
        $this->searchArr = array('id' => $id);
        $personnelRow = $this->listBySqlId("select_default");
        return $personnelRow['0'];
    }

    /**
     * ��ȡ�б�����
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $searchVal
     * @param $projectId
     * @return array|bool
     */
    function summaryList_d($beginYear, $beginMonth, $endYear, $endMonth, $searchVal, $projectId, $sortField = '', $sortOrder = '')
    {
        // ��������ƴ��
        $searchVal = util_jsonUtil::iconvUTF2GB($searchVal);
        $mainSearch = $searchVal ? " AND (c.contractCode LIKE '%" . $searchVal .
            "%' OR c.signCode LIKE '%" . $searchVal . "%' OR c.projectCode LIKE '%" . $searchVal . "%')" : "";

        $beginSearch = $beginYear ? " AND i.yearMonthTS >= " . strtotime($beginYear . "-" . $beginMonth . '-1') : "";
        $endSearch = $endYear ? " AND i.yearMonthTS <= " . strtotime($endYear . "-" . $endMonth . '-1') : "";

        // ��Ŀ��ѯ
        $projectSearch = $projectId ? " AND c.projectId = " . $projectId : "";

        // ����ѯ�ű�
//        $sql = "SELECT
//                c.id,c.contractId,c.contractCode,c.signCode,c.taxRate,c.projectId,
//		        c.projectCode,c.projectName,c.businessType,c.area,
//                i.yearMonth,i.yearMonthTS,i.original,i.adjust,i.prepaidWithTax,
//                i.prepaid,i.remark,i.act,i.invoice,i.invoiceDT,i.pay,i.payDT,
//                i.diff
//		    FROM
//		        oa_outsourcing_nprepaid c
//                INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
//		    WHERE 1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . " ORDER BY c.id DESC, i.yearMonthTS DESC ";


        $orderBy = ($sortField != '')? " order by t2.{$sortField} {$sortOrder},t2.tid" : " order by  t1.id DESC, t1.yearMonthTS DESC ";//
        $sql = "select * from (
            SELECT c.id,c.contractId,c.contractCode,c.signCode,c.taxRate,c.projectId, c.projectCode,c.projectName,c.businessType,c.area, i.yearMonth,i.yearMonthTS,i.original,i.adjust,i.prepaidWithTax, i.prepaid,i.remark,i.act,i.invoice,i.invoiceDT,i.pay,i.payDT, i.diff FROM oa_outsourcing_nprepaid c INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId 
            WHERE 1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . " 
            ORDER BY c.id DESC, i.yearMonthTS DESC
            )t1
            left join 
            (SELECT
                t.id as tid,
                sum(t.prepaid) as subPrepaid,
                sum(t.prepaidWithTax) as subPrepaidWithTax,
                sum(t.pay) as subPay,
                (sum(t.prepaidWithTax) - sum(t.pay)) as unDeal
            from(
            select c.id,i.prepaidWithTax,i.prepaid,i.pay
            FROM
                oa_outsourcing_nprepaid c
            INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
            WHERE
                1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . ")t
            group by t.id)t2 on t1.id = t2.tid {$orderBy}";

        $data = $this->_db->getArray($sql);

        if ($data) {
            // ���ݴ���
            $data = $this->summaryDeal_d($data);
        } else {
            $data = array();
        }

        return $data;
    }

    function summaryDeal_d($data)
    {
        // ӳ���
        $keyArr = array();

        foreach ($data as $k => $v) {
            if (!isset($keyArr[$v['id']])) {
                $keyArr[$v['id']] = array(
                    'k' => $k, 'subPrepaid' => 0, 'subPrepaidWithTax' => 0, 'subPay' => 0
                );
            } else {
                $data[$k]['subPrepaid'] = $data[$k]['subPrepaidWithTax'] = $data[$k]['subPay'] = $data[$k]['unDeal'] = "";
                $data[$k]['contractCode'] = $data[$k]['signCode'] = $data[$k]['taxRate'] = "";
                $data[$k]['projectCode'] = $data[$k]['projectName'] = $data[$k]['businessType'] = $data[$k]['area'] = "";
            }
//            $keyArr[$v['id']]['subPrepaid'] = bcadd($keyArr[$v['id']]['subPrepaid'], $v['prepaid'], 2);
//            $keyArr[$v['id']]['subPrepaidWithTax'] = bcadd($keyArr[$v['id']]['subPrepaidWithTax'], $v['prepaidWithTax'], 2);
//            $keyArr[$v['id']]['subPay'] = bcadd($keyArr[$v['id']]['subPay'], $v['pay'], 2);
        }

//        foreach ($keyArr as $k => $v) {
//            $data[$v['k']]['subPrepaid'] = $v['subPrepaid'];
//            $data[$v['k']]['subPrepaidWithTax'] = $v['subPrepaidWithTax'];
//            $data[$v['k']]['subPay'] = $v['subPay'];
//            $data[$v['k']]['unDeal'] = $unDeal = bcsub($v['subPrepaidWithTax'], $v['subPay'], 2);
//            foreach ($data as $dk => $dv){
//                if($dv['id'] == $k && $dk != $v['k']){
//                    $data[$dk]['subPrepaid_x'] = $v['subPrepaid'];
//                    $data[$dk]['subPrepaidWithTax_x'] = $v['subPrepaidWithTax'];
//                    $data[$dk]['subPay_x'] = $v['subPay'];
//                    $data[$dk]['unDeal_x'] = $unDeal;
//
//                }
//            }
//        }

        // ���ܱ�
        $summaryAll = array(
            'contractCode' => '�ϼ�', 'taxRate' => '', 'subPrepaid' => 0, 'subPrepaidWithTax' => 0, 'unDeal' => 0, 'subPay' => 0,
            'original' => 0, 'adjust' => 0, 'act' => 0,'prepaid' => 0, 'prepaidWithTax' => 0,
            'pay' => 0, 'invoice' => 0, 'diff' => 0
        );

        // �����ֶε���
        foreach ($data as $v) {
            foreach ($summaryAll as $ki => $vi) {
                if (!in_array($ki, array('contractCode', 'taxRate'))) {
                    $summaryAll[$ki] = bcadd($summaryAll[$ki], $v[$ki], 2);
                }
            }
        }

        $data[] = $summaryAll;

        return $data;
    }

    /******************* S ���뵼��ϵ�� ************************/
    function excelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // �������
        $tempArr = array(); // ��ʱ�������
        $itemDao = new model_outsourcing_prepaid_prepaidMonth();
        $projectDao = new model_engineering_project_esmproject(); //������Ŀ
        $budgetDao = new model_engineering_budget_esmbudget(); // ʵ������ĿԤ��
        $projectIds = array(); // ��Ŀid����
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                if (is_array($excelData)) {

                    // ��ͷ
                    $titleRow = $excelData[0];
                    unset($excelData[0]);

                    // �ȴ��������ֶ�
                    foreach ($excelData as $k => $v){
                        if($v[7] != ''){// �·��ֶ�
                            $patten = "/^\d{4}[\-](0?[1-9]|1[012])?$/";
                            if (!preg_match($patten, $v[7])){
                                $timeStr = bcmul(bcsub($v[7],25569,0),86400);
                                $excelData[$k][7] = date('Y-m', $timeStr);
                            }
                        }
                        if($v[15] != ''){// ��Ʊ����
                            $patten = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
                            if (!preg_match($patten, $v[15])){
                                $timeStr = bcmul(bcsub($v[15],25569,0),86400);
                                $excelData[$k][15] = date('Y-m-d', $timeStr);
                            }
                        }
                        if($v[17] != ''){// ֧������
                            $patten = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
                            if (!preg_match($patten, $v[17])){
                                $timeStr = bcmul(bcsub($v[17],25569,0),86400);
                                $excelData[$k][17] = date('Y-m-d', $timeStr);
                            }
                        }
                    }

                    //������ѭ��
                    $dataRecord = array();
                    foreach ($excelData as $key => $val) {
                        $actNum = $key + 2;

                        if (empty($val[0])) {
                            continue;
                        } else {
                            // ��ʽ������
                            $val = $this->formatArray_d($val, $titleRow);

                            // �Ƿ����δ��
                            $hasEmpty = false;

                            // ����У��
                            foreach ($this->needTitle as $k => $v) {
                                if (!isset($val[$v]) || !$val[$v]) {
                                    $resultArr[] = array(
                                        'docCode' => '��' . $actNum . '������', 'result' => 'û����д' . $k
                                    );
                                    $hasEmpty = true;
                                    break;
                                }
                            }
                            if ($hasEmpty) continue;

                            $project = $projectDao->find(array('projectCode' => $val['projectCode']), null, "id");
                            if (!$project) {
                                $resultArr[] = array(
                                    'docCode' => '��' . $actNum . '������', 'result' => '��Ч����Ŀ���' . $val['projectCode']
                                );
                                break;
                            }

                            $contractCode = '';
                            try {
                                // ��ѯ�Ƿ������¼��ͬ
                                $contract = $this->find(array("contractCode" => $val['contractCode']), null, "id,contractCode,projectId");
                                $contractData = array(
                                    'contractCode' => $val['contractCode'], 'signCode' => $val['signCode'],
                                    'taxRate' => bcmul($val['taxRate'], 100, 2), 'projectCode' => $val['projectCode'],
                                    'projectName' => $val['projectName'], 'businessType' => $val['businessType'],
                                    'area' => $val['area'], 'projectId' => $project['id']
                                );
                                if ($contract) {
                                    $contractCode = $contract['contractCode'];
                                    $contractData['id'] = $contractId = $contract["id"];
                                    $this->edit_d($contractData);

                                    // ������ڸ������ͬ�ļ�¼,���¼�ú�ͬ�ľ���Ŀ��Ϣ
                                    if(!isset($dataRecord[$contractCode])){
                                        $dataRecord[$contractCode]['oldProjectId'] = $contract["projectId"];
                                    }
                                } else {
                                    $contractId = $this->add_d($contractData);
                                }

                                if(isset($dataRecord[$contractCode])){
                                    $dataRecord[$contractCode]['newProjectId'] = $project['id'];
                                }

                                // ��ѯ�Ƿ������ϸ
                                $item = $itemDao->find(array("mainId" => $contractId, 'yearMonth' => $val['yearMonth']), null, "id");
                                $itemData = array(
                                    'yearMonth' => $val['yearMonth'], 'yearMonthTS' => strtotime($val['yearMonth'] . "-01"),
                                    'original' => $val['original'], 'adjust' => $val['adjust'], 'prepaidWithTax' => $val['prepaidWithTax'],
                                    'prepaid' => $val['prepaid'], 'remark' => $val['remark'], 'act' => $val['act'],
                                    'invoice' => $val['invoice'], 'invoiceDT' => $val['invoiceDT'], 'pay' => $val['pay'],
                                    'payDT' => $val['payDT'], 'diff' => $val['diff'], 'mainId' => $contractId,
                                    'projectId' => $project['id']
                                );
                                if ($item) $itemData['id'] = $item['id'];
                                $item ? $itemDao->edit_d($itemData, true) : $itemDao->add_d($itemData, true);

                                // ���¼��������ͬ���� - ���׶δ���

                                // ��Ŀ����������¼���
                                $projectIds[] = $project['id'];
                                $budgetDao->importByCostMainTain(array(
                                    'projectId' => $project['id'], 'projectCode' => $val['projectCode'],
                                    'projectName' => $val['projectName'], 'parentCostType' => '���Ԥ��',
                                    'costType' => $val['businessType'], 'remark' => $val['remark'], 'budget' => 0,
                                    'fee' => $val['prepaid']
                                ));

                                $tempArr['result'] = '����ɹ�';
                            } catch (Exception $e) {
                                $tempArr['result'] = '����ʧ��' . $e->getMessage();
                            }
                        }
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }

                    if(!empty($dataRecord)){
                        $projectIdsArr = array();
                        foreach ($dataRecord as $v){
                            if($v['newProjectId'] != $v['oldProjectId']){// ����������ĿID��֮ǰ�Ĳ�һ��,��ͳһ�����¾���Ŀ��Ԥ������Ϣ
                                $projectIdsArr[] = $v['oldProjectId'];
                                $projectIdsArr[] = $v['newProjectId'];
                            }
                        }
                        $budgetDao->updateCostByProjectIds($projectIdsArr);
                    }

                    return $resultArr;
                } else {
                    msg("�ļ������ڿ�ʶ������!");
                }
            } else {
                msg("�ϴ��ļ����Ͳ���EXCEL!");
            }
        }
    }

    // �������
    private $importTitle = array(
        // ����
        '�����ͬ��' => 'contractCode', 'ǩԼ��ͬ��' => 'signCode', '˰��' => 'taxRate', '��Ŀ���' => 'projectCode',
        '��Ŀ����' => 'projectName', '������ʽ' => 'businessType', '����' => 'area',

        // ��ϸ��
        '�·�' => 'yearMonth', 'ԭʼ' => 'original', '�����' => 'adjust', 'Ԥ��' => 'prepaidWithTax',
        'Ԥ��(����˰)' => 'prepaid', '��ע' => 'remark', 'ʵ��' => 'act', '��Ʊ' => 'invoice', '��Ʊ����' => 'invoiceDT',
        '֧��' => 'pay', '֧������' => 'payDT', 'Ԥ����ʵ�ʲ��' => 'diff'
    );

    // �������
    private $needTitle = array(
        '�����ͬ��' => 'contractCode', '��Ŀ���' => 'projectCode', '������ʽ' => 'businessType', '�·�' => 'yearMonth',
        'Ԥ��(����˰)' => 'prepaid'
    );

    /**
     * ƥ��excel�ֶ�
     * @param $data
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($data, $titleRow)
    {
        // �����µ�����
        foreach ($titleRow as $k => $v) {
            // ��������Ѷ������ݣ�����м�ֵ�滻
            if (isset($this->importTitle[$v])) {
                // ��ʽ����������
                $data[$this->importTitle[$v]] = trim($data[$k]);
            }
            // ������ɺ�ɾ������
            unset($data[$k]);
        }
        return $data;
    }
}