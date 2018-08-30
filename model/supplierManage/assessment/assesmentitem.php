<?php
/**
 * @author Administrator
 * @Date 2012年1月11日 16:58:43
 * @version 1.0
 * @description:供应商评估明细 Model层 
 */
 class model_supplierManage_assessment_assesmentitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses_detail";
		$this->sql_map = "supplierManage/assessment/assesmentitemSql.php";
		parent::__construct ();
	}

     /**
      * 根据评估ID获取评估明细
      */
     function getItemByParentId_d($parentId)
     {
         $conditions = array(
             "parentId" => $parentId
         );
         return parent :: findAll($conditions);
     }

     /**
      * 根据评估ID 计算评估总分
      */
     function getSumscoreByParentId_d($parentId)
     {

         $this->searchArr = array("parentId" => $parentId);
         $this->groupBy="c.parentId";
         $row=$this->list_d("select_sumscore");
         return $row[0]['sumScore'];
     }


 }
?>