<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginYear=$_GET['beginYear'];
$endYear=$_GET['endYear'];
$beginQuarter=$_GET['beginQuarter'];
$endQuarter=$_GET['endQuarter'];
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
if(!empty($beginQuarter)){
	switch($beginQuarter){
		case '1';$condition.= ' and date_format(c.formDate,"%c") >= 1 ';break;
		case '2';$condition.= ' and date_format(c.formDate,"%c") >= 4 ';break;
		case '3';$condition.= ' and date_format(c.formDate,"%c") >= 7 ';break;
		case '4';$condition.= ' and date_format(c.formDate,"%c") >= 10 ';break;

	}
}if(!empty($endQuarter)){
	switch($endQuarter){
		case '1';$condition.= ' and date_format(c.formDate,"%c") <= 3 ';break;
		case '2';$condition.= ' and date_format(c.formDate,"%c") <= 6 ';break;
		case '3';$condition.= ' and date_format(c.formDate,"%c") <= 9 ';break;
		case '4';$condition.= ' and date_format(c.formDate,"%c") <= 12 ';break;
	}
}
$QuerySQL = <<<QuerySQL
select c.id ,c.suppId ,c.suppName ,c.formCode,c.formDate,date_format(c.formDate,'%Y') as formYear ,c.assessType,c.assessTypeName ,c.assessId ,c.assessName ,c.assessCode ,
c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct ,c.suppSource ,c.isFirst ,c.parentId,c.assesManId ,c.assesManName ,c.totalNum ,
c.assesState ,c.suppGrade ,c.ExaStatus,c.ExaDT,
case date_format(c.formDate,'%c')
   when '1' then '第一季度'
   when '2' then '第一季度'
   when '3' then '第一季度'
   when '4' then '第二季度'
   when '5' then '第二季度'
   when '6' then '第二季度'
   when '7' then '第三季度'
   when '8' then '第三季度'
   when '9' then '第三季度'
   when '10' then '第四季度'
   when '11' then '第四季度'
   when '12' then '第四季度'
   else "" end
 as formQuarter,
(select sum(d.assesScore)  from oa_supp_suppasses_detail d where d.parentId=c.id and d.assesProName="品质评价" ) as quality,
 (select sum(d.assesScore)  from oa_supp_suppasses_detail d where d.parentId=c.id and d.assesProName="交期评价" ) as delivery,
 (select sum(d.assesScore) from oa_supp_suppasses_detail d  where d.parentId=c.id and d.assesProName="服务评价" ) as service,
 (select sum(d.assesScore)  from oa_supp_suppasses_detail d  where d.parentId=c.id and d.assesProName="价格评价" ) as price
  from oa_supp_suppasses c   where c.assessType="gysjd"  and c.ExaStatus='完成' $condition
group by c.id
order by id DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
