<?php
/**
 * @author Show
 * @Date 2011年10月9日 星期日 14:39:27
 * @version 1.0
 * @description:(new)license模 板 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.licenseId ,c.name ,c.remark ,c.thisVal ,c.extVal ,c.status ,c.licenseType,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.rowVal  from oa_license_template c where 1=1 "
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
   		"name" => "name",
   		"sql" => " and c.name like concat('%',#,'%') "
    ),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
    array(
   		"name" => "createName",
   		"sql" => " and c.createName like concat('%',#,'%')"
    ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
    array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
    ),
    array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
    ),
    array(
   		"name" => "licenseType",
   		"sql" => " and c.licenseType=# "
    ),
    array(
   		"name" => "rowVal",
   		"sql" => " and c.rowVal=# "
    )
)
?>