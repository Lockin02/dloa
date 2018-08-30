<?php
/**
 * @author Administrator
 * @Date 2013年12月4日 星期三 16:44:10
 * @version 1.0
 * @description:外包立项整包分包表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.costTypeId ,c.costType ,c.parentId ,c.parentName ,c.feeType ,c.price ,c.number ,c.period ,c.amount ,c.suppId ,c.suppCode ,c.suppName ,c.remark ,c.isSelf ,c.isOtherFee ,c.isManageFee ,c.isProfit ,c.isTax ,c.isServerCost ,c.isAllCost ,c.isChoosed ,c.isPoint ,c.isDetail ,c.isNetProfit ,c.sysNo ,c.groupKey ,c.isCustom  from oa_outsourcing_approval_projectrental c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "costTypeId",
   		"sql" => " and c.costTypeId=# "
   	  ),
   array(
   		"name" => "costType",
   		"sql" => " and c.costType=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentName",
   		"sql" => " and c.parentName=# "
   	  ),
   array(
   		"name" => "feeType",
   		"sql" => " and c.feeType=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "period",
   		"sql" => " and c.period=# "
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isSelf",
   		"sql" => " and c.isSelf=# "
   	  ),
   array(
   		"name" => "isOtherFee",
   		"sql" => " and c.isOtherFee=# "
   	  ),
   array(
   		"name" => "isManageFee",
   		"sql" => " and c.isManageFee=# "
   	  ),
   array(
   		"name" => "isProfit",
   		"sql" => " and c.isProfit=# "
   	  ),
   array(
   		"name" => "isTax",
   		"sql" => " and c.isTax=# "
   	  ),
   array(
   		"name" => "isServerCost",
   		"sql" => " and c.isServerCost=# "
   	  ),
   array(
   		"name" => "isAllCost",
   		"sql" => " and c.isAllCost=# "
   	  ),
   array(
   		"name" => "isChoosed",
   		"sql" => " and c.isChoosed=# "
   	  ),
   array(
   		"name" => "isPoint",
   		"sql" => " and c.isPoint=# "
   	  ),
   array(
   		"name" => "isDetail",
   		"sql" => " and c.isDetail=# "
   	  ),
   array(
   		"name" => "isNetProfit",
   		"sql" => " and c.isNetProfit=# "
   	  ),
   array(
   		"name" => "sysNo",
   		"sql" => " and c.sysNo=# "
   	  ),
   array(
   		"name" => "groupKey",
   		"sql" => " and c.groupKey=# "
   	  ),
   array(
   		"name" => "isCustom",
   		"sql" => " and c.isCustom=# "
   	  )
)
?>