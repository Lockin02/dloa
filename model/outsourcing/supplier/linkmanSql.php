<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:04
 * @version 1.0
 * @description:供应商联系人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppCode ,c.suppId ,c.name ,c.jobName ,c.mobile ,c.mobile2 ,c.zipCode ,c.address ,c.fax ,c.email ,c.defaultContact ,c.plane ,c.remarks ,c.createTime ,c.createName ,c.createId ,c.updateTime ,c.updateName ,c.updateId  from oa_outsourcesupp_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName=# "
   	  ),
   array(
   		"name" => "mobile",
   		"sql" => " and c.mobile=# "
   	  ),
   array(
   		"name" => "mobile2",
   		"sql" => " and c.mobile2=# "
   	  ),
   array(
   		"name" => "zipCode",
   		"sql" => " and c.zipCode=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "fax",
   		"sql" => " and c.fax=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "defaultContact",
   		"sql" => " and c.defaultContact=# "
   	  ),
   array(
   		"name" => "plane",
   		"sql" => " and c.plane=# "
   	  ),
   array(
   		"name" => "remarks",
   		"sql" => " and c.remarks=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  )
)
?>