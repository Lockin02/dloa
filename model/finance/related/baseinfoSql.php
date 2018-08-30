<?php
/**
 * @author Show
 * @Date 2010年12月29日 星期三 19:31:43
 * @version 1.0
 * @description:钩稽关系主表 sql配置文件 只有钩稽和反钩,无修改操作
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.years ,c.createId ,c.createName ,c.createTime ,c.status  from oa_finance_related c where 1=1 ",
         "hook_list" => "select c.id,c.years,c.createName,c.createTime,c.status,c.supplierName ,d.id as detailId,d.amount,d.number,d.productName,d.hookObj,d.hookObjCode,d.productNo,d.hookMainId,d.hookDate " .
         		"from oa_finance_related c left join oa_finance_related_detail d on c.id = d.relatedId where 1=1 ",
 		 "detail_list" => "select c.id as relatedId,c.years,c.createName,c.createTime,c.status,c.supplierName ,d.id,d.amount,d.number,d.productName,d.hookObj,d.hookObjCode,d.productNo,d.hookMainId,d.hookDate " .
     		"from oa_finance_related c left join oa_finance_related_detail d on c.id = d.relatedId where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
    array(
   		"name" => "ids",
   		"sql" => " and c.id in(arr)"
        ),
   array(
   		"name" => "years",
   		"sql" => " and c.years=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
	),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
	),
    array(
   		"name" => "hookObjCode",
   		"sql" => " and d.hookObjCode like CONCAT('%',#,'%')  "
	),
    array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%')  "
	),
    array(
        "name" => "hookMainId",
        "sql" => " and d.hookMainId=# "
    )
)
?>