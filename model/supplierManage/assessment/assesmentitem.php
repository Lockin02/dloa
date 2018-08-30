<?php
/**
 * @author Administrator
 * @Date 2012��1��11�� 16:58:43
 * @version 1.0
 * @description:��Ӧ��������ϸ Model�� 
 */
 class model_supplierManage_assessment_assesmentitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses_detail";
		$this->sql_map = "supplierManage/assessment/assesmentitemSql.php";
		parent::__construct ();
	}

     /**
      * ��������ID��ȡ������ϸ
      */
     function getItemByParentId_d($parentId)
     {
         $conditions = array(
             "parentId" => $parentId
         );
         return parent :: findAll($conditions);
     }

     /**
      * ��������ID ���������ܷ�
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