<?php
/**
 * @author Administrator
 * @Date 2013年2月27日 星期三 14:28:01
 * @version 1.0
 * @description:生产物料需求 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.relDocCode ,c.relDocId ,c.relDocType ,c.configName ,c.configNum ,c.sendDate ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,ifnull(psub.onWayAmount,0) as planInstockNum,ifnull(isub.actNum,0) as actNum  from oa_stock_extra_produceproitem c 
         left join (select cast(if(SUM(p.amountAll-p.amountIssued) is null,0,SUM(p.amountAll-p.amountIssued)) as decimal (10, 0)) as onWayAmount,productId 
											from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id=p.basicId		where c.isTemp=0 and c.state=7 and c.ExaStatus='完成'
							GROUP BY productId)psub on(psub.productId=c.productId)
				left join (select sum(actNum) as actNum,productId from oa_stock_inventory_info where stockCode<>'OUTSTOCK'  GROUP BY productId)isub on(isub.productId=c.productId)
         where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
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
   		"name" => "relDocCode",
   		"sql" => " and c.relDocCode=# "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "relDocType",
   		"sql" => " and c.relDocType=# "
   	  ),
   array(
   		"name" => "configName",
   		"sql" => " and c.configName=# "
   	  ),
   array(
   		"name" => "configNum",
   		"sql" => " and c.configNum=# "
   	  ),
   array(
   		"name" => "sendDate",
   		"sql" => " and c.sendDate=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>