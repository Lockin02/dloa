<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:33
 * @version 1.0
 * @description:供应商银行信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.suppCode ,c.bankName ,c.depositbank ,c.accountNum ,c.remark ,c.isDefault ,c.suppAccount  from oa_outsourcesupp_bankinfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "bankName",
   		"sql" => " and c.bankName=# "
   	  ),
   array(
   		"name" => "depositbank",
   		"sql" => " and c.depositbank=# "
   	  ),
   array(
   		"name" => "accountNum",
   		"sql" => " and c.accountNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isDefault",
   		"sql" => " and c.isDefault=# "
   	  )
)
?>