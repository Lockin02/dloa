<?php

$sql_arr = array (
         "select_default"=>"select c.id ,c.formId,c.optionName,c.canUse,c.type from oa_license_category_options c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formId",
   		"sql" => " and c.formId=# "
   	  ),
   array(
   		"name" => "optionName",
   		"sql" => " and c.optionName=# "
   	  ), 
   array(
   		"name" => "canUse",
   		"sql" => " and c.canUse=# "
   	  ), 
   array(
   		"name" => "type",
   		"sql" => " and c.type=# "
   	  )
)
?>