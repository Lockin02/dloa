<?php
$sql_arr = array(
	//注册库
	"select_default" => "select ".
			"c.id,c.objCode,c.systemCode,c.busiCode,c.suppName,c.regiCapital,c.legalRepre,c.foundedDate,c.employeesNum,".
			"c.companySize,c.companyNature,c.fax,c.suppSource,c.requestType,c.recomComments,c.effectDate,c.failureDate,".
			"c.license,c.advantages,c.taxCode,c.businRegistCode,c.products,c.businessCode,c.plantArea,c.address,c.status,".
			"c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime ".
			"from oa_supp_lib_temp c where 1=1 and c.delFlag=0",
	//产品
	"select_prod" => "select ".
			"a.id,a.objCode,a.systemCode,a.busiCode,a.parentCode,parentId,productId,a.productName,a.createTime,a.createName,".
			"a.createId,a.updateTime,a.updateName,a.updateId ".
			"from oa_supp_prod_temp a where 1=1",
	//联系人
	"select_cont" => "select ".
			"d.id,d.objCode,d.systemCode,d.busiCode,d.parentCode,d.parentId,d.name,d.mobile1,d.mobile2,d.remarks,d.fax".
			"d.plane,d.email,d.defaultContact,d.updateTime,d.updateName,d.updateId,d.createTime,d.createname,d.createName".
			"d.createId ".
			"from oa_supp_cont_temp d where 1=1",
	/*********************************工作流部分************************************/
	"sql_examine" => "select " .
			"w.task,p.ID as appId,c.suppName,c.busiCode,c.products,c.fax,c.address,c.ExaStatus,c.createTime,c.id " .
			"from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_supp_lib_temp c " .
			" where w.Pid =c.id",
	'myResSupp' =>'select * from oa_supp_lib_temp c' .
	' where 1=1 '
);


$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=# "
	),
	array(
		"name" => "suppName",	//供应商名称
		"sql" => "and c.suppName like CONCAT('%',#,'%') "
	),
	array(
		"name"=>"busiCode",
		"sql"=>" and c.busiCode like CONCAT('%',#,'%')"
	),
	array(
		"name" => "legalRepre",	//法人代表
		"sql" => "and c.legalRegre like CONCAT('%',#,'%')"
	),
	array(
		"name" => "companyNature",	//公司性质
		"sql" => "and c.companyNature like CONCAT('%',#,'%')"
	),
	array(
		"name" => "products",		//主要产品
		"sql" => "and c.products like CONCAT('%',#,'%')"
	),
	array(
		"name" => "status",
		"sql" => "and c.status=#"
	),
	array(
		"name" => "parentId",
		"sql" => "and a.parentId=#"
	),
	array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
	array(
		"name" => "examines",
		"sql" => " and w.examines <> 'no' "
	),
	array(
		"name" => "ajaxSuppName",//ajax唯一性验证
		"sql"=>" and c.suppName= # "
	),array(
		"name" => "suppName", //搜索字段，评估方案名称
		"sql" => " and c.suppName like CONCAT('%',#,'%') "
	),array(
		"name" => "createId", //搜索字段，评估方案名称
		"sql" => " and c.createId = # "
	),array(
		"name" => "createNameSeach", //搜索字段，评估方案名称
		"sql" => " and c.createId like CONCAT('%',#,'%') "
	),
	array(
		"name" => "suppNameSeach",
		"sql" => " and c.suppName like CONCAT('%',#,'%') "
	)
);
?>
