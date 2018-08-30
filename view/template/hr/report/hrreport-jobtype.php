<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
/*$searchYear=$_GET ['searchYear'];
$searchMonth=$_GET ['searchMonth'];

if(!empty($searchYear)){
	$condition.=" and date_format(c.formDate,'%Y')=$searchYear";
}

if(!empty($searchMonth)){
	$condition.=" and date_format(c.formDate,'%m')=$searchMonth";
}*/
//update chenrf 20130521
$startDate=$_GET ['startDate'];
$endDate=$_GET ['endDate'];
$postType=$_GET['postType'];
if(!empty($startDate)){
	$condition.=" and p.formDate >= '$startDate' ";
	$d_condition.=" and d.entryDate >= '$startDate' ";
}

if(!empty($endDate)){
	$condition.=" and p.formDate <= '$endDate'";
	$d_condition.=" and d.entryDate <= '$endDate'";
}
if(!empty($postType)){
	$condition.=" and p.postType = '$postType'";
}
//echo $condition;
$QuerySQL = <<<QuerySQL
				SELECT c.postType,c.postTypeName,c.needNum,round((count(d.postType)/ c.needNum) * 100,4) AS entryPercent,
				sum(IF (d.postType = 'YPZW-WY',IF (substr(d.personLevel, 2, 1) IN (0, 1),1,0),IF (d.positionLevel = 1, 1, 0))) AS level1,
				sum(IF (d.postType = 'YPZW-WY',IF (substr(d.personLevel, 2, 1) = 2,1,0),IF (d.positionLevel = 2, 1, 0))) AS level2,
				sum(IF (d.postType = 'YPZW-WY',IF (substr(d.personLevel, 2, 1) >= 3,1,0),IF (d.positionLevel = 3, 1, 0))) AS level3,
				sum(if(d.postType='YPZW-WY',if(substr(d.personLevel,2,1) in (0,1),1,0),if(d.positionLevel = 1,1,0)))+
				sum(if(d.postType='YPZW-WY',if(substr(d.personLevel,2,1) = 2,1,0),if(d.positionLevel = 2,1,0)))+
				sum(if(d.postType='YPZW-WY',if(substr(d.personLevel,2,1) >= 3,1,0),if(d.positionLevel = 3,1,0))) as entryNum
				FROM(
					select p.postType,p.postTypeName,sum(p.needNum) AS needNum from oa_hr_recruitment_apply p where 1=1 and p.state not in (0,8) $condition
				GROUP BY p.postType) c LEFT JOIN oa_hr_recruitment_entrynotice d ON (c.postType = d.postType AND d.state = '2' $d_condition)
				WHERE 1 = 1 
				GROUP BY c.postType
				ORDER BY c.postType DESC
QuerySQL;

GenAttrXmlData ( $QuerySQL, false );
?>
