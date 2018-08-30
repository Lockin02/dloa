<?php
/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.formCode ,c.formDate ,c.formStatus ,c.formMoney ,c.objId ,
			c.objCode ,c.objType ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime ,c.dutyType ,c.dutyTypeName ,c.dutyObjName ,
			c.dutyObjId ,c.confirmName ,c.confirmId ,c.confirmTime ,
			c.auditorName ,c.auditorId ,c.auditTime ,c.compensateMoney,c.comConfirmId,c.comConfirmName,
			c.comConfirmTime,c.deptId,c.deptName,c.chargerId,c.chargerName,c.closerId,c.closerName,c.closeTime,
			c.relDocId,c.relDocType,c.relDocCode,d.deductMoney
		from oa_finance_compensate c 
		left join  
		(select compensateId,sum(deductMoney) as deductMoney from oa_finance_compensate_deduct group by compensateId) d on d.compensateId = c.id
		where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "formCode",
		"sql" => " and c.formCode=# "
	),
	array (
		"name" => "formCodeSearch",
		"sql" => " and c.formCode like concat('%',#,'%')"
	),
	array (
		"name" => "formDate",
		"sql" => " and c.formDate=# "
	),
	array (
		"name" => "formStatus",
		"sql" => " and c.formStatus=# "
	),
	array (
		"name" => "formStatusArr",
		"sql" => " and c.formStatus in(arr) "
	),
	array (
		"name" => "formMoney",
		"sql" => " and c.formMoney=# "
	),
	array (
		"name" => "objId",
		"sql" => " and c.objId=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objCodeSearch",
		"sql" => " and c.objCode like concat('%',#,'%')"
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
	array (
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array (
		"name" => "relDocCode",
		"sql" => " and c.relDocCode=# "
	),
	array (
		"name" => "relDocCodeSearch",
		"sql" => " and c.relDocCode like concat('%',#,'%')"
	),
	array (
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "dutyType",
		"sql" => " and c.dutyType=# "
	),
	array (
		"name" => "dutyTypeName",
		"sql" => " and c.dutyTypeName=# "
	),
	array (
		"name" => "dutyObjName",
		"sql" => " and c.dutyObjName=# "
	),
	array (
		"name" => "dutyObjId",
		"sql" => " and c.dutyObjId=# "
	),
	array (
		"name" => "confirmName",
		"sql" => " and c.confirmName=# "
	),
	array (
		"name" => "confirmId",
		"sql" => " and c.confirmId=# "
	),
	array (
		"name" => "confirmTime",
		"sql" => " and c.confirmTime=# "
	),
	array (
		"name" => "auditorName",
		"sql" => " and c.auditorName=# "
	),
	array (
		"name" => "auditorId",
		"sql" => " and c.auditorId=# "
	),
	array (
		"name" => "auditTime",
		"sql" => " and c.auditTime=# "
	),
    array (
        "name" => "chargerNameSearch",
        "sql" => " and c.chargerName like concat('%',#,'%')"
    )
);