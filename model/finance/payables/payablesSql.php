<?php
/**
 * @author Show
 * @Date 2011��5��6�� ������ 16:17:38
 * @version 1.0
 * @description:Ӧ�����/Ӧ��Ԥ����/Ӧ���˿ sql�����ļ�
 */
$sql_arr = array (
	'select_default' => "select c.id ,c.payApplyId ,c.payApplyNo ,c.formNo ,c.formDate ,c.financeDate ,c.supplierName ,c.supplierId ,
            c.payType ,c.bank ,c.amount ,c.deptId ,c.deptName ,c.salesman ,c.status ,c.ExaStatus ,c.ExaDT ,c.createName ,
            c.createTime ,c.updateTime,c.belongId,c.isEntrust,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,
			c.currency,c.rate,c.amountCur
        from oa_finance_payables c where 1=1 ",
	'select_history' => "select c.id ,c.payApplyId ,c.formType,c.payApplyNo ,c.formNo ,c.formDate ,c.financeDate ,c.supplierName,
			c.payType ,c.bank ,c.amount ,c.deptId ,c.deptName ,c.salesman ,c.status ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,c.updateTime,d.money,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from oa_finance_payables c left join oa_finance_payables_detail d on c.id = d.advancesId ",
	'select_historyNew' => "select c.id ,c.payApplyId ,c.formType,c.payApplyNo,c.formNo,c.formDate,c.financeDate,c.supplierName,
			c.payType ,c.bank ,c.amount ,c.deptId ,c.deptName ,c.salesman ,c.status ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,c.updateTime,sum(d.money) as money,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from oa_finance_payables c left join oa_finance_payables_detail d on c.id = d.advancesId ",
	'sum_list' => "select c.id,if(sum(d.money) is null ,0,sum(if(c.formType = 'CWYF-03',-d.money,d.money))) as payed
		from oa_finance_payables c left join oa_finance_payables_detail d on c.id = d.advancesId",
	'sum_money' => "select sum(c.amount) as amount from oa_finance_payables c where 1=1 ",
	'count_money' => "select sum(c.amount) as amount ,sum(d.money) as money from oa_finance_payables_detail d left join oa_finance_payables c on d.advancesId=c.id "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "payApplyId",
		"sql" => " and c.payApplyId=# "
	),
	array (
		"name" => "payApplyNo",
		"sql" => " and c.payApplyNo=# "
	),
	array (//��Ӧ������,���ڿ�������
		"name" => "payApplyNoSearch",
		"sql" => " and c.payApplyNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "formNo",
		"sql" => " and c.formNo=# "
	),
	array (
		"name" => "formNoSearch",
		"sql" => " and c.formNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "formDate",
		"sql" => " and c.formDate=# "
	),
	array (
		"name" => "formDateSearch",
		"sql" => " and c.formDate like binary CONCAT('%',#,'%') "
	),
	array (
		"name" => "supplierId",
		"sql" => " and c.supplierId=# "
	),
	array (
		"name" => "amount",
		"sql" => " and c.amount=# "
	),
	array (//��Ӧ������,���ڿ�������
		"name" => "supplierName",
		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "formType",
		"sql" => " and c.formType = #"
	),
	array (
		"name" => "dObjId", //�ӱ������
		"sql" => " and d.objId = # "
	),
	array (
		"name" => "dObjType", //�ӱ������
		"sql" => " and d.objType= # "
	),
	array (
		"name" => "belongId", //����id
		"sql" => " and c.belongId= # "
	),
	array (//Դ�����
		"name" => "objCodeSearchDetail",
		"sql" => " and  c.id in(select i.advancesId from oa_finance_payables_detail i where i.objCode like CONCAT('%',#,'%')) "
	)
);
?>