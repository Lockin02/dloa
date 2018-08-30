<?php
/**
 * @author Show
 * @Date 2012年9月7日 17:14:21
 * @version 1.0
 * @description:任职资格称谓表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.titleName ,c.careerDirection ,c.careerDirectionName ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,c.baseGradeName ,c.remark ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_baseinfo_certifytitle c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "titleNameEq",
   		"sql" => " and c.titleName=# "
   	  ),
   array(
   		"name" => "titleName",
   		"sql" => " and c.titleName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "careerDirection",
   		"sql" => " and c.careerDirection=# "
   	  ),
   array(
   		"name" => "careerDirectionName",
   		"sql" => " and c.careerDirectionName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "baseLevel",
   		"sql" => " and c.baseLevel=# "
   	  ),
   array(
   		"name" => "baseLevelName",
   		"sql" => " and c.baseLevelName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "baseGrade",
   		"sql" => " and c.baseGrade=# "
   	  ),
   array(
   		"name" => "baseGradeName",
   		"sql" => " and c.baseGradeName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   	  ),
   array(
   		"name" => "sysCompanyName",
   		"sql" => " and c.sysCompanyName=# "
   	  ),
   array(
   		"name" => "sysCompanyId",
   		"sql" => " and c.sysCompanyId=# "
   	  )
)
?>