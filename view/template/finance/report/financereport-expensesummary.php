<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = "";
//年
$thisYear = $_GET['thisYear'];
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$monthSet = $_GET['status'] == 'paying' ? 'InputDate' : 'PayDT';
//开始
$beginPeriod = $beginMonth < 10 ? $thisYear . "0" . $beginMonth : $thisYear . $beginMonth;

$condition .= " and date_format(l.$monthSet,'%Y%m') >= '$beginPeriod' ";

//结束
$endPeriod = $endMonth < 10 ? $thisYear . "0" . $endMonth : $thisYear . $endMonth;

$condition .= " and date_format(l.$monthSet,'%Y%m') <= '$endPeriod' ";

//公司
$company = $_GET['company'];
if ($company != 'all') {
	$condition .= ' and l.CostBelongComId = "' . $company . '"';
}

//if ('all' != $moduleName) {
//	$condition .= ' and c.module = "' . $moduleName . '"';
//	$conditionPro .= ' and c.module = "' . $moduleName . '"';
//}

//项目报销取值
$conditionPro = $condition;

//单据状态处理
$newStatus = $_GET['status'] == 'paying' ? "AND l.isEffected = 1 AND l.`Status` <> '完成'" : "AND l.`Status` = '完成'"; // 新报销的状态
$oldStatus = $_GET['status'] == 'paying' ? "AND l.`Status` = '出纳付款'" : "AND l.`Status` = '完成'"; // 旧报销的状态（一般报销）


$detailType = $_GET['DetailType'];  //类型
//费用类型
if ($detailType) {
	if ($detailType != 'all') {
		$typeArr = explode(',', $detailType);
		if (!in_array(1, $typeArr)) {//没有选择部门费用的时候，可以直接用费用类型筛选
			$condition .= " and l.DetailType in(" . $detailType . ") ";
		} else {//如果含有部门费用
			$condition .= " and (l.DetailType in(" . $detailType . ") or l.DetailType = 0)";
		}

		//旧项目费用加在
		if (in_array(2, $typeArr) && in_array(4, $typeArr)) {//当同时含有合同项目费用和售前费用，则不加入查询条件

		} elseif (!in_array(2, $typeArr) && in_array(4, $typeArr)) {//只含有售前
			$conditionPro .= ' and left(l.projectNo,2) ="PK" ';
		} elseif (in_array(2, $typeArr) && !in_array(4, $typeArr)) {//只含有合同项目
			$conditionPro .= ' and left(l.projectNo,2) <>"PK" ';
		} else {//两个都没有
			$conditionPro .= ' and 0 ';
		}
	}
}

//部门权限
$deptNames = $_GET['deptNames'];
if (!empty($deptNames)) {
	$deptArr = explode(",", $deptNames);
	$deptNamesStr = "";
	foreach ($deptArr as $key => $val) {
		$deptNamesStr .= "'" . $val . "',";
	}
	$deptNamesStr = substr($deptNamesStr, 0, strlen($deptNamesStr) - 1);
	$condition .= ' and l.CostBelongtoDeptIds in (' . $deptNamesStr . ') ';

	if (in_array('营销线', $deptArr) && in_array('服务线', $deptArr)) {//当同时含有营销线和服务线，则不加入查询条件

	} elseif (!in_array('营销线', $deptArr) && in_array('服务线', $deptArr)) {//只含有服务线
		$conditionPro .= ' and left(l.projectNo,2) <>"PK" ';
	} elseif (in_array('营销线', $deptArr) && !in_array('服务线', $deptArr)) {//只含有营销线
		$conditionPro .= ' and left(l.projectNo,2) ="PK" ';
	} else {//两个都没有
		$conditionPro .= ' and 0 ';
	}
}


$sql = <<<QuerySQL
select
	l.thisYearMonth,l.thisYear,l.thisMonth,sum(l.CostMoney) as CostMoney,c.CostTypeName,l.isNew,
	if(l.CostBelongDeptId is null or l.CostBelongDeptId = '',d.DEPT_ID,l.CostBelongDeptId) as CostBelongDeptId,
	l.CostBelongDeptName,
	if(d.parentDeptId is null or d.parentDeptId = '',l.parentDeptId,d.parentDeptId) as parentDeptId,
	if(d.parentDeptId is null or d.parentDeptId = '',l.parentDeptName,d.parentDeptName) as parentDeptName,
	c.ParentTypeName,c.ParentCostTypeID
from
	(
		select
			date_format(l.$monthSet,'%Y%m') as thisYearMonth,year(l.$monthSet) as thisYear,month(l.$monthSet) as thisMonth,l.isNew,
			d.CostTypeID,sum(d.CostMoney * d.days) as CostMoney,
			'' as CostBelongDeptId,
			CostBelongtoDeptIds as CostBelongDeptName,
			'' as parentDeptId,
			'' as parentDeptName,
			l.CostBelongComId,
			l.CostBelongCom
		from cost_summary_list l
			inner join cost_detail d on (l.BillNo=d.BillNo)
		where isProject = 0 and l.isNew = 1  and d.CostTypeID $newStatus $condition
		group by l.CostBelongtoDeptIds,d.CostTypeID,date_format(l.$monthSet,'%Y%m')
		union all

		select
			date_format(l.$monthSet,'%Y%m') as thisYearMonth,year(l.$monthSet) as thisYear,month(l.$monthSet) as thisMonth,l.isNew,
			d.CostTypeID,sum(d.CostMoney * d.days) as CostMoney,
			'' as CostBelongDeptId,
			CostBelongtoDeptIds as CostBelongDeptName,
			'' as parentDeptId,
			'' as parentDeptName,
			l.CostBelongComId,
			l.CostBelongCom
		from cost_summary_list l
			INNER JOIN cost_detail_assistant a on (l.BillNo=a.BillNo)
			inner join cost_detail d on a.ID=d.AssID
		where isProject = 0 and l.isNew = 0  and d.CostTypeID $oldStatus $condition
		group by l.CostBelongtoDeptIds,d.CostTypeID,date_format(l.$monthSet,'%Y%m')

		union all

		select
			date_format(l.$monthSet,'%Y%m') as thisYearMonth,year(l.$monthSet) as thisYear,month(l.$monthSet) as thisMonth,l.isNew,
			d.CostTypeID,sum(d.CostMoney * d.days) as CostMoney,
			if(left(l.ProjectNo,2) = 'PK','-2','-1') as CostBelongDeptId,
			if(left(l.ProjectNo,2) = 'PK','PK项目(旧)','工程项目(旧)') as CostBelongDeptName,
			if(left(l.ProjectNo,2) = 'PK','37','35') as parentDeptId,
			if(left(l.ProjectNo,2) = 'PK','营销线','服务线') as parentDeptName,
			l.CostBelongComId as CostBelongComId,
			l.CostBelongComId as CostBelongCom
		from cost_summary_list l
			INNER JOIN cost_detail_assistant a on (l.BillNo=a.BillNo)
			inner join cost_detail_project d on a.ID=d.AssID
		where l.isProject = 1 and l.isNew = 0 and d.CostTypeID $oldStatus $conditionPro
		group by if(left(l.ProjectNo,2) = 'PK','-2','-1'),d.CostTypeID,date_format(l.$monthSet,'%Y%m')
	) l
	left join
	(
		select
			c.CostTypeID,c.CostTypeName,c2.CostTypeID as ParentCostTypeID,c2.CostTypeName as ParentTypeName,c.orderNum,c2.orderNum as parentOrder
		from
			cost_type c inner join cost_type c2 on c.ParentCostTypeID = c2.CostTypeID
		where
			c.ParentCostTypeID <> 1
	) c on c.CostTypeID = l.CostTypeID
	left join
	(
		select
			d1.DEPT_NAME,if(d2.DEPT_NAME is null,d1.DEPT_NAME,d2.DEPT_NAME) as parentDeptName,if(d2.DEPT_ID is null,d1.DEPT_ID,d2.DEPT_ID) as parentDeptId,
			d1.DEPT_ID as DEPT_ID
		from
			department d1 left join department d2 on d1.PARENT_ID = d2.DEPT_ID
			GROUP BY d1.DEPT_NAME
			order by d2.Depart_x
	) d on d.DEPT_NAME = l.CostBelongDeptName
group by l.CostBelongDeptName,c.CostTypeName,l.thisYearMonth
order by c.ParentTypeName,c.ParentCostTypeID,l.CostBelongDeptName,l.thisYearMonth asc
QuerySQL;
GenAttrXmlData($sql, false);