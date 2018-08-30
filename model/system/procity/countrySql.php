<?php
/**
 * ¹ú¼Òsql
 */
$sql_arr = array (
	"select" => "select c.id,c.countryCode,c.countryName,c.orderNum,c.remark from oa_system_country_info c where  1=1 ",
	"select_country" => "select c.id,c.countryCode as code,c.countryName as name,c.orderNum,c.remark from oa_system_country_info c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "countryCode",
   		"sql" => " and c.countryCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "countryName",
   		"sql" => " and c.countryName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "orderNum",
   		"sql" => " and c.orderNum  like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  ),
   	  array(
   		"name" => "countryCodeEq",
   		"sql" => " and c.countryCode=#"
   	  ),
   	  array(
   		"name" => "countryNameEq",
   		"sql" => " and c.countryName=#"
   	  )
)
?>
