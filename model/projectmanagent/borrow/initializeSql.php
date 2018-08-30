<?php
/**
 * @author Administrator
 * @Date 2012年2月16日 13:28:34
 * @version 1.0
 * @description:借试用初始化数据表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.applyDate ,c.Type ,c.applyUserName ,c.applyUserId ,c.getGoodsName ,c.getGoodsId ,c.productId ,c.productCode ,c.productName ,c.productModel ,c.number ,c.unitName ,c.price ,c.money ,c.backNum ,c.beginTime ,c.closeTime ,c.closeTimeTrue ,c.productNoKS ,c.Code ,c.KCode ,c.outStorage ,c.inStorage ,c.dept ,c.deptId ,c.reason ,c.seriesNumber ,c.customerName ,c.customerId ,c.customerInfo ,c.remark ,c.createTime ,c.createName ,c.createId  from oa_borrow_initialize c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "applyDate",
   		"sql" => " and c.applyDate=# "
   	  ),
   array(
   		"name" => "Type",
   		"sql" => " and c.Type=# "
   	  ),
   array(
   		"name" => "applyUserName",
   		"sql" => " and c.applyUserName=# "
   	  ),
   array(
   		"name" => "applyUserId",
   		"sql" => " and c.applyUserId=# "
   	  ),
   array(
   		"name" => "getGoodsName",
   		"sql" => " and c.getGoodsName=# "
   	  ),
   array(
   		"name" => "getGoodsId",
   		"sql" => " and c.getGoodsId=# "
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
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "backNum",
   		"sql" => " and c.backNum=# "
   	  ),
   array(
   		"name" => "beginTime",
   		"sql" => " and c.beginTime=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "closeTimeTrue",
   		"sql" => " and c.closeTimeTrue=# "
   	  ),
   array(
   		"name" => "productNoKS",
   		"sql" => " and c.productNoKS=# "
   	  ),
   array(
   		"name" => "Code",
   		"sql" => " and c.Code=# "
   	  ),
   array(
   		"name" => "KCode",
   		"sql" => " and c.KCode=# "
   	  ),
   array(
   		"name" => "outStorage",
   		"sql" => " and c.outStorage=# "
   	  ),
   array(
   		"name" => "inStorage",
   		"sql" => " and c.inStorage=# "
   	  ),
   array(
   		"name" => "dept",
   		"sql" => " and c.dept=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "reason",
   		"sql" => " and c.reason=# "
   	  ),
   array(
   		"name" => "seriesNumber",
   		"sql" => " and c.seriesNumber=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerInfo",
   		"sql" => " and c.customerInfo=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  )
)
?>