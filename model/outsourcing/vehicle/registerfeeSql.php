<?php
/**
 * @author yxin1
 * @Date 2014年10月8日 19:50:39
 * @version 1.0
 * @description:租车登记合同附加费 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.registerId ,c.contractId ,c.orderCode ,c.feeName ,c.feeAmount ,c.yesOrNo ,c.remark  from oa_outsourcing_registerfee c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "registerId",
   		"sql" => " and c.registerId=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "feeName",
   		"sql" => " and c.feeName=# "
   	  ),
   array(
   		"name" => "feeAmount",
   		"sql" => " and c.feeAmount=# "
   	  ),
   array(
   		"name" => "yesOrNo",
   		"sql" => " and c.yesOrNo=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>