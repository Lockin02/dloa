<?php
/**
 * @author Administrator
 * @Date 2013年9月6日 15:43:40
 * @version 1.0
 * @description:产品分类信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.licenseId,c.categoryName,c.appendDesc,c.orderNum,c.showType,c.lineFeed,c.isHideTitle,c.type from oa_license_category c where 1=1 ",
		"select_treeinfo"=>"select c.id ,c.licenseId,c.categoryName as name,c.categoryName,c.appendDesc,c.orderNum,c.showType from oa_license_category c where 1=1"
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
   		"name" => "categoryName",
   		"sql" => " and c.categoryName=# "
   	  ),
   array(
   		"name" => "appendDesc",
   		"sql" => " and c.appendDesc=# "
   	  ),
   array(
   		"name" => "orderNum",
   		"sql" => " and c.orderNum=# "
   	  ),
   array (//自定义条件
		"name" => "showType",
		"sql" => "and c.showType=# "
	),
   array (//自定义条件
		"name" => "lineFeed",
		"sql" => "and c.lineFeed=# "
	),
   array (//自定义条件
		"name" => "isHideTitle",
		"sql" => "and c.isHideTitle=# "
	),
   array (//自定义条件
		"name" => "type",
		"sql" => "and c.type=# "
	)		
)
?>