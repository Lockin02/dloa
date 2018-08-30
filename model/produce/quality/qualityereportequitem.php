<?php
/**
 * @author Show
 * @Date 2013年5月23日 星期四 10:54:51
 * @version 1.0
 * @description:质检报告清单 Model层
 */
class model_produce_quality_qualityereportequitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_ereportequitem";
		$this->sql_map = "produce/quality/qualityereportequitemSql.php";
		parent :: __construct();
	}

    //数据字典
    public $datadictFieldArr = array(
    	'priority'
    );

    //重写新增
    function add_d($object){
    	$object = $this->processDatadict($object);
		return parent::add_d($object,true);
    }

    //重写编辑
    function edit_d($object){
    	$object = $this->processDatadict($object);
		return parent::edit_d($object,true);
    }
    /**
     * 更新质检时间
     *
     */
    function updateComDate($id){
    	return $this->update(array('id'=>$id),array('completionTime'=>date('Y-m-d H:i:s')));
    }

 	/**
     * 更新质检时间
     * 根据mainid更新
     */
    function updateComDateFromMainId($mainId){
    	return $this->update(array('mainId'=>$mainId),array('completionTime'=>date('Y-m-d H:i:s')));
    }

    /**
     * 根据源单物料ID及源单类型，获取物料质检合格率
     *
     */
    function getQualifiedRate_d($objItemId,$objType){
        $rateRow=array();
        $this->searchArr = array("objType" => $objType,
            "objItemId" => $objItemId,"ExaStatus"=>'完成');
        $this->groupBy='c.objItemId,c.objType';
        $this->sort='c.objItemId';
        $row = $this->listBySqlId("select_qualitedRate");
        return $row;
    }
    /**
     * 根据质检任务物料ID及源单类型，获取物料质检合格率
     *
     */
    function getTaskQualifiedRate_d($relItemId){
        $rateRow=array();
        $this->searchArr = array("relItemId" => $relItemId,"ExaStatus"=>'完成');
        $this->groupBy='c.relItemId';
        $this->sort='c.relItemId';
        $rateRow = $this->listBySqlId("select_qualitedRate");
        return $rateRow;
    }
}