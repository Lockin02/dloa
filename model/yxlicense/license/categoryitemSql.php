<?php
/**
 * @author sony
 * @Date 2013年9月9日 11:12:38
 * @version 1.0
 * @description:sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.licenseId,c.categoryId,c.itemName,c.groupName,c.appendShow from oa_license_category_item c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "licenseId",
   		"sql" => " and c.licenseId=# "
   	  ),
   array(
   		"name" => "categoryId",
   		"sql" => " and c.categoryId=# "
   	  ),
   array(
   		"name" => "itemName",
   		"sql" => " and c.itemName=# "
   	  ),
   array(
   		"name" => "groupName",
   		"sql" => " and c.groupName=# "
   	  ),
   array(
   		"name" => "appendShow",
   		"sql" => " and c.appendShow=# "
   	  )
)
?>