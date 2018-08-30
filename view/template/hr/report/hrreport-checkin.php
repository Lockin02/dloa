<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
$postTypeName = $_GET ['postTypeName'];
$deptName = $_GET ['deptName'];
$positionName = $_GET ['positionName'];
extract($_GET);
if(!empty($postType)){
	$condition .= " and c.postType='$postType'";
}
if(!empty($deptName)){
	$condition .= " and c.deptName like CONCAT('%','".$deptName."','%')";
}
if(!empty($hrJobName)){
	$condition .= " and c.hrJobName like CONCAT('%','".$hrJobName."','%')";
}
if(!empty($developPositionName)){
	$condition .= " and c.developPositionName like CONCAT('%','".$developPositionName."','%')";
}
if(!empty($userName)){
	$condition .= " and c.userName like CONCAT('%','".$userName."','%')";
}
if(!empty($applyType)){
	$condition .= " and c.addTypeCode ='$applyType'";
}
if(!empty($projectGroup)){
	$condition .= " and c.projectGroup like CONCAT('%','".$projectGroup."','%')";
}
if(!empty($workPlace)){
	$condition .= " and (i.workProvince like CONCAT('%','".$workPlace."','%') or i.workCity like CONCAT('%','".$workPlace."','%'))";
}
if(!empty($hrSourceType1Name)){
	$condition .= " and c.hrSourceType1Name like CONCAT('%','".$hrSourceType1Name."','%')";
}
if(!empty($hrSourceType2Name)){
	$condition .= " and c.hrSourceType2Name like CONCAT('%','".$hrSourceType2Name."','%')";
}
if(!empty($sysCompanyName)){
	$condition .= " and c.sysCompanyName like CONCAT('%','".$sysCompanyName."','%')";
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select c.formDate ,c.invitationId ,c.interviewType ,c.userNo ,c.userAccount ,c.userName ,
c.sex ,c.phone ,c.email ,c.positionsName ,c.developPositionName ,c.deptName ,c.projectGroup ,c.useHireTypeName ,
c.useJobName ,c.useJobId ,c.useTrialWage ,c.useFormalWage ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.hrRequire ,c.addType ,
c.hrSourceType1Name ,c.hrSourceType2Name ,c.hrJobName ,c.hrIsManageJob ,c.socialPlace ,c.entryDate ,c.entryRemark ,c.createTime ,c.postTypeName ,
c.sourceCode ,
if (c.postTypeName <> '网优',
	case c.positionLevel
		when '1' then '初级'
		when '2' then '中级'
		when '3' then '高级'
		else '' end, '')
as positionLevel,
if (c.postTypeName = '网优' ,c.personLevel ,'') as personLevel,
	if(c.staffFileState=1 ,
		if(p.employeesState!='YGZTZZ','已离职','已入职'),
			case c.state
		when '1' then '待入职'
		when '2' then '已入职'
		when '3' then '放弃入职'
		else '' end)
as state
,c.isLocal ,c.sysCompanyName ,c.createName ,c.assistManName ,c.cancelReason ,c.departReason
,CONCAT('试用：' ,IFNULL(i.levelSubsidy ,''),',转正：' ,IFNULL(i.levelSubsidyFormal ,'')) as levelSubsidy
,CONCAT('试用：' ,IFNULL(i.areaSubsidy ,''),',转正：' ,IFNULL(i.areaSubsidyFormal ,'')) as areaSubsidy
,CONCAT('试用：' ,IFNULL(i.travelBonus ,''),',转正：' ,IFNULL(i.travelBonusFormal ,'')) as travelBonus
,CONCAT('试用：' ,IFNULL(i.tripSubsidy ,''),',转正：' ,IFNULL(i.tripSubsidyFormal ,'')) as tripSubsidy
,CONCAT('试用：' ,IFNULL(i.bonusLimit ,''),',转正：' ,IFNULL(i.bonusLimitFormal ,'')) as bonusLimit
,CONCAT('试用：' ,IFNULL(i.otherSubsidy ,''),',转正：' ,IFNULL(i.otherSubsidyFormal ,'')) as otherSubsidy
,CONCAT('试用：' ,IFNULL(i.manageSubsidy ,''),',转正：' ,IFNULL(i.manageSubsidyFormal ,'')) as manageSubsidy
,CONCAT('试用：' ,IFNULL(i.accommodSubsidy ,''),',转正：' ,IFNULL(i.accommodSubsidyFormal ,'')) as accommodSubsidy
,CONCAT('试用：' ,IFNULL(i.phoneSubsidy ,''),',转正：' ,IFNULL(i.phoneSubsidyFormal ,'')) as phoneSubsidy
,CONCAT('试用：' ,IFNULL(i.computerSubsidy ,''),',转正：' ,IFNULL(i.computerSubsidyFormal ,'')) as computerSubsidy
,CONCAT('试用：' ,IFNULL(i.workBonus ,''),',转正：' ,IFNULL(i.workBonusFormal ,'')) as workBonus
,CONCAT('试用：' ,IFNULL(i.bonusCoefficient ,''),',转正：' ,IFNULL(i.bonusCoefficientFormal ,'')) as bonusCoefficient
,i.allTrialWage ,i.allFormalWage,
if(i.useHireType='HRLYXX-03' ,
	CONCAT(i.internshipSalaryType , '  ',i.internshipSalary),
	''
) as internshipSalary
,CONCAT(i.workProvince ,'-' ,i.workCity) as workPlace
,ri.interviewDate ,
case re.education
	when 'HRXLXX' then '小学'
	when 'HRXLCZ' then '初中'
	when 'HRXLGZ' then '高中'
	when 'HRXLDZ' then '大专'
	when 'HRXLBK' then '本科'
	when 'HRXLYJS' then '研究生'
	when 'HRXLBS' then '博士'
	when 'HRXLZZ' then '中专'
	else '' end
 as education
,CONCAT(if(a.developPositionName!='' ,CONCAT(a.developPositionName,',') ,''), if(a.network!='', CONCAT(a.network,',') ,'') ,a.device) as positionNote
 from oa_hr_recruitment_entrynotice c
 left join oa_hr_recruitment_interview i on i.id=c.parentId
 left join oa_hr_recruitment_invitation ri on ri.id=c.invitationId
 left join oa_hr_recruitment_employment e on e.id=c.applyId
 left join oa_hr_recruitment_apply a on a.id=c.sourceId
 left join oa_hr_recruitment_resume re on re.id=c.resumeId
 left join oa_hr_personnel p on p.userNo=c.userNo
 where  1=1 $condition
 group by c.id
 order by c.id
 DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
