<?php
/**
 * @author Administrator
 * @Date 2011年11月22日 17:08:26
 * @version 1.0
 * @description:借试用物料金额设置 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.borrowType ,c.areaCode ,c.areaName ,c.deptName ,c.deptId ,c.userId ,c.userName ,c.roleId ,c.roleName ,c.maxMoney,c.deptuserMoney,c.controlType,c.deptuserMoney  from oa_borrow_money_config c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "borrowType",
   		"sql" => " and c.borrowType=# "
   	  ),
   array(
   		"name" => "areaCode",
   		"sql" => " and c.areaCode=# "
   	  ),
   array(
   		"name" => "areaName",
   		"sql" => " and c.areaName=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "roleId",
   		"sql" => " and c.roleId=# "
   	  ),
   array(
   		"name" => "roleName",
   		"sql" => " and c.roleName=# "
   	  ),
   array(
   		"name" => "maxMoney",
   		"sql" => " and c.maxMoney=# "
   	  ),
   array(
        "name" => "controlType",
        "sql" => "and c.controlType=#"
   )
)
?>