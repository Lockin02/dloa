<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货申请 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.listNo ,c.remark ,c.isClose ,c.batchNum ,c.stockNum ,c.needsNum ,c.appUserId ,c.appUserName ,c.appDate ,c.stockupNum ,c.expectAmount ,c.ExaDT ,c.ExaStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_stockup_application c where 1=1 and find_in_set(c.ExaStatus,'部门审批,完成')",
         "personList"=>"select c.id ,c.listNo ,c.remark ,c.isClose ,c.batchNum ,c.stockNum ,c.needsNum ,c.appUserId ,c.appUserName ,c.appDate ,c.stockupNum ,c.expectAmount ,c.ExaDT ,c.ExaStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_stockup_application c where 1=1 and c.createId='".$_SESSION['USER_ID']."'"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "listNo",
   		"sql" => " and c.listNo=# "
   	  ),
   array(
   		"name" => "listNoS",
   		"sql" => " and c.listNo like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isClose",
   		"sql" => " and c.isClose=# "
   	  ),
   array(
   		"name" => "batchNum",
   		"sql" => " and c.batchNum=# "
   	  ),
   array(
   		"name" => "batchNumS",
   		"sql" => " and c.batchNum like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "stockNum",
   		"sql" => " and c.stockNum=# "
   	  ),
   array(
   		"name" => "needsNum",
   		"sql" => " and c.needsNum=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "appDate",
   		"sql" => " and c.appDate=# "
   	  ),
   array(
   		"name" => "stockupNum",
   		"sql" => " and c.stockupNum=# "
   	  ),
   array(
   		"name" => "expectAmount",
   		"sql" => " and c.expectAmount=# "
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
   		"name" => "createNameS",
   		"sql" => " and c.createName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createTimeS",
   		"sql" => " and c.createTime >=# "
   	  ),
   array(
   		"name" => "createTimeE",
   		"sql" => " and c.createTime <=# "
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