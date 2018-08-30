<?php
$sql_arr = array (
	"default" => "select c.id,c.name,c.code,c.start,c.Creator from
		(select c.task as id,c.Electric,c.Creator,c.Enter_user,c.name,c.code,c.infomation,c.train,c.form,
		c.secrecy,c.speed,c.sendread,c.seqn,c.Archiving,c.examines,c.`Status`,c.start,c.finish,
		c.ATTACHMENT_NAME,c.ATTACHMENT_ID,c.ATTACHMENT_COMMENT,c.Pid,c.Prcsalert,c.PassSqlCode,
		c.DisPassSqlCode,c.UpdateDT from wf_task c ) c ",
	"select_auditing" => "select p.ID as spId,c.task as taskId,c.start AS flowTime,c.infomation as objMsg,c.name as formName,c.code,c.Pid as billId,
		if(u.has_left='1', concat(u.USER_NAME,'（离职）') , u.USER_NAME ) as userName,h.mobile AS phoneNumber,'1' as mbType 
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id 
		left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
			where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
			group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
    	left join user u on c.Creator = u.USER_ID
			LEFT JOIN oa_hr_personnel  h ON h.userAccount=u.USER_ID
    	where  p.Flag = 0 AND c.examines = ''
		and c.pid is not null  and c.pid<>''  and p.id is not null and p.id<>'' and c.task is not null and c.task<>'' 
		AND c.`name` NOT IN ('赔偿单审批','借试用赔偿单确认','采购询价单审批','离职申请审批','试用考核评估','调岗申请审批','工程项目周报审批')",
	"select_audited" => "select p.ID as spId,c.task as taskId,c.start AS flowTime,c.infomation as objMsg,c.name as formName,c.code,c.Pid as billId,
		u.USER_NAME  as userName,p.result,p.content,p.endTime,'1' as mbType
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id 
    left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and 1!=1  
group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
    left join user u on c.Creator = u.USER_ID 
	where	p.Flag = 1  and c.pid is not null  and c.pid<>''  and p.id is not null and p.id<>'' and c.task is not null and c.task<>''  "
);

$condition_arr = array (
	array (
		"name" => "inCode",
		"sql" => "and c.code in(arr)"
	),
	array (
		"name" => "code",
		"sql" => "and c.code = #"
	),
	array (
		"name" => "inNames",
		"sql" => "and c.name in(arr)"
	),
	array (
		"name" => "notInNames",
		"sql" => "and c.name not in(arr)"
	),
	array(
		"name" => "findFormName",//查询表单类型用
		"sql"=>" and  ( find_in_set( # , c.name ) > 0 ) "
	),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( ( find_in_set( # , p.User ) > 0 ) or ( find_in_set( # , powi.to_ids  ) > 0 )) "
	),
	array(
		"name" => "taskSearch",//审批单号查询
		"sql"=>" and c.task like concat('%',#,'%') "
	),
	array(
		"name" => "creatorNameSearch",//提交人查询
		"sql"=>" and u.USER_NAME like concat('%',#,'%') "
	),
	array(
		"name" => "startSearch",//提交时间
		"sql"=>" and c.start like concat('%',#,'%') "
	),
	array(
		"name" => "endTimeSearch",//审批时间
		"sql"=>" and p.Endtime like concat('%',#,'%') "
	),
	array(
		"name" => "objCodeSearch",//业务编号
		"sql"=>" and c.objCode like concat('%',#,'%') "
	),
	array(
		"name" => "objNameSearch",//业务名称
		"sql"=>" and c.objName like concat('%',#,'%') "
	),
	array(
		"name" => "objCustomerSearch",//业务客户
		"sql"=>" and c.objCustomer like concat('%',#,'%') "
	),
	array(
		"name" => "objAmountSearch",//业务金额
		"sql"=>" and c.objAmount like concat('%',#,'%') "
	),
	array(
		"name" => "infomationSearch",//扩展信息
		"sql"=>" and c.infomation like concat('%',#,'%') "
	),
	array(
		"name" => "formName",//提交时间
		"sql"=>" and c.name = # "
	),
	array(
		"name" => "formNames",//提交时间
		"sql"=>" and c.name in(arr) "
	),
	array(
		"name" => "saleNotChange",//销售合同非变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_order_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "saleIsChange",//销售合同变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_order_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "serviceNotChange",//服务合同非变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_service_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "serviceIsChange",//服务合同变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_service_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "rentalNotChange",//租赁合同非变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_lease_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "rentalIsChange",//租赁合同变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_lease_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "rdprojectNotChange",//研发合同非变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_rdproject_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "rdprojectIsChange",//研发合同变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_sale_rdproject_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "purchasecontractNotChange",//采购订单非变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_purchase_contract_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "purchasecontractIsChange",//采购订单变更申请审批
		"sql" => " and (select count(ch.tempId) from oa_purchase_contract_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "startTime",//提交时间
		"sql"=>" and c.start >from_unixtime(#) "
	),
	array(
		"name" => "endTime",//提交时间
		"sql"=>" and p.endTime <from_unixtime(#) "
	),
	array(
		"name" => "endTimes",//提交时间
		"sql"=>" and p.endTime >from_unixtime(#) "
	)
);
?>
