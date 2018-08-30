<?php
/**
 * @author Show
 * @Date 2013��5��23�� ������ 10:54:51
 * @version 1.0
 * @description:�ʼ챨���嵥 Model��
 */
class model_produce_quality_qualityereportequitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_ereportequitem";
		$this->sql_map = "produce/quality/qualityereportequitemSql.php";
		parent :: __construct();
	}

    //�����ֵ�
    public $datadictFieldArr = array(
    	'priority'
    );

    //��д����
    function add_d($object){
    	$object = $this->processDatadict($object);
		return parent::add_d($object,true);
    }

    //��д�༭
    function edit_d($object){
    	$object = $this->processDatadict($object);
		return parent::edit_d($object,true);
    }
    /**
     * �����ʼ�ʱ��
     *
     */
    function updateComDate($id){
    	return $this->update(array('id'=>$id),array('completionTime'=>date('Y-m-d H:i:s')));
    }

 	/**
     * �����ʼ�ʱ��
     * ����mainid����
     */
    function updateComDateFromMainId($mainId){
    	return $this->update(array('mainId'=>$mainId),array('completionTime'=>date('Y-m-d H:i:s')));
    }

    /**
     * ����Դ������ID��Դ�����ͣ���ȡ�����ʼ�ϸ���
     *
     */
    function getQualifiedRate_d($objItemId,$objType){
        $rateRow=array();
        $this->searchArr = array("objType" => $objType,
            "objItemId" => $objItemId,"ExaStatus"=>'���');
        $this->groupBy='c.objItemId,c.objType';
        $this->sort='c.objItemId';
        $row = $this->listBySqlId("select_qualitedRate");
        return $row;
    }
    /**
     * �����ʼ���������ID��Դ�����ͣ���ȡ�����ʼ�ϸ���
     *
     */
    function getTaskQualifiedRate_d($relItemId){
        $rateRow=array();
        $this->searchArr = array("relItemId" => $relItemId,"ExaStatus"=>'���');
        $this->groupBy='c.relItemId';
        $this->sort='c.relItemId';
        $rateRow = $this->listBySqlId("select_qualitedRate");
        return $rateRow;
    }
}