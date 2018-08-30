<?php

/**
 * ���ŷ��ñ�
 *
 * Class controller_bi_deptFee_deptFee
 */
class controller_bi_deptFee_deptFee extends controller_base_action
{

    function __construct()
    {
        $this->objName = "deptFee";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * tabs
     */
    function c_tabs()
    {
        $this->view('tabs');
    }

    /**
     * �б�
     */
    function c_page()
    {
        $this->assign('year', date('Y'));
        $this->assign('month', date('n'));

        // ��ȡ������ʾ�㼶
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');
        $this->assign('deptLevel', $deptLevel ? $deptLevel : 0);

        $this->view('list');
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimit()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ��ȡ����ͳ�Ƶķ�������
     */
    function c_getCostTypeList()
    {
        echo util_jsonUtil::encode($this->service->getCostTypeList_d($_POST['beginYear'], $_POST['beginMonth'],
            $_POST['endYear'], $_POST['endMonth']));
    }

    /**
     * ����ͳ���б�
     */
    function c_summaryList()
    {
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth']);

        if ($data) {
            // ��ͷ��ȡ
            $detailTitle = $this->service->getCostTypeList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                $_REQUEST['endYear'], $_REQUEST['endMonth']);

            // �ϼ���
            $countRow = array('business' => '�ϼ�', 'budget' => 0.00, 'fee' => 0.00);

            // ���ݼ���
            foreach ($data as $k => $v) {
                // ��ϸ���ݻ�ȡ
                $detailData = $this->service->summaryDetail_d($k, $_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                    $_REQUEST['endYear'], $_REQUEST['endMonth'], $v['business'], $v['secondDept'], $v['thirdDept'],
                    $v['fourthDept']);

                foreach ($detailTitle as $vi) {
                    if (isset($detailData['rows'][$vi['costType']])) {
                        $data[$k][$vi['costType']] = $detailData['rows'][$vi['costType']];
                    } else {
                        $data[$k][$vi['costType']] = 0;
                    }
                    $countRow[$vi['costType']] = bcadd($data[$k][$vi['costType']], $countRow[$vi['costType']], 2);
                }
                $countRow['budget'] = bcadd($data[$k]['budget'], $countRow['budget'], 2);
                $countRow['fee'] = bcadd($data[$k]['fee'], $countRow['fee'], 2);
            }
            $countRow['feeProcess'] = $countRow['budget'] ?
                round(bcmul(bcdiv($countRow['fee'], $countRow['budget'], 6), 100, 4), 2) : 0.00;

            // ����ϼ�
            $data[] = $countRow;
        }
        echo util_jsonUtil::encode($data);
    }

    /**
     * ����ͳ����ϸ
     */
    function c_summaryDetail()
    {
        echo util_jsonUtil::encode($this->service->summaryDetail_d($_REQUEST['rowNum'], $_REQUEST['beginYear'],
            $_REQUEST['beginMonth'], $_REQUEST['endYear'], $_REQUEST['endMonth'],
            util_jsonUtil::iconvUTF2GB($_REQUEST['business']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['secondDept']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['thirdDept']),
            util_jsonUtil::iconvUTF2GB($_REQUEST['fourthDept'])));
    }

    /**
     * ��������
     */
    function c_exportSummary()
    {
        set_time_limit(0);
        $data = $this->service->summaryList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth']);

        if ($data) {
            // ��ͷ��ȡ
            $detailTitle = $this->service->getCostTypeList_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                $_REQUEST['endYear'], $_REQUEST['endMonth']);

            // �ϼ���
            $countRow = array('business' => '�ϼ�', 'budget' => 0.00, 'fee' => 0.00);

            // ���ݼ���
            foreach ($data as $k => $v) {
                // ��ϸ���ݻ�ȡ
                $detailData = $this->service->summaryDetail_d($k, $_REQUEST['beginYear'], $_REQUEST['beginMonth'],
                    $_REQUEST['endYear'], $_REQUEST['endMonth'], $v['business'], $v['secondDept'], $v['thirdDept'],
                    $v['fourthDept']);

                foreach ($detailTitle as $vi) {
                    if (isset($detailData['rows'][$vi['costType']])) {
                        $data[$k][$vi['costType']] = $detailData['rows'][$vi['costType']];
                    } else {
                        $data[$k][$vi['costType']] = 0;
                    }
                    $countRow[$vi['costType']] = bcadd($data[$k][$vi['costType']], $countRow[$vi['costType']], 2);
                }
                $countRow['budget'] = bcadd($data[$k]['budget'], $countRow['budget'], 2);
                $countRow['fee'] = bcadd($data[$k]['fee'], $countRow['fee'], 2);
            }
            $countRow['feeProcess'] = $countRow['budget'] ?
                round(bcmul(bcdiv($countRow['fee'], $countRow['budget'], 6), 100, 4), 2) : 0.00;

            // ����ϼ�
            $data[] = $countRow;

            $colCode = $_REQUEST['colCode'];
            $colName = $_REQUEST['colName'];
            $head = array_combine(explode(',', $colCode), explode(',', $colName));
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, $_REQUEST['thisYear'] . '���ŷ��û���', array('feeProcess'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ��ϸ����
     */
    function c_exportDetail()
    {
        set_time_limit(0);
        $data = $this->service->getDetail_d($_REQUEST['exportYear'], $_REQUEST['exportMonth'], $_REQUEST['exportItems']);
        if ($data) {
            // ��ͷģ��
            $headsTemplate = array(
                'person' => array('deptName' => '��������', 'fee' => '����'),
                'expense' => array('deptName' => '��������', 'module' => '���', 'BillNo' => '��������', 'fee' => '����'),
                'pay' => array('deptName' => '��������', 'module' => '���', 'objCode' => '���ݱ��', 'fee' => '����'),
                'pk' => array('deptName' => '��������', 'projectCode' => '��Ŀ���', 'feeMonth' => '����ת�����¾���',
                    'feeAll' => '����ת�����о���', 'feeNotTurn' => 'δת�����¾���')
            );
            $heads = array();
            $sheets = explode(',', $_REQUEST['exportItems']);
            foreach ($sheets as $k => $v) {
                // ������ͷ
                $heads[] = $headsTemplate[$v];

                // �ع�sheet������
                $sheets[$k] = $this->service->feeType[$v];
            }

            model_finance_common_financeExcelUtil::exportExcelMulUtil($sheets, $heads, $data, $_REQUEST['exportYear'] . '������ϸ����');
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ������Ŀ�������á����Ų㼶��ʾ����
     */
    function c_otherSetting()
    {
        $this->assign('year', date('Y'));
        $this->assign('month', date('n'));
        $this->view('otherSetting');
    }

    /**
     * ���·���
     */
    function c_updateFee()
    {
        echo util_jsonUtil::encode($this->service->updateFee_d($_REQUEST['year'], $_REQUEST['month'], $_REQUEST['feeType']));
    }

    /**
     * ��������ά��
     */
    function c_otherFee()
    {
        $this->assign('beginYear', isset($_REQUEST['beginYear']) ? $_REQUEST['beginYear'] :date('Y'));
        $this->assign('beginMonth', isset($_REQUEST['beginMonth']) ? $_REQUEST['beginMonth'] :date('n'));
        $this->assign('endYear', isset($_REQUEST['endYear']) ? $_REQUEST['endYear'] :date('Y'));
        $this->assign('endMonth', isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] :date('n'));
        $this->view('otherFee');
    }

    /**
     * ��������ͳ����ͼ
     */
    function c_otherFeeSummary()
    {
        echo util_jsonUtil::encode($this->service->otherFeeSummary_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']));
    }

    /**
     * ��������ͳ����ͼ
     */
    function c_otherFeeDetail()
    {
        echo util_jsonUtil::encode($this->service->otherFeeDetail_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']));
    }

    /**
     * ��ת���������
     */
    function c_toImport()
    {
        $this->view('import');
    }

    /**
     * ���빦��
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '�������ݵ���';
        $head = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }

    /**
     * ����
     */
    function c_export()
    {
        set_time_limit(0);
        // ��ȡ����
        $data = $this->service->otherFeeSummary_d($_REQUEST['beginYear'], $_REQUEST['beginMonth'],
            $_REQUEST['endYear'], $_REQUEST['endMonth'], $_REQUEST['isImport']);
        if ($data) {
            $head = array(
                'business' => '��ҵ��', 'secondDept' => '��������', 'thirdDept' => '��������', 'fourthDept' => '�ļ�����',
                'costType' => '��������', 'budget' => 'Ԥ��', 'fee' => '����'
            );
            // �����´洢�ֶ�
            foreach ($data as $k => $v) {
                for ($i = $_REQUEST['beginYear']; $i <= $_REQUEST['endYear']; $i++) {
                    $begin = 1;
                    $end = 12;
                    if ($i == $_REQUEST['beginYear']) {
                        $begin = $_REQUEST['beginMonth'];
                    }
                    if ($i == $_REQUEST['endYear']) {
                        $end = $_REQUEST['endMonth'];
                    }
                    // �·�����Ⱦ
                    for ($j = $begin; $j <= $end; $j++) {
                        // �����·�
                        $yearMonth = $j >= 10 ? $i . $j : $i . str_pad($j, 2, 0, STR_PAD_LEFT);

                        // �����µ�����
                        $head['d' . $yearMonth] = $yearMonth;
                    }
                }
            }
            model_finance_common_financeExcelUtil::export2ExcelUtil($head, $data, $_REQUEST['thisYear'] . '������������');
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }
}