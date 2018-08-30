<?php

$sql_arr = array (
         "select_default"=>"select c.id ,c.itemId,c.formName,c.isHideTitle from oa_license_category_form c where 1=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "itemId",
   		"sql" => " and c.itemId=# "
   	  ),
   array(
   		"name" => "formName",
   		"sql" => " and c.formName=# "
   	  ),
   array(
   		"name" => "isHideTitle",
   		"sql" => " and c.isHideTitle=# "
   	  )
)
?>