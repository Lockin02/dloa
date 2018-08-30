<?php
/**
 * @author Show
 * @Date 2012年8月1日 16:20:04
 * @version 1.0
 * @description:员工状况(oa_esm_pesonnelinfo) sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.nameCode ,c.name ,c.nameId ,c.deptName ,c.deptId ,c.personnelId ,c.entryDate ,c.country ,c.countryId ,c.provinceId ,c.province ,c.cityId ,c.city ,c.officeId ,c.officeName ,c.personLevel ,c.personLevelId ,c.projectId ,c.projectCode ,c.projectName ,c.planEndDate ,c.taskName ,c.taskId ,c.taskPlanEnd ,c.place ,c.workStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_pesonnelinfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "nameCode",
   		"sql" => " and c.nameCode=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "nameId",
   		"sql" => " and c.nameId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "personnelId",
   		"sql" => " and c.personnelId=# "
   	  ),
   array(
   		"name" => "entryDate",
   		"sql" => " and c.entryDate=# "
   	  ),
   array(
   		"name" => "country",
   		"sql" => " and c.country=# "
   	  ),
   array(
   		"name" => "countryId",
   		"sql" => " and c.countryId=# "
   	  ),
   array(
   		"name" => "provinceId",
   		"sql" => " and c.provinceId=# "
   	  ),
   array(
   		"name" => "province",
   		"sql" => " and c.province=# "
   	  ),
   array(
   		"name" => "cityId",
   		"sql" => " and c.cityId=# "
   	  ),
   array(
   		"name" => "city",
   		"sql" => " and c.city=# "
   	  ),
   array(
   		"name" => "officeId",
   		"sql" => " and c.officeId=# "
   	  ),
   array(
   		"name" => "officeName",
   		"sql" => " and c.officeName=# "
   	  ),
   array(
   		"name" => "personLevel",
   		"sql" => " and c.personLevel=# "
   	  ),
   array(
   		"name" => "personLevelId",
   		"sql" => " and c.personLevelId=# "
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
   		"name" => "planEndDate",
   		"sql" => " and c.planEndDate=# "
   	  ),
   array(
   		"name" => "taskName",
   		"sql" => " and c.taskName=# "
   	  ),
   array(
   		"name" => "taskId",
   		"sql" => " and c.taskId=# "
   	  ),
   array(
   		"name" => "taskPlanEnd",
   		"sql" => " and c.taskPlanEnd=# "
   	  ),
   array(
   		"name" => "place",
   		"sql" => " and c.place=# "
   	  ),
   array(
   		"name" => "workStatus",
   		"sql" => " and c.workStatus=# "
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