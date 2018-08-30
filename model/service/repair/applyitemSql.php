<?php
/**
 * @author huangzf
 * @Date 2011年12月4日 10:52:28
 * @version 1.0
 * @description:维修申请(报价)清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,a.docCode as applyCode,q.docCode as quoteCode,c.quoteId ,c.productTypeCode ,c.productType ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.serilnoName ,c.fittings ,c.place ,c.process ,c.troubleInfo ,c.checkInfo ,c.remark ,c.isGurantee ,c.repairType ,c.repairCost ,c.cost ,c.reduceCost ,c.isDetect ,c.isShip ,c.isQuote  from oa_service_repair_applyitem c 
	         left join oa_service_repair_apply a on(a.id=c.mainId) left join oa_service_repair_quote q on(q.id=c.quoteId)
    	     	where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "ids",
   		"sql" => " and c.id in($) "
        ),        
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "quoteId",
   		"sql" => " and c.quoteId=# "
   	  ),
   array(//已确认 未提交报价
   		"name" => "quoteIdNull",
   		"sql" => " and c.quoteId is null and c.isQuote=1 "
   	  ),
   array(
   		"name" => "quoteIdAudit",
   		"sql" => " and c.quoteId in(select id from oa_service_repair_quote  where ExaStatus='完成') "
   	  ),      	     	  
   array(
   		"name" => "productTypeCode",
   		"sql" => " and c.productTypeCode=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "serilnoName",
   		"sql" => " and c.serilnoName=# "
   	  ),
   array(
   		"name" => "fittings",
   		"sql" => " and c.fittings=# "
   	  ),
   array(
   		"name" => "place",
   		"sql" => " and c.place=# "
   	  ),
   array(
   		"name" => "process",
   		"sql" => " and c.process=# "
   	  ),
   array(
   		"name" => "troubleInfo",
   		"sql" => " and c.troubleInfo=# "
   	  ),
   array(
   		"name" => "checkInfo",
   		"sql" => " and c.checkInfo=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isGurantee",
   		"sql" => " and c.isGurantee=# "
   	  ),
   array(
   		"name" => "repairType",
   		"sql" => " and c.repairType=# "
   	  ),
   array(
   		"name" => "repairCost",
   		"sql" => " and c.repairCost=# "
   	  ),
   array(
   		"name" => "cost",
   		"sql" => " and c.cost=# "
   	  ),
   array(
   		"name" => "reduceCost",
   		"sql" => " and c.reduceCost=# "
   	  ),
   array(
   		"name" => "isDetect",
   		"sql" => " and c.isDetect=# "
   	  ),
   array(
   		"name" => "isShip",
   		"sql" => " and c.isShip=# "
   	  ),
   array(
   		"name" => "isQuote",
   		"sql" => " and c.isQuote=# "
   	  )
)
?>