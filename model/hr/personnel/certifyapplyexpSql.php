<?php
/**
 * @author Show
 * @Date 2012��8��22�� ������ 17:25:00
 * @version 1.0
 * @description:��ְ�ʸ�-��רҵ������ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.applyId ,c.beginYear ,c.beginMonth ,c.endYear ,c.endMonth ,c.unitName ,c.deptName ,c.mainWork  from oa_hr_personnel_certifyapplyexp c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "beginYear",
   		"sql" => " and c.beginYear=# "
   	  ),
   array(
   		"name" => "beginMonth",
   		"sql" => " and c.beginMonth=# "
   	  ),
   array(
   		"name" => "endYear",
   		"sql" => " and c.endYear=# "
   	  ),
   array(
   		"name" => "endMonth",
   		"sql" => " and c.endMonth=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "mainWork",
   		"sql" => " and c.mainWork=# "
   	  )
)
?>