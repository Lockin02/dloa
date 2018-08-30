<?php
/**
 * @author dloa
 * @Date 2015年1月20日 14:09:51
 * @version 1.0
 * @description:合同操作记录表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractId ,c.handleNum ,c.stepName ,c.stepCode ,c.handleId ,c.handleName ,c.handleDate ,c.isChange,c.stepInfo,c.remark  from oa_contract_handle c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "handleNum",
   		"sql" => " and c.handleNum=# "
   	  ),
   array(
   		"name" => "stepName",
   		"sql" => " and c.stepName=# "
   	  ),
   array(
   		"name" => "stepCode",
   		"sql" => " and c.stepCode=# "
   	  ),
   array(
   		"name" => "handleId",
   		"sql" => " and c.handleId=# "
   	  ),
   array(
   		"name" => "handleName",
   		"sql" => " and c.handleName=# "
   	  ),
   array(
   		"name" => "handleDate",
   		"sql" => " and c.handleDate=# "
   	  ),
   array(
        "name" => "stepInfo",
        "sql" => " and c.stepInfo=#"
   ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=#"
    )
)
?>