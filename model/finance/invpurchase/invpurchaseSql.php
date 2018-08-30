<?php

/**
 * @author Show
 * @Date 2010年12月21日 星期二 15:52:09
 * @version 1.0
 * @description:采购发票 sql配置文件
 */
$sql_arr = array (
	'select_default' => "select c.id ,c.objCode ,c.objNo ,c.payDate ,c.formNumber,c.supplierName ,c.invType,c.exaMan,c.ExaDT,c.createName ,c.createTime,c.status,
     		c.ExaStatus,c.belongId,c.departments,c.formDate,c.salesman,c.formType,c.purcontId,c.purcontCode,c.payStatus,c.amount,c.taxRate,c.formAssessment,c.formCount,c.updateTime,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName from oa_finance_invpurchase c ".
        "left JOIN(select purchType,basicId from oa_purch_apply_equ group by basicId)e on e.basicId = c.purcontid where 1=1 ",
	'hook_list' => "select c.id,c.createTime,c.objCode,c.formType,c.formDate,d.id as detailId,d.productName,d.productNo,d.productId,d.price,d.number, " .
		"d.hookNumber,d.hookAmount,d.unHookNumber,d.unHookAmount,d.productId,d.amount,c.status,d.objId,d.contractCode,d.contractId,d.objType,d.objCode as dObjCode " .
		"from oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId where 1=1 ",
	'easy_list' => "select c.id ,c.objCode ,c.objNo,c.formType ,c.formDate,c.createTime,c.status  from oa_finance_invpurchase c where 1=1",
	'sum_list' => "select c.id,round(if(sum(d.allCount) is null,0,sum(d.allCount)),2) as handInvoiceMoney " .
		"from oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId where 1=1 ",
	'history' => "select c.id ,c.objCode ,c.objNo ,c.payDate ,c.supplierName ,c.invType,c.createName ,c.ExaStatus,c.exaMan,c.ExaDT," .
		"c.createTime,c.status,c.belongId,c.departments,c.purcontCode,c.formDate,c.salesman,c.formType," .
		"c.purcontId,c.purcontCode,c.payStatus,c.amount,c.taxRate,c.formAssessment,c.formCount " .
		"from oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId where 1=1",
	'count_list' => "select sum(if(c.formType='blue',c.formNumber,-c.formNumber)) as formNumber,sum(if(c.formType='blue',c.amount,-c.amount)) as amount,sum(if(c.formType='blue',c.formAssessment,-c.formAssessment)) as formAssessment,sum(if(c.formType='blue',c.formCount,-c.formCount)) as formCount " .
		"from oa_finance_invpurchase c left JOIN(select purchType,basicId from oa_purch_apply_equ group by basicId)e on e.basicId = c.purcontid where 1=1 ",
	'count_list2' => "select sum(if(c.formType='blue',d.amount,-d.amount)) as allAmount,sum(if(c.formType='blue',d.assessment,-d.assessment)) as allAssessment,sum(if(c.formType='blue',d.allCount,-d.allCount)) as allCount,sum(if(c.formType='blue',d.number,-d.number)) as allNumber " .
		"from oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr)"
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objNo",
		"sql" => " and c.objNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "payDate",
		"sql" => " and c.payDate=# "
	),
	array (
		"name" => "supplierName",
		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "supplierId",
		"sql" => " and c.supplierId=# "
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
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusNo",
		"sql" => " and c.status <># "
	),
	array (
		"name" => "purcontId",
		"sql" => " and c.purcontId=# "
	),
	array (
		"name" => "payStatus",
		"sql" => " and c.payStatus=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr)"
	),
	array (
		"name" => "notEqual",
		"sql" => " and d.unHookNumber <> #"
	),
	array (//单据年
		"name" => "formDateYear",
		"sql" => " and year(c.formDate) = #"
	),
	array (//单据月份
		"name" => "formDateMonth",
		"sql" => " and month(c.formDate) = #"
	),
	array (
		"name" => "objCodeSearch",
		"sql" => " and c.objCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "formDateSearch",
		"sql" => " and c.formDate like CONCAT('%',#,'%') "
	),
	array (//关联id
		"name" => "dobjId",
		"sql" => " and d.objId = #"
	),
	array (//关联类型
		"name" => "dobjType",
		"sql" => " and d.objType = #"
	),
	array (//单据开始日期
		"name" => "formDateBegin",
		"sql" => " and c.formDate >= # "
	),
	array (//单据结束日期
		"name" => "formDateEnd",
		"sql" => " and c.formDate <= # "
	),
	array (
		"name" => "exaManId",
		"sql" => " and c.exaManId=# "
	),
	array (
		"name" => "salesmanId",
		"sql" => " and c.salesmanId =#"
	),
	array (
		"name" => "formType",
		"sql" => " and c.formType =#"
	),
	array (
		"name" => "dcontractId",
		"sql" => " and d.contractId =#"
	),
	array (
		"name" => "invType",
		"sql" => " and c.invType =#"
	),
    array (
        "name" => "noPruType",
        "sql" => " AND (e.purchType<>'oa_asset_purchase_apply' or e.purchType is NULL)"
    ),
    array (
        "name" => "inPruType",
        "sql" => " AND (e.purchType='oa_asset_purchase_apply')"
    ),
	array (//源单编号
		"name" => "objCodeSearchDetail",
		"sql" => " and  c.id in(select i.invPurId from oa_finance_invpurchase_detail i where i.objCode like CONCAT('%',#,'%')) "
	),
	array (//采购订单编号
		"name" => "contractCodeSearch",
		"sql" => " and  c.id in(select i.invPurId from oa_finance_invpurchase_detail i where i.contractCode like CONCAT('%',#,'%')) "
	),
	array (//物料编号搜索
		"name" => "productNoSearch",
		"sql" => " and  c.id in(select i.invPurId from oa_finance_invpurchase_detail i where i.productNo like CONCAT('%',#,'%')) "
	),
	array (//物料名称搜索
		"name" => "productNameSearch",
		"sql" => " and  c.id in(select i.invPurId from oa_finance_invpurchase_detail i where i.productName like CONCAT('%',#,'%')) "
	),
	array (//物料名称搜索
		"name" => "productModelSearch",
		"sql" => " and  c.id in(select i.invPurId from oa_finance_invpurchase_detail i where i.productModel like CONCAT('%',#,'%')) "
	)
)
?>