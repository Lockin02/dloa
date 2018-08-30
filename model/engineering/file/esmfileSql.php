<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:24:00
 * @version 1.0
 * @description:工程附件表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.serviceId ,c.serviceNo ,c.serviceType ,c.typeId ,c.typeName ,c.originalName ,c.newName ,c.originalId ,c.inDocument ,c.tFileSize ,c.uploadPath ,c.isTemp ,c.styleThree ,c.styleTwo ,c.styleOne ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.projectId  from oa_esm_file c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "serviceId",
   		"sql" => " and c.serviceId=# "
   	  ),
   array(
   		"name" => "serviceNo",
   		"sql" => " and c.serviceNo=# "
   	  ),
   array(
   		"name" => "serviceType",
   		"sql" => " and c.serviceType=# "
   	  ),
   array(
   		"name" => "typeId",
   		"sql" => " and c.typeId=# "
   	  ),
   array(
   		"name" => "typeName",
   		"sql" => " and c.typeName=# "
   	  ),
   array(
   		"name" => "originalName",
   		"sql" => " and c.originalName=# "
   	  ),
   array(
   		"name" => "newName",
   		"sql" => " and c.newName=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "inDocument",
   		"sql" => " and c.inDocument=# "
   	  ),
   array(
   		"name" => "tFileSize",
   		"sql" => " and c.tFileSize=# "
   	  ),
   array(
   		"name" => "uploadPath",
   		"sql" => " and c.uploadPath=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "styleThree",
   		"sql" => " and c.styleThree=# "
   	  ),
   array(
   		"name" => "styleTwo",
   		"sql" => " and c.styleTwo=# "
   	  ),
   array(
   		"name" => "styleOne",
   		"sql" => " and c.styleOne=# "
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
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "serviceTypeSch",
   		"sql" => " and c.serviceType like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "typeNameSch",
   		"sql" => " and c.typeName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "originalNameSch",
   		"sql" => " and c.originalName like concat('%',#,'%') "
   	  )
)
?>