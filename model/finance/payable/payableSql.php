<?php
$sql_arr = array (
	"select_payable" => "select
		c.id,c.applyNumb,c.suppName,c.createName,
		e.applyMoney,
		ifnull(i.invoiceMoney,0) as invoiceMoney,
		ifnull(sum(p.payMoney),0) as payMoney,
		round(ifnull(sum(p.payMoney),0)/e.applyMoney,3)*100 as perMoney ,
		ifnull(ifnull(i.invoiceMoney,0)-ifnull(sum(p.payMoney),0),0) as remainMoney
		from
		oa_purch_apply_basic c
		left join (select basicNumb,sum(applyPrice*amountAll)  as applyMoney from oa_purch_apply_equ group by basicNumb) as e  on c.applyNumb = e.basicNumb
		left join oa_finance_payment p on p.purchApplyId=c.id
		left join (select purcontCode,sum(amount)  as invoiceMoney from oa_finance_invpurchase group by purcontCode) as i on c.applyNumb=i.purcontCode",
	'detail' => "select c.id ,c.objCode ,c.objNo ,c.payDate ,c.supplierName ,c.createName ,c.createTime,c.status," .
		"c.belongId,c.departments,c.salesman,c.formType,c.formDate,c.payStatus,c.amount " .
		"from oa_finance_invpurchase c where 1=1",
	//获取应付帐明细
	'detail_union' => "select a.* from (
		select " .
		"c.id as objId,c.objCode as objCode,c.supplierName ,c.supplierId as supplierId,c.formDate as formDate,c.departments as deptName ,c.departmentsId as deptId ,c.salesman as salesman,c.salesmanId as salesmanId,c.formType as formType,c.createName ,c.createTime, " .
		"if(c.formType = 'blue' , c.amount ,-c.amount ) as amount , null as needPay ,concat(c.formType,c.id) as id
		from oa_finance_invpurchase c where c.status <> 'CGFPZT-WSH'
		union
		select " .
		"b.id  as objId,b.formNo as objCode,b.supplierName ,b.supplierId as supplierId ,b.formDate as formDate  ,b.deptName as deptName ,b.deptId as deptId,b.salesman as salesman,b.salesmanId as salesmanId,b.formType as formType,b.createName ,b.createTime ," .
		"null as amount,if(b.formType = 'CWYF-03', -b.amount ,b.amount ) as needPay ,concat(b.formType,b.id) as id
		from oa_finance_payables b ) a where 1=1 ",
	//计算明细账余额
	'sum_union' => "select sum(a.amount) as amount,sum(a.needPay) as needPay , sum(a.balance) as balance from (
		select " .
		"c.supplierId as supplierId,c.formDate as formDate,c.departmentsId as deptId ,c.salesmanId as salesmanId,c.formType as formType," .
		"if(c.formType = 'blue' , c.amount ,-c.amount ) as amount , null as needPay ,if(c.formType = 'blue' , c.amount ,-c.amount ) as balance ,concat(c.formType,c.id) as id
		from oa_finance_invpurchase c where c.status <> 'CGFPZT-WSH'
		union
		select " .
		"b.supplierId as supplierId ,b.formDate as formDate ,b.deptId as deptId,b.salesmanId as salesmanId,b.formType as formType," .
		"null as amount,if(b.formType = 'CWYF-03', -b.amount ,b.amount ) as needPay ,if(b.formType = 'CWYF-03', b.amount ,-b.amount ) as balance , concat(b.formType,b.id) as id
		from oa_finance_payables b ) a where 1=1 ",
	//应付账汇总
	'count_union' => "select a.objId,a.id,a.supplierId,a.supplierName,sum(a.amount) as amount,sum(a.needPay) as needPay , sum(a.balance) as balance,thisYear ,thisMonth,period from (
		select " .
		"c.id as objId,c.supplierName,c.supplierId as supplierId,c.formDate as formDate,c.departmentsId as deptId ,c.salesmanId as salesmanId,c.formType as formType," .
		"if(c.formType = 'blue' , c.amount ,-c.amount ) as amount , null as needPay ,if(c.formType = 'blue' , c.amount ,-c.amount ) as balance ,concat(c.supplierId,'-',c.id) as id," .
		" year(c.formDate) as thisYear , month(c.formDate) as thisMonth , concat(year(c.formDate),'.',month(c.formDate)) as period
		from oa_finance_invpurchase c where c.status <> 'CGFPZT-WSH'
		union
		select " .
		"b.id  as objId,b.supplierName ,b.supplierId as supplierId ,b.formDate as formDate ,b.deptId as deptId,b.salesmanId as salesmanId,b.formType as formType," .
		"null as amount,if(b.formType = 'CWYF-03', -b.amount ,b.amount ) as needPay ,if(b.formType = 'CWYF-03', b.amount ,-b.amount ) as balance , concat(b.supplierId,'-',b.id) as id," .
		" year(b.formDate) as thisYear , month(b.formDate) as thisMonth , concat(year(b.formDate),'.',month(b.formDate)) as period
		from oa_finance_payables b ) a where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "applyNumb",
		"sql" => "and c.applyNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "status",
		"sql" => "and c.status = # "
	),
	array (
		"name" => "beginMonth",
		"sql" => "and month(formDate) >= # "
	),
	array (
		"name" => "endMonth",
		"sql" => "and month(formDate) <= # "
	),
	array (
		"name" => "year",
		"sql" => "and year(formDate) = #"
	),
	array (
		"name" => "supplierId",
		"sql" => "and supplierId = #"
	),
	array (
		"name" => "supplierIds",
		"sql" => "and supplierId in(arr) "
	),
	array (
		"name" => "salesmanId",
		"sql" => "and salesmanId = # "
	),
	array (
		"name" => "deptIds",
		"sql" => "and deptId in(arr)"
	),
	array(
		"name" => "formDate",
		"sql" => " and formDate >= #"
	),
	array(
		"name" => "formDateMonth",
		"sql" => " and month(formDate) < #"
	)
);
?>
