<?php
/**
 * @author Administrator
 * @Date 2011年7月5日 20:39:17
 * @version 1.0
 * @description:共享合同表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderType ,c.orderId ,c.orderName ,c.shareName ,c.shareNameId ,c.shareDate ,c.toshareName ,c.toshareNameId ,c.toshareBranch ,c.toshareBranchId ,c.toshareArea ,c.toshareAreaId ,c.obligate1 ,c.obligate2  from oa_sale_share c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderType",
   		"sql" => " and c.orderType=# "
   	  ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "shareName",
   		"sql" => " and c.shareName=# "
   	  ),
   array(
   		"name" => "shareNameId",
   		"sql" => " and c.shareNameId=# "
   	  ),
   array(
   		"name" => "shareDate",
   		"sql" => " and c.shareDate=# "
   	  ),
   array(
   		"name" => "toshareName",
   		"sql" => " and c.toshareName=# "
   	  ),
   array(
   		"name" => "toshareNameId",
   		"sql" => " and c.toshareNameId=# "
   	  ),
   array(
   		"name" => "toshareBranch",
   		"sql" => " and c.toshareBranch=# "
   	  ),
   array(
   		"name" => "toshareBranchId",
   		"sql" => " and c.toshareBranchId=# "
   	  ),
   array(
   		"name" => "toshareArea",
   		"sql" => " and c.toshareArea=# "
   	  ),
   array(
   		"name" => "toshareAreaId",
   		"sql" => " and c.toshareAreaId=# "
   	  ),
   array(
   		"name" => "obligate1",
   		"sql" => " and c.obligate1=# "
   	  ),
   array(
   		"name" => "obligate2",
   		"sql" => " and c.obligate2=# "
   	  )
)
?>