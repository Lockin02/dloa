<?php
$sql_arr = array(

	'readproInSupp' =>'select  c.id ,c.objCode ,c.systemCode ,' .
			'c.busiCode ,c.parentCode ,c.parentId ,c.productId ,' .
			'c.productName ,c.createName ,c.createId ,c.createTime ,' .
			'c.updateId ,c.updateName ,c.updateTime  from oa_supp_prod c where 1=1 '
);
$condition_arr = array (
	array(
		"name"=>"parentId",
		"sql"=>" and c.parentId = # "
	),array(
		"name" => "productName", //�����ֶΣ�������������
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	),array(
		"name" => "productNameSeach", //�����ֶΣ�������������
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	)
)
?>
