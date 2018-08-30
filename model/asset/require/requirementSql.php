<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 15:53:55
 * @version 1.0
 * @description:资产需求申请 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.backReason,c.address,c.isSubmit,c.beginDate,c.recognizeAmount,c.isRecognize,c.id ,c.requireCode ,c.expectAmount ,c.userId ,c.userName ,c.userPhone ,c.userDeptId ,c.userDeptName ,c.applyId ,c.applyName ,c.applyDeptId ,c.applyDeptName ,c.requireType ,c.projectId ,c.projectCode ,c.returnDate ,c.applyDate ,c.useCode ,c.useName ,c.DeliveryStatus ,c.remark ,c.ExaStatus ,c.ExaDT ,c.requireInStatus ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_asset_requirement c where 1=1 ",
         "select_signview"=>"select * from oa_requirement_sign_view c where 1=1 "
);

$condition_arr = array (
   array(// 物料名称
		"name" => "productName",
		"sql" => " and  c.id in(select i.mainId from oa_asset_requireitem i where i.description like CONCAT('%',#,'%')) "
	),
   array(
   		"name" => "requireId",
   		"sql" => " and c.requireId=# "
   	  ),
   array(
   		"name" => "isSign",
   		"sql" => " and c.isSign=# "
   	  ),
   array(
   		"name" => "isSubmit",
   		"sql" => " and c.isSubmit=# "
   	  ),
   array(
   		"name" => "isRecognizeFlag",
   		"sql" => " and c.isRecognize not in(arr) "
   	  ),
   array(
   		"name" => "isRecognize",
   		"sql" => " and c.isRecognize=# "
   	  ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "requireCode",
   		"sql" => " and c.requireCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "expectAmount",
   		"sql" => " and c.expectAmount=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "userPhone",
   		"sql" => " and c.userPhone=# "
   	  ),
   array(
   		"name" => "userDeptId",
   		"sql" => " and c.userDeptId like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "userDeptName",
   		"sql" => " and c.userDeptName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyName",
   		"sql" => " and c.applyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "applyDeptId",
   		"sql" => " and c.applyDeptId=# "
   	  ),
   array(
   		"name" => "applyDeptName",
   		"sql" => " and c.applyDeptName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "requireType",
   		"sql" => " and c.requireType=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "returnDate",
   		"sql" => " and c.returnDate=# "
   	  ),
   array(
   		"name" => "applyDate",
   		"sql" => " and c.applyDate=# "
   	  ),
   array(
   		"name" => "useCode",
   		"sql" => " and c.useCode=# "
   	  ),
   array(
   		"name" => "useName",
   		"sql" => " and c.useName=# "
   	  ),
   array(
   		"name" => "DeliveryStatus",
   		"sql" => " and c.DeliveryStatus=# "
   	  ),
   array(
   		"name" => "DeliveryStatusArr",
   		"sql" => " and c.DeliveryStatus in(arr) "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
	array(
		"name" => "requireInStatus",
		"sql" => " and c.requireInStatus=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "applyManId",
   		"sql" => " and (c.createId=# or c.userId=# or c.applyId=#) "
   	  )
)
?>