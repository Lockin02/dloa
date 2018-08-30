<?php
$sql_arr = array (
	"select_default" => "select " .
				"p.id,p.objCode,p.inquiryEquId,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued, " .
				"(p.amountAll-p.amountIssued) as amountUnArrival".
				",p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status,p.isChanged,p.version,p.changeType,p.planEquType " .
			"from oa_purch_apply_equ_version p where 1=1",
	//�����汾���ֵ
	"select_max" => "select " .
				"p.id,p.objCode,p.inquiryEquId,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued, " .
				"(p.amountAll-p.amountIssued) as amountUnArrival".
				",p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status,p.isChanged,p.version,p.changeType,p.planEquType " .
			"from oa_purch_apply_equ_version p where p.version = " .
			"(select max(p.version) from oa_purch_apply_equ_version p )",
	//����ָ���������ߵİ汾ֵ
	"version_max" => "select p.version from oa_purch_apply_equ_version p where p.version = " .
			"(select max(p.version) from oa_purch_apply_equ_version p )",

);

$condition_arr = array (
	//�������뵥Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=# "
	),
	//���ݰ汾
	array(
		"name" => "version",
		"sql" => "and p.version = #"
	),

	//��ҵ�������
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	)
	//ԭ�ɹ��嵥ID
//	,array(
//		"name" => "parentId",
//		"sql" => "and p.parentId = #"
//	)
	//ѯ�۵���ƷID
	,array(
		"name" => "inquiryEquId",
		"sql" => "and p.inquiryEquId = #"
	)
);
?>