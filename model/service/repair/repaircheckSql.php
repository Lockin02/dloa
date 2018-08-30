<?php
/**
 * @author huangzf
 * @Date 2011年12月2日 10:22:13
 * @version 1.0
 * @description:检测维修任务 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.applyItemId ,c.docCode ,c.issuedUserCode ,c.issuedUserName ,c.issuedTime ,c.applyDocId ,c.applyDocCode ,c.repairDeptCode ,c.repairDeptName ,c.repairUserCode ,c.repairUserName ,c.productType ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.serilnoName ,c.fittings ,c.troubleType ,c.troubleInfo ,c.checkInfo ,c.isAgree ,c.finishTime ,c.docStatus ,c.remark ,c.isByHuman ,
			a.customerName ,a.contactUserName ,a.telephone ,a.prov ,ai.isGurantee from oa_service_repair_check c left join oa_service_repair_apply a on c.applyDocId = a.id left join oa_service_repair_applyitem ai on c.applyItemId = ai.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "applyItemId",
   		"sql" => " and c.applyItemId=# "
   	  ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "issuedUserCode",
   		"sql" => " and c.issuedUserCode=# "
   	  ),
   array(
   		"name" => "issuedUserName",
   		"sql" => " and c.issuedUserName=# "
   	  ),
   array(
   		"name" => "issuedTime",
   		"sql" => " and c.issuedTime=# "
   	  ),
   array(
   		"name" => "applyDocId",
   		"sql" => " and c.applyDocId=# "
   	  ),
   array(
   		"name" => "applyDocCode",
   		"sql" => " and c.applyDocCode=# "
   	  ),
   array(
   		"name" => "repairDeptCode",
   		"sql" => " and c.repairDeptCode=# "
   	  ),
   array(
   		"name" => "repairDeptName",
   		"sql" => " and c.repairDeptName=# "
   	  ),
   array(
   		"name" => "repairUserCode",
   		"sql" => " and c.repairUserCode=# "
   	  ),
   array(
   		"name" => "repairUserName",
   		"sql" => " and c.repairUserName=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
		"name" => "serilnoName",
		"sql" => " and c.serilnoName=# "
      ),
   array(
   		"name" => "serilnoNameSer",
   		"sql" => " and c.serilnoName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "fittings",
   		"sql" => " and c.fittings=# "
   	  ),
   array(
   		"name" => "troubleInfo",
   		"sql" => " and c.troubleInfo=# "
   	  ),
   array(
   		"name" => "checkInfo",
   		"sql" => " and c.checkInfo=# "
   	  ),
   array(
   		"name" => "isAgree",
   		"sql" => " and c.isAgree=# "
   	  ),
   array(
   		"name" => "finishTime",
   		"sql" => " and c.finishTime=# "
   	  ),
   array(
   		"name" => "docStatus",
   		"sql" => " and c.docStatus=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
	array(
		"name" => "isByHuman",
		"sql" => " and c.isByHuman=# "
	),
	array(
		"name" => "troubleType",
		"sql" => " and c.troubleType like CONCAT('%',#,'%') "
	),
	//关联表搜索
	array(
		"name" => "prov",
		"sql" => " and a.prov like CONCAT('%',#,'%') "
	),
	array(
		"name" => "customerName",
		"sql" => " and a.customerName like CONCAT('%',#,'%') "
	)
)
?>