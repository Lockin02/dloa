<?php
/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:13:27
 * @version 1.0
 * @description:申请明细表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.HeadID ,c.RNo ,c.Place ,c.Note ,c.CostDateBegin ,c.CostDateEnd ,c.BillNo ,c.Status ,c.ID ,c.UpdateDT ,c.Updator ,c.Creator ,c.CreateDT  from cost_detail_assistant c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "HeadID",
   		"sql" => " and c.HeadID=# "
   	  ),
   array(
   		"name" => "RNo",
   		"sql" => " and c.RNo=# "
   	  ),
   array(
   		"name" => "Place",
   		"sql" => " and c.Place=# "
   	  ),
   array(
   		"name" => "Note",
   		"sql" => " and c.Note=# "
   	  ),
   array(
   		"name" => "CostDateBegin",
   		"sql" => " and c.CostDateBegin=# "
   	  ),
   array(
   		"name" => "CostDateEnd",
   		"sql" => " and c.CostDateEnd=# "
   	  ),
   array(
   		"name" => "BillNo",
   		"sql" => " and c.BillNo=# "
   	  ),
   array(
   		"name" => "Status",
   		"sql" => " and c.Status=# "
   	  ),
   array(
   		"name" => "ID",
   		"sql" => " and c.ID=# "
   	  ),
   array(
   		"name" => "UpdateDT",
   		"sql" => " and c.UpdateDT=# "
   	  ),
   array(
   		"name" => "Updator",
   		"sql" => " and c.Updator=# "
   	  ),
   array(
   		"name" => "Creator",
   		"sql" => " and c.Creator=# "
   	  ),
   array(
   		"name" => "CreateDT",
   		"sql" => " and c.CreateDT=# "
   	  )
)
?>