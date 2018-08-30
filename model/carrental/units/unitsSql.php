<?php
/**
 * @author Show
 * @Date 2011年12月25日 星期日 14:36:05
 * @version 1.0
 * @description:租车单位(oa_carrental_units) sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.unitName ,c.unitCode ,c.address ,c.unitNature ,c.countryName ,c.countryCode ,c.provinceName ,c.provinceCode ,c.cityName ,c.cityCode ,c.linkMan ,c.linkPhone ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_carrental_units c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "unitCode",
   		"sql" => " and c.unitCode=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "unitNature",
   		"sql" => " and c.unitNature=# "
   	  ),
   array(
   		"name" => "countryName",
   		"sql" => " and c.countryName=# "
   	  ),
   array(
   		"name" => "countryCode",
   		"sql" => " and c.countryCode=# "
   	  ),
   array(
   		"name" => "provinceName",
   		"sql" => " and c.provinceName=# "
   	  ),
   array(
   		"name" => "provinceCode",
   		"sql" => " and c.provinceCode=# "
   	  ),
   array(
   		"name" => "cityName",
   		"sql" => " and c.cityName=# "
   	  ),
   array(
   		"name" => "cityCode",
   		"sql" => " and c.cityCode=# "
   	  ),
   array(
   		"name" => "linkMan",
   		"sql" => " and c.linkMan like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "linkPhone",
   		"sql" => " and c.linkPhone=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>