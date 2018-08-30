<?php

$sql_arr = array (
         "select_default"=>"select c.id ,c.formId,c.titleName,c.optionType from oa_license_category_title c where 1=1 "
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
   		"name" => "titleName",
   		"sql" => " and c.titleName=# "
   	  ),
   array(
   		"name" => "optionType",
   		"sql" => " and c.optionType=# "
   	  )
)
?>