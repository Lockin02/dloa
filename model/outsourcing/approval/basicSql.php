<?php
/**
 * @author Administrator
 * @Date 2013年11月19日 星期二 23:55:18
 * @version 1.0
 * @description:外包立项 sql配置文件 合同状态
                             0.未提交
                             1.审批中
                             2.执行中
                             3.已关闭
                             4.变更中
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.applyId ,c.applyCode ,c.formCode ,c.projectCode ,c.projectId ,c.projectName ,c.payCondition ,c.orderMoney ,c.beginDate ,c.endDate ,c.outContractCode ,c.suppName ,c.suppId ,c.suppCode ,c.outSuppMoney ,c.outsourcing ,c.outsourcingName ,c.projectType ,c.projectTypeName ,c.saleManangerId ,c.saleManangerName ,c.projectManangerId ,c.projectManangerName ,c.grossProfit ,c.payTypeName ,c.payType ,c.taxPointCode ,c.taxPoint ,c.proName ,c.proCode ,c.address ,c.phone ,c.linkman ,c.principalName ,c.principalId ,c.deptId ,c.deptName ,c.outsourceType ,c.outsourceTypeName ,c.status ,c.ExaDT ,c.ExaStatus ,c.createId ,c.originalId ,c.changeTips ,c.changeReason ,c.remark ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.projectAddress,c.isAllAddContract from oa_outsourcing_approval c where 1=1 and c.isTemp=0 ",
         //yxcombogrid-outsourcPersonnel.js文件下拉查询
		 "select_pull"=>"select c.id ,c.applyId ,c.applyCode ,c.formCode ,c.projectCode ,c.projectId ,c.projectName ,c.payCondition ,c.orderMoney ,c.beginDate ,c.endDate ,c.outContractCode ,c.suppName ,c.suppId ,c.suppCode ,c.outSuppMoney ,c.outsourcing ,c.outsourcingName ,c.projectType ,c.projectTypeName ,c.saleManangerId ,c.saleManangerName ,c.projectManangerId ,c.projectManangerName ,c.grossProfit ,c.payTypeName ,c.payType ,c.taxPointCode ,c.taxPoint ,c.proName ,c.proCode ,c.address ,c.phone ,c.linkman ,c.principalName ,c.principalId ,c.deptId ,c.deptName ,c.outsourceType ,c.outsourceTypeName ,c.status ,c.ExaDT ,c.ExaStatus ,c.createId ,c.originalId ,c.changeTips ,c.changeReason ,c.remark ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.projectAddress,c.isAllAddContract,d.personSum from oa_outsourcing_approval c LEFT JOIN oa_outsourcing_apply d on c.applyId = d.id where 1=1 and c.isTemp=0 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyCode",
   		"sql" => " and c.applyCode  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "payCondition",
   		"sql" => " and c.payCondition=# "
   	  ),
   array(
   		"name" => "orderMoney",
   		"sql" => " and c.orderMoney=# "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
   	  ),
   array(
   		"name" => "outContractCode",
   		"sql" => " and c.outContractCode  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "outSuppMoney",
   		"sql" => " and c.outSuppMoney=# "
   	  ),
   array(
   		"name" => "outsourcing",
   		"sql" => " and c.outsourcing=# "
   	  ),
   array(
   		"name" => "outsourcingName",
   		"sql" => " and c.outsourcingName=# "
   	  ),
   array(
   		"name" => "projectType",
   		"sql" => " and c.projectType=# "
   	  ),
   array(
   		"name" => "projectTypeName",
   		"sql" => " and c.projectTypeName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "saleManangerId",
   		"sql" => " and c.saleManangerId=# "
   	  ),
   array(
   		"name" => "saleManangerName",
   		"sql" => " and c.saleManangerName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "projectManangerId",
   		"sql" => " and c.projectManangerId=# "
   	  ),
   array(
   		"name" => "projectManangerName",
   		"sql" => " and c.projectManangerName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "grossProfit",
   		"sql" => " and c.grossProfit=# "
   	  ),
   array(
   		"name" => "payTypeName",
   		"sql" => " and c.payTypeName=# "
   	  ),
   array(
   		"name" => "payType",
   		"sql" => " and c.payType=# "
   	  ),
   array(
   		"name" => "taxPointCode",
   		"sql" => " and c.taxPointCode=# "
   	  ),
   array(
   		"name" => "taxPoint",
   		"sql" => " and c.taxPoint=# "
   	  ),
   array(
   		"name" => "proName",
   		"sql" => " and c.proName=# "
   	  ),
   array(
   		"name" => "proCode",
   		"sql" => " and c.proCode=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "phone",
   		"sql" => " and c.phone=# "
   	  ),
   array(
   		"name" => "linkman",
   		"sql" => " and c.linkman=# "
   	  ),
   array(
   		"name" => "principalName",
   		"sql" => " and c.principalName=# "
   	  ),
   array(
   		"name" => "principalId",
   		"sql" => " and c.principalId=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "outsourceType",
   		"sql" => " and c.outsourceType=# "
   	  ),
   array(
   		"name" => "outsourceTypeName",
   		"sql" => " and c.outsourceTypeName=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
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
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "changeTip",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "changeReason",
   		"sql" => " and c.changeReason=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  )
)
?>