<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:11:22
 * @version 1.0
 * @description:供应商项目信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.projectCode ,c.projectName ,c.projectId ,c.outContractCode ,c.outContractId ,c.outContractName ,c.projectType ,c.projectTypeCode ,c.outsourcing ,c.outsourcingName ,c.personNum ,c.beginDate ,c.endDate ,c.totalMoney ,c.checkScore ,c.deductReason ,c.evaluate  from oa_outsourcesupp_project c where 1=1 ",
         "select_project"=>"select c.id, c.projectId, c.projectCode, c.projectName, c.signCompanyId, c.signCompanyName, c.outContractCode, p.natureName, c.outsourcing, c.outsourcingName, '' as personNum, c.beginDate, c.endDate, c.orderMoney, '' as checkScore, '' as deductReason, '' as evaluate from oa_sale_outsourcing c left join oa_esm_project p on c.projectId = p.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "outContractCode",
   		"sql" => " and c.outContractCode=# "
   	  ),
   array(
   		"name" => "outContractId",
   		"sql" => " and c.outContractId=# "
   	  ),
   array(
   		"name" => "outContractName",
   		"sql" => " and c.outContractName=# "
   	  ),
   array(
   		"name" => "projectType",
   		"sql" => " and c.projectType=# "
   	  ),
   array(
   		"name" => "projectTypeCode",
   		"sql" => " and c.projectTypeCode=# "
   	  ),
   array(
   		"name" => "outsourcing",
   		"sql" => " and c.outsourcing=# "
   	  ),
   array(
   		"name" => "outsourcingName",
   		"sql" => " and c.outsourcingName=# "
   	  ),
   array(
   		"name" => "personNum",
   		"sql" => " and c.personNum=# "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
   	  ),
   array(
   		"name" => "totalMoney",
   		"sql" => " and c.totalMoney=# "
   	  ),
   array(
   		"name" => "checkScore",
   		"sql" => " and c.checkScore=# "
   	  ),
   array(
   		"name" => "deductReason",
   		"sql" => " and c.deductReason=# "
   	  ),
   array(
   		"name" => "evaluate",
   		"sql" => " and c.evaluate=# "
   	  ),
   array(
   		"name" => "signCompanyId",
   		"sql" => " and c.signCompanyId=# "
   	  )
)
?>