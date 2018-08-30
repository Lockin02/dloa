<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
extract($_GET);
$userName=$_GET ['userName'];
$deptName=$_GET ['deptName'];
$hrJobName=$_GET ['hrJobName'];
$createName=$_GET ['createName'];

if(!empty($userName)){
	$condition.=" and c.userName like CONCAT('%','".$userName."','%')";
}

if(!empty($deptName)){
	$condition.=" and c.deptName like CONCAT('%','".$deptName."','%')";
}

if(!empty($hrJobName)){
	$condition.=" and c.hrJobName like CONCAT('%','".$hrJobName."','%')";
}

if(!empty($createName)){
	$condition.=" and c.createName like CONCAT('%','".$createName."','%')";
}
if(!empty($formDate)){
	$condition.=" and date_format(c.formDate,'%Y-%m' ) = '$formDate'";   //入职时间
}
if(!empty($postTypeName)){
	$condition.=" and c.postTypeName like CONCAT('%','".$postTypeName."','%')";  //岗位
}
if(!empty($positionLevelId)){ //级别
	if ($postTypeName != '网优') {
		$condition.=" and c.positionLevel like CONCAT('%','".$positionLevelId."','%')";
	}else {
		$condition.=" and c.personLevel like CONCAT('%','".$positionLevelId."','%')";
	}
}
if(!empty($workPlace)){
	$condition.=" and ri.workPlace like CONCAT('%','".$workPlace."','%')";  //工作地点
}
if(!empty($applyTypeName)){
	$condition.=" and a.addType like CONCAT('%','".$applyTypeName."','%')";  //职位需求类型
}
$QuerySQL = <<<QuerySQL
select c.formDate ,c.invitationId ,c.interviewType ,c.userNo ,c.userAccount ,c.userName ,c.sex ,c.phone ,c.email ,c.positionsName ,c.developPositionName ,c.deptName ,c.projectGroup ,c.useHireTypeName ,c.useJobName ,c.useJobId ,c.useAreaName ,c.useTrialWage ,c.useFormalWage ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.hrRequire ,c.addType ,c.hrSourceType1Name ,c.hrSourceType2Name ,c.hrJobName ,c.hrIsManageJob ,c.socialPlace ,c.entryDate ,c.state ,c.entryRemark ,c.createTime ,c.sysCompanyName ,c.postTypeName ,date_format(c.entryDate,'%Y-%m' ) as entryMonth ,a.addType as applyType ,a.recruitManName ,a.assistManName  ,c.createName ,
if (c.postTypeName <> '网优',
	case c.positionLevel
		when '1' then '低级'
		when '2' then '中级'
		when '3' then '高级'
		else '' end, '')
as positionLevel,
if (c.postTypeName = '网优' ,c.personLevel ,'') as personLevel,
case
	when TIMESTAMPDIFF(MONTH,c.entryDate,p.quitDate)>=3 then '是'
	when TIMESTAMPDIFF(MONTH,c.entryDate,p.quitDate)<0 then ''
	when TIMESTAMPDIFF(MONTH,c.entryDate,p.quitDate)<3 then '否'
	else '' end
 as isFull3Month
,if(p.quitDate='0000-00-00','',p.quitDate) as quitDate
,ri.workPlace
from oa_hr_recruitment_entrynotice c
left join oa_hr_recruitment_interview i on (i.id=c.parentId)
left join oa_hr_personnel p on (c.userNo=p.userNo)
left join oa_hr_recruitment_apply a on (a.id=i.parentId and i.interviewType=1)
left join oa_hr_recruitment_invitation ri on ri.id=c.invitationId
 where c.staffFileState=1  $condition
group by c.id
order by c.id
DESC
QuerySQL;

GenAttrXmlData ( $QuerySQL, false );
?>
