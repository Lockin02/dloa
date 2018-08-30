<?php
/**
 * @author Administrator
 * @Date 2012年7月16日 15:00:09
 * @version 1.0
 * @description:行政区域表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.agencyName ,c.agencyCode ,c.remark ,c.chargeId ,c.chargeName  from oa_asset_agency c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "agencyName",
   		"sql" => " and c.agencyName like CONCAT('%',#,'%')  "
   	  ),
	array(
		"name" => "agencyNameEq",
		"sql" => " and c.agencyName=# "
	),
   array(
   		"name" => "agencyCode",
   		"sql" => " and c.agencyCode=# "
   	  ),
	array(
		"name" => "agencyCodeEq",
		"sql" => " and c.agencyCode=# "
	),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "chargeId",
   		"sql" => " and c.chargeId=# "
   	  ),
   array(
   		"name" => "chargeName",
   		"sql" => " and c.chargeName like CONCAT('%',#,'%') "
   	  )
)
?>