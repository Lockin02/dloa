<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$condition = " ";
extract($_GET);

if(!empty($postType)){
	$condition.=" and c.postType = '$postType'";
}
if(!empty($deptName)){
	$condition.=" and f.pdeptname LIKE CONCAT('%','".$deptName."','%')";
}
if(!empty($state)){
	$condition.=" and c.state = '$state' ";
}

if(!empty($workPlace)){
	$condition.=" and c.workPlace LIKE CONCAT('%','".$workPlace."','%')";
}
if(!empty($positionName)){
	$condition.=" and c.positionName LIKE CONCAT('%','".$positionName."','%')";
}
if(!empty($positionLevel)){
	$condition.=" and c.positionLevel LIKE CONCAT('%','".$positionLevel."','%')";
}

if(!empty($projectGroup)){
	$condition.=" and c.projectGroup LIKE CONCAT('%','".$projectGroup."','%')";
}
if(!empty($applyType)){
	$condition.=" and c.AddTypeCode ='$applyType'";
}
if(!empty($resumeToName)){
	$condition.=" and c.resumeToName LIKE CONCAT('%','".$resumeToName."','%')";
}

if(!empty($recruitManName)){
	$condition.=" and c.recruitManName LIKE CONCAT('%','".$recruitManName."','%')";
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
 c.formManName,
 c.resumeToName,

IF (
	f.deptIdO <> '',
	f.deptIdO,
	c.deptId
) AS deptOId,

IF (
	f.deptNameO <> '',
	f.deptNameO,
	c.deptName
) AS deptNameO,
 f.deptIdS AS deptSId,
 f.deptNameS AS deptNameS,
 f.deptIdT AS deptTId,
 f.deptNameT AS deptNameT,
 f.deptIdF AS deptFId,
 f.deptNameF AS deptNameF,
 c.workPlace,
 c.postTypeName,
 c.positionName,
 c.developPositionName,
 c.network,
 c.device,
 c.positionLevel,
 c.projectGroup,

IF (c.isEmergency = 1, '是', '否') AS isEmergency,
 c.tutor,
 c.computerConfiguration,
 c.formDate,

IF (
	c.ExaStatus = '完成',
	c.ExaDT,
	''
) AS ExaDT,

IF (
	c.assignedDate = '0000-00-00',
	'',
	c.assignedDate
) AS assignedDate,
 DATE_FORMAT(MIN(d.createTime), '%Y-%m-%d') AS createTime,
 MIN(d.entrydate) AS entrydate,
 DATE_FORMAT(MIN(d.createTime), '%Y-%m-%d') AS firstOfferTime,
 DATE_FORMAT(MAX(d.createTime), '%Y-%m-%d') AS lastOfferTime,
 c.addType,
 c.needNum,
 c.entryNum,
 c.beEntryNum,
 c.stopCancelNum,
 (
	c.needNum -
	IF (c.entryNum > 0, c.entryNum, 0) -
	IF (c.beEntryNum > 0, c.beEntryNum, 0) - c.stopCancelNum
) AS ingtryNum,
 c.recruitManName,
 c.assistManName,
 CONCAT_WS(
	',',
	GROUP_CONCAT(d.userName),
	c.employName
) AS userName,
 c.applyReason,
 c.workDuty,
 c.jobRequire,
 c.keyPoint,
 c.attentionMatter,
 c.leaderLove,
 c.applyRemark
FROM
	oa_hr_recruitment_apply c
LEFT JOIN department_view f ON c.deptId = f.deptId
LEFT JOIN (
	SELECT
		e.userName,
		e.sourceCode,
		e.createTime,
		e.entryDate,
		e.state
	FROM
		oa_hr_recruitment_entrynotice e
	WHERE
		e.state IN (1, 2)
	ORDER BY
		e.createTime ASC
) d ON c.formCode = d.sourceCode
WHERE
	c.state <> 0
	$condition
GROUP BY
	c.id
ORDER BY
	c.id DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
