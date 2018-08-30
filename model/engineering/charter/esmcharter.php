<?php

/**
 * @author Show
 * @Date 2011��11��26�� ������ 17:00:10
 * @version 1.0
 * @description:��Ŀ�³�(oa_esm_charter) Model��
 */
class model_engineering_charter_esmcharter extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_charter";
        $this->sql_map = "engineering/charter/esmcharterSql.php";
        parent::__construct();
    }

    // �����ֵ��ֶδ���
    public $datadictFieldArr = array('productLine', 'category', 'newProLine', 'incomeType');

    /**
     * ��ȡ��Ŀ�³�������Ϣ
     * @param $object
     * @return bool|mixed
     */
    function getObjInfo_d($object)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        $contractArr = $esmprojectDao->getProjectForCharter_d($object);
        //Ĭ�ϴ�������
        $contractArr['areaManagerId'] = '';
        $contractArr['areaManager'] = '';
        return $contractArr;
    }

    /**
     * ��дadd_d��������Ӷ���
     * �³��´�ʱ����Ŀ�������Ѹ������Ϣ��ʼ��
     * @param $object
     * @return bool
     */
    function add_d($object)
    {
        $closeDetail = $object['closedetail'];
        unset($object['closedetail']);
        if(isset($object['contractId']) && $object['contractId'] > 0){
            $conDao = new model_contract_contract_contract();
            $conArr = $conDao->get_d($object['contractId']);
            $object['moduleName'] = $conArr['moduleName'];
            $object['module'] = $conArr['module'];
            $object['areaCode'] = $conArr['areaCode'];
            $object['areaName'] = $conArr['areaName'];
        }

        try {
            $this->start_d();
            // �����ֵ�
            $object = $this->processDatadict($object);
            // �����³�
            $newId = parent::add_d($object, true);

            // ���Ԥ�ƹ��ڴ���
            $expectedDuration = round((strtotime($object['planEndDate']) - strtotime($object['planBeginDate'])) / 86400) + 1;

            // ����������Ŀ����
            $esmprojectArr = $object;
            $esmprojectArr['charterId'] = $newId;
            $esmprojectArr['expectedDuration'] = $expectedDuration;
            $esmprojectArr['attribute'] = 'GCXMSS-02';
            $esmprojectArr['peopleNumber'] = 1;
            $esmprojectArr['dlPeople'] = 1;
            $esmprojectArr['incomeType'] = $object['incomeType'];
            $esmprojectArr['projectObjectives'] = $object['projectObjectives'];
            $esmprojectArr['description'] = $object['description'];
            // ʵ����������Ŀ��
            $esmprojectDao = new model_engineering_project_esmproject();
            // ��ӹ�����Ŀ
            $projectId = $esmprojectDao->addProject_d($esmprojectArr, true);

            // �رչ���
            if ($closeDetail) {
                $esmCloseDetailDao = new model_engineering_close_esmclosedetail();
                $esmCloseDetailDao->addBatch_d($closeDetail, array('projectId' => $projectId));
            }

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}