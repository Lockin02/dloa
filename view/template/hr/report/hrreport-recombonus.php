<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
$recommendName=$_GET ['recommendName'];
$isRecommendName=$_GET ['isRecommendName'];
$jobName=$_GET['jobName'];
$developPositionName=$_GET['developPositionName'];
if(!empty($recommendName)){
	$condition.=" and c.recommendName like CONCAT('%','".$recommendName."','%')";
}

if(!empty($isRecommendName)){
	$condition.=" and c.isRecommendName like CONCAT('%','".$isRecommendName."','%')";
}

if(!empty($jobName)){
	$condition.=" and c.jobName like CONCAT('%','".$jobName."','%')";
}

if(!empty($developPositionName)){
	$condition.=" and c.developPositionName like CONCAT('%','".$developPositionName."','%')";
}

//echo $condition;
$QuerySQL = <<<QuerySQL
select c.id ,c.formCode ,c.formDate ,c.formManName ,c.isRecommendName ,c.developPositionName ,c.jobName ,c.entryDate ,c.becomeDate ,c.beBecomDate ,c.recommendName ,c.recommendReason ,c.state  ,c.bonus ,c.bonusProprotion ,c.firstGrantDate ,c.firstGrantBonus ,c.secondGrantDate ,c.secondGrantBonus ,c.remark ,c.ExaStatus ,
round(c.bonus * c.bonusProprotion/100 ,2) as isBonus
 from oa_hr_recommend_bonus c
 where c.ExaStatus='Íê³É' $condition
group by c.id
order by c.id
DESC
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
