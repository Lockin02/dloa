<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:20
 * @version 1.0
 * @description:设备预算表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select b.useEndDate,c.id ,c.budgetCode,c.budgetTypeId ,c.budgetTypeName ,c.equId ,c.equCode ,c.equName ,c.equRemark ,c.allMoney ,c.status ,c.createName ,c.createId ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_equ_budget c " .
         		"left join oa_equ_budget_baseinfo b on c.equId=b.id" .
         		"  where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
   		"name" => "budgetCode",
   		"sql" => " and c.budgetCode like CONCAT('%',#,'%') "
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
   		"name" => "equId",
   		"sql" => " and c.equId=# "
   	  ),
   array(
   		"name" => "equCode",
   		"sql" => " and c.equCode=# "
   	  ),
   array(
   		"name" => "equName",
   		"sql" => " and c.equName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "equRemark",
   		"sql" => " and c.equRemark=# "
   	  ),
   array(
   		"name" => "allMoney",
   		"sql" => " and c.allMoney=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
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