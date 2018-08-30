<?php
/**
 * @author Show
 * @Date 2012年10月20日 15:27:58
 * @version 1.0
 * @description:盖章申请表(oa_sale_stampapply) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.contractId ,c.contractName ,c.contractCode ,c.contractType ,c.objCode ,
            c.signCompanyName ,c.signCompanyId ,c.contractMoney ,c.applyUserId ,c.applyUserName ,c.deptId ,
            c.deptName ,c.applyDate ,c.stampType ,c.stampIds ,c.legalPersonUsername,c.stampCompany,c.stampCompanyId, c.legalPersonName, c.stampUserId ,c.stampUserName ,c.stampDate ,c.remark ,
            c.status ,c.ExaDT ,c.ExaStatus ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName,
            c.updateTime,c.fileName ,c.stampExecution ,c.stampExecutionName,c.useMatters,c.useMattersId,c.isNeedAudit,
			c.attn,c.attnId,c.attnDept,c.attnDeptId,t.businessBelong,c.categoryId,c.printDoubleSide,c.fileNum,c.filePageNum
        from oa_sale_stampapply c left join oa_contract_contract t on t.id = c.contractId where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "signCompanyName",
		"sql" => " and c.signCompanyName=# "
	),
	array (
		"name" => "signCompanyId",
		"sql" => " and c.signCompanyId=# "
	),
	array (
		"name" => "contractMoney",
		"sql" => " and c.contractMoney=# "
	),
	array (
		"name" => "applyUserId",
		"sql" => " and c.applyUserId=# "
	),
	array (
		"name" => "applyUserName",
		"sql" => " and c.applyUserName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
	array (
		"name" => "stampType",
		"sql" => " and c.stampType=# "
	),
	array (
		"name" => "stampUserId",
		"sql" => " and c.stampUserId=# "
	),
	array (
		"name" => "stampUserName",
		"sql" => " and c.stampUserName=# "
	),
	array (
		"name" => "stampDate",
		"sql" => " and c.stampDate=# "
	),
	array (
		"name" => "categoryId",
		"sql" => " and c.categoryId=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
			"name" => "useMatters",
			"sql" => " and c.useMatters=# "
	),
	array (
			"name" => "useMattersId",
			"sql" => " and c.useMattersId=# "
	),
	array (
			"name" => "isNeedAudit",
			"sql" => " and c.isNeedAudit=# "
	),
	array (//根据合同编号搜索
			"name" => "contractCodeSer",
			"sql" => " and c.contractCode like CONCAT('%',#,'%') "
	),
	array (//根据申请人搜索
			"name" => "applyUserNameSer",
			"sql" => " and c.applyUserName like CONCAT('%',#,'%') "
	),array (//盖章申请的公司名搜索
        "name" => "stampCompanySearch",
        "sql" => " and c.stampCompany like CONCAT('%',#,'%') "
    )
)
?>