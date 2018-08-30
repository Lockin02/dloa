<?php
/**
 * @author Administrator
 * @Date 2014年2月25日 14:22:58
 * @version 1.0
 * @description:合同成本概算信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.costRemark,c.costAppRemark,c.id ,c.issale,c.contractId ,c.productLine ,c.productLineName ,c.confirmName ,c.confirmId ,c.confirmDate ,c.state ,c.ExaState ,c.confirmMoney ,c.xfProductLine" .
         		" ,ce.contractCode,ce.customerName,ce.customerType,ce.contractName" .
         		" from oa_contract_cost c " .
         		" left join oa_contract_contract ce on c.contractId = ce.id" .
         		" where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractName",
   		"sql" => " and ce.contractName like CONCAT('%',#,'%') "
        ),
   array(
   		"name" => "customerName",
   		"sql" => " and ce.customerName like CONCAT('%',#,'%') "
        ),
   array(
   		"name" => "contractCode",
   		"sql" => " and ce.contractCode like CONCAT('%',#,'%') "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
   	  ),
   array(
   		"name" => "productLineArr",
   		"sql" => " and c.productLine in(arr) "
   	  ),
   array(
   		"name" => "productLineName",
   		"sql" => " and c.productLineName=# "
   	  ),
   array(
   		"name" => "confirmName",
   		"sql" => " and c.confirmName=# "
   	  ),
   array(
   		"name" => "confirmId",
   		"sql" => " and c.confirmId=# "
   	  ),
   array(
   		"name" => "confirmDate",
   		"sql" => " and c.confirmDate=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "ExaState",
   		"sql" => " and c.ExaState=# "
   	  ),
   array(
   		"name" => "confirmMoney",
   		"sql" => " and c.confirmMoney=# "
   	  ),
   array(
   		"name" => "xfProductLine",
   		"sql" => " and c.xfProductLine=# "
   	  )
)
?>