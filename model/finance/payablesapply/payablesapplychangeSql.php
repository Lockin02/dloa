<?php
/**
 * @author tse
 * @Date 2014年3月14日 10:43:27
 * @version 1.0
 * @description:审批付款时间变更表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.purOrderId ,c.perAuditDate ,c.newAuditDate ,c.supplierId ,c.supplierName ,c.payMoney ,c.changeReason, c.ExaStatus, c.ExaDT,c.deptName,c.deptId,c.salesman,c.salesmanId,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.createId,c.createName,c.createTime  from oa_finance_payablesapply_changeaudit c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "purOrderId",
   		"sql" => " and c.purOrderId=# "
   	  ),
   array(
   		"name" => "perAuditDate",
   		"sql" => " and c.perAuditDate=# "
   	  ),
   array(
   		"name" => "newAuditDate",
   		"sql" => " and c.newAuditDate=# "
   	  ),
   array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName=# "
   	  ),
   array(
   		"name" => "payMoney",
   		"sql" => " and c.payMoney=# "
   	  ),
   array(
   		"name" => "changeReason",
   		"sql" => " and c.changeReason=# "
   	  ),
   array(
   		"name" => "salesmanId",
   		"sql" => " and c.salesmanId=# "
   	  )
)
?>