<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$beginDate = $_GET['beginDate'] ;//开始日期
$endDate=$_GET['endDate'];
$priceLimit=$_GET['priceLimit'];
$subPriceLimit=$_GET['subPriceLimit'];

$searchCondition=" and o.auditDate between '".$beginDate."' and '".$endDate."'";
$priceLimitArr=explode(",",$priceLimit);
if(!empty($priceLimitArr)&&in_array("cost",$priceLimitArr)){
    $cost="oi.`cost`";
}else{
    $cost="0 as cost";
}

$subPriceLimitArr=explode(",",$subPriceLimit);
if(!empty($subPriceLimitArr)&&in_array("subCost",$subPriceLimitArr)){
    $subCost="oi.`subCost`";
}else{
    $subCost="0 as subCost";
}


//
//$productId = '1359';
//$monthBeginDate = '2011-09-01';
//$monthEndDate = '2011-09-30';
$QuerySQL = <<<QuerySQL
	select oi.productCode,
			oi.productName,
        	oi.`batchNum`,
            case o.isRed when 0 THEN oi.`actOutNum` else -oi.`actOutNum` end as actOutNum,
            o.`auditDate`,
            $cost,
            $subCost,
            p.proType 
              from oa_stock_outstock_item oi   
					inner join oa_stock_outstock o 
                    	on(o.id=oi.`mainId`) 
                    left join oa_stock_product_info p 
                     	ON(p.id=oi.productId)   
              where o.docStatus='YSH' and o.docType='CKPICKING' $searchCondition  order by productCode,auditDate;
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
//print_r ( GenAttrXmlData ( "CALL inventory_subitem('2011-09-01','2011-09-30','1359')", false ) );
?>
