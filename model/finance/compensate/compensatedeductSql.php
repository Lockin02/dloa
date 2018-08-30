<?php
/**
 * @author show
 * @Date 2014年12月31日 
 * @version 1.0
 * @description:赔偿单扣款记录sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.compensateId ,c.compensateCode ,c.dutyObjId ,c.dutyObjName ,c.dutyType ,c.dutyTypeName ,
			c.payType ,c.payTypeName ,c.deductMoney ,c.operateId ,c.operateName ,c.operateTime ,c.remark
		from oa_finance_compensate_deduct c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "compensateId",
		"sql" => " and c.compensateId=# "
	),
	array (
		"name" => "compensateCode",
		"sql" => " and c.compensateCode like concat('%',#,'%') "
	),
	array (
		"name" => "dutyObjId",
		"sql" => " and c.dutyObjId=# "
	),
	array (
		"name" => "dutyObjName",
		"sql" => " and c.dutyObjName like concat('%',#,'%')"
	),
	array (
		"name" => "dutyType",
		"sql" => " and c.dutyType=# "
	),
	array (
		"name" => "dutyTypeName",
		"sql" => " and c.dutyTypeName like concat('%',#,'%')"
	),
	array (
		"name" => "payType",
		"sql" => " and c.payType=# "
	),
	array (
		"name" => "payTypeName",
		"sql" => " and c.payTypeName like concat('%',#,'%')"
	),
	array (
		"name" => "deductMoney",
		"sql" => " and c.deductMoney=# "
	),
	array (
		"name" => "operateId",
		"sql" => " and c.operateId=# "
	),
	array (
		"name" => "operateName",
		"sql" => " and c.operateName=# "
	),
	array (
		"name" => "operateTime",
		"sql" => " and c.operateTime=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>