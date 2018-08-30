<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = "";
//��
$thisYear = $_GET['thisYear'];
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$monthSet = $_GET['status'] == 'paying' ? 'InputDate' : 'PayDT';
//��ʼ
$beginPeriod = $beginMonth < 10 ? $thisYear . "0" . $beginMonth : $thisYear . $beginMonth;

$condition .= " and date_format(l.$monthSet,'%Y%m') >= '$beginPeriod' ";

//����
$endPeriod = $endMonth < 10 ? $thisYear . "0" . $endMonth : $thisYear . $endMonth;

$condition .= " and date_format(l.$monthSet,'%Y%m') <= '$endPeriod' ";

//��˾
$company = $_GET['company'];
if ($company != 'all') {
	$condition .= ' and l.CostBelongComId = "' . $company . '"';
}

//if ('all' != $moduleName) {
//	$condition .= ' and c.module = "' . $moduleName . '"';
//	$conditionPro .= ' and c.module = "' . $moduleName . '"';
//}

//��Ŀ����ȡֵ
$conditionPro = $condition;

//����״̬����
$newStatus = $_GET['status'] == 'paying' ? "AND l.isEffected = 1 AND l.`Status` <> '���'" : "AND l.`Status` = '���'"; // �±�����״̬
$oldStatus = $_GET['status'] == 'paying' ? "AND l.`Status` = '���ɸ���'" : "AND l.`Status` = '���'"; // �ɱ�����״̬��һ�㱨����


$detailType = $_GET['DetailType'];  //����
//��������
if ($detailType) {
	if ($detailType != 'all') {
		$typeArr = explode(',', $detailType);
		if (!in_array(1, $typeArr)) {//û��ѡ���ŷ��õ�ʱ�򣬿���ֱ���÷�������ɸѡ
			$condition .= " and l.DetailType in(" . $detailType . ") ";
		} else {//������в��ŷ���
			$condition .= " and (l.DetailType in(" . $detailType . ") or l.DetailType = 0)";
		}

		//����Ŀ���ü���
		if (in_array(2, $typeArr) && in_array(4, $typeArr)) {//��ͬʱ���к�ͬ��Ŀ���ú���ǰ���ã��򲻼����ѯ����

		} elseif (!in_array(2, $typeArr) && in_array(4, $typeArr)) {//ֻ������ǰ
			$conditionPro .= ' and left(l.projectNo,2) ="PK" ';
		} elseif (in_array(2, $typeArr) && !in_array(4, $typeArr)) {//ֻ���к�ͬ��Ŀ
			$conditionPro .= ' and left(l.projectNo,2) <>"PK" ';
		} else {//������û��
			$conditionPro .= ' and 0 ';
		}
	}
}

//����Ȩ��
$deptNames = $_GET['deptNames'];
if (!empty($deptNames)) {
	$deptArr = explode(",", $deptNames);
	$deptNamesStr = "";
	foreach ($deptArr as $key => $val) {
		$deptNamesStr .= "'" . $val . "',";
	}
	$deptNamesStr = substr($deptNamesStr, 0, strlen($deptNamesStr) - 1);
	$condition .= ' and l.CostBelongtoDeptIds in (' . $deptNamesStr . ') ';

	if (in_array('Ӫ����', $deptArr) && in_array('������', $deptArr)) {//��ͬʱ����Ӫ���ߺͷ����ߣ��򲻼����ѯ����

	} elseif (!in_array('Ӫ����', $deptArr) && in_array('������', $deptArr)) {//ֻ���з�����
		$conditionPro .= ' and left(l.projectNo,2) <>"PK" ';
	} elseif (in_array('Ӫ����', $deptArr) && !in_array('������', $deptArr)) {//ֻ����Ӫ����
		$conditionPro .= ' and left(l.projectNo,2) ="PK" ';
	} else {//������û��
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
			if(left(l.ProjectNo,2) = 'PK','PK��Ŀ(��)','������Ŀ(��)') as CostBelongDeptName,
			if(left(l.ProjectNo,2) = 'PK','37','35') as parentDeptId,
			if(left(l.ProjectNo,2) = 'PK','Ӫ����','������') as parentDeptName,
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