<?php

/**
 * @author Show
 * @Date 2011年11月26日 星期六 17:00:10
 * @version 1.0
 * @description:项目章程(oa_esm_charter) Model层
 */
class model_engineering_charter_esmcharter extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_charter";
        $this->sql_map = "engineering/charter/esmcharterSql.php";
        parent::__construct();
    }

    // 数据字典字段处理
    public $datadictFieldArr = array('productLine', 'category', 'newProLine', 'incomeType');

    /**
     * 获取项目章程所需信息
     * @param $object
     * @return bool|mixed
     */
    function getObjInfo_d($object)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        $contractArr = $esmprojectDao->getProjectForCharter_d($object);
        //默认大区经理
        $contractArr['areaManagerId'] = '';
        $contractArr['areaManager'] = '';
        return $contractArr;
    }

    /**
     * 重写add_d方法，添加对象
     * 章程下达时对项目的新增已付相关信息初始化
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
            // 数据字典
            $object = $this->processDatadict($object);
            // 新增章程
            $newId = parent::add_d($object, true);

            // 添加预计工期处理
            $expectedDuration = round((strtotime($object['planEndDate']) - strtotime($object['planBeginDate'])) / 86400) + 1;

            // 创建工程项目数组
            $esmprojectArr = $object;
            $esmprojectArr['charterId'] = $newId;
            $esmprojectArr['expectedDuration'] = $expectedDuration;
            $esmprojectArr['attribute'] = 'GCXMSS-02';
            $esmprojectArr['peopleNumber'] = 1;
            $esmprojectArr['dlPeople'] = 1;
            $esmprojectArr['incomeType'] = $object['incomeType'];
            $esmprojectArr['projectObjectives'] = $object['projectObjectives'];
            $esmprojectArr['description'] = $object['description'];
            // 实例化工程项目类
            $esmprojectDao = new model_engineering_project_esmproject();
            // 添加工程项目
            $projectId = $esmprojectDao->addProject_d($esmprojectArr, true);

            // 关闭规则
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