<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$searchCondition="";
//echo "select oi.assetId,
//	oi.assetCode,
//			oi.assetName,
//			p.deptId,
//			p.deptName,
//			p.borrowId,
//			p.borrowIdName,
//			p.ExaDT,
//			p.ExaStatus
//              from oa_asset_borrowitem oi
//                    left join oa_asset_borrow p
//                     	ON(p.id=oi.borrowID)
//               where p.ExaStatus='完成'  $searchCondition  order by assetCode";
$QuerySQL = <<<QuerySQL
	select oi.assetId,
	oi.assetCode,
			oi.assetName,
			p.deptId,
			p.deptName,
			p.reposeManId,
			p.reposeMan,
			p.predictDate,
			case when oi.isReturn=1 then '是'
			else '否'
			end as  isReturn,
			p.ExaDT,
			p.ExaStatus
              from oa_asset_borrowitem oi
                    left join oa_asset_borrow p
                     	ON(p.id=oi.borrowID)
               where p.ExaStatus='完成'  $searchCondition  order by assetCode;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>