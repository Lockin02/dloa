<?php
/**
 * @author show
 * @Date 2014年4月29日 16:28:21
 * @version 1.0
 * @description:不合格物料明细 Model层
 */
class model_produce_quality_failureitem extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_produce_quality_ereportfailureitem";
        $this->sql_map = "produce/quality/failureitemSql.php";
        parent::__construct();
    }

    //数据字典处理
    public $datadictFieldArr = array(
        'result','level'
    );

    //新增
    function add_d($object){
        $object = $this->processDatadict($object);
        return parent::add_d($object);
    }

    //修改
    function edit_d($object){
        $object = $this->processDatadict($object);
        return parent::edit_d($object);
    }

    /**
     * 获取未赔偿的不合格物料清单
     */
    function getUnCompensateDetail_d($objId,$objType){
        $this->searchArr = array('objId' => $objId,'objType' => $objType,'isCompensated' => 0);
        $this->asc = false;
        return $this->list_d('for_compensate');
    }

    /**
     * 设置已赔偿
     */
    function setCompensated_d($id){
        return $this->update(array('id' => $id),array('isCompensated' => 1));
    }

    /**
     * 获取不合格的序列号
     */
    function getSerailNums_d($objItemId){
        return $this->get_table_fields($this->tbl_name,' objItemId = "'.$objItemId .'"','GROUP_CONCAT(serialNo)');
    }
}