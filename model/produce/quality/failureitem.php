<?php
/**
 * @author show
 * @Date 2014��4��29�� 16:28:21
 * @version 1.0
 * @description:���ϸ�������ϸ Model��
 */
class model_produce_quality_failureitem extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_produce_quality_ereportfailureitem";
        $this->sql_map = "produce/quality/failureitemSql.php";
        parent::__construct();
    }

    //�����ֵ䴦��
    public $datadictFieldArr = array(
        'result','level'
    );

    //����
    function add_d($object){
        $object = $this->processDatadict($object);
        return parent::add_d($object);
    }

    //�޸�
    function edit_d($object){
        $object = $this->processDatadict($object);
        return parent::edit_d($object);
    }

    /**
     * ��ȡδ�⳥�Ĳ��ϸ������嵥
     */
    function getUnCompensateDetail_d($objId,$objType){
        $this->searchArr = array('objId' => $objId,'objType' => $objType,'isCompensated' => 0);
        $this->asc = false;
        return $this->list_d('for_compensate');
    }

    /**
     * �������⳥
     */
    function setCompensated_d($id){
        return $this->update(array('id' => $id),array('isCompensated' => 1));
    }

    /**
     * ��ȡ���ϸ�����к�
     */
    function getSerailNums_d($objItemId){
        return $this->get_table_fields($this->tbl_name,' objItemId = "'.$objItemId .'"','GROUP_CONCAT(serialNo)');
    }
}