<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year = $_GET['thisYear'];  //��
$beginMonth = $_GET['beginMonth']; //��
$endMonth = $_GET['endMonth']; //��
$detailType = $_GET['DetailType'];  //����
$company = $_GET['company'];  //��˾
$moduleName = $_GET['moduleName'];  //���
$costBelongDeptId = $_GET['CostBelongDeptId'];//��ϸ����
$CostBelongDeptName = $_GET['CostBelongDeptName'];//��ϸ��������
$parentDeptId = $_GET['parentDeptId'];//һ������
$parentDeptName = $_GET['parentDeptName'];//һ������
$CostTypeName = isset($_GET['CostTypeName']) ? $_GET['CostTypeName'] : "";//��������

$monthSet = $_GET['status'] == 'paying' ? 'InputDate' : 'PayDT';
$conditionPro = $condition = " and year(c.$monthSet)= '$year' and month(c.$monthSet)>='$beginMonth' and month(c.$monthSet) <= '$endMonth'";

if ('all' != $detailType) {
	$typeArr = explode(',', $detailType);
	if (!in_array(1, $typeArr)) {//û��ѡ���ŷ��õ�ʱ�򣬿���ֱ���÷�������ɸѡ
		$condition .= " and c.DetailType in(" . $detailType . ") ";
	} else {//������в��ŷ���
		$condition .= " and (c.DetailType in(" . $detailType . ") or c.DetailType = 0)";
	}

	//����Ŀ���ü���
	if (in_array(2, $typeArr) && in_array(4, $typeArr)) {//��ͬʱ���к�ͬ��Ŀ���ú���ǰ���ã��򲻼����ѯ����

	} elseif (!in_array(2, $typeArr) && in_array(4, $typeArr)) {//ֻ������ǰ
		$conditionPro .= ' and left(c.projectNo,2) ="PK" ';
	} elseif (in_array(2, $typeArr) && !in_array(4, $typeArr)) {//ֻ���к�ͬ��Ŀ
		$conditionPro .= ' and left(c.projectNo,2) <>"PK" ';
	} else {//������û��
		$conditionPro .= ' and 0 ';
	}
}

if ('all' != $company) {
	$condition .= ' and c.CostBelongComId = "' . $company . '"';
	$conditionPro .= ' and c.CostBelongComId = "' . $company . '"';
}

if ('all' != $moduleName) {
	$condition .= ' and c.module = "' . $moduleName . '"';
	$conditionPro .= ' and c.module = "' . $moduleName . '"';
}

//����״̬����
$newStatus = $_GET['status'] == 'paying' ? "AND c.isEffected = 1 AND c.`Status` <> '���'" : "AND c.`Status` = '���'"; // �±�����״̬
$oldStatus = $_GET['status'] == 'paying' ? "AND c.`Status` = '���ɸ���'" : "AND c.`Status` = '���'"; // �ɱ�����״̬��һ�㱨����

//���ù������� - ��ϸ����
if ($costBelongDeptId != "") {
	//��������ʱ��PK������Ŀ
	if ($costBelongDeptId == '-1') {
		$condition .= ' and 0';
		$conditionPro .= ' and left(c.projectNo,2) <> "PK"';
	} elseif ($costBelongDeptId == '-2') {
		$condition .= ' and 0';
		$conditionPro .= ' and left(c.projectNo,2) = "PK"';
	} else {
		$condition .= ' and c.CostBelongtoDeptIds = "' . $CostBelongDeptName . '"';
		$conditionPro .= ' and 0 ';
	}
}

//���ù������� - һ������
if (!empty($parentDeptId)) {
	//��������ʱ��PK������Ŀ
	$condition .= ' and c.CostBelongtoDeptIds in (select d1.DEPT_NAME from department d1 left join department d2 on d1.PARENT_ID = d2.DEPT_ID
		where d1.DEPT_NAME = "' . $parentDeptName . '" or d2.DEPT_NAME = "' . $parentDeptName . '")';
	if ($costBelongDeptId == '35') {
		$conditionPro .= ' and left(c.projectNo,2) <> "PK"';
	} elseif ($costBelongDeptId == '37') {
		$conditionPro .= ' and left(c.projectNo,2) = "PK"';
	} else {
		$conditionPro .= ' and 0 ';
	}
}

if ($CostTypeName) {
	$allCondition = " and c.CostTypeName = '$CostTypeName'";
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
	$condition .= ' and c.CostBelongtoDeptIds in (' . $deptNamesStr . ') ';

	if (in_array('Ӫ����', $deptArr) && in_array('������', $deptArr)) {//��ͬʱ����Ӫ���ߺͷ����ߣ��򲻼����ѯ����

	} elseif (!in_array('Ӫ����', $deptArr) && in_array('������', $deptArr)) {//ֻ���з�����
		$conditionPro .= ' and left(c.projectNo,2) <>"PK" ';
	} elseif (in_array('Ӫ����', $deptArr) && !in_array('������', $deptArr)) {//ֻ����Ӫ����
		$conditionPro .= ' and left(c.projectNo,2) ="PK" ';
	} else {//������û��
		$conditionPro .= ' and 0 ';
	}
}
$conditionF = str_replace('c.module','e.module',$condition);
$sql = <<<QuerySQL
select T.* from (
select
	p.$monthSet, p.ID ,p.thisMonth,p.BillNo,u.USER_NAME as CostManName,p.Purpose,d.DEPT_NAME as CostDepartName,p.CostDepartID,
	p.CostBelongDeptName,p.isNew,p.chanceCode,p.CostMan,p.proManagerId,r.DEPT_NAME as proDepartment,p.proManagerName,
	p.DetailType,p.ProjectNo,p.contractCode,p.feeManId,p.feeMan,p.salesAreaId,p.salesArea,
	p.CustomerType,p.CostBelonger,p.Amount,p.CostTypeID,p.CostMoney,p.moduleName,
	c.CostTypeName,c.ParentCostTypeID,c.ParentTypeName,c.orderNum,c.parentOrder,p.province,p.projectId,p.projectType,t.productLineName
from
	(
		select c.ID ,c.$monthSet,MONTH(c.$monthSet) as thisMonth,c.BillNo,c.CostManName,c.Purpose,c.CostDepartName,
			c.CostBelongtoDeptIds as CostBelongDeptName,c.isNew,c.chanceCode,c.CostMan,c.CostDepartID,
			CASE c.DetailType
			WHEN 1 then '���ŷ���'
			WHEN 2 then '��ͬ��Ŀ����'
			WHEN 3 then '�з�����'
			WHEN 4 then '��ǰ����'
			WHEN 5 then '�ۺ����'
			END as DetailType,c.ProjectNo,c.contractCode,c.proManagerId,c.proManagerName,
			c.CustomerType,c.CostBelonger,ROUND(c.Amount,2) AS Amount,e.CostTypeID,ROUND(SUM(e.CostMoney),2) as CostMoney,
			c.province,c.projectId,c.projectType,e.moduleName,c.feeManId,c.feeMan,c.salesAreaId,c.salesArea
		from cost_summary_list c
			LEFT JOIN oa_finance_costshare e on (c.BillNo=e.BillNo)
		where
			c.isProject = 0 and c.isNew = 1 $newStatus and e.CostTypeID
			$conditionF
		GROUP BY c.BillNo,e.CostTypeID,e.moduleName

		union all

		select
			c.ID ,c.$monthSet,MONTH(c.$monthSet) as thisMonth,c.BillNo,c.CostManName,c.Purpose,c.CostDepartName,
			c.CostBelongtoDeptIds as CostBelongDeptName,c.isNew,c.chanceCode,c.CostMan,c.CostDepartID,
			'���ŷ���' as DetailType,c.ProjectNo,c.contractCode,c.proManagerId,c.proManagerName,
			c.CustomerType,c.CostBelonger,ROUND(c.Amount,2) AS Amount,d.CostTypeID,ROUND(SUM(d.CostMoney*d.days),2) as CostMoney,
			c.CostClientArea as province,c.projectId,c.projectType,'-' AS moduleName,c.feeManId,c.feeMan,c.salesAreaId,c.salesArea
    	from cost_summary_list c
			INNER JOIN cost_detail_assistant a on (c.BillNo=a.BillNo)
			inner join cost_detail d on a.ID=d.AssID
		where
			c.isProject = 0 and c.isNew = 0 $oldStatus and d.CostTypeID
			$condition
		GROUP BY c.BillNo,d.CostTypeID
		union all

		select
			c.ID ,c.$monthSet,MONTH(c.$monthSet) as thisMonth,c.BillNo,c.CostManName,c.Purpose,c.CostDepartName,
			if(left(c.ProjectNo,2) = 'PK','PK��Ŀ','������Ŀ(������)') as CostBelongDeptName,
			c.isNew,c.chanceCode,c.CostMan,c.CostDepartID,
			if(left(c.ProjectNo,2) = 'PK','PK��Ŀ','������Ŀ(������)') as DetailType,
			c.ProjectNo,c.contractCode,c.proManagerId,c.proManagerName,
			c.CustomerType,c.CostBelonger,ROUND(c.Amount,2) AS Amount,d.CostTypeID,ROUND(SUM(d.CostMoney*d.days),2) as CostMoney,
			c.CostClientArea as province,c.projectId,c.projectType,'-' AS moduleName,c.feeManId,c.feeMan,c.salesAreaId,c.salesArea
		from
			cost_summary_list c
			INNER JOIN cost_detail_assistant a on (c.BillNo=a.BillNo)
			inner join cost_detail_project d on a.ID=d.AssID
		where
			c.isProject = 1 $oldStatus and d.CostTypeID
			$conditionPro
		GROUP BY c.BillNo,d.CostTypeID
	) p left join
	(
		select
			c.CostTypeID,c.CostTypeName,c2.CostTypeID as ParentCostTypeID,c2.CostTypeName as ParentTypeName,c.orderNum,c2.orderNum as parentOrder
		from
			cost_type c inner join cost_type c2 on c.ParentCostTypeID = c2.CostTypeID
		where c.ParentCostTypeID <> 1
	) c on p.CostTypeID=c.CostTypeID
	left join
    	user u on p.CostMan = u.USER_ID
	left join
    	(
	    	select
	    		d1.DEPT_NAME,if(d2.DEPT_NAME is null,d1.DEPT_NAME,d2.DEPT_NAME) as parentDeptName,if(d2.DEPT_ID is null,d1.DEPT_ID,d2.DEPT_ID) as parentDeptId,
	    		d1.DEPT_ID as DEPT_ID
	    	from
	    		department d1 left join department d2 on d1.PARENT_ID = d2.DEPT_ID
	    		order by d2.Depart_x
    	) d on d.DEPT_ID = p.CostDepartID
   LEFT JOIN
		(select u.USER_ID,d.DEPT_NAME from user u left join department d on u.DEPT_ID = d.DEPT_ID) r ON r.USER_ID=p.proManagerId
   LEFT JOIN
		 oa_esm_project t  on p.projectId = t.id  and p.projectType = 'esm'
where 1 $allCondition
order by p.BillNo,c.parentOrder,c.orderNum asc
)T order by T.$monthSet desc;
QuerySQL;
// echo $sql;exit();
file_put_contents("D:sql.log", $sql);
GenAttrXmlData($sql, false);