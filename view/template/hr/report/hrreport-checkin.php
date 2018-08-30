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
if (c.postTypeName <> '����',
	case c.positionLevel
		when '1' then '����'
		when '2' then '�м�'
		when '3' then '�߼�'
		else '' end, '')
as positionLevel,
if (c.postTypeName = '����' ,c.personLevel ,'') as personLevel,
	if(c.staffFileState=1 ,
		if(p.employeesState!='YGZTZZ','����ְ','����ְ'),
			case c.state
		when '1' then '����ְ'
		when '2' then '����ְ'
		when '3' then '������ְ'
		else '' end)
as state
,c.isLocal ,c.sysCompanyName ,c.createName ,c.assistManName ,c.cancelReason ,c.departReason
,CONCAT('���ã�' ,IFNULL(i.levelSubsidy ,''),',ת����' ,IFNULL(i.levelSubsidyFormal ,'')) as levelSubsidy
,CONCAT('���ã�' ,IFNULL(i.areaSubsidy ,''),',ת����' ,IFNULL(i.areaSubsidyFormal ,'')) as areaSubsidy
,CONCAT('���ã�' ,IFNULL(i.travelBonus ,''),',ת����' ,IFNULL(i.travelBonusFormal ,'')) as travelBonus
,CONCAT('���ã�' ,IFNULL(i.tripSubsidy ,''),',ת����' ,IFNULL(i.tripSubsidyFormal ,'')) as tripSubsidy
,CONCAT('���ã�' ,IFNULL(i.bonusLimit ,''),',ת����' ,IFNULL(i.bonusLimitFormal ,'')) as bonusLimit
,CONCAT('���ã�' ,IFNULL(i.otherSubsidy ,''),',ת����' ,IFNULL(i.otherSubsidyFormal ,'')) as otherSubsidy
,CONCAT('���ã�' ,IFNULL(i.manageSubsidy ,''),',ת����' ,IFNULL(i.manageSubsidyFormal ,'')) as manageSubsidy
,CONCAT('���ã�' ,IFNULL(i.accommodSubsidy ,''),',ת����' ,IFNULL(i.accommodSubsidyFormal ,'')) as accommodSubsidy
,CONCAT('���ã�' ,IFNULL(i.phoneSubsidy ,''),',ת����' ,IFNULL(i.phoneSubsidyFormal ,'')) as phoneSubsidy
,CONCAT('���ã�' ,IFNULL(i.computerSubsidy ,''),',ת����' ,IFNULL(i.computerSubsidyFormal ,'')) as computerSubsidy
,CONCAT('���ã�' ,IFNULL(i.workBonus ,''),',ת����' ,IFNULL(i.workBonusFormal ,'')) as workBonus
,CONCAT('���ã�' ,IFNULL(i.bonusCoefficient ,''),',ת����' ,IFNULL(i.bonusCoefficientFormal ,'')) as bonusCoefficient
,i.allTrialWage ,i.allFormalWage,
if(i.useHireType='HRLYXX-03' ,
	CONCAT(i.internshipSalaryType , '  ',i.internshipSalary),
	''
) as internshipSalary
,CONCAT(i.workProvince ,'-' ,i.workCity) as workPlace
,ri.interviewDate ,
case re.education
	when 'HRXLXX' then 'Сѧ'
	when 'HRXLCZ' then '����'
	when 'HRXLGZ' then '����'
	when 'HRXLDZ' then '��ר'
	when 'HRXLBK' then '����'
	when 'HRXLYJS' then '�о���'
	when 'HRXLBS' then '��ʿ'
	when 'HRXLZZ' then '��ר'
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
