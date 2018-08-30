<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
/*$searchYear=$_GET ['searchYear'];
$searchMonth=$_GET ['searchMonth'];

if(!empty($searchYear)){
	$yearMonth=$searchYear.$searchMonth;
	$condition.=" and date_format(c.entryDate,'%Y%m')=".$yearMonth;
}*/
$startDate=$_GET ['startDate'];
$endDate=$_GET ['endDate'];
$createName = $_GET['createName'];
//$hrreport = new controller_base_action();
//$datas = $hrreport->getDatadicts ( $parentCodeArr ,NULL);

$hrSourceType1Name=$_GET['hrSourceType1Name'];
if(!empty($startDate)){
	$condition.=" and c.entryDate >='$startDate'";
}
if(!empty($endDate)){
	$condition.=" and c.entryDate <='$endDate'";
}
if(!empty($hrSourceType1Name)){
	$condition.=" and c.hrSourceType1Name ='$hrSourceType1Name'";
}
if(isset($createName)){
	$condition.=" and c.createName like CONCAT('%','".$createName."','%')";
}
//echo $condition;
/*$QuerySQL = <<<QuerySQL
select c.hrSourceType1Name,count(*) as sumNum,sum(if(c.positionLevel=1,1,0)) as level1num,sum(if(c.positionLevel=2,1,0)) as level2num,
sum(if(c.positionLevel=3,1,0)) as level3num,re.totalNum,round((count(*)  / re.totalNum)*100,4) as entryPercent,
round((sum(if(c.positionLevel=1,1,0)) / count(*))*100,4) as level1Percent,
round((sum(if(c.positionLevel=2,1,0)) / count(*))*100,4) as level2Percent,
round((sum(if(c.positionLevel=3,1,0)) / count(*))*100,4) as level3Percent
 from oa_hr_recruitment_entrynotice c
left join
(select count(*) as totalNum,date_format(e.entryDate,"%Y%m") as searchYearMonth from oa_hr_recruitment_entrynotice e group by date_format(e.entryDate,"%Y%m"))re on re.searchYearMonth=date_format(c.entryDate,"%Y%m")
where 1=1 $condition
group by c.hrSourceType1
order by c.hrSourceType1
DESC
QuerySQL;*/
$QuerySQL = <<<QuerySQL
				select IFNULL(c.hrSourceType1Name,'Лузм') AS hrSourceType1Name,
					count(*) as sumNum,round((count(*) / (select count(c.hrSourceType1Name)
 					from oa_hr_recruitment_entrynotice c where 1=1 and staffFileState=1 $condition )) * 100,4) AS entryPercent,	
 					sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) in (0,1),1,0),if(c.positionLevel = 1,1,0))) AS level1num,
					sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) = 2,1,0),if(c.positionLevel = 2,1,0))) AS level2num,
					sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) >=3,1,0),if(c.positionLevel = 3,1,0))) AS level3num,
					round((sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) in (0,1),1,0),if(c.positionLevel = 1,1,0))) / count(*)) * 100,4) AS level1Percent,
					round((sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) = 2,1,0),if(c.positionLevel = 2,1,0))) / count(*)) * 100,4) AS level2Percent,
					round((sum(if(c.postType='YPZW-WY',if(substr(c.personLevel,2,1) >=3,1,0),if(c.positionLevel = 3,1,0))) / count(*)) * 100,4) AS level3Percent
 					from oa_hr_recruitment_entrynotice c
					where 1=1 and staffFileState=1 $condition
					group by c.hrSourceType1Name
					with rollup
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
