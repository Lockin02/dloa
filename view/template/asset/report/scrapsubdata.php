<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$beginDate = $_GET['beginDate'] ;//开始日期
$endDate=$_GET['endDate'];

$searchCondition="ac.scrapDate between '".$beginDate."' and '".$endDate."'";

//$searchCon="";
//if( isset($_GET['years'])&&$_GET['years']!=''){
//$years= $_GET['years'] ;//年度
//$searchCon=" and years ='".$years."'";
//}


$QuerySQL = <<<QuerySQL
	select c.assetCode,
			c.assetName,
			c.buyDate,
			c.spec,
			c.depreciation,
			c.salvage,
			c.remark,
			ac.billNo,
			ac.scrapDate,
			ac.deptName,
			ac.proposer,
			ac.ExaDT,
			card.origina
              from oa_asset_scrapItem c
              			left join oa_asset_scrap ac
                     	ON(ac.id=c.allocateID)
                     	left join oa_asset_card card
                     	ON(card.id=c.assetId)
               where $searchCondition order by ac.scrapDate;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>


