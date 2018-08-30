<?php
/**
 * @author Administrator
 * @Date 2012年1月7日 14:57:01
 * @version 1.0
 * @description:评估项目 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.assesProCode ,c.assesProName ,c.assesProProportion ,c.formManId ,c.formManName ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_supp_assesproject c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "assesProCode",
   		"sql" => " and c.assesProCode=# "
   	  ),
   array(
   		"name" => "assesProName",
   		"sql" => " and c.assesProName=# "
   	  ),
   array(
   		"name" => "assesProCodeEq",
   		"sql" => " and c.assesProCode=# "
   	  ),
   array(
   		"name" => "assesProNameEq",
   		"sql" => " and c.assesProName=# "
   	  ),
   array(
   		"name" => "assesProProportion",
   		"sql" => " and c.assesProProportion=# "
   	  ),
   array(
   		"name" => "formManId",
   		"sql" => " and c.formManId=# "
   	  ),
   array(
   		"name" => "formManName",
   		"sql" => " and c.formManName=# "
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