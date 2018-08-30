<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
$recommendName=$_GET ['recommendName'];
$isRecommendName=$_GET ['isRecommendName'];
$positionName=$_GET ['positionName'];
$recruitManName=$_GET ['recruitManName'];

if(!empty($recommendName)){
	$condition.=" and c.recommendName like CONCAT('%','".$recommendName."','%')";
}

if(!empty($isRecommendName)){
	$condition.=" and c.isRecommendName like CONCAT('%','".$isRecommendName."','%')";
}

if(!empty($positionName)){
	$condition.=" and c.positionName like CONCAT('%','".$positionName."','%')";
}

if(!empty($recruitManName)){
	$condition.=" and c.recruitManName like CONCAT('%','".$recruitManName."','%')";
}

//echo $condition;
$QuerySQL = <<<QuerySQL
select c.id ,c.recommendName ,c.isRecommendName ,c.positionName ,c.formDate ,c.recruitManName ,c.assistManName ,
 case c.state
	when '1' then 'δ����'
	when '2' then '�ѷ���'
	when '3' then '��̭'
	when '4' then '������'
	when '5' then '����ְ'
	when '6' then '�ر�'
	when '7' then '������'
	when '8' then '����ְ'
	when '9' then '������ְ'
 else '' end
	as state ,
 c.recommendReason ,c.closeRemark ,
 p.becomeDate ,p.realBecomeDate ,p.quitDate
 from oa_hr_recruitment_recommend c
 left join oa_hr_recruitment_entrynotice e on (c.id=e.recommendId and c.state in(2,5,8,9) and e.state<>0)
 left join oa_hr_personnel p on (e.userAccount=p.userAccount)
 where c.state!=0 $condition
 group by c.id
 order by c.id
 DESC
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
