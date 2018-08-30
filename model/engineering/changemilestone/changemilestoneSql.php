<?php
/**
 * @author Show
 * @Date 2012年2月10日 星期五 14:05:49
 * @version 1.0
 * @description:项目里程碑变更表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.changeId ,c.milestoneId ,c.milestoneName ,c.planBeginDate ,c.planEndDate ,c.actBeginDate ,c.actEndDate ,c.projectId ,c.projectCode ,c.projectName ,c.isUsing ,c.versionNo ,c.status ,c.confirmStatus ,c.effortRate ,c.warpRate ,c.preMilestoneId ,c.preMilestoneName ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_change_milestone c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "changeId",
   		"sql" => " and c.changeId=# "
   	  ),
   array(
   		"name" => "milestoneId",
   		"sql" => " and c.milestoneId=# "
   	  ),
   array(
   		"name" => "milestoneName",
   		"sql" => " and c.milestoneName=# "
   	  ),
   array(
   		"name" => "planBeginDate",
   		"sql" => " and c.planBeginDate=# "
   	  ),
   array(
   		"name" => "planEndDate",
   		"sql" => " and c.planEndDate=# "
   	  ),
   array(
   		"name" => "actBeginDate",
   		"sql" => " and c.actBeginDate=# "
   	  ),
   array(
   		"name" => "actEndDate",
   		"sql" => " and c.actEndDate=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  ),
   array(
   		"name" => "isUsing",
   		"sql" => " and c.isUsing=# "
   	  ),
   array(
   		"name" => "versionNo",
   		"sql" => " and c.versionNo=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "confirmStatus",
   		"sql" => " and c.confirmStatus=# "
   	  ),
   array(
   		"name" => "effortRate",
   		"sql" => " and c.effortRate=# "
   	  ),
   array(
   		"name" => "warpRate",
   		"sql" => " and c.warpRate=# "
   	  ),
   array(
   		"name" => "preMilestoneId",
   		"sql" => " and c.preMilestoneId=# "
   	  ),
   array(
   		"name" => "preMilestoneName",
   		"sql" => " and c.preMilestoneName=# "
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