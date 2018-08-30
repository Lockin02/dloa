<?php
/**
 * @author Administrator
 * @Date 2012-10-25 15:02:55
 * @version 1.0
 * @description:设备基本信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.budgetTypeId ,c.budgetTypeName ,c.equCode ,c.equName ,c.useEndDate ,c.unitName ,c.version ,c.useStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.pattern ,c.color ,c.brand ,c.quotedPrice  from oa_equ_budget_baseinfo c where 1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
        "name" => "useEndDate",
        "sql" => " and c.useEndDate=#"
   ),
   array(
   		"name" => "budgetTypeId",
   		"sql" => " and c.budgetTypeId=# "
   	  ),
   array(
   		"name" => "budgetTypeName",
   		"sql" => " and c.budgetTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "equCode",
   		"sql" => " and c.equCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "equName",
   		"sql" => " and c.equName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
   		"name" => "useStatus",
   		"sql" => " and c.useStatus=# "
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