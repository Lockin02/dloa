<?php
/**
 * @author LiuBo
 * @Date 2011年3月3日 10:40:34
 * @version 1.0
 * @description:售前线索 sql配置文件 售前线索
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.createSection,c.cluesCode ,c.cluesName ,c.createDate ,c.trackman ,c.customerId ,c.customerName ,c.customerType ,c.address ,c.customerProvince ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.status ,c.closeId ,c.closeName ,c.closeTime ,c.closeRegard ,c.customerNeed,c.areaCode,c.areaName,c.areaPrincipal,c.areaPrincipalId  from oa_sale_clues c where 1=1 ",

         "select_clues"=>"select  c.id,c.cluesCode,c.cluesName,c.customerId,c.customerName,c.customerType,c.address,c.customerProvince,c.updateTime,c.updateName,c.updateId,c.createTime,c.createName,c.createId,c.status,c.closeId,c.closeName,c.closeTime,c.closeRegard,c.customerNeed,c.areaCode,c.areaName,c.areaPrincipal,c.areaPrincipalId ,s.id as trackId,c.trackman,s.trackmanId,s.cluesId from oa_sale_clues_trackman s inner join oa_sale_clues c on s.cluesId=c.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "cluesCode",
   		"sql" => " and c.cluesCode=# "
   	  ),
   array(
   		"name" => "cluesName",
   		"sql" => " and c.cluesName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "createDate",
   		"sql" => " and c.createDate=# "
   	  ),
   array(
   		"name" => "trackman",
   		"sql" => " and c.trackman like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "customerType",
   		"sql" => " and c.customerType=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "customerProvince",
   		"sql" => " and c.customerProvince=# "
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
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "closeId",
   		"sql" => " and c.closeId=# "
   	  ),
   array(
   		"name" => "closeName",
   		"sql" => " and c.closeName=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "closeRegard",
   		"sql" => " and c.closeRegard=# "
   	  ),
   array(
   		"name" => "customerNeed",
   		"sql" => " and c.customerNeed=# "
   	  ),
   array(
   		"name" => "trackmanIdForS",
   		"sql" => " and s.trackmanId=# "
   	  )
)
?>