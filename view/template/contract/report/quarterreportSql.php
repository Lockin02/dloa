<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$areaCode = $_GET['areaCode'];
$area = $_GET['area'];
$principalId = $_GET['principalId'];
$principal = $_GET['principal'];
if($areaCode != "all"){
	$areaCodeA = " and areaCode = $areaCode";
}else{
	$areaCodeA = "";
}
//负责人
if($principalId != ""){
	$principalId = " and prinvipalId = '$principalId'";
}else{
	$principalId = "";
}
//季度日期
$Q1 = " and c.isTemp = 0 and c.createTime >= '$year-1-1' and c.createTime < '$year-4-1'    $areaCodeA $principalId";
$Q2 = " and c.isTemp = 0 and c.createTime >= '$year-4-1' and c.createTime < '$year-7-1'    $areaCodeA $principalId";
$Q3 = " and c.isTemp = 0 and c.createTime >= '$year-7-1' and c.createTime < '$year-10-1'   $areaCodeA $principalId";
$Q4 = " and c.isTemp = 0 and c.createTime >= '$year-10-1' and c.createTime <= '$year-12-31' $areaCodeA $principalId";
//范围
if($areaCode == "all" && $principalId == ""){
    $limit = "公司";
}
if($areaCode != "all" && $principalId == "" ){
    $limit =  "所属区域($area)";
}else if($areaCode == "all" && $principalId != "" ){
	$limit = "合同负责人($principal)";
}else if($principalId != "" && $areaCode != ""){
    $limit = "所属区域($area)  合同负责人($principal)";
}

$QuerySQL = <<<QuerySQL
select  $year as year,"$limit" as lim,rs.contractTypeName,rs.natureName,
              if(rs.Q1Num is null,0,rs.Q1Num) as Q1Num,if(rs.Q1Money is null,0,rs.Q1Money) as Q1Money,if(rs.Q1TempNum is null,0,rs.Q1TempNum) as Q1TempNum,if(rs.Q1TempMoney is null,0,rs.Q1TempMoney) as Q1TempMoney,
              if(rs.Q2Num is null,0,rs.Q2Num) as Q2Num,if(rs.Q2Money is null,0,rs.Q2Money) as Q2Money,if(rs.Q2TempNum is null,0,rs.Q2TempNum) as Q2TempNum,if(rs.Q2TempMoney is null,0,rs.Q2TempMoney) as Q2TempMoney,
              if(rs.Q3Num is null,0,rs.Q3Num) as Q3Num,if(rs.Q3Money is null,0,rs.Q3Money) as Q3Money,if(rs.Q3TempNum is null,0,rs.Q3TempNum) as Q3TempNum,if(rs.Q3TempMoney is null,0,rs.Q3TempMoney) as Q3TempMoney,
              if(rs.Q4Num is null,0,rs.Q4Num) as Q4Num,if(rs.Q4Money is null,0,rs.Q4Money) as Q4Money,if(rs.Q4TempNum is null,0,rs.Q4TempNum) as Q4TempNum,if(rs.Q4TempMoney is null,0,rs.Q4TempMoney) as Q4TempMoney
from
(
select sys.dataName as natureName,sys.dataCode,"销售合同" as contractTypeName,
             con.Q1Num,con.Q1Money,con.Q1Tempnum,con.Q1TempMoney,
             con.Q2Num,con.Q2Money,con.Q2Tempnum,con.Q2TempMoney,
             con.Q3Num,con.Q3Money,con.Q3Tempnum,con.Q3TempMoney,
             con.Q4Num,con.Q4Money,con.Q4Tempnum,con.Q4TempMoney
 from oa_system_datadict sys left join
 (
     select c.contractTypeName,c.contractNatureName,c.contractNature,
      sum(if(c.winRate = '100%' $Q1,1,0)) as Q1Num,sum(if(c.winRate = '100%' $Q1,c.contractMoney,0)) as Q1Money,sum(if(c.winRate <> '100%' $Q1,1,0)) as Q1TempNum,sum(if(c.winRate <> '100%' $Q1,c.contractMoney,0)) as Q1TempMoney,
      sum(if(c.winRate = '100%' $Q2,1,0)) as Q2Num,sum(if(c.winRate = '100%' $Q2,c.contractMoney,0)) as Q2Money,sum(if(c.winRate <> '100%' $Q2,1,0)) as Q2TempNum,sum(if(c.winRate <> '100%' $Q2,c.contractMoney,0)) as Q2TempMoney,
      sum(if(c.winRate = '100%' $Q3,1,0)) as Q3Num,sum(if(c.winRate = '100%' $Q3,c.contractMoney,0)) as Q3Money,sum(if(c.winRate <> '100%' $Q3,1,0)) as Q3TempNum,sum(if(c.winRate <> '100%' $Q3,c.contractMoney,0)) as Q3TempMoney,
      sum(if(c.winRate = '100%' $Q4,1,0)) as Q4Num,sum(if(c.winRate = '100%' $Q4,c.contractMoney,0)) as Q4Money,sum(if(c.winRate <> '100%' $Q4,1,0)) as Q4TempNum,sum(if(c.winRate <> '100%' $Q4,c.contractMoney,0)) as Q4TempMoney
  from oa_contract_contract c
    where 1=1 and contractTypeName="销售合同" group by c.contractNature
 ) con  on sys.dataCode=con.contractNature where parentCode = 'HTLX-XSHT' group by natureName

union all

select sys.dataName as natureName,sys.dataCode,"服务合同" as contractTypeName,
             con.Q1Num,con.Q1Money,con.Q1Tempnum,con.Q1TempMoney,
             con.Q2Num,con.Q2Money,con.Q2Tempnum,con.Q2TempMoney,
             con.Q3Num,con.Q3Money,con.Q3Tempnum,con.Q3TempMoney,
             con.Q4Num,con.Q4Money,con.Q4Tempnum,con.Q4TempMoney
 from oa_system_datadict sys left join
 (
     select c.contractTypeName,c.contractNatureName,c.contractNature,
      sum(if(c.winRate = '100%' $Q1,1,0)) as Q1Num,sum(if(c.winRate = '100%' $Q1,c.contractMoney,0)) as Q1Money,sum(if(c.winRate <> '100%' $Q1,1,0)) as Q1TempNum,sum(if(c.winRate <> '100%' $Q1,c.contractMoney,0)) as Q1TempMoney,
      sum(if(c.winRate = '100%' $Q2,1,0)) as Q2Num,sum(if(c.winRate = '100%' $Q2,c.contractMoney,0)) as Q2Money,sum(if(c.winRate <> '100%' $Q2,1,0)) as Q2TempNum,sum(if(c.winRate <> '100%' $Q2,c.contractMoney,0)) as Q2TempMoney,
      sum(if(c.winRate = '100%' $Q3,1,0)) as Q3Num,sum(if(c.winRate = '100%' $Q3,c.contractMoney,0)) as Q3Money,sum(if(c.winRate <> '100%' $Q3,1,0)) as Q3TempNum,sum(if(c.winRate <> '100%' $Q3,c.contractMoney,0)) as Q3TempMoney,
      sum(if(c.winRate = '100%' $Q4,1,0)) as Q4Num,sum(if(c.winRate = '100%' $Q4,c.contractMoney,0)) as Q4Money,sum(if(c.winRate <> '100%' $Q4,1,0)) as Q4TempNum,sum(if(c.winRate <> '100%' $Q4,c.contractMoney,0)) as Q4TempMoney
  from oa_contract_contract c
    where 1=1 and contractTypeName="服务合同" group by c.contractNature
 ) con  on sys.dataCode=con.contractNature where parentCode = 'HTLX-FWHT' group by natureName

union all

select sys.dataName as natureName,sys.dataCode,"租赁合同" as contractTypeName,
             con.Q1Num,con.Q1Money,con.Q1Tempnum,con.Q1TempMoney,
             con.Q2Num,con.Q2Money,con.Q2Tempnum,con.Q2TempMoney,
             con.Q3Num,con.Q3Money,con.Q3Tempnum,con.Q3TempMoney,
             con.Q4Num,con.Q4Money,con.Q4Tempnum,con.Q4TempMoney
 from oa_system_datadict sys left join
 (
     select c.contractTypeName,c.contractNatureName,c.contractNature,
      sum(if(c.winRate = '100%' $Q1,1,0)) as Q1Num,sum(if(c.winRate = '100%' $Q1,c.contractMoney,0)) as Q1Money,sum(if(c.winRate <> '100%' $Q1,1,0)) as Q1TempNum,sum(if(c.winRate <> '100%' $Q1,c.contractMoney,0)) as Q1TempMoney,
      sum(if(c.winRate = '100%' $Q2,1,0)) as Q2Num,sum(if(c.winRate = '100%' $Q2,c.contractMoney,0)) as Q2Money,sum(if(c.winRate <> '100%' $Q2,1,0)) as Q2TempNum,sum(if(c.winRate <> '100%' $Q2,c.contractMoney,0)) as Q2TempMoney,
      sum(if(c.winRate = '100%' $Q3,1,0)) as Q3Num,sum(if(c.winRate = '100%' $Q3,c.contractMoney,0)) as Q3Money,sum(if(c.winRate <> '100%' $Q3,1,0)) as Q3TempNum,sum(if(c.winRate <> '100%' $Q3,c.contractMoney,0)) as Q3TempMoney,
      sum(if(c.winRate = '100%' $Q4,1,0)) as Q4Num,sum(if(c.winRate = '100%' $Q4,c.contractMoney,0)) as Q4Money,sum(if(c.winRate <> '100%' $Q4,1,0)) as Q4TempNum,sum(if(c.winRate <> '100%' $Q4,c.contractMoney,0)) as Q4TempMoney
  from oa_contract_contract c
    where 1=1 and contractTypeName="租赁合同" group by c.contractNature
 ) con  on sys.dataCode=con.contractNature where parentCode = 'HTLX-ZLHT' group by natureName

union all

select sys.dataName as natureName,sys.dataCode,"研发合同" as contractTypeName,
             con.Q1Num,con.Q1Money,con.Q1Tempnum,con.Q1TempMoney,
             con.Q2Num,con.Q2Money,con.Q2Tempnum,con.Q2TempMoney,
             con.Q3Num,con.Q3Money,con.Q3Tempnum,con.Q3TempMoney,
             con.Q4Num,con.Q4Money,con.Q4Tempnum,con.Q4TempMoney
 from oa_system_datadict sys left join
 (
     select c.contractTypeName,c.contractNatureName,c.contractNature,
      sum(if(c.winRate = '100%' $Q1,1,0)) as Q1Num,sum(if(c.winRate = '100%' $Q1,c.contractMoney,0)) as Q1Money,sum(if(c.winRate <> '100%' $Q1,1,0)) as Q1TempNum,sum(if(c.winRate <> '100%' $Q1,c.contractMoney,0)) as Q1TempMoney,
      sum(if(c.winRate = '100%' $Q2,1,0)) as Q2Num,sum(if(c.winRate = '100%' $Q2,c.contractMoney,0)) as Q2Money,sum(if(c.winRate <> '100%' $Q2,1,0)) as Q2TempNum,sum(if(c.winRate <> '100%' $Q2,c.contractMoney,0)) as Q2TempMoney,
      sum(if(c.winRate = '100%' $Q3,1,0)) as Q3Num,sum(if(c.winRate = '100%' $Q3,c.contractMoney,0)) as Q3Money,sum(if(c.winRate <> '100%' $Q3,1,0)) as Q3TempNum,sum(if(c.winRate <> '100%' $Q3,c.contractMoney,0)) as Q3TempMoney,
      sum(if(c.winRate = '100%' $Q4,1,0)) as Q4Num,sum(if(c.winRate = '100%' $Q4,c.contractMoney,0)) as Q4Money,sum(if(c.winRate <> '100%' $Q4,1,0)) as Q4TempNum,sum(if(c.winRate <> '100%' $Q4,c.contractMoney,0)) as Q4TempMoney
  from oa_contract_contract c
    where 1=1 and contractTypeName="研发合同" group by c.contractNature
 ) con  on sys.dataCode=con.contractNature where parentCode = 'HTLX-YFHT' group by natureName
) rs
group by rs.natureName ,rs.contractTypeName order by rs.contractTypeName
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
