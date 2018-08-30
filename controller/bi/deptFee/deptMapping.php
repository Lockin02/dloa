<?php

/**
 * ����ӳ���
 *
 * Class controller_bi_deptFee_deptMapping
 */
class controller_bi_deptFee_deptMapping extends controller_base_action
{

    function __construct()
    {
        $this->objName = "deptMapping";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * �б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ����
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * �༭
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        // ��ʼ����
        $filterStartDateY = empty($obj['filterStartDate'])? '' : date("Y",$obj['filterStartDate']);
        $filterStartDateM = empty($obj['filterStartDate'])? '' : date("m",$obj['filterStartDate']);
        $this->assign("filterStartDateM", $filterStartDateM);
        $this->assign("filterStartDateY", $filterStartDateY);

        // ��ֹ����
        $filterEndDateY = empty($obj['filterEndDate'])? '' : date("Y",$obj['filterEndDate']);
        $filterEndDateM = empty($obj['filterEndDate'])? '' : date("m",$obj['filterEndDate']);
        $this->assign("filterEndDateM", $filterEndDateM);
        $this->assign("filterEndDateY", $filterEndDateY);

        $this->view('edit');
    }

    /**
     * �鿴
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ���� - ��ʾҳ��
     */
    function c_toImport()
    {
        $this->view('import');
    }

    /**
     * ����
     */
    function c_import()
    {
        $resultArr = $this->service->import_d();
        $title = '��Ŀ�������б�';
        $head = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }

    /**
     * ����
     */
    function c_export()
    {
        set_time_limit(0); // ���ò���ʱ
        $rows = $this->service->list_d();

        //��չ���ݴ���
        foreach ($rows as $k => $v) {
            $rows[$k]['projectRate'] = bcmul($v['projectRate'], 100, 7);
            $rows[$k]['productRate'] = bcmul($v['productRate'], 100, 7);
        }
        $colCode = $_GET['colCode'];
        $colName = $_GET['colName'];
        $head = array_combine(explode(',', $colCode), explode(',', $colName));
        model_finance_common_financeExcelUtil::export2ExcelUtil($head, $rows, '���Ź�ϵ����', array(
            'projectRate', 'productRate'
        ));
    }

    /**
     * ����д������
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        //��ʼ����
        $filterStartDateY = $filterStartDateM = '';
        if(isset($object['filterStartDateY']) && $object['filterStartDateY'] <> ''){
            $filterStartDateY = $object['filterStartDateY'];
            $filterStartDateM = empty($object['filterStartDateM'])? 1 : $object['filterStartDateM'];//���ûѡ�·�,Ĭ����1��
        }
        unset($object['filterStartDateY']);
        unset($object['filterStartDateM']);

        $filterStartDate = empty($filterStartDateY)? '' : strtotime($filterStartDateY . "-" . $filterStartDateM . "-1");
        $object['filterStartDateStr'] = ($filterStartDateY != '')? $filterStartDateY."-".$filterStartDateM : '';
        $object['filterStartDate'] = $filterStartDate;

        // ��ֹ����
        $filterEndDateY = $filterEndDateM = '';
        if(isset($object['filterEndDateY']) && $object['filterEndDateY'] <> ''){
            $filterEndDateY = $object['filterEndDateY'];
            $filterEndDateM = empty($object['filterEndDateM'])? 1 : $object['filterEndDateM'];//���ûѡ�·�,Ĭ����1��
        }
        unset($object['filterEndDateY']);
        unset($object['filterEndDateM']);

        $filterEndDate = empty($filterEndDateY)? '' : strtotime($filterEndDateY . "-" . $filterEndDateM . "-1");
        $object['filterEndDateStr'] = ($filterEndDateY != '')? $filterEndDateY."-".$filterEndDateM : '';
        $object['filterEndDate'] = $filterEndDate;

        $id = $this->service->add_d ( $object, $isAddInfo );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        if ($id) {
            msg ( $msg );
        }
    }

    /**
     * ����д���༭
     */
    function c_edit($isEditInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        //��ʼ����
        $filterStartDateY = $filterStartDateM = '';
        if(isset($object['filterStartDateY']) && $object['filterStartDateY'] <> ''){
            $filterStartDateY = $object['filterStartDateY'];
            $filterStartDateM = empty($object['filterStartDateM'])? 1 : $object['filterStartDateM'];//���ûѡ�·�,Ĭ����1��
        }
        unset($object['filterStartDateY']);
        unset($object['filterStartDateM']);

        $filterStartDate = empty($filterStartDateY)? '' : strtotime($filterStartDateY . "-" . $filterStartDateM . "-1");
        $object['filterStartDateStr'] = ($filterStartDateY != '')? $filterStartDateY."-".$filterStartDateM : '';
        $object['filterStartDate'] = $filterStartDate;

        // ��ֹ����
        $filterEndDateY = $filterEndDateM = '';
        if(isset($object['filterEndDateY']) && $object['filterEndDateY'] <> ''){
            $filterEndDateY = $object['filterEndDateY'];
            $filterEndDateM = empty($object['filterEndDateM'])? 1 : $object['filterEndDateM'];//���ûѡ�·�,Ĭ����1��
        }
        unset($object['filterEndDateY']);
        unset($object['filterEndDateM']);

        $filterEndDate = empty($filterEndDateY)? '' : strtotime($filterEndDateY . "-" . $filterEndDateM . "-1");
        $object['filterEndDateStr'] = ($filterEndDateY != '')? $filterEndDateY."-".$filterEndDateM : '';
        $object['filterEndDate'] = $filterEndDate;

        if ($this->service->edit_d ( $object, $isEditInfo )) {
            msg ( '�༭�ɹ���' );
        }
    }
}