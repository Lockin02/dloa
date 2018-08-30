<?php
/**
 * @author Show
 * @Date 2012年3月9日 星期五 15:50:47
 * @version 1.0
 * @description:产品配置缓存表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.goodsId ,c.goodsName ,c.fileName ,c.filePath  from oa_goods_cache c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "goodsId",
   		"sql" => " and c.goodsId=# "
   	  ),
   array(
   		"name" => "goodsName",
   		"sql" => " and c.goodsName=# "
   	  ),
   array(
   		"name" => "fileName",
   		"sql" => " and c.fileName=# "
   	  ),
   array(
   		"name" => "filePath",
   		"sql" => " and c.filePath=# "
   	  )
)
?>