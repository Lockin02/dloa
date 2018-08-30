<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$beginDate = $_GET['beginDate'] ;//开始日期
$endDate=$_GET['endDate'];

$searchCondition="c.deprTime between '".$beginDate."' and '".$endDate."'";

//$searchCon="";
//if( isset($_GET['years'])&&$_GET['years']!=''){
//$years= $_GET['years'] ;//年度
//$searchCon=" and years ='".$years."'";
//}


$QuerySQL = <<<QuerySQL
	select c.depr,
			c.deprRate,
			c.deprRemain,
			c.deprShould,
			c.workLoad,
			c.period,
			c.years,
			c.deprTime,
			c.origina,
			c.salvage,
			c.estimateDay,
			ac.assetCode,
			ac.assetName,
			ac.assetTypeName,
			ac.unit,
			ac.buyDate,
			ac.wirteDate,
			ac.spec,
			ac.deprName,
			ac.useOrgName,
			ac.alreadyDay,
			ac.beginTime,
			ac.depreciation,
			ac.buyDepr,
			ac.netValue
              from oa_asset_balance c
              left join oa_asset_card ac
                     	ON(ac.id=c.assetId)
               where $searchCondition order by c.deprTime;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>


