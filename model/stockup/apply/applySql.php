<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:21:40
 * @version 1.0
 * @description:备货申请表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.listNo ,c.remark ,c.isClose ,c.projectID ,c.projectCode ,c.projectName ,c.appUserId ,c.appUserName ,c.appDate ,c.description ,c.appDeptId ,c.appDeptName ,c.ExaDT ,c.ExaStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.chanceCode,c.chanceName  from oa_stockup_apply c where 1=1 ",
         "personList"=>"select c.id ,c.listNo ,c.remark ,c.isClose ,c.projectID ,c.projectCode ,c.projectName ,c.appUserId ,c.appUserName ,c.appDate ,c.description ,c.appDeptId ,c.appDeptName ,c.ExaDT ,c.ExaStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.chanceCode,c.chanceName,
         				(SELECT SUM(d.productNum) FROM oa_stockup_apply_products d WHERE d.appId=c.id) AS productNum from oa_stockup_apply c where 1=1 and c.createId='".$_SESSION['USER_ID']."' "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "listNo",
   		"sql" => " and c.listNo   like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark  like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "isClose",
   		"sql" => " and c.isClose=# "
   	  ),
   array(
   		"name" => "projectID",
   		"sql" => " and c.projectID=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName   like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode   like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName   like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "appUserId",
   		"sql" => " and c.appUserId=# "
   	  ),
   array(
   		"name" => "appUserName",
   		"sql" => " and c.appUserName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "appDate",
   		"sql" => " and c.appDate=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  ),
   array(
   		"name" => "appDeptId",
   		"sql" => " and c.appDeptId=# "
   	  ),
   array(
   		"name" => "appDeptName",
   		"sql" => " and c.appDeptName=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
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