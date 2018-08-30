<?php
/**
 * @author huangzf
 * @Date 2012年6月1日 星期五 11:27:45
 * @version 1.0
 * @description:产品物料库存采购销售综合表基本信息 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.reportName ,c.activeYear ,c.periodSeNum ,c.periodType ,c.isActive,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_extra_procompositebase c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "reportName",
   		"sql" => " and c.reportName like CONCAT('%',# ,'%')"
   	  ),
   array(
   		"name" => "activeYear",
   		"sql" => " and c.activeYear=# "
   	  ),
   array(
   		"name" => "periodSeNum",
   		"sql" => " and c.periodSeNum=# "
   	  ),
   array(
   		"name" => "periodType",
   		"sql" => " and c.periodType=# "
   	  ),
  array(
   		"name" => "isActive",
   		"sql" => " and c.isActive=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>