<?php
/**
 * @author huangzf
 * @Date 2011年5月9日 8:42:00
 * @version 1.0
 * @description:仓库期初库存信息 sql配置文件
 */
$sql_arr = array (
 	"select_default"=>"select c.id ,c.stockId ,c.stockName ,c.stockCode ,c.proTypeId ,c.proType ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.aidUnit ,c.converRate ,c.initialNum ,c.safeNum ,c.actNum ,c.exeNum ,c.maxNum ,c.miniNum ,c.assigedNum ,c.lockedNum ,c.planInstockNum ,c.price ,c.sumAmount ,c.docStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_inventory_info c where 1=1 ",
	"select_comp_inventoryinfo"=>"select c.id,c.stockId,c.stockName,c.stockCode,c.proTypeId,c.typecode,c.proType,c.productId,c.productCode,c.productName,c.initialNum,c.actNum,c.safeNum,c.price,c.remark,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,c.exeNum,p.productCode,p.pattern,p.versonNum,p.priCost from oa_stock_inventory_info c inner join oa_stock_product_info p on(p.id=c.productId) where 1=1",
	"select_all" =>"select c.id ,c.stockId ,c.stockName ,c.pattern ,c.unitName ,c.stockCode ,c.proTypeId ,c.proType ,c.productId ,c.productCode ,c.productName ,c.initialNum ,c.actNum ,c.safeNum ,c.exeNum ,c.price ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_inventory_info c where 1=1 ",
	"select_forCalculate" => "select c.id,c.stockId,c.stockName,c.stockCode,c.productId,c.productName,c.productCode,c.actNum,c.price from oa_stock_inventory_info c where 1=1",
	"inventoryinfo_excel" => "select c.stockName,c.productName,c.productCode,c.actNum,c.price from oa_stock_inventory_info c where 1=1",
	"product_info" =>"select c.stockId ,c.stockName ,c.stockCode ,c.proTypeId ,c.typecode ,c.proType ,c.productId as id ,c.productCode ,c.productName ,c.initialNum ,c.actNum ,c.safeNum ,c.exeNum ,c.price ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_inventory_info c where 1=1 ",
	"select_subitem"=>"select c.productName,c.productCode,IFNULL(sum(c.actNum),0) actNum,IFNULL(sum(c.initialNum),0) initialNum,IFNULL(sum(c.exeNum),0) exeNum,IFNULL(sum(c.assigedNum),0) assigedNum,IFNULL(sum(c.lockedNum),0) lockedNum,IFNULL(sum(c.planInstockNum),0) planInstockNum from oa_stock_inventory_info c where 1=1 and c.stockId<>-1  ",
	"subactnum_proid"=>"select sum(c.actNum) as actNum from oa_stock_inventory_info c where 1=1 ",
	"select_count"=>"select count(c.id) as countNum from oa_stock_inventory_info c where 1=1 ",
 	"select_intimelist"=>
 	"select c.id ,c.stockId ,c.stockName ,c.stockCode ,c.proTypeId ,c.proType ,c.productId ,c.productCode ,
		c.productName ,c.pattern ,c.unitName ,c.aidUnit ,c.converRate ,c.initialNum ,c.safeNum ,c.actNum ,
		c.exeNum ,c.maxNum ,c.miniNum ,c.assigedNum ,c.lockedNum ,c.planInstockNum ,c.price ,c.sumAmount ,
		c.docStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,
		ifnull(psub.onWayAmount,0) as planInstockNum ,
		if(k.applyNum is null,0,(k.applyNum-ifnull(psub.orderNum,0))) as auditNum,
		pi.ext2 as k3Code
	from oa_stock_inventory_info c
		left join (
			select if(SUM(p.amountAll-p.amountIssued) is null,0,SUM(p.amountAll-p.amountIssued)) as onWayAmount,
				productId,if(p.purchType='produce',SUM(p.amountAll),0)  as orderNum
			from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id=p.basicId
			where c.isTemp=0 and c.state=7 and p.purchType='produce' and c.ExaStatus='完成' and (p.taskEquId > 0) 
			GROUP BY productId
		)  psub
		on (psub.productId=c.productId)
		left join(
			select if(SUM(p.amountAll) is null,0,SUM(p.amountAll)) as applyNum,productId
			from oa_purch_plan_equ p
				left join oa_purch_plan_basic c
					on p.basicId=c.id
			where p.purchType='produce' and c.ExaStatus='完成'
			GROUP BY productId
		) k
		on (k.productId = c.productId)
		left join oa_stock_product_info pi ON c.productId = pi.id
 	where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=#"
   	  ),
   array(
   		"name" => "stockIds",
   		"sql" => " and c.stockId in($)"
   	  ),
   array(
   		"name" => "nstockId",
   		"sql" => " and c.stockId<>#"
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "proTypeId",
   		"sql" => " and c.proTypeId=# "
   	  ),
   array(
   		"name" => "proType",
   		"sql" => " and c.proType=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%')"
   	  ),
	array(
		"name"=>"productIds",
		"sql"=>" and c.productId in($)"
	),
	array (
		"name" => "stockIds",
		"sql" => " and c.stockId in(arr) "
	),
	array(
		"name"=>"productName",
		"sql"=>" and c.productName like CONCAT('%',#,'%')"
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
   		"name" => "aidUnit",
   		"sql" => " and c.aidUnit=# "
   	  ),
   array(
   		"name" => "converRate",
   		"sql" => " and c.converRate=# "
   	  ),
   array(
   		"name" => "initialNum",
   		"sql" => " and c.initialNum=# "
   	  ),
   array(
   		"name" => "safeNum",
   		"sql" => " and c.safeNum=# "
   	  ),
   array(
   		"name" => "actNum",
   		"sql" => " and c.actNum=# "
   	  ),
   array(
   		"name" => "exeNum",
   		"sql" => " and c.exeNum=# "
   	  ),
   array(
   		"name" => "maxNum",
   		"sql" => " and c.maxNum=# "
   	  ),
   array(
   		"name" => "miniNum",
   		"sql" => " and c.miniNum=# "
   	  ),
   array(
   		"name" => "assigedNum",
   		"sql" => " and c.assigedNum=# "
   	  ),
   array(
   		"name" => "lockedNum",
   		"sql" => " and c.lockedNum=# "
   	  ),
   array(
   		"name" => "planInstockNum",
   		"sql" => " and c.planInstockNum=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "sumAmount",
   		"sql" => " and c.sumAmount=# "
   	  ),
   array(
   		"name" => "docStatus",
   		"sql" => " and c.docStatus=# "
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
   	  ),
    array(
        "name" => "k3Code",
        "sql" => " and pi.ext2 like CONCAT('%',#,'%')"
    )
)
?>