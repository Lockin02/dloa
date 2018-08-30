<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$areaName = $_GET['areaName'];
if($areaName == ';;'){
	$areaNameSql = " ";
}else if($areaName == ''){
	$areaNameSql = " and province = 'none'";
}else{
    $areaNameSql = " and province in ($areaName)";
}
$whereSql =  " where 1=1 and year = '$year' $areaNameSql";
$sql = "and year = '$year' $areaNameSql";

$QuerySQL = <<<QuerySQL
/*全国*/
select year,areaPrincipal,province,customerTypeName,0 as ordnum,type,if(type='金额',"全国","") as typeName,1 as gradeNum,0 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view $whereSql GROUP BY type

 union all
 /*移动+第三方*/
select year,areaPrincipal,province,customerTypeName,0 as ordnum,type,if(type='金额',"移动+第三方","") as typeName,1 as gradeNum,1 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view where (customerTypeName = '移动' or customerTypeName = '运营商-移动集团' or customerTypeName = '第三方' or customerTypeName = '移动+第三方') $sql  GROUP BY type

 union all
  /*联通*/
select year,areaPrincipal,province,customerTypeName,0 as ordnum,type,if(type='金额',"联通","") as typeName,1 as gradeNum,1 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view where (customerTypeName = '联通' or customerTypeName = '运营商-联通集团') $sql  GROUP BY type

 union all
 /*电信*/
select year,areaPrincipal,province,customerTypeName,0 as ordnum,type,if(type='金额',"电信","") as typeName,1 as gradeNum,1 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view where (customerTypeName = '电信' or customerTypeName = '运营商-电信集团') $sql  GROUP BY type

 union all
 /*系统商*/
select year,areaPrincipal,province,customerTypeName,0 as ordnum,type,if(type='金额',"系统商","") as typeName,1 as gradeNum,1 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view where customerTypeName = '系统商' $sql GROUP BY type

 union all
/*明细*/
select * from (

select year,areaPrincipal,if(p.replacename is not null or p.replacename != '',p.replacename,province) as province,'' as customerTypeName,1 as ordnum,type,if(type='金额',if(p.replacename is not null or p.replacename != '',p.replacename,province),"") as typeName,2 as gradeNum,6 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view
  left join oa_sale_chance_viewarea_person p on province=p.proname
  $whereSql GROUP BY type,province
union all
select year,areaPrincipal,if(p.replacename is not null or p.replacename != '',p.replacename,province) as province,customerTypeName,0 as ordnum,type,if(type='金额',customerTypeName,"") as typeName,3 as gradeNum,6 as num,
   sum(Q1_ht) as Q1_ht,sum(Q1_100) as Q1_100,sum(Q1_50) as Q1_50,sum(Q1_25) as Q1_25,sum(Q1_0) as Q1_0,sum(Q1_c) as Q1_c,
   sum(Q2_ht) as Q2_ht,sum(Q2_100) as Q2_100,sum(Q2_50) as Q2_50,sum(Q2_25) as Q2_25,sum(Q2_0) as Q2_0,sum(Q2_c) as Q2_c,
   sum(Q3_ht) as Q3_ht,sum(Q3_100) as Q3_100,sum(Q3_50) as Q3_50,sum(Q3_25) as Q3_25,sum(Q3_0) as Q3_0,sum(Q3_c) as Q3_c,
   sum(Q4_ht) as Q4_ht,sum(Q4_100) as Q4_100,sum(Q4_50) as Q4_50,sum(Q4_25) as Q4_25,sum(Q4_0) as Q4_0,sum(Q4_c) as Q4_c,
   sum(all_ht) as all_ht,sum(all_100) as all_100,sum(all_50) as all_50,sum(all_25) as all_25,sum(all_0) as all_0,sum(all_c) as all_c
 from oa_saleall_view
  left join oa_sale_chance_viewarea_person p on province=p.proname
  $whereSql GROUP BY type,province,customerTypeName
) c
ORDER BY num,CONVERT(province USING gbk),customerTypeName,type desc



QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
