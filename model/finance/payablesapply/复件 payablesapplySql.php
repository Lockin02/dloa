<?php
/**
 * @author Show
 * @Date 2011年5月8日 星期日 13:55:05
 * @version 1.0
 * @description:付款申请(新) sql配置文件
 */
$sql_arr = array (
	'select_default'=>"select c.id ,c.formNo ,c.formDate,c.isAdvPay,c.payDate ,c.supplierName 
  ,c.sourceType,c.payFor,if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney) 
  as payMoney ,if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney) as payedMoney 
  ,c.status ,c.deptName,c.salesman,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime 
  ,c.updateName ,c.updateTime,c.printCount,c.feeDeptName,c.feeDeptId  
  from oa_finance_payablesapply c 
  left join user u on (c.salesmanid=u.user_id)  where 1=1  and u.company='".$_SESSION['USER_COM']."'   ",
	'select_auditing' =>
		"select w.task,p.ID as id, u.USER_NAME as UserName, c.id as applyId,c.formNo,c.supplierName,c.formDate,
		c.payMoney,c.payType,
		c.createName,c.ExaStatus,c.payCondition,c.status,c.createTime
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_finance_payablesapply c
		where
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName, c.id as applyId,c.formNo,c.supplierName,c.formDate,
		c.payMoney,c.payType,
		c.createName,c.ExaStatus,c.payCondition,c.status,c.createTime
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_finance_payablesapply c where
		p.Flag='1' and
		w.Pid =c.id ",
	'sum_payedmoney' => "select c.payMoney , sum(p.amount) as thisPayedMoney from oa_finance_payablesapply c left join " .
			"oa_finance_payables p on c.id = p.payApplyId ",
	'select_history' => "select DISTINCT c.id ,c.formNo ,c.formDate  ,c.payFor,c.supplierName ,if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney) as payMoney ,if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney) as payedMoney ,c.status ,c.deptName,
		c.deptId,c.salesman,c.salesmanId,c.bank,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,c.updateName ,c.updateTime,sum(if(c.payFor = 'FKLX-03' ,-d.money,d.money)) as money,d.objCode,
		d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'sum_list' => "select c.id,if(sum(d.money) is null ,0,sum(d.money)) as payed " .
		"from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId",
	'sum_listAll' => "select c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money) ) as payed " .
		"from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId",
	'select_detail' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
		c.deptId,c.salesman,c.salesmanId,c.bank,d.money,d.objCode,
		d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'select_detailcount' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
		c.deptId,c.salesman,c.salesmanId,c.bank,sum(d.money) as money,d.objCode,c.sourceType,
		d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'select_detailcountNew' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
		c.deptId,c.salesman,c.salesmanId,c.bank,d.money as money,d.objCode,c.sourceType,
		d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName,d.expand1,d.expand2,d.expand3,d.productNo,d.productName,d.number,d.payDesc
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId "
);

$condition_arr = array (
    array(
   		"name" => "id",
   		"sql" => " and c.id=# "
    ),
    array(
   		"name" => "ids",
   		"sql" => " and c.id in(arr) "
    ),
    array(
   		"name" => "formNo",
   		"sql" => " and c.formNo=# "
    ),
    array(
   		"name" => "formNoSearch",
   		"sql" => " and c.formNo  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
    ),
    array(
   		"name" => "payMoney",
   		"sql" => " and c.payMoney =# "
    ),
    array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
    ),
    array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
    ),
    array(
   		"name" => "deptIds",
   		"sql" => " and c.deptId in(arr) "
    ),
    array(
   		"name" => "salesmanId",
   		"sql" => " and c.salesmanId=# "
    ),
    array(
   		"name" => "salesmanSearch",
   		"sql" => " and c.salesman like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "deptNameSearch",
   		"sql" => " and c.deptName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "feeDeptNameSearch",
   		"sql" => " and c.feeDeptName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "payFor",
   		"sql" => " and c.payFor=# "
    ),
    array(
   		"name" => "noPayFor",
   		"sql" => " and c.payFor <> # "
    ),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
    array(
   		"name" => "noStatus",
   		"sql" => " and c.status <> # "
    ),
    array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
    ),
    array(
   		"name" => "noExaStatus",
   		"sql" => " and c.ExaStatus <> # "
    ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//流程名称
		"sql"=>" and w.name in (#) "
	),
	array(
		"name" => "workFlowCode",//流程名称
		"sql"=>" and w.code = # "
	),
	array(
		"name" => "dObjId",//从表对象编号
		"sql"=>" and d.objId = # "
	),
	array(
		"name" => "dObjType",//从表对象编号
		"sql"=>" and d.objType= # "
	),
	array(
		"name" => "dProductId",//从表对象编号
		"sql"=>" and d.ProductId= # "
	),
    array(
        "name" => "dExpand1",//从表扩展字段1
        "sql"=>" and d.expand1= # "
    ),
	array(//关联编号
		"name"=>"objCodeSearch",
		"sql" => " and  c.id in(select i.payapplyId from oa_finance_payablesapply_detail i where i.objCode like CONCAT('%',#,'%')) "
	),
	array(
		"name" => "sourceTypePurchase",
   		"sql" => " and (c.sourceType = 'YFRK-01' or c.sourceType = 'YFRK-04' or sourceType is null) "
	),
	array(
		"name" => "sourceType",
   		"sql" => " and c.sourceType = #"
	)
)
?>