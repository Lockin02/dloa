<?php

/**
 * @author Show
 * @Date 2011��12��16�� ������ 11:24:28
 * @version 1.0
 * @description:���¼�¼(oa_sale_stamp) sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.contractId ,c.contractCode ,c.contractType ,c.objCode ,c.contractMoney,c.applyUserId ,c.applyUserName ,c.applyDate ,c.stampType ,c.stampUserId ,c.stampUserName ,c.stampDate ,c.remark ,c.status,c.contractName,c.signCompanyId,c.signCompanyName,c.batchNo,a.legalPersonName,a.stampCompanyId,a.stampCompany  from oa_sale_stamp c left join oa_sale_stampapply a on c.applyid = a.id where 1=1 ",
    "select_list1"=>"select * @FROM
(
SELECT
 c.id ,c.contractId ,c.contractCode ,c.contractType ,c.objCode ,c.contractMoney,c.applyUserId ,c.applyUserName ,c.applyDate ,c.stampType ,c.stampUserId ,c.stampUserName ,c.stampDate ,c.remark ,c.status,c.contractName,c.signCompanyId,c.signCompanyName,c.batchNo,a.legalPersonName,a.stampCompanyId,a.stampCompany 
 FROM
 oa_sale_stamp c
 INNER JOIN oa_sale_stampapply a ON c.applyid = a.id
 WHERE 1 = 1 and (c.applyId <> '' or c.applyId is not null)
union ALL
SELECT
	c.id ,c.contractId ,c.contractCode ,c.contractType ,c.objCode ,c.contractMoney,c.applyUserId ,c.applyUserName ,c.applyDate ,c.stampType ,c.stampUserId ,c.stampUserName ,c.stampDate ,c.remark ,c.status,c.contractName,c.signCompanyId,c.signCompanyName,c.batchNo,'' as legalPersonName,'' as stampCompanyId,'' as stampCompany 
 FROM
 oa_sale_stamp c
 WHERE 1 = 1 and (c.applyId = '' or c.applyId is null) 
)c where 1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "noId",
		"sql" => " and c.Id <>#"
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
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
		"name" => "applyUserId",
		"sql" => " and c.applyUserId=# "
	),
	array (
		"name" => "applyUserName",
		"sql" => " and c.applyUserName=# "
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
		"name" => "stampTypes",
		"sql" => " and c.stampType in(arr) "
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
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "batchNo",
		"sql" => " and c.batchNo=# "
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array (//���ݺ�ͬ�������
		"name" => "contractCodeSer",
		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
	),
	array (//��������������
		"name" => "applyUserNameSer",
		"sql" => " and c.applyUserName like CONCAT('%',#,'%') "
	),array (//��������Ĺ�˾������
        "name" => "stampCompanySearch",
        "sql" => " and c.stampCompany like CONCAT('%',#,'%') "
    ),array (//��������Ĺ�˾������
        "name" => "stampCompanySearcha",
        "sql" => " and a.stampCompany like CONCAT('%',#,'%') "
    ),array(//���ݸ��¸����˻�ȡ��Ӧ�ĸ�������
        "name" => "session_uid",
        "sql" => "AND c.stampType IN (select stampName from oa_system_stamp_config c where 1=1 and find_in_set( #,c.principalId ))"
    )
)
?>