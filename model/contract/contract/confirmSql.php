<?php

$sql_arr = array (
         "select_default"=>"select c.id ,c.type,c.money,c.state,c.contractId,c.contractCode,c.handleId,c.handleName,date_format(c.handleDate,'%Y-%m-%d') as handleDate,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.createName ,c.createId  from oa_contract_confirm c where 1=1 ",
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "type",
   		"sql" => " and c.type=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "handleId",
   		"sql" => " and c.handleId=# "
   	  ),
   array(
   		"name" => "handleName",
   		"sql" => " and c.handleName=# "
   	  ),
   array(
   		"name" => "handleDate",
   		"sql" => " and c.handleDate=# "
   	  ),
	array(
		"name" => "createYear",
		"sql" => " and date_format(c.createTime,'%Y')=# "
	),
	array(
		"name" => "createMonth",
		"sql" => " and date_format(c.createTime,'%m')=# "
	)
)
?>