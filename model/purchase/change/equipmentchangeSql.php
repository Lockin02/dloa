<?php
$sql_arr = array (
	"select_default" => "select " .
				"p.id,p.objCode,p.inquiryEquId,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued, " .
				"(p.amountAll-p.amountIssued) as amountUnArrival".
				",p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status,p.isChanged,p.version,p.changeType,p.planEquType " .
			"from oa_purch_apply_equ_version p where 1=1",
	//搜索版本最高值
	"select_max" => "select " .
				"p.id,p.objCode,p.inquiryEquId,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued, " .
				"(p.amountAll-p.amountIssued) as amountUnArrival".
				",p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status,p.isChanged,p.version,p.changeType,p.planEquType " .
			"from oa_purch_apply_equ_version p where p.version = " .
			"(select max(p.version) from oa_purch_apply_equ_version p )",
	//搜索指定编号下最高的版本值
	"version_max" => "select p.version from oa_purch_apply_equ_version p where p.version = " .
			"(select max(p.version) from oa_purch_apply_equ_version p )",

);

$condition_arr = array (
	//根据申请单Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=# "
	),
	//根据版本
	array(
		"name" => "version",
		"sql" => "and p.version = #"
	),

	//主业务对象编号
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	)
	//原采购清单ID
//	,array(
//		"name" => "parentId",
//		"sql" => "and p.parentId = #"
//	)
	//询价单产品ID
	,array(
		"name" => "inquiryEquId",
		"sql" => "and p.inquiryEquId = #"
	)
);
?>