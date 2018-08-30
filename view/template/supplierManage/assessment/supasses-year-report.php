<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginYear=$_GET['beginYear'];
$endYear=$_GET['endYear'];
$suppId=$_GET['suppId'];
$condition="";
if(!empty($beginYear)){
	$condition.= ' and date_format(c.formDate,"%Y") >= "'.$beginYear.'" ';
}
if(!empty($endYear)){
	$condition.= ' and date_format(c.formDate,"%Y") <= "'.$endYear.'" ';
}
if(!empty($suppId)){
	$condition.= ' and c.suppId= "'.$suppId.'" ';
}
$QuerySQL = <<<QuerySQL
select c.id ,c.suppId ,c.suppName ,c.formCode,c.formDate,date_format(c.formDate,'%Y') as formYear ,c.assessType,c.assessTypeName ,c.assessName ,
c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct ,c.isFirst ,c.parentId,c.assesManId ,c.assesManName ,c.totalNum ,
c.assesState ,c.suppGrade  ,c.yearTotal,c.yearSupGrade,c.ExaStatus,c.ExaDT,
(select sum(d.assesScore)  from oa_supp_suppasses_detail d where d.parentId=c.id and d.assesProName="品质评价" ) as quality,
 (select sum(d.assesScore)  from oa_supp_suppasses_detail d where d.parentId=c.id and d.assesProName="交期评价" ) as delivery,
 (select sum(d.assesScore) from oa_supp_suppasses_detail d  where d.parentId=c.id and d.assesProName="服务评价" ) as service,
 (select sum(d.assesScore)  from oa_supp_suppasses_detail d  where d.parentId=c.id and d.assesProName="价格评价" ) as price
  from oa_supp_suppasses c   where c.assessType="gysnd" and c.ExaStatus='完成' $condition
group by c.id
order by id DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
