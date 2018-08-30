<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
/*$searchYear=$_GET ['searchYear'];
$searchMonth=$_GET ['searchMonth'];

if(!empty($searchYear)){
	$condition.=" and date_format(c.entryDate,'%Y')=$searchYear";
}

if(!empty($searchMonth)){
	$condition.=" and date_format(c.entryDate,'%m')=$searchMonth";
}*/

//update chenrf 20130521
$startDate=$_GET ['startDate'];
$endDate=$_GET ['endDate'];
$createName=$_GET ['createName'];
if(!empty($startDate)){
	$condition.=" and c.entryDate >= '$startDate' ";
}

if(!empty($endDate)){
	$condition.=" and c.entryDate <= '$endDate' ";
}
if(!empty($createName)){
	$condition.=" and c.createName like CONCAT('%','".$createName."','%')";
}
//print_r ($condition);
$QuerySQL = <<<QuerySQL
SELECT
	c.createName,
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) in (0,1),1,0),if(c.positionLevel = 1,1,0))) +  
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) = 2,1,0),if(c.positionLevel = 2,1,0))) + 
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) >= 3,1,0),if(c.positionLevel = 3,1,0))) AS sumNum,
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) in (0,1),1,0),if(c.positionLevel = 1,1,0))) AS level1num,
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) = 2,1,0),if(c.positionLevel = 2,1,0))) AS level2num,
	sum(
		if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) >= 3,1,0),if(c.positionLevel = 3,1,0))) AS level3num,
	COUNT(
		IF (c.parentCode = "", TRUE, NULL)) AS directEntry,
	COUNT(
		IF (DATEDIFF(d.quitDate, d.becomeDate) < 0,TRUE,NULL)) AS leaveUser
	FROM
	oa_hr_recruitment_entrynotice c
	LEFT JOIN oa_hr_personnel d ON c.userNo = d.userNo
	WHERE
	c.state = 2 $condition
	GROUP BY
	c.createName
	ORDER BY
	c.createName DESC
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
