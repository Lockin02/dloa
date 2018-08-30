<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$beginDate = $_GET['beginDate'];
$endDate = $_GET['endDate'];
$condition = " and date_format(c.createTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.createTime,'%Y-%m-%d') <= '$endDate'";
$condition .= isset($_GET['orderCode']) && !empty($_GET['orderCode']) ? ' AND c.orderCode like "%'.$_GET['orderCode'].'%"' : ''; // 合同号
$condition .= isset($_GET['signCompanyName']) && !empty($_GET['signCompanyName']) ? ' AND c.signCompanyName like "%'.$_GET['signCompanyName'].'%"' : ''; // 签约单位
$condition .= isset($_GET['createName']) && !empty($_GET['createName']) ? ' AND c.createName like "%'.$_GET['createName'].'%"' : ''; // 申请人
$condition .= isset($_GET['payedMoney']) && $_GET['payedMoney'] !== '' ? ' AND (if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney) = '.$_GET['payedMoney'] : ''; // 付款金额
$QuerySQL = <<<QuerySQL
select
	c.id ,c.orderCode ,c.signCompanyName ,c.signDate ,c.orderMoney ,
	c.createName ,c.returnMoney,c.description,date_format(c.createTime,'%Y-%m-%d') as createDate,c.remark,
	if(invo.invotherMoney is null ,0,invo.invotherMoney) + c.initInvotherMoney as invotherMoney,
	if(invo.invotherMoneyConfirm is null,0,invo.invotherMoneyConfirm) + c.initInvotherMoney as invotherMoneyConfirm,
	if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
	    if(invo.invotherMoneyConfirm is null ,0,invo.invotherMoneyConfirm) - c.initInvotherMoney - c.returnMoney as invotherMoneyNone,
	if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney as payedMoney,
	if(pa.payApplyMoney is null,0,pa.payApplyMoney) + c.initPayMoney as payApplyMoney,
	c.isNeedPayapply,c.feeDeptId,c.feeDeptName,
	pd.formDate,pd.payId
from
	oa_sale_other c
	left join
		(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
	left join
		(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
	left join
		(
            select
                i.objId,
                SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
            from oa_finance_invother_detail i inner join oa_finance_invother c on i.mainId = c.id
            where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
        ) invo on c.id = invo.objId
	left join
		(select i.id as payId,p.objId,if(i.formType <>'CWYF-03', p.money,-p.money) as payedMoney,i.formDate from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by i.id) pd on c.id = pd.objId
where c.isTemp = 0 and c.fundType = 'KXXZB' $condition
union
select
	'' as id ,'' as orderCode ,'' as signCompanyName ,'' as signDate ,sum(c.orderMoney) as orderMoney,
	'' as createName ,sum(c.returnMoney) as returnMoney,'' as description,'' as createDate,'' as remark,
	sum(if(invo.invotherMoney is null ,0,invo.invotherMoney)) + SUM(c.initInvotherMoney) as invotherMoney,
	sum(if(invo.invotherMoneyConfirm is null,0,invo.invotherMoneyConfirm)) + SUM(c.initInvotherMoney) as invotherMoneyConfirm,
	sum(if(p.payedMoney is null,0,p.payedMoney)) + SUM(c.initPayMoney) -
	    SUM(if(invo.invotherMoneyConfirm is null ,0,invo.invotherMoneyConfirm)) - SUM(c.initInvotherMoney) - SUM(c.returnMoney) as invotherMoneyNone,
	sum(if(p.payedMoney is null,0,p.payedMoney)) + SUM(c.initPayMoney) as payedMoney,
	sum(if(pa.payApplyMoney is null,0,pa.payApplyMoney)) + SUM(c.initPayMoney) as payApplyMoney,
	'' as isNeedPayapply,'' as feeDeptId,'' as feeDeptName,
	'合计' as formDate,'' as payId
from
	oa_sale_other c
	left join
		(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
	left join
		(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
	left join
		(
            select i.objId,
                SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
            from oa_finance_invother_detail i inner join oa_finance_invother c on i.mainId = c.id
            where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
        ) invo on c.id = invo.objId
where c.isTemp = 0 and c.fundType = 'KXXZB' $condition
QuerySQL;
GenAttrXmlData($QuerySQL, false);