<?php

/**
 * @author liangjj
 * @Date 2013年9月22日 14:30:28
 * @version 1.0
 * @description:外包合同人员租赁sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id,c.mainId,c.personLevel,c.personLevelName,c.pesonName,c.supplierName,
		c.beginDate,c.endDate,c.selfPrice,c.rentalPrice,c.skillsRequired,c.interviewResults,c.interviewName,
		c.interviewId,c.remark from oa_sale_outsourcing_personrental c where 1=1",
);

$condition_arr = array (
	   	array(
	   		"name" => "id",
	   		"sql" => " and c.id=# "
	   	  ),
		array (
			"name" => "mainId",
			"sql" => " and c.mainId=# "
		),
		array (
				"name" => "personLevel",
				"sql" => " and c.personLevel=# "
		),
		array (
				"name" => "personLevelName",
				"sql" => " and c.personLevelName=# "
		),
		array (
				"name" => "pesonName",
				"sql" => " and c.pesonName=# "
		),
		array (
				"name" => "supplierName",
				"sql" => " and c.supplierName=# "
		),
		array (
				"name" => "beginDate",
				"sql" => " and c.beginDate=# "
		),
		array (
				"name" => "endDate",
				"sql" => " and c.endDate=# "
		),
		array (
				"name" => "selfPrice",
				"sql" => " and c.selfPrice=# "
		),
		array (
				"name" => "rentalPrice",
				"sql" => " and c.rentalPrice=# "
		),
		array (
				"name" => "skillsRequired",
				"sql" => " and c.skillsRequired=# "
		),
		array (
				"name" => "interviewResults",
				"sql" => " and c.interviewResults=# "
		),
		array (
				"name" => "interviewName",
				"sql" => " and c.interviewName=# "
		),
		array (
				"name" => "interviewId",
				"sql" => " and c.interviewId=# "
		),
		array (
				"name" => "remark",
				"sql" => " and c.remark=# "
		),
		
)
?>