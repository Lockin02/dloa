<?php
$sql_arr = array(
	//默认搜索语句
	"select_default" => "select c.id ,c.objCode ,date_format(c.createTime,'%Y-%m-%d') as orderTime,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ,c.isStamp," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppBankName,c.suppAccount ,c.suppAddress ,c.allMoney," .
			"c.billingType ,c.billingTypeName,c.paymentCondition ,c.paymentConditionName,c.paymentType ,c.paymentTypeName,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.payRatio,c.signState,c.signTime,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp ,c.originalId,c.isApplyPay,c.allMoneyCur,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName from oa_purch_apply_basic c where c.isTemp =0",
	//默认搜索语句
	"select_leftjoin" => "select c.id ,c.objCode ,date_format(c.createTime,'%Y-%m-%d') as orderTime,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppBankName,c.suppAccount ,c.suppAddress ,c.allMoney,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName," .
			"c.billingType ,c.billingTypeName,c.paymentCondition ,c.paymentConditionName,c.paymentType ,c.paymentTypeName,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.payRatio,c.signState,c.signTime,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp ,c.originalId, c.currency,c.currencyCode,c.rate,p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,cast((p.price*p.amountAll) as decimal(20,6)) as noTaxMoney,p.taxRate,p.units,p.moneyAll,p.amountAll,p.applyPrice,p.applyDeptId,p.applyDeptName,p.dateIssued,p.dateHope,p.remark,p.purchType  from oa_purch_apply_basic c left join oa_purch_apply_equ p on c.id=p.basicId  where c.isTemp =0",
	/*****************************************工作流部分***********************************/
	"sql_examine" => "select " .
		"w.task as taskId,p.ID as spid ,c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppBankName,c.suppAccount ,c.suppAddress ,c.allMoney," .
			"c.billingType ,c.paymentCondition,c.paymentConditionName ,c.paymentType ,c.paymentTypeName ,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange,c.payRatio,c.signState,c.signTime ,c.createId ,c.createName  " .
		" ,c.isTemp ,c.originalId from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_apply_basic c " .
		" where w.Pid =c.id and w.examines <> 'no' ",
	"sql_examine2" => "select " .
		"w.task as taskId,p.ID as spid ,c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppBankName,c.suppAccount ,c.suppAddress ,c.allMoney," .
			"c.billingType ,c.paymentCondition,c.paymentConditionName ,c.paymentType ,c.paymentTypeName ,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.payRatio,c.signState,c.signTime,c.createId ,c.createName  " .
		" ,c.isTemp ,c.originalId from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_apply_basic c " .
		" where w.Pid =c.id ",
	"myResSupp" =>"select c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppBankName,c.suppAccount ,c.suppAddress ,c.allMoney," .
			"c.billingType ,c.billingTypeName,c.paymentCondition ,c.paymentConditionName,c.paymentType ,c.paymentTypeName,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange,c.payRatio,c.signState,c.signTime ,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp ,c.originalId from oa_purch_apply_basic c where c.isTemp =0 ",
	"get_suppInfo" => "select " .
			"a.parentId,a.suppName,a.suppId,a.suppTel,a.quote,a.payRatio,a.arrivalDate,a.paymentCondition,a.paymentConditionName,b.id,b.parentId,b.productName,b.productName,b.productId,b.productNumb,b.pattem,b.auxiliary,b.units," .
			"b.amount,b.transportation,b.price,b.deliveryDate,b.inquiryEquid,b.takeEquId,b.taxRate,ie.purchType,ie.batchNumb " .
			"from oa_purch_inquiry_supp a right join oa_purch_inquiry_suppequ b " .
			"on b.parentId = a.id left join oa_purch_inquiry_equ ie on(ie.id=b.inquiryEquId) where 1=1 ",
	"get_suppInfo_minDate" => "select a.id," .
			"min(a.arrivalDate) as dateHope " .
			"from oa_purch_inquiry_supp a right join oa_purch_inquiry_suppequ b " .
			"on b.parentId = a.id left join oa_purch_inquiry_equ ie on(ie.id=b.inquiryEquId) where 1=1 ",
	"exportItem"=>"select c.id,c.sendUserId,c.sendName,c.sendTime,c.hwapplyNumb,date_format(c.createTime,'%Y-%m-%d') as createTime,c.suppId,c.suppName,c.state,c.ExaStatus,p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,cast((p.price*p.amountAll) as decimal(20,6)) as noTaxMoney,p.taxRate,p.units,p.moneyAll,cast(p.amountAll as decimal(10,0)) as amountAll,p.applyPrice,p.applyDeptId,p.applyDeptName,p.dateIssued,p.dateHope,p.remark" .
			"		from oa_purch_apply_basic c left join oa_purch_apply_equ p on c.id=p.basicId where c.isTemp =0 and p.amountAll>0",
	"executEquList"=>"select c.id,c.sendUserId,c.sendName,c.sendTime,c.hwapplyNumb,date_format(c.createTime,'%Y-%m-%d') as createTime,c.suppId,c.suppName,c.state,c.ExaStatus,p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,p.units,p.moneyAll,p.amountAll,p.amountIssued,p.applyPrice,p.purchType,p.dateIssued,p.dateHope " .
			"		from oa_purch_apply_basic c left join oa_purch_apply_equ p on c.id=p.basicId where c.isTemp =0 and p.amountAll>0 and p.purchType!='assets'",
    "purchEquList"=>"select c.id,c.sendUserId,c.sendName,c.sendTime,c.hwapplyNumb,date_format(c.createTime,'%Y-%m-%d') as createTime,c.suppId,c.suppName,c.state,c.ExaStatus,p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,p.units,p.moneyAll,p.amountAll,p.amountIssued,p.applyPrice,p.purchType,p.dateIssued,p.dateHope " .
        "		from oa_purch_apply_basic c left join oa_purch_apply_equ p on c.id=p.basicId where c.isTemp =0 and p.amountAll>0 ",
	"manageExeList"=>"select c.id,c.isTemp,date_format(c.createTime,'%Y-%m-%d') as orderTime,c.updateTime,c.objCode,c.applyNumb,c.sendUserId,c.sendName,c.sendTime,c.dateHope,c.dateFact,c.state," .
					"c.suppId,c.suppName,c.allMoney,c.billingType,c.billingTypeName,c.paymentCondition,c.paymentConditionName,c.paymentType,c.stampType ,c.isNeedStamp ,c.isStamp," .
					"c.paymentTypeName,c.ExaStatus,c.hwapplyNumb,c.payRatio," .
					"sub.purchType,sub.amountAll,sub.amountIssued,sub.shallPay," .
					"cast(if(sum(ad.money) is null,0,sum(ad.money)) as decimal(20,6)) as payed ,(c.allMoney - if(sum(ad.money) is null ,0,sum(ad.money)))  as unpayed," .
					"cast(if(sum(ind.allCount) is null,0,sum(ind.allCount)) as decimal(20,6)) as handInvoiceMoney " .
					"from oa_purch_apply_basic c " .
					            "left join (select max(purchType) as purchType,basicId,cast(sum(amountAll) as decimal(10,0)) as amountAll,sum(amountIssued) as amountIssued,sum(amountIssued*applyPrice) as shallPay from oa_purch_apply_equ  group by basicId) sub on(sub.basicId=c.id) " .
					"left join (" .
					            " select c.id ,c.advancesId ,sum(if(p.formType = 'CWYF-03',-c.money,c.money)) as money ,c.objId  from oa_finance_payables_detail c left join  oa_finance_payables p on c.advancesId = p.id where 1=1 and c.objType='YFRK-01' GROUP by c.objId" .
					                     " ) ad on ad.objId=c.id " .
					"left join (" .
					             "select sum(allCount) as allCount,contractId from oa_finance_invpurchase_detail group by contractId" .
					                        " ) ind  on ind.contractId=c.id " .
					             "where c.isTemp =0 ",
	"manageEndList"=>"select c.id,c.isTemp,date_format(c.createTime,'%Y-%m-%d') as orderTime,c.updateTime,c.objCode,c.applyNumb,c.sendUserId,c.sendName,c.sendTime,c.dateHope,c.dateFact,c.state," .
					"c.suppId,c.suppName,c.allMoney,c.billingType,c.billingTypeName,c.paymentCondition,c.paymentConditionName,c.paymentType,c.stampType ,c.isNeedStamp ,c.isStamp," .
					"c.paymentTypeName,c.ExaStatus,c.hwapplyNumb,c.payRatio," .
					"sub.purchType,sub.amountAll,sub.amountIssued,sub.shallPay," .
					"cast(if(sum(ad.money) is null,0,sum(ad.money)) as decimal(20,6)) as payed ,(c.allMoney - if(sum(ad.money) is null ,0,sum(ad.money)))  as unpayed," .
					"cast(if(sum(ind.allCount) is null,0,sum(ind.allCount)) as decimal(20,6)) as handInvoiceMoney,stock.auditDate,inv.formDate,ad.payFormDate " .
					"from oa_purch_apply_basic c " .
					            "left join (select max(purchType) as purchType,basicId,cast(sum(amountAll) as decimal(10,0)) as amountAll,sum(amountIssued) as amountIssued,sum(amountIssued*applyPrice) as shallPay from oa_purch_apply_equ  group by basicId) sub on(sub.basicId=c.id) " .
					"left join (" .
					            " select c.id ,c.advancesId ,sum(if(p.formType = 'CWYF-03',-c.money,c.money)) as money ,max(formDate) as payFormDate,c.objId  from oa_finance_payables_detail c left join  oa_finance_payables p on c.advancesId = p.id where 1=1 and c.objType='YFRK-01'  GROUP by c.objId" .
					                     " ) ad on ad.objId=c.id " .
					"left join (" .
					             "select sum(allCount) as allCount,contractId from oa_finance_invpurchase_detail group by contractId" .
					                        " ) ind  on ind.contractId=c.id " .
                    "left join ( " .
            					 "select max(formDate) as formDate,purcontId from oa_finance_invpurchase group by purcontId " .
                         ") inv on inv.purcontId=c.id  " .
					"left join( " .
							"select max(auditDate) as auditDate,purOrderId from oa_stock_instock  group by purOrderId " .
								") stock on (stock.purOrderId=c.id)" .
					             "where c.isTemp =0 ",
	"payapply_list" => "select
				c.id ,c.suppId ,c.suppName ,c.allMoney,c.sendName,c.sendUserId,
				c.hwapplyNumb ,c.payed, c.handInvoiceMoney,c.applyed,c.allMoney - c.applyed as canApply,
				c.currency,c.currencyCode,c.rate,c.allMoneyCur
			 from (
				select
					b.id ,b.sendUserId,b.businessBelong,b.currency,b.currencyCode,b.rate,b.allMoneyCur,
					b.suppId ,b.suppName ,b.allMoney,b.sendName,b.createId,b.state,if( ap.applyed is null , 0,ap.applyed) as applyed,
					b.hwapplyNumb ,if( p.payed is null , 0,p.payed) as payed, if(i.handInvoiceMoney is null , 0, i.handInvoiceMoney) as handInvoiceMoney
				from
					oa_purch_apply_basic b
				left join (
					select
						c.id,sum(if(c.formType = 'CWYF-03',-d.money,d.money)) as payed,d.objId,d.objType
					from
						oa_finance_payables c inner join oa_finance_payables_detail d on c.id = d.advancesId and d.objType = 'YFRK-01'
					group by d.objId
				) p on b.id = p.objId
				left join (
					select
						c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money)) as applyed,d.objId,d.objType
					from
						oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where c.ExaStatus <> '打回' and c.status not in('FKSQD-04','FKSQD-05') and d.objType = 'YFRK-01'
					group by d.objId
				) ap on b.id = ap.objId
				left join (
					select
						c.id,sum(if(c.formType = 'blue',d.amount,-d.amount)) as handInvoiceMoney,c.purcontId
					from
						oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId
					group by c.purcontId
				) i on b.id = i.purcontId
				where b.isTemp =0
				) c
			where 1=1",
	"orderinfo_money"=>"select c.id,c.isTemp,c.updateTime,c.sendUserId,c.signStatus,c.sendName,c.dateHope,c.state," .
					"c.suppId,c.suppName,c.allMoney,c.ExaDT,c.stampType ,c.isNeedStamp ,c.isStamp," .
					"c.ExaStatus,c.hwapplyNumb," .
					"sub.purchType,sub.amountAll,sub.amountIssued,sub.shallPay," .
					"cast(if(sum(ad.money) is null,0,sum(ad.money)) as decimal(20,6)) as payed ,(c.allMoney - if(sum(ad.money) is null ,0,sum(ad.money)))  as unpayed," .
					"cast(if(sum(ind.allCount) is null,0,sum(ind.allCount)) as decimal(20,6)) as handInvoiceMoney ,cast(if(sum(ap.applyed) is null,0,sum(ap.applyed)) as decimal(20,6)) as applyed " .
					"from oa_purch_apply_basic c " .
					            "left join (select max(purchType) as purchType,basicId,cast(sum(amountAll) as decimal(10,0)) as amountAll,sum(amountIssued) as amountIssued,sum(amountIssued*applyPrice) as shallPay from oa_purch_apply_equ  group by basicId) sub on(sub.basicId=c.id) " .
					"left join (" .
					            " select c.id ,c.advancesId ,sum(if(p.formType = 'CWYF-03',-c.money,c.money)) as money ,c.objId  from oa_finance_payables_detail c left join  oa_finance_payables p on c.advancesId = p.id where 1=1 and c.objType='YFRK-01' GROUP by c.objId" .
					                     " ) ad on ad.objId=c.id " .
					"left join (" .
					             "select sum(allCount) as allCount,contractId from oa_finance_invpurchase_detail group by contractId" .
					                        " ) ind  on ind.contractId=c.id  " .
				"left join (" .
						"select c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money)) as applyed,d.objId,d.objType " .
						"from oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where c.ExaStatus <> '打回' and c.status not in('FKSQD-04','FKSQD-05') and d.objType = 'YFRK-01' " .
						"group by d.objId" .
					") ap on c.id = ap.objId " .
					             "where c.isTemp =0 "
);


$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=# "
	),
	array(
		"name" => "ids",
		"sql" => "and c.id in(arr) "
	),
	array(
		"name"=>"sendName",
		"sql"=>" and c.sendName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"sendUserId",
		"sql"=>" and c.sendUserId = #"
	),
	array(
		"name"=>"state",
		"sql"=>" and c.state = #"
	),
	array(
		"name" => "createName",
		"sql" => "and c.createName = #"
	),
	array(
		"name" => "ExaDT",
		"sql" => "and c.ExaDT = #"
	)
	,array(
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus in(arr)"
	)
		//审核工作流
	,array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
	array (
		"name" => "seachApplyNumb",
		"sql" => " and c.hwapplyNumb like CONCAT('%',#,'%') "
	),
	array (
		"name" => "applyNumb",
		"sql" => " and c.applyNumb like CONCAT('%',#,'%')"
	),
//	array(
//		"name" => "isUse",
//		"sql" => " and isUse=# "
//	),

	array(
		"name" => "createId",
		"sql" => " and c.createId =# ",
	),

	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
	),
	array(
		"name" => "hwapplyNumb",
		"sql" => " and c.hwapplyNumb like CONCAT('%',#,'%') "
	),


	array(
		"name" => "wfCode",
		"sql" => " and w.code=# "
	),
	array(
		"name" => "wfFlag",
		"sql" => " and p.flag=# "
	),
	array(
		"name" => "wfTake",
		"sql" => " and p.wf_task_id=w.task "
	),
	array(
		"name" => "wfUser",
		"sql" => " and find_in_set(#,p.User)>0 "
	),
	array(
		"name" => "wfStatus",
		"sql" => " and w.Status=# "
	),
	array(
		"name" => "wfExamines",
		"sql" => " and w.examines='' "
	),
	array(
		"name" => "wfEnter_user",
		"sql" => " and u.USER_ID=w.Enter_user "
	),
	array(
		"name" => "wfName",
		"sql" => " and w.name in(arr) "
	)
	,array(
		"name" => "ajaxContractNumb",//ajax验证编号
		"sql"=>" and c.hwapplyNumb = # "
	),
	//采购询价单ID
	array(
		"name" => "inquiryId",
		"sql" => "and a.parentId = #"
	),
	array(
		"name" => "inquiryIdArr",
		"sql" => "and a.parentId in(arr)"
	),
	array(
		"name" => "inquiryIdEquArr",
		"sql" => "and b.id in(arr)"
	),
	//供应商ID
	array(
		"name" => "suppId",
		"sql" => "and a.suppId = #"
	),

	array(
		"name" => "purchType",
		"sql" => "and purchType = #"
	),
	//供应商
	array(
		"name" => "suppName",
		"sql" => "and c.suppName like CONCAT('%',#,'%')"
	),
	array(//供应商id 对应本表
		"name" => "csuppId",
		"sql" => "and c.suppId =#"
	),
	array(
		"name" => "originalId",
		"sql" => "and c.originalId =#"
	),
	array(
		"name" => "signState",
		"sql" => "and c.signState =#"
	),
	array(
		"name" => "isTemp",
		"sql" => "and c.isTemp =#"
	),
	array(
		"name" => "cannotpayed",
		"sql" => "and c.applyed <> c.allMoney"
	),
	array(
		"name" => "createTime",
		"sql" => "and c.createTime =#"
	),
	array(
		"name" => "orderTime",
		"sql" => "and date_format(c.createTime,'%Y-%m-%d') LIKE BINARY CONCAT('%',#,'%')"
	),
    array(
        "name" => "orderMonth",
        "sql" => "and date_format(c.createTime,'%Y-%m')  in(arr) "
    ),
	array(
		"name" => "productId",
		"sql" => "and p.productId =#"
	),
	array(
		"name" => "applyDeptName",
		"sql" => "and p.applyDeptName =#"
	),
	array(
		"name" => "beginTime",
		"sql" => "and date_format(c.createTime,'%Y-%m-%d') >=#"
	),
	array(
		"name" => "endTime",
		"sql" => "and date_format(c.createTime,'%Y-%m-%d') <=#"
	),
	array(
		"name" => "productNumb",
		"sql" => "and c.id in(select basicId from oa_purch_apply_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select basicId from oa_purch_apply_equ where productName like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "searchProductNumb",
		"sql" => "and p.id in(select id from oa_purch_apply_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "searchPproductName",
		"sql" => "and p.id in(select id from oa_purch_apply_equ where productName like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "searchPattem",
		"sql" => "and p.id in(select id from oa_purch_apply_equ where pattem like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "isInStock",
		"sql" => "and p.amountAll !=p.amountIssued"
	)
);
?>
