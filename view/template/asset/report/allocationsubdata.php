<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$searchCondition="";
//echo "select oi.assetId,
//	oi.assetCode,
//			oi.assetName,
//			oi.spec,
//			oi.buyDate,
//			p.outDeptName,
//			p.inDeptName,
//			p.outProName,
//			p.inProName,
//			p.ExaDT,
//			p.ExaStatus,
//			p.moveDate
//              from oa_asset_allocationitem oi
//                    left join oa_asset_allocation p
//                     	ON(p.id=oi.allocateID)
//               where p.ExaStatus='完成'  $searchCondition  order by assetCode,moveDate";
$QuerySQL = <<<QuerySQL
	select oi.assetId,
	oi.assetCode,
			oi.assetName,
			oi.spec,
			oi.buyDate,
			p.outDeptName,
			p.inDeptName,
			p.outProName,
			p.inProName,
			p.ExaDT,
			p.ExaStatus,
			p.moveDate
              from oa_asset_allocationitem oi
                    left join oa_asset_allocation p
                     	ON(p.id=oi.allocateID)
               where p.ExaStatus='完成'  $searchCondition  order by assetCode,moveDate;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>