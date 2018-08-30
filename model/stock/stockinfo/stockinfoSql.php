<?php
/**
 * @author Show
 * @Date 2011年5月3日 19:15:50
 * @version 1.0
 * @description:仓库基本信息 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.stockCode ,c.stockName ,c.adress ,c.chargeUserName ,c.chargeUserCode ,
			c.stockUseCode ,c.stockUse ,c.stockType ,c.remark ,c.createName ,c.createId ,
			c.createTime ,c.updateName ,c.updateId ,c.updateTime, c.businessBelongName, c.businessBelong
		from oa_stock_baseinfo c where id<>-1  ",
	"select_treejson" => "select c.id ,c.stockCode ,c.stockName as name ,c.stockUseCode ,c.stockUse ,c.stockType ,
		0 as isParent from oa_stock_baseinfo c where 1=1  ",
	"select_treeinfo" => "select c.id,c.stockName as name,c.parentName,c.parentId,c.lft,c.rgt,c.orderNum,
		case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent from oa_stock_baseinfo c where 1=1"
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "stockCode",
		"sql" => " and c.stockCode like CONCAT('%',#,'%') "
	),
	array(
		"name" => "stockCodeEq",
		"sql" => " and c.stockCode =#"
	),
	array(
		"name" => "nstockName",
		"sql" => "and c.stockName =#"
	),
	array(
		"name" => "stockName",
		"sql" => "and c.stockName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "nstockCode",
		"sql" => "and c.stockCode =#"
	),
	array(
		"name" => "adress",
		"sql" => " and c.adress=# "
	),
	array(
		"name" => "chargeUserName",
		"sql" => " and c.chargeUserName =# "
	),
	array(
		"name" => "chargeUserCode",
		"sql" => " and c.chargeUserCode=# "
	),
	array(
		"name" => "stockUseCode",
		"sql" => " and c.stockUseCode=# "
	),
	array(
		"name" => "stockUse",
		"sql" => " and c.stockUse=# "
	),
	array(
		"name" => "stockType",
		"sql" => " and c.stockType=# "
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
);