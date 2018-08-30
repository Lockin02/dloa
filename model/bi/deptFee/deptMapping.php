<?php

/**
 * ���Ź�ϵ��
 * Class model_bi_deptFee_deptMapping
 */
class model_bi_deptFee_deptMapping extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_dept_mapping";
        $this->sql_map = "bi/deptFee/deptMappingSql.php";
        parent::__construct();
    }

    /**
     * ��д����
     * @param $object
     * @return bool|int|mixed
     */
    function add_d($object)
    {
        $object['forbidFeeType'] = is_array($object['forbidFeeType']) ? implode(',', $object['forbidFeeType']) : '';
        return parent::add_d($object);
    }

    /**
     * ��д�༭
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        $object['forbidFeeType'] = is_array($object['forbidFeeType']) ? implode(',', $object['forbidFeeType']) : '';

        // ���������ԭ��ҵ���ֶΣ����ұ����޸�����ҵ�����������š��������š��ļ����ţ�����²�������
        if (isset($object['orgBusiness'])) {
            if ($object['business'] != $object['orgBusiness'] || $object['secondDept'] != $object['orgSecondDept'] ||
                $object['thirdDept'] != $object['orgThirdDept'] || $object['fourthDept'] != $object['orgFourthDept']
            ) {
                $deptFeeDao = new model_bi_deptFee_deptFee();
                $deptFeeDao->updateOldData_d(array(
                    'business' => $object['orgBusiness'], 'secondDept' => $object['orgSecondDept'],
                    'thirdDept' => $object['orgThirdDept'], 'fourthDept' => $object['orgFourthDept']
                ), array(
                    'business' => $object['business'], 'secondDept' => $object['secondDept'],
                    'thirdDept' => $object['thirdDept'], 'fourthDept' => $object['fourthDept']
                ));
            }
            unset($object['orgBusiness']);
            unset($object['orgSecondDept']);
            unset($object['orgThirdDept']);
            unset($object['orgFourthDept']);
        }

        return parent::edit_d($object);
    }

    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {

                // ��ͷ
                $titleRow = $excelData[0];
                unset($excelData[0]);

                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;

                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        // ��ʽ������
                        $val = $this->formatArray_d($val, $titleRow);

                        // ����У��
                        foreach ($this->needTitle as $k => $v) {
                            if (!isset($val[$v])) {
                                $resultArr[] = array(
                                    'docCode' => '��' . $actNum . '������', 'result' => 'û����д' . $k
                                );
                                break;
                            }
                        }

                        try {
                            // ����ƴװ
                            $condition = array(
                                'business' => $val['business'], 'secondDept' => $val['secondDept'],
                                'thirdDept' => $val['thirdDept'], 'fourthDept' => $val['fourthDept'],
                                'module' => $val['module']
                            );
                            // ƥ���Ƿ��Ѵ���
                            $deptFee = $this->find($condition, null, 'id');
                            if ($deptFee) {
                                //����ӳ���
                                $val['id'] = $deptFee['id'];
                                parent::edit_d($val);
                                $tempArr['result'] = '���³ɹ�';
                            } else {
                                parent::add_d($val);
                                $tempArr['result'] = '����ɹ�';
                            }
                        } catch (Exception $e) {
                            $tempArr['result'] = '����ʧ��' . $e->getMessage();
                        }
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

    // �������
    private $importTitle = array(
        '��ҵ��' => 'business', '��������' => 'secondDept', '��������' => 'thirdDept', '�ļ�����' => 'fourthDept',
        '���' => 'module', '��Ʒ��' => 'productLine', '��������' => 'mappingDept', '�������' => 'costCategory',
        '��ͳ����' => 'forbidFeeType', '��ע˵��' => 'remark', '���' => 'sortOrder'
    );

    // �������
    private $needTitle = array(
        '��ҵ��' => 'business', '��������' => 'secondDept'
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

    /**
     * ��ȡ���Ź�ϵӳ��
     * @param string $forbidFeeType ��������
     * @return array
     */
    function getMapping_d($forbidFeeType)
    {
        $mapping = array();
        $sql = "SELECT *,FIND_IN_SET('$forbidFeeType', forbidFeeType) AS getForbidItem FROM " . $this->tbl_name;
        $data = $this->_db->getArray($sql);

        // ƴװ
        if (!empty($data)) {
            foreach ($data as $v) {
                if ($v['getForbidItem']) {
                    continue;
                }
                if ($v['mappingDept']) {
                    $mappingDept = explode(',', $v['mappingDept']);

                    foreach ($mappingDept as $vi) {
                        // ��������Ǳ���\֧������ӳ����ڰ�飬��ʹ�� ������_��顿 ����Ϊӳ��key
                        $dept = $v['module'] && in_array($forbidFeeType, array('����', '֧��')) ?
                            $vi . '_' . $v['module'] : $vi;

                        // ����ӳ��
                        $mapping[$dept] = array(
                            'business' => $v['business'],
                            'secondDept' => $v['secondDept'],
                            'thirdDept' => $v['thirdDept'],
                            'fourthDept' => $v['fourthDept']
                        );
                    }
                }
            }
        }
        return $mapping;
    }

    /**
     * ������Ŀ�Ͳ��ŵ�ӳ���ϵ
     * @return array
     */
    function getProjectMapping_d()
    {
        $mapping = array();
        // ��ȡ��Ŀ�Ĳ���
        $sql = "SELECT p.id,b.feeDeptName
            FROM oa_esm_office_baseinfo b INNER JOIN oa_esm_project p ON p.officeId = b.id;";
        $projectData = $this->_db->getArray($sql);

        if (!empty($projectData)) {
            // ������Ŀ�Ͳ��ŵ�ӳ��
            foreach ($projectData as $v) {
                $deptArr = explode(',', $v['feeDeptName']);
                $mapping[$v['id']] = $deptArr[0];
            }
        }
        return $mapping;
    }

    /**
     * ��ȡ��Ŀid����Ŀ�����ӳ��
     * @return array
     */
    function getProjectCodeMapping_d()
    {
        $mapping = array();
        // ��ȡ��Ŀ�Ĳ���
        $sql = "SELECT id,projectCode FROM oa_esm_project;";
        $projectData = $this->_db->getArray($sql);

        if (!empty($projectData)) {
            // ������Ŀ�Ͳ��ŵ�ӳ��
            foreach ($projectData as $v) {
                $mapping[$v['id']] = $v['projectCode'];
            }
        }
        return $mapping;
    }

    /**
     * PK����ӳ��ת��
     * @param $mapping
     * @return mixed
     */
    function dealPKMapping_d($mapping)
    {
        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $pkMapping = $otherDatasDao->getConfig('deptFee_pk_mapping');

        // ����PK����ӳ��� [ӳ������ => ӳ�벿��]
        $finMapping = array();

        // ��ʼ���PK����ӳ��
        $pkMappingArr = explode(',', $pkMapping);

        // ѭ��ת��ӳ�䲿��
        foreach ($pkMappingArr as $k) {
            $deptMap = explode('|', $k);
            $finMapping[$deptMap[0]] = $deptMap[1];
        }

        // ����Ŀ����תΪӳ������
        foreach ($mapping as $k => $v) {
            if (isset($finMapping[$v])) {
                $mapping[$k] = $finMapping[$v];
            }
        }
        return $mapping;
    }

    /**
     * ���ݲ������ƻ�ȡ������ҵ��
     *
     * @param string $Dept
     * @return array
     */
    function getBusinessByDept($Dept = "")
    {
        if ($Dept != "") {
            $row = $this->_db->getArray("select * from oa_bi_dept_mapping c where 1=1 and FIND_IN_SET('{$Dept}',c.mappingDept)>0 limit 1");
            return $row ? $row[0] : array();
        } else {
            $rows = $this->_db->getArray("select * from oa_bi_dept_mapping c where 1=1");
            return $rows ? $rows : array();
        }
    }
}