<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
extract($_GET);

if(!empty($startDate)){
	$condition.=" and c.assignedDate >= '$startDate' ";
}

if(!empty($endDate)){
	$condition.=" and c.assignedDate <= '$endDate'";
}
if(!empty($postType)){
	$condition.=" and c.postType = '$postType'";
}

if(!empty($deptName)){
	$condition.=" and c.deptName like CONCAT('%','".$deptName."','%')";
}
if(!empty($projectGroup)){
	$condition.=" and c.projectGroup like CONCAT('%','".$projectGroup."','%')";
}
if(!empty($applyType)){
	$condition.=" and c.AddTypeCode ='$applyType'";
}
if(!empty($recruitManName)){
	$condition.=" and c.recruitManName like CONCAT('%','".$recruitManName."','%')";
}
//echo $condition;
$QuerySQL = <<<QuerySQL
SELECT
	c.id,
	c.formCode,
	CASE c.state
WHEN '1' THEN
	'未下达'
WHEN '2' THEN
	'招聘中'
WHEN '3' THEN
	'暂停'
WHEN '4' THEN
	'完成'
WHEN '5' THEN
	'关闭'
WHEN '6' THEN
	'挂起'
WHEN '7' THEN
	'取消'
WHEN '8' THEN
	'提交'
ELSE
	''
END AS state,
 c.ExaStatus,
 c.addType,
 c.formManName,
 c.resumeToName,
 c.deptName,
 f.levelflag,
 IF(f.deptIdO<>'',f.deptIdO,c.deptId) AS deptOId ,
 IF(f.deptNameO<>'',f.deptNameO,c.deptName) AS deptNameO ,
 f.deptIdS AS deptSId ,
 f.deptNameS AS deptNameS ,
 f.deptIdT AS deptTId ,
 f.deptNameT AS deptNameT ,
 f.deptIdF AS deptFId ,
 f.deptNameF AS deptNameF,
 c.postTypeName,
 c.positionName,
 c.positionLevel AS positionLevel,
 c.recruitManName,
 c.assistManName,
 c.recruitManId,
 c.assignedDate,
 c.hopeDate,
 c.needNum,
 IFNULL(c.entryNum, 0) AS entryNum,
 IFNULL(c.beEntryNum, 0) AS beEntryNum,
 c.stopCancelNum,
 IFNULL(c.needNum, 0) - IFNULL(c.entryNum, 0) - IFNULL(c.beEntryNum, 0) - c.stopCancelNum AS ingtryNum,
 c.workPlace,
 c.ExaDT,
 d.createTime,
 GROUP_CONCAT(d.userName) AS userName,
 COUNT(
	case
	when (d.jobLevel=2 or d.jobLevel=3) and (d.createTime!='' or d.createTime is not null) and  ( c.assignedDate!='' or  c.assignedDate is not null) then IF(DATEDIFF( d.createTime, c.assignedDate)>30,NULL,TRUE)
when (d.createTime!='' or d.createTime is not null)  and  ( c.assignedDate!='' or  c.assignedDate is not null) then IF(DATEDIFF( d.createTime, c.assignedDate)>15,NULL,TRUE)
else null
end
) AS timelyNum,
 COUNT(
	case
	when (d.jobLevel=2 or d.jobLevel=3) and (d.createTime!='' or d.createTime is not null) then IF(DATEDIFF( d.createTime, c.assignedDate)>30,TRUE,NULL)
when(d.createTime!='' or d.createTime is not null) then IF(DATEDIFF( d.createTime, c.assignedDate)>15,TRUE,NULL)
else null
end
) AS notTimelyNum,
CASE
when (d.jobLevel=2 or d.jobLevel=3) then IF(DATEDIFF( date(NOW()), c.assignedDate)>30,0,c.needNum-c.entryNum-c.beEntryNum)
when(c.assignedDate!='' or c.assignedDate is not null) then IF(DATEDIFF( date(NOW()), c.assignedDate)>15,0,c.needNum-c.entryNum-c.beEntryNum)
else 0
end
as timelyNotNum
FROM
	oa_hr_recruitment_apply c
LEFT JOIN (
	SELECT
		id,
		userName,
		sourceCode,
		DATE_FORMAT(createTime, '%Y-%m-%d') AS createTime,
		CASE postType
		WHEN 'YPZW-WY' THEN
			(
				CASE SUBSTRING(personLevel, 1, 1)
				WHEN 'A' OR 'B' OR 'C' THEN SUBSTRING(personLevel, 2, 1)
				WHEN '1'  THEN 1
				WHEN '2' OR '3' THEN 2
				WHEN '4' OR '5'  THEN 3
				WHEN '无' THEN	1
				ELSE 1
				END
			)
		ELSE positionLevel
		END
		as jobLevel
	FROM
		oa_hr_recruitment_entrynotice
 where state in(1,2)
	ORDER BY
		createTime
) d ON (c.formCode = d.sourceCode)
LEFT JOIN department_view f on (c.deptId = f.deptId)
WHERE
	c.state IN (
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8'
	) and (c.recruitManId!='' or c.recruitManId is not NULL)  $condition
GROUP BY
	c.id
ORDER BY
	c.recruitManId DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
